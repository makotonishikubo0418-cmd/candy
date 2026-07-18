#!/usr/bin/env python3
"""Shared deterministic helpers for CANDY hotel/blog page tooling."""

from __future__ import annotations

import html
import json
import os
import re
import shutil
import subprocess
import tempfile
from datetime import date
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urlparse


RELATED_TEXT = "ここにはリンク先のタイトルを表示します。"
RELATED_LINK = f'<a href="#" class="fade">{RELATED_TEXT}</a>'
RELATED_COUNT = 8
SEPARATOR_RE = re.compile(r"^(?:-{10,}|━{10,})\s*$")
SCENE_RE = re.compile(r"^scene（h2(?: / [^)]+)?）\s*$")
PLACEHOLDER_RE = re.compile(r"a{6,}|(?:未入力|未確定|TODO|TBD)", re.I)


class PageToolError(RuntimeError):
    pass


def _find_repo_root(start: Path) -> Path:
    candidate = start.resolve()
    while True:
        if (candidate / ".git").exists() or (
            (candidate / "AGENTS.md").is_file() and (candidate / "HP").is_dir()
        ):
            return candidate
        if candidate.parent == candidate:
            break
        candidate = candidate.parent
    raise PageToolError(f"CANDY repository root was not found above: {start}")


SCRIPTS_DIR = Path(__file__).resolve().parent
REPO_ROOT = _find_repo_root(SCRIPTS_DIR)
HP_ROOT = REPO_ROOT / "HP"
TEXT_AREA_DIR = REPO_ROOT / "Text_area_data"
TEXT_HOTEL_DIR = REPO_ROOT / "Text_hotel_data"
TEXT_BLOG_DIR = REPO_ROOT / "Text_blog_data"
DOCS_DIR = REPO_ROOT / "codex" / "docs"
SITE_STATE_OUTPUT_NAMES = (
    "CANDY_SITE_PAGE_LEDGER.md",
    "CANDY_UPCOMING_PAGES.md",
    "CANDY_CODE_ASSET_INVENTORY.md",
    "CANDY_SEO_STATUS.md",
)


def repo_root() -> Path:
    return REPO_ROOT


def hp_root() -> Path:
    return HP_ROOT


def site_state_output_paths() -> list[Path]:
    return [DOCS_DIR / "generated" / name for name in SITE_STATE_OUTPUT_NAMES]


def read_utf8(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8-sig").replace("\r\n", "\n")
    except UnicodeDecodeError as exc:
        raise PageToolError(f"UTF-8で読めません: {path}") from exc


def is_separator(value: str) -> bool:
    return bool(SEPARATOR_RE.fullmatch(value.strip()))


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
        raise PageToolError("scene（h2）がありません")
    return [lines[start : starts[offset + 1] if offset + 1 < len(starts) else len(lines)] for offset, start in enumerate(starts)]


def first_scene_heading(section: list[str]) -> str:
    for value in section[1:]:
        stripped = value.strip()
        if stripped:
            return re.sub(r'^id="scene\d+"\s*:\s*', "", stripped)
    raise PageToolError("scene見出しがありません")


def repeated_pairs(section: list[str], first_label: str = "subtitle_", second_label: str = "description_") -> list[tuple[str, str]]:
    starts = [i for i, value in enumerate(section) if value.strip() == first_label]
    pairs: list[tuple[str, str]] = []
    for offset, start in enumerate(starts):
        end = starts[offset + 1] if offset + 1 < len(starts) else len(section)
        second = next((i for i in range(start + 1, end) if section[i].strip() == second_label), None)
        if second is None:
            raise PageToolError(f"{first_label}に対応する{second_label}がありません")
        left = "\n".join(value.strip() for value in section[start + 1 : second] if value.strip())
        right_lines: list[str] = []
        for value in section[second + 1 : end]:
            if is_separator(value):
                break
            if value.strip():
                right_lines.append(value.strip())
        right = "\n".join(right_lines)
        if not left or not right:
            raise PageToolError(f"{first_label}/{second_label}の値が不足しています")
        pairs.append((left, right))
    return pairs


def ensure_value(name: str, value: str) -> str:
    if not value:
        raise PageToolError(f"必須項目不足: {name}")
    if PLACEHOLDER_RE.search(value):
        raise PageToolError(f"placeholderが残っています: {name}")
    return value


def slug_from_canonical(canonical: str, category: str) -> str:
    path = urlparse(canonical).path
    match = re.fullmatch(rf"/kagoshima-deliveryhealth-{category}-([a-z0-9-]+)\.php", path)
    if not match:
        raise PageToolError(f"canonicalが{category}形式ではありません")
    return match.group(1)


def htext(value: str) -> str:
    return "<br>".join(html.escape(item.strip()) for item in value.replace("<改行>", "\n").splitlines() if item.strip())


def hattr(value: str) -> str:
    return html.escape(value, quote=True)


def replace_one(source: str, pattern: str, replacement: str, label: str, flags: int = 0) -> str:
    result, count = re.subn(pattern, lambda _match: replacement, source, count=1, flags=flags)
    if count != 1:
        raise PageToolError(f"置換位置が一意ではありません: {label} ({count})")
    return result


def json_scripts(objects: list[dict[str, object]]) -> str:
    return "\n".join(
        '<script type="application/ld+json">\n'
        + json.dumps(item, ensure_ascii=False, indent=2)
        + "\n</script>"
        for item in objects
    )


def render_template_shell(
    template_path: Path,
    *,
    title: str,
    description: str,
    canonical: str,
    og_image: str,
    structured_data: list[dict[str, object]],
    main_html: str,
) -> str:
    source = read_utf8(template_path)
    replacements = (
        (r'<meta name="description" content="[^"]*">', f'<meta name="description" content="{hattr(description)}">', "description"),
        (r"<title>.*?</title>", f"<title>{html.escape(title)}</title>", "title"),
        (r'<link rel="canonical" href="[^"]*">', f'<link rel="canonical" href="{hattr(canonical)}">', "canonical"),
        (r'<meta property="og:title" content="[^"]*">', f'<meta property="og:title" content="{hattr(title)}">', "og:title"),
        (r'<meta property="og:url" content="[^"]*">', f'<meta property="og:url" content="{hattr(canonical)}">', "og:url"),
        (r'<meta property="og:image" content="[^"]*">', f'<meta property="og:image" content="{hattr(og_image)}">', "og:image"),
        (r'<meta property="og:description" content="[^"]*">', f'<meta property="og:description" content="{hattr(description)}">', "og:description"),
    )
    for pattern, replacement, label in replacements:
        source = replace_one(source, pattern, replacement, label, re.S)
    source = replace_one(
        source,
        r"<!-- 構造化データ（JSON-LD） START -->.*?<!-- 構造化データ（JSON-LD） END -->",
        "<!-- 構造化データ（JSON-LD） START -->\n" + json_scripts(structured_data) + "\n<!-- 構造化データ（JSON-LD） END -->",
        "JSON-LD",
        re.S,
    )
    source = replace_one(
        source,
        r"<!-- メインコンテンツ START -->.*?<!-- メインコンテンツ END -->",
        "<!-- メインコンテンツ START -->\n" + main_html.rstrip() + "\n<!-- メインコンテンツ END -->",
        "main content",
        re.S,
    )
    if re.search(r"a{6,}", source, re.I):
        raise PageToolError("生成HTMLにtemplate placeholderが残っています")
    return source.rstrip() + "\n"


def related_links_html(border_class: str = "bd") -> str:
    links = "<br>\n".join(f"\t\t\t\t\t{RELATED_LINK}" for _ in range(RELATED_COUNT))
    return (
        f'\t\t\t\t<div class="lmt_20 lp_40 {border_class}">\n'
        '\t\t\t\t<h3 class="lpb_10 fs_l">関連記事</h3>\n'
        '\t\t\t\t<div class="fs_md3">\n'
        f"{links}\n"
        "\t\t\t\t</div>\n\t\t\t\t</div>"
    )


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


def bundle_paths(category: str, slug: str) -> tuple[Path, Path, Path]:
    hp = hp_root()
    stem = f"kagoshima-deliveryhealth-{category}-{slug}"
    return hp / f"{stem}.php", hp / "source" / f"{stem}.html", hp / "includefile" / f"dataset_{stem}.php"


def update_dataset_base(source: str, category: str, slug: str) -> str:
    html_name = f"kagoshima-deliveryhealth-{category}-{slug}.html"
    php_name = f"kagoshima-deliveryhealth-{category}-{slug}.php"
    dataset_name = f"dataset_kagoshima-deliveryhealth-{category}-{slug}.php"
    case_count = source.count(f"case '{html_name}':")
    if case_count > 1:
        raise PageToolError(f"dataset_base case重複: {case_count}")
    if case_count == 0:
        block = f"\tcase '{html_name}':\n\t\tinclude(INCLUDE_DIR . '{dataset_name}');\n\t\tbreak;\n\n"
        source = replace_one(source, r"(?m)^\tcase 'area\.html':", block + "\tcase 'area.html':", "dataset case")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    conversion_count = source.count(conversion)
    if conversion_count > 1:
        raise PageToolError(f"dataset_baseリンク変換重複: {conversion_count}")
    if conversion_count == 0:
        source = replace_one(
            source,
            r"(?m)^\$source = str_replace\('area\.html'",
            conversion + "\n$source = str_replace('area.html'",
            "dataset conversion",
        )
    return source


def update_sitemap(source: str, canonical: str) -> str:
    count = source.count(f"<loc>{canonical}</loc>")
    if count > 1:
        raise PageToolError(f"sitemap URL重複: {count}")
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
    return replace_one(source, r"(?m)^</urlset>", entry + "</urlset>", "sitemap")


def insert_before_button(block: str, entry: str) -> str:
    match = re.search(r'(?m)^(\s*<div class="center"><a href="\./(?:hotel|blog)\.php".*)$', block)
    if not match:
        match = re.search(r'(?m)^(\s*<div[^>]+id="button_[^"]+".*)$', block)
    if not match:
        raise PageToolError("一覧挿入位置がありません")
    return block[: match.start()] + entry.rstrip() + "\n" + block[match.start() :]


def update_blog_registries(blog_source: str, index_source: str, slug: str, title: str) -> tuple[str, str]:
    php_name = f"kagoshima-deliveryhealth-blog-{slug}.php"
    href = f"./{php_name}"
    for label, source in (("blog一覧", blog_source), ("indexブログ", index_source)):
        if source.count(href) > 1:
            raise PageToolError(f"{label}リンク重複")
    if href not in blog_source:
        entry = f'\t\t\t\t<div class="lp_20_0 fs_md3 bd_t"><a href="{href}" class="fade">{html.escape(title)}</a></div>'
        pattern = r'(<div class="lp_5 [^>]*>BLOG INFO</div>)(.*?)(\n\s*</div>\n\s*<div[^>]+id="button_)'
        match = re.search(pattern, blog_source, re.S)
        if not match:
            raise PageToolError("blog一覧挿入位置がありません")
        body = match.group(2).rstrip() + "\n" + entry
        blog_source = blog_source[: match.start()] + match.group(1) + body + match.group(3) + blog_source[match.end() :]
    if href not in index_source:
        start = index_source.find("<!-- スタッフブログ START -->")
        end = index_source.find("<!-- スタッフブログ END -->", start)
        if start < 0 or end < 0:
            raise PageToolError("indexブログ領域がありません")
        block = index_source[start:end]
        entry = f'\t\t\t<div class="lp_14_0 fs_sm2 bd_t"><a href="{href}" class="fade">{html.escape(title)}</a></div>'
        block = insert_before_button(block, entry)
        index_source = index_source[:start] + block + index_source[end:]
    return blog_source, index_source


def update_hotel_registries(
    hotel_source: str,
    index_source: str,
    *,
    slug: str,
    name: str,
    postal: str,
    address: str,
    telephone: str,
    map_url: str,
    rate_lines: list[tuple[str, str]],
    schema: dict[str, object],
) -> tuple[str, str]:
    php_name = f"kagoshima-deliveryhealth-hotel-{slug}.php"
    href = f"./{php_name}"
    if hotel_source.count(href) > 1 or index_source.count(href) > 1:
        raise PageToolError("hotel一覧リンク重複")
    if href not in hotel_source:
        values = {label: value for label, value in rate_lines}
        rest = values.get("休憩", "記載なし")
        stay = values.get("宿泊", "記載なし")
        entry = (
            f'\t\t\t\t\t<div class="lpt_15 bd_t"><a href="{href}" class="fs_md1 fade">{html.escape(name)}</a></div>\n'
            f'\t\t\t\t\t<div class="lp_10_0_15 f_xxs fc_g">{html.escape(postal)} {html.escape(address)}'
            f'<a href="{hattr(map_url)}" target="_blank" rel="noopener noreferrer" class="fade lmr_10 fc_g">［MAP］</a> '
            f'<br class="spOnly">TEL {html.escape(telephone)}<br>\n'
            f'\t\t\t\t\t\t<span class="lym_4">休憩料金目安 {html.escape(rest)}</span><br class="spOnly">宿泊料金目安 {html.escape(stay)}</div>'
        )
        placeholder = re.compile(
            r'\s*<div class="lpt_15 bd_t"><a href="\./kagoshima-deliveryhealth-hotel-aaaaaaaaaa\.php".*?</div>\s*'
            r'<div class="lp_10_0_15 f_xxs fc_g">.*?</div>',
            re.S,
        )
        if placeholder.search(hotel_source):
            hotel_source = placeholder.sub("\n" + entry, hotel_source, count=1)
        else:
            marker = '<div class="lp_10_0_15 f_xxs fc_g">'
            positions = [item.start() for item in re.finditer(re.escape(marker), hotel_source)]
            if not positions:
                raise PageToolError("hotel一覧挿入位置がありません")
            container_end = hotel_source.find("\n\t\t\t\t</div>", positions[-1])
            if container_end < 0:
                raise PageToolError("hotel一覧終端がありません")
            hotel_source = hotel_source[:container_end] + "\n" + entry + hotel_source[container_end:]
        script = '<script type="application/ld+json">\n' + json.dumps(schema, ensure_ascii=False, indent=2) + "\n</script>\n"
        hotel_source = replace_one(
            hotel_source,
            r"<!-- 構造化データ（JSON-LD） END -->",
            script + "<!-- 構造化データ（JSON-LD） END -->",
            "hotel JSON-LD",
        )
    if href not in index_source:
        start = index_source.find("<!-- 対応ホテル情報 START -->")
        end = index_source.find("<!-- 対応ホテル情報 END -->", start)
        if start < 0 or end < 0:
            raise PageToolError("indexホテル領域がありません")
        block = index_source[start:end]
        plain_pattern = re.compile(rf'(<div class="[^"]*">){re.escape(name)}(</div>)')
        if plain_pattern.search(block):
            block = plain_pattern.sub(rf'\1<a href="{href}" class="fade">{html.escape(name)}</a>\2', block, count=1)
        else:
            entry = f'\t\t\t<div class="lp_14_0 fs_sm2 bd_t"><a href="{href}" class="fade">{html.escape(name)}</a></div>'
            block = insert_before_button(block, entry)
        index_source = index_source[:start] + block + index_source[end:]
    return hotel_source, index_source


class BalanceParser(HTMLParser):
    VOID = {"area", "base", "br", "col", "embed", "hr", "img", "input", "link", "meta", "param", "source", "track", "wbr"}

    def __init__(self) -> None:
        super().__init__(convert_charrefs=True)
        self.stack: list[str] = []
        self.errors: list[str] = []

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        if tag not in self.VOID:
            self.stack.append(tag)

    def handle_endtag(self, tag: str) -> None:
        if not self.stack or self.stack[-1] != tag:
            self.errors.append(f"終了タグ不整合: {tag}")
            return
        self.stack.pop()


def validate_json_ld(source: str) -> list[str]:
    errors: list[str] = []
    scripts = re.findall(r'<script type="application/ld\+json">\s*(.*?)\s*</script>', source, re.S)
    if not scripts:
        return ["JSON-LDがありません"]
    for index, payload in enumerate(scripts, 1):
        try:
            json.loads(payload)
        except json.JSONDecodeError as exc:
            errors.append(f"JSON-LD {index}構文エラー: {exc}")
    return errors


def validate_html_common(source: str, canonical: str, expected_images: list[str]) -> list[str]:
    errors = validate_json_ld(source)
    if f'<link rel="canonical" href="{canonical}">' not in source:
        errors.append("canonical不一致")
    if re.search(r"a{6,}", source, re.I):
        errors.append("placeholder残存")
    ids = re.findall(r'\bid="([^"]+)"', source)
    duplicates = sorted({item for item in ids if ids.count(item) > 1})
    if duplicates:
        errors.append("ID重複: " + ",".join(duplicates))
    if source.count(RELATED_LINK) != RELATED_COUNT:
        errors.append(f"関連記事ダミーが{RELATED_COUNT}件ではありません")
    hp = hp_root()
    for image in expected_images:
        relative = image.removeprefix("./")
        if not (hp / relative).is_file():
            errors.append(f"画像がありません: {relative}")
        if image not in source and relative not in source:
            errors.append(f"画像参照がありません: {relative}")
    parser = BalanceParser()
    parser.feed(source)
    errors.extend(parser.errors[:5])
    if parser.stack:
        errors.append("未終了タグ: " + ",".join(parser.stack[-5:]))
    return errors


def shared_validation(category: str, slug: str, canonical: str) -> list[str]:
    hp = hp_root()
    html_name = f"kagoshima-deliveryhealth-{category}-{slug}.html"
    php_name = f"kagoshima-deliveryhealth-{category}-{slug}.php"
    base = read_utf8(hp / "includefile" / "dataset_base.php")
    errors: list[str] = []
    if base.count(f"case '{html_name}':") != 1:
        errors.append("dataset_base case登録が1件ではありません")
    conversion = f"$source = str_replace('{html_name}', '{php_name}', $source);"
    if base.count(conversion) != 1:
        errors.append("dataset_baseリンク変換が1件ではありません")
    if read_utf8(hp / "sitemap.xml").count(f"<loc>{canonical}</loc>") != 1:
        errors.append("sitemap登録が1件ではありません")
    for name in (category, "index"):
        source = read_utf8(hp / "source" / f"{name}.html")
        if source.count(f"./{php_name}") != 1:
            errors.append(f"{name}一覧リンクが1件ではありません")
    return errors


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
        if result.returncode:
            errors.append(f"{path}: {(result.stdout + result.stderr).strip()}")
    return ("PASSED" if not errors else "FAILED"), errors
