#!/usr/bin/env python3
"""Block incomplete same-path replacements of public Candy area images."""

from __future__ import annotations

import argparse
import hashlib
from pathlib import Path, PurePosixPath
import re
import shutil
import subprocess
import sys


GIT = shutil.which("git") or "git"
PUBLIC_DIRECTORY = "HP/imgHtml/new_202601/area"
ACCEPTED_DIRECTORY = "Text_area_data/画像データ"
PUBLIC_URL_DIRECTORY = "imgHtml/new_202601/area"
AREA_IMAGE_PATTERN = re.compile(
    r"kagoshima-deliveryhealth-area-(?P<slug>[a-z0-9]+)_(?P<position>[12])\.jpg"
)
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
JPEG_SOF_MARKERS = {
    0xC0,
    0xC1,
    0xC2,
    0xC3,
    0xC5,
    0xC6,
    0xC7,
    0xC9,
    0xCA,
    0xCB,
    0xCD,
    0xCE,
    0xCF,
}


class GuardError(RuntimeError):
    """An area-image replacement violates the deploy contract."""


class Snapshot:
    def __init__(self, root: Path, commit: str | None):
        self.root = root
        self.commit = commit

    def read_bytes(self, path: str) -> bytes:
        if self.commit is None:
            candidate = (self.root / Path(*PurePosixPath(path).parts)).resolve()
            try:
                candidate.relative_to(self.root)
            except ValueError as exc:
                raise GuardError(f"Path escaped repository root: {path}") from exc
            return candidate.read_bytes()
        result = subprocess.run(
            [GIT, "show", f"{self.commit}:{path}"],
            cwd=self.root,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            check=False,
        )
        if result.returncode:
            raise FileNotFoundError(path)
        return result.stdout

    def exists(self, path: str) -> bool:
        try:
            self.read_bytes(path)
        except (FileNotFoundError, OSError):
            return False
        return True

    def controlled_paths(self) -> list[str]:
        if self.commit is None:
            result = subprocess.run(
                [GIT, "ls-files", "-z", "--", "HP"],
                cwd=self.root,
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE,
                check=False,
            )
        else:
            result = subprocess.run(
                [GIT, "ls-tree", "-r", "-z", "--name-only", self.commit, "--", "HP"],
                cwd=self.root,
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE,
                check=False,
            )
        if result.returncode:
            raise GuardError(result.stderr.decode("utf-8", errors="replace").strip())
        paths = result.stdout.decode("utf-8", errors="surrogateescape").split("\0")
        return sorted(
            path
            for path in paths
            if path and PurePosixPath(path).suffix.lower() in TEXT_SUFFIXES
        )


def git_output(root: Path, *args: str) -> str:
    result = subprocess.run(
        [GIT, *args],
        cwd=root,
        text=True,
        encoding="utf-8",
        errors="replace",
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=False,
    )
    if result.returncode:
        raise GuardError(f"git {' '.join(args)} failed: {result.stderr.strip()}")
    return result.stdout.strip()


def resolve_commit(root: Path, value: str) -> str:
    resolved = git_output(root, "rev-parse", "--verify", f"{value}^{{commit}}")
    if not re.fullmatch(r"[0-9a-f]{40}", resolved):
        raise GuardError(f"Could not resolve commit: {value}")
    return resolved


def changed_existing_area_images(root: Path, before: str, after: str | None) -> list[str]:
    command = [
        GIT,
        "-c",
        "core.quotepath=false",
        "diff",
        "--name-status",
        "--find-renames",
        before,
    ]
    if after is not None:
        command.append(after)
    command.extend(["--", PUBLIC_DIRECTORY])
    result = subprocess.run(
        command,
        cwd=root,
        text=True,
        encoding="utf-8",
        errors="replace",
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=False,
    )
    if result.returncode:
        raise GuardError(f"git diff failed: {result.stderr.strip()}")

    before_snapshot = Snapshot(root, before)
    after_snapshot = Snapshot(root, after)
    changed: list[str] = []
    for line in result.stdout.splitlines():
        fields = line.split("\t")
        if len(fields) != 2 or fields[0][0] not in {"M", "T"}:
            continue
        path = fields[1]
        if not AREA_IMAGE_PATTERN.fullmatch(PurePosixPath(path).name):
            continue
        if not before_snapshot.exists(path) or not after_snapshot.exists(path):
            continue
        if before_snapshot.read_bytes(path) != after_snapshot.read_bytes(path):
            changed.append(path)
    return sorted(set(changed))


def sha256(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def jpeg_dimensions(data: bytes) -> tuple[int, int]:
    if len(data) < 4 or not data.startswith(b"\xff\xd8") or not data.endswith(b"\xff\xd9"):
        raise ValueError("not a complete JPEG stream")
    position = 2
    while position < len(data):
        if data[position] != 0xFF:
            position += 1
            continue
        while position < len(data) and data[position] == 0xFF:
            position += 1
        if position >= len(data):
            break
        marker = data[position]
        position += 1
        if marker in {0x01, *range(0xD0, 0xDA)}:
            continue
        if marker in {0xD8, 0xD9}:
            continue
        if position + 2 > len(data):
            break
        segment_length = int.from_bytes(data[position : position + 2], "big")
        if segment_length < 2 or position + segment_length > len(data):
            break
        if marker in JPEG_SOF_MARKERS:
            if segment_length < 7:
                break
            height = int.from_bytes(data[position + 3 : position + 5], "big")
            width = int.from_bytes(data[position + 5 : position + 7], "big")
            return width, height
        if marker == 0xDA:
            break
        position += segment_length
    raise ValueError("JPEG dimensions were not found")


def reference_pattern(filename: str) -> re.Pattern[bytes]:
    public_path = f"{PUBLIC_URL_DIRECTORY}/{filename}".encode("ascii")
    return re.compile(re.escape(public_path) + rb"(?P<query>\?[^\s\"'<>)]*)?")


def collect_references(snapshot: Snapshot, filename: str) -> list[tuple[str, int, str | None]]:
    pattern = reference_pattern(filename)
    references: list[tuple[str, int, str | None]] = []
    for path in snapshot.controlled_paths():
        try:
            data = snapshot.read_bytes(path)
        except (FileNotFoundError, OSError):
            continue
        for match in pattern.finditer(data):
            line = data.count(b"\n", 0, match.start()) + 1
            query = match.group("query")
            references.append(
                (path, line, query.decode("ascii", errors="replace") if query else None)
            )
    return references


def validate_area_image_replacements(
    root: Path, before: str, after: str | None
) -> tuple[int, int]:
    root = root.resolve()
    before = resolve_commit(root, before)
    if after is not None:
        after = resolve_commit(root, after)
    changed_paths = changed_existing_area_images(root, before, after)
    if not changed_paths:
        print("AREA_IMAGE_REPLACEMENT_GUARD: no existing public area image bytes changed")
        return 0, 0

    before_snapshot = Snapshot(root, before)
    after_snapshot = Snapshot(root, after)
    errors: list[str] = []
    checked_slugs: set[str] = set()
    reference_count = 0

    for changed_path in changed_paths:
        filename = PurePosixPath(changed_path).name
        match = AREA_IMAGE_PATTERN.fullmatch(filename)
        assert match is not None
        slug = match.group("slug")
        if slug not in checked_slugs:
            checked_slugs.add(slug)
            pair_hashes: list[str] = []
            for position in ("1", "2"):
                pair_filename = f"kagoshima-deliveryhealth-area-{slug}_{position}.jpg"
                public_path = f"{PUBLIC_DIRECTORY}/{pair_filename}"
                accepted_path = f"{ACCEPTED_DIRECTORY}/{pair_filename}"
                if not after_snapshot.exists(public_path):
                    errors.append(f"missing canonical public image: {public_path}")
                    continue
                if not after_snapshot.exists(accepted_path):
                    errors.append(f"missing accepted-source image: {accepted_path}")
                    continue
                public_data = after_snapshot.read_bytes(public_path)
                accepted_data = after_snapshot.read_bytes(accepted_path)
                public_hash = sha256(public_data)
                accepted_hash = sha256(accepted_data)
                pair_hashes.append(public_hash)
                if public_hash != accepted_hash:
                    errors.append(
                        f"accepted/public SHA-256 mismatch: {pair_filename} "
                        f"({accepted_hash} != {public_hash})"
                    )
                try:
                    dimensions = jpeg_dimensions(public_data)
                except ValueError as exc:
                    errors.append(f"invalid public JPEG: {public_path} ({exc})")
                else:
                    if dimensions != (1000, 750):
                        errors.append(
                            f"wrong public image dimensions: {public_path} "
                            f"({dimensions[0]}x{dimensions[1]}, required 1000x750)"
                        )
            if len(pair_hashes) == 2 and pair_hashes[0] == pair_hashes[1]:
                errors.append(f"area image pair has identical bytes: {slug} _1/_2")

        target_data = after_snapshot.read_bytes(changed_path)
        target_hash = sha256(target_data)
        references = collect_references(after_snapshot, filename)
        old_references = collect_references(before_snapshot, filename)
        reference_count += len(references)
        if not references:
            errors.append(f"no controlled public reference found: {filename}")
            continue

        old_queries = {query for _path, _line, query in old_references}
        for reference_path, line, query in references:
            location = f"{reference_path}:{line}"
            if query is None:
                errors.append(f"bare public image reference: {location} -> {filename}")
                continue
            version_match = re.fullmatch(r"\?v=([0-9a-f]{7,64})", query)
            if version_match is None:
                errors.append(f"invalid content-version query: {location} -> {query}")
                continue
            version = version_match.group(1)
            if not target_hash.startswith(version):
                errors.append(
                    f"content version does not match new SHA-256: {location} "
                    f"({version} vs {target_hash})"
                )
            if query in old_queries:
                errors.append(
                    f"content-version URL was not changed with the image bytes: "
                    f"{location} -> {query}"
                )

    if errors:
        details = "\n".join(f"  - {error}" for error in errors)
        raise GuardError(
            "AREA_IMAGE_REPLACEMENT_GUARD: blocked incomplete replacement\n" + details
        )
    print(
        "AREA_IMAGE_REPLACEMENT_GUARD: passed "
        f"({len(changed_paths)} changed image(s), {reference_count} controlled reference(s))"
    )
    return len(changed_paths), reference_count


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--before", required=True, help="Base commit or ref")
    target = parser.add_mutually_exclusive_group(required=True)
    target.add_argument("--after", help="Target commit or ref")
    target.add_argument("--worktree", action="store_true", help="Validate the current worktree")
    parser.add_argument("--root", type=Path, default=Path.cwd())
    args = parser.parse_args()
    validate_area_image_replacements(args.root, args.before, None if args.worktree else args.after)
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except Exception as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        raise SystemExit(1)
