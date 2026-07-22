#!/usr/bin/env python3
"""Inspect and convert legacy CANDY hotel Text into the current input format."""

from __future__ import annotations

import argparse
import re
import subprocess
import sys
import tempfile
from dataclasses import dataclass
from pathlib import Path

import candy_area_page as area_common
import candy_hotel_page as current
import candy_page_common as path_config


FORMAT_CURRENT = "CURRENT"
FORMAT_LEGACY_V1 = "LEGACY_V1"
FORMAT_LEGACY_NUMBERED = "LEGACY_NUMBERED"
FORMAT_UNKNOWN = "UNKNOWN"
LEGACY_FORMATS = {FORMAT_LEGACY_V1, FORMAT_LEGACY_NUMBERED}

HEAVY_SEPARATOR = "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
LIGHT_SEPARATOR = "-------------------------------------------------------------------"

METADATA_LABELS = {
    "title",
    "description",
    'meta name="description"',
    "canonical",
    'link rel="canonical"',
    "image",
    'meta property="og:image"',
    "img_1",
    "img_2",
    "写真",
    "page_title_h1",
    "page_title_h1 / パンくずリスト",
    "subtitle_h1",
    "description_h1",
    "description_h1（改行無し）",
    "option",
    "option_subtitle",
    "option_description",
}

BASIC_LABELS = (
    "ホテル名",
    "URL",
    "住所",
    "住所（郵便番号迄）",
    "電話番号",
    "部屋・駐車場",
    "支払方法",
)


@dataclass(frozen=True)
class NormalizedSection:
    kind: str
    lines: list[str]


@dataclass
class Inspection:
    source_format: str
    converted_text: str | None
    data: current.HotelData | None
    mapped_fields: list[str]
    issues: list[str]

    @property
    def ready(self) -> bool:
        return self.source_format in LEGACY_FORMATS and not self.issues and self.data is not None


def read_utf8(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8-sig").replace("\r\n", "\n")
    except UnicodeDecodeError as exc:
        raise current.HotelToolError(f"UTF-8で読めません: {path}") from exc


def detect_source_format(text: str) -> str:
    labels = {line.strip() for line in text.splitlines()}
    if {'meta name="description"', 'link rel="canonical"'} & labels:
        return FORMAT_LEGACY_V1
    has_current_metadata = {"description", "canonical", "image"}.issubset(labels)
    has_current_images = {"img_1", "img_2"}.issubset(labels)
    has_semantic_scene = any(line.startswith("scene（h2") for line in labels)
    has_numbered_scene = any(re.fullmatch(r"scene\d+", line) for line in labels)
    if has_current_metadata and has_current_images and has_semantic_scene and not has_numbered_scene:
        return FORMAT_CURRENT
    if "写真" in labels or has_numbered_scene:
        return FORMAT_LEGACY_NUMBERED
    if has_current_metadata and has_current_images:
        return FORMAT_CURRENT
    return FORMAT_UNKNOWN


def is_legacy_format(source_format: str) -> bool:
    return source_format in LEGACY_FORMATS


def _value_after_label(
    lines: list[str],
    labels: tuple[str, ...],
    *,
    start: int = 0,
    end: int | None = None,
) -> str:
    stop = len(lines) if end is None else end
    position = next(
        (index for index in range(start, stop) if lines[index].strip() in labels),
        None,
    )
    if position is None:
        return ""
    values: list[str] = []
    for raw in lines[position + 1 : stop]:
        value = raw.strip()
        if current.is_separator(value) or current.SCENE_MARKER_RE.fullmatch(value):
            break
        if value in METADATA_LABELS or value in {"記事", "記事内容", "基本情報"}:
            break
        values.append(raw.rstrip())
    return "\n".join(values).strip()


def _aliased_value(
    lines: list[str],
    labels: tuple[str, ...],
    issues: list[str],
    field: str,
    *,
    end: int,
) -> str:
    values: list[str] = []
    for label in labels:
        count = sum(1 for line in lines[:end] if line.strip() == label)
        if count > 1:
            issues.append(f"FIELD_DUPLICATE|{field}のラベルが重複しています: {label} count={count}")
        value = _value_after_label(lines, (label,), end=end)
        if value:
            values.append(value)
    unique_values = list(dict.fromkeys(values))
    if len(unique_values) > 1:
        issues.append(f"FIELD_CONFLICT|{field}に異なる値が複数あります")
    return unique_values[0] if unique_values else ""


def _prefixed_src(value: str) -> str:
    return area_common.value_after_prefix(value, "src")


def _legacy_images(lines: list[str], issues: list[str]) -> tuple[str, str]:
    explicit_1 = _prefixed_src(_value_after_label(lines, ("img_1",)))
    explicit_2 = _prefixed_src(_value_after_label(lines, ("img_2",)))
    if explicit_1 or explicit_2:
        if not explicit_1 or not explicit_2:
            issues.append("IMAGE_PAIR_PARTIAL|img_1とimg_2の両方が必要です")
        return explicit_1, explicit_2

    sources: list[str] = []
    for position, raw in enumerate(lines):
        if raw.strip() != "写真":
            continue
        for candidate in lines[position + 1 :]:
            value = candidate.strip()
            if current.is_separator(value) or current.SCENE_MARKER_RE.fullmatch(value):
                break
            match = re.fullmatch(r"src\s*[:：]\s*(.+)", value)
            if match:
                sources.append(match.group(1).strip())
                break
    if len(sources) != 2:
        issues.append(
            f"LEGACY_IMAGE_COUNT|汎用「写真」は正確に2件必要です: count={len(sources)}"
        )
        return "", ""
    return sources[0], sources[1]


def _pair_blocks(
    section: list[str],
    issues: list[str],
    label: str,
    *,
    allow_one_note: bool = False,
) -> tuple[list[current.TextBlock], list[str]]:
    subtitle_count = sum(
        1 for line in section if current.SUBTITLE_RE.fullmatch(line.strip())
    )
    description_count = sum(
        1 for line in section if current.DESCRIPTION_RE.fullmatch(line.strip())
    )
    try:
        blocks = current.pair_blocks(section)
        notes = current.unmatched_descriptions(section) if allow_one_note else []
    except current.HotelToolError as exc:
        issues.append(f"{label}_PAIR_ERROR|{exc}")
        return [], []
    expected_descriptions = subtitle_count + len(notes)
    if description_count != expected_descriptions:
        issues.append(
            f"{label}_DESCRIPTION_COUNT|subtitle={subtitle_count} "
            f"description={description_count} note={len(notes)}"
        )
    if len(blocks) != subtitle_count:
        issues.append(
            f"{label}_PAIR_COUNT|subtitle={subtitle_count} parsed={len(blocks)}"
        )
    if len(notes) > 1 or (notes and not allow_one_note):
        issues.append(f"{label}_NOTE_COUNT|単独補足文は最大1件です")
    return blocks, notes


def _direct_fields(section: list[str], labels: tuple[str, ...]) -> dict[str, str]:
    output: dict[str, str] = {}
    for label in labels:
        value = current.field_value(section, (label,), labels)
        if value:
            output[label] = value
    return output


def _merge_field(
    output: dict[str, str],
    key: str,
    value: str,
    issues: list[str],
    label: str,
) -> None:
    normalized_key = "住所" if key == "住所（郵便番号迄）" else key
    if normalized_key in output and output[normalized_key] != value:
        issues.append(f"{label}_CONFLICT|{normalized_key}に異なる値が複数あります")
        return
    output[normalized_key] = value


def _normalize_basic(section: list[str], issues: list[str]) -> NormalizedSection:
    fields: dict[str, str] = {}
    for key, value in _direct_fields(section, BASIC_LABELS).items():
        _merge_field(fields, key, value, issues, "BASIC")
    blocks, _notes = _pair_blocks(section, issues, "BASIC")
    for block in blocks:
        key = block.title.strip()
        if key not in BASIC_LABELS:
            issues.append(f"BASIC_UNKNOWN_FIELD|未対応項目です: {key}")
            continue
        _merge_field(fields, key, block.description, issues, "BASIC")

    for key in ("ホテル名", "URL", "住所"):
        if not fields.get(key):
            issues.append(f"BASIC_REQUIRED|基本情報の{key}がありません")
        elif fields[key].strip() == "不明":
            issues.append(f"BASIC_REQUIRED|基本情報の{key}が不明です")

    lines = ["scene（h2）", "基本情報", ""]
    for key in ("ホテル名", "URL", "住所", "電話番号", "部屋・駐車場", "支払方法"):
        value = fields.get(key, "").strip()
        if not value or (key not in {"ホテル名", "URL", "住所"} and value == "不明"):
            continue
        lines.extend([key, value, ""])
    return NormalizedSection("basic", lines)


def _normalize_rates(section: list[str], issues: list[str]) -> NormalizedSection | None:
    fields = _direct_fields(section, current.RATE_LABELS)
    blocks, notes = _pair_blocks(section, issues, "RATES", allow_one_note=True)
    for block in blocks:
        key = block.title.strip()
        if key not in current.RATE_LABELS:
            issues.append(f"RATES_UNKNOWN_FIELD|未対応項目です: {key}")
            continue
        _merge_field(fields, key, block.description, issues, "RATES")
    rows = [
        (key, fields[key])
        for key in current.RATE_LABELS
        if fields.get(key) and fields[key].strip() != "不明"
    ]
    if not rows:
        if notes:
            issues.append("RATES_NOTE_WITHOUT_ROWS|料金行がないのに補足文があります")
        return None
    lines = ["scene（h2）", "料金情報", ""]
    for key, value in rows:
        lines.extend([key, value.strip(), ""])
    if notes:
        lines.extend(["description_", notes[0], ""])
    return NormalizedSection("rates", lines)


def _normalize_access(section: list[str], issues: list[str]) -> NormalizedSection | None:
    map_url = current.field_value(
        section,
        ("地図URL",),
        ("地図URL", "地図タイトル", "subtitle_", "description_"),
    )
    map_title = current.field_value(
        section,
        ("地図タイトル",),
        ("地図URL", "地図タイトル", "subtitle_", "description_"),
    )
    if not map_url:
        marker = next(
            (index for index, value in enumerate(section) if value.strip() == "マップ設置"),
            None,
        )
        if marker is not None:
            for raw in section[marker + 1 :]:
                value = raw.strip()
                if current.is_separator(value) or current.SCENE_MARKER_RE.fullmatch(value):
                    break
                if "<iframe" in value or re.fullmatch(r"https://\S+", value):
                    map_url = value
                    break

    blocks, _notes = _pair_blocks(section, issues, "ACCESS")
    if len(blocks) > 1:
        issues.append(f"ACCESS_PAIR_COUNT|アクセス本文は1組だけです: {len(blocks)}")
    subtitle = blocks[0].title if blocks else ""
    description = blocks[0].description if blocks else ""
    values = (map_url, map_title, subtitle, description)
    has_legacy_access_marker = any(
        value.strip() == "マップ設置"
        or current.SUBTITLE_RE.fullmatch(value.strip())
        or current.DESCRIPTION_RE.fullmatch(value.strip())
        for value in section
    )
    if not any(values) and not has_legacy_access_marker:
        return None
    if not all(values):
        missing = [
            name
            for name, value in zip(
                ("地図URL", "地図タイトル", "subtitle", "description"),
                values,
            )
            if not value
        ]
        issues.append("ACCESS_PARTIAL|不足: " + ",".join(missing))
    lines = [
        "scene（h2）",
        "アクセス情報",
        "",
        "地図URL",
        map_url,
        "",
        "地図タイトル",
        map_title,
        "",
        "subtitle_",
        subtitle,
        "",
        "description_",
        description,
        "",
    ]
    return NormalizedSection("access", lines)


def _normalize_pair_scene(
    section: list[str],
    heading: str,
    kind: str,
    issues: list[str],
    *,
    allow_note: bool = False,
) -> NormalizedSection | None:
    blocks, notes = _pair_blocks(section, issues, kind.upper(), allow_one_note=allow_note)
    if not blocks:
        if notes:
            issues.append(f"{kind.upper()}_NOTE_WITHOUT_ITEMS|項目がありません")
        return None
    marker = (
        "scene（h2 / よくあるご質問）"
        if kind == "faqs"
        else "scene（h2）"
    )
    lines = [marker, heading, ""]
    for block in blocks:
        lines.extend(["subtitle_", block.title, "description_", block.description, ""])
    if notes:
        lines.extend(["description_", notes[0], ""])
    return NormalizedSection(kind, lines)


def _normalize_shop(section: list[str], heading: str, issues: list[str]) -> NormalizedSection:
    bullets = [line.strip() for line in section if line.strip().startswith("・")]
    if not bullets:
        issues.append("SHOP_ITEMS|店舗指定がありません")
    else:
        try:
            area_common.parse_shop_requests(bullets)
        except area_common.AreaToolError as exc:
            issues.append(f"SHOP_ITEMS|{exc}")
    return NormalizedSection(
        "shops",
        ["scene（h2 / 「人気デリヘル店」情報）", heading, "", *bullets, ""],
    )


def _normalize_article(
    section: list[str],
    heading: str,
    issues: list[str],
) -> NormalizedSection:
    blocks, _notes = _pair_blocks(section, issues, "ARTICLE")
    if len(blocks) != 1:
        issues.append(f"ARTICLE_PAIR_COUNT|通常記事は1組必要です: {len(blocks)}")
    block = blocks[0] if blocks else current.TextBlock("", "")
    return NormalizedSection(
        "article",
        [
            "scene（h2）",
            heading,
            "",
            "subtitle_",
            block.title,
            "",
            "description_",
            block.description,
            "",
        ],
    )


def _normalize_sections(lines: list[str], issues: list[str]) -> list[NormalizedSection]:
    try:
        source_sections = current.scene_sections(lines)
    except current.HotelToolError as exc:
        issues.append(f"SCENES|{exc}")
        return []

    output: list[NormalizedSection] = []
    seen_known: set[str] = set()
    for section in source_sections:
        try:
            heading = current.section_heading(section)
        except current.HotelToolError as exc:
            issues.append(f"SCENE_HEADING|{exc}")
            continue
        if "人気デリヘル店" in heading or "人気デリヘル店" in section[0]:
            normalized = _normalize_shop(section, heading, issues)
        elif "よくあるご質問" in heading or "FAQ" in heading:
            normalized = _normalize_pair_scene(section, heading, "faqs", issues)
        elif heading == "基本情報":
            normalized = _normalize_basic(section, issues)
        elif heading == "料金情報":
            normalized = _normalize_rates(section, issues)
        elif heading == "アクセス情報":
            normalized = _normalize_access(section, issues)
        elif "周辺スポット" in heading:
            normalized = _normalize_pair_scene(
                section, heading, "spots", issues, allow_note=True
            )
        else:
            normalized = _normalize_article(section, heading, issues)
        if normalized is None:
            continue
        if normalized.kind != "article":
            if normalized.kind in seen_known:
                issues.append(f"SCENE_DUPLICATE|{normalized.kind}が重複しています")
            seen_known.add(normalized.kind)
        output.append(normalized)
    if "shops" not in seen_known:
        issues.append("SCENES_REQUIRED|人気デリヘル店sceneがありません")
    if "basic" not in seen_known:
        issues.append("SCENES_REQUIRED|基本情報sceneがありません")
    return output


def _append_field(lines: list[str], label: str, value: str) -> None:
    lines.extend([label, value, LIGHT_SEPARATOR])


def _serialize_current(
    metadata: dict[str, str],
    image1: str,
    image2: str,
    sections: list[NormalizedSection],
) -> str:
    lines = [HEAVY_SEPARATOR, "基本情報", HEAVY_SEPARATOR]
    for label in ("title", "description", "canonical", "image"):
        _append_field(lines, label, metadata.get(label, ""))
    lines.extend([HEAVY_SEPARATOR, "記事内容", HEAVY_SEPARATOR, "img_1"])
    lines.extend([f"src : {image1}", LIGHT_SEPARATOR])
    _append_field(lines, "page_title_h1 / パンくずリスト", metadata.get("page_title", ""))
    _append_field(lines, "subtitle_h1", metadata.get("subtitle_h1", ""))
    _append_field(lines, "description_h1", metadata.get("description_h1", ""))
    if any(metadata.get(key) for key in ("option", "option_subtitle", "option_description")):
        _append_field(lines, "option", metadata.get("option", ""))
        _append_field(lines, "option_subtitle", metadata.get("option_subtitle", ""))
        _append_field(lines, "option_description", metadata.get("option_description", ""))

    inserted_image2 = False
    for section in sections:
        if section.kind == "basic" and not inserted_image2:
            lines.extend(["img_2", f"src : {image2}", LIGHT_SEPARATOR])
            inserted_image2 = True
        lines.extend(section.lines)
        lines.append(LIGHT_SEPARATOR)
    if not inserted_image2:
        lines.extend(["img_2", f"src : {image2}", LIGHT_SEPARATOR])
    return "\n".join(lines).rstrip() + "\n"


def _validate_converted(text: str) -> tuple[current.HotelData | None, str | None]:
    with tempfile.TemporaryDirectory(prefix="candy-hotel-text-migration-") as directory:
        path = Path(directory) / "converted.txt"
        path.write_text(text, encoding="utf-8")
        try:
            return current.parse_hotel_text(path), None
        except (current.HotelToolError, area_common.AreaToolError) as exc:
            return None, str(exc)


def inspect_text(text: str, source_path: Path) -> Inspection:
    source_format = detect_source_format(text)
    if source_format == FORMAT_CURRENT:
        try:
            data = current.parse_hotel_text(source_path)
            return Inspection(source_format, None, data, [], [])
        except (current.HotelToolError, area_common.AreaToolError) as exc:
            return Inspection(
                source_format,
                None,
                None,
                [],
                [f"CURRENT_VALIDATION|{exc}"],
            )
    if source_format not in LEGACY_FORMATS:
        return Inspection(
            source_format,
            None,
            None,
            [],
            ["FORMAT_UNKNOWN|旧形式または現行形式として識別できません"],
        )

    lines = text.splitlines()
    first_scene = next(
        (
            index
            for index, value in enumerate(lines)
            if current.SCENE_MARKER_RE.fullmatch(value.strip())
        ),
        len(lines),
    )
    issues: list[str] = []
    metadata = {
        "title": _aliased_value(
            lines, ("title",), issues, "title", end=first_scene
        ),
        "description": _aliased_value(
            lines,
            ("description", 'meta name="description"'),
            issues,
            "description",
            end=first_scene,
        ),
        "canonical": _aliased_value(
            lines,
            ("canonical", 'link rel="canonical"'),
            issues,
            "canonical",
            end=first_scene,
        ),
        "image": _aliased_value(
            lines,
            ("image", 'meta property="og:image"'),
            issues,
            "image",
            end=first_scene,
        ),
        "page_title": _aliased_value(
            lines,
            ("page_title_h1 / パンくずリスト", "page_title_h1"),
            issues,
            "page_title",
            end=first_scene,
        ),
        "subtitle_h1": _aliased_value(
            lines, ("subtitle_h1",), issues, "subtitle_h1", end=first_scene
        ),
        "description_h1": _aliased_value(
            lines,
            ("description_h1", "description_h1（改行無し）"),
            issues,
            "description_h1",
            end=first_scene,
        ),
        "option": _aliased_value(
            lines, ("option",), issues, "option", end=first_scene
        ),
        "option_subtitle": _aliased_value(
            lines,
            ("option_subtitle",),
            issues,
            "option_subtitle",
            end=first_scene,
        ),
        "option_description": _aliased_value(
            lines,
            ("option_description",),
            issues,
            "option_description",
            end=first_scene,
        ),
    }
    for key in (
        "title",
        "description",
        "canonical",
        "image",
        "page_title",
        "subtitle_h1",
        "description_h1",
    ):
        if not metadata[key]:
            issues.append(f"REQUIRED_FIELD|{key}がありません")
    option_values = [
        metadata["option"],
        metadata["option_subtitle"],
        metadata["option_description"],
    ]
    if any(option_values) and not all(option_values):
        issues.append("OPTION_PARTIAL|option 3項目が揃っていません")
    data_start = next(
        (index for index, value in enumerate(lines) if value.strip() == "title"),
        0,
    )
    if current.PLACEHOLDER_RE.search("\n".join(lines[data_start:])):
        issues.append("PLACEHOLDER|元データにplaceholderが残っています")

    image1, image2 = _legacy_images(lines, issues)
    sections = _normalize_sections(lines, issues)
    converted = _serialize_current(metadata, image1, image2, sections)
    data, validation_error = _validate_converted(converted)
    if validation_error:
        issues.append(f"CURRENT_VALIDATION|{validation_error}")
    mapped = [
        key
        for key in (
            "title",
            "description",
            "canonical",
            "image",
            "img_1",
            "img_2",
            "page_title",
            "subtitle_h1",
            "description_h1",
        )
        if (
            metadata.get(key)
            or key == "img_1" and image1
            or key == "img_2" and image2
        )
    ]
    return Inspection(
        source_format,
        converted,
        data if not issues else None,
        mapped,
        list(dict.fromkeys(issues)),
    )


def resolve_input(value: str) -> Path:
    path = Path(value)
    if not path.is_absolute():
        path = path_config.REPO_ROOT / path
    path = path.resolve()
    try:
        path.relative_to(path_config.TEXT_HOTEL_DIR.resolve())
    except ValueError as exc:
        raise current.HotelToolError(
            f"input must be under Text_hotel_data: {value}"
        ) from exc
    if not path.is_file():
        raise current.HotelToolError(f"input does not exist: {value}")
    return path


def inspect_path(path: Path) -> Inspection:
    return inspect_text(read_utf8(path), path)


def print_inspection(path: Path, inspection: Inspection) -> None:
    print(f"INPUT={path}")
    print(f"SOURCE_FORMAT={inspection.source_format}")
    print("TARGET_FORMAT=CURRENT")
    if inspection.source_format == FORMAT_CURRENT and inspection.data and not inspection.issues:
        print("LEGACY_TEXT_STATUS=NOT_REQUIRED")
        print("CURRENT_TEXT_STATUS=VALID")
        print(f"CANONICAL_SLUG={inspection.data.slug}")
        return
    if inspection.ready:
        print("LEGACY_TEXT_STATUS=READY_TO_CONVERT")
        print(f"CANONICAL_SLUG={inspection.data.slug}")
    else:
        print("RESULT=STOP")
        print("LEGACY_TEXT_STATUS=STOP")
    for field in inspection.mapped_fields:
        print(f"MAPPED_FIELD={field}")
    for issue in inspection.issues:
        print(f"ISSUE={issue}")


def command_check(args: argparse.Namespace) -> int:
    path = resolve_input(args.input)
    inspection = inspect_path(path)
    print_inspection(path, inspection)
    if inspection.source_format == FORMAT_CURRENT and inspection.data and not inspection.issues:
        return 0
    return 0 if inspection.ready else 2


def _git_path_is_clean_and_tracked(path: Path) -> bool:
    relative = path.relative_to(path_config.REPO_ROOT).as_posix()
    tracked = subprocess.run(
        ["git", "-C", str(path_config.REPO_ROOT), "cat-file", "-e", f"HEAD:{relative}"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
        check=False,
    )
    unstaged = subprocess.run(
        ["git", "-C", str(path_config.REPO_ROOT), "diff", "--quiet", "--", relative],
        check=False,
    )
    staged = subprocess.run(
        ["git", "-C", str(path_config.REPO_ROOT), "diff", "--cached", "--quiet", "--", relative],
        check=False,
    )
    return tracked.returncode == 0 and unstaged.returncode == 0 and staged.returncode == 0


def command_convert(args: argparse.Namespace) -> int:
    path = resolve_input(args.input)
    inspection = inspect_path(path)
    print_inspection(path, inspection)
    if inspection.source_format == FORMAT_CURRENT and inspection.data and not inspection.issues:
        print("CONVERSION_STATUS=NOT_REQUIRED")
        return 0
    if not inspection.ready or inspection.converted_text is None:
        print("CONVERSION_STATUS=STOP")
        return 2

    if args.replace:
        if not _git_path_is_clean_and_tracked(path):
            raise current.HotelToolError(
                "replace requires a Git-tracked input with no staged or unstaged change"
            )
        destination = path
        original = read_utf8(path)
        area_common.atomic_write(destination, inspection.converted_text)
        try:
            current.parse_hotel_text(destination)
        except Exception:
            area_common.atomic_write(destination, original)
            raise
        print(f"CONVERTED_OUTPUT={destination}")
        print("CONVERSION_STATUS=REPLACED")
        print(
            "RECOVERY_COMMAND=git restore -- "
            + path.relative_to(path_config.REPO_ROOT).as_posix()
        )
        return 0

    destination = Path(args.output)
    if not destination.is_absolute():
        destination = path_config.REPO_ROOT / destination
    destination = destination.resolve()
    if destination == path:
        raise current.HotelToolError("use --replace for in-place conversion")
    if destination.exists():
        raise current.HotelToolError(f"output already exists: {destination}")
    if destination.suffix.lower() != ".txt":
        raise current.HotelToolError("output must use the .txt extension")
    if not destination.parent.is_dir():
        raise current.HotelToolError(f"output directory does not exist: {destination.parent}")
    area_common.atomic_write(destination, inspection.converted_text)
    try:
        current.parse_hotel_text(destination)
    except Exception:
        destination.unlink(missing_ok=True)
        raise
    print(f"CONVERTED_OUTPUT={destination}")
    print("CONVERSION_STATUS=CREATED")
    return 0


def command_self_test(_args: argparse.Namespace) -> int:
    fixture = f"""title
Legacy Fixture｜鹿児島市でデリヘルが呼べるホテル
{LIGHT_SEPARATOR}
meta name="description"
鹿児島市でデリヘルが呼べるホテルLegacy Fixture
{LIGHT_SEPARATOR}
link rel="canonical"
https://www.55810.com/kagoshima-deliveryhealth-hotel-legacyfixture.php
{LIGHT_SEPARATOR}
meta property="og:image"
https://www.55810.com/imgHtml/new_202601/hotel/legacyfixture_1.jpg
{LIGHT_SEPARATOR}
記事
{LIGHT_SEPARATOR}
写真
src : ./imgHtml/new_202601/hotel/legacyfixture_1.jpg
{LIGHT_SEPARATOR}
page_title_h1
鹿児島市でデリヘルが呼べるホテル「Legacy Fixture」
subtitle_h1
Legacy Fixtureの案内です。
description_h1
Legacy Fixtureの説明です。
{LIGHT_SEPARATOR}
scene1
Legacy Fixtureに呼べる「鹿児島の人気デリヘル店」
・CANDY（内容は同じ）
{LIGHT_SEPARATOR}
写真
src : ./imgHtml/new_202601/hotel/legacyfixture_2.jpg
{LIGHT_SEPARATOR}
scene2
基本情報
subtitle_2_1
ホテル名
description_2_1
Legacy Fixture
subtitle_2_2
URL
description_2_2
https://example.com/legacy-fixture
subtitle_2_3
住所
description_2_3
鹿児島県鹿児島市テスト1-2-3
"""
    with tempfile.TemporaryDirectory(prefix="candy-hotel-legacy-self-test-") as directory:
        source = Path(directory) / "legacy.txt"
        source.write_text(fixture, encoding="utf-8")
        inspection = inspect_text(fixture, source)
        if not inspection.ready or not inspection.converted_text or not inspection.data:
            raise current.HotelToolError(
                "legacy ready self-test failed: " + " / ".join(inspection.issues)
            )
        if inspection.data.slug != "legacyfixture":
            raise current.HotelToolError("legacy slug self-test failed")
        converted = Path(directory) / "converted.txt"
        converted.write_text(inspection.converted_text, encoding="utf-8")
        reparsed = current.parse_hotel_text(converted)
        if reparsed.hotel_name != "Legacy Fixture" or reparsed.slug != "legacyfixture":
            raise current.HotelToolError("converted parse self-test failed")
        numbered_fixture = (
            fixture
            .replace('meta name="description"', "description")
            .replace('link rel="canonical"', "canonical")
            .replace('meta property="og:image"', "image")
        )
        numbered = inspect_text(numbered_fixture, source)
        if (
            numbered.source_format != FORMAT_LEGACY_NUMBERED
            or not numbered.ready
            or not numbered.data
        ):
            raise current.HotelToolError(
                "legacy numbered self-test failed: " + " / ".join(numbered.issues)
            )
        ambiguous = fixture.replace(
            "scene1",
            "写真\nsrc : ./imgHtml/new_202601/hotel/legacyfixture_extra.jpg\n"
            + LIGHT_SEPARATOR
            + "\nscene1",
        )
        rejected = inspect_text(ambiguous, source)
        if not any(issue.startswith("LEGACY_IMAGE_COUNT|") for issue in rejected.issues):
            raise current.HotelToolError("ambiguous image self-test failed")
        conflict = fixture.replace(
            'meta name="description"',
            "description\n異なるdescription\n"
            + LIGHT_SEPARATOR
            + '\nmeta name="description"',
        )
        conflicted = inspect_text(conflict, source)
        if not any(issue.startswith("FIELD_CONFLICT|") for issue in conflicted.issues):
            raise current.HotelToolError("metadata conflict self-test failed")
    print("LEGACY_TEXT_SELF_TEST=PASS")
    return 0


def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="CANDY legacy hotel Text migration")
    commands = parser.add_subparsers(dest="command", required=True)
    check = commands.add_parser("legacy-check")
    check.add_argument("--input", required=True)
    check.set_defaults(func=command_check)
    convert = commands.add_parser("legacy-convert")
    convert.add_argument("--input", required=True)
    destination = convert.add_mutually_exclusive_group(required=True)
    destination.add_argument("--output")
    destination.add_argument("--replace", action="store_true")
    convert.set_defaults(func=command_convert)
    self_test = commands.add_parser("legacy-self-test")
    self_test.set_defaults(func=command_self_test)
    return parser


def main() -> int:
    for stream in (sys.stdout, sys.stderr):
        if hasattr(stream, "reconfigure"):
            stream.reconfigure(encoding="utf-8", errors="backslashreplace")
    args = create_parser().parse_args()
    try:
        return args.func(args)
    except (current.HotelToolError, area_common.AreaToolError, OSError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
