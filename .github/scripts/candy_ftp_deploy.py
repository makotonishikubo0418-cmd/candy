#!/usr/bin/env python3
"""Safely deploy changed CANDY HP files to KAGOYA over FTP."""

from __future__ import annotations

import argparse
import ftplib
import hashlib
import io
import os
from dataclasses import dataclass
from pathlib import Path, PurePosixPath
import subprocess
import sys


REMOTE_ROOT = PurePosixPath("/public_html/group/candy")
ZERO_SHA = "0" * 40
DEPLOYABLE_STATUSES = {"A", "M", "T"}
BLOCKED_STATUSES = {"D", "R"}
PROTECTED_PATHS = {"HP/index.php"}
EXCLUDED_PREFIXES = (
    "HP/codex/",
    "HP/log/",
    "HP/Text_area_data/",
    "HP/Text_blog_data/",
    "HP/Text_hotel_data/",
    "HP/.well-known/",
    "HP/.git/",
    "HP/.github/",
    "HP/.vscode/",
)


@dataclass
class Change:
    status: str
    path: str
    old_path: str | None = None


@dataclass
class UploadTarget:
    local_path: Path
    repository_path: str
    remote_directory: str
    final_name: str
    temporary_name: str
    backup_name: str
    sha256: str
    had_original: bool = False
    promoted: bool = False


def is_excluded(path: str) -> bool:
    if path in PROTECTED_PATHS:
        return True
    if path == "HP/AGENTS.md":
        return True
    if path.lower().endswith(".md"):
        return True
    return any(path.startswith(prefix) for prefix in EXCLUDED_PREFIXES)


def validate_repository_path(path: str) -> PurePosixPath:
    if "\\" in path:
        raise ValueError(f"Backslashes are not allowed: {path}")
    candidate = PurePosixPath(path)
    if candidate.is_absolute() or ".." in candidate.parts:
        raise ValueError(f"Unsafe repository path: {path}")
    if len(candidate.parts) < 2 or candidate.parts[0] != "HP":
        raise ValueError(f"Path is outside HP: {path}")
    return candidate


def server_path_for(path: str) -> PurePosixPath:
    candidate = validate_repository_path(path)
    relative = PurePosixPath(*candidate.parts[1:])
    destination = REMOTE_ROOT / relative
    root = REMOTE_ROOT.as_posix().rstrip("/") + "/"
    if not destination.as_posix().startswith(root):
        raise ValueError(f"Server path escaped deployment root: {path}")
    return destination


def parse_diff_output(output: str) -> list[Change]:
    changes: list[Change] = []
    for raw_line in output.splitlines():
        if not raw_line:
            continue
        fields = raw_line.split("\t")
        code = fields[0]
        status = code[0]
        if status in {"R", "C"}:
            if len(fields) != 3:
                raise RuntimeError(f"Unexpected rename/copy diff line: {raw_line}")
            changes.append(Change(status=status, old_path=fields[1], path=fields[2]))
        else:
            if len(fields) != 2:
                raise RuntimeError(f"Unexpected diff line: {raw_line}")
            changes.append(Change(status=status, path=fields[1]))
    return changes


def commit_exists(commit: str) -> bool:
    result = subprocess.run(
        ["git", "cat-file", "-e", f"{commit}^{{commit}}"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
        check=False,
    )
    return result.returncode == 0


def collect_changes(before: str, after: str) -> list[Change]:
    if not before or before == ZERO_SHA:
        raise RuntimeError("Comparison base is missing or all zeros; refusing full upload")
    if not after:
        raise RuntimeError("Comparison target is missing")
    if not commit_exists(before) or not commit_exists(after):
        raise RuntimeError("Comparison commit is unavailable; refusing full upload")
    result = subprocess.run(
        [
            "git",
            "diff",
            "--name-status",
            "--find-renames",
            before,
            after,
            "--",
            "HP",
        ],
        text=True,
        encoding="utf-8",
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=False,
    )
    if result.returncode != 0:
        raise RuntimeError(f"git diff failed: {result.stderr.strip()}")
    return parse_diff_output(result.stdout)


def collect_full_deploy_paths() -> tuple[list[str], list[str]]:
    result = subprocess.run(
        ["git", "ls-files", "-z", "--", "HP"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=False,
    )
    if result.returncode != 0:
        raise RuntimeError(
            f"git ls-files failed: {result.stderr.decode('utf-8', errors='replace').strip()}"
        )
    deployable: list[str] = []
    excluded: list[str] = []
    for raw_path in result.stdout.split(b"\0"):
        if not raw_path:
            continue
        path = raw_path.decode("utf-8")
        validate_repository_path(path)
        if is_excluded(path):
            excluded.append(path)
        else:
            deployable.append(path)
    return sorted(set(deployable)), sorted(set(excluded))


def classify_changes(changes: list[Change]) -> tuple[list[str], list[str], list[Change]]:
    deployable: list[str] = []
    excluded: list[str] = []
    blocked: list[Change] = []
    for change in changes:
        validate_repository_path(change.path)
        if change.old_path is not None:
            validate_repository_path(change.old_path)
        if change.status in BLOCKED_STATUSES:
            blocked.append(change)
            continue
        if is_excluded(change.path):
            excluded.append(change.path)
            continue
        if change.status in DEPLOYABLE_STATUSES:
            deployable.append(change.path)
            continue
        raise RuntimeError(f"Unsupported Git status {change.status}: {change.path}")
    return sorted(set(deployable)), sorted(set(excluded)), blocked


def print_plan(
    before: str,
    after: str,
    deployable: list[str],
    excluded: list[str],
    blocked: list[Change],
) -> None:
    print(f"Comparison base: {before}")
    print(f"Comparison target: {after}")
    print("Deployable HP files:")
    if deployable:
        for path in deployable:
            print(f"  {path} -> {server_path_for(path)}")
    else:
        print("  (none)")
    print("Excluded HP files:")
    if excluded:
        for path in excluded:
            print(f"  {path}")
    else:
        print("  (none)")
    print("Deleted or renamed HP files:")
    if blocked:
        for change in blocked:
            if change.status == "R":
                print(f"  R {change.old_path} -> {change.path}")
            else:
                print(f"  {change.status} {change.path}")
    else:
        print("  (none)")


def sha256_bytes(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def sha256_file(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as handle:
        for chunk in iter(lambda: handle.read(1024 * 1024), b""):
            digest.update(chunk)
    return digest.hexdigest()


def list_remote_names(ftp: ftplib.FTP, directory: str) -> set[str]:
    ftp.cwd(directory)
    try:
        entries = ftp.nlst()
    except ftplib.error_temp as exc:
        if str(exc).startswith("450"):
            return set()
        raise
    return {entry.rstrip("/").split("/")[-1] for entry in entries}


def remote_exists(ftp: ftplib.FTP, directory: str, name: str) -> bool:
    return name in list_remote_names(ftp, directory)


def ensure_remote_directory(ftp: ftplib.FTP, directory: str) -> None:
    root = REMOTE_ROOT.as_posix()
    if directory == root:
        ftp.cwd(root)
        return
    prefix = root.rstrip("/") + "/"
    if not directory.startswith(prefix):
        raise RuntimeError(f"Remote directory escaped deployment root: {directory}")
    ftp.cwd(root)
    relative_parts = PurePosixPath(directory[len(prefix) :]).parts
    for part in relative_parts:
        if part in {"", ".", ".."} or "/" in part:
            raise RuntimeError(f"Unsafe remote directory component: {part}")
        try:
            ftp.cwd(part)
        except ftplib.error_perm as exc:
            if not str(exc).startswith("550"):
                raise
            ftp.mkd(part)
            ftp.cwd(part)


def download_remote(ftp: ftplib.FTP, directory: str, name: str) -> bytes:
    ftp.cwd(directory)
    buffer = io.BytesIO()
    ftp.retrbinary(f"RETR {name}", buffer.write)
    return buffer.getvalue()


def delete_if_exists(ftp: ftplib.FTP, directory: str, name: str) -> None:
    if remote_exists(ftp, directory, name):
        ftp.cwd(directory)
        ftp.delete(name)


def build_targets(paths: list[str], run_id: str) -> list[UploadTarget]:
    repository_root = Path.cwd().resolve()
    hp_root = (repository_root / "HP").resolve()
    targets: list[UploadTarget] = []
    for repository_path in paths:
        destination = server_path_for(repository_path)
        local_path = (repository_root / Path(*PurePosixPath(repository_path).parts)).resolve()
        try:
            local_path.relative_to(hp_root)
        except ValueError as exc:
            raise RuntimeError(f"Local path escaped HP: {repository_path}") from exc
        if local_path.is_symlink() or not local_path.is_file():
            raise RuntimeError(f"Deployable path is not a regular file: {repository_path}")
        final_name = destination.name
        targets.append(
            UploadTarget(
                local_path=local_path,
                repository_path=repository_path,
                remote_directory=destination.parent.as_posix(),
                final_name=final_name,
                temporary_name=f".{final_name}.candy-upload-{run_id}",
                backup_name=f".{final_name}.candy-backup-{run_id}",
                sha256=sha256_file(local_path),
            )
        )
    return targets


def rollback_promotions(ftp: ftplib.FTP, targets: list[UploadTarget]) -> None:
    for target in reversed(targets):
        if not target.promoted:
            continue
        ftp.cwd(target.remote_directory)
        if remote_exists(ftp, target.remote_directory, target.final_name):
            ftp.delete(target.final_name)
        if target.had_original and remote_exists(
            ftp, target.remote_directory, target.backup_name
        ):
            ftp.rename(target.backup_name, target.final_name)
        target.promoted = False


def cleanup_temporary_files(ftp: ftplib.FTP, targets: list[UploadTarget]) -> None:
    for target in targets:
        try:
            delete_if_exists(ftp, target.remote_directory, target.temporary_name)
        except Exception as exc:
            print(
                f"WARNING: temporary cleanup failed for {target.repository_path}: {exc}",
                file=sys.stderr,
            )


def deploy(paths: list[str]) -> None:
    required_env = ("FTP_SERVER", "FTP_USERNAME", "FTP_PASSWORD", "GITHUB_RUN_ID", "GITHUB_SHA")
    missing = [name for name in required_env if not os.environ.get(name)]
    if missing:
        raise RuntimeError(f"Required deployment environment is missing: {', '.join(missing)}")

    run_id = os.environ["GITHUB_RUN_ID"]
    if not run_id.isdigit():
        raise RuntimeError("GITHUB_RUN_ID must be numeric")
    targets = build_targets(paths, run_id)
    ftp = ftplib.FTP(timeout=60)
    try:
        ftp.connect(os.environ["FTP_SERVER"], 21)
        ftp.login(os.environ["FTP_USERNAME"], os.environ["FTP_PASSWORD"])
        ftp.cwd(REMOTE_ROOT.as_posix())

        try:
            for target in targets:
                ensure_remote_directory(ftp, target.remote_directory)
                if remote_exists(ftp, target.remote_directory, target.backup_name):
                    raise RuntimeError(
                        f"Backup collision for {target.repository_path}; manual inspection required"
                    )
                delete_if_exists(ftp, target.remote_directory, target.temporary_name)
                ftp.cwd(target.remote_directory)
                with target.local_path.open("rb") as handle:
                    ftp.storbinary(f"STOR {target.temporary_name}", handle)
                temporary_data = download_remote(
                    ftp, target.remote_directory, target.temporary_name
                )
                if sha256_bytes(temporary_data) != target.sha256:
                    raise RuntimeError(
                        f"Temporary SHA256 mismatch for {target.repository_path}"
                    )

            for target in targets:
                target.had_original = remote_exists(
                    ftp, target.remote_directory, target.final_name
                )
                ftp.cwd(target.remote_directory)
                if target.had_original:
                    ftp.rename(target.final_name, target.backup_name)
                try:
                    ftp.rename(target.temporary_name, target.final_name)
                except Exception:
                    if target.had_original and remote_exists(
                        ftp, target.remote_directory, target.backup_name
                    ):
                        ftp.rename(target.backup_name, target.final_name)
                    raise
                target.promoted = True
                final_data = download_remote(
                    ftp, target.remote_directory, target.final_name
                )
                if sha256_bytes(final_data) != target.sha256:
                    raise RuntimeError(f"Final SHA256 mismatch for {target.repository_path}")
        except Exception:
            rollback_promotions(ftp, targets)
            cleanup_temporary_files(ftp, targets)
            raise

        for target in targets:
            if target.had_original:
                delete_if_exists(ftp, target.remote_directory, target.backup_name)
        cleanup_temporary_files(ftp, targets)
        for target in targets:
            print(f"DEPLOYED: {target.repository_path} -> {server_path_for(target.repository_path)}")
        print(f"SUCCESS: deployed and SHA256-verified {len(targets)} file(s)")
    finally:
        try:
            ftp.quit()
        except Exception:
            try:
                ftp.close()
            except Exception:
                pass


def self_test() -> None:
    assert is_excluded("HP/index.php")
    assert is_excluded("HP/AGENTS.md")
    assert is_excluded("HP/codex/example.txt")
    assert is_excluded("HP/log/example.log")
    assert is_excluded("HP/example.md")
    assert not is_excluded("HP/main.php")
    assert server_path_for("HP/css/style.css").as_posix() == (
        "/public_html/group/candy/css/style.css"
    )
    for unsafe in ("../HP/index.php", "/HP/index.php", "HP/../AGENTS.md", "HP\\index.php"):
        try:
            server_path_for(unsafe)
        except ValueError:
            pass
        else:
            raise AssertionError(f"Unsafe path was accepted: {unsafe}")
    parsed = parse_diff_output("A\tHP/a.php\nM\tHP/b.php\nT\tHP/c.php\nD\tHP/d.php\nR100\tHP/e.php\tHP/f.php\n")
    deployable, excluded, blocked = classify_changes(parsed)
    assert deployable == ["HP/a.php", "HP/b.php", "HP/c.php"]
    assert excluded == []
    assert [item.status for item in blocked] == ["D", "R"]
    protected = parse_diff_output("M\tHP/index.php\nM\tHP/main.php\n")
    deployable, excluded, blocked = classify_changes(protected)
    assert deployable == ["HP/main.php"]
    assert excluded == ["HP/index.php"]
    assert blocked == []
    print("SELF-TEST: passed")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--before")
    parser.add_argument("--after")
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--self-test", action="store_true")
    parser.add_argument("--full-deploy", action="store_true")
    args = parser.parse_args()

    if args.self_test:
        self_test()
        return 0
    if args.full_deploy:
        if args.before or args.after:
            parser.error("--full-deploy cannot be combined with --before or --after")
        deployable, excluded = collect_full_deploy_paths()
        print_plan("(full tracked HP snapshot)", os.environ.get("GITHUB_SHA", "HEAD"), deployable, excluded, [])
        if not deployable:
            print("No deployable HP files found.")
            return 0
        if args.dry_run:
            print("DRY-RUN: FTP connection and server changes were not performed.")
            return 0
        deploy(deployable)
        return 0
    if not args.before or not args.after:
        parser.error("--before and --after are required unless --self-test is used")

    changes = collect_changes(args.before, args.after)
    deployable, excluded, blocked = classify_changes(changes)
    print_plan(args.before, args.after, deployable, excluded, blocked)

    if blocked:
        print("Automatic server deletion is disabled.", file=sys.stderr)
        print(
            "Manual approval is required for deleted or renamed production files.",
            file=sys.stderr,
        )
        return 2
    if not deployable:
        print("No deployable HP files changed.")
        return 0
    if args.dry_run:
        print("DRY-RUN: FTP connection and server changes were not performed.")
        return 0

    deploy(deployable)
    return 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except Exception as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        raise SystemExit(1)
