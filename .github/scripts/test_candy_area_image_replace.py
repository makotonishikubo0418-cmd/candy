from __future__ import annotations

import hashlib
from pathlib import Path
import shutil
import subprocess
import sys
import tempfile


GIT = shutil.which("git") or r"C:\Program Files\Git\cmd\git.exe"
SCRIPT = (
    Path(__file__).resolve().parents[2] / "codex" / "scripts" / "candy_area_image_replace.py"
)
PREFIX = "kagoshima-deliveryhealth-area-testcho"
PUBLIC = Path("HP/imgHtml/new_202601/area")
ACCEPTED = Path("Text_area_data/画像データ")
SOURCE = Path("HP/source")


def run(
    args: list[str], root: Path, *, succeeds: bool = True
) -> subprocess.CompletedProcess[str]:
    result = subprocess.run(
        args, cwd=root, text=True, encoding="utf-8", errors="replace", capture_output=True
    )
    if succeeds and result.returncode:
        raise AssertionError(
            f"command failed: {args}\nstdout:\n{result.stdout}\nstderr:\n{result.stderr}"
        )
    if not succeeds and result.returncode == 0:
        raise AssertionError(f"command unexpectedly passed: {args}\n{result.stdout}")
    return result


def git(root: Path, *args: str) -> subprocess.CompletedProcess[str]:
    return run([GIT, "-c", "core.quotepath=false", *args], root)


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


def image_url(position: int, data: bytes) -> str:
    return f"./imgHtml/new_202601/area/{PREFIX}_{position}.jpg?v={digest(data)[:7]}"


def make_repository(root: Path, *, include_second_reference: bool = True) -> tuple[bytes, bytes]:
    (root / PUBLIC).mkdir(parents=True)
    (root / ACCEPTED).mkdir(parents=True)
    (root / SOURCE).mkdir(parents=True)
    (root / "HP" / "css").mkdir(parents=True)
    git(root, "init", "-q", "-b", "main")
    git(root, "config", "user.email", "test@example.invalid")
    git(root, "config", "user.name", "Area Replace Test")
    old1 = jpeg(b"old-image-one")
    old2 = jpeg(b"old-image-two")
    for position, data in ((1, old1), (2, old2)):
        (root / image_path(position)).write_bytes(data)
        (root / image_path(position, accepted=True)).write_bytes(data)
    second = f'<img src="{image_url(2, old2)}">' if include_second_reference else ""
    (root / SOURCE / f"{PREFIX}.html").write_text(
        f'<img src="{image_url(1, old1)}">\n{second}\n', encoding="utf-8"
    )
    (root / "HP" / "css" / "area-test.css").write_text(
        f'body{{background-image:url("{image_url(1, old1)}")}}\n', encoding="utf-8"
    )
    commit(root, "base")
    return old1, old2


def command(root: Path, image1: Path, image2: Path, *, write: bool = False) -> list[str]:
    args = [
        sys.executable,
        str(SCRIPT),
        "replace-images",
        "--slug",
        "testcho",
        "--image1",
        str(image1),
        "--image2",
        str(image2),
        "--root",
        str(root),
    ]
    if write:
        args.append("--write")
    return args


def assert_preview_and_write() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        base = Path(temp_dir)
        root = base / "repo"
        inputs = base / "inputs"
        root.mkdir()
        inputs.mkdir()
        old1, old2 = make_repository(root)
        new1 = jpeg(b"new-image-one")
        new2 = jpeg(b"new-image-two")
        input1 = inputs / "one.jpg"
        input2 = inputs / "two.jpg"
        input1.write_bytes(new1)
        input2.write_bytes(new2)

        preview = run(command(root, input1, input2), root)
        assert "AREA_IMAGE_REPLACE_MODE=PREVIEW" in preview.stdout
        assert "PREVIEW_ONLY: no files were changed" in preview.stdout
        assert git(root, "status", "--short").stdout == ""
        assert (root / image_path(1)).read_bytes() == old1
        assert (root / image_path(2)).read_bytes() == old2

        written = run(command(root, input1, input2, write=True), root)
        assert "AREA_IMAGE_REPLACEMENT_GUARD: passed" in written.stdout
        assert "AREA_IMAGE_REPLACE_OK=testcho" in written.stdout
        assert "CHANGED_FILE_COUNT=6" in written.stdout
        for position, data in ((1, new1), (2, new2)):
            assert (root / image_path(position)).read_bytes() == data
            assert (root / image_path(position, accepted=True)).read_bytes() == data
        source = (root / SOURCE / f"{PREFIX}.html").read_text(encoding="utf-8")
        css = (root / "HP" / "css" / "area-test.css").read_text(encoding="utf-8")
        assert image_url(1, new1) in source
        assert image_url(2, new2) in source
        assert image_url(1, new1) in css
        status = set(git(root, "status", "--short").stdout.splitlines())
        expected_status = {
            f" M {image_path(1).as_posix()}",
            f" M {image_path(2).as_posix()}",
            f" M {image_path(1, accepted=True).as_posix()}",
            f" M {image_path(2, accepted=True).as_posix()}",
            f" M {(SOURCE / f'{PREFIX}.html').as_posix()}",
            " M HP/css/area-test.css",
        }
        assert status == expected_status, f"actual={sorted(status)} expected={sorted(expected_status)}"


def assert_dirty_target_is_blocked() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        base = Path(temp_dir)
        root = base / "repo"
        inputs = base / "inputs"
        root.mkdir()
        inputs.mkdir()
        old1, old2 = make_repository(root)
        input1 = inputs / "one.jpg"
        input2 = inputs / "two.jpg"
        input1.write_bytes(jpeg(b"new-image-one"))
        input2.write_bytes(jpeg(b"new-image-two"))
        source = root / SOURCE / f"{PREFIX}.html"
        source.write_text(source.read_text(encoding="utf-8") + "<!-- unrelated -->\n", encoding="utf-8")
        result = run(command(root, input1, input2, write=True), root, succeeds=False)
        assert "Target already has an uncommitted change" in result.stderr
        assert (root / image_path(1)).read_bytes() == old1
        assert (root / image_path(2)).read_bytes() == old2


def assert_invalid_input_and_missing_reference_are_blocked() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        base = Path(temp_dir)
        root = base / "repo"
        inputs = base / "inputs"
        root.mkdir()
        inputs.mkdir()
        old1, old2 = make_repository(root, include_second_reference=False)
        same = jpeg(b"same-image")
        input1 = inputs / "one.jpg"
        input2 = inputs / "two.jpg"
        input1.write_bytes(same)
        input2.write_bytes(same)
        identical = run(command(root, input1, input2, write=True), root, succeeds=False)
        assert "image1 and image2 have identical bytes" in identical.stderr
        input1.write_bytes(jpeg(b"new-image-one"))
        input2.write_bytes(jpeg(b"new-image-two"))
        missing = run(command(root, input1, input2, write=True), root, succeeds=False)
        assert "No controlled public reference found" in missing.stderr
        assert (root / image_path(1)).read_bytes() == old1
        assert (root / image_path(2)).read_bytes() == old2
        assert git(root, "status", "--short").stdout == ""


def main() -> None:
    assert_preview_and_write()
    assert_dirty_target_is_blocked()
    assert_invalid_input_and_missing_reference_are_blocked()
    print("AREA_IMAGE_REPLACE_TOOL_TESTS: passed")


if __name__ == "__main__":
    main()
