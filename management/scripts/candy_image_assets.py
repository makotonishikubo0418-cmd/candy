"""Read-only pair reconciliation and first installation for instructed hotel builds.

File checks never constitute visual acceptance, Git registration, or deployment.
"""
from __future__ import annotations

import argparse
import hashlib
import json
import os
import re
import sys
import tempfile
from functools import lru_cache
from pathlib import Path

import candy_page_common as common


def pair_paths(category: str, slug: str) -> tuple[list[Path], list[Path]]:
    if category not in {"area", "hotel"} or not re.fullmatch(r"[a-z0-9]+(?:-[a-z0-9]+)*", slug):
        raise common.PageToolError("invalid image category or slug")
    prefix = f"kagoshima-deliveryhealth-area-{slug}" if category == "area" else slug
    accepted = common.REPO_ROOT / f"Text_{category}_data" / "画像データ"
    public = common.HP_ROOT / "imgHtml" / "new_202601" / category
    return ([accepted / f"{prefix}_{i}.jpg" for i in (1, 2)], [public / f"{prefix}_{i}.jpg" for i in (1, 2)])


@lru_cache(maxsize=2048)
def _other_digest(path: Path, size: int, modified_ns: int) -> str:
    return hashlib.sha256(path.read_bytes()).hexdigest()


def inspect_pair(accepted: list[Path], public: list[Path], *, require_rgb: bool = True, check_other_hotels: bool = False) -> dict:
    from PIL import Image

    issues = []
    hashes = {}
    exists = [[path.is_file() for path in pair] for pair in (accepted, public)]
    for label, pair, flags in zip(("accepted", "public"), (accepted, public), exists):
        if any(flags) and not all(flags):
            issues.append(f"{label} image pair is partial")
        for path, present in zip(pair, flags):
            if path.is_symlink():
                issues.append(f"symlink image is not permitted: {path}")
            if not present:
                if path.exists():
                    issues.append(f"not an image file: {path}")
                continue
            try:
                with Image.open(path) as image:
                    if image.format != "JPEG" or image.size != (1000, 750) or (require_rgb and image.mode != "RGB"):
                        issues.append(f"invalid JPEG dimensions/color: {path} {image.format} {image.size} {image.mode}")
                    image.verify()
                with Image.open(path) as image:
                    image.load()
                hashes[str(path)] = hashlib.sha256(path.read_bytes()).hexdigest()
            except (OSError, ValueError) as exc:
                issues.append(f"unreadable image: {path}: {exc}")
        if all(flags) and all(str(path) in hashes for path in pair):
            if hashes[str(pair[0])] == hashes[str(pair[1])]:
                issues.append(f"{label} image pair has identical bytes")
    if check_other_hotels and all(exists[0]) and not issues:
        targets = set(hashes[str(path)] for path in accepted)
        for folder in {path.parent for path in accepted + public}:
            for other in sorted(folder.glob("*.jpg")):
                if other in accepted or other in public or not other.is_file():
                    continue
                stat = other.stat()
                if _other_digest(other, stat.st_size, stat.st_mtime_ns) in targets:
                    issues.append(f"image bytes duplicate another hotel: {other}")
    if issues:
        state = "STOP"
    elif all(exists[0]) and all(exists[1]):
        state = "INSTALLED_LOCAL" if all(hashes[str(a)] == hashes[str(p)] for a, p in zip(accepted, public)) else "REVIEW"
        if state == "REVIEW":
            issues.append("accepted/public image hash mismatch; do not overwrite")
    elif all(exists[0]):
        state = "ACCEPTED_SOURCE_PRESENT"
    elif all(exists[1]):
        state = "LEGACY_PUBLIC_ONLY"
    else:
        state = "MISSING"
    action = {"STOP": "resolve pair integrity", "REVIEW": "obtain target-specific replacement decision",
              "ACCEPTED_SOURCE_PRESENT": "confirm IMAGE RESULT: PASS; install for the instructed local page build without additional permission; Git/deployment remain separate",
              "INSTALLED_LOCAL": "verify Git registration and deployed bytes before page publication",
              "LEGACY_PUBLIC_ONLY": "preserve; verify tracked clean public dependencies",
              "MISSING": "create and visually accept the image pair"}[state]
    return {"state": state, "issues": issues, "hashes": hashes, "next_action": action,
            "visual_acceptance": "NOT_VERIFIED", "production": "NOT_VERIFIED"}


def install_pair(accepted: list[Path], public: list[Path], *, expected_hashes: dict[str, str] | None = None) -> None:
    """Exclusive first creation; on failure remove only bytes created by this call."""
    payloads = [path.read_bytes() for path in accepted]
    if expected_hashes is not None and any(
        hashlib.sha256(payload).hexdigest() != expected_hashes.get(str(path))
        for path, payload in zip(accepted, payloads)
    ):
        raise common.PageToolError("accepted image changed after validation; no files written")
    if any(path.exists() for path in public):
        raise common.PageToolError("first installation requires both public filenames absent")
    written = []
    try:
        for path, payload in zip(public, payloads):
            path.parent.mkdir(parents=True, exist_ok=True)
            # Stage complete bytes, then create the destination exclusively.
            # Unlike replace(), link() cannot overwrite a concurrently created file.
            handle, temporary = tempfile.mkstemp(prefix=".candy-image-", dir=path.parent)
            try:
                with os.fdopen(handle, "wb") as stream:
                    stream.write(payload)
                os.link(temporary, path)
                written.append((path, payload))
            finally:
                Path(temporary).unlink(missing_ok=True)
            if path.read_bytes() != payload:
                raise common.PageToolError(f"installed bytes differ: {path}")
        if any(path.read_bytes() != payload for path, payload in zip(accepted, payloads)):
            raise common.PageToolError("accepted image changed during installation")
    except BaseException:
        for path, payload in reversed(written):
            if path.is_file() and path.read_bytes() == payload:
                path.unlink()
        raise


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("command", choices=("image-status", "image-install"))
    parser.add_argument("--input", required=True)
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--image-result-pass", action="store_true", help="operator confirms visual acceptance for this exact pair")
    parser.add_argument("--publication-authorized", action="store_true", help="deprecated compatibility flag; not required for local installation and does not authorize publication")
    args = parser.parse_args()
    import candy_hotel_page
    path = (common.REPO_ROOT / args.input).resolve()
    if not path.is_relative_to(common.TEXT_HOTEL_DIR.resolve()):
        raise common.PageToolError("input must be under Text_hotel_data")
    data = candy_hotel_page.parse_hotel_text(path)
    accepted, public = pair_paths("hotel", data.slug)
    referenced = [common.HP_ROOT / value.removeprefix("./").split("?", 1)[0] for value in (data.image1, data.image2)]
    if referenced != public:
        raise common.PageToolError("input image names do not match canonical slug")
    status = inspect_pair(accepted, public, check_other_hotels=True)
    print("IMAGE_STATUS_JSON=" + json.dumps(status, ensure_ascii=False))
    if args.command == "image-status":
        return 2 if status["state"] in {"STOP", "REVIEW", "MISSING"} else 0
    if status["state"] != "ACCEPTED_SOURCE_PRESENT":
        raise common.PageToolError("first installation requires complete accepted pair and absent public pair")
    print("INSTALL_PATHS_JSON=" + json.dumps([str(path) for path in public], ensure_ascii=False))
    if args.dry_run:
        print("RESULT=IMAGE_INSTALL_PLAN; VISUAL_ACCEPTANCE=NOT_VERIFIED; ADDITIONAL_INSTALLATION_PERMISSION=NOT_REQUIRED_FOR_INSTRUCTED_PAGE")
        return 0
    if not args.image_result_pass:
        raise common.PageToolError("--image-result-pass is required after verifying image acceptance; no files written")
    install_pair(accepted, public, expected_hashes=status["hashes"])
    print("RESULT=INSTALLED_LOCAL; GIT=NOT_REGISTERED; PRODUCTION=NOT_VERIFIED")
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except (common.PageToolError, OSError, ValueError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        raise SystemExit(2)
