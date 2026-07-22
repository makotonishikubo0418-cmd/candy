from __future__ import annotations

import hashlib
from pathlib import Path
import shutil
import subprocess
import sys
import tempfile


GIT = shutil.which("git") or r"C:\Program Files\Git\cmd\git.exe"
SCRIPT = Path(__file__).with_name("candy_area_image_replacement_guard.py").resolve()
DEPLOY_SCRIPT = Path(__file__).with_name("candy_ftp_deploy.py").resolve()
PUBLIC = Path("HP/imgHtml/new_202601/area")
ACCEPTED = Path("Text_area_data/画像データ")
SOURCE = Path("HP/source")
PREFIX = "kagoshima-deliveryhealth-area-testcho"


def run(
    args: list[str], root: Path, *, succeeds: bool = True
) -> subprocess.CompletedProcess[str]:
    result = subprocess.run(args, cwd=root, text=True, capture_output=True)
    if succeeds and result.returncode:
        raise AssertionError(
            f"command failed: {args}\nstdout:\n{result.stdout}\nstderr:\n{result.stderr}"
        )
    if not succeeds and result.returncode == 0:
        raise AssertionError(f"command unexpectedly passed: {args}\n{result.stdout}")
    return result


def git(root: Path, *args: str) -> subprocess.CompletedProcess[str]:
    return run([GIT, *args], root)


def commit(root: Path, message: str) -> str:
    git(root, "add", ".")
    git(root, "commit", "-qm", message)
    return git(root, "rev-parse", "HEAD").stdout.strip()


def jpeg(label: bytes, *, width: int = 1000, height: int = 750) -> bytes:
    sof = (
        b"\xff\xc0"
        + (17).to_bytes(2, "big")
        + b"\x08"
        + height.to_bytes(2, "big")
        + width.to_bytes(2, "big")
        + b"\x03\x01\x11\x00\x02\x11\x00\x03\x11\x00"
    )
    comment = b"\xff\xfe" + (len(label) + 2).to_bytes(2, "big") + label
    return b"\xff\xd8" + sof + comment + b"\xff\xd9"


def digest(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def image_path(position: int, *, accepted: bool = False) -> Path:
    directory = ACCEPTED if accepted else PUBLIC
    return directory / f"{PREFIX}_{position}.jpg"


def reference(position: int, data: bytes, *, query: str | None = None) -> str:
    suffix = f"?v={digest(data)[:7]}" if query is None else query
    return f"./imgHtml/new_202601/area/{PREFIX}_{position}.jpg{suffix}"


def write_source(root: Path, image1: str, image2: str, extra: str = "") -> None:
    source = root / SOURCE / f"{PREFIX}.html"
    source.write_text(
        f'<img src="{image1}">\n<img src="{image2}">\n{extra}', encoding="utf-8"
    )


def make_repository(root: Path) -> tuple[str, bytes, bytes]:
    (root / PUBLIC).mkdir(parents=True)
    (root / ACCEPTED).mkdir(parents=True)
    (root / SOURCE).mkdir(parents=True)
    git(root, "init", "-q")
    git(root, "config", "user.email", "test@example.invalid")
    git(root, "config", "user.name", "Area Guard Test")
    image1 = jpeg(b"old-image-one")
    image2 = jpeg(b"old-image-two")
    for position, data in ((1, image1), (2, image2)):
        (root / image_path(position)).write_bytes(data)
        (root / image_path(position, accepted=True)).write_bytes(data)
    write_source(root, reference(1, image1), reference(2, image2))
    return commit(root, "base"), image1, image2


def guard(root: Path, before: str, after: str | None = None, *, succeeds: bool = True):
    target = ["--worktree"] if after is None else ["--after", after]
    return run(
        [sys.executable, str(SCRIPT), "--before", before, *target],
        root,
        succeeds=succeeds,
    )


def assert_no_replacement_passes() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, _old1, _old2 = make_repository(root)
        (root / "HP" / "main.php").write_text("<?php\n", encoding="utf-8")
        after = commit(root, "unrelated change")
        result = guard(root, before, after)
        assert "no existing public area image bytes changed" in result.stdout


def assert_valid_replacement_passes() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, _old1, old2 = make_repository(root)
        new1 = jpeg(b"new-image-one")
        (root / image_path(1)).write_bytes(new1)
        (root / image_path(1, accepted=True)).write_bytes(new1)
        write_source(root, reference(1, new1), reference(2, old2))
        worktree = guard(root, before)
        assert "passed (1 changed image(s), 1 controlled reference(s))" in worktree.stdout
        after = commit(root, "valid replacement")
        committed = guard(root, before, after)
        assert "AREA_IMAGE_REPLACEMENT_GUARD: passed" in committed.stdout


def assert_new_image_addition_is_not_a_replacement() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        (root / "HP").mkdir()
        git(root, "init", "-q")
        git(root, "config", "user.email", "test@example.invalid")
        git(root, "config", "user.name", "Area Guard Test")
        (root / "HP" / "main.php").write_text("base\n", encoding="utf-8")
        before = commit(root, "base")
        (root / PUBLIC).mkdir(parents=True)
        (root / ACCEPTED).mkdir(parents=True)
        data = jpeg(b"first-install")
        (root / image_path(1)).write_bytes(data)
        (root / image_path(1, accepted=True)).write_bytes(data)
        after = commit(root, "new image")
        result = guard(root, before, after)
        assert "no existing public area image bytes changed" in result.stdout


def assert_deploy_plan_is_blocked() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, old1, old2 = make_repository(root)
        new1 = jpeg(b"new-image-one")
        (root / image_path(1)).write_bytes(new1)
        (root / image_path(1, accepted=True)).write_bytes(old1)
        write_source(root, reference(1, new1), reference(2, old2))
        after = commit(root, "invalid replacement")
        result = run(
            [
                sys.executable,
                str(DEPLOY_SCRIPT),
                "--before",
                before,
                "--after",
                after,
                "--dry-run",
            ],
            root,
            succeeds=False,
        )
        assert "AREA_IMAGE_REPLACEMENT_GUARD: blocked" in result.stderr
        assert "DRY-RUN" not in result.stdout


def assert_failure(
    mutate,
    expected: str,
) -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        before, old1, old2 = make_repository(root)
        new1 = jpeg(b"new-image-one")
        (root / image_path(1)).write_bytes(new1)
        (root / image_path(1, accepted=True)).write_bytes(new1)
        write_source(root, reference(1, new1), reference(2, old2))
        mutate(root, old1, old2, new1)
        after = commit(root, "invalid replacement")
        result = guard(root, before, after, succeeds=False)
        assert expected in result.stderr, result.stderr


def main() -> None:
    assert_no_replacement_passes()
    assert_valid_replacement_passes()
    assert_new_image_addition_is_not_a_replacement()
    assert_deploy_plan_is_blocked()

    assert_failure(
        lambda root, old1, _old2, _new1: (root / image_path(1, accepted=True)).write_bytes(old1),
        "accepted/public SHA-256 mismatch",
    )
    assert_failure(
        lambda root, _old1, _old2, _new1: (root / image_path(1, accepted=True)).unlink(),
        "missing accepted-source image",
    )
    assert_failure(
        lambda root, old1, old2, _new1: write_source(
            root, reference(1, old1), reference(2, old2)
        ),
        "content version does not match new SHA-256",
    )
    assert_failure(
        lambda root, _old1, old2, _new1: write_source(
            root, reference(1, b"", query=""), reference(2, old2)
        ),
        "bare public image reference",
    )
    assert_failure(
        lambda root, old1, old2, new1: write_source(
            root,
            reference(1, new1),
            reference(2, old2),
            f'body{{background:url("{reference(1, old1)}")}}',
        ),
        "content version does not match new SHA-256",
    )
    assert_failure(
        lambda root, _old1, old2, _new1: (
            (root / image_path(1)).write_bytes(jpeg(b"wrong-size", width=999)),
            (root / image_path(1, accepted=True)).write_bytes(jpeg(b"wrong-size", width=999)),
            write_source(
                root,
                reference(1, jpeg(b"wrong-size", width=999)),
                reference(2, old2),
            ),
        ),
        "wrong public image dimensions",
    )
    assert_failure(
        lambda root, _old1, old2, _new1: (
            (root / image_path(1)).write_bytes(old2),
            (root / image_path(1, accepted=True)).write_bytes(old2),
            write_source(root, reference(1, old2), reference(2, old2)),
        ),
        "area image pair has identical bytes",
    )
    print("AREA_IMAGE_REPLACEMENT_GUARD_TESTS: passed")


if __name__ == "__main__":
    main()
