from __future__ import annotations

from contextlib import redirect_stderr, redirect_stdout
import io
from pathlib import Path
import sys
import tempfile
from unittest.mock import patch


SCRIPTS = Path(__file__).resolve().parents[2] / "management" / "scripts"
if str(SCRIPTS) not in sys.path:
    sys.path.insert(0, str(SCRIPTS))

import candy_site_state as site_state
import candy_area_page as area_page
from candy_page_common import title_metadata_issues, title_text_issues


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


def assert_title_metadata_rejects_source_spillover() -> None:
    valid_title = "鹿児島市谷山港で呼べるデリヘル - CANDY &amp; ご案内"
    assert title_text_issues(valid_title) == []
    assert title_text_issues("\n  鹿児島市谷山港で呼べるデリヘル\n  - CANDY\n") == []
    assert title_text_issues("rep09000004eot") == []
    for value in ("谷山港<br>ご案内", "谷山港<改行>ご案内", "谷山港&lt;br&gt;ご案内", "谷山港<!-- comment -->"):
        assert "title_markup" in title_text_issues(value), value
    for value in ("谷山港-----s\ndescription\n別の説明", "谷山港\n━━━━━\n説明"):
        assert "title_separator" in title_text_issues(value), value
    for label in ("description", "canonical", "image", "og:title"):
        assert "title_metadata_label" in title_text_issues(f"谷山港\n{label}\n別の値"), label
    assert "title_metadata_label" in title_text_issues("谷山港\ndescription: 別の説明")
    assert title_text_issues(" \n &nbsp; ") == ["title_empty"]

    valid = f'<title>{valid_title}</title><meta property="og:title" content="{valid_title}">'
    assert title_metadata_issues(valid) == {}
    assert title_metadata_issues('<!-- <title>ignored</title> -->' + valid) == {}
    assert title_metadata_issues(valid.replace(f"<title>{valid_title}</title>", "<title>谷山港<br>説明</title>")) == {
        "title": ["title_markup"]
    }
    for bad_og in ("谷山港&lt;br&gt;説明", "谷山港<br>説明"):
        source = f'<title>正常なタイトル</title><meta content="{bad_og}" property="og:title">'
        assert title_metadata_issues(source) == {"og:title": ["title_markup"]}
    assert title_metadata_issues(valid + "<title>二つ目</title>") == {"title": ["title_count=2"]}
    assert title_metadata_issues(valid + '<meta property="og:title" content="二つ目">') == {
        "og:title": ["title_count=2"]
    }
    assert title_metadata_issues('<title> </title><meta property="og:title" content="&nbsp;">') == {
        "title": ["title_empty"], "og:title": ["title_empty"]
    }
    assert title_metadata_issues("") == {"title": ["title_count=0"], "og:title": ["title_count=0"]}
    assert "title_markup" in title_metadata_issues("<title>閉じていないタイトル")["title"]


def assert_collect_reports_title_and_og_title_separately() -> None:
    """Exercise the actual SEO verdict with offline files, including dynamic exclusions."""
    def source(title: str, og_title: str, *, girls: bool = False, development: bool = False) -> str:
        canonical = "rep03010092eot" if girls else "https://www.55810.com/"
        robots = "noindex,nofollow" if development else "index"
        structured = "rep09000007eot" if girls else '<script type="application/ld+json">{"@type":"WebSite"}</script>'
        return (
            f"<title>{title}</title>"
            '<meta name="description" content="ページの説明">'
            f'<meta name="robots" content="{robots}">'
            f'<link rel="canonical" href="{canonical}">'
            f'<meta property="og:title" content="{og_title}">'
            f'<meta property="og:url" content="{canonical}">'
            '<meta property="og:image" content="rep09000006eot">'
            '<meta property="og:description" content="ページの説明">'
            "<h1>ページの見出し</h1>" + structured
        )

    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        hp = root / "HP"
        sources = hp / "source"
        sources.mkdir(parents=True)
        for stem in ("index", "girls", "privacy"):
            (hp / f"{stem}.php").write_text("<?php require 'includefile/member/bootstrap.php';", encoding="utf-8")
        bootstrap = hp / "includefile" / "member" / "bootstrap.php"
        bootstrap.parent.mkdir(parents=True)
        bootstrap.write_text("<?php header('X-Robots-Tag: noindex, nofollow');", encoding="utf-8")
        (hp / "sitemap.xml").write_text(
            '<urlset><url><loc>https://www.55810.com/</loc><lastmod>2026-10-13</lastmod></url></urlset>',
            encoding="utf-8",
        )
        (sources / "girls.html").write_text(source("rep09000004eot", "rep09000004eot", girls=True), encoding="utf-8")
        (sources / "privacy.html").write_text(source("未公開<br>ページ", "未公開&lt;br&gt;ページ", development=True), encoding="utf-8")
        with (
            patch.object(site_state, "REPO_ROOT", root),
            patch.object(site_state, "HP_ROOT", hp),
            patch.object(site_state, "parse_text_records", return_value=[]),
            patch.object(site_state, "sitemap_lastmod_expectations", return_value=({"https://www.55810.com/": "2026-10-13"}, [])),
            patch.object(site_state, "asset_references", return_value=({}, {}, {})),
            patch.object(site_state, "generation_base_head", return_value=("f" * 40, "2026-10-13")),
            patch.object(site_state, "git_value", return_value="offline"),
            patch.object(site_state, "state_fingerprint_paths", return_value=[]),
        ):
            cases = (
                ("谷山港 - CANDY &amp; 案内\n", "谷山港 - CANDY &amp; 案内", None),
                ("谷山港<br>-----s\ndescription\n原稿説明", "正常なOGタイトル", "title_markup"),
                ("正常なタイトル", "谷山港&lt;br&gt;原稿説明", "og:title:title_markup"),
            )
            for title, og_title, expected_issue in cases:
                (sources / "index.html").write_text(source(title, og_title), encoding="utf-8")
                rows = {row["stem"]: row for row in site_state.collect()["seo"]}
                row = rows["index"]
                if expected_issue is None:
                    assert row["overall"] == "OK", row["issues"]
                else:
                    assert row["overall"] == "ISSUE", row
                    assert expected_issue in row["issues"], row["issues"]
                if expected_issue == "title_markup":
                    assert row["ogp"] == "OK", row["issues"]
                    assert "title_separator" in row["issues"]
                    assert "title_metadata_label" in row["issues"]
                if expected_issue == "og:title:title_markup":
                    assert row["ogp"] == "ISSUE"
                    assert "title" not in row["issues"], row["issues"]
                assert rows["girls"]["overall"] == "OK", rows["girls"]["issues"]
                assert rows["privacy"]["overall"] == "OK", rows["privacy"]["issues"]
                assert rows["privacy"]["ogp"] == "NOT_APPLICABLE"


def assert_faq_items_do_not_depend_on_heading_copy() -> None:
    source = (
        '<section id="scene10">\n'
        '<h2>よくある「女の子選び」の疑問</h2>\n'
        '<div class="girl-choice-faq">\n'
        '<div class="faq-item"><h3>質問1</h3><div>回答1</div></div>\n'
        '<div class="faq-item bd_b"><h3>質問2</h3><div>回答2</div></div>\n'
        '</div>\n'
        '</section>\n'
        '<script>document.querySelectorAll(".faq-item")</script>\n'
    )
    assert site_state.visible_faq_item_count(source) == 2


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


def main() -> None:
    assert_metadata_is_not_content_drift()
    assert_fingerprint_is_deterministic()
    assert_check_preview_and_write_modes()
    assert_area_og_image_pair_is_enforced()
    assert_site_state_checks_og_image_target()
    assert_title_metadata_rejects_source_spillover()
    assert_collect_reports_title_and_og_title_separately()
    assert_faq_items_do_not_depend_on_heading_copy()
    assert_sitemap_lastmod_rendering_is_exact()
    assert_sitemap_lastmod_rejects_ambiguous_or_invalid_input()
    print("SITE_STATE_METADATA_TESTS: passed")


if __name__ == "__main__":
    main()
