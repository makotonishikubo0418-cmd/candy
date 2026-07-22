#!/usr/bin/env python3
"""Replace one approved Candy area-image pair and all controlled URL versions."""

from __future__ import annotations

import argparse
from dataclasses import dataclass
import os
from pathlib import Path, PurePosixPath
import re
import shutil
import stat
import subprocess
import sys


SCRIPT_DIR = Path(__file__).resolve().parent
REPOSITORY_ROOT = SCRIPT_DIR.parents[1]
GITHUB_SCRIPTS = REPOSITORY_ROOT / ".github" / "scripts"
if str(GITHUB_SCRIPTS) not in sys.path:
    sys.path.insert(0, str(GITHUB_SCRIPTS))

from candy_area_image_replacement_guard import (  # noqa: E402
    jpeg_dimensions,
    sha256,
    validate_area_image_replacements,
)


GIT = shutil.which("git") or "git"
PUBLIC_DIRECTORY = Path("HP/imgHtml/new_202601/area")
ACCEPTED_DIRECTORY = Path("Text_area_data/画像データ")
PUBLIC_URL_DIRECTORY = "imgHtml/new_202601/area"
TEXT_SUFFIXES = {
    ".css",
    ".htm",
    ".html",
    ".inc",
    ".js",
    ".json",
    ".php",
    ".svg",
    ".txt",
    ".xml",
}
SLUG_PATTERN = re.compile(r"[a-z0-9]+")
VERSION_QUERY_PATTERN = re.compile(rb"\?v=[0-9a-f]{7,64}")


class ReplacementError(RuntimeError):
    """The requested replacement cannot be completed safely."""


@dataclass(frozen=True)
class PlannedWrite:
    path: Path
    before: bytes
    after: bytes


def relative(root: Path, path: Path) -> str:
    return path.resolve().relative_to(root).as_posix()


def run_git(root: Path, *args: str, check: bool = True) -> subprocess.CompletedProcess[bytes]:
    result = subprocess.run(
        [GIT, "-c", "core.quotepath=false", *args],
        cwd=root,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=False,
    )
    if check and result.returncode:
        stderr = result.stderr.decode("utf-8", errors="replace").strip()
        raise ReplacementError(f"git {' '.join(args)} failed: {stderr}")
    return result


def require_repository(root: Path) -> None:
    resolved = run_git(root, "rev-parse", "--show-toplevel").stdout.decode().strip()
    if Path(resolved).resolve() != root:
        raise ReplacementError(f"Repository root mismatch: {resolved} != {root}")
    branch = run_git(root, "branch", "--show-current").stdout.decode().strip()
    if branch != "main":
        raise ReplacementError(f"Branch must be main: {branch or '(detached)'}")


def tracked_controlled_paths(root: Path) -> list[Path]:
    output = run_git(root, "ls-files", "-z", "--", "HP").stdout
    paths = output.decode("utf-8", errors="surrogateescape").split("\0")
    return sorted(
        root / Path(*PurePosixPath(path).parts)
        for path in paths
        if path and PurePosixPath(path).suffix.lower() in TEXT_SUFFIXES
    )


def require_tracked_clean(root: Path, paths: list[Path]) -> None:
    for path in sorted(set(paths)):
        repository_path = relative(root, path)
        tracked = run_git(root, "ls-files", "--error-unmatch", "--", repository_path, check=False)
        if tracked.returncode:
            raise ReplacementError(f"Target is not tracked by Git: {repository_path}")
        changed = run_git(root, "diff", "--quiet", "HEAD", "--", repository_path, check=False)
        if changed.returncode == 1:
            raise ReplacementError(f"Target already has an uncommitted change: {repository_path}")
        if changed.returncode not in {0, 1}:
            stderr = changed.stderr.decode("utf-8", errors="replace").strip()
            raise ReplacementError(f"Could not check target state: {repository_path} ({stderr})")


def read_input_image(path: Path, label: str) -> bytes:
    resolved = path.resolve()
    if resolved.is_symlink() or not resolved.is_file():
        raise ReplacementError(f"{label} is not a regular file: {path}")
    data = resolved.read_bytes()
    try:
        dimensions = jpeg_dimensions(data)
    except ValueError as exc:
        raise ReplacementError(f"{label} is not a valid complete JPEG: {path} ({exc})") from exc
    if dimensions != (1000, 750):
        raise ReplacementError(
            f"{label} dimensions are {dimensions[0]}x{dimensions[1]}; required 1000x750"
        )
    return data


def reference_pattern(filename: str) -> re.Pattern[bytes]:
    public_path = f"{PUBLIC_URL_DIRECTORY}/{filename}".encode("ascii")
    return re.compile(re.escape(public_path) + rb"(?P<query>\?[^\s\"'<>)]*)?")


def plan_reference_updates(
    root: Path, filenames_and_versions: list[tuple[str, str]]
) -> tuple[list[PlannedWrite], dict[str, list[str]]]:
    updates: dict[Path, tuple[bytes, bytes]] = {}
    locations: dict[str, list[str]] = {filename: [] for filename, _version in filenames_and_versions}
    for path in tracked_controlled_paths(root):
        original = path.read_bytes()
        changed = original
        for filename, version in filenames_and_versions:
            pattern = reference_pattern(filename)
            matches = list(pattern.finditer(changed))
            for match in matches:
                query = match.group("query")
                if query is not None and VERSION_QUERY_PATTERN.fullmatch(query) is None:
                    line = changed.count(b"\n", 0, match.start()) + 1
                    raise ReplacementError(
                        f"Unsupported existing image query: {relative(root, path)}:{line} "
                        f"{query.decode('ascii', errors='replace')}"
                    )
                line = changed.count(b"\n", 0, match.start()) + 1
                locations[filename].append(f"{relative(root, path)}:{line}")
            replacement = f"{PUBLIC_URL_DIRECTORY}/{filename}?v={version}".encode("ascii")
            changed = pattern.sub(replacement, changed)
        if changed != original:
            updates[path] = (original, changed)
    missing = [filename for filename, refs in locations.items() if not refs]
    if missing:
        raise ReplacementError("No controlled public reference found: " + ", ".join(missing))
    writes = [
        PlannedWrite(path=path, before=before, after=after)
        for path, (before, after) in sorted(updates.items(), key=lambda item: str(item[0]))
    ]
    return writes, locations


def atomic_write(path: Path, data: bytes) -> None:
    temporary = path.with_name(f".{path.name}.candy-area-replace-{os.getpid()}.tmp")
    if temporary.exists():
        raise ReplacementError(f"Temporary-file collision: {temporary}")
    mode = stat.S_IMODE(path.stat().st_mode)
    try:
        temporary.write_bytes(data)
        temporary.chmod(mode)
        os.replace(temporary, path)
    finally:
        if temporary.exists():
            temporary.unlink()


def print_plan(
    root: Path,
    slug: str,
    images: list[tuple[int, bytes]],
    writes: list[PlannedWrite],
    locations: dict[str, list[str]],
    write: bool,
) -> None:
    print(f"AREA_IMAGE_REPLACE_MODE={'WRITE' if write else 'PREVIEW'}")
    print(f"SLUG={slug}")
    for position, data in images:
        filename = f"kagoshima-deliveryhealth-area-{slug}_{position}.jpg"
        digest = sha256(data)
        print(f"IMAGE_{position}_SHA256={digest}")
        print(f"IMAGE_{position}_VERSION={digest[:7]}")
        print(f"ACCEPTED_TARGET={(ACCEPTED_DIRECTORY / filename).as_posix()}")
        print(f"PUBLIC_TARGET={(PUBLIC_DIRECTORY / filename).as_posix()}")
        for location in locations[filename]:
            print(f"REFERENCE_TARGET={location}")
    for planned in writes:
        if planned.path.suffix.lower() not in {".jpg", ".jpeg"}:
            print(f"TEXT_UPDATE={relative(root, planned.path)}")


def build_plan(
    root: Path, slug: str, image1_path: Path, image2_path: Path
) -> tuple[list[tuple[int, bytes]], list[PlannedWrite], dict[str, list[str]]]:
    if SLUG_PATTERN.fullmatch(slug) is None:
        raise ReplacementError(f"Invalid canonical slug: {slug}")
    images = [
        (1, read_input_image(image1_path, "image1")),
        (2, read_input_image(image2_path, "image2")),
    ]
    if sha256(images[0][1]) == sha256(images[1][1]):
        raise ReplacementError("image1 and image2 have identical bytes")

    canonical_paths: list[Path] = []
    canonical_writes: list[PlannedWrite] = []
    filenames_and_versions: list[tuple[str, str]] = []
    input_paths = {image1_path.resolve(), image2_path.resolve()}
    for position, data in images:
        filename = f"kagoshima-deliveryhealth-area-{slug}_{position}.jpg"
        filenames_and_versions.append((filename, sha256(data)[:7]))
        accepted = root / ACCEPTED_DIRECTORY / filename
        public = root / PUBLIC_DIRECTORY / filename
        for target in (accepted, public):
            if target.resolve() in input_paths:
                raise ReplacementError(f"Input image must be outside replacement targets: {target}")
            if target.is_symlink() or not target.is_file():
                raise ReplacementError(f"Existing canonical target is missing: {relative(root, target)}")
            canonical_paths.append(target)
            before = target.read_bytes()
            if before == data:
                raise ReplacementError(f"Replacement bytes are unchanged: {relative(root, target)}")
            canonical_writes.append(PlannedWrite(target, before, data))

    reference_writes, locations = plan_reference_updates(root, filenames_and_versions)
    require_tracked_clean(root, canonical_paths + [write.path for write in reference_writes])
    return images, canonical_writes + reference_writes, locations


def execute(root: Path, writes: list[PlannedWrite]) -> None:
    completed: list[PlannedWrite] = []
    try:
        for planned in writes:
            atomic_write(planned.path, planned.after)
            completed.append(planned)
        validate_area_image_replacements(root, "HEAD", None)
    except Exception as exc:
        rollback_failures: list[str] = []
        for planned in reversed(completed):
            try:
                atomic_write(planned.path, planned.before)
            except Exception as rollback_exc:
                rollback_failures.append(f"{relative(root, planned.path)}: {rollback_exc}")
        if rollback_failures:
            raise ReplacementError(
                "Replacement failed and rollback was incomplete: " + "; ".join(rollback_failures)
            ) from exc
        raise ReplacementError(
            f"Replacement failed; all changed files were restored: {exc}"
        ) from exc


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("command", choices=("replace-images",))
    parser.add_argument("--slug", required=True)
    parser.add_argument("--image1", type=Path, required=True)
    parser.add_argument("--image2", type=Path, required=True)
    parser.add_argument("--write", action="store_true")
    parser.add_argument("--root", type=Path, default=REPOSITORY_ROOT)
    args = parser.parse_args()

    root = args.root.resolve()
    require_repository(root)
    images, writes, locations = build_plan(root, args.slug, args.image1, args.image2)
    print_plan(root, args.slug, images, writes, locations, args.write)
    if not args.write:
        print("PREVIEW_ONLY: no files were changed; add --write to execute this exact replacement")
        return 0
    execute(root, writes)
    print(f"AREA_IMAGE_REPLACE_OK={args.slug}")
    print(f"CHANGED_FILE_COUNT={len(writes)}")
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except Exception as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        raise SystemExit(1)
