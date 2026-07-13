from __future__ import annotations

import re
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path


GIT = shutil.which("git") or r"C:\Program Files\Git\cmd\git.exe"
SCRIPT = Path(__file__).with_name("candy_ftp_deploy.py").resolve()
CONFIRMATION = "DEPLOY-CANDY-PRODUCTION"


def run(args: list[str], cwd: Path, *, succeeds: bool = True) -> subprocess.CompletedProcess[str]:
    result = subprocess.run(args, cwd=cwd, text=True, capture_output=True)
    if succeeds and result.returncode:
        raise AssertionError(
            f"command failed: {args}\nstdout:\n{result.stdout}\nstderr:\n{result.stderr}"
        )
    return result


def git(root: Path, *args: str) -> subprocess.CompletedProcess[str]:
    return run([GIT, *args], root)


def commit(root: Path, message: str) -> str:
    git(root, "add", ".")
    git(root, "commit", "-qm", message)
    return git(root, "rev-parse", "HEAD").stdout.strip()


def make_repository(root: Path) -> tuple[str, str]:
    (root / "HP").mkdir()
    (root / ".github" / "scripts").mkdir(parents=True)
    shutil.copy2(SCRIPT, root / ".github" / "scripts" / SCRIPT.name)
    git(root, "init", "-q")
    git(root, "config", "user.email", "test@example.invalid")
    git(root, "config", "user.name", "Safety Test")
    (root / "HP" / "main.php").write_bytes(b"v1\n")
    before = commit(root, "base")
    (root / "HP" / "main.php").write_bytes(b"v2\n")
    after = commit(root, "target")
    return before, after


def main() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, after = make_repository(root)
        script = root / ".github" / "scripts" / SCRIPT.name
        base = [sys.executable, str(script), "--before", before, "--after", after]

        preview = run([*base, "--dry-run"], root)
        assert "Deployable file count: 1" in preview.stdout
        assert "Deployable total bytes: 3" in preview.stdout
        token_match = re.search(r"PLAN_TOKEN: ([0-9a-f]{64})", preview.stdout)
        assert token_match
        token = token_match.group(1)

        missing = run([*base, "--verify-approval"], root, succeeds=False)
        assert missing.returncode != 0
        assert "Expected file count" in missing.stderr

        wrong_token = run(
            [
                *base,
                "--verify-approval",
                "--expected-file-count",
                "1",
                "--approved-plan-token",
                "0" * 64,
                "--confirm-production",
                CONFIRMATION,
            ],
            root,
            succeeds=False,
        )
        assert wrong_token.returncode != 0
        assert "Approved plan token" in wrong_token.stderr

        approved = run(
            [
                *base,
                "--verify-approval",
                "--expected-file-count",
                "1",
                "--approved-plan-token",
                token,
                "--confirm-production",
                CONFIRMATION,
            ],
            root,
        )
        assert "APPROVAL CHECK: passed" in approved.stdout

        git(root, "checkout", "-q", before)
        mismatch = run([*base, "--dry-run"], root, succeeds=False)
        assert mismatch.returncode != 0
        assert "Checked-out HEAD" in mismatch.stderr

    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, _ = make_repository(root)
        large_file = root / "HP" / "large.bin"
        large_file.touch()
        large_file.write_bytes(b"\0")
        with large_file.open("r+b") as handle:
            handle.truncate(50 * 1024 * 1024 + 1)
        after = commit(root, "oversized asset")
        script = root / ".github" / "scripts" / SCRIPT.name
        base = [sys.executable, str(script), "--before", before, "--after", after]
        preview = run([*base, "--dry-run"], root)
        token_match = re.search(r"PLAN_TOKEN: ([0-9a-f]{64})", preview.stdout)
        assert token_match
        oversized = run(
            [
                *base,
                "--verify-approval",
                "--expected-file-count",
                "2",
                "--approved-plan-token",
                token_match.group(1),
                "--confirm-production",
                CONFIRMATION,
            ],
            root,
            succeeds=False,
        )
        assert oversized.returncode != 0
        assert "maximum is 52428800" in oversized.stderr

    print("TWO_STAGE_CLI_INTEGRATION: passed")


if __name__ == "__main__":
    main()
