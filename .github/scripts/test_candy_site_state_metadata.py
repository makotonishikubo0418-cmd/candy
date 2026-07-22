from __future__ import annotations

from contextlib import redirect_stderr, redirect_stdout
import io
from pathlib import Path
import sys
import tempfile


SCRIPTS = Path(__file__).resolve().parents[2] / "codex" / "scripts"
if str(SCRIPTS) not in sys.path:
    sys.path.insert(0, str(SCRIPTS))

import candy_site_state as site_state


FINGERPRINT = "a" * 64


def document(commit: str, generated_at: str, body: str, fingerprint: str = FINGERPRINT) -> str:
    return (
        "# GENERATED\n\n"
        f"> Generated at: {generated_at} (reproducible generation baseline)\n"
        "> Branch: main\n"
        f"> Commit: {commit}\n"
        f"> State fingerprint: sha256:{fingerprint}\n\n"
        "| page ID | verification source |\n"
        "|---|---|\n"
        f"| area:test | {commit} / {generated_at} |\n\n"
        f"{body}\n"
    )


def assert_metadata_is_not_content_drift() -> None:
    current = document("1" * 40, "2026-01-01T00:00:00+09:00", "STATE=OK")
    expected = document("2" * 40, "2026-02-02T00:00:00+09:00", "STATE=OK")
    assert not site_state.document_differs(current, expected, False)
    assert site_state.document_differs(current, expected, True)
    assert site_state.document_differs(
        current,
        document("2" * 40, "2026-02-02T00:00:00+09:00", "STATE=CHANGED"),
        False,
    )
    assert site_state.document_differs(
        current,
        document(
            "2" * 40,
            "2026-02-02T00:00:00+09:00",
            "STATE=OK",
            fingerprint="b" * 64,
        ),
        False,
    )


def assert_fingerprint_is_deterministic() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        text = root / "a.txt"
        binary = root / "b.jpg"
        text.write_bytes(b"line1\r\nline2\r\n")
        binary.write_bytes(b"binary-one")
        first = site_state.fingerprint_for_paths([binary, text], root)
        second = site_state.fingerprint_for_paths([text, binary], root)
        assert first == second
        text.write_bytes(b"line1\nline2\n")
        assert site_state.fingerprint_for_paths([text, binary], root) == first
        binary.write_bytes(b"binary-two")
        assert site_state.fingerprint_for_paths([text, binary], root) != first


def assert_check_preview_and_write_modes() -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        generated = Path(temp_dir)
        original_generated = site_state.GENERATED_DIR
        site_state.GENERATED_DIR = generated
        try:
            current = document("1" * 40, "2026-01-01T00:00:00+09:00", "STATE=OK")
            expected = document("2" * 40, "2026-02-02T00:00:00+09:00", "STATE=OK")
            target = generated / "STATE.md"
            target.write_text(current, encoding="utf-8", newline="\n")
            rendered = {"STATE.md": expected}
            data = {"pages": [], "upcoming": [], "state_fingerprint": FINGERPRINT}

            output = io.StringIO()
            with redirect_stdout(output), redirect_stderr(output):
                assert site_state.check(data, rendered, None, False) == 0
                assert site_state.check(data, rendered, None, True) == 1
                assert site_state.preview(rendered, False) == 0
            text = output.getvalue()
            assert "CHECK=OK" in text
            assert "metadata_or_content_drift" in text
            assert "metadata_only=yes" in text
            assert "--- " not in text

            with redirect_stdout(io.StringIO()):
                assert site_state.write(rendered, False) == 0
            assert target.read_text(encoding="utf-8") == current
            with redirect_stdout(io.StringIO()):
                assert site_state.write(rendered, True) == 0
            assert target.read_text(encoding="utf-8") == expected

            target.write_text(expected.replace("STATE=OK", "STATE=STALE"), encoding="utf-8")
            with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
                assert site_state.check(data, rendered, None, False) == 1
        finally:
            site_state.GENERATED_DIR = original_generated


def main() -> None:
    assert_metadata_is_not_content_drift()
    assert_fingerprint_is_deterministic()
    assert_check_preview_and_write_modes()
    print("SITE_STATE_METADATA_TESTS: passed")


if __name__ == "__main__":
    main()
