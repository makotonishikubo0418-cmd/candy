# -*- coding: utf-8 -*-
"""Select, check, and classify CANDY hotel page production targets."""

from __future__ import annotations

import argparse
import csv
import json
import re
import subprocess
from dataclasses import dataclass
from datetime import datetime
from pathlib import Path

import candy_hotel_page
import candy_hotel_text_migration
import candy_page_common as common

REPO_ROOT = common.REPO_ROOT
HP_ROOT = common.HP_ROOT
TEXT_ROOT = common.TEXT_HOTEL_DIR

READY = "作成可能"
IMAGE_MISSING = "画像なし"
INPUT_ERROR = "入力不備"
LEGACY_INPUT = "旧形式要変換"
EXISTING = "作成済み/登録あり"
INPUT_UNTRACKED = "入力未追跡"
DUPLICATE_SLUG = "重複slug"
ADMIN_DOC = "管理用txt"
OTHER_STOP = "その他停止"

BLOCK_INPUT_UNTRACKED = "入力未追跡"
BLOCK_MISSING_IMAGE = "画像なし"
BLOCK_EXISTING_ARTIFACT = "既存ページファイルあり"
BLOCK_EXISTING_REGISTRATION = "共有登録あり"
BLOCK_DUPLICATE_SLUG = "重複slug"
BLOCK_PLACEHOLDER = "placeholder残存"
BLOCK_UNSAFE_URL = "危険URL/http URL"
BLOCK_CANONICAL = "canonical slug不足"
BLOCK_UNKNOWN_SHOP = "未登録店舗"
BLOCK_H1_MISMATCH = "h1ホテル名不一致"
BLOCK_PARTIAL_BLOCK = "途中入力"
BLOCK_BASIC_INFO = "基本情報不足"
BLOCK_OTHER_INPUT = "その他入力不備"
BLOCK_ADMIN = "管理用txt"
BLOCK_LEGACY_FORMAT = "旧形式要変換"


@dataclass(frozen=True)
class Candidate:
    path: Path
    slug: str
    hotel_name: str
    canonical: str
    image1: str
    image2: str


@dataclass(frozen=True)
class Result:
    path: Path
    category: str
    reasons: list[str]
    blockers: list[str]
    slug: str = ""
    hotel_name: str = ""


def rel(path: Path) -> str:
    try:
        return path.resolve().relative_to(REPO_ROOT.resolve()).as_posix()
    except ValueError:
        return str(path)


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="replace")


def input_paths() -> list[Path]:
    return sorted(TEXT_ROOT.glob("*.txt"), key=lambda path: path.name)


def is_admin_doc(path: Path) -> bool:
    return path.name.lower().startswith("cursor") or path.name == "01_対応ホテル_テンプレート.txt"


def git_tracked(path: Path) -> bool:
    completed = subprocess.run(
        ["git", "-c", "safe.directory=*", "-C", str(REPO_ROOT), "cat-file", "-e", f"HEAD:{rel(path)}"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
        check=False,
    )
    return completed.returncode == 0


def artifact_paths(slug: str) -> list[Path]:
    return [
        HP_ROOT / f"kagoshima-deliveryhealth-hotel-{slug}.php",
        HP_ROOT / "source" / f"kagoshima-deliveryhealth-hotel-{slug}.html",
        HP_ROOT / "includefile" / f"dataset_kagoshima-deliveryhealth-hotel-{slug}.php",
    ]


def shared_paths() -> list[Path]:
    return [
        HP_ROOT / "includefile" / "dataset_base.php",
        HP_ROOT / "source" / "hotel.html",
        HP_ROOT / "sitemap.xml",
    ]


def image_file(relative_image: str) -> Path:
    return HP_ROOT / relative_image.removeprefix("./")


def candidate_from_path(path: Path) -> Candidate:
    data = candy_hotel_page.parse_hotel_text(path)
    return Candidate(path, data.slug, data.hotel_name, data.canonical, data.image1, data.image2)


def blockers_for_input_error(reason: str) -> list[str]:
    blockers: list[str] = []
    if "placeholder" in reason:
        blockers.append(BLOCK_PLACEHOLDER)
    if "http://" in reason or "安全なURLではありません" in reason:
        blockers.append(BLOCK_UNSAFE_URL)
    if "canonical slug" in reason:
        blockers.append(BLOCK_CANONICAL)
    if "template_shop.html" in reason:
        blockers.append(BLOCK_UNKNOWN_SHOP)
    if "page_title_h1" in reason:
        blockers.append(BLOCK_H1_MISMATCH)
    if "subtitle" in reason and "description" in reason:
        blockers.append(BLOCK_PARTIAL_BLOCK)
    if "基本情報" in reason:
        blockers.append(BLOCK_BASIC_INFO)
    return blockers or [BLOCK_OTHER_INPUT]


def check_candidate(candidate: Candidate) -> tuple[list[str], list[str]]:
    reasons: list[str] = []
    blockers: list[str] = []
    if not git_tracked(candidate.path):
        reasons.append(f"input is not tracked in HEAD: {rel(candidate.path)}")
        blockers.append(BLOCK_INPUT_UNTRACKED)
    for path in artifact_paths(candidate.slug):
        if path.exists():
            reasons.append(f"existing page artifact: {rel(path)}")
            blockers.append(BLOCK_EXISTING_ARTIFACT)
    needle = f"kagoshima-deliveryhealth-hotel-{candidate.slug}"
    for path in shared_paths():
        if path.exists() and needle in read_text(path):
            reasons.append(f"existing shared registration: {rel(path)}")
            blockers.append(BLOCK_EXISTING_REGISTRATION)
    for image in (candidate.image1, candidate.image2):
        path = image_file(image)
        if not path.exists():
            reasons.append(f"missing image: {rel(path)}")
            blockers.append(BLOCK_MISSING_IMAGE)
    return reasons, blockers


def category_from_blockers(blockers: list[str]) -> str:
    if BLOCK_DUPLICATE_SLUG in blockers:
        return DUPLICATE_SLUG
    if BLOCK_EXISTING_ARTIFACT in blockers or BLOCK_EXISTING_REGISTRATION in blockers:
        return EXISTING
    if BLOCK_MISSING_IMAGE in blockers:
        return IMAGE_MISSING
    if BLOCK_INPUT_UNTRACKED in blockers:
        return INPUT_UNTRACKED
    if blockers:
        return OTHER_STOP
    return READY


def scan_inputs() -> list[Result]:
    parsed: list[Candidate] = []
    results: list[Result] = []
    for path in input_paths():
        if is_admin_doc(path):
            results.append(Result(path, ADMIN_DOC, ["hotel inputではない管理用txt"], [BLOCK_ADMIN]))
            continue
        text = read_text(path)
        source_format = candy_hotel_text_migration.detect_source_format(text)
        if candy_hotel_text_migration.is_legacy_format(source_format):
            inspection = candy_hotel_text_migration.inspect_text(text, path)
            reasons = [
                f"legacy hotel Text format: {source_format}",
                f'run legacy-check: candy-hotel.cmd legacy-check --input "{rel(path)}"',
                *inspection.issues,
            ]
            blockers = [BLOCK_LEGACY_FORMAT]
            for reason in inspection.issues:
                blockers.extend(blockers_for_input_error(reason))
            results.append(
                Result(
                    path,
                    LEGACY_INPUT,
                    reasons,
                    sorted(set(blockers)),
                    inspection.data.slug if inspection.data else "",
                    inspection.data.hotel_name if inspection.data else "",
                )
            )
            continue
        try:
            parsed.append(candidate_from_path(path))
        except Exception as exc:  # noqa: BLE001 - classify malformed source text without crashing target selection.
            reason = str(exc)
            results.append(Result(path, INPUT_ERROR, [reason], blockers_for_input_error(reason)))
    slug_counts: dict[str, int] = {}
    for candidate in parsed:
        slug_counts[candidate.slug] = slug_counts.get(candidate.slug, 0) + 1
    for candidate in parsed:
        reasons, blockers = check_candidate(candidate)
        if slug_counts.get(candidate.slug, 0) > 1:
            reasons.append(f"duplicate slug: {candidate.slug} count={slug_counts[candidate.slug]}")
            blockers.append(BLOCK_DUPLICATE_SLUG)
        unique_blockers = sorted(set(blockers))
        results.append(
            Result(
                candidate.path,
                category_from_blockers(unique_blockers),
                reasons,
                unique_blockers,
                candidate.slug,
                candidate.hotel_name,
            )
        )
    return sorted(results, key=lambda result: result.path.name)


def counts(results: list[Result]) -> dict[str, int]:
    output: dict[str, int] = {}
    for result in results:
        output[result.category] = output.get(result.category, 0) + 1
    return dict(sorted(output.items()))


def blocker_counts(results: list[Result]) -> dict[str, int]:
    output: dict[str, int] = {}
    for result in results:
        if result.category == ADMIN_DOC:
            continue
        for blocker in result.blockers:
            output[blocker] = output.get(blocker, 0) + 1
    return dict(sorted(output.items()))


def write_report(results: list[Result]) -> tuple[Path, Path]:
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    latest = TEXT_ROOT / "制作可否管理_ホテル_最新.tsv"
    stamped = TEXT_ROOT / f"制作可否管理_ホテル_{timestamp}.tsv"
    for path in (latest, stamped):
        with path.open("w", encoding="utf-8", newline="") as handle:
            writer = csv.writer(handle, delimiter="\t")
            writer.writerow(["category", "file", "slug", "hotel", "blockers", "reasons"])
            for result in results:
                writer.writerow([
                    result.category,
                    result.path.name,
                    result.slug,
                    result.hotel_name,
                    " / ".join(result.blockers),
                    " / ".join(result.reasons),
                ])
    return latest, stamped


def existing_hotel_rows() -> list[dict[str, object]]:
    base = read_text(HP_ROOT / "includefile" / "dataset_base.php")
    hotel_list = read_text(HP_ROOT / "source" / "hotel.html")
    sitemap = read_text(HP_ROOT / "sitemap.xml")
    rows: list[dict[str, object]] = []
    for php in sorted(HP_ROOT.glob("kagoshima-deliveryhealth-hotel-*.php"), key=lambda path: path.name):
        slug = php.name.removeprefix("kagoshima-deliveryhealth-hotel-").removesuffix(".php")
        source = HP_ROOT / "source" / f"kagoshima-deliveryhealth-hotel-{slug}.html"
        dataset = HP_ROOT / "includefile" / f"dataset_kagoshima-deliveryhealth-hotel-{slug}.php"
        html = read_text(source) if source.exists() else ""
        images = sorted(set(re.findall(r"\./imgHtml/new_202601/hotel/[^'\"]+", html)))
        canonical = f"https://www.55810.com/kagoshima-deliveryhealth-hotel-{slug}.php"
        rows.append(
            {
                "slug": slug,
                "php": php.exists(),
                "source": source.exists(),
                "dataset": dataset.exists(),
                "images": len(images),
                "images_exist": all((HP_ROOT / image.removeprefix("./")).exists() for image in images),
                "dataset_base": f"kagoshima-deliveryhealth-hotel-{slug}.html" in base
                and f"dataset_kagoshima-deliveryhealth-hotel-{slug}.php" in base,
                "hotel_list": canonical in hotel_list or f"./kagoshima-deliveryhealth-hotel-{slug}.php" in hotel_list,
                "sitemap": canonical in sitemap,
            }
        )
    return rows


def command_next(_args: argparse.Namespace) -> int:
    results = scan_inputs()
    skipped = 0
    for result in results:
        if result.category == READY:
            print(f"NEW_HOTEL_TARGET_OK={result.slug}")
            print(f"HOTEL={result.hotel_name}")
            print(f"INPUT={rel(result.path)}")
            print(f"SKIPPED_COUNT={skipped}")
            return 0
        if result.category != ADMIN_DOC:
            skipped += 1
    print("RESULT=STOP")
    print("REASON=no eligible new hotel page target")
    print(f"CHECKED_COUNT={skipped}")
    print("COUNTS_JSON=" + json.dumps(counts(results), ensure_ascii=False))
    print("BLOCKER_COUNTS_JSON=" + json.dumps(blocker_counts(results), ensure_ascii=False))
    for result in [item for item in results if item.category != ADMIN_DOC][:40]:
        print(
            f"SKIP={result.path.name}\t{result.slug}\t{result.category}\t"
            + " / ".join(result.blockers)
            + "\t"
            + " / ".join(result.reasons)
        )
    return 2


def command_check(args: argparse.Namespace) -> int:
    path = (REPO_ROOT / args.input).resolve()
    results = scan_inputs()
    matches = [result for result in results if result.path.resolve() == path]
    if not matches:
        print("RESULT=STOP")
        print(f"REASON=input does not exist or is outside hotel inputs: {args.input}")
        return 2
    result = matches[0]
    if result.category == READY:
        print(f"NEW_HOTEL_TARGET_OK={result.slug}")
        print(f"HOTEL={result.hotel_name}")
        print(f"INPUT={rel(result.path)}")
        return 0
    print("RESULT=STOP")
    print(f"HOTEL={result.hotel_name}")
    print(f"SLUG={result.slug}")
    print(f"CATEGORY={result.category}")
    for blocker in result.blockers:
        print(f"BLOCKER={blocker}")
    for reason in result.reasons:
        print(f"REASON={reason}")
    return 2


def command_direct_check(args: argparse.Namespace) -> int:
    path = (REPO_ROOT / args.input).resolve()
    results = scan_inputs()
    matches = [result for result in results if result.path.resolve() == path]
    if not matches:
        print("RESULT=STOP")
        print("DIRECT_TEXT_STATUS=STOP")
        print(f"REASON=input does not exist or is outside hotel inputs: {args.input}")
        return 2
    result = matches[0]
    if result.category == READY:
        print(f"DIRECT_TEXT_INPUT_OK={result.slug}")
        print("DIRECT_TEXT_STATUS=READY_FOR_BUILD")
        print(f"NEW_HOTEL_TARGET_OK={result.slug}")
        print(f"HOTEL={result.hotel_name}")
        print(f"INPUT={rel(result.path)}")
        return 0
    if result.category == IMAGE_MISSING and set(result.blockers) == {BLOCK_MISSING_IMAGE}:
        print(f"DIRECT_TEXT_INPUT_OK={result.slug}")
        print("DIRECT_TEXT_STATUS=READY_FOR_IMAGES")
        print(f"HOTEL={result.hotel_name}")
        print(f"INPUT={rel(result.path)}")
        for reason in result.reasons:
            print(f"IMAGE_REQUIREMENT={reason}")
        return 0
    print("RESULT=STOP")
    print("DIRECT_TEXT_STATUS=STOP")
    print(f"HOTEL={result.hotel_name}")
    print(f"SLUG={result.slug}")
    print(f"CATEGORY={result.category}")
    for blocker in result.blockers:
        print(f"BLOCKER={blocker}")
    for reason in result.reasons:
        print(f"REASON={reason}")
    return 2


def command_audit(args: argparse.Namespace) -> int:
    results = scan_inputs()
    print(f"TOTAL={len(results)}")
    print("COUNTS_JSON=" + json.dumps(counts(results), ensure_ascii=False))
    print("BLOCKER_COUNTS_JSON=" + json.dumps(blocker_counts(results), ensure_ascii=False))
    for category, count in counts(results).items():
        print(f"CATEGORY={category}\tCOUNT={count}")
    for blocker, count in blocker_counts(results).items():
        print(f"BLOCKER={blocker}\tCOUNT={count}")
    for result in results:
        print(
            f"ROW={result.category}\t{result.path.name}\t{result.slug}\t{result.hotel_name}\t"
            + " / ".join(result.blockers)
            + "\t"
            + " / ".join(result.reasons)
        )
    if args.write_report:
        latest, stamped = write_report(results)
        print(f"REPORT_LATEST={rel(latest)}")
        print(f"REPORT_TIMESTAMPED={rel(stamped)}")
    return 0


def command_audit_existing(_args: argparse.Namespace) -> int:
    rows = existing_hotel_rows()
    print(f"EXISTING_HOTEL_COUNT={len(rows)}")
    for row in rows:
        print("EXISTING_HOTEL=" + json.dumps(row, ensure_ascii=False, sort_keys=True))
    hotel_list = read_text(HP_ROOT / "source" / "hotel.html")
    print("HOTEL_LIST_PLACEHOLDER_AAAAAAAAAA=" + str("kagoshima-deliveryhealth-hotel-aaaaaaaaaa.php" in hotel_list))
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(description="CANDY hotel target gate")
    sub = parser.add_subparsers(dest="command", required=True)
    sub.add_parser("target-next")
    check_parser = sub.add_parser("target-check")
    check_parser.add_argument("--input", required=True)
    direct_check_parser = sub.add_parser("direct-check")
    direct_check_parser.add_argument("--input", required=True)
    audit_parser = sub.add_parser("audit-inputs")
    audit_parser.add_argument("--write-report", action="store_true")
    sub.add_parser("audit-existing")
    args = parser.parse_args()
    if args.command == "target-next":
        return command_next(args)
    if args.command == "target-check":
        return command_check(args)
    if args.command == "direct-check":
        return command_direct_check(args)
    if args.command == "audit-inputs":
        return command_audit(args)
    if args.command == "audit-existing":
        return command_audit_existing(args)
    return 2


if __name__ == "__main__":
    raise SystemExit(main())
