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
import candy_area_page as area_page


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


def assert_area_og_image_pair_is_enforced() -> None:
    image1 = "./imgHtml/new_202601/area/kagoshima-deliveryhealth-area-test_1.jpg"
    expected = "https://www.55810.com/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-test_1.jpg"
    assert area_page.ensure_og_image_matches(expected, image1) == expected
    try:
        area_page.ensure_og_image_matches(
            "https://www.55810.com/imgHtml/new_202601/kagoshima-deliveryhealth-area-test_1.jpg",
            image1,
        )
    except area_page.AreaToolError as exc:
        assert "imageとimg_1の公開URLが一致しません" in str(exc)
    else:
        raise AssertionError("area OGP mismatch was accepted")


def assert_site_state_checks_og_image_target() -> None:
    def source(image: str) -> str:
        return (
            '<meta property="og:title" content="test">\n'
            '<meta property="og:url" content="https://www.55810.com/test.php">\n'
            f'<meta property="og:image" content="{image}">\n'
            '<meta property="og:description" content="test">\n'
        )

    with tempfile.TemporaryDirectory() as temp_dir:
        hp_root = Path(temp_dir)
        image = hp_root / "imgHtml" / "new_202601" / "area" / "test.jpg"
        image.parent.mkdir(parents=True)
        image.write_bytes(b"jpeg")
        valid = "https://www.55810.com/imgHtml/new_202601/area/test.jpg"
        missing = "https://www.55810.com/imgHtml/new_202601/test.jpg"
        assert site_state.ogp_validation_issues(source(valid), hp_root) == []
        assert site_state.ogp_validation_issues(source(missing), hp_root) == [
            "og_image_missing=/imgHtml/new_202601/test.jpg"
        ]
        assert site_state.ogp_validation_issues(source("./imgHtml/new_202601/area/test.jpg"), hp_root) == [
            "og_image_not_absolute_https"
        ]
        assert site_state.ogp_validation_issues(source("rep01010007eot"), hp_root) == []


def assert_sitemap_lastmod_rendering_is_exact() -> None:
    root_url = "https://www.55810.com"
    area_url = "https://www.55810.com/area.php"
    source = (
        '<?xml version="1.0" encoding="UTF-8"?>\n'
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'
        "  <url>\n"
        f"    <loc>{root_url}</loc>\n"
        "    <lastmod>2026-03-03</lastmod>\n"
        "    <changefreq>weekly</changefreq>\n"
        "  </url>\n"
        "  <url>\n"
        f"    <loc>{area_url}</loc>\n"
        "    <lastmod>2026-07-25</lastmod>\n"
        "    <changefreq>weekly</changefreq>\n"
        "  </url>\n"
        "</urlset>\n"
    )
    rendered, changes = site_state.render_sitemap_lastmods(
        source,
        {
            root_url: "2026-07-25",
            area_url: "2026-07-25",
        },
    )
    assert changes == [(root_url, "2026-03-03", "2026-07-25")]
    assert rendered.count("<lastmod>2026-07-25</lastmod>") == 2
    assert "<changefreq>weekly</changefreq>" in rendered
    assert site_state.sitemap_source_rel(root_url) == "HP/source/index.html"
    assert site_state.sitemap_source_rel(area_url) == "HP/source/area.html"
    assert site_state.sitemap_source_rel("http://www.55810.com/area.php") is None


def assert_sitemap_lastmod_rejects_ambiguous_or_invalid_input() -> None:
    url = "https://www.55810.com/area.php"
    duplicate = (
        "<urlset>\n"
        f"<url><loc>{url}</loc><lastmod>2026-03-03</lastmod></url>\n"
        f"<url><loc>{url}</loc><lastmod>2026-03-03</lastmod></url>\n"
        "</urlset>\n"
    )
    try:
        site_state.render_sitemap_lastmods(duplicate, {url: "2026-07-25"})
    except ValueError as exc:
        assert "match count invalid" in str(exc)
    else:
        raise AssertionError("duplicate sitemap URL was accepted")

    try:
        site_state.render_sitemap_lastmods(duplicate, {url: "2026/07/25"})
    except ValueError as exc:
        assert "invalid sitemap lastmod expectation" in str(exc)
    else:
        raise AssertionError("invalid sitemap lastmod date was accepted")


def assert_noindex_test_page_is_sitemap_excluded() -> None:
    assert "girls_test" in site_state.SPECIAL_STEMS
    assert "girls_test" in site_state.SEO_HELPER_STEMS


def main() -> None:
    assert_metadata_is_not_content_drift()
    assert_fingerprint_is_deterministic()
    assert_check_preview_and_write_modes()
    assert_area_og_image_pair_is_enforced()
    assert_site_state_checks_og_image_target()
    assert_sitemap_lastmod_rendering_is_exact()
    assert_sitemap_lastmod_rejects_ambiguous_or_invalid_input()
    assert_noindex_test_page_is_sitemap_excluded()
    print("SITE_STATE_METADATA_TESTS: passed")


if __name__ == "__main__":
    main()
