#!/usr/bin/env python3
"""Generate deterministic CANDY site-state documents from repository files."""

from __future__ import annotations

import argparse
import difflib
import hashlib
import html
import json
import os
import re
import subprocess
import sys
import tempfile
from collections import Counter, defaultdict
from dataclasses import dataclass
from pathlib import Path
from urllib.parse import urlparse

import candy_hotel_text_migration as hotel_text_migration

from candy_page_common import (
    DOCS_DIR,
    HP_ROOT,
    REPO_ROOT,
    TEXT_AREA_DIR,
    TEXT_BLOG_DIR,
    TEXT_HOTEL_DIR,
    SITE_STATE_OUTPUT_NAMES,
    atomic_write,
    read_utf8,
)


GENERATED_DIR = DOCS_DIR / "generated"
OUTPUT_NAMES = SITE_STATE_OUTPUT_NAMES
SCRIPT_REL = "codex/scripts/candy_site_state.py"
DETAIL_RE = re.compile(r"^kagoshima-deliveryhealth-(area|hotel|blog)-([a-z0-9-]+)$")
CANONICAL_RE = re.compile(
    r"https?://(?:www\.)?55810\.com/kagoshima-deliveryhealth-(area|hotel|blog)-([a-z0-9-]+)\.php",
    re.I,
)
TAG_RE = re.compile(r"<[^>]+>")
JSON_RE = re.compile(r'<script\s+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>', re.I | re.S)
LINK_RE = re.compile(r'(?:href|src)=["\']([^"\']+)["\']', re.I)
ANCHOR_LINK_RE = re.compile(r'<a\b[^>]*\bhref=["\']([^"\']+)["\']', re.I)
SCRIPT_SRC_RE = re.compile(r'<script\b[^>]*\bsrc=["\']([^"\']+)["\']', re.I)
CSS_URL_RE = re.compile(r"url\(\s*['\"]?([^)'\"]+)", re.I)
ASSET_EXTENSIONS = {
    ".css",
    ".js",
    ".jpg",
    ".jpeg",
    ".png",
    ".gif",
    ".svg",
    ".webp",
    ".mp4",
    ".webm",
    ".mov",
    ".avi",
    ".ttf",
    ".otf",
    ".woff",
    ".woff2",
    ".eot",
}
IMAGE_EXTENSIONS = {".jpg", ".jpeg", ".png", ".gif", ".svg", ".webp"}
VIDEO_EXTENSIONS = {".mp4", ".webm", ".mov", ".avi"}
FONT_EXTENSIONS = {".ttf", ".otf", ".woff", ".woff2", ".eot"}
SPECIAL_STEMS = {"create", "girls", "main", "makeSitemap", "movie_iframe", "page", "test"}
SYSTEM_STEMS = {"confirm", "contact", "login", "mypage", "system"}
SEO_HELPER_STEMS = {"create", "movie_iframe"}
SEO_ADMIN_STEMS = {"create"}
SOURCE_SCOPE = (
    "HP",
    "Text_area_data",
    "Text_blog_data",
    "Text_hotel_data",
    "codex/scripts/candy_hotel_text_migration.py",
    "codex/scripts/candy_site_state.py",
)
STATE_FINGERPRINT_EXCLUDED_HP_PARTS = {
    ".git",
    ".github",
    ".vscode",
    "codex",
    "log",
}
STATE_FINGERPRINT_EXCLUDED_HP_FILES = {"HP/AGENTS.md"}
STATE_FINGERPRINT_TEXT_SUFFIXES = {
    ".cmd",
    ".css",
    ".csv",
    ".htm",
    ".html",
    ".inc",
    ".js",
    ".json",
    ".php",
    ".py",
    ".svg",
    ".tsv",
    ".txt",
    ".xml",
}


@dataclass(frozen=True)
class TextRecord:
    category: str
    path: Path
    title: str
    page_name: str
    canonical: str
    slug: str
    required_missing: tuple[str, ...]
    image_refs: tuple[str, ...]
    source_format: str


def rel(path: Path) -> str:
    try:
        return path.resolve().relative_to(REPO_ROOT.resolve()).as_posix()
    except ValueError:
        return path.as_posix()


def md(value: object) -> str:
    text = str(value) if value not in (None, "") else "-"
    return text.replace("|", "\\|").replace("\r", " ").replace("\n", "<br>")


def strip_tags(value: str) -> str:
    return html.unescape(re.sub(r"\s+", " ", TAG_RE.sub(" ", value))).strip()


def first_match(source: str, pattern: str, flags: int = 0) -> str:
    match = re.search(pattern, source, flags)
    return strip_tags(match.group(1)) if match else ""


def git_value(*args: str) -> str:
    try:
        result = subprocess.run(
            ["git", "-C", str(REPO_ROOT), *args],
            check=True,
            capture_output=True,
            text=True,
            encoding="utf-8",
        )
        return result.stdout.strip()
    except (OSError, subprocess.CalledProcessError):
        return "UNVERIFIED"


def generation_base_head() -> tuple[str, str]:
    """Return a commit/time pair reproducible before and after one combined commit.

    While relevant inputs are dirty, the current HEAD is the generation base. Once
    those inputs and generated files are committed together, the same base is the
    parent of that source commit. This avoids a follow-up documentation-only commit.
    """
    current = git_value("rev-parse", "HEAD")
    status = git_value("status", "--porcelain=v1", "--", *SOURCE_SCOPE)
    base = current
    if status == "":
        source_commit = git_value("log", "-1", "--format=%H", "--", *SOURCE_SCOPE)
        if re.fullmatch(r"[0-9a-f]{40}", source_commit):
            changed = set(
                git_value("diff-tree", "--no-commit-id", "--name-only", "-r", source_commit).splitlines()
            )
            generated_paths = {f"codex/docs/generated/{name}" for name in OUTPUT_NAMES}
            if changed & generated_paths:
                parent = git_value("rev-parse", f"{source_commit}^")
                base = parent if re.fullmatch(r"[0-9a-f]{40}", parent) else source_commit
            else:
                base = source_commit
    timestamp = git_value("show", "-s", "--format=%cI", base)
    if not timestamp or timestamp == "UNVERIFIED":
        timestamp = "UNVERIFIED"
    return base, timestamp


def state_fingerprint_paths() -> list[Path]:
    paths: set[Path] = {
        Path(__file__).resolve(),
        Path(hotel_text_migration.__file__).resolve(),
    }
    for path in HP_ROOT.rglob("*"):
        if not path.is_file() or path.is_symlink():
            continue
        repository_path = rel(path)
        relative_parts = path.relative_to(HP_ROOT).parts
        if repository_path in STATE_FINGERPRINT_EXCLUDED_HP_FILES:
            continue
        if any(part.lower() in STATE_FINGERPRINT_EXCLUDED_HP_PARTS for part in relative_parts):
            continue
        paths.add(path.resolve())
    for root in (TEXT_AREA_DIR, TEXT_BLOG_DIR, TEXT_HOTEL_DIR):
        if not root.is_dir():
            continue
        paths.update(
            path.resolve()
            for path in root.rglob("*")
            if path.is_file() and not path.is_symlink()
        )
    return sorted(paths, key=lambda path: rel(path).casefold())


def fingerprint_for_paths(paths: list[Path], root: Path = REPO_ROOT) -> str:
    digest = hashlib.sha256()
    resolved_root = root.resolve()
    for path in sorted(paths, key=lambda item: item.resolve().as_posix().casefold()):
        resolved = path.resolve()
        try:
            repository_path = resolved.relative_to(resolved_root).as_posix()
        except ValueError as exc:
            raise RuntimeError(f"Fingerprint path is outside the repository: {path}") from exc
        data = resolved.read_bytes()
        if resolved.suffix.lower() in STATE_FINGERPRINT_TEXT_SUFFIXES:
            data = data.replace(b"\r\n", b"\n").replace(b"\r", b"\n")
        digest.update(repository_path.encode("utf-8"))
        digest.update(b"\0")
        digest.update(len(data).to_bytes(8, "big"))
        digest.update(data)
        digest.update(b"\0")
    return digest.hexdigest()


def parse_labeled(source: str, label: str) -> str:
    lines = source.replace("\r\n", "\n").splitlines()
    for index, line in enumerate(lines):
        if line.strip() != label:
            continue
        values: list[str] = []
        for candidate in lines[index + 1 :]:
            stripped = candidate.strip()
            if re.fullmatch(r"[-━]{10,}", stripped):
                break
            if stripped:
                values.append(stripped)
        return " ".join(values).strip()
    return ""


def parse_text_records() -> list[TextRecord]:
    records: list[TextRecord] = []
    for category, root in (
        ("area", TEXT_AREA_DIR),
        ("hotel", TEXT_HOTEL_DIR),
        ("blog", TEXT_BLOG_DIR),
    ):
        if not root.is_dir():
            continue
        for path in sorted(root.rglob("*.txt"), key=lambda item: rel(item).casefold()):
            source = read_utf8(path)
            source_format = (
                hotel_text_migration.detect_source_format(source)
                if category == "hotel"
                else "NOT_APPLICABLE"
            )
            canonical_match = CANONICAL_RE.search(source)
            canonical = canonical_match.group(0) if canonical_match else parse_labeled(source, "canonical")
            slug = ""
            if canonical_match and canonical_match.group(1).lower() == category:
                slug = canonical_match.group(2).lower()
            title = parse_labeled(source, "title")
            page_name = parse_labeled(source, "page_title_h1 / パンくずリスト") or parse_labeled(source, "page_title_h1")
            required = {
                "title": title,
                "description": parse_labeled(source, "description"),
                "canonical": canonical if slug else "",
                "page_title_h1": page_name,
                "img_1": parse_labeled(source, "img_1"),
                "img_2": parse_labeled(source, "img_2"),
            }
            image_refs = tuple(
                dict.fromkeys(
                    item.replace("\\", "/")
                    for item in re.findall(
                        r'(?:src\s*:\s*|https?://(?:www\.)?55810\.com/|["\'])([^\s"\']+\.(?:jpe?g|png|gif|svg|webp))',
                        source,
                        re.I,
                    )
                )
            )
            records.append(
                TextRecord(
                    category=category,
                    path=path,
                    title=title,
                    page_name=page_name,
                    canonical=canonical,
                    slug=slug,
                    required_missing=tuple(key for key, value in required.items() if not value),
                    image_refs=image_refs,
                    source_format=source_format,
                )
            )
    return records


def normalize_asset_ref(
    value: str,
    base: Path,
    extensions: set[str] = ASSET_EXTENSIONS,
) -> Path | None:
    cleaned = html.unescape(value.strip()).split("#", 1)[0].split("?", 1)[0]
    if not cleaned or cleaned.startswith(("#", "data:", "mailto:", "tel:", "javascript:")):
        return None
    parsed = urlparse(cleaned)
    if parsed.scheme in {"http", "https"}:
        if parsed.netloc.lower() not in {"55810.com", "www.55810.com"}:
            return None
        cleaned = parsed.path
    if Path(cleaned).suffix.lower() not in extensions:
        return None
    if cleaned.startswith("/"):
        return HP_ROOT / cleaned.lstrip("/")
    # source HTML is rendered from the HP public root, not from HP/source.
    if base.parent == HP_ROOT / "source":
        return HP_ROOT / cleaned.removeprefix("./")
    return (base.parent / cleaned).resolve()


def asset_references() -> tuple[dict[Path, set[Path]], dict[Path, set[Path]]]:
    referenced_by: dict[Path, set[Path]] = defaultdict(set)
    missing_by: dict[Path, set[Path]] = defaultdict(set)
    source_files = sorted((HP_ROOT / "source").glob("*.html")) + sorted(HP_ROOT.rglob("*.css"))
    for source_path in source_files:
        source = read_utf8(source_path)
        values = LINK_RE.findall(source)
        if source_path.suffix.lower() == ".css":
            values.extend(CSS_URL_RE.findall(source))
        for value in values:
            target = normalize_asset_ref(value, source_path)
            if target is None:
                continue
            target = target.resolve()
            if target.is_file():
                referenced_by[target].add(source_path)
            else:
                missing_by[target].add(source_path)
        for value in SCRIPT_SRC_RE.findall(source):
            target = normalize_asset_ref(value, source_path, ASSET_EXTENSIONS | {".php"})
            if target is None or target.suffix.lower() != ".php":
                continue
            target = target.resolve()
            if target.is_file():
                referenced_by[target].add(source_path)
            else:
                missing_by[target].add(source_path)
    return referenced_by, missing_by


def extract_json(source: str) -> tuple[list[object], list[str]]:
    objects: list[object] = []
    errors: list[str] = []
    payloads = JSON_RE.findall(source)
    for index, payload in enumerate(payloads, 1):
        try:
            objects.append(json.loads(payload))
        except json.JSONDecodeError as exc:
            errors.append(f"JSON-LD {index}: {exc.msg}")
    return objects, errors


def json_type_count(value: object, wanted: str) -> int:
    if isinstance(value, dict):
        count = 1 if value.get("@type") == wanted else 0
        return count + sum(json_type_count(item, wanted) for item in value.values())
    if isinstance(value, list):
        return sum(json_type_count(item, wanted) for item in value)
    return 0


def json_main_entity_count(value: object) -> int:
    if isinstance(value, dict):
        own = len(value.get("mainEntity", [])) if value.get("@type") == "FAQPage" and isinstance(value.get("mainEntity"), list) else 0
        return own + sum(json_main_entity_count(item) for item in value.values())
    if isinstance(value, list):
        return sum(json_main_entity_count(item) for item in value)
    return 0


def category_for(stem: str) -> tuple[str, str]:
    detail = DETAIL_RE.match(stem)
    if detail:
        return detail.group(1), detail.group(2)
    if stem == "index":
        return "top", "index"
    if stem in {"area", "hotel", "blog"}:
        return stem, stem
    if stem.startswith("girls") or stem in {"schedule", "ranking"}:
        return "girls", stem
    if stem in SYSTEM_STEMS or stem in SPECIAL_STEMS:
        return "system", stem
    return "other", stem


def role_for(category: str, stem: str) -> str:
    detail = DETAIL_RE.match(stem)
    if category == "top":
        return "Site entry / CANDY_HP_STRUCTURE_MAP.md"
    if detail:
        return f"{category} detail / CANDY_{category.upper()}_PAGE_GENERATION_SPEC.md"
    if stem in {"area", "hotel", "blog"}:
        return f"{stem} index / CANDY_{stem.upper()}_PAGE_GENERATION_SPEC.md"
    if category == "system":
        return "Dynamic and operational page / CANDY_OTHER_PAGES_MANAGEMENT.md"
    return "Other page / CANDY_OTHER_PAGES_MANAGEMENT.md"


def source_image_state(source_path: Path | None) -> tuple[str, list[str], list[str]]:
    if source_path is None or not source_path.is_file():
        return "UNVERIFIED", [], []
    source = read_utf8(source_path)
    refs: list[str] = []
    missing: list[str] = []
    for value in LINK_RE.findall(source):
        target = normalize_asset_ref(value, source_path)
        if target is None or target.suffix.lower() not in IMAGE_EXTENSIONS:
            continue
        refs.append(rel(target))
        if not target.is_file():
            missing.append(rel(target))
    if missing:
        return "ISSUE", sorted(set(refs)), sorted(set(missing))
    if refs:
        return "OK", sorted(set(refs)), []
    return "UNVERIFIED", [], []


def collect() -> dict[str, object]:
    base_path = HP_ROOT / "includefile" / "dataset_base.php"
    dataset_base = read_utf8(base_path) if base_path.is_file() else ""
    sitemap_path = HP_ROOT / "sitemap.xml"
    sitemap = read_utf8(sitemap_path) if sitemap_path.is_file() else ""
    sitemap_urls = set(re.findall(r"<loc>\s*([^<]+)\s*</loc>", sitemap, re.I))
    text_records = parse_text_records()
    texts_by_key: dict[tuple[str, str], list[TextRecord]] = defaultdict(list)
    for record in text_records:
        if record.slug:
            texts_by_key[(record.category, record.slug)].append(record)

    page_sources = sorted((HP_ROOT / "source").glob("*.html"), key=lambda item: item.name.casefold())
    incoming: Counter[str] = Counter()
    for source_path in page_sources:
        for link in LINK_RE.findall(read_utf8(source_path)):
            path = urlparse(html.unescape(link)).path
            name = Path(path).name
            if name.endswith(".php"):
                incoming[Path(name).stem] += 1

    pages: list[dict[str, object]] = []
    for public_path in sorted(HP_ROOT.glob("*.php"), key=lambda item: item.name.casefold()):
        stem = public_path.stem
        category, slug = category_for(stem)
        source_path = HP_ROOT / "source" / f"{stem}.html"
        dataset_path = HP_ROOT / "includefile" / f"dataset_{stem}.php"
        source_exists = source_path.is_file()
        dataset_exists = dataset_path.is_file()
        source = read_utf8(source_path) if source_exists else ""
        title = first_match(source, r"<title[^>]*>(.*?)</title>", re.I | re.S)
        h1_values = [strip_tags(item) for item in re.findall(r"<h1\b[^>]*>(.*?)</h1>", source, re.I | re.S)]
        canonical = first_match(source, r'<link\s+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)', re.I)
        case_count = dataset_base.count(f"case '{stem}.html':")
        conversion_count = len(re.findall(rf"str_replace\(\s*['\"]{re.escape(stem)}\.html['\"]\s*,\s*['\"]{re.escape(stem)}\.php['\"]", dataset_base))
        detail = DETAIL_RE.match(stem)
        list_count: int | None = None
        if detail:
            list_path = HP_ROOT / "source" / f"{category}.html"
            list_count = (
                len(
                    re.findall(
                        rf'href=["\'][^"\']*{re.escape(stem)}\.php(?:[?#][^"\']*)?["\']',
                        read_utf8(list_path),
                        re.I,
                    )
                )
                if list_path.is_file()
                else 0
            )
        sitemap_count = sum(1 for url in sitemap_urls if urlparse(url).path.rstrip("/") in {f"/{stem}.php", "" if stem == "index" else "__none__"})
        image_status, images, missing_images = source_image_state(source_path if source_exists else None)
        text_matches = texts_by_key.get((category, slug), []) if detail else []
        special = stem in SPECIAL_STEMS or not source_exists
        expected_complete = source_exists and dataset_exists and case_count == 1 and conversion_count == 1
        registration_conflict = case_count > 1 or conversion_count > 1 or (list_count is not None and list_count > 1)
        if special:
            structure = "SPECIAL"
        elif registration_conflict:
            structure = "CONFLICT"
        elif expected_complete:
            structure = "COMPLETE"
        else:
            structure = "PARTIAL"
        pages.append(
            {
                "page_id": f"{category}:{slug}",
                "category": category,
                "slug": slug,
                "stem": stem,
                "page_name": h1_values[0] if h1_values else title or "UNVERIFIED",
                "title": title,
                "h1_values": h1_values,
                "canonical": canonical,
                "public": public_path,
                "source": source_path if source_exists else None,
                "dataset": dataset_path if dataset_exists else None,
                "case_count": case_count,
                "conversion_count": conversion_count,
                "texts": text_matches,
                "template": HP_ROOT / "source" / f"template_kagoshima-deliveryhealth-{category}.html" if detail else None,
                "list_count": list_count,
                "sitemap_count": sitemap_count,
                "incoming": incoming[stem],
                "image_status": image_status,
                "images": images,
                "missing_images": missing_images,
                "structure": structure,
                "role": role_for(category, stem),
                "source_text": source,
            }
        )

    title_counts = Counter(page["title"] for page in pages if page["title"])
    canonical_counts = Counter(page["canonical"] for page in pages if page["canonical"])
    seo_rows: list[dict[str, object]] = []
    for page in pages:
        source = str(page["source_text"])
        title = str(page["title"])
        description = first_match(source, r'<meta\s+name=["\']description["\'][^>]+content=["\']([^"\']*)', re.I)
        robots = first_match(source, r'<meta\s+name=["\']robots["\'][^>]+content=["\']([^"\']*)', re.I)
        canonical = str(page["canonical"])
        h1_count = len(page["h1_values"])
        og_required = ("og:title", "og:url", "og:image", "og:description")
        og_missing = [name for name in og_required if not re.search(rf'<meta\s+property=["\']{re.escape(name)}["\']', source, re.I)]
        json_objects, json_errors = extract_json(source)
        breadcrumb_count = sum(json_type_count(obj, "BreadcrumbList") for obj in json_objects)
        faq_schema_count = sum(json_type_count(obj, "FAQPage") for obj in json_objects)
        faq_schema_items = sum(json_main_entity_count(obj) for obj in json_objects)
        faq_body_count = 0
        for faq_heading in re.finditer(r"<h2\b[^>]*>(.*?)</h2>", source, re.I | re.S):
            if not re.search(r"FAQ|よくあるご質問", strip_tags(faq_heading.group(1)), re.I):
                continue
            next_heading = re.search(r"<h2\b", source[faq_heading.end() :], re.I)
            section_end = faq_heading.end() + next_heading.start() if next_heading else len(source)
            faq_body_count += len(
                re.findall(r'class=["\'][^"\']*faq-item', source[faq_heading.end() : section_end], re.I)
            )
        item_count = sum(json_type_count(obj, "ItemList") for obj in json_objects)
        internal_refs = []
        missing_internal = []
        for value in ANCHOR_LINK_RE.findall(source):
            parsed = urlparse(html.unescape(value))
            if parsed.scheme in {"http", "https"} and parsed.netloc.lower() not in {"55810.com", "www.55810.com"}:
                continue
            name = Path(parsed.path).name
            if not name.endswith((".php", ".html")):
                continue
            target_name = re.sub(r"\.html$", ".php", name)
            internal_refs.append(target_name)
            if not (HP_ROOT / target_name).is_file():
                missing_internal.append(target_name)
        img_tags = re.findall(r"<img\b[^>]*>", source, re.I)
        img_alt_missing = sum(1 for tag in img_tags if not re.search(r'\balt=["\'][^"\']+["\']', tag, re.I))
        expected_path = "" if page["stem"] == "index" else f"/{page['stem']}.php"
        seo_helper = page["stem"] in SEO_HELPER_STEMS
        seo_admin = page["stem"] in SEO_ADMIN_STEMS
        canonical_path = urlparse(canonical).path.rstrip("/") if canonical else ""
        checks = {
            "title": "OK" if title else "ISSUE" if page["source"] else "UNVERIFIED",
            "description": "OK" if description else "ISSUE" if page["source"] else "UNVERIFIED",
            "canonical": "NOT_APPLICABLE" if seo_helper else "OK" if canonical else "ISSUE" if page["source"] else "UNVERIFIED",
            "robots": "OK" if robots else "ISSUE" if page["source"] else "UNVERIFIED",
            "h1": "NOT_APPLICABLE" if seo_helper else "OK" if h1_count == 1 else "ISSUE" if page["source"] else "UNVERIFIED",
            "ogp": "NOT_APPLICABLE" if seo_helper else "OK" if not og_missing and page["source"] else "ISSUE" if page["source"] else "UNVERIFIED",
            "json_ld": "NOT_APPLICABLE" if seo_helper else "OK" if json_objects and not json_errors else "ISSUE" if page["source"] else "UNVERIFIED",
            "breadcrumb": "NOT_APPLICABLE" if seo_helper else "OK" if breadcrumb_count else "NOT_APPLICABLE" if page["category"] in {"top", "system"} else "ISSUE",
            "faq": (
                "OK"
                if faq_schema_count and faq_body_count and faq_schema_items == faq_body_count
                else "NOT_APPLICABLE"
                if not faq_schema_count and not faq_body_count
                else "ISSUE"
            ),
            "item_list": "OK" if item_count else "NOT_APPLICABLE",
            "internal_links": "NOT_APPLICABLE" if seo_admin else "ISSUE" if missing_internal else "OK" if internal_refs else "NOT_APPLICABLE",
            "image_alt": "ISSUE" if img_alt_missing else "OK" if img_tags else "NOT_APPLICABLE",
            "sitemap": "OK" if page["sitemap_count"] == 1 else "NOT_APPLICABLE" if page["stem"] in SPECIAL_STEMS else "ISSUE",
            "url_canonical": "NOT_APPLICABLE" if seo_helper else "OK" if canonical and (canonical_path == expected_path or (page["stem"] == "girls" and canonical == "rep03010092eot")) else "ISSUE" if canonical else "UNVERIFIED",
            "duplicate_title": "ISSUE" if title and title_counts[title] > 1 else "OK" if title else "UNVERIFIED",
            "duplicate_canonical": "NOT_APPLICABLE" if seo_helper else "ISSUE" if canonical and canonical_counts[canonical] > 1 else "OK" if canonical else "UNVERIFIED",
            "orphan": "NOT_APPLICABLE" if page["stem"] == "index" or page["stem"] in SPECIAL_STEMS else "ISSUE" if page["incoming"] == 0 else "OK",
        }
        overall = "ISSUE" if "ISSUE" in checks.values() else "UNVERIFIED" if "UNVERIFIED" in checks.values() else "OK"
        issues = []
        for key, value in checks.items():
            if value == "ISSUE":
                issues.append(key)
        if json_errors:
            issues.extend(json_errors)
        if missing_internal and not seo_admin:
            issues.append("missing_links=" + ",".join(sorted(set(missing_internal))))
        seo_rows.append(
            {
                "page_id": page["page_id"],
                "stem": page["stem"],
                "title": title or "UNVERIFIED",
                "description": description or "UNVERIFIED",
                "canonical": canonical or "UNVERIFIED",
                "robots": robots or "UNVERIFIED",
                "h1": page["h1_values"][0] if page["h1_values"] else "UNVERIFIED",
                "h1_count": h1_count,
                "ogp": checks["ogp"],
                "json_ld": checks["json_ld"],
                "breadcrumb": checks["breadcrumb"],
                "faq": checks["faq"],
                "item_list": checks["item_list"],
                "internal_links": checks["internal_links"],
                "image_alt": checks["image_alt"],
                "sitemap": checks["sitemap"],
                "url_canonical": checks["url_canonical"],
                "duplicate_title": checks["duplicate_title"],
                "duplicate_canonical": checks["duplicate_canonical"],
                "orphan": checks["orphan"],
                "overall": overall,
                "issues": issues,
            }
        )
    seo_by_id = {row["page_id"]: row for row in seo_rows}
    for page in pages:
        page["seo"] = seo_by_id[page["page_id"]]["overall"]
        issues: list[str] = []
        if page["structure"] == "PARTIAL":
            issues.append("Missing structure file or dataset_base registration")
        if page["structure"] == "CONFLICT":
            issues.append("Duplicate registration")
        if page["image_status"] == "ISSUE":
            issues.append("Missing image reference")
        if page["seo"] == "ISSUE":
            issues.append("CANDY_SEO_STATUS.md")
        page["issues"] = issues

    upcoming: list[dict[str, object]] = []
    grouped: dict[tuple[str, str], list[TextRecord]] = defaultdict(list)
    for record in text_records:
        key = (record.category, record.slug or f"path:{rel(record.path)}")
        grouped[key].append(record)
    page_by_key = {(str(page["category"]), str(page["slug"])): page for page in pages}
    for (category, key), records in sorted(grouped.items(), key=lambda item: item[0]):
        slug = records[0].slug
        page = page_by_key.get((category, slug)) if slug else None
        duplicate = len(records) > 1
        legacy_formats = sorted(
            {
                record.source_format
                for record in records
                if hotel_text_migration.is_legacy_format(record.source_format)
            }
        )
        missing = sorted(set(value for record in records for value in record.required_missing))
        referenced_images: set[str] = set()
        missing_images: set[str] = set()
        for record in records:
            for value in record.image_refs:
                cleaned = value.removeprefix("https://www.55810.com/").removeprefix("http://www.55810.com/").removeprefix("./")
                options = [HP_ROOT / cleaned]
                if category == "area":
                    options.append(TEXT_AREA_DIR / "画像データ" / Path(cleaned).name)
                referenced_images.add(cleaned)
                if not any(option.is_file() for option in options):
                    missing_images.add(cleaned)
        image_state = "ISSUE" if missing_images else "OK" if referenced_images else "UNVERIFIED"
        if page and page["structure"] == "COMPLETE":
            existing = "COMPLETE"
        elif page:
            existing = "PARTIAL"
        else:
            existing = "NOT_STARTED"
        list_count = page["list_count"] if page else 0
        sitemap_count = page["sitemap_count"] if page else 0
        blockers: list[str] = []
        if not slug:
            blockers.append("canonical/slug UNVERIFIED")
        if missing:
            blockers.append("Missing required fields: " + ",".join(missing))
        if image_state != "OK":
            blockers.append("Image status: " + image_state)
        if duplicate:
            blockers.append("Duplicate Text records for the same slug")
        if legacy_formats:
            blockers.append("Legacy hotel Text format: " + ",".join(legacy_formats))
        if page and page["structure"] in {"PARTIAL", "CONFLICT"}:
            blockers.append("Existing page structure: " + str(page["structure"]))
        if list_count and int(list_count) > 1:
            blockers.append("Duplicate index registration")
        if category == "area" and not page and list_count != 1:
            blockers.append("Missing area index registration")
        if page and page["structure"] == "COMPLETE" and not duplicate:
            gate = "EXISTING"
            next_action = "Use the category specification when changing the existing page"
        elif duplicate or (page and page["structure"] == "CONFLICT"):
            gate = "CONFLICT"
            next_action = "Resolve the duplicate or slug mapping"
        elif blockers:
            gate = "BLOCKED"
            next_action = (
                "Run legacy-check and resolve every migration issue"
                if legacy_formats
                else "Resolve missing data or the partial structure"
            )
        else:
            gate = "READY"
            next_action = "Production may proceed under the category runbook"
        upcoming.append(
            {
                "category": category,
                "texts": records,
                "page_name": records[0].page_name or records[0].title or "UNVERIFIED",
                "slug": slug or "UNVERIFIED",
                "input": "COMPLETE" if not missing and slug and not duplicate else "CONFLICT" if duplicate else "BLOCKED",
                "image": image_state + (f" ({len(referenced_images) - len(missing_images)}/{len(referenced_images)})" if referenced_images else ""),
                "existing": existing,
                "list": str(list_count) if list_count is not None else "NOT_APPLICABLE",
                "sitemap": str(sitemap_count),
                "gate": gate,
                "blocker": "; ".join(blockers) or "NONE",
                "next": next_action,
                "source": (
                    "CANDY_AREA_105_PAGE_QUEUE.md"
                    if category == "area"
                    else "CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md"
                    if category == "hotel"
                    else "CANDY_BLOG_PAGE_GENERATION_SPEC.md"
                ),
            }
        )

    referenced_by, missing_by = asset_references()
    all_assets = sorted(
        (path for path in HP_ROOT.rglob("*") if path.is_file() and path.suffix.lower() in ASSET_EXTENSIONS),
        key=lambda item: rel(item).casefold(),
    )
    unreferenced = [path for path in all_assets if path.resolve() not in referenced_by]
    duplicate_hashes: dict[str, list[Path]] = defaultdict(list)
    for path in all_assets:
        duplicate_hashes[hashlib.sha256(path.read_bytes()).hexdigest()].append(path)
    duplicate_groups = [paths for paths in duplicate_hashes.values() if len(paths) > 1]
    public_candidates = [
        path
        for path in HP_ROOT.rglob("*")
        if path.is_file()
        and (
            path.suffix.lower() in {".txt", ".md", ".bak", ".old", ".tmp"}
            or re.search(r"(?:backup|copy|コピー|old|before)", path.name, re.I)
        )
        and "log" not in {part.lower() for part in path.parts}
    ]
    generation_head, generation_time = generation_base_head()
    return {
        "pages": pages,
        "seo": seo_rows,
        "texts": text_records,
        "upcoming": upcoming,
        "assets": all_assets,
        "referenced_by": referenced_by,
        "missing_by": missing_by,
        "unreferenced": unreferenced,
        "duplicate_groups": duplicate_groups,
        "public_candidates": public_candidates,
        "dataset_base": dataset_base,
        "branch": git_value("branch", "--show-current"),
        "head": generation_head,
        "current_head": git_value("rev-parse", "HEAD"),
        "generation_time": generation_time,
        "state_fingerprint": fingerprint_for_paths(state_fingerprint_paths()),
    }


def header(data: dict[str, object], scope: str, population: str, result: str, unverified: str) -> list[str]:
    return [
        "> **Automatically generated. Manual editing is prohibited.**",
        ">",
        f"> Generated at: {data['generation_time']} (reproducible generation baseline)",
        f"> Branch: {data['branch']}",
        f"> Commit: {data['head']}",
        f"> State fingerprint: sha256:{data['state_fingerprint']}",
        f"> Scope: {scope}",
        f"> Population: {population}",
        f"> Generator: `{SCRIPT_REL}`",
        f"> Result: {result}",
        f"> Unverified scope: {unverified}",
    ]


def render_ledger(data: dict[str, object]) -> str:
    pages = data["pages"]
    lines = ["# CANDY SITE PAGE LEDGER", ""]
    lines += header(data, "Public PHP files directly under HP and the corresponding source, dataset, Text, index, and sitemap entries", f"Public PHP files: {len(pages)}", "OK", "Production HTTP, database state, and external include targets")
    lines += [
        "",
        "This structural ledger records one page per row. It records locations and automated checks without duplicating page copy.",
        "",
        "| page ID | category | page name | slug | role | public PHP | source HTML | dataset PHP | dataset_base | source Text | template | index registrations | sitemap entries | SEO | images | structure | issues | verification source |",
        "|---|---|---|---|---|---|---|---|---|---|---|---:|---:|---|---|---|---|---|",
    ]
    for page in pages:
        registrations = f"case {page['case_count']} / conversions {page['conversion_count']}"
        texts = "<br>".join(rel(record.path) for record in page["texts"]) or "NOT_APPLICABLE"
        template = rel(page["template"]) if page["template"] and page["template"].is_file() else "NOT_APPLICABLE"
        lines.append(
            "| "
            + " | ".join(
                md(value)
                for value in (
                    page["page_id"],
                    page["category"],
                    page["page_name"],
                    page["slug"],
                    page["role"],
                    rel(page["public"]),
                    rel(page["source"]) if page["source"] else "MISSING",
                    rel(page["dataset"]) if page["dataset"] else "MISSING",
                    registrations,
                    texts,
                    template,
                    page["list_count"] if page["list_count"] is not None else "NOT_APPLICABLE",
                    page["sitemap_count"],
                    page["seo"],
                    page["image_status"],
                    page["structure"],
                    "; ".join(page["issues"]) or "NONE",
                    f"{data['head']} / {data['generation_time']}",
                )
            )
            + " |"
        )
    return "\n".join(lines) + "\n"


def render_upcoming(data: dict[str, object]) -> str:
    rows = data["upcoming"]
    gates = Counter(row["gate"] for row in rows)
    lines = ["# CANDY UPCOMING PAGES", ""]
    lines += header(
        data,
        "Text_area_data, Text_hotel_data, Text_blog_data, and current pages, images, indexes, and sitemap entries",
        f"Unique candidates: {len(rows)} / Text records: {len(data['texts'])}",
        " / ".join(f"{key}={gates[key]}" for key in ("READY", "BLOCKED", "EXISTING", "CONFLICT")),
        "Text accuracy, Git tracking, and the owner's publication decision",
    )
    lines += [
        "",
        "This is the current cross-category state. It does not replace the task history in the existing queue or classification documents.",
        "",
        "| category | source Text | page name | slug | input status | image status | existing page | index registrations | sitemap entries | target gate | blocker | next action | operational source |",
        "|---|---|---|---|---|---|---|---:|---:|---|---|---|---|",
    ]
    for row in rows:
        lines.append(
            "| "
            + " | ".join(
                md(value)
                for value in (
                    row["category"],
                    "<br>".join(rel(record.path) for record in row["texts"]),
                    row["page_name"],
                    row["slug"],
                    row["input"],
                    row["image"],
                    row["existing"],
                    row["list"],
                    row["sitemap"],
                    row["gate"],
                    row["blocker"],
                    row["next"],
                    row["source"],
                )
            )
            + " |"
        )
    return "\n".join(lines) + "\n"


def refs_for_extension(data: dict[str, object], extension: str) -> list[tuple[Path, list[str]]]:
    rows: list[tuple[Path, list[str]]] = []
    referenced_by = data["referenced_by"]
    paths = list(HP_ROOT.rglob(f"*{extension}"))
    if extension == ".js":
        paths.extend(
            path
            for path in referenced_by
            if path.suffix.lower() == ".php" and path.parent == (HP_ROOT / "js").resolve()
        )
    for path in sorted(set(paths), key=lambda item: rel(item).casefold()):
        rows.append((path, sorted(rel(item) for item in referenced_by.get(path.resolve(), set()))))
    return rows


def render_assets(data: dict[str, object]) -> str:
    pages = data["pages"]
    assets: list[Path] = data["assets"]
    extensions = Counter(path.suffix.lower() for path in assets)
    folders = Counter(rel(path.parent) for path in assets)
    lines = ["# CANDY CODE ASSET INVENTORY", ""]
    lines += header(
        data,
        "Public PHP, source files, datasets, shared PHP, CSS, JavaScript, images, videos, and fonts",
        f"Public PHP files: {len(pages)} / assets: {len(assets)}",
        f"Missing references: {len(data['missing_by'])} / duplicate hash groups: {len(data['duplicate_groups'])}",
        "Runtime-generated references, database-derived references, external URLs, and log contents",
    )
    lines += [
        "",
        "## Public PHP and Structure Files",
        "",
        "| public PHP | source | dataset | case | link conversions |",
        "|---|---|---|---:|---:|",
    ]
    for page in pages:
        lines.append(
            f"| {md(rel(page['public']))} | {md(rel(page['source']) if page['source'] else 'MISSING')} | "
            f"{md(rel(page['dataset']) if page['dataset'] else 'MISSING')} | {page['case_count']} | {page['conversion_count']} |"
        )
    common = {
        "HP/includefile/dataset_base.php": "Included by public PHP files. This is the common entry point for source selection, external session and database settings, dataset branching, and HTML link conversion.",
        "HP/includefile/class.hpgcoder2.php": "Loaded by dataset_base. It assigns rep...eot placeholders to their functions.",
        "HP/includefile/funcs.php": "Loaded by dataset_base and the class file. It provides shared functions for database retrieval, HTML generation, headers, and related operations.",
        "HP/create.php": "Special entry point related to file generation. MUST NOT be used during ordinary production.",
    }
    lines += ["", "## Shared PHP", "", "| path | role and impact |", "|---|---|"]
    for path, role in common.items():
        state = "OK" if (REPO_ROOT / path).is_file() else "UNVERIFIED"
        lines.append(f"| `{path}` | {role} Status={state} |")
    lines += ["", "Only the external session and database configuration references in `dataset_base.php` are checked. Secret values are neither collected nor output.", ""]
    for label, extension in (("CSS", ".css"), ("JavaScript", ".js")):
        lines += [f"## {label} Files and Referrers", "", "| file | referrers |", "|---|---|"]
        for path, referrers in refs_for_extension(data, extension):
            lines.append(f"| {md(rel(path))} | {md('<br>'.join(referrers) if referrers else 'UNVERIFIED')} |")
        lines.append("")
    lines += ["## Asset Summary", "", "### By Extension", "", "| extension | count |", "|---|---:|"]
    for extension, count in sorted(extensions.items()):
        lines.append(f"| {md(extension)} | {count} |")
    lines += ["", "### By Folder", "", "| folder | count |", "|---|---:|"]
    for folder, count in sorted(folders.items()):
        lines.append(f"| {md(folder)} | {count} |")
    lines += ["", "## Assets by Page", "", "| page ID | referenced images | missing | status |", "|---|---:|---|---|"]
    for page in pages:
        lines.append(f"| {md(page['page_id'])} | {len(page['images'])} | {md('<br>'.join(page['missing_images']) or 'NONE')} | {page['image_status']} |")
    lines += ["", "## Missing Reference Targets", "", "| target | referrers |", "|---|---|"]
    if not data["missing_by"]:
        lines.append("| NONE | - |")
    else:
        for path, referrers in sorted(data["missing_by"].items(), key=lambda item: rel(item[0]).casefold()):
            lines.append(f"| {md(rel(path))} | {md('<br>'.join(sorted(rel(item) for item in referrers)))} |")
    by_folder = defaultdict(list)
    for path in data["unreferenced"]:
        by_folder[rel(path.parent)].append(path)
    lines += [
        "",
        "## Assets Without a Confirmed Referrer",
        "",
        "These candidates have no confirmed static HTML or CSS reference. They may be referenced dynamically by the database, JavaScript, or PHP, so this is not a deletion decision.",
        "",
        "| folder | count | examples (first five) |",
        "|---|---:|---|",
    ]
    for folder, paths in sorted(by_folder.items()):
        lines.append(f"| {md(folder)} | {len(paths)} | {md(', '.join(path.name for path in paths[:5]))} |")
    lines += ["", "## Duplicate Hash Candidates", "", "| SHA-256 | files |", "|---|---|"]
    if not data["duplicate_groups"]:
        lines.append("| NONE | - |")
    else:
        for paths in sorted(data["duplicate_groups"], key=lambda group: rel(group[0]).casefold()):
            digest = hashlib.sha256(paths[0].read_bytes()).hexdigest()
            lines.append(f"| `{digest}` | {md('<br>'.join(rel(path) for path in paths))} |")
    lines += ["", "## Candidates That May Not Require Publication", "", "| path | assessment |", "|---|---|"]
    if not data["public_candidates"]:
        lines.append("| NONE | No automatic assessment |")
    else:
        for path in sorted(data["public_candidates"], key=lambda item: rel(item).casefold()):
            lines.append(f"| {md(rel(path))} | Candidate based only on extension and name. MUST NOT be deleted before the owner decides. |")
    return "\n".join(lines) + "\n"


def render_seo(data: dict[str, object]) -> str:
    rows = data["seo"]
    overall = Counter(row["overall"] for row in rows)
    lines = ["# CANDY SEO STATUS", ""]
    lines += header(
        data,
        "Source HTML corresponding to public PHP files directly under HP",
        f"Pages: {len(rows)}",
        " / ".join(f"{key}={overall[key]}" for key in ("OK", "ISSUE", "UNVERIFIED")),
        "Production HTTP, search engine index state, redirects, and database-generated HTML",
    )
    lines += [
        "",
        "Only `OK / ISSUE / UNVERIFIED / NOT_APPLICABLE` are used. Detected issues are not corrected automatically.",
        "",
        "| page ID | title | description | canonical | robots | H1 | H1 count | OGP | JSON-LD | BreadcrumbList | FAQPage match | ItemList | internal links | image alt | sitemap | URL=canonical | duplicate title | duplicate canonical | orphan candidate | SEO | issues |",
        "|---|---|---|---|---|---|---:|---|---|---|---|---|---|---|---|---|---|---|---|---|---|",
    ]
    for row in rows:
        lines.append(
            "| "
            + " | ".join(
                md(value)
                for value in (
                    row["page_id"],
                    row["title"],
                    row["description"],
                    row["canonical"],
                    row["robots"],
                    row["h1"],
                    row["h1_count"],
                    row["ogp"],
                    row["json_ld"],
                    row["breadcrumb"],
                    row["faq"],
                    row["item_list"],
                    row["internal_links"],
                    row["image_alt"],
                    row["sitemap"],
                    row["url_canonical"],
                    row["duplicate_title"],
                    row["duplicate_canonical"],
                    row["orphan"],
                    row["overall"],
                    "; ".join(row["issues"]) or "NONE",
                )
            )
            + " |"
        )
    lines += [
        "",
        "## Assessment Boundaries",
        "",
        "- FAQ matching compares the number of static `.faq-item` elements with the number of FAQPage `mainEntity` entries. Semantic equivalence is UNVERIFIED.",
        "- Orphan candidates are based on inbound static PHP and HTML links in source HTML. Links generated by the database or JavaScript are UNVERIFIED.",
        "- index/noindex, canonical, URL, and structured data values are not changed automatically.",
    ]
    return "\n".join(lines) + "\n"


def render_all(data: dict[str, object]) -> dict[str, str]:
    return {
        "CANDY_SITE_PAGE_LEDGER.md": render_ledger(data),
        "CANDY_UPCOMING_PAGES.md": render_upcoming(data),
        "CANDY_CODE_ASSET_INVENTORY.md": render_assets(data),
        "CANDY_SEO_STATUS.md": render_seo(data),
    }


def audit(data: dict[str, object]) -> int:
    pages = data["pages"]
    seo = Counter(row["overall"] for row in data["seo"])
    gates = Counter(row["gate"] for row in data["upcoming"])
    print("AUDIT=OK")
    print(f"branch={data['branch']} current_head={data['current_head']} generation_base_head={data['head']}")
    print(f"pages={len(pages)} complete={sum(page['structure'] == 'COMPLETE' for page in pages)} partial={sum(page['structure'] == 'PARTIAL' for page in pages)} special={sum(page['structure'] == 'SPECIAL' for page in pages)} conflict={sum(page['structure'] == 'CONFLICT' for page in pages)}")
    print(f"texts={len(data['texts'])} upcoming={len(data['upcoming'])} ready={gates['READY']} blocked={gates['BLOCKED']} existing={gates['EXISTING']} conflict={gates['CONFLICT']}")
    print(f"seo_ok={seo['OK']} seo_issue={seo['ISSUE']} seo_unverified={seo['UNVERIFIED']}")
    print(f"assets={len(data['assets'])} missing_refs={len(data['missing_by'])} unreferenced_candidates={len(data['unreferenced'])} duplicate_hash_groups={len(data['duplicate_groups'])}")
    return 0


def content_comparison_view(value: str) -> str:
    lines: list[str] = []
    for line in value.splitlines():
        if line.startswith("> Generated at: "):
            lines.append("> Generated at: <metadata-only>")
            continue
        if line.startswith("> Commit: "):
            lines.append("> Commit: <metadata-only>")
            continue
        lines.append(
            re.sub(
                r"\| (?:[0-9a-f]{40}|UNVERIFIED) / [^|]+ \|$",
                "| <verification-metadata-only> |",
                line,
            )
        )
    return "\n".join(lines) + ("\n" if value.endswith("\n") else "")


def document_differs(current: str | None, expected: str, strict_metadata: bool) -> bool:
    if current is None:
        return True
    if strict_metadata:
        return current != expected
    return content_comparison_view(current) != content_comparison_view(expected)


def preview(rendered: dict[str, str], strict_metadata: bool = False) -> int:
    for name, expected in rendered.items():
        # Keep the preview in a bounded in-memory temporary stream. This avoids
        # writing source or generated documents while still exercising UTF-8 I/O.
        with tempfile.SpooledTemporaryFile(
            max_size=64 * 1024 * 1024,
            mode="w+",
            encoding="utf-8",
            newline="\n",
        ) as stream:
            stream.write(expected)
            stream.seek(0)
            preview_content = stream.read()
        target = GENERATED_DIR / name
        current = read_utf8(target) if target.is_file() else None
        exact_current = current or ""
        diff = list(
            difflib.unified_diff(
                exact_current.splitlines(),
                preview_content.splitlines(),
                fromfile=rel(target),
                tofile=f"preview/{name}",
                lineterm="",
            )
        )
        content_changed = document_differs(current, expected, False)
        metadata_only = bool(diff) and not content_changed
        print(
            f"PREVIEW={name} changed={'yes' if content_changed else 'no'} "
            f"metadata_only={'yes' if metadata_only else 'no'} diff_lines={len(diff)}"
        )
        if metadata_only and not strict_metadata:
            continue
        limit = 120
        for line in diff[:limit]:
            print(line)
        if len(diff) > limit:
            print(f"... omitted {len(diff) - limit} diff lines")
    return 0


def write(rendered: dict[str, str], strict_metadata: bool = False) -> int:
    changed = 0
    metadata_ignored = 0
    for name, expected in rendered.items():
        target = GENERATED_DIR / name
        current = read_utf8(target) if target.is_file() else None
        if document_differs(current, expected, strict_metadata):
            atomic_write(target, expected)
            changed += 1
            print(f"WRITE={rel(target)}")
        elif current != expected:
            metadata_ignored += 1
    print(
        f"WRITE=OK changed={changed} unchanged={len(rendered) - changed} "
        f"metadata_only_ignored={metadata_ignored}"
    )
    return 0


def check(
    data: dict[str, object],
    rendered: dict[str, str],
    target: str | None,
    strict_metadata: bool = False,
) -> int:
    drift: list[str] = []
    for name, expected in rendered.items():
        path = GENERATED_DIR / name
        current = read_utf8(path) if path.is_file() else None
        if document_differs(current, expected, strict_metadata):
            drift.append(rel(path))
    if target:
        matches = [page for page in data["pages"] if page["slug"] == target or page["stem"] == target]
        candidates = [row for row in data["upcoming"] if row["slug"] == target]
        print(f"TARGET={target} pages={len(matches)} upcoming={len(candidates)}")
        for page in matches:
            print(f"TARGET_PAGE={page['page_id']} structure={page['structure']} seo={page['seo']} images={page['image_status']} list={page['list_count']} sitemap={page['sitemap_count']}")
        for row in candidates:
            print(f"TARGET_UPCOMING={row['category']}:{row['slug']} gate={row['gate']} blocker={row['blocker']}")
        if not matches and not candidates:
            print("CHECK=FAIL target_not_found", file=sys.stderr)
            return 2
    if drift:
        label = "metadata_or_content_drift" if strict_metadata else "content_drift"
        print(f"CHECK=FAIL {label}=" + ",".join(drift), file=sys.stderr)
        return 1
    mode = "strict-metadata" if strict_metadata else "content"
    print(
        f"CHECK=OK documents={len(rendered)} mode={mode} "
        f"state_fingerprint=sha256:{data['state_fingerprint']}"
    )
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(description="Audit and generate deterministic CANDY site-state documents")
    parser.add_argument("command", choices=("audit", "preview", "write", "check"))
    parser.add_argument("--target", help="during check, limit output to a slug or public PHP stem")
    parser.add_argument(
        "--strict-metadata",
        action="store_true",
        help="include generated timestamp, Commit SHA, and row verification metadata in comparison",
    )
    args = parser.parse_args()
    if args.target and args.command != "check":
        parser.error("--target may be used only with check")
    if args.strict_metadata and args.command == "audit":
        parser.error("--strict-metadata may be used only with preview, write, or check")
    data = collect()
    if args.command == "audit":
        return audit(data)
    rendered = render_all(data)
    if args.command == "preview":
        return preview(rendered, args.strict_metadata)
    if args.command == "write":
        return write(rendered, args.strict_metadata)
    return check(data, rendered, args.target, args.strict_metadata)


if __name__ == "__main__":
    raise SystemExit(main())
