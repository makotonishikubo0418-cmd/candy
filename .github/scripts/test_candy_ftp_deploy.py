from __future__ import annotations

import re
import importlib.util
import os
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path


GIT = shutil.which("git") or r"C:\Program Files\Git\cmd\git.exe"
SCRIPT = Path(__file__).with_name("candy_ftp_deploy.py").resolve()
WORKFLOW = SCRIPT.parent.parent / "workflows" / "candy-production-deploy.yml"
CONFIRMATION = "DEPLOY-CANDY-PRODUCTION"


def load_deploy_module():
    spec = importlib.util.spec_from_file_location("candy_ftp_deploy_tested", SCRIPT)
    assert spec and spec.loader
    module = importlib.util.module_from_spec(spec)
    sys.modules[spec.name] = module
    spec.loader.exec_module(module)
    return module


class FakeFTP:
    ROOT = "/public_html/group/candy"

    def __init__(self, files: dict[str, bytes], *, fail_final_name: str | None = None, **_kwargs):
        self.files = files
        self.directory = self.ROOT
        self.fail_final_name = fail_final_name
        self.failed = False

    def connect(self, *_args):
        return None

    def login(self, *_args):
        return None

    def cwd(self, directory: str):
        if directory.startswith("/"):
            self.directory = directory.rstrip("/")
        else:
            self.directory = f"{self.directory}/{directory}".rstrip("/")

    def nlst(self):
        prefix = self.directory.rstrip("/") + "/"
        return [path[len(prefix):] for path in self.files if path.startswith(prefix) and "/" not in path[len(prefix):]]

    def mkd(self, _directory: str):
        return None

    def storbinary(self, command: str, handle):
        name = command.split(" ", 1)[1]
        self.files[f"{self.directory}/{name}"] = handle.read()

    def retrbinary(self, command: str, callback):
        name = command.split(" ", 1)[1]
        if name == self.fail_final_name and not self.failed:
            self.failed = True
            raise RuntimeError("injected final verification failure")
        callback(self.files[f"{self.directory}/{name}"])

    def delete(self, name: str):
        del self.files[f"{self.directory}/{name}"]

    def rename(self, old: str, new: str):
        self.files[f"{self.directory}/{new}"] = self.files.pop(f"{self.directory}/{old}")

    def quit(self):
        return None

    def close(self):
        return None


def assert_transactional_rollback() -> None:
    module = load_deploy_module()
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        (root / "HP").mkdir()
        new_files = {"a.php": b"new-a", "b.php": b"new-b", "c.php": b"new-c"}
        for name, body in new_files.items():
            (root / "HP" / name).write_bytes(body)
        remote = {
            f"{FakeFTP.ROOT}/a.php": b"old-a",
            f"{FakeFTP.ROOT}/c.php": b"old-c",
        }
        fake = FakeFTP(remote, fail_final_name="c.php")
        original_factory = module.ftplib.FTP
        original_cwd = Path.cwd()
        original_env = os.environ.copy()
        try:
            module.ftplib.FTP = lambda **_kwargs: fake
            os.chdir(root)
            os.environ.update(
                FTP_SERVER="example.invalid",
                FTP_USERNAME="test",
                FTP_PASSWORD="test",
                GITHUB_RUN_ID="12345",
                GITHUB_SHA="a" * 40,
            )
            try:
                module.deploy([f"HP/{name}" for name in new_files])
            except RuntimeError as exc:
                assert "all files changed by this run were rolled back" in str(exc)
            else:
                raise AssertionError("Injected FTP failure did not stop deployment")
        finally:
            module.ftplib.FTP = original_factory
            os.chdir(original_cwd)
            os.environ.clear()
            os.environ.update(original_env)
        assert remote == {
            f"{FakeFTP.ROOT}/a.php": b"old-a",
            f"{FakeFTP.ROOT}/c.php": b"old-c",
        }


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


def assert_workflow_contract() -> None:
    text = WORKFLOW.read_text(encoding="utf-8")
    required = (
        "push:",
        "workflow_dispatch:",
        'branches:\n      - main',
        '".github/workflows/candy-production-deploy.yml"',
        '".github/scripts/candy_release_check.py"',
        '"!HP/codex/**"',
        '"!HP/AGENTS.md"',
        '"!HP/Text_area_data/**"',
        "github.event.before",
        "github.sha",
        "steps.plan.outputs.count",
        "steps.plan.outputs.token",
        "--verify-approval",
        "--lint-php",
        "cancel-in-progress: false",
    )
    for marker in required:
        assert marker in text, f"workflow contract is missing: {marker}"
    assert "full_deploy" not in text
    assert "--full-deploy" not in text


def main() -> None:
    assert_workflow_contract()
    assert_transactional_rollback()
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

    print("DEPLOY_AUTOMATION_INTEGRATION: passed")


if __name__ == "__main__":
    main()
