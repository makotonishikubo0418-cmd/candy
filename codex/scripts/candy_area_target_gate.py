# -*- coding: utf-8 -*-
"""Select and verify CANDY area page production targets.

This gate separates text quality from new-page eligibility.
"""

from __future__ import annotations

import argparse
import hashlib
import re
import subprocess
from dataclasses import dataclass
from pathlib import Path

import candy_page_common as common


REPO_ROOT = common.REPO_ROOT
HP_ROOT = common.HP_ROOT
TEXT_ROOT = common.TEXT_AREA_DIR
QUEUE_PATH = common.DOCS_DIR / "CANDY_AREA_105_PAGE_QUEUE.md"


@dataclass(frozen=True)
class Candidate:
    source: Path
    direct: Path
    slug: str
    region: str
    rank: tuple[int, str, str]


def rel(path: Path) -> str:
    return path.relative_to(REPO_ROOT).as_posix()


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def extract_slug(text: str) -> str:
    match = re.search(r"kagoshima-deliveryhealth-area-([a-z0-9-]+)\.php", text)
    return match.group(1) if match else ""


def region_name(path: Path) -> str:
    return re.sub(r"_テンプレート\s*\.txt$", "", path.name).removesuffix(".txt")


def git_tracked(path: Path) -> bool:
    relative = rel(path)
    completed = subprocess.run(
        ["git", "-c", "safe.directory=*", "-C", str(REPO_ROOT), "cat-file", "-e", f"HEAD:{relative}"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
        check=False,
    )
    return completed.returncode == 0


def artifact_paths(slug: str) -> list[Path]:
    return [
        HP_ROOT / f"kagoshima-deliveryhealth-area-{slug}.php",
        HP_ROOT / "source" / f"kagoshima-deliveryhealth-area-{slug}.html",
        HP_ROOT / "includefile" / f"dataset_kagoshima-deliveryhealth-area-{slug}.php",
    ]


def blocking_shared_paths() -> list[Path]:
    return [
        HP_ROOT / "includefile" / "dataset_base.php",
        HP_ROOT / "sitemap.xml",
    ]


def area_path() -> Path:
    return HP_ROOT / "source" / "area.html"


def image_paths(slug: str) -> list[Path]:
    return [
        HP_ROOT / "imgHtml" / "new_202601" / "area" / f"kagoshima-deliveryhealth-area-{slug}_1.jpg",
        HP_ROOT / "imgHtml" / "new_202601" / "area" / f"kagoshima-deliveryhealth-area-{slug}_2.jpg",
    ]


def accepted_image_paths(slug: str) -> list[Path]:
    return [
        TEXT_ROOT / "画像データ" / f"kagoshima-deliveryhealth-area-{slug}_1.jpg",
        TEXT_ROOT / "画像データ" / f"kagoshima-deliveryhealth-area-{slug}_2.jpg",
    ]


def area_list_reasons(candidate: Candidate) -> list[str]:
    path = area_path()
    if not path.exists():
        return ["area list missing: " + rel(path)]
    source = read_text(path)
    link = f'./kagoshima-deliveryhealth-area-{candidate.slug}.php'
    count = source.count(link)
    reasons: list[str] = []
    if count > 1:
        reasons.append(f"area list duplicate target link: {link} count={count}")
    pattern = re.compile(
        rf'href="\./kagoshima-deliveryhealth-area-([^"]+)\.php"[^>]*>{re.escape(candidate.region)}</a>'
    )
    slugs = sorted(set(match.group(1) for match in pattern.finditer(source)))
    mismatches = [slug for slug in slugs if slug != candidate.slug]
    if mismatches:
        reasons.append(
            f"area list same-region slug mismatch: canonical={candidate.slug} existing_region_slugs={','.join(mismatches)}"
        )
    return reasons


def image_pair_reasons(candidate: Candidate) -> list[str]:
    accepted = accepted_image_paths(candidate.slug)
    public = image_paths(candidate.slug)
    accepted_exists = [path.is_file() for path in accepted]
    public_exists = [path.is_file() for path in public]
    reasons: list[str] = []
    if any(accepted_exists) and not all(accepted_exists):
        reasons.append(
            "accepted image pair is partial: "
            + ", ".join(rel(path) for path, exists in zip(accepted, accepted_exists) if not exists)
        )
    if any(public_exists) and not all(public_exists):
        reasons.append(
            "public image pair is partial: "
            + ", ".join(rel(path) for path, exists in zip(public, public_exists) if not exists)
        )
    if reasons:
        return reasons
    if not all(accepted_exists) and not all(public_exists):
        return [
            "no complete accepted or public image pair: "
            + ", ".join(rel(path) for path in accepted + public)
        ]
    if all(accepted_exists) and all(public_exists):
        for accepted_path, public_path in zip(accepted, public):
            if hashlib.sha256(accepted_path.read_bytes()).digest() != hashlib.sha256(public_path.read_bytes()).digest():
                reasons.append(
                    f"accepted/public image hash mismatch: {rel(accepted_path)} != {rel(public_path)}"
                )
    return reasons


def check_candidate(candidate: Candidate) -> tuple[bool, list[str]]:
    reasons: list[str] = []
    if not candidate.slug:
        reasons.append("canonical slug missing")
    if not git_tracked(candidate.source):
        reasons.append(f"source input is not tracked in HEAD: {rel(candidate.source)}")
    if candidate.source != candidate.direct and candidate.direct.exists():
        reasons.append(f"direct input already exists while source is classified copy: {rel(candidate.direct)}")
    for path in artifact_paths(candidate.slug):
        if path.exists():
            reasons.append(f"existing page artifact: {rel(path)}")
    reasons.extend(area_list_reasons(candidate))
    needle = f"kagoshima-deliveryhealth-area-{candidate.slug}"
    for path in blocking_shared_paths():
        if path.exists() and needle in read_text(path):
            reasons.append(f"existing shared registration: {rel(path)}")
    reasons.extend(image_pair_reasons(candidate))
    return not reasons, reasons


def candidate_from_path(path: Path, rank_prefix: int) -> Candidate | None:
    text = read_text(path)
    slug = extract_slug(text)
    direct = TEXT_ROOT / path.name
    return Candidate(path, direct, slug, region_name(path), (rank_prefix, path.name, slug))


def latest_classification_ok_dirs() -> list[Path]:
    dirs = sorted(TEXT_ROOT.glob("分類_*/01_間違い無し"), key=lambda p: p.parent.name, reverse=True)
    return [p for p in dirs if p.is_dir()][:1]


def available_candidates() -> list[Candidate]:
    candidates: list[Candidate] = []
    seen_names: set[str] = set()
    for path in sorted(TEXT_ROOT.glob("*.txt"), key=lambda p: p.name):
        candidate = candidate_from_path(path, 0)
        if candidate:
            candidates.append(candidate)
            seen_names.add(path.name)
    for ok_dir in latest_classification_ok_dirs():
        for path in sorted(ok_dir.glob("*.txt"), key=lambda p: p.name):
            if path.name in seen_names:
                continue
            candidate = candidate_from_path(path, 1)
            if candidate:
                candidates.append(candidate)
                seen_names.add(path.name)
    return candidates


def ready_queue_rows() -> list[tuple[int, str, str]]:
    if not QUEUE_PATH.is_file():
        return []
    rows: list[tuple[int, str, str]] = []
    for line in read_text(QUEUE_PATH).splitlines():
        parts = line.split("|")
        if len(parts) < 7 or not parts[1].strip().isdigit():
            continue
        if parts[4].strip() != "READY_CANDIDATE":
            continue
        rows.append((int(parts[1].strip()), parts[2].strip(), parts[3].strip().strip("`")))
    return rows


def iter_candidates() -> list[Candidate]:
    by_slug: dict[str, list[Candidate]] = {}
    for candidate in available_candidates():
        by_slug.setdefault(candidate.slug, []).append(candidate)
    ordered: list[Candidate] = []
    for queue_number, region, slug in ready_queue_rows():
        matches = [candidate for candidate in by_slug.get(slug, []) if candidate.region == region]
        if len(matches) != 1:
            continue
        candidate = matches[0]
        ordered.append(
            Candidate(
                candidate.source,
                candidate.direct,
                candidate.slug,
                candidate.region,
                (queue_number, candidate.source.name, candidate.slug),
            )
        )
    return ordered


def select_next_candidate() -> tuple[Candidate | None, list[tuple[Candidate, list[str]]]]:
    skipped: list[tuple[Candidate, list[str]]] = []
    for candidate in iter_candidates():
        ok, reasons = check_candidate(candidate)
        if ok:
            return candidate, skipped
        skipped.append((candidate, reasons))
    return None, skipped


def command_next(args: argparse.Namespace) -> int:
    candidate, skipped = select_next_candidate()
    if candidate:
        if args.restore and candidate.source != candidate.direct:
            print("RESULT=STOP")
            print("REASON=classified input must remain at its tracked source path; publish SOURCE directly")
            print(f"SOURCE={rel(candidate.source)}")
            return 2
        print(f"NEW_PAGE_TARGET_OK={candidate.slug}")
        print(f"REGION={candidate.region}")
        print(f"INPUT={rel(candidate.source)}")
        print(f"SOURCE={rel(candidate.source)}")
        print("RESTORE_REQUIRED=no")
        print(f"QUEUE_NUMBER={candidate.rank[0]}")
        print(f"SKIPPED_COUNT={len(skipped)}")
        return 0
    print("RESULT=STOP")
    print("REASON=no eligible new area page target")
    print(f"CHECKED_COUNT={len(skipped)}")
    for candidate, reasons in skipped[:30]:
        print(f"SKIP={candidate.region}\t{candidate.slug}\t" + " / ".join(reasons))
    return 2


def command_check(args: argparse.Namespace) -> int:
    path = (REPO_ROOT / args.input).resolve()
    if not path.exists():
        print("RESULT=STOP")
        print(f"REASON=input does not exist: {args.input}")
        return 2
    candidate = candidate_from_path(path, 0)
    if not candidate:
        print("RESULT=STOP")
        print("REASON=could not read candidate")
        return 2
    ok, reasons = check_candidate(candidate)
    if ok:
        print(f"NEW_PAGE_TARGET_OK={candidate.slug}")
        print(f"REGION={candidate.region}")
        print(f"INPUT={rel(candidate.direct)}")
        return 0
    print("RESULT=STOP")
    print(f"REGION={candidate.region}")
    print(f"SLUG={candidate.slug}")
    for reason in reasons:
        print(f"REASON={reason}")
    return 2


def main() -> int:
    parser = argparse.ArgumentParser(description="CANDY area target gate")
    sub = parser.add_subparsers(dest="command", required=True)
    next_parser = sub.add_parser("target-next")
    next_parser.add_argument("--restore", action="store_true", help="move the selected classified txt back to Text_area_data")
    check_parser = sub.add_parser("target-check")
    check_parser.add_argument("--input", required=True)
    args = parser.parse_args()
    if args.command == "target-next":
        return command_next(args)
    if args.command == "target-check":
        return command_check(args)
    return 2


if __name__ == "__main__":
    raise SystemExit(main())
