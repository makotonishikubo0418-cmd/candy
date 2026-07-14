#!/usr/bin/env python3
"""CANDY area page builder and validator.

The builder copies the official area template, applies the matching Text_area_data
file, expands or removes variable blocks, and validates the whole page bundle.
It intentionally stops on unknown input instead of inventing public information.
"""

from __future__ import annotations

import argparse
import hashlib
import html
import itertools
import json
import math
import os
import re
import shutil
import subprocess
import sys
import tempfile
import time
from collections import Counter
from dataclasses import dataclass, field
from datetime import date
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urlparse


SHOP_ALIASES = {
    "CANDY": "candy",
    "黒薔薇": "kurobara",
    "REBORN": "reborn",
    "人妻エステ": "hitozuma",
    "after5": "after5",
    "楊貴妃": "yokihi",
    "激情団地妻": "danzuma",
    "Sチャンネル": "schannel",
    "CANDY BELOVED": "beloved",
    "ラブ♡エル": "loveel",
    "ラブ♡エル霧島": "loveel_kirishima",
}
KEY_TO_NAME = {value: key for key, value in SHOP_ALIASES.items()}
PLACEHOLDERS = (
    "aaaaaaaaaaaaaaaaaaaa",
    "ここにはリンク先のタイトルを表示します。",
    "<!-- このタグを削除してここに店舗情報を設置 -->",
    'href="#"',
    "<改行>",
)
SEPARATOR_RE = re.compile(r"^(?:-{10,}|━{10,})\s*$")
SCENE_RE = re.compile(r"^scene（h2(?: / [^)]+)?）\s*$")


class AreaToolError(RuntimeError):
    pass


@dataclass
class Place:
    title: str
    address: str
    telephone: str | None
    url: str


@dataclass
class ShopRequest:
    name: str
    key: str
    time_text: str | None
    fee_text: str | None
    source: str


@dataclass
class ShopResolved:
    name: str
    key: str
    time_text: str
    fee_text: str
    source: str
    reference: str | None = None
    distance_km: float | None = None


@dataclass
class ArticleScene:
    heading: str
    subtitle: str
    description: str


@dataclass
class BasicInfo:
    heading: str
    map_title: str
    map_src: str
    population: str
    area: str
    established: str
    description: str


@dataclass
class AreaData:
    input_path: Path
    region: str
    slug: str
    title: str
    meta_description: str
    canonical: str
    og_image: str
    image1: str
    image2: str
    page_title: str
    subtitle_h1: str
    description_h1: str
    shop_heading: str
    shop_description: str
    shops: list[ShopRequest]
    articles: list[ArticleScene]
    basic: BasicInfo
    hotel_heading: str | None
    hotels: list[Place]
    spot_heading: str | None
    spots: list[Place]
    warnings: list[str] = field(default_factory=list)


@dataclass
class ShopTemplate:
    key: str
    name: str
    block: str
    telephone: str
    url: str
    description: str
    default_time: str
    default_fee: str


def repo_root() -> Path:
    return Path(__file__).resolve().parents[3]


def read_utf8(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8-sig").replace("\r\n", "\n")
    except UnicodeDecodeError as exc:
        raise AreaToolError(f"UTF-8で読めません: {path}") from exc


def is_separator(value: str) -> bool:
    return bool(SEPARATOR_RE.fullmatch(value.strip()))


def next_nonblank(lines: list[str], start: int, end: int | None = None) -> tuple[int, str]:
    stop = len(lines) if end is None else end
    for index in range(start, stop):
        if lines[index].strip():
            return index, lines[index].strip()
    raise AreaToolError("見出し直後の値がありません")


def labeled_value(
    lines: list[str],
    label: str,
    start: int = 0,
    end: int | None = None,
    stop_labels: tuple[str, ...] = (),
) -> str:
    stop = len(lines) if end is None else end
    position = next((i for i in range(start, stop) if lines[i].strip() == label), None)
    if position is None:
        return ""
    values: list[str] = []
    for index in range(position + 1, stop):
        value = lines[index]
        stripped = value.strip()
        if is_separator(stripped) or stripped in stop_labels:
            break
        values.append(value.rstrip())
    return "\n".join(values).strip()


def value_after_prefix(value: str, prefix: str) -> str:
    match = re.match(rf"^{re.escape(prefix)}\s*:\s*(.+)$", value.strip())
    return match.group(1).strip() if match else value.strip()


def split_scenes(lines: list[str]) -> list[list[str]]:
    starts = [index for index, value in enumerate(lines) if SCENE_RE.fullmatch(value.strip())]
    if not starts:
        raise AreaToolError("scene（h2）がありません")
    result: list[list[str]] = []
    for offset, start in enumerate(starts):
        end = starts[offset + 1] if offset + 1 < len(starts) else len(lines)
        result.append(lines[start:end])
    return result


def parse_places(section: list[str]) -> list[Place]:
    subtitle_positions = [i for i, value in enumerate(section) if value.strip() == "subtitle_"]
    places: list[Place] = []
    for offset, position in enumerate(subtitle_positions):
        end = subtitle_positions[offset + 1] if offset + 1 < len(subtitle_positions) else len(section)
        description_position = next(
            (i for i in range(position + 1, end) if section[i].strip() == "description_"), None
        )
        if description_position is None:
            raise AreaToolError("施設のdescription_がありません")
        title = " ".join(value.strip() for value in section[position + 1 : description_position] if value.strip())
        raw_values: list[str] = []
        for value in section[description_position + 1 : end]:
            if is_separator(value):
                break
            raw_values.extend(part.strip() for part in value.replace("<改行>", "\n").splitlines())
        telephone = None
        url = ""
        address_parts: list[str] = []
        for value in raw_values:
            if not value:
                continue
            if value.startswith("TEL "):
                telephone = value[4:].strip()
            elif value.startswith("URL "):
                url = value[4:].strip()
            else:
                address_parts.append(value)
        if not title or not address_parts or not url:
            raise AreaToolError(f"施設情報不足: {title or '(名称なし)'}")
        places.append(Place(title, " ".join(address_parts), telephone, url))
    return places


def parse_shop_requests(section: list[str]) -> list[ShopRequest]:
    requests: list[ShopRequest] = []
    for value in section:
        stripped = value.strip()
        if not stripped.startswith("・"):
            continue
        match = re.fullmatch(r"・(.+?)(?:（(.*)）)?", stripped)
        if not match:
            raise AreaToolError(f"店舗指定を解析できません: {stripped}")
        name = match.group(1).strip()
        note = (match.group(2) or "").strip()
        key = SHOP_ALIASES.get(name)
        if not key:
            raise AreaToolError(f"template_shop.htmlにない店舗名です: {name}")
        if "内容は同じ" in note:
            requests.append(ShopRequest(name, key, None, None, "text-template-default"))
            continue
        values = re.search(
            r"移動時間\s*[:：]\s*(.+?)\s*/\s*交通費\s*[:：]\s*(.+)$", note
        )
        if values:
            requests.append(
                ShopRequest(name, key, values.group(1).strip(), values.group(2).strip(), "text")
            )
        else:
            requests.append(ShopRequest(name, key, None, None, "missing"))
    return requests


def parse_area_text(path: Path) -> AreaData:
    text = read_utf8(path)
    lines = text.splitlines()
    scene_start = next((i for i, value in enumerate(lines) if SCENE_RE.fullmatch(value.strip())), len(lines))

    title = labeled_value(lines, "title", 0, scene_start)
    meta_description = labeled_value(lines, "description", 0, scene_start)
    canonical = labeled_value(lines, "canonical", 0, scene_start)
    og_image = labeled_value(lines, "image", 0, scene_start)
    image1 = value_after_prefix(labeled_value(lines, "img_1", 0, scene_start), "src")
    page_title = labeled_value(lines, "page_title_h1 / パンくずリスト", 0, scene_start, stop_labels=("subtitle_h1", "description_h1"))
    subtitle_h1 = labeled_value(lines, "subtitle_h1", 0, scene_start, stop_labels=("description_h1",))
    description_h1 = labeled_value(lines, "description_h1", 0, scene_start)
    image2 = value_after_prefix(labeled_value(lines, "img_2"), "src")

    canonical_path = urlparse(canonical).path
    slug_match = re.fullmatch(r"/kagoshima-deliveryhealth-area-([a-z0-9-]+)\.php", canonical_path)
    region_match = re.match(r"鹿児島市(.+?)で呼べる", page_title) or re.match(r"鹿児島市(.+?)で呼べる", title)
    errors: list[str] = []
    required = {
        "title": title,
        "description": meta_description,
        "canonical": canonical,
        "image": og_image,
        "img_1": image1,
        "img_2": image2,
        "page_title_h1": page_title,
        "subtitle_h1": subtitle_h1,
        "description_h1": description_h1,
    }
    errors.extend(name for name, value in required.items() if not value)
    if not slug_match:
        errors.append("canonical slug")
    if not region_match:
        errors.append("地域名")
    if errors:
        raise AreaToolError("必須項目不足: " + ", ".join(errors))

    shops: list[ShopRequest] = []
    shop_heading = ""
    shop_description = ""
    articles: list[ArticleScene] = []
    basic: BasicInfo | None = None
    hotel_heading: str | None = None
    hotels: list[Place] = []
    spot_heading: str | None = None
    spots: list[Place] = []

    for section in split_scenes(lines):
        _, heading = next_nonblank(section, 1)
        heading = re.sub(r'^id="scene\d+"\s*:\s*', "", heading)
        if "人気デリヘル店" in section[0] or "人気デリヘル店" in heading:
            shop_heading = heading
            shops = parse_shop_requests(section)
            shop_description = labeled_value(section, "description_")
        elif "基本情報" in heading:
            iframe = labeled_value(section, "【1】地図URL", stop_labels=("【2】地図タイトル",))
            map_src_match = re.search(r'\bsrc="([^"]+)"', iframe)
            map_title = labeled_value(
                section,
                "【2】地図タイトル",
                stop_labels=("人口", "面積", "設置年月日", "description_"),
            )
            basic = BasicInfo(
                heading=heading,
                map_title=map_title,
                map_src=map_src_match.group(1) if map_src_match else "",
                population=labeled_value(section, "人口", stop_labels=("面積", "設置年月日", "description_")),
                area=labeled_value(section, "面積", stop_labels=("設置年月日", "description_")),
                established=labeled_value(section, "設置年月日", stop_labels=("description_",)),
                description=labeled_value(section, "description_"),
            )
        elif "ホテル・宿泊施設情報" in heading:
            hotel_heading = heading
            hotels = parse_places(section)
        elif "待ち合わせ・周辺スポット" in heading:
            spot_heading = heading
            spots = parse_places(section)
        else:
            articles.append(
                ArticleScene(
                    heading=heading,
                    subtitle=labeled_value(section, "subtitle_", stop_labels=("description_",)),
                    description=labeled_value(section, "description_"),
                )
            )

    if not shop_heading or not shop_description:
        raise AreaToolError("人気デリヘル店sceneの情報が不足しています")
    if not articles or any(not item.subtitle or not item.description for item in articles):
        raise AreaToolError("通常記事sceneの情報が不足しています")
    if basic is None or any(
        not value
        for value in (
            basic.map_title,
            basic.map_src,
            basic.population,
            basic.area,
            basic.established,
            basic.description,
        )
    ):
        raise AreaToolError("基本情報sceneの情報が不足しています")

    return AreaData(
        input_path=path,
        region=region_match.group(1),
        slug=slug_match.group(1),
        title=title,
        meta_description=meta_description,
        canonical=canonical,
        og_image=og_image,
        image1=image1,
        image2=image2,
        page_title=page_title,
        subtitle_h1=subtitle_h1,
        description_h1=description_h1,
        shop_heading=shop_heading,
        shop_description=shop_description,
        shops=shops,
        articles=articles,
        basic=basic,
        hotel_heading=hotel_heading,
        hotels=hotels,
        spot_heading=spot_heading,
        spots=spots,
    )


def strip_tags(value: str) -> str:
    return html.unescape(re.sub(r"<[^>]+>", "", value)).strip()


def table_value(block: str, label: str) -> str:
    match = re.search(
        rf"<td>{re.escape(label)}</td>\s*<td>(?:<span[^>]*>)?(.*?)(?:</span>)?</td>",
        block,
        re.S,
    )
    return strip_tags(match.group(1)) if match else ""


def load_shop_templates(path: Path) -> dict[str, ShopTemplate]:
    source = read_utf8(path)
    templates: dict[str, ShopTemplate] = {}
    for key in KEY_TO_NAME:
        match = re.search(
            rf"(?ms)^[ \t]*<!-- {re.escape(key)} -->\s*\n(?P<block>[ \t]*<li\b.*?^[ \t]*</li>)",
            source,
        )
        if not match:
            raise AreaToolError(f"template_shop.htmlの店舗ブロックがありません: {key}")
        block = f"\t\t\t\t\t<!-- {key} -->\n{match.group('block')}"
        heading = re.search(r"<h3[^>]*>(.*?)</h3>", block, re.S)
        links = re.findall(r'<a href="([^"]+)"', block)
        strong = re.search(r"<strong>(.*?)</strong>", block, re.S)
        templates[key] = ShopTemplate(
            key=key,
            name=KEY_TO_NAME[key],
            block=block,
            telephone=table_value(block, "電話番号"),
            url=links[-1] if links else "",
            description=strip_tags(strong.group(1)) if strong else strip_tags(heading.group(1)) if heading else "",
            default_time=table_value(block, "移動時間"),
            default_fee=table_value(block, "交通費"),
        )
    return templates


def coordinates(map_src: str) -> tuple[float, float] | None:
    longitude = re.search(r"!2d(-?\d+(?:\.\d+)?)", map_src)
    latitude = re.search(r"!3d(-?\d+(?:\.\d+)?)", map_src)
    if not longitude or not latitude:
        return None
    return float(latitude.group(1)), float(longitude.group(1))


def haversine_km(left: tuple[float, float], right: tuple[float, float]) -> float:
    lat1, lon1 = map(math.radians, left)
    lat2, lon2 = map(math.radians, right)
    dlat = lat2 - lat1
    dlon = lon2 - lon1
    value = math.sin(dlat / 2) ** 2 + math.cos(lat1) * math.cos(lat2) * math.sin(dlon / 2) ** 2
    return 6371.0088 * 2 * math.asin(math.sqrt(value))


def extract_existing_shop(block: str, key: str) -> tuple[str, str] | None:
    match = re.search(
        rf"(?ms)^[ \t]*<!-- {re.escape(key)} -->\s*\n(?P<block>[ \t]*<li\b.*?^[ \t]*</li>)",
        block,
    )
    if not match:
        return None
    time_text = table_value(match.group("block"), "移動時間")
    fee_text = table_value(match.group("block"), "交通費")
    return (time_text, fee_text) if time_text and fee_text else None


def nearest_travel(
    hp_root: Path,
    target_slug: str,
    target_coords: tuple[float, float],
    key: str,
) -> tuple[str, str, str, float] | None:
    candidates: list[tuple[float, str, str, str]] = []
    for path in (hp_root / "source").glob("kagoshima-deliveryhealth-area-*.html"):
        if path.stem.endswith(target_slug):
            continue
        source = read_utf8(path)
        map_match = re.search(r'<iframe[^>]+class="map-iframe[^>]+src="([^"]+)"', source)
        if not map_match:
            map_match = re.search(r'<iframe[^>]+src="([^"]+)"[^>]+class="map-iframe', source)
        existing_coords = coordinates(map_match.group(1)) if map_match else None
        travel = extract_existing_shop(source, key)
        if not existing_coords or not travel:
            continue
        distance = haversine_km(target_coords, existing_coords)
        candidates.append((distance, path.name, travel[0], travel[1]))
    if not candidates:
        return None
    distance, filename, time_text, fee_text = min(candidates, key=lambda item: item[0])
    return time_text, fee_text, filename, distance


def suspicious_fee(value: str | None) -> bool:
    if not value:
        return True
    numbers = [int(item.replace(",", "")) for item in re.findall(r"\d[\d,]*", value)]
    return bool(numbers and max(numbers) > 10000)


def choose_store_combination(hp_root: Path, slug: str) -> list[ShopRequest]:
    counts: Counter[tuple[str, ...]] = Counter()
    for path in (hp_root / "source").glob("kagoshima-deliveryhealth-area-*.html"):
        keys = tuple(
            sorted(
                {
                    item
                    for item in re.findall(r"<!-- ([a-z0-9_]+) -->", read_utf8(path))
                    if item in KEY_TO_NAME
                }
            )
        )
        if keys:
            counts[keys] += 1
    combinations = [
        ("candy",) + item
        for item in itertools.combinations((k for k in KEY_TO_NAME if k != "candy"), 3)
    ]
    ranked = sorted(
        combinations,
        key=lambda item: (
            counts[tuple(sorted(item))],
            hashlib.sha256((slug + "|" + "|".join(item)).encode("utf-8")).hexdigest(),
        ),
    )
    selected = ranked[0]
    return [ShopRequest(KEY_TO_NAME[key], key, None, None, "auto-low-frequency") for key in selected]


def resolve_shops(data: AreaData, hp_root: Path, templates: dict[str, ShopTemplate]) -> list[ShopResolved]:
    requests = data.shops or choose_store_combination(hp_root, data.slug)
    if len({item.key for item in requests}) != len(requests):
        raise AreaToolError("店舗指定が重複しています")
    target_coords = coordinates(data.basic.map_src)
    resolved: list[ShopResolved] = []
    for request in requests:
        template = templates[request.key]
        if request.source == "text-template-default":
            resolved.append(
                ShopResolved(
                    request.name,
                    request.key,
                    template.default_time,
                    template.default_fee,
                    request.source,
                )
            )
            continue
        if request.time_text and request.fee_text and not suspicious_fee(request.fee_text):
            resolved.append(
                ShopResolved(
                    request.name,
                    request.key,
                    request.time_text,
                    request.fee_text,
                    request.source,
                )
            )
            continue
        if not target_coords:
            raise AreaToolError(f"{request.name}の交通費推定に必要な地図座標がありません")
        nearest = nearest_travel(hp_root, data.slug, target_coords, request.key)
        if not nearest:
            raise AreaToolError(f"{request.name}の近隣交通費データがありません")
        reason = "nearest-invalid-text" if request.fee_text else "nearest-missing-text"
        resolved.append(
            ShopResolved(
                request.name,
                request.key,
                nearest[0],
                nearest[1],
                reason,
                nearest[2],
                nearest[3],
            )
        )
    return resolved


def htext(value: str) -> str:
    return "<br>".join(html.escape(item.strip()) for item in value.replace("<改行>", "\n").splitlines() if item.strip())


def hattr(value: str) -> str:
    return html.escape(value, quote=True)


def replace_exact(source: str, pattern: str, replacement: str, label: str, flags: int = 0) -> str:
    result, count = re.subn(pattern, lambda match: match.expand(replacement), source, count=1, flags=flags)
    if count != 1:
        raise AreaToolError(f"テンプレート置換位置が一意ではありません: {label} ({count})")
    return result


def replace_table_value(block: str, label: str, value: str) -> str:
    pattern = rf"(<td>{re.escape(label)}</td>\s*<td>)(.*?)(</td>)"
    result, count = re.subn(pattern, rf"\g<1>{html.escape(value)}\g<3>", block, count=1, flags=re.S)
    if count != 1:
        raise AreaToolError(f"店舗ブロックの{label}を置換できません")
    return result


def render_shop_blocks(
    resolved: list[ShopResolved], templates: dict[str, ShopTemplate]
) -> str:
    blocks: list[str] = []
    for index, item in enumerate(resolved):
        block = templates[item.key].block
        block = replace_table_value(block, "移動時間", item.time_text)
        block = replace_table_value(block, "交通費", item.fee_text)
        block = re.sub(
            r'class="campaign-flex bg_f(?: lmt_20)?"',
            'class="campaign-flex bg_f"' if index == 0 else 'class="campaign-flex bg_f lmt_20"',
            block,
            count=1,
        )
        blocks.append(block)
    return "\n".join(blocks)


def render_article(scene: ArticleScene, number: int, first: bool) -> str:
    heading_class = "lp_40_0 fs_xxl fc_p" if first else "lp_38_0 bd_t fs_xxl fc_p"
    return (
        f'\t\t\t\t<h2 class="{heading_class}" id="scene{number}">{htext(scene.heading)}</h2>\n'
        f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_{number}">{htext(scene.subtitle)}</div>\n'
        f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_{number}">{htext(scene.description)}</div>\n\n'
    )


def render_basic(basic: BasicInfo, number: int) -> str:
    return (
        f'\t\t\t\t<h2 class="lp_38_0 bd_t fs_xxl fc_p" id="scene{number}">{htext(basic.heading)}</h2>\n'
        f'\t\t\t\t<iframe title="{hattr(basic.map_title)}" src="{hattr(basic.map_src)}" class="map-iframe lmb_25" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>\n'
        '\t\t\t\t<table class="info-table1">\n\t\t\t\t\t<tbody>\n'
        f'\t\t\t\t\t\t<tr><td class="lp_15 fs_sm2">人口</td><td class="lp_15 fs_sm2">{htext(basic.population)}</td></tr>\n'
        f'\t\t\t\t\t\t<tr><td class="lp_15 fs_sm2">面積</td><td class="lp_15 fs_sm2">{htext(basic.area)}</td></tr>\n'
        f'\t\t\t\t\t\t<tr><td class="lp_15 fs_sm2">設置年月日</td><td class="lp_15 fs_sm2">{htext(basic.established)}</td></tr>\n'
        '\t\t\t\t\t</tbody>\n\t\t\t\t</table>\n'
        f'\t\t\t\t<div class="lm_40_0_75 fs_md3" id="description_{number}">{htext(basic.description)}</div>\n\n'
    )


def render_places(heading: str, places: list[Place], number: int, kind: str) -> str:
    lines = [
        f'\t\t\t\t<h2 class="lp_38_0 bd_t fs_xxl fc_p" id="scene{number}">{htext(heading)}</h2>',
        "",
    ]
    for index, place in enumerate(places, 1):
        border = "bd_tb" if index == len(places) else "bd_t"
        lines.extend(
            [
                f'\t\t\t\t<div class="faq-item {border}">',
                f'\t\t\t\t<div class="faq-question fs_md2" id="subtitle_{number}_{index}">{htext(place.title)}</div>',
                f'\t\t\t\t<div class="faq-answer fs_md2" id="description_{number}_{index}">{htext(place.address)}<br>',
            ]
        )
        if place.telephone:
            lines.append(f"\t\t\t\t\tTEL {htext(place.telephone)}")
        lines.extend(
            [
                f'\t\t\t\t\t<div class="lm_25_0_35"><a href="{hattr(place.url)}" class="bt-pk-m">詳細はコチラ</a></div>',
                "\t\t\t\t</div>",
                "\t\t\t\t</div>",
                "",
            ]
        )
    if kind == "hotel":
        lines.append(
            '\t\t\t\t<div class="lm_40_0_75 fs_md3">デリヘルのご利用可否や施設情報詳細については、直接お問い合わせください。</div>'
        )
    else:
        lines.append(
            f'\t\t\t\t<div class="lm_40_0_75 fs_md3" id="description_{number}_{len(places) + 1}">待ち合わせ・周辺スポット情報は変更されている場合がございますので、詳細は直接お問い合わせください。</div>'
        )
    lines.append("")
    return "\n".join(lines)


def render_json_ld(
    data: AreaData, resolved: list[ShopResolved], templates: dict[str, ShopTemplate]
) -> tuple[str, str]:
    breadcrumb = {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "TOP", "item": "https://www.55810.com/"},
            {
                "@type": "ListItem",
                "position": 2,
                "name": "対応エリア一覧",
                "item": "https://www.55810.com/area.php",
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": data.page_title,
                "item": data.canonical,
            },
        ],
    }
    item_list = {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": f"{data.shop_heading}人気デリヘル店一覧" if "人気デリヘル店" not in data.shop_heading else data.shop_heading.replace("「人気デリヘル店」情報", "人気デリヘル店一覧"),
        "itemListOrder": "https://schema.org/ItemListUnordered",
        "numberOfItems": len(resolved),
        "itemListElement": [],
    }
    for position, item in enumerate(resolved, 1):
        template = templates[item.key]
        item_list["itemListElement"].append(
            {
                "@type": "ListItem",
                "position": position,
                "item": {
                    "@type": "Organization",
                    "name": item.name,
                    "telephone": template.telephone,
                    "url": template.url,
                    "description": template.description,
                },
            }
        )
    return (
        json.dumps(breadcrumb, ensure_ascii=False, indent=2),
        json.dumps(item_list, ensure_ascii=False, indent=2),
    )


def render_source(
    data: AreaData,
    resolved: list[ShopResolved],
    templates: dict[str, ShopTemplate],
    template_path: Path,
) -> str:
    source = read_utf8(template_path)
    source = replace_exact(source, r'(<meta name="description" content=")[^"]*(">)', rf"\g<1>{hattr(data.meta_description)}\g<2>", "meta description")
    source = replace_exact(source, r"<title>.*?</title>", f"<title>{htext(data.title)}</title>", "title")
    source = replace_exact(source, r'(<link rel="canonical" href=")[^"]*(">)', rf"\g<1>{hattr(data.canonical)}\g<2>", "canonical")
    source = replace_exact(source, r'(<meta property="og:title" content=")[^"]*(">)', rf"\g<1>{hattr(data.title)}\g<2>", "og:title")
    source = replace_exact(source, r'(<meta property="og:url" content=")[^"]*(">)', rf"\g<1>{hattr(data.canonical)}\g<2>", "og:url")
    source = replace_exact(source, r'(<meta property="og:image" content=")[^"]*(">)', rf"\g<1>{hattr(data.og_image)}\g<2>", "og:image")
    source = replace_exact(source, r'(<meta property="og:description" content=")[^"]*(">)', rf"\g<1>{hattr(data.meta_description)}\g<2>", "og:description")

    json_values = iter(render_json_ld(data, resolved, templates))
    source, json_count = re.subn(
        r'(?s)<script type="application/ld\+json">.*?</script>',
        lambda _: '<script type="application/ld+json">\n' + next(json_values) + "\n</script>",
        source,
        count=2,
    )
    if json_count != 2:
        raise AreaToolError(f"JSON-LDテンプレート数が2ではありません: {json_count}")

    source = replace_exact(source, r"<li><span>.*?</span></li>", f"<li><span>{htext(data.page_title)}</span></li>", "breadcrumb")
    source = replace_exact(source, r'<img src="[^"]+" class="img_1 nolazy" alt="[^"]+">', f'<img src="{hattr(data.image1)}" class="img_1 nolazy" alt="{hattr(data.page_title)}">', "image1")
    h1_suffix = "呼べるデリヘル"
    if data.page_title.endswith(h1_suffix):
        h1_prefix = data.page_title[: -len(h1_suffix)]
        h1_value = f'{htext(h1_prefix)}<span class="fc_p"><br class="spOnly">{h1_suffix}</span>'
    else:
        h1_value = htext(data.page_title)
    source = replace_exact(source, r'(<h1[^>]+id="page_title_h1">).*?(</h1>)', rf"\g<1>{h1_value}\g<2>", "h1", re.S)
    source = replace_exact(source, r'(<div[^>]+id="subtitle_h1">).*?(</div>)', rf"\g<1>{htext(data.subtitle_h1)}\g<2>", "subtitle_h1", re.S)
    source = replace_exact(source, r'(<div[^>]+id="description_h1">).*?(</div>)', rf"\g<1>{htext(data.description_h1)}\g<2>", "description_h1", re.S)
    if "人気デリヘル店" in data.shop_heading:
        shop_label = data.shop_heading.replace("「人気デリヘル店」情報", "人気デリヘル店情報")
        shop_h2 = htext(data.shop_heading).replace("「人気デリヘル店」情報", '<span class="fc_p"><br class="spOnly">「人気デリヘル店」情報</span>')
    else:
        shop_label = data.shop_heading + "人気デリヘル店情報"
        shop_h2 = htext(data.shop_heading) + '<span class="fc_p"><br class="spOnly">「人気デリヘル店」情報</span>'
    source = replace_exact(source, r'(<div class="titleimg_1[^>]+aria-label=")[^"]*("></div>)', rf"\g<1>{hattr(shop_label)}\g<2>", "shop aria")
    source = replace_exact(source, r'(<h2[^>]+id="scene1">).*?(</h2>)', rf"\g<1>{shop_h2}\g<2>", "shop heading", re.S)
    source = replace_exact(source, r'(<ul class="campaign-list" role="list">).*?(</ul>)', rf"\g<1>\n{render_shop_blocks(resolved, templates)}\n\t\t\t\t\g<2>", "shop blocks", re.S)
    source = replace_exact(source, r'(<div[^>]+id="description_1">).*?(</div>)', rf"\g<1>{htext(data.shop_description)}\g<2>", "shop description", re.S)
    source = replace_exact(source, r'<img src="[^"]+" class="img_1 nolazy" loading="lazy" alt="[^"]+">', f'<img src="{hattr(data.image2)}" class="img_1 nolazy" loading="lazy" alt="鹿児島「{hattr(data.region)}」について">', "image2")

    number = 2
    dynamic_parts: list[str] = []
    for index, article in enumerate(data.articles):
        dynamic_parts.append(render_article(article, number, index == 0))
        number += 1
    dynamic_parts.append(render_basic(data.basic, number))
    number += 1
    if data.hotels:
        dynamic_parts.append(render_places(data.hotel_heading or f"鹿児島市「{data.region}」近辺にあるホテル・宿泊施設情報", data.hotels, number, "hotel"))
        number += 1
    if data.spots:
        dynamic_parts.append(render_places(data.spot_heading or f"鹿児島市「{data.region}」待ち合わせ・周辺スポット", data.spots, number, "spot"))

    source = replace_exact(
        source,
        r'(?s)[ \t]*<h2[^>]+id="scene2">.*?(?=[ \t]*<div class="lmt_20 lp_40 bd">)',
        "\n" + "".join(dynamic_parts),
        "dynamic scenes",
    )
    source = replace_exact(
        source,
        r'(?s)[ \t]*<div class="lmt_20 lp_40 bd">.*?(?=[ \t]*<div class="lm_40_0_75 center" id="button_3">)',
        "\n",
        "related placeholder",
    )
    source = "\n".join(line.rstrip() for line in source.splitlines())
    return source.rstrip() + "\n"


class BalanceParser(HTMLParser):
    void = {"area", "base", "br", "col", "embed", "hr", "img", "input", "link", "meta", "param", "source", "track", "wbr"}

    def __init__(self) -> None:
        super().__init__(convert_charrefs=True)
        self.stack: list[str] = []
        self.errors: list[str] = []

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        if tag not in self.void:
            self.stack.append(tag)

    def handle_endtag(self, tag: str) -> None:
        if not self.stack or self.stack[-1] != tag:
            self.errors.append(f"終了タグ不整合: {tag}")
            return
        self.stack.pop()


def validate_rendered(
    data: AreaData,
    resolved: list[ShopResolved],
    source: str,
    hp_root: Path,
) -> list[str]:
    errors: list[str] = []
    for placeholder in PLACEHOLDERS:
        if placeholder in source:
            errors.append(f"placeholder残存: {placeholder}")
    ids = re.findall(r'\bid="([^"]+)"', source)
    duplicates = [value for value, count in Counter(ids).items() if count > 1]
    if duplicates:
        errors.append("ID重複: " + ", ".join(duplicates))
    scenes = [int(value) for value in re.findall(r'\bid="scene(\d+)"', source)]
    if scenes != list(range(1, len(scenes) + 1)):
        errors.append(f"scene連番不整合: {scenes}")
    json_blocks = re.findall(r'(?s)<script type="application/ld\+json">\s*(.*?)\s*</script>', source)
    if len(json_blocks) != 2:
        errors.append(f"JSON-LD件数: {len(json_blocks)}")
    else:
        for index, value in enumerate(json_blocks, 1):
            try:
                json.loads(value)
            except json.JSONDecodeError as exc:
                errors.append(f"JSON-LD {index}: {exc}")
    actual_keys = [item for item in re.findall(r"<!-- ([a-z0-9_]+) -->", source) if item in KEY_TO_NAME]
    expected_keys = [item.key for item in resolved]
    scene1_match = re.search(r'<h2[^>]+id="scene1">(.*?)</h2>', source, re.S)
    if not scene1_match or "「人気デリヘル店」情報" not in strip_tags(scene1_match.group(1)):
        errors.append("人気デリヘル店見出し不整合")
    if actual_keys != expected_keys:
        errors.append(f"店舗順不整合: expected={expected_keys} actual={actual_keys}")
    for item in resolved:
        block = extract_existing_shop(source, item.key)
        if not block or block != (item.time_text, item.fee_text):
            errors.append(f"店舗交通費不整合: {item.name}")
    for relative in (data.image1, data.image2):
        image_path = hp_root / relative.removeprefix("./")
        if not image_path.is_file():
            errors.append(f"画像なし: {relative}")
    if f'<link rel="canonical" href="{hattr(data.canonical)}">' not in source:
        errors.append("canonical不整合")
    if f"<title>{htext(data.title)}</title>" not in source:
        errors.append("title不整合")
    parser = BalanceParser()
    parser.feed(source)
    if parser.errors or parser.stack:
        errors.extend(parser.errors[:5])
        if parser.stack:
            errors.append("未終了タグ: " + ", ".join(parser.stack[-5:]))
    return errors


def public_php_content() -> str:
    return """<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


?>
"""


def dataset_content() -> str:
    return """<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
"""


def update_dataset_base(source: str, slug: str) -> str:
    html_name = f"kagoshima-deliveryhealth-area-{slug}.html"
    php_name = f"kagoshima-deliveryhealth-area-{slug}.php"
    dataset_name = f"dataset_kagoshima-deliveryhealth-area-{slug}.php"
    case_block = f"\tcase '{html_name}':\n\t\tinclude(INCLUDE_DIR . '{dataset_name}');\n\t\tbreak;\n\n"
    case_count = source.count(f"case '{html_name}':")
    if case_count > 1:
        raise AreaToolError(f"dataset_base case重複: {case_count}")
    if case_count == 0:
        source = replace_exact(source, r"(?m)^\tcase 'contact\.html':", case_block + "\tcase 'contact.html':", "dataset case insertion")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    conversion_count = source.count(conversion)
    if conversion_count > 1:
        raise AreaToolError(f"dataset_baseリンク変換重複: {conversion_count}")
    if conversion_count == 0:
        source = replace_exact(source, r"(?m)^\$source = str_replace\('contact\.html'", conversion + "\n$source = str_replace('contact.html'", "dataset conversion insertion")
    return source


def update_sitemap(source: str, canonical: str) -> str:
    count = source.count(f"<loc>{canonical}</loc>")
    if count > 1:
        raise AreaToolError(f"sitemap URL重複: {count}")
    if count == 1:
        return source
    entry = (
        "  <url>\n"
        f"    <loc>{html.escape(canonical)}</loc>\n"
        f"    <lastmod>{date.today().isoformat()}</lastmod>\n"
        "    <changefreq>weekly</changefreq>\n"
        "    <priority>0.7</priority>\n"
        "  </url>\n\n"
    )
    return replace_exact(source, r"(?m)^</urlset>", entry + "</urlset>", "sitemap insertion")


def page_record(data: AreaData, resolved: list[ShopResolved]) -> str:
    lines = [
        "",
        f"### 自動生成記録 {date.today().isoformat()} {data.region} / {data.slug}",
        f"<!-- CANDY_AREA_TOOL_RECORD:{data.slug} -->",
        f"- 入力: `{data.input_path.relative_to(repo_root()).as_posix()}`",
        f"- 可変件数: 店舗{len(resolved)}、通常記事{len(data.articles)}、ホテル{len(data.hotels)}、周辺スポット{len(data.spots)}。",
    ]
    for item in resolved:
        evidence = f"、参照 `{item.reference}`、直線距離約{item.distance_km:.2f}km" if item.reference and item.distance_km is not None else ""
        lines.append(f"- {item.name}: {item.time_text} / {item.fee_text} / 根拠 `{item.source}`{evidence}。")
    lines.extend(
        [
            "- 専用ツールでテンプレート複製、可変ブロック、JSON-LD、dataset_base、sitemapを同期。",
            "- 未知店舗、必須情報不足、slug競合、画像不足、共有登録重複は自動停止する。",
        ]
    )
    return "\n".join(lines) + "\n"


def update_page_notes(source: str, data: AreaData, resolved: list[ShopResolved]) -> str:
    marker = f"<!-- CANDY_AREA_TOOL_RECORD:{data.slug} -->"
    if marker in source:
        return source
    return source.rstrip() + "\n" + page_record(data, resolved)


def update_queue(source: str, data: AreaData, php_status: str) -> str:
    lines = source.splitlines()
    matches = 0
    status = "LOCAL_COMPLETE" if php_status == "PASSED" else "IN_PROGRESS"
    php_note = "PHP構文確認済み" if php_status == "PASSED" else "PHP CLI未確認"
    for index, line in enumerate(lines):
        parts = line.split("|")
        if len(parts) < 7 or not parts[1].strip().isdigit():
            continue
        if parts[3].strip() != f"`{data.slug}`":
            continue
        parts[4] = f" {status} "
        parts[5] = f" 専用ツール / {date.today().isoformat()} / 3ファイル・共有登録・静的検査済み / {php_note} "
        lines[index] = "|".join(parts)
        matches += 1
    if matches != 1:
        raise AreaToolError(f"areaキュー行が1件ではありません: {matches}")
    return "\n".join(lines).rstrip() + "\n"

def atomic_write(path: Path, content: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    handle, temp_name = tempfile.mkstemp(prefix=f".{path.name}.", suffix=".tmp", dir=path.parent)
    try:
        with os.fdopen(handle, "w", encoding="utf-8", newline="\n") as stream:
            stream.write(content)
        os.replace(temp_name, path)
    except Exception:
        try:
            os.unlink(temp_name)
        except FileNotFoundError:
            pass
        raise


def php_lint(paths: list[Path]) -> tuple[str, list[str]]:
    php = shutil.which("php")
    if not php:
        return "UNAVAILABLE", []
    errors: list[str] = []
    for path in paths:
        result = subprocess.run([php, "-l", str(path)], capture_output=True, text=True, encoding="utf-8", errors="replace")
        if result.returncode != 0:
            errors.append(f"{path}: {(result.stdout + result.stderr).strip()}")
    return ("PASSED" if not errors else "FAILED"), errors


def bundle_paths(hp_root: Path, slug: str) -> tuple[Path, Path, Path]:
    return (
        hp_root / f"kagoshima-deliveryhealth-area-{slug}.php",
        hp_root / "source" / f"kagoshima-deliveryhealth-area-{slug}.html",
        hp_root / "includefile" / f"dataset_kagoshima-deliveryhealth-area-{slug}.php",
    )


def shared_validation(data: AreaData, hp_root: Path) -> list[str]:
    errors: list[str] = []
    html_name = f"kagoshima-deliveryhealth-area-{data.slug}.html"
    php_name = f"kagoshima-deliveryhealth-area-{data.slug}.php"
    base = read_utf8(hp_root / "includefile" / "dataset_base.php")
    if base.count(f"case '{html_name}':") != 1:
        errors.append("dataset_base case登録が1件ではありません")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    if base.count(conversion) != 1:
        errors.append("dataset_baseリンク変換が1件ではありません")
    sitemap = read_utf8(hp_root / "sitemap.xml")
    if sitemap.count(f"<loc>{data.canonical}</loc>") != 1:
        errors.append("sitemap登録が1件ではありません")
    area_source = read_utf8(hp_root / "source" / "area.html")
    if area_source.count(f'./{php_name}') != 1:
        errors.append("area一覧リンクが1件ではありません")
    return errors


def run_build(args: argparse.Namespace) -> int:
    started = time.perf_counter()
    root = repo_root()
    hp_root = root / "HP"
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = root / input_path
    data = parse_area_text(input_path)
    parsed_at = time.perf_counter()
    templates = load_shop_templates(hp_root / "source" / "template_shop.html")
    resolved = resolve_shops(data, hp_root, templates)
    source_html = render_source(data, resolved, templates, hp_root / "source" / "template_kagoshima-deliveryhealth-area.html")
    render_errors = validate_rendered(data, resolved, source_html, hp_root)
    if render_errors:
        raise AreaToolError("生成前検証失敗:\n- " + "\n- ".join(render_errors))
    rendered_at = time.perf_counter()
    public_path, source_path, dataset_path = bundle_paths(hp_root, data.slug)
    existing = [path for path in (public_path, source_path, dataset_path) if path.exists()]
    if existing and not args.force and not args.dry_run:
        raise AreaToolError("既存ファイルがあります。上書きする場合だけ --force: " + ", ".join(str(path) for path in existing))

    base_path = hp_root / "includefile" / "dataset_base.php"
    sitemap_path = hp_root / "sitemap.xml"
    area_path = hp_root / "source" / "area.html"
    page_notes_path = root / "ページ作成用.md"
    queue_path = hp_root / "codex" / "docs" / "CANDY_AREA_105_PAGE_QUEUE.md"
    if read_utf8(area_path).count(f'./kagoshima-deliveryhealth-area-{data.slug}.php') != 1:
        raise AreaToolError("area一覧の正式リンクが1件ではありません。未知の一覧例外として停止します")
    new_base = update_dataset_base(read_utf8(base_path), data.slug)
    new_sitemap = update_sitemap(read_utf8(sitemap_path), data.canonical)
    new_notes = update_page_notes(read_utf8(page_notes_path), data, resolved)

    if args.dry_run:
        print(f"RESULT=DRY_RUN_OK slug={data.slug} region={data.region}")
        print(f"COUNTS shops={len(resolved)} articles={len(data.articles)} hotels={len(data.hotels)} spots={len(data.spots)}")
        for item in resolved:
            reference = f" reference={item.reference} distance_km={item.distance_km:.2f}" if item.reference and item.distance_km is not None else ""
            print(f"SHOP={item.name}|{item.time_text}|{item.fee_text}|source={item.source}{reference}")
        print(f"TIMING parse={parsed_at-started:.3f}s render_validate={rendered_at-parsed_at:.3f}s total={time.perf_counter()-started:.3f}s")
        return 0

    atomic_write(public_path, public_php_content())
    atomic_write(source_path, source_html)
    atomic_write(dataset_path, dataset_content())
    atomic_write(base_path, new_base)
    atomic_write(sitemap_path, new_sitemap)
    if not args.no_docs:
        atomic_write(page_notes_path, new_notes)
    written_at = time.perf_counter()

    actual_errors = validate_rendered(data, resolved, read_utf8(source_path), hp_root)
    actual_errors.extend(shared_validation(data, hp_root))
    php_status, php_errors = php_lint([public_path, dataset_path, base_path])
    actual_errors.extend(php_errors)
    if actual_errors:
        raise AreaToolError("書込後検証失敗:\n- " + "\n- ".join(actual_errors))
    if not args.no_docs:
        atomic_write(queue_path, update_queue(read_utf8(queue_path), data, php_status))
    print(f"RESULT=BUILD_OK slug={data.slug} region={data.region}")
    print(f"FILES={public_path.relative_to(root)},{source_path.relative_to(root)},{dataset_path.relative_to(root)}")
    print(f"COUNTS shops={len(resolved)} articles={len(data.articles)} hotels={len(data.hotels)} spots={len(data.spots)}")
    for item in resolved:
        reference = f" reference={item.reference} distance_km={item.distance_km:.2f}" if item.reference and item.distance_km is not None else ""
        print(f"SHOP={item.name}|{item.time_text}|{item.fee_text}|source={item.source}{reference}")
    print(f"PHP_LINT={php_status}")
    print(f"TIMING parse={parsed_at-started:.3f}s render_validate={rendered_at-parsed_at:.3f}s write={written_at-rendered_at:.3f}s total={time.perf_counter()-started:.3f}s")
    return 0


def run_check(args: argparse.Namespace) -> int:
    started = time.perf_counter()
    root = repo_root()
    hp_root = root / "HP"
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = root / input_path
    data = parse_area_text(input_path)
    templates = load_shop_templates(hp_root / "source" / "template_shop.html")
    resolved = resolve_shops(data, hp_root, templates)
    public_path, source_path, dataset_path = bundle_paths(hp_root, data.slug)
    missing = [str(path) for path in (public_path, source_path, dataset_path) if not path.is_file()]
    if missing:
        raise AreaToolError("生成ファイル不足: " + ", ".join(missing))
    errors = validate_rendered(data, resolved, read_utf8(source_path), hp_root)
    errors.extend(shared_validation(data, hp_root))
    php_status, php_errors = php_lint([public_path, dataset_path, hp_root / "includefile" / "dataset_base.php"])
    errors.extend(php_errors)
    if args.require_php and php_status == "UNAVAILABLE":
        errors.append("PHP CLIがありません")
    if errors:
        raise AreaToolError("検証失敗:\n- " + "\n- ".join(errors))
    print(f"RESULT=CHECK_OK slug={data.slug}")
    print(f"PHP_LINT={php_status}")
    print(f"TIMING total={time.perf_counter()-started:.3f}s")
    return 0


def run_audit_inputs(args: argparse.Namespace) -> int:
    started = time.perf_counter()
    root = repo_root()
    hp_root = root / "HP"
    base = hp_root / "Text_area_data"
    paths = sorted(list(base.glob("*.txt")) + (list((base / "Completion").glob("*.txt")) if args.include_completion else []))
    parse_failures: list[str] = []
    render_failures: list[str] = []
    pattern_counts: Counter[tuple[int, int, int, int]] = Counter()
    templates = load_shop_templates(hp_root / "source" / "template_shop.html") if args.render else {}
    for path in paths:
        try:
            data = parse_area_text(path)
            pattern_counts[(len(data.shops), len(data.articles), len(data.hotels), len(data.spots))] += 1
        except AreaToolError as exc:
            parse_failures.append(f"{path.relative_to(root)}: {exc}")
            continue
        if args.render:
            try:
                resolved = resolve_shops(data, hp_root, templates)
                source = render_source(data, resolved, templates, hp_root / "source" / "template_kagoshima-deliveryhealth-area.html")
                errors = validate_rendered(data, resolved, source, hp_root)
                if errors:
                    render_failures.append(f"{path.relative_to(root)}: {"; ".join(errors)}")
            except AreaToolError as exc:
                render_failures.append(f"{path.relative_to(root)}: {exc}")
    print(f"INPUTS={len(paths)} PARSED={len(paths)-len(parse_failures)} PARSE_FAILED={len(parse_failures)}")
    for pattern, count in sorted(pattern_counts.items()):
        print(f"PATTERN shops={pattern[0]} articles={pattern[1]} hotels={pattern[2]} spots={pattern[3]} count={count}")
    for failure in parse_failures:
        print(f"PARSE_FAIL={failure}")
    if args.render:
        parsed = len(paths) - len(parse_failures)
        print(f"RENDER_TARGETS={parsed} RENDER_PASSED={parsed-len(render_failures)} RENDER_STOPPED={len(render_failures)}")
        for failure in render_failures:
            print(f"RENDER_STOP={failure}")
    print(f"TIMING total={time.perf_counter()-started:.3f}s")
    return 1 if parse_failures or render_failures else 0

def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="CANDY area page builder/validator")
    subparsers = parser.add_subparsers(dest="command", required=True)
    build = subparsers.add_parser("build", help="テンプレートからareaページ一式を生成")
    build.add_argument("--input", required=True, help="Text_area_dataの入力ファイル")
    build.add_argument("--dry-run", action="store_true", help="ファイルを書かず解析・生成・検証")
    build.add_argument("--force", action="store_true", help="既存の3ファイルを意図的に上書き")
    build.add_argument("--no-docs", action="store_true", help="ページ作成用.mdへ記録しない")
    build.set_defaults(func=run_build)
    check = subparsers.add_parser("check", help="生成済みareaページ一式を一括検証")
    check.add_argument("--input", required=True, help="Text_area_dataの入力ファイル")
    check.add_argument("--require-php", action="store_true", help="PHP CLI不在も検証失敗にする")
    check.set_defaults(func=run_check)
    audit = subparsers.add_parser("audit-inputs", help="全area Textの入力パターンを解析")
    audit.add_argument("--include-completion", action="store_true", help="Completion配下も含める")
    audit.add_argument("--render", action="store_true", help="全入力を生成し画像を含む静的検証まで行う")
    audit.set_defaults(func=run_audit_inputs)
    return parser


def main() -> int:
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8", errors="backslashreplace")
    if hasattr(sys.stderr, "reconfigure"):
        sys.stderr.reconfigure(encoding="utf-8", errors="backslashreplace")
    parser = create_parser()
    args = parser.parse_args()
    try:
        return args.func(args)
    except AreaToolError as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
