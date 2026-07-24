#!/usr/bin/env python3
"""Build and validate CANDY hotel pages from Text_hotel_data."""

from __future__ import annotations

import argparse
import json
import re
import sys
import tempfile
import time
from collections import Counter
from dataclasses import dataclass, replace
from pathlib import Path
from urllib.parse import urlparse

import candy_area_page as common
import candy_page_common as path_config


class HotelToolError(RuntimeError):
    pass


SCENE_MARKER_RE = re.compile(r"^scene(?:\d+|（h2(?:\s*/\s*[^）]+)?）)$")
SUBTITLE_RE = re.compile(r"^subtitle(?:_\d+(?:_\d+)?)?_$|^subtitle_\d+(?:_\d+)?$")
DESCRIPTION_RE = re.compile(r"^description(?:_\d+(?:_\d+)?)?_$|^description_\d+(?:_\d+)?$")
PLACEHOLDER_RE = re.compile(r"a{8,}|placeholder|replace[_ -]?me|\b(?:todo|tbd)\b|不明時は削除|未入力", re.I)
RATE_LABELS = ("休憩", "休憩等", "ショート", "フリー", "宿泊", "延長")
CANONICAL_HOST = "www.55810.com"
DEFAULT_SPOT_NOTE = "周辺スポット情報は変更されている場合がございますので、詳細は直接お問い合わせください。"


@dataclass
class TextBlock:
    title: str
    description: str


@dataclass
class BasicInfo:
    hotel_name: str
    official_url: str
    address: str
    telephone: str | None
    room_parking: str | None
    payment: str | None


@dataclass
class AccessInfo:
    map_src: str
    map_title: str
    subtitle: str
    description: str


@dataclass
class HotelData:
    input_path: Path
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
    legacy_option: TextBlock | None
    article_scenes: list[TextBlock]
    scene_order: list[str]
    shop_heading: str
    shops: list[common.ShopRequest]
    faqs: list[TextBlock]
    basic: BasicInfo
    rates: list[tuple[str, str]]
    rate_note: str | None
    access: AccessInfo | None
    spot_heading: str
    spots: list[common.Place]
    spot_note: str

    @property
    def hotel_name(self) -> str:
        return self.basic.hotel_name


def repo_root() -> Path:
    return path_config.REPO_ROOT


def read_utf8(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8-sig").replace("\r\n", "\n")
    except UnicodeDecodeError as exc:
        raise HotelToolError(f"UTF-8で読めません: {path}") from exc


def validated_url(value: str, label: str, *, https_only: bool = False) -> str:
    parsed = urlparse(value)
    allowed_schemes = {"https"} if https_only else {"http", "https"}
    if parsed.scheme not in allowed_schemes or not parsed.netloc or parsed.username or parsed.password:
        raise HotelToolError(f"{label}が安全なURLではありません: {value}")
    return value


def ensure_unique(values: list[str], label: str) -> None:
    duplicates = [value for value, count in Counter(values).items() if count > 1]
    if duplicates:
        raise HotelToolError(f"{label}が重複しています: {', '.join(duplicates)}")


def ensure_label_count(lines: list[str], labels: tuple[str, ...], expected: int | tuple[int, ...], label: str) -> None:
    count = sum(1 for value in lines if value.strip() in labels)
    allowed = (expected,) if isinstance(expected, int) else expected
    if count not in allowed:
        raise HotelToolError(f"{label}の件数が不正です: {count}")


def is_separator(value: str) -> bool:
    return bool(common.SEPARATOR_RE.fullmatch(value.strip()))


def first_value(lines: list[str], labels: tuple[str, ...], start: int = 0, end: int | None = None) -> str:
    stop = len(lines) if end is None else end
    positions = [index for index in range(start, stop) if lines[index].strip() in labels]
    if not positions:
        return ""
    index = positions[0]
    values: list[str] = []
    for value in lines[index + 1 : stop]:
        stripped = value.strip()
        if is_separator(stripped) or SCENE_MARKER_RE.fullmatch(stripped):
            break
        if stripped in {
            "title", "description", "canonical", "image", "img_1", "img_2", "写真",
            "page_title_h1 / パンくずリスト", "subtitle_h1", "description_h1", "description_h1（改行無し）",
        }:
            break
        values.append(value.rstrip())
    return "\n".join(values).strip()


def field_value(section: list[str], labels: tuple[str, ...], all_labels: tuple[str, ...]) -> str:
    position = next((index for index, value in enumerate(section) if value.strip() in labels), None)
    if position is None:
        return ""
    values: list[str] = []
    for value in section[position + 1 :]:
        stripped = value.strip()
        if SUBTITLE_RE.fullmatch(stripped) or DESCRIPTION_RE.fullmatch(stripped):
            break
        if is_separator(stripped) or stripped in all_labels or SCENE_MARKER_RE.fullmatch(stripped):
            break
        values.append(value.rstrip())
    return "\n".join(values).strip()


def scene_sections(lines: list[str]) -> list[list[str]]:
    starts = [index for index, value in enumerate(lines) if SCENE_MARKER_RE.fullmatch(value.strip())]
    if not starts:
        raise HotelToolError("scene見出しがありません")
    return [lines[start : starts[offset + 1] if offset + 1 < len(starts) else len(lines)] for offset, start in enumerate(starts)]


def section_heading(section: list[str]) -> str:
    for value in section[1:]:
        stripped = value.strip()
        if stripped and not is_separator(stripped):
            return stripped
    raise HotelToolError("scene見出しの本文がありません")


def pair_blocks(section: list[str]) -> list[TextBlock]:
    positions = [index for index, value in enumerate(section) if SUBTITLE_RE.fullmatch(value.strip())]
    result: list[TextBlock] = []
    for offset, position in enumerate(positions):
        end = positions[offset + 1] if offset + 1 < len(positions) else len(section)
        description_position = next(
            (index for index in range(position + 1, end) if DESCRIPTION_RE.fullmatch(section[index].strip())),
            None,
        )
        if description_position is None:
            raise HotelToolError("subtitleに対応するdescriptionがありません")
        title = "\n".join(value.strip() for value in section[position + 1 : description_position] if value.strip())
        description_lines: list[str] = []
        for value in section[description_position + 1 : end]:
            stripped = value.strip()
            if is_separator(stripped) or DESCRIPTION_RE.fullmatch(stripped):
                break
            description_lines.append(value.rstrip())
        description = "\n".join(description_lines).strip()
        if not title or not description:
            raise HotelToolError("FAQ型項目の本文が不足しています")
        result.append(TextBlock(title, description))
    return result


def unmatched_descriptions(section: list[str]) -> list[str]:
    subtitle_positions = [index for index, value in enumerate(section) if SUBTITLE_RE.fullmatch(value.strip())]
    matched: set[int] = set()
    for offset, position in enumerate(subtitle_positions):
        end = subtitle_positions[offset + 1] if offset + 1 < len(subtitle_positions) else len(section)
        description_position = next(
            (index for index in range(position + 1, end) if DESCRIPTION_RE.fullmatch(section[index].strip())),
            None,
        )
        if description_position is not None:
            matched.add(description_position)
    result: list[str] = []
    for position, value in enumerate(section):
        if position in matched or not DESCRIPTION_RE.fullmatch(value.strip()):
            continue
        lines: list[str] = []
        for raw in section[position + 1 :]:
            stripped = raw.strip()
            if is_separator(stripped) or SCENE_MARKER_RE.fullmatch(stripped):
                break
            if SUBTITLE_RE.fullmatch(stripped) or DESCRIPTION_RE.fullmatch(stripped):
                break
            lines.append(raw.rstrip())
        description = "\n".join(lines).strip()
        if not description:
            raise HotelToolError(f"単独補足文が未完成です: {value.strip()}")
        result.append(description)
    return result


def parse_spots(section: list[str]) -> list[common.Place]:
    result: list[common.Place] = []
    for block in pair_blocks(section):
        address: list[str] = []
        telephone: str | None = None
        url = ""
        for raw in block.description.replace("<改行>", "\n").splitlines():
            value = raw.strip()
            if not value:
                continue
            telephone_match = re.match(r"^(?:TEL|電話)\s*[:：]?\s*(.+)$", value, re.I)
            url_match = re.match(r"^URL\s*[:：]?\s*(https?://\S+)$", value, re.I)
            if telephone_match:
                telephone = telephone_match.group(1).strip()
            elif url_match:
                url = url_match.group(1).strip()
            else:
                address.append(value)
        if not address or not url:
            raise HotelToolError(f"周辺スポット情報不足: {block.title}")
        result.append(common.Place(block.title, " ".join(address), telephone, url))
    return result


def image_values(lines: list[str]) -> tuple[str, str]:
    image1 = common.value_after_prefix(first_value(lines, ("img_1",)), "src")
    image2 = common.value_after_prefix(first_value(lines, ("img_2",)), "src")
    src_values = [
        match.group(1).strip()
        for value in lines
        if (match := re.match(r"^src\s*[:：]\s*(.+)$", value.strip()))
    ]
    if not image1 and src_values:
        image1 = src_values[0]
    if not image2 and len(src_values) > 1:
        image2 = src_values[1]
    return image1, image2


def parse_hotel_text(path: Path) -> HotelData:
    text = read_utf8(path)
    lines = text.splitlines()
    data_start = next((index for index, value in enumerate(lines) if value.strip() == "title"), 0)
    if PLACEHOLDER_RE.search("\n".join(lines[data_start:])):
        raise HotelToolError("元データにplaceholderが残っています")
    first_scene = next((index for index, value in enumerate(lines) if SCENE_MARKER_RE.fullmatch(value.strip())), len(lines))
    metadata = lines[:first_scene]
    for labels, expected, label in (
        (("title",), 1, "title"),
        (("description",), 1, "description"),
        (("canonical",), 1, "canonical"),
        (("image",), 1, "image"),
        (("page_title_h1 / パンくずリスト",), 1, "page_title_h1"),
        (("subtitle_h1",), 1, "subtitle_h1"),
        (("description_h1", "description_h1（改行無し）"), 1, "description_h1"),
    ):
        ensure_label_count(metadata, labels, expected, label)
    ensure_label_count(lines, ("img_1",), 1, "img_1")
    ensure_label_count(lines, ("img_2",), 1, "img_2")
    title = common.labeled_value(lines, "title", 0, first_scene)
    meta_description = common.labeled_value(lines, "description", 0, first_scene)
    canonical = common.labeled_value(lines, "canonical", 0, first_scene)
    og_image = common.labeled_value(lines, "image", 0, first_scene)
    image1, image2 = image_values(lines)
    page_title = first_value(lines, ("page_title_h1 / パンくずリスト",), 0, first_scene)
    subtitle_h1 = first_value(lines, ("subtitle_h1",), 0, first_scene)
    description_h1 = first_value(lines, ("description_h1", "description_h1（改行無し）"), 0, first_scene)
    canonical_parts = urlparse(canonical)
    canonical_match = re.fullmatch(
        r"/kagoshima-deliveryhealth-hotel-([a-z0-9-]+)\.php", canonical_parts.path
    )
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
    missing = [name for name, value in required.items() if not value]
    if (
        not canonical_match
        or canonical_parts.scheme != "https"
        or canonical_parts.netloc != CANONICAL_HOST
        or canonical_parts.query
        or canonical_parts.fragment
    ):
        missing.append("canonical slug")
    if missing:
        raise HotelToolError("必須項目不足: " + ", ".join(missing))

    slug = canonical_match.group(1)
    image_pattern = re.compile(rf"^\./imgHtml/[A-Za-z0-9_/-]*/hotel/{re.escape(slug)}_([12])\.(?:jpe?g|png|webp)$", re.I)
    image1_match = image_pattern.fullmatch(image1)
    image2_match = image_pattern.fullmatch(image2)
    if not image1_match or image1_match.group(1) != "1":
        raise HotelToolError("img_1とcanonical slugが一致しません")
    if not image2_match or image2_match.group(1) != "2":
        raise HotelToolError("img_2とcanonical slugが一致しません")
    if image1 == image2:
        raise HotelToolError("img_1とimg_2が重複しています")
    expected_og_image = f"https://{CANONICAL_HOST}/{image1.removeprefix('./')}"
    if og_image != expected_og_image:
        raise HotelToolError("OGP imageとimg_1が一致しません")

    option_heading = common.labeled_value(lines, "option", 0, first_scene, stop_labels=("option_subtitle",))
    option_subtitle = common.labeled_value(lines, "option_subtitle", 0, first_scene, stop_labels=("option_description",))
    option_description = common.labeled_value(lines, "option_description", 0, first_scene)
    if any((option_heading, option_subtitle, option_description)) and not all((option_heading, option_subtitle, option_description)):
        raise HotelToolError("option項目が未完成です")
    legacy_option = (
        TextBlock(option_heading, f"{option_subtitle}\n{option_description}")
        if option_heading and option_subtitle and option_description
        else None
    )
    article_scenes: list[TextBlock] = []
    scene_order: list[str] = []

    shop_heading = ""
    shops: list[common.ShopRequest] = []
    faqs: list[TextBlock] = []
    basic: BasicInfo | None = None
    rates: list[tuple[str, str]] = []
    rate_note: str | None = None
    access: AccessInfo | None = None
    spot_heading = ""
    spots: list[common.Place] = []
    spot_note = DEFAULT_SPOT_NOTE
    room_parking_labels = ("部屋・駐車場", "部屋数・駐車場")
    basic_labels = ("ホテル名", "URL", "住所", "住所（郵便番号迄）", "電話番号", *room_parking_labels, "支払方法")
    access_labels = ("地図URL", "地図タイトル")
    seen_sections: set[str] = set()

    for section in scene_sections(lines):
        heading = section_heading(section)
        if "人気デリヘル店" in heading or "人気デリヘル店" in section[0]:
            if "shops" in seen_sections:
                raise HotelToolError("人気デリヘル店sceneが重複しています")
            seen_sections.add("shops")
            scene_order.append("shops")
            shop_heading = heading
            shops = common.parse_shop_requests(section)
        elif heading == "subtitle_":
            if "faqs" in seen_sections:
                raise HotelToolError("FAQ sceneが重複しています")
            seen_sections.add("faqs")
            faqs = pair_blocks(section)
            if faqs:
                scene_order.append("faqs")
        elif "よくあるご質問" in heading or "FAQ" in heading:
            if "faqs" in seen_sections:
                raise HotelToolError("FAQ sceneが重複しています")
            seen_sections.add("faqs")
            faqs = pair_blocks(section)
            if faqs:
                scene_order.append("faqs")
        elif heading == "基本情報":
            if "basic" in seen_sections:
                raise HotelToolError("基本情報sceneが重複しています")
            seen_sections.add("basic")
            scene_order.append("basic")
            for label in basic_labels:
                ensure_label_count(section, (label,), (0, 1), f"基本情報 {label}")
            ensure_label_count(section, room_parking_labels, (0, 1), "基本情報 部屋数・駐車場")
            basic = BasicInfo(
                hotel_name=field_value(section, ("ホテル名",), basic_labels),
                official_url=field_value(section, ("URL",), basic_labels),
                address=field_value(section, ("住所", "住所（郵便番号迄）"), basic_labels),
                telephone=field_value(section, ("電話番号",), basic_labels) or None,
                room_parking=field_value(section, room_parking_labels, basic_labels) or None,
                payment=field_value(section, ("支払方法",), basic_labels) or None,
            )
        elif heading == "料金情報":
            if "rates" in seen_sections:
                raise HotelToolError("料金情報sceneが重複しています")
            seen_sections.add("rates")
            for label in RATE_LABELS:
                ensure_label_count(section, (label,), (0, 1), f"料金情報 {label}")
                value = field_value(section, (label,), RATE_LABELS)
                if value:
                    rates.append((label, value))
            rate_notes = unmatched_descriptions(section)
            if len(rate_notes) > 1:
                raise HotelToolError("料金情報の単独補足文が複数あります")
            rate_note = rate_notes[0] if rate_notes else None
            if rates:
                scene_order.append("rates")
            elif rate_note:
                raise HotelToolError("料金行がないのに料金補足文だけが指定されています")
        elif heading == "アクセス情報":
            if "access" in seen_sections:
                raise HotelToolError("アクセス情報sceneが重複しています")
            seen_sections.add("access")
            raw_map = field_value(section, ("地図URL",), access_labels + ("subtitle_", "description_"))
            map_match = re.search(r'\bsrc="([^"]+)"', raw_map)
            subtitle_blocks = pair_blocks(section)
            if not subtitle_blocks:
                subtitle = common.labeled_value(section, "subtitle_", stop_labels=("description_",))
                description = common.labeled_value(section, "description_")
            else:
                subtitle = subtitle_blocks[0].title
                description = subtitle_blocks[0].description
            candidate = AccessInfo(
                map_src=map_match.group(1) if map_match else raw_map.strip(),
                map_title=field_value(section, ("地図タイトル",), access_labels + ("subtitle_", "description_")),
                subtitle=subtitle,
                description=description,
            )
            access_values = (candidate.map_src, candidate.map_title, candidate.subtitle, candidate.description)
            if any(access_values) and not all(access_values):
                raise HotelToolError("アクセス情報sceneが部分入力です")
            if all(access_values):
                access = candidate
                scene_order.append("access")
        elif "周辺スポット" in heading:
            if "spots" in seen_sections:
                raise HotelToolError("周辺スポットsceneが重複しています")
            seen_sections.add("spots")
            spot_heading = heading
            spots = parse_spots(section)
            spot_notes = unmatched_descriptions(section)
            if len(spot_notes) > 1:
                raise HotelToolError("周辺スポットの単独注意文が複数あります")
            if spots:
                scene_order.append("spots")
                if spot_notes:
                    spot_note = spot_notes[0]
            elif spot_notes:
                raise HotelToolError("周辺スポットがないのに注意文だけが指定されています")
        else:
            subtitle = common.labeled_value(section, "subtitle_", stop_labels=("description_",))
            description = common.labeled_value(section, "description_")
            if not subtitle or not description:
                raise HotelToolError(f"通常sceneが未完成です: {heading}")
            token = f"article:{len(article_scenes)}"
            article_scenes.append(TextBlock(heading, f"{subtitle}\n{description}"))
            scene_order.append(token)

    if not shop_heading or not shops:
        raise HotelToolError("人気デリヘル店sceneの情報が不足しています")
    if basic is None or not basic.hotel_name or not basic.official_url or not basic.address:
        raise HotelToolError("基本情報sceneのホテル名・URL・住所が不足しています")
    known_order = [token for token in scene_order if not token.startswith("article:")]
    expected_order = [
        token for token in ("shops", "faqs", "basic", "rates", "access", "spots")
        if token in known_order
    ]
    if known_order != expected_order:
        raise HotelToolError(f"hotel scene順序が不正です: {known_order}")
    ensure_unique([request.key for request in shops], "店舗")
    ensure_unique([item.title for item in faqs], "FAQ質問")
    ensure_unique([item.title for item in spots], "周辺スポット")
    validated_url(basic.official_url, "ホテル公式URL")
    if access:
        validated_url(access.map_src, "地図URL", https_only=True)
    for spot in spots:
        validated_url(spot.url, f"周辺スポットURL ({spot.title})", https_only=True)
    if basic.hotel_name not in page_title:
        raise HotelToolError("page_title_h1とホテル名が一致しません")
    if basic.hotel_name not in title:
        raise HotelToolError("titleとホテル名が一致しません")
    parsed_values = "\n".join(
        [title, meta_description, page_title, subtitle_h1, description_h1, basic.hotel_name, basic.address]
        + [value for value in (basic.room_parking, basic.payment) if value]
    )
    if PLACEHOLDER_RE.search(parsed_values):
        raise HotelToolError("使用項目にplaceholderが残っています")

    return HotelData(
        input_path=path,
        slug=slug,
        title=title,
        meta_description=meta_description,
        canonical=canonical,
        og_image=og_image,
        image1=image1,
        image2=image2,
        page_title=page_title,
        subtitle_h1=subtitle_h1,
        description_h1=description_h1,
        legacy_option=legacy_option,
        article_scenes=article_scenes,
        scene_order=scene_order,
        shop_heading=shop_heading,
        shops=shops,
        faqs=faqs,
        basic=basic,
        rates=rates,
        rate_note=rate_note,
        access=access,
        spot_heading=spot_heading,
        spots=spots,
        spot_note=spot_note,
    )


def resolve_shops(
    data: HotelData,
    hp_root: Path,
    templates: dict[str, common.ShopTemplate],
) -> list[common.ShopResolved]:
    resolved: list[common.ShopResolved] = []
    target_coords = common.coordinates(data.access.map_src) if data.access else None
    for request in data.shops:
        template = templates[request.key]
        if request.time_text and request.fee_text:
            resolved.append(common.ShopResolved(
                request.name, request.key, request.time_text, request.fee_text, request.source
            ))
            continue
        if request.source == "text-template-default" and template.default_time and template.default_fee:
            resolved.append(common.ShopResolved(
                request.name, request.key, template.default_time, template.default_fee, request.source
            ))
            continue
        if not target_coords:
            raise HotelToolError(
                f"店舗の移動時間・交通費が不足し、ホテル地図から座標を取得できません: {request.name}"
            )
        nearest = common.nearest_travel(hp_root, data.slug, target_coords, request.key)
        if not nearest:
            raise HotelToolError(
                f"店舗の移動時間・交通費が不足し、参照可能な近隣完成ページがありません: {request.name}"
            )
        time_text, fee_text, reference, distance_km = nearest
        resolved.append(common.ShopResolved(
            request.name,
            request.key,
            time_text,
            fee_text,
            "nearest-missing-text",
            reference,
            distance_km,
        ))
    return resolved


def json_scripts(data: HotelData, resolved: list[common.ShopResolved], templates: dict[str, common.ShopTemplate]) -> str:
    values: list[dict[str, object]] = [
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {"@type": "ListItem", "position": 1, "name": "TOP", "item": "https://www.55810.com/"},
                {"@type": "ListItem", "position": 2, "name": "対応ホテル一覧", "item": "https://www.55810.com/hotel.php"},
                {"@type": "ListItem", "position": 3, "name": data.page_title, "item": data.canonical},
            ],
        }
    ]
    if data.faqs:
        values.append(
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": item.title,
                        "acceptedAnswer": {"@type": "Answer", "text": item.description.replace("\n", " ")},
                    }
                    for item in data.faqs
                ],
            }
        )
    item_elements: list[dict[str, object]] = []
    if data.spots:
        for position, spot in enumerate(data.spots, 1):
            item: dict[str, object] = {
                "@type": "LocalBusiness",
                "name": spot.title,
                "address": spot.address,
                "url": spot.url,
            }
            if spot.telephone:
                item["telephone"] = spot.telephone
            item_elements.append({"@type": "ListItem", "position": position, "item": item})
        item_name = f"{data.hotel_name} 周辺スポット"
        item_count = len(data.spots)
    else:
        for position, shop in enumerate(resolved, 1):
            template = templates[shop.key]
            item_elements.append(
                {
                    "@type": "ListItem",
                    "position": position,
                    "item": {
                        "@type": "Organization",
                        "name": shop.name,
                        "telephone": template.telephone,
                        "url": template.url,
                        "description": template.description,
                    },
                }
            )
        item_name = data.shop_heading.replace("情報", "一覧")
        item_count = len(resolved)
    values.append(
        {
            "@context": "https://schema.org",
            "@type": "ItemList",
            "name": item_name,
            "itemListOrder": "https://schema.org/ItemListUnordered",
            "numberOfItems": item_count,
            "itemListElement": item_elements,
        }
    )
    return "\n".join(
        '<script type="application/ld+json">\n' + json.dumps(value, ensure_ascii=False, indent=2) + "\n</script>"
        for value in values
    )


def render_text_scene(item: TextBlock, number: int) -> str:
    subtitle, _, description = item.description.partition("\n")
    return (
        f'\t\t\t\t<h2 class="lp_38_0 bd_t fs_xxl fc_p" id="scene{number}">{common.htext(item.title)}</h2>\n'
        f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_{number}">{common.htext(subtitle)}</div>\n'
        f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_{number}">{common.htext(description)}</div>\n'
    )


def render_faq(items: list[TextBlock], number: int) -> str:
    lines = [
        "\t\t\t<!-- FAQ START -->",
        '\t\t\t<div class="lm_0_auto w_1000 lp_0_7">',
        f'\t\t\t\t<h2 class="lp_50_0_40 fs_xxl fc_p" id="scene{number}">よくあるご質問「FAQ」</h2>',
        "",
    ]
    for index, item in enumerate(items, 1):
        border = "bd_tb" if index == len(items) else "bd_t"
        lines.extend(
            [
                f'\t\t\t\t<div class="faq-item {border}">',
                f'\t\t\t\t<div class="faq-question fs_md2" id="subtitle_{number}_{index}">{common.htext(item.title)}</div>',
                f'\t\t\t\t<div class="faq-answer fs_md2" id="description_{number}_{index}">{common.htext(item.description)}</div>',
                "\t\t\t\t</div>",
                "",
            ]
        )
    lines.extend(
        [
            '\t\t\t\t<div class="lp_40_0_75 center"><a href="./#shopinfo" class="bt-pk-xl">対応デリヘル店一覧</a></div>',
            "\t\t\t</div>",
            "\t\t\t<!-- FAQ END -->",
        ]
    )
    return "\n".join(lines)


def render_basic(data: HotelData, number: int) -> str:
    rows = [
        ("ホテル名", f'<a href="{common.hattr(data.basic.official_url)}" target="_blank" rel="noopener noreferrer" class="fade">{common.htext(data.hotel_name)}</a>'),
        ("住所", common.htext(data.basic.address)),
    ]
    if data.basic.telephone:
        rows.append(("電話番号", common.htext(data.basic.telephone)))
    if data.basic.room_parking:
        rows.append(("部屋・駐車場", common.htext(data.basic.room_parking)))
    if data.basic.payment:
        rows.append(("支払方法", common.htext(data.basic.payment)))
    body = "\n".join(
        f'\t\t\t\t\t\t<tr><td class="lp_15 fs_sm2">{label}</td><td class="lp_15 fs_sm2">{value}</td></tr>'
        for label, value in rows
    )
    return (
        f'\t\t\t\t<h2 class="lp_40_0_25 fs_xxl fc_p" id="scene{number}">基本情報</h2>\n'
        '\t\t\t\t<table class="info-table1">\n\t\t\t\t\t<tbody>\n'
        f"{body}\n"
        "\t\t\t\t\t</tbody>\n\t\t\t\t</table>"
    )


def render_rates(data: HotelData, number: int) -> str:
    body = "\n".join(
        f'\t\t\t\t\t\t<tr><td class="lp_15 fs_sm2">{common.htext(label)}</td><td class="lp_15 fs_sm2">{common.htext(value)}</td></tr>'
        for label, value in data.rates
    )
    note = (
        f'\n\t\t\t\t<div class="lp_30_0 fs_md3" id="description_{number}">{common.htext(data.rate_note)}</div>'
        if data.rate_note
        else ""
    )
    return (
        f'\t\t\t\t<h2 class="lp_40_0_25 fs_xxl fc_p" id="scene{number}">料金情報</h2>\n'
        '\t\t\t\t<table class="info-table1">\n\t\t\t\t\t<tbody>\n'
        f"{body}\n"
        "\t\t\t\t\t</tbody>\n\t\t\t\t</table>"
        f"{note}"
    )


def render_legacy_option(item: TextBlock) -> str:
    subtitle, _, description = item.description.partition("\n")
    return (
        f'\t\t\t\t<h2 class="lp_38_0 bd_t fs_xxl fc_p" id="option">{common.htext(item.title)}</h2>\n'
        f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="option_subtitle">{common.htext(subtitle)}</div>\n'
        f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="option_description">{common.htext(description)}</div>'
    )


def render_shop_scene(
    data: HotelData,
    resolved: list[common.ShopResolved],
    templates: dict[str, common.ShopTemplate],
    number: int,
) -> str:
    heading = common.htext(data.shop_heading).replace(
        "「鹿児島の人気デリヘル店」",
        '<span class="fc_p">「鹿児島の人気デリヘル店」</span>',
    )
    return f'''\t\t\t<div class="lm_full bg_g1">
\t\t\t\t<h2 class="lp_38_0 fs_xxl center" id="scene{number}">{heading}</h2>
\t\t\t\t<div class="lm_0_auto w_1080"><ul class="campaign-list" role="list">
{common.render_shop_blocks(resolved, templates)}
\t\t\t\t</ul>
\t\t\t\t<div class="lp_30_0 fs_md3 center" id="description_{number}">最新の対応状況は各店舗へ<br class="spOnly">直接お問い合わせくださいませ。</div>
\t\t\t\t<div class="lpb_75 center"><a href="./#shopinfo" class="bt-pk-xl">対応デリヘル店一覧</a></div></div>
\t\t\t</div>'''


def render_access(data: HotelData, number: int) -> str:
    return (
        f'\t\t\t\t<h2 class="lp_40_0_25 fs_xxl fc_p" id="scene{number}">アクセス情報</h2>\n'
        f'\t\t\t\t<iframe title="{common.hattr(data.access.map_title)}" src="{common.hattr(data.access.map_src)}" class="map-iframe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>\n'
        f'\t\t\t\t<div class="lpt_30 fs_l" id="subtitle_{number}">{common.htext(data.access.subtitle)}</div>\n'
        f'\t\t\t\t<div class="lm_25_0_75 fs_md3" id="description_{number}">{common.htext(data.access.description)}</div>'
    )


def render_spots(data: HotelData, number: int) -> str:
    lines = [
        f'\t\t\t\t<h2 class="lp_38_0 bd_t fs_xxl fc_p" id="scene{number}">{common.htext(data.spot_heading)}</h2>',
        "",
    ]
    for index, spot in enumerate(data.spots, 1):
        border = "bd_tb" if index == len(data.spots) else "bd_t"
        lines.extend(
            [
                f'\t\t\t\t<div class="faq-item {border}">',
                f'\t\t\t\t<div class="faq-question fs_md2" id="subtitle_{number}_{index}">{common.htext(spot.title)}</div>',
                f'\t\t\t\t<div class="faq-answer fs_md2" id="description_{number}_{index}">{common.htext(spot.address)}<br>',
            ]
        )
        if spot.telephone:
            lines.append(f"\t\t\t\t\tTEL {common.htext(spot.telephone)}")
        lines.extend(
            [
                f'\t\t\t\t\t<div class="lm_25_0_35"><a href="{common.hattr(spot.url)}" target="_blank" rel="noopener noreferrer" class="bt-pk-m">詳細はコチラ</a></div>',
                "\t\t\t\t</div>",
                "\t\t\t\t</div>",
                "",
            ]
        )
    lines.append(
        f'\t\t\t\t<div class="lm_40_0_75 fs_md3" id="spot_note">{common.htext(data.spot_note)}</div>'
    )
    return "\n".join(lines)


def render_related(slug: str) -> str:
    return path_config.related_links_html("hotel", slug, "bd")


def render_terminal_shop_cta() -> str:
    return (
        '\t\t\t\t<div class="lm_40_0_75 center" id="button_3">'
        '<a href="./#shopinfo" class="bt-pk-xl">対応デリヘル店一覧</a></div>'
    )


def render_main(data: HotelData, resolved: list[common.ShopResolved], templates: dict[str, common.ShopTemplate]) -> str:
    numbers = {token: index for index, token in enumerate(data.scene_order, 1)}
    articles = {f"article:{index}": item for index, item in enumerate(data.article_scenes)}
    shop_index = data.scene_order.index("shops")
    leading_tokens = data.scene_order[:shop_index]
    remaining_tokens = data.scene_order[shop_index:]
    title_prefix = data.page_title[: -len(data.hotel_name)].rstrip() if data.page_title.endswith(data.hotel_name) else ""
    h1 = (
        f'{common.htext(title_prefix)} <span class="fc_p">{common.htext(data.hotel_name)}</span>'
        if title_prefix
        else common.htext(data.page_title)
    )
    intro_parts: list[str] = []
    if data.legacy_option:
        intro_parts.append(render_legacy_option(data.legacy_option))
    intro_parts.extend(render_text_scene(articles[token], numbers[token]) for token in leading_tokens)
    intro_html = "\n".join(intro_parts)

    content: list[str] = []
    inline: list[str] = []
    image2_rendered = False

    def flush_inline() -> None:
        if not inline:
            return
        content.append(
            '\t\t\t<div class="lm_0_auto w_1000 lp_0_7">\n'
            + "\n".join(inline)
            + "\n\t\t\t</div>"
        )
        inline.clear()

    for token in remaining_tokens:
        number = numbers[token]
        if token == "shops":
            flush_inline()
            content.append(render_shop_scene(data, resolved, templates, number))
        elif token == "faqs":
            flush_inline()
            content.append(render_faq(data.faqs, number))
        else:
            if token in {"basic", "rates", "access", "spots"} and not image2_rendered:
                flush_inline()
                content.append(
                    f'\t\t\t<div class="lpt_55 center" id="img_2"><img src="{common.hattr(data.image2)}" class="img_1 nolazy" loading="lazy" alt="{common.hattr(data.hotel_name)}基本情報"></div>'
                )
                image2_rendered = True
            if token.startswith("article:"):
                inline.append(render_text_scene(articles[token], number))
            elif token == "basic":
                inline.append(render_basic(data, number))
            elif token == "rates":
                inline.append(render_rates(data, number))
            elif token == "access":
                inline.append(render_access(data, number))
            elif token == "spots":
                inline.append(render_spots(data, number))
            else:
                raise HotelToolError(f"未対応scene種別です: {token}")
    inline.extend((render_related(data.slug), render_terminal_shop_cta()))
    flush_inline()
    content_html = "\n".join(content)
    return f'''<!-- メインコンテンツ START -->
\t\t<div id="main" class="main">
\t\t\t<div class="pcOnly lm_0_auto w_1000 lp_20_0 fc_g">
\t\t\t\t<nav aria-label="パンくずリスト"><ol class="bread_list f_xxs">
\t\t\t\t\t<li><a href="./" class="fc_b">TOP</a></li><li class="lm_0_10">&gt;</li>
\t\t\t\t\t<li><a href="./hotel.php" class="fc_b">対応ホテル一覧</a></li><li class="lm_0_10">&gt;</li>
\t\t\t\t\t<li><span>{common.htext(data.page_title)}</span></li>
\t\t\t\t</ol></nav>
\t\t\t</div>
\t\t\t<div class="center" id="img_1"><img src="{common.hattr(data.image1)}" class="img_1 nolazy" alt="{common.hattr(data.hotel_name)}"></div>
\t\t\t<div class="lm_0_auto w_1000 lp_0_7">
\t\t\t\t<h1 class="lp_50_0_40 center fs_xxl" id="page_title_h1">{h1}</h1>
\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_h1">{common.htext(data.subtitle_h1)}</div>
\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_h1">{common.htext(data.description_h1)}</div>
{intro_html}
\t\t\t\t<div class="lm_40_0_75 center"><a href="{common.hattr(data.basic.official_url)}" target="_blank" rel="noopener noreferrer" class="bt-pk-xl">ホテル詳細</a></div>
\t\t\t</div>
{content_html}
\t\t</div>
<!-- メインコンテンツ END -->'''


def render_source(data: HotelData, resolved: list[common.ShopResolved], templates: dict[str, common.ShopTemplate], template_path: Path) -> str:
    source = read_utf8(template_path)
    source = common.replace_exact(source, r'(<meta name="description" content=")[^"]*(">)', rf"\g<1>{common.hattr(data.meta_description)}\g<2>", "meta description")
    source = common.replace_exact(source, r"<title>.*?</title>", f"<title>{common.htext(data.title)}</title>", "title")
    source = common.replace_exact(source, r'(<link rel="canonical" href=")[^"]*(">)', rf"\g<1>{common.hattr(data.canonical)}\g<2>", "canonical")
    source = common.replace_exact(source, r'(<meta property="og:title" content=")[^"]*(">)', rf"\g<1>{common.hattr(data.title)}\g<2>", "og title")
    source = common.replace_exact(source, r'(<meta property="og:url" content=")[^"]*(">)', rf"\g<1>{common.hattr(data.canonical)}\g<2>", "og url")
    source = common.replace_exact(source, r'(<meta property="og:image" content=")[^"]*(">)', rf"\g<1>{common.hattr(data.og_image)}\g<2>", "og image")
    source = common.replace_exact(source, r'(<meta property="og:description" content=")[^"]*(">)', rf"\g<1>{common.hattr(data.meta_description)}\g<2>", "og description")
    source = common.replace_exact(
        source,
        r'(?s)(<!-- 構造化データ（JSON-LD） START -->).*?(<!-- 構造化データ（JSON-LD） END -->)',
        rf"\g<1>\n{json_scripts(data, resolved, templates)}\n\g<2>",
        "JSON-LD",
    )
    source = common.replace_exact(
        source,
        r"(?s)<!-- メインコンテンツ START -->.*?<!-- メインコンテンツ END -->",
        render_main(data, resolved, templates),
        "main content",
    )
    return "\n".join(line.rstrip() for line in source.splitlines()).rstrip() + "\n"


def related_validation(source: str, canonical: str) -> list[str]:
    return path_config.validate_related_links(source, canonical)


def terminal_shop_cta_validation(source: str) -> list[str]:
    expected = render_terminal_shop_cta()
    if source.count(expected) != 1:
        return ["関連記事後の対応デリヘル店一覧ボタンまたは終端余白が不整合"]
    return []


def validate_rendered(data: HotelData, resolved: list[common.ShopResolved], source: str, hp_root: Path) -> list[str]:
    errors: list[str] = related_validation(source, data.canonical)
    errors.extend(terminal_shop_cta_validation(source))
    if PLACEHOLDER_RE.search(source) or "<改行>" in source:
        errors.append("placeholder残存")
    if '<meta name="robots" content="index">' not in source:
        errors.append("robots indexなし")
    if f'<link rel="canonical" href="{common.hattr(data.canonical)}">' not in source:
        errors.append("canonical不整合")
    if f"<title>{common.htext(data.title)}</title>" not in source:
        errors.append("title不整合")
    h1_match = re.search(r'(?s)<h1\b[^>]*id="page_title_h1"[^>]*>(.*?)</h1>', source)
    h1_text = common.strip_tags(h1_match.group(1)) if h1_match else ""
    if data.hotel_name not in h1_text:
        errors.append("h1ホテル名不整合")
    ids = re.findall(r'\bid="([^"]+)"', source)
    duplicates = [value for value, count in Counter(ids).items() if count > 1]
    if duplicates:
        errors.append("ID重複: " + ", ".join(duplicates))
    scenes = [int(value) for value in re.findall(r'\bid="scene(\d+)"', source)]
    if scenes != list(range(1, len(scenes) + 1)):
        errors.append(f"scene連番不整合: {scenes}")
    article_map = {f"article:{index}": item for index, item in enumerate(data.article_scenes)}
    expected_headings = []
    for token in data.scene_order:
        if token.startswith("article:"):
            expected_headings.append(article_map[token].title)
        elif token == "shops":
            expected_headings.append(data.shop_heading)
        elif token == "faqs":
            expected_headings.append("よくあるご質問「FAQ」")
        elif token == "basic":
            expected_headings.append("基本情報")
        elif token == "rates":
            expected_headings.append("料金情報")
        elif token == "access":
            expected_headings.append("アクセス情報")
        elif token == "spots":
            expected_headings.append(data.spot_heading)
    actual_headings = [
        common.strip_tags(value)
        for value in re.findall(r'(?s)<h2\b[^>]*id="scene\d+"[^>]*>(.*?)</h2>', source)
    ]
    if actual_headings != expected_headings:
        errors.append(f"scene見出し順不整合: expected={expected_headings} actual={actual_headings}")
    option_ids = {"option", "option_subtitle", "option_description"}
    actual_option_ids = option_ids.intersection(ids)
    if data.legacy_option:
        if actual_option_ids != option_ids:
            errors.append("legacy option構造不足")
        else:
            option_values = [data.legacy_option.title] + data.legacy_option.description.split("\n", 1)
            if not all(common.htext(value) in source for value in option_values):
                errors.append("legacy option本文不整合")
    elif actual_option_ids:
        errors.append("optionなし入力にlegacy optionが残っています")
    scene_numbers = {token: index for index, token in enumerate(data.scene_order, 1)}
    rate_match = None
    if "rates" in scene_numbers:
        rate_match = re.search(
            rf'(?s)<div\b[^>]*id="description_{scene_numbers["rates"]}"[^>]*>(.*?)</div>',
            source,
        )
    actual_rate_note = common.strip_tags(rate_match.group(1)) if rate_match else None
    if actual_rate_note != data.rate_note:
        errors.append(f"料金補足文不整合: expected={data.rate_note!r} actual={actual_rate_note!r}")
    spot_note_match = re.search(r'(?s)<div\b[^>]*id="spot_note"[^>]*>(.*?)</div>', source)
    actual_spot_note = common.strip_tags(spot_note_match.group(1)) if spot_note_match else None
    expected_spot_note = data.spot_note if data.spots else None
    if actual_spot_note != expected_spot_note:
        errors.append(f"周辺スポット注意文不整合: expected={expected_spot_note!r} actual={actual_spot_note!r}")
    json_values: list[dict[str, object]] = []
    for index, value in enumerate(re.findall(r'(?s)<script type="application/ld\+json">\s*(.*?)\s*</script>', source), 1):
        try:
            json_values.append(json.loads(value))
        except json.JSONDecodeError as exc:
            errors.append(f"JSON-LD {index}: {exc}")
    expected_json_count = 3 if data.faqs else 2
    if len(json_values) != expected_json_count:
        errors.append(f"JSON-LD件数: expected={expected_json_count} actual={len(json_values)}")
    faq_json = next((value for value in json_values if value.get("@type") == "FAQPage"), None)
    if data.faqs:
        expected_faqs = [(item.title, item.description.replace("\n", " ")) for item in data.faqs]
        actual_faqs = []
        if faq_json:
            for entity in faq_json.get("mainEntity", []):
                answer = entity.get("acceptedAnswer", {}) if isinstance(entity, dict) else {}
                actual_faqs.append((entity.get("name"), answer.get("text")))
        if actual_faqs != expected_faqs:
            errors.append("FAQ本文とJSON-LD不一致")
    elif faq_json is not None:
        errors.append("FAQなしページにFAQPageが残っています")
    item_json = next((value for value in json_values if value.get("@type") == "ItemList"), None)
    expected_item_names = [item.title for item in data.spots] if data.spots else [item.name for item in resolved]
    if not item_json or item_json.get("numberOfItems") != len(expected_item_names):
        errors.append("ItemList本文件数不一致")
    else:
        actual_item_names = []
        for entity in item_json.get("itemListElement", []):
            item = entity.get("item", {}) if isinstance(entity, dict) else {}
            actual_item_names.append(item.get("name"))
        if actual_item_names != expected_item_names:
            errors.append("ItemList本文順序不一致")
    actual_keys = [value for value in re.findall(r"<!-- ([a-z0-9_]+) -->", source) if value in common.KEY_TO_NAME]
    expected_keys = [value.key for value in resolved]
    if actual_keys != expected_keys:
        errors.append(f"店舗順不整合: expected={expected_keys} actual={actual_keys}")
    for item in resolved:
        travel = common.extract_existing_shop(source, item.key)
        if travel != (item.time_text, item.fee_text):
            errors.append(f"店舗交通費不整合: {item.name}")
    for relative in (data.image1, data.image2):
        if not (hp_root / relative.removeprefix("./")).is_file():
            errors.append(f"画像なし: {relative}")
    if data.hotel_name not in common.strip_tags(source):
        errors.append("ホテル名なし")
    if source.count(data.basic.official_url) < 2:
        errors.append("公式URL不整合")
    parser = common.BalanceParser()
    parser.feed(source)
    errors.extend(parser.errors[:5])
    if parser.stack:
        errors.append("未終了タグ: " + ", ".join(parser.stack[-5:]))
    return errors


def public_php_content() -> str:
    return common.public_php_content()


def dataset_content() -> str:
    return common.dataset_content()


def bundle_paths(hp_root: Path, slug: str) -> tuple[Path, Path, Path]:
    return (
        hp_root / f"kagoshima-deliveryhealth-hotel-{slug}.php",
        hp_root / "source" / f"kagoshima-deliveryhealth-hotel-{slug}.html",
        hp_root / "includefile" / f"dataset_kagoshima-deliveryhealth-hotel-{slug}.php",
    )


def update_dataset_base(source: str, slug: str) -> str:
    html_name = f"kagoshima-deliveryhealth-hotel-{slug}.html"
    php_name = f"kagoshima-deliveryhealth-hotel-{slug}.php"
    dataset_name = f"dataset_kagoshima-deliveryhealth-hotel-{slug}.php"
    case_block = f"\tcase '{html_name}':\n\t\tinclude(INCLUDE_DIR . '{dataset_name}');\n\t\tbreak;\n\n"
    if source.count(f"case '{html_name}':") > 1:
        raise HotelToolError("dataset_base case重複")
    if f"case '{html_name}':" not in source:
        source = common.replace_exact(source, r"(?m)^\tcase 'area\.html':", case_block + "\tcase 'area.html':", "dataset case")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    if source.count(conversion) > 1:
        raise HotelToolError("dataset_baseリンク変換重複")
    if conversion not in source:
        source = common.replace_exact(source, r"(?m)^\$source = str_replace\('area\.html'", conversion + "\n$source = str_replace('area.html'", "dataset link")
    return source


def hotel_schema(data: HotelData) -> dict[str, object]:
    postal = re.search(r"〒?\s*(\d{3}-\d{4})", data.basic.address)
    normalized_address = data.basic.address.replace("<改行>", " ").replace("\n", " ")
    address_text = re.sub(r"〒?\s*\d{3}-\d{4}\s*", "", normalized_address).strip()
    schema: dict[str, object] = {
        "@context": "https://schema.org",
        "@type": "Hotel",
        "name": data.hotel_name,
        "url": data.canonical,
        "address": {"@type": "PostalAddress", "streetAddress": address_text, "addressCountry": "JP"},
    }
    if data.rates:
        schema["priceRange"] = " / ".join(f"{label} {value}" for label, value in data.rates)
    if postal:
        schema["address"]["postalCode"] = postal.group(1)
    if data.basic.telephone:
        schema["telephone"] = data.basic.telephone
    return schema


def update_hotel_list(source: str, data: HotelData) -> str:
    php_name = f"kagoshima-deliveryhealth-hotel-{data.slug}.php"
    if source.count(f'./{php_name}') > 1 or source.count(data.canonical) > 1:
        raise HotelToolError("hotel一覧登録重複")
    address = common.htext(data.basic.address)
    phone = f' <br class="spOnly">TEL {common.htext(data.basic.telephone)}' if data.basic.telephone else ""
    rest = next((value for label, value in data.rates if label in {"休憩", "休憩等", "ショート", "フリー"}), None)
    stay = next((value for label, value in data.rates if label == "宿泊"), None)
    rate_lines = []
    if rest:
        rate_lines.append(f'<span class="lym_4">休憩料金目安 {common.htext(rest)}</span>')
    if stay:
        rate_lines.append(f'宿泊料金目安 {common.htext(stay)}')
    rates = '<br class="spOnly">'.join(rate_lines)
    map_link = (
        f'<a href="{common.hattr(data.access.map_src)}" target="_blank" rel="noopener noreferrer" class="fade lmr_10 fc_g">［MAP］</a>'
        if data.access
        else ""
    )
    rate_details = f'<br>\n\t\t\t\t\t\t{rates}' if rates else ""
    entry = (
        f'\t\t\t\t\t<div class="lpt_15 bd_t"><a href="./{php_name}" class="fs_md1 fade">{common.htext(data.hotel_name)}</a></div>\n'
        f'\t\t\t\t\t<div class="lp_10_0_15 f_xxs fc_g">{address}{map_link}{phone}{rate_details}</div>'
    )
    if f'./{php_name}' in source:
        source = common.replace_exact(
            source,
            (
                rf'(?ms)(?:^[ \t]*\r?\n)*^[ \t]*<div class="lpt_15 bd_t"><a href="\./{re.escape(php_name)}".*?</div>'
                r'\s*^[ \t]*<div class="lp_10_0_15 f_xxs fc_g">.*?</div>'
            ),
            "\n" + entry,
            "hotel list existing entry",
        )
    else:
        source = common.replace_exact(
            source,
            (
                r'(?ms)(^[ \t]*<div class="lp_0_55_35 w_1050 lm_30_auto bg_f">.*?</div>)'
                r'\r?\n(?:^[ \t]*\r?\n)*'
                r'(^[ \t]*</div>\r?\n^[ \t]*</div>\r?\n^[ \t]*\r?\n'
                r'^[ \t]*</div>\r?\n^<!-- メインコンテンツ END -->)'
            ),
            rf"\g<1>\n\n{entry}\n\g<2>",
            "hotel list insertion point",
        )
    script = '<script type="application/ld+json">\n' + json.dumps(hotel_schema(data), ensure_ascii=False, indent=2) + "\n</script>\n"
    escaped_script = script.replace("\\", "\\\\")
    if data.canonical in source:
        source = common.replace_exact(
            source,
            (
                r'(?ms)^<script type="application/ld\+json">\r?\n'
                r'(?:(?!^</script>).)*?'
                + re.escape(data.canonical)
                + r'(?:(?!^</script>).)*?^</script>\r?\n?'
            ),
            escaped_script,
            "hotel list existing JSON-LD",
        )
    else:
        source = common.replace_exact(source, r"(?m)^<!-- 構造化データ（JSON-LD） END -->", escaped_script + "<!-- 構造化データ（JSON-LD） END -->", "hotel list JSON-LD")
    return source


def shared_validation(data: HotelData, hp_root: Path) -> list[str]:
    errors: list[str] = []
    html_name = f"kagoshima-deliveryhealth-hotel-{data.slug}.html"
    php_name = f"kagoshima-deliveryhealth-hotel-{data.slug}.php"
    base = read_utf8(hp_root / "includefile" / "dataset_base.php")
    if base.count(f"case '{html_name}':") != 1:
        errors.append("dataset_base case登録が1件ではありません")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    if base.count(conversion) != 1:
        errors.append("dataset_baseリンク変換が1件ではありません")
    hotel_list = read_utf8(hp_root / "source" / "hotel.html")
    if hotel_list.count(f'./{php_name}') != 1:
        errors.append("hotel一覧リンクが1件ではありません")
    if hotel_list.count(data.canonical) != 1:
        errors.append("hotel一覧JSON-LDが1件ではありません")
    sitemap = read_utf8(hp_root / "sitemap.xml")
    if sitemap.count(f"<loc>{data.canonical}</loc>") != 1:
        errors.append("sitemap登録が1件ではありません")
    return errors


def run_build(args: argparse.Namespace) -> int:
    started = time.perf_counter()
    root = repo_root()
    hp_root = path_config.HP_ROOT
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = root / input_path
    data = parse_hotel_text(input_path)
    templates = common.load_shop_templates(hp_root / "source" / "template_shop.html")
    resolved = resolve_shops(data, hp_root, templates)
    rendered = render_source(data, resolved, templates, hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html")
    errors = validate_rendered(data, resolved, rendered, hp_root)
    if errors:
        raise HotelToolError("生成前検証失敗:\n- " + "\n- ".join(errors))
    public_path, source_path, dataset_path = bundle_paths(hp_root, data.slug)
    existing = [path for path in (public_path, source_path, dataset_path) if path.exists()]
    if existing and not args.dry_run and not args.force:
        raise HotelToolError("既存ファイルがあります: " + ", ".join(str(path) for path in existing))
    base_path = hp_root / "includefile" / "dataset_base.php"
    hotel_path = hp_root / "source" / "hotel.html"
    sitemap_path = hp_root / "sitemap.xml"
    new_base = update_dataset_base(read_utf8(base_path), data.slug)
    new_hotel = update_hotel_list(read_utf8(hotel_path), data)
    new_sitemap = common.update_sitemap(read_utf8(sitemap_path), data.canonical)
    if args.dry_run:
        print(f"RESULT=DRY_RUN_OK hotel={data.hotel_name} slug={data.slug}")
        print(f"COUNTS shops={len(resolved)} faqs={len(data.faqs)} rates={len(data.rates)} spots={len(data.spots)}")
        print(f"TIMING total={time.perf_counter()-started:.3f}s")
        return 0
    common.atomic_write(public_path, public_php_content())
    common.atomic_write(source_path, rendered)
    common.atomic_write(dataset_path, dataset_content())
    common.atomic_write(base_path, new_base)
    common.atomic_write(hotel_path, new_hotel)
    common.atomic_write(sitemap_path, new_sitemap)
    actual_errors = validate_rendered(data, resolved, read_utf8(source_path), hp_root)
    actual_errors.extend(shared_validation(data, hp_root))
    php_status, php_errors = common.php_lint([public_path, dataset_path, base_path])
    actual_errors.extend(php_errors)
    if actual_errors:
        raise HotelToolError("書込後検証失敗:\n- " + "\n- ".join(actual_errors))
    print(f"RESULT=BUILD_OK hotel={data.hotel_name} slug={data.slug}")
    changed_paths = (public_path, source_path, dataset_path, base_path, hotel_path, sitemap_path)
    print("FILES=" + ",".join(str(path.relative_to(root)) for path in changed_paths))
    print(f"COUNTS shops={len(resolved)} faqs={len(data.faqs)} rates={len(data.rates)} spots={len(data.spots)}")
    print(f"PHP_LINT={php_status}")
    print(f"TIMING total={time.perf_counter()-started:.3f}s")
    return 0


def run_check(args: argparse.Namespace) -> int:
    root = repo_root()
    hp_root = path_config.HP_ROOT
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = root / input_path
    data = parse_hotel_text(input_path)
    templates = common.load_shop_templates(hp_root / "source" / "template_shop.html")
    resolved = resolve_shops(data, hp_root, templates)
    public_path, source_path, dataset_path = bundle_paths(hp_root, data.slug)
    missing = [str(path) for path in (public_path, source_path, dataset_path) if not path.is_file()]
    if missing:
        raise HotelToolError("生成ファイル不足: " + ", ".join(missing))
    errors = validate_rendered(data, resolved, read_utf8(source_path), hp_root)
    errors.extend(shared_validation(data, hp_root))
    php_status, php_errors = common.php_lint([public_path, dataset_path, hp_root / "includefile" / "dataset_base.php"])
    errors.extend(php_errors)
    if args.require_php and php_status == "UNAVAILABLE":
        errors.append("PHP CLIがありません")
    if errors:
        raise HotelToolError("検証失敗:\n- " + "\n- ".join(errors))
    print(f"RESULT=CHECK_OK slug={data.slug}")
    print(f"PHP_LINT={php_status}")
    return 0


def run_audit(_: argparse.Namespace) -> int:
    root = repo_root()
    paths = sorted(path_config.TEXT_HOTEL_DIR.glob("*.txt"))
    parsed = 0
    failures: list[str] = []
    for path in paths:
        if "手順" in path.name:
            continue
        try:
            data = parse_hotel_text(path)
            parsed += 1
            print(f"INPUT_OK={path.relative_to(root)}|{data.slug}|{data.hotel_name}")
        except HotelToolError as exc:
            failures.append(f"{path.relative_to(root)}: {exc}")
    print(f"INPUTS={len(paths)} PARSED={parsed} STOPPED={len(failures)} SKIPPED_GUIDES={len(paths)-parsed-len(failures)}")
    for failure in failures:
        print(f"INPUT_STOP={failure}")
    return 1 if failures else 0


def run_self_test(_: argparse.Namespace) -> int:
    root = repo_root()
    hp_root = path_config.HP_ROOT
    input_path = path_config.TEXT_HOTEL_DIR / "グリーンリッチホテル鹿児島天文館.txt"
    data = parse_hotel_text(input_path)
    templates = common.load_shop_templates(hp_root / "source" / "template_shop.html")
    resolved = resolve_shops(data, hp_root, templates)
    source = render_source(data, resolved, templates, hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html")
    errors = validate_rendered(data, resolved, source, hp_root)
    if errors:
        raise HotelToolError("self-test失敗:\n- " + "\n- ".join(errors))
    if (len(resolved), len(data.faqs), len(data.rates), len(data.spots)) != (4, 4, 2, 4):
        raise HotelToolError("self-test count mismatch")
    if len(path_config.related_link_targets(source)) != path_config.RELATED_COUNT:
        raise HotelToolError("related link self-test failed")
    broken_related = re.sub(r'<a href="\./kagoshima-deliveryhealth-(?:blog|area)-[^"]+" class="fade">.*?</a><br>\s*', "", source, count=1)
    if not related_validation(broken_related, data.canonical):
        raise HotelToolError("related negative self-test failed")
    broken_terminal_cta = source.replace(render_terminal_shop_cta(), "", 1)
    if not terminal_shop_cta_validation(broken_terminal_cta):
        raise HotelToolError("terminal shop CTA negative self-test failed")
    faqless = replace(data, faqs=[], scene_order=[token for token in data.scene_order if token != "faqs"])
    faqless_source = render_source(faqless, resolved, templates, hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html")
    faqless_errors = validate_rendered(faqless, resolved, faqless_source, hp_root)
    if faqless_errors:
        raise HotelToolError("faqless self-test failed: " + "; ".join(faqless_errors))
    base_text = read_utf8(input_path)
    base_lines = base_text.splitlines()
    first_marker = next(line for line in base_lines if SCENE_MARKER_RE.fullmatch(line.strip()))
    candy_index = next(index for index, line in enumerate(base_lines) if "CANDY" in line)
    duplicate_shop_lines = list(base_lines)
    duplicate_shop_lines[candy_index + 1] = duplicate_shop_lines[candy_index]
    faq_marker = "scene（h2 / よくあるご質問）"
    pattern_text = base_text.replace(
        first_marker,
        "option\n"
        "ホテル固有案内\n\n"
        "option_subtitle\n"
        "固有案内の要点\n"
        "option_description\n"
        "固有案内の本文\n"
        "------------------------------------------------------------\n"
        "scene（h2）\n"
        "店舗案内前の記事\n\n"
        "subtitle_\n"
        "前記事の要点\n"
        "description_\n"
        "前記事の本文\n"
        "------------------------------------------------------------\n"
        + first_marker,
        1,
    )
    pattern_text = pattern_text.replace(
        faq_marker,
        "scene（h2）\n"
        "店舗案内後の記事\n\n"
        "subtitle_\n"
        "後記事の要点\n"
        "description_\n"
        "後記事の本文\n"
        "------------------------------------------------------------\n"
        + faq_marker,
        1,
    )
    pattern_text = pattern_text.replace(
        "延長\n要お問い合わせ\n-------------------------------------------------------------------",
        "延長\n要お問い合わせ\n\ndescription_\n料金情報の補足文\n-------------------------------------------------------------------",
        1,
    )
    pattern_text = pattern_text.rstrip() + "\n\ndescription_\nText指定の周辺スポット注意文\n"
    sparse_text = base_text
    sparse_patterns = (
        (r"(?s)scene（h2 / よくあるご質問）.*?(?=img_2\s*\n)", ""),
        (r"(?s)scene（h2）\n料金情報.*?(?=scene（h2）\nアクセス情報)", ""),
        (r"(?s)scene（h2）\nアクセス情報.*?(?=scene（h2）\n[^\n]*周辺スポット)", ""),
        (r"(?s)scene（h2）\n[^\n]*周辺スポット.*$", ""),
        (r"(?m)^・Sチャンネル.*(?:\n|$)", ""),
        (r"(?m)^・REBORN.*(?:\n|$)", ""),
        (r"(?m)^・楊貴妃.*(?:\n|$)", ""),
        (r"(?m)^電話番号\n[^\n]+\n\n", ""),
        (r"(?m)^部屋・駐車場\n[^\n]+\n\n", ""),
        (r"(?m)^支払方法\n[^\n]+(?:\n|$)", ""),
    )
    for pattern, replacement in sparse_patterns:
        sparse_text, count = re.subn(pattern, replacement, sparse_text, count=1)
        if count != 1:
            raise HotelToolError(f"sparse fixture replacement failed: {pattern}")
    variable_cases: list[tuple[str, HotelData]] = []
    for count in range(1, len(data.shops) + 1):
        variable_cases.append((f"shops-{count}", replace(data, shops=data.shops[:count])))
    for count in range(0, 7):
        articles = [TextBlock(f"Article heading {index}", f"Article body {index}") for index in range(1, count + 1)]
        order = [f"article:{index}" for index in range(count)] + data.scene_order
        variable_cases.append(
            (f"article-scenes-{count}", replace(data, article_scenes=articles, scene_order=order))
        )
    for count in range(0, 7):
        faqs = [TextBlock(f"Question {index}", f"Answer {index}") for index in range(1, count + 1)]
        order = [token for token in data.scene_order if token != "faqs" or count]
        variable_cases.append((f"faqs-{count}", replace(data, faqs=faqs, scene_order=order)))
    for count in range(0, len(RATE_LABELS) + 1):
        rates = [(label, f"Rate {index}") for index, label in enumerate(RATE_LABELS[:count], start=1)]
        order = [token for token in data.scene_order if token != "rates" or count]
        variable_cases.append((f"rates-{count}", replace(data, rates=rates, rate_note=None, scene_order=order)))
    variable_cases.append(
        ("access-0", replace(data, access=None, scene_order=[token for token in data.scene_order if token != "access"]))
    )
    for count in range(0, 7):
        spots = [
            common.Place(
                title=f"Nearby spot {index}",
                address=f"Test address {index}",
                telephone=None,
                url=f"https://example.com/spot-{index}",
            )
            for index in range(1, count + 1)
        ]
        order = [token for token in data.scene_order if token != "spots" or count]
        variable_cases.append((f"spots-{count}", replace(data, spots=spots, scene_order=order)))
    for mask in range(8):
        basic = replace(
            data.basic,
            telephone=data.basic.telephone if mask & 1 else None,
            room_parking=data.basic.room_parking if mask & 2 else None,
            payment=data.basic.payment if mask & 4 else None,
        )
        variable_cases.append((f"basic-optional-mask-{mask}", replace(data, basic=basic)))
    for name, variant in variable_cases:
        variant_resolved = resolve_shops(variant, hp_root, templates)
        variant_source = render_source(
            variant, variant_resolved, templates,
            hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html",
        )
        variant_errors = validate_rendered(variant, variant_resolved, variant_source, hp_root)
        if variant_errors:
            raise HotelToolError(f"variable count self-test failed ({name}): " + "; ".join(variant_errors))

    fallback_shops = [
        replace(request, time_text=None, fee_text=None, source="text")
        if request.key == "schannel" else request
        for request in data.shops
    ]
    fallback_data = replace(data, shops=fallback_shops)
    fallback_resolved = resolve_shops(fallback_data, hp_root, templates)
    fallback = next(item for item in fallback_resolved if item.key == "schannel")
    if fallback.source != "nearest-missing-text" or not fallback.reference or fallback.distance_km is None:
        raise HotelToolError("nearest travel fallback self-test failed")
    fallback_source = render_source(
        fallback_data, fallback_resolved, templates,
        hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html",
    )
    fallback_errors = validate_rendered(fallback_data, fallback_resolved, fallback_source, hp_root)
    if fallback_errors:
        raise HotelToolError("fallback render self-test failed: " + "; ".join(fallback_errors))
    cases = {
        "wrong_host": base_text.replace(data.canonical, data.canonical.replace(CANONICAL_HOST, "evil.example"), 1),
        "slug_image_mismatch": base_text.replace(data.canonical, f"https://{CANONICAL_HOST}/kagoshima-deliveryhealth-hotel-fakehotel.php", 1),
        "duplicate_images": base_text.replace(data.image1, data.image2, 1),
        "unsafe_official_url": base_text.replace(data.basic.official_url, "javascript:alert(1)", 1),
        "other_placeholder": base_text.replace(data.basic.room_parking or "", "REPLACE_ME", 1),
        "duplicate_shop": "\n".join(duplicate_shop_lines),
        "partial_option": base_text.replace(
            first_marker,
            "option\nOptional Heading\noption_subtitle\nOnly subtitle\n\n" + first_marker,
            1,
        ),
        "partial_scene": base_text.replace(
            first_marker,
            "scene1\nOptional Scene\nsubtitle_\nOnly subtitle\n------------------------------------------------------------\n" + first_marker,
            1,
        ),
    }
    with tempfile.TemporaryDirectory() as directory:
        room_label_path = Path(directory) / "room_count_label.txt"
        room_label_path.write_text(base_text.replace("部屋・駐車場", "部屋数・駐車場", 1), encoding="utf-8")
        room_label_data = parse_hotel_text(room_label_path)
        if room_label_data.basic.room_parking != data.basic.room_parking:
            raise HotelToolError("room count label self-test failed")

        pattern_path = Path(directory) / "information_patterns.txt"
        pattern_path.write_text(pattern_text, encoding="utf-8")
        pattern_data = parse_hotel_text(pattern_path)
        if pattern_data.legacy_option is None or len(pattern_data.article_scenes) != 2:
            raise HotelToolError("information pattern parse self-test failed")
        if pattern_data.scene_order[:4] != ["article:0", "shops", "article:1", "faqs"]:
            raise HotelToolError(f"scene order parse self-test failed: {pattern_data.scene_order}")
        if pattern_data.rate_note != "料金情報の補足文":
            raise HotelToolError("rate note parse self-test failed")
        if pattern_data.spot_note != "Text指定の周辺スポット注意文":
            raise HotelToolError("spot note parse self-test failed")
        pattern_resolved = resolve_shops(pattern_data, hp_root, templates)
        pattern_source = render_source(
            pattern_data, pattern_resolved, templates,
            hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html",
        )
        for expected in (
            'id="option"',
            "ホテル固有案内",
            'id="scene1">店舗案内前の記事',
            'id="scene2"',
            'id="scene3">店舗案内後の記事',
            "料金情報の補足文",
            "Text指定の周辺スポット注意文",
        ):
            if expected not in pattern_source:
                raise HotelToolError(f"information pattern render self-test failed: {expected}")
        pattern_errors = validate_rendered(pattern_data, pattern_resolved, pattern_source, hp_root)
        if pattern_errors:
            raise HotelToolError("information pattern validation self-test failed: " + "; ".join(pattern_errors))

        sparse_path = Path(directory) / "sparse_counts.txt"
        sparse_path.write_text(sparse_text, encoding="utf-8")
        sparse_data = parse_hotel_text(sparse_path)
        if sparse_data.scene_order != ["shops", "basic"]:
            raise HotelToolError(f"sparse scene order self-test failed: {sparse_data.scene_order}")
        if (
            len(sparse_data.shops) != 1
            or sparse_data.faqs
            or sparse_data.basic.telephone is not None
            or sparse_data.basic.room_parking is not None
            or sparse_data.basic.payment is not None
            or sparse_data.rates
            or sparse_data.access is not None
            or sparse_data.spots
        ):
            raise HotelToolError("sparse count parse self-test failed")
        sparse_resolved = resolve_shops(sparse_data, hp_root, templates)
        sparse_source = render_source(
            sparse_data, sparse_resolved, templates,
            hp_root / "source" / "template_kagoshima-deliveryhealth-hotel.html",
        )
        sparse_errors = validate_rendered(sparse_data, sparse_resolved, sparse_source, hp_root)
        if sparse_errors:
            raise HotelToolError("sparse count validation self-test failed: " + "; ".join(sparse_errors))
        if len(path_config.related_link_targets(sparse_source)) != path_config.RELATED_COUNT:
            raise HotelToolError("sparse related link self-test failed")
        sparse_hotel_list_fixture = (
            '<!-- 構造化データ（JSON-LD） END -->\n'
            '<!-- メインコンテンツ START -->\n'
            '<div class="lp_0_7">\n'
            '\t<div class="lp_0_55_35 w_1050 lm_30_auto bg_f">\n'
            '\t\t<div class="lp_10_0 lm_0_auto w_130 center bg_p fs_xs fc_w">HOTEL INFO</div>\n'
            '\t</div>\n'
            '</div>\n'
            '\n'
            '</div>\n'
            '<!-- メインコンテンツ END -->'
        )
        sparse_hotel_list = update_hotel_list(sparse_hotel_list_fixture, sparse_data)
        sparse_php_name = f"kagoshima-deliveryhealth-hotel-{sparse_data.slug}.php"
        sparse_entry = re.search(
            rf'(?s)<div class="lpt_15 bd_t"><a href="\./{re.escape(sparse_php_name)}".*?</div>\s*'
            r'<div class="lp_10_0_15 f_xxs fc_g">(.*?)</div>',
            sparse_hotel_list,
        )
        if (
            not sparse_entry
            or common.htext(sparse_data.basic.address) not in sparse_entry.group(1)
            or "［MAP］" in sparse_entry.group(1)
            or "TEL " in sparse_entry.group(1)
            or "料金目安" in sparse_entry.group(1)
        ):
            raise HotelToolError("sparse hotel list self-test failed")
        if "priceRange" in hotel_schema(sparse_data):
            raise HotelToolError("sparse hotel schema self-test failed")
        for name, case_text in cases.items():
            case_path = Path(directory) / f"{name}.txt"
            case_path.write_text(case_text, encoding="utf-8")
            try:
                parse_hotel_text(case_path)
            except HotelToolError:
                continue
            raise HotelToolError(f"negative self-test accepted invalid input: {name}")
    print("RESULT=SELF_TEST_OK")
    hotel_list_source = update_hotel_list(read_utf8(hp_root / "source" / "hotel.html"), data)
    php_name = f"kagoshima-deliveryhealth-hotel-{data.slug}.php"
    if hotel_list_source.count(f"./{php_name}") != 1 or hotel_list_source.count(data.canonical) != 1:
        raise HotelToolError("hotel list self-test failed")
    for block in re.findall(r'(?s)<script type="application/ld\+json">\s*(.*?)\s*</script>', hotel_list_source):
        json.loads(block)

    print(
        f"HOTEL={data.hotel_name} SLUG={data.slug} "
        f"SHOPS={len(data.shops)} FAQS={len(data.faqs)} "
        f"RATES={len(data.rates)} SPOTS={len(data.spots)}"
    )
    return 0


def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="CANDY hotel page builder/validator")
    commands = parser.add_subparsers(dest="command", required=True)
    build = commands.add_parser("build")
    build.add_argument("--input", required=True)
    build.add_argument("--dry-run", action="store_true")
    build.add_argument("--force", action="store_true")
    build.set_defaults(func=run_build)
    check = commands.add_parser("check")
    check.add_argument("--input", required=True)
    check.add_argument("--require-php", action="store_true")
    check.set_defaults(func=run_check)
    audit = commands.add_parser("audit-inputs")
    audit.set_defaults(func=run_audit)
    self_test = commands.add_parser("self-test")
    self_test.set_defaults(func=run_self_test)
    return parser


def main() -> int:
    for stream in (sys.stdout, sys.stderr):
        if hasattr(stream, "reconfigure"):
            stream.reconfigure(encoding="utf-8", errors="backslashreplace")
    args = create_parser().parse_args()
    try:
        return args.func(args)
    except (HotelToolError, common.AreaToolError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
