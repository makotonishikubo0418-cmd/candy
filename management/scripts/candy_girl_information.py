#!/usr/bin/env python3
"""Manage the canonical local woman-information ledger for CANDY."""

from __future__ import annotations

import argparse
import html
import json
import re
import sys
import tempfile
from dataclasses import dataclass
from pathlib import Path


REPO_ROOT = Path(__file__).resolve().parents[2]
DATA_PATH = REPO_ROOT / "codex" / "data" / "CANDY_GIRL_INFORMATION.json"
PUBLIC_IMAGE_DIR = REPO_ROOT / "HP" / "imgHtml" / "new_202601" / "girl"
LOCAL_IMAGE_DIR = REPO_ROOT / "Text_girl_data" / "画像データ"
LEGACY_TEMPLATE_PATH = REPO_ROOT / "HP" / "source" / "template_girls.html"
PUBLIC = "PUBLIC"
LOCAL_ONLY = "LOCAL_ONLY"
VALID_IMAGE_STATES = {PUBLIC, LOCAL_ONLY}


class GirlInformationError(RuntimeError):
    pass


@dataclass(frozen=True)
class GirlRecord:
    key: str
    profile_no: int | None
    profile_url: str
    name: str
    title_html: str
    summary_html: str
    description_html: str
    style_html: str
    hobby_html: str
    image_pc: str
    image_sp: str
    image_alt: str
    loading: str
    image_state: str

    @classmethod
    def from_mapping(cls, value: dict[str, object]) -> "GirlRecord":
        try:
            return cls(
                key=str(value["key"]),
                profile_no=None if value["profile_no"] is None else int(value["profile_no"]),
                profile_url=str(value["profile_url"]),
                name=str(value["name"]),
                title_html=str(value["title_html"]),
                summary_html=str(value["summary_html"]),
                description_html=str(value["description_html"]),
                style_html=str(value["style_html"]),
                hobby_html=str(value["hobby_html"]),
                image_pc=str(value["image_pc"]),
                image_sp=str(value["image_sp"]),
                image_alt=str(value["image_alt"]),
                loading=str(value["loading"]),
                image_state=str(value["image_state"]),
            )
        except (KeyError, TypeError, ValueError) as exc:
            raise GirlInformationError(f"Invalid woman-information record: {value!r}") from exc

    def to_mapping(self) -> dict[str, object]:
        return {
            "key": self.key,
            "profile_no": self.profile_no,
            "profile_url": self.profile_url,
            "name": self.name,
            "title_html": self.title_html,
            "summary_html": self.summary_html,
            "description_html": self.description_html,
            "style_html": self.style_html,
            "hobby_html": self.hobby_html,
            "image_pc": self.image_pc,
            "image_sp": self.image_sp,
            "image_alt": self.image_alt,
            "loading": self.loading,
            "image_state": self.image_state,
        }


def read_utf8(path: Path) -> str:
    return path.read_text(encoding="utf-8-sig")


def plain_text(value: str) -> str:
    return html.unescape(re.sub(r"<[^>]+>", "", value)).strip()


def required_match(pattern: str, source: str, label: str, flags: int = 0) -> re.Match[str]:
    match = re.search(pattern, source, flags)
    if not match:
        raise GirlInformationError(f"Legacy woman block is missing {label}")
    return match


def parse_legacy_template(path: Path, local_only_keys: set[str]) -> list[GirlRecord]:
    source = read_utf8(path)
    matches = list(
        re.finditer(
            r"(?ms)^\s*<!-- ([a-z0-9_]+) -->\s*\n(?P<block>\s*<li\b.*?^\s*</li>)",
            source,
        )
    )
    if not matches:
        raise GirlInformationError("Legacy template contains no woman blocks")

    records: list[GirlRecord] = []
    for match in matches:
        key = match.group(1)
        block = match.group("block")
        profile = required_match(r'<a href="(\./girls(?:\.php\?no=[0-9]+|_list\.php))" class="bt-pk-m">\s*([^<]+?)\s+詳細</a>', block, "profile button")
        pc_image = required_match(r'<img src="\./imgHtml/new_202601/girl/([^"]+)" alt="([^"]*)"[^>]*\bloading="([^"]+)"', block, "PC image")
        sp_image = required_match(r'<source media="\(max-width: 768px\)" srcset="\./imgHtml/new_202601/girl/([^"]+)">', block, "SP image")
        title = required_match(r'<h3[^>]*>(.*?)</h3>', block, "title", re.DOTALL)
        summary = required_match(r'<div class="lpb_7 fs_md3"><strong>(.*?)</strong></div>', block, "summary", re.DOTALL)
        description = required_match(r'<div class="lpb_30 fs_md3">(.*?)</div>', block, "description", re.DOTALL)
        style = required_match(r'<td>スタイル</td>\s*<td>(.*?)</td>', block, "style", re.DOTALL)
        hobby = required_match(r'<td>趣味</td>\s*<td>(.*?)</td>', block, "hobby", re.DOTALL)
        record = GirlRecord(
            key=key,
            profile_no=(
                int(required_match(r"[?&]no=([0-9]+)", profile.group(1), "profile number").group(1))
                if "girls.php" in profile.group(1)
                else None
            ),
            profile_url=profile.group(1),
            name=plain_text(profile.group(2)),
            title_html=title.group(1).strip(),
            summary_html=summary.group(1).strip(),
            description_html=description.group(1).strip(),
            style_html=style.group(1).strip(),
            hobby_html=hobby.group(1).strip(),
            image_pc=pc_image.group(1),
            image_sp=sp_image.group(1),
            image_alt=pc_image.group(2),
            loading=pc_image.group(3),
            image_state=LOCAL_ONLY if key in local_only_keys else PUBLIC,
        )
        records.append(record)

    found_keys = {record.key for record in records}
    unknown_local_only = sorted(local_only_keys - found_keys)
    if unknown_local_only:
        raise GirlInformationError("Unknown local-only keys: " + ", ".join(unknown_local_only))
    validate_records(records)
    return records


def validate_records(records: list[GirlRecord]) -> None:
    if not records:
        raise GirlInformationError("Woman-information ledger is empty")
    keys: set[str] = set()
    profile_numbers: set[int] = set()
    image_names: set[str] = set()
    errors: list[str] = []
    for record in records:
        if not re.fullmatch(r"[a-z0-9_]+", record.key):
            errors.append(f"invalid key: {record.key}")
        if record.key in keys:
            errors.append(f"duplicate key: {record.key}")
        keys.add(record.key)
        if record.profile_no is not None:
            if record.profile_no <= 0 or record.profile_no in profile_numbers:
                errors.append(f"invalid or duplicate profile_no: {record.profile_no}")
            profile_numbers.add(record.profile_no)
            if record.profile_url != f"./girls.php?no={record.profile_no}":
                errors.append(f"profile URL/number mismatch for {record.key}: {record.profile_url}")
        elif record.profile_url != "./girls_list.php":
            errors.append(f"invalid profile URL without number for {record.key}: {record.profile_url}")
        if record.image_pc != f"{record.key}_1.jpg":
            errors.append(f"unexpected PC image for {record.key}: {record.image_pc}")
        if record.image_sp != f"{record.key}_1_sp.jpg":
            errors.append(f"unexpected SP image for {record.key}: {record.image_sp}")
        for image_name in (record.image_pc, record.image_sp):
            if image_name in image_names:
                errors.append(f"duplicate image name: {image_name}")
            image_names.add(image_name)
        if record.loading not in {"eager", "lazy"}:
            errors.append(f"invalid loading value for {record.key}: {record.loading}")
        if record.image_state not in VALID_IMAGE_STATES:
            errors.append(f"invalid image_state for {record.key}: {record.image_state}")
        for label, value in (
            ("name", record.name),
            ("title_html", record.title_html),
            ("summary_html", record.summary_html),
            ("description_html", record.description_html),
            ("style_html", record.style_html),
            ("hobby_html", record.hobby_html),
            ("image_alt", record.image_alt),
        ):
            if not value.strip():
                errors.append(f"empty {label} for {record.key}")
    if errors:
        raise GirlInformationError("Woman-information validation failed:\n- " + "\n- ".join(errors))


def load_records(path: Path = DATA_PATH) -> list[GirlRecord]:
    try:
        payload = json.loads(read_utf8(path))
    except (OSError, json.JSONDecodeError) as exc:
        raise GirlInformationError(f"Cannot load woman-information ledger: {path}") from exc
    if payload.get("schema_version") != 1 or not isinstance(payload.get("women"), list):
        raise GirlInformationError("Unsupported woman-information ledger schema")
    records = [GirlRecord.from_mapping(value) for value in payload["women"]]
    validate_records(records)
    return records


def write_records(path: Path, records: list[GirlRecord]) -> None:
    payload = {
        "schema_version": 1,
        "responsibility": "Canonical local woman information and public-image state",
        "women": [record.to_mapping() for record in records],
    }
    path.parent.mkdir(parents=True, exist_ok=True)
    content = json.dumps(payload, ensure_ascii=False, indent=2) + "\n"
    with tempfile.NamedTemporaryFile("w", encoding="utf-8", newline="\n", delete=False, dir=path.parent, prefix=f".{path.name}.", suffix=".tmp") as stream:
        stream.write(content)
        temporary = Path(stream.name)
    temporary.replace(path)


def render_block(record: GirlRecord) -> str:
    if record.image_state != PUBLIC:
        raise GirlInformationError(f"Local-only woman cannot be rendered publicly: {record.key}")
    profile_open = f'<a href="{record.profile_url}" class="girls-info-img-link">' if record.profile_no is not None else ""
    profile_close = "</a>" if record.profile_no is not None else ""
    detail = (
        f'<div class="lmt_20"><a href="{record.profile_url}" class="bt-pk-m">{record.name} 詳細</a></div>'
        if record.profile_no is not None
        else ""
    )
    return f'''\t\t\t\t\t\t<!-- {record.key} -->
\t\t\t\t\t\t<li class="girls-info-item">
\t\t\t\t\t\t\t<div class="girls-info bg_f lmt_20">
\t\t\t\t\t\t\t\t<div class="girls-info-img-wrap">
\t\t\t\t\t\t\t\t\t{profile_open}
\t\t\t\t\t\t\t\t\t\t<picture>
\t\t\t\t\t\t\t\t\t\t\t<source media="(max-width: 768px)" srcset="./imgHtml/new_202601/girl/{record.image_sp}">
\t\t\t\t\t\t\t\t\t\t\t<img src="./imgHtml/new_202601/girl/{record.image_pc}" alt="{record.image_alt}" width="300" height="498" loading="{record.loading}" class="nolazy">
\t\t\t\t\t\t\t\t\t\t</picture>
\t\t\t\t\t\t\t\t\t{profile_close}
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class="girls-info-text lp_25">
\t\t\t\t\t\t\t\t\t<h3 class="lpb_15 fs_xl fc_p">{record.title_html}</h3>
\t\t\t\t\t\t\t\t\t<div class="lpb_7 fs_md3"><strong>{record.summary_html}</strong></div>
\t\t\t\t\t\t\t\t\t<div class="lpb_30 fs_md3">{record.description_html}</div>
\t\t\t\t\t\t\t\t\t<table class="campaign-table fs_md3">
\t\t\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t\t\t<td>スタイル</td>
\t\t\t\t\t\t\t\t\t\t\t<td>{record.style_html}</td>
\t\t\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t\t\t<td>趣味</td>
\t\t\t\t\t\t\t\t\t\t\t<td>{record.hobby_html}</td>
\t\t\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t\t\t</table>
\t\t\t\t\t\t\t\t\t{detail}
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</li>'''


def check_storage(records: list[GirlRecord]) -> None:
    errors: list[str] = []
    for record in records:
        public_paths = [PUBLIC_IMAGE_DIR / record.image_pc, PUBLIC_IMAGE_DIR / record.image_sp]
        local_paths = [LOCAL_IMAGE_DIR / record.image_pc, LOCAL_IMAGE_DIR / record.image_sp]
        if record.image_state == PUBLIC:
            for path in public_paths:
                if not path.is_file():
                    errors.append(f"missing public image: {path.relative_to(REPO_ROOT)}")
            for path in local_paths:
                if path.exists():
                    errors.append(f"unexpected duplicate local-only image: {path.relative_to(REPO_ROOT)}")
        else:
            for path in local_paths:
                if not path.is_file():
                    errors.append(f"missing local-only image: {path.relative_to(REPO_ROOT)}")
            for path in public_paths:
                if path.exists():
                    errors.append(f"local-only image remains public: {path.relative_to(REPO_ROOT)}")
    if LEGACY_TEMPLATE_PATH.exists():
        errors.append(f"obsolete public template remains: {LEGACY_TEMPLATE_PATH.relative_to(REPO_ROOT)}")
    if errors:
        raise GirlInformationError("Woman-image placement validation failed:\n- " + "\n- ".join(errors))


def run_import(args: argparse.Namespace) -> int:
    template = Path(args.template)
    if not template.is_absolute():
        template = REPO_ROOT / template
    output = Path(args.output)
    if not output.is_absolute():
        output = REPO_ROOT / output
    if output.exists() and not args.force:
        raise GirlInformationError(f"Output already exists; use --force: {output}")
    local_only_keys = {item for item in args.local_only_keys.split(",") if item}
    records = parse_legacy_template(template, local_only_keys)
    write_records(output, records)
    print(
        f"RESULT=IMPORT_OK women={len(records)} public={sum(item.image_state == PUBLIC for item in records)} "
        f"local_only={sum(item.image_state == LOCAL_ONLY for item in records)} output={output.relative_to(REPO_ROOT)}"
    )
    return 0


def run_check(_args: argparse.Namespace) -> int:
    records = load_records()
    check_storage(records)
    public = sum(record.image_state == PUBLIC for record in records)
    local_only = len(records) - public
    print(f"RESULT=CHECK_OK women={len(records)} public={public} local_only={local_only}")
    return 0


def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Manage CANDY woman information and image publication state")
    commands = parser.add_subparsers(dest="command", required=True)
    importer = commands.add_parser("import-template")
    importer.add_argument("--template", default="HP/source/template_girls.html")
    importer.add_argument("--output", default="codex/data/CANDY_GIRL_INFORMATION.json")
    importer.add_argument("--local-only-keys", required=True)
    importer.add_argument("--force", action="store_true")
    importer.set_defaults(func=run_import)
    checker = commands.add_parser("check")
    checker.set_defaults(func=run_check)
    return parser


def main() -> int:
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8", errors="backslashreplace")
    if hasattr(sys.stderr, "reconfigure"):
        sys.stderr.reconfigure(encoding="utf-8", errors="backslashreplace")
    args = create_parser().parse_args()
    try:
        return args.func(args)
    except (GirlInformationError, OSError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
