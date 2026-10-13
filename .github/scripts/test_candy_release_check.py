from __future__ import annotations

import importlib.util
import re
import sys
import tempfile
from pathlib import Path


SCRIPT = Path(__file__).with_name("candy_release_check.py").resolve()
REPOSITORY_ROOT = SCRIPT.parents[2]
PRODUCTION_WORKFLOW = REPOSITORY_ROOT / ".github" / "workflows" / "candy-production-deploy.yml"
HTACCESS_WORKFLOW = REPOSITORY_ROOT / ".github" / "workflows" / "candy-htaccess-deploy.yml"
HTACCESS = REPOSITORY_ROOT / "HP" / ".htaccess"
PUBLISHERS = (
    REPOSITORY_ROOT / "management" / "scripts" / "candy_area_publish.py",
    REPOSITORY_ROOT / "management" / "scripts" / "candy_hotel_publish.py",
    REPOSITORY_ROOT / "management" / "scripts" / "candy_category_publish.py",
)


def load_module():
    spec = importlib.util.spec_from_file_location("candy_release_check_tested", SCRIPT)
    assert spec and spec.loader
    module = importlib.util.module_from_spec(spec)
    sys.modules[spec.name] = module
    spec.loader.exec_module(module)
    return module


def response_map(module) -> dict[str, tuple[int, str, dict[str, str], bytes]]:
    body = (
        '<html><head><title>鹿児島 デリヘル キャンディ</title>'
        '<link rel="canonical" href="https://www.55810.com"></head>'
        '<body><h1>鹿児島 デリヘル キャンディ</h1></body></html>'
    ).encode("utf-8")
    return {
        module.PUBLIC_ROOT: (200, module.PUBLIC_ROOT, {}, body),
        module.PUBLIC_INDEX: (301, module.PUBLIC_INDEX, {"Location": module.PUBLIC_ROOT}, b""),
        "http://www.55810.com/": (
            301,
            "http://www.55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        "https://55810.com/": (
            301,
            "https://55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        "http://55810.com/": (
            301,
            "http://55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        module.DIRECT_ROOT: (
            200,
            module.DIRECT_ROOT,
            {"X-Robots-Tag": "noindex"},
            body,
        ),
    }


def access_response_map(module) -> dict[str, tuple[int, str, dict[str, str], bytes]]:
    return {
        module.SOURCE_DIRECTORY: (404, module.SOURCE_DIRECTORY, {}, b""),
        module.SOURCE_HTML: (404, module.SOURCE_HTML, {}, b""),
        module.SOURCE_TEMPLATE: (404, module.SOURCE_TEMPLATE, {}, b""),
        module.SOURCE_STYLE: (200, module.SOURCE_STYLE, {}, b"body{}"),
        module.INCLUDE_DIRECTORY: (403, module.INCLUDE_DIRECTORY, {}, b""),
        module.INCLUDE_FILE: (403, module.INCLUDE_FILE, {}, b""),
        module.INCLUDE_NESTED_FILE: (403, module.INCLUDE_NESTED_FILE, {}, b""),
    }


def expect_failure(module, responses, expected: str) -> None:
    try:
        module.verify_entry_contract(lambda url: responses[url])
    except RuntimeError as exc:
        assert expected in str(exc), str(exc)
    else:
        raise AssertionError(f"invalid entry contract was accepted: {expected}")


def expect_access_failure(module, responses, expected: str) -> None:
    try:
        module.verify_internal_path_access_contract(lambda url: responses[url])
    except RuntimeError as exc:
        assert expected in str(exc), str(exc)
    else:
        raise AssertionError(f"invalid internal-path access contract was accepted: {expected}")


def assert_htaccess_access_contract() -> None:
    lines = HTACCESS.read_text(encoding="utf-8").splitlines()
    source_directory_rule = "RewriteRule ^source/?$ - [R=404,L,NC]"
    source_html_rule = "RewriteRule ^source/.*\\.html$ - [R=404,L,NC]"
    include_rule = "RewriteRule ^includefile(?:/|$) - [F,L,NC]"
    for rule in (source_directory_rule, source_html_rule, include_rule):
        assert lines.count(rule) == 1, f"missing or duplicate htaccess rule: {rule}"

    canonical_redirect = "RewriteRule ^ https://www.55810.com%{REQUEST_URI} [R=301,L,NE]"
    index_removal = "# Remove an explicitly requested index.php/index.html from public URLs."
    assert (
        lines.index(canonical_redirect)
        < lines.index(source_directory_rule)
        < lines.index(include_rule)
        < lines.index(index_removal)
    )

    source_directory_pattern = re.compile(r"^source/?$", flags=re.IGNORECASE)
    source_html_pattern = re.compile(r"^source/.*\.html$", flags=re.IGNORECASE)
    include_pattern = re.compile(r"^includefile(?:/|$)", flags=re.IGNORECASE)
    assert source_directory_pattern.search("source/")
    assert source_html_pattern.search("source/mypage.html")
    assert source_html_pattern.search("source/template_kagoshima-deliveryhealth-blog.html")
    assert include_pattern.search("includefile/dataset_base.php")
    assert include_pattern.search("includefile/member/bootstrap.php")
    assert not source_directory_pattern.search("source/style.css")
    assert not source_html_pattern.search("source/style.css")
    assert not include_pattern.search("source/style.css")
    print("HTACCESS_ACCESS_RULES_OK=source_404,include_403,source_style_unmatched")


def seo_body(url: str, title: str, extra_body: str = "") -> bytes:
    return (
        "<html><head>"
        f"<title>{title}</title>"
        '<meta name="description" content="説明">'
        '<meta name="robots" content="index">'
        f'<link rel="canonical" href="{url}">'
        f'<meta property="og:title" content="{title}">'
        '<meta property="og:type" content="website">'
        f'<meta property="og:url" content="{url}">'
        '<meta property="og:image" content="https://www.55810.com/image.jpg">'
        '<meta property="og:description" content="説明">'
        '<meta property="og:site_name" content="CANDY">'
        '<script type="application/ld+json">{"@type":"WebPage"}</script>'
        "</head><body>"
        f"<h1>{title}</h1>{extra_body}"
        "</body></html>"
    ).encode("utf-8")


def assert_title_content_contract(module) -> None:
    url = f"{module.PUBLIC_ROOT}kagoshima-deliveryhealth-area-taniyamakou.php"
    baseline = seo_body(url, "正常なタイトル")
    original_title = "<title>正常なタイトル</title>".encode("utf-8")
    invalid = (
        ("谷山港<br>ホテル情報", {"contains_markup"}),
        ("谷山港<BR />ホテル情報", {"contains_markup"}),
        ("谷山港<b>ホテル情報</b>", {"contains_markup"}),
        ("谷山港<!-- 原稿 -->ホテル情報", {"contains_markup"}),
        ("谷山港<?draft metadata?>ホテル情報", {"contains_markup"}),
        ("谷山港&lt;br&gt;ホテル情報", {"contains_markup"}),
        ("谷山港&#60;br&#62;ホテル情報", {"contains_markup"}),
        ("谷山港<改行>ホテル情報", {"contains_markup"}),
        ("谷山港\n-------------------------------------------------------------------s", {"contains_separator"}),
        ("谷山港\n━━━━━━━━━━━━━━━━\nホテル情報", {"contains_separator"}),
        ("谷山港\ndescription\n説明文", {"contains_field_label"}),
        ("谷山港\r\ncanonical\r\nhttps://www.55810.com/", {"contains_field_label"}),
        ("谷山港\nimage\n画像URL", {"contains_field_label"}),
        (
            "鹿児島市谷山港で呼べるデリヘル｜対応店舗・ホテル情報"
            "<br>-------------------------------------------------------------------s"
            "<br>description<br>鹿児島市「谷山港」の店舗情報",
            {"contains_markup", "contains_separator"},
        ),
    )
    for value, expected in invalid:
        body = baseline.replace(original_title, f"<title>{value}</title>".encode("utf-8"), 1)
        issues, _title, canonical = module.validate_public_seo_page(url, (200, url, {}, body))
        assert canonical == url
        assert set(issues) == {f"{url}:title_{item}" for item in expected}, (value, issues)

    valid = (
        ("鹿児島市谷山港で呼べるデリヘル｜対応店舗・ホテル情報", "鹿児島市谷山港で呼べるデリヘル｜対応店舗・ホテル情報"),
        ("鹿児島 - 谷山港・ホテル情報", "鹿児島 - 谷山港・ホテル情報"),
        ("Relax &amp; Sleep - 鹿児島", "Relax & Sleep - 鹿児島"),
        ("1 &lt; 2・鹿児島", "1 < 2・鹿児島"),
        ("description付きの案内 - 鹿児島", "description付きの案内 - 鹿児島"),
        ("A--Bの案内", "A--Bの案内"),
        ("\n  鹿児島 -\n  谷山港・ホテル情報\n", "鹿児島 - 谷山港・ホテル情報"),
    )
    for value, expected_title in valid:
        body = baseline.replace(original_title, f"<title>{value}</title>".encode("utf-8"), 1)
        issues, title, canonical = module.validate_public_seo_page(url, (200, url, {}, body))
        assert (issues, title, canonical) == ([], expected_title, url), (value, issues, title)

    # OGP uses the same plain-text contract, even when the document title is clean.
    bad_ogp = baseline.replace(
        'content="正常なタイトル"'.encode("utf-8"),
        'content="谷山港\n-------------------------------------------------------------------s\ndescription\n説明文"'.encode("utf-8"),
        1,
    )
    issues, _title, _canonical = module.validate_public_seo_page(url, (200, url, {}, bad_ogp))
    assert set(issues) == {
        f"{url}:og:title_contains_separator",
        f"{url}:og:title_contains_field_label",
    }, issues
    for extra in (
        '<meta property="og:title" content="">',
        '<meta property="og:title" content="   ">',
        '<meta property="og:title" content="別タイトル">',
    ):
        body = baseline.replace(b"</head>", (extra + "</head>").encode("utf-8"), 1)
        issues, _title, _canonical = module.validate_public_seo_page(url, (200, url, {}, body))
        assert issues == [f"{url}:og:title_count:2"], (extra, issues)
    empty_ogp = baseline.replace(
        'property="og:title" content="正常なタイトル"'.encode("utf-8"),
        b'property="og:title" content=""',
        1,
    )
    issues, _title, _canonical = module.validate_public_seo_page(url, (200, url, {}, empty_ogp))
    assert issues == [f"{url}:og:title_count:1"], issues
    print("TITLE_CONTENT_CONTRACT_TESTS: passed")


def assert_full_seo_contract(module) -> None:
    with tempfile.TemporaryDirectory() as temp_dir:
        root = Path(temp_dir)
        hp_root = root / "HP"
        hp_root.mkdir()
        sitemap_urls = [module.PUBLIC_ROOT, module.PUBLIC_GIRLS_LIST]
        sitemap_body = (
            '<?xml version="1.0" encoding="UTF-8"?>'
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            + "".join(f"<url><loc>{url}</loc></url>" for url in sitemap_urls)
            + "</urlset>"
        ).encode("utf-8")
        sitemap_path = hp_root / "sitemap.xml"
        sitemap_path.write_bytes(sitemap_body)
        for name in module.ACTIVE_NON_SITEMAP_ROOTS | {"girls_list.php"}:
            (hp_root / name).write_text("<?php", encoding="utf-8")

        profile_url = f"{module.PUBLIC_ROOT}girls.php?no=7"
        responses = response_map(module)
        responses.update(access_response_map(module))
        responses[module.PUBLIC_SITEMAP] = (
            200,
            module.PUBLIC_SITEMAP,
            {},
            sitemap_body,
        )
        responses[module.PUBLIC_ROOT] = (
            200,
            module.PUBLIC_ROOT,
            {},
            seo_body(module.PUBLIC_ROOT, module.TOP_TEXT),
        )
        responses[module.PUBLIC_GIRLS_LIST] = (
            200,
            module.PUBLIC_GIRLS_LIST,
            {},
            seo_body(
                module.PUBLIC_GIRLS_LIST,
                "女の子一覧",
                '<a href="girls.php?no=7">プロフィール</a>',
            ),
        )
        responses[profile_url] = (
            200,
            profile_url,
            {},
            seo_body(profile_url, "テストのプロフィール"),
        )
        girls_url = f"{module.PUBLIC_ROOT}girls.php"
        responses[girls_url] = (
            301,
            girls_url,
            {"Location": module.PUBLIC_GIRLS_LIST},
            b"",
        )
        movie_url = f"{module.PUBLIC_ROOT}movie_iframe.php"
        responses[movie_url] = (
            404,
            movie_url,
            {"X-Robots-Tag": "noindex, nofollow"},
            b"",
        )

        result = module.verify_full_seo_contract(
            responses.__getitem__, sitemap_path, hp_root
        )
        assert result == {
            "sitemap_pages": 2,
            "profile_pages": 1,
            "public_pages": 3,
            "active_non_sitemap_routes": 2,
        }

        clean_profile = responses[profile_url]
        responses[profile_url] = (
            200,
            profile_url,
            {},
            seo_body(profile_url, "プロフィール&lt;br&gt;description"),
        )
        try:
            module.verify_full_seo_contract(responses.__getitem__, sitemap_path, hp_root)
        except module.FullSeoAuditError as exc:
            assert f"{profile_url}:title_contains_markup" in exc.issues, exc.issues
        else:
            raise AssertionError("full SEO accepted a contaminated title")
        responses[profile_url] = clean_profile

        (hp_root / "unmanaged.php").write_text("<?php", encoding="utf-8")
        try:
            module.verify_full_seo_contract(responses.__getitem__, sitemap_path, hp_root)
        except module.FullSeoAuditError as exc:
            assert any(
                "local_php:unclassified_non_sitemap:unmanaged.php" in issue
                for issue in exc.issues
            )
        else:
            raise AssertionError("unmanaged non-sitemap page was accepted")


def main() -> int:
    module = load_module()
    assert_htaccess_access_contract()
    assert_title_content_contract(module)
    assert_full_seo_contract(module)
    responses = response_map(module)
    requested: list[str] = []

    def fetch(url: str):
        requested.append(url)
        return responses[url]

    module.verify_entry_contract(fetch)
    assert requested == [
        module.PUBLIC_ROOT,
        module.PUBLIC_INDEX,
        "http://www.55810.com/",
        "https://55810.com/",
        "http://55810.com/",
        module.DIRECT_ROOT,
    ]

    external_redirect = response_map(module)
    external_redirect[module.PUBLIC_ROOT] = (
        301,
        module.PUBLIC_ROOT,
        {"Location": "https://www.cityheaven.net/example/"},
        b"",
    )
    expect_failure(module, external_redirect, "root_200")

    wrong_index = response_map(module)
    wrong_index[module.PUBLIC_INDEX] = (
        301,
        module.PUBLIC_INDEX,
        {"Location": "https://www.cityheaven.net/example/"},
        b"",
    )
    expect_failure(module, wrong_index, "index_redirect")

    public_noindex = response_map(module)
    public_status, public_final, _headers, public_body = public_noindex[module.PUBLIC_ROOT]
    public_noindex[module.PUBLIC_ROOT] = (
        public_status,
        public_final,
        {"X-Robots-Tag": "noindex"},
        public_body,
    )
    expect_failure(module, public_noindex, "public_indexable")

    direct_without_noindex = response_map(module)
    direct_without_noindex[module.DIRECT_ROOT] = (
        200,
        module.DIRECT_ROOT,
        {},
        b"",
    )
    expect_failure(module, direct_without_noindex, "direct_host_noindex")

    access_responses = access_response_map(module)
    access_requested: list[str] = []

    def access_fetch(url: str):
        access_requested.append(url)
        return access_responses[url]

    module.verify_internal_path_access_contract(access_fetch)
    assert access_requested == [
        module.SOURCE_DIRECTORY,
        module.SOURCE_HTML,
        module.SOURCE_TEMPLATE,
        module.SOURCE_STYLE,
        module.INCLUDE_DIRECTORY,
        module.INCLUDE_FILE,
        module.INCLUDE_NESTED_FILE,
    ]

    exposed_source = access_response_map(module)
    exposed_source[module.SOURCE_HTML] = (200, module.SOURCE_HTML, {}, b"raw source")
    expect_access_failure(module, exposed_source, "source_html_404")

    exposed_include = access_response_map(module)
    exposed_include[module.INCLUDE_FILE] = (200, module.INCLUDE_FILE, {}, b"")
    expect_access_failure(module, exposed_include, "include_file_403")

    missing_style = access_response_map(module)
    missing_style[module.SOURCE_STYLE] = (404, module.SOURCE_STYLE, {}, b"")
    expect_access_failure(module, missing_style, "source_style_200")

    for workflow in (PRODUCTION_WORKFLOW, HTACCESS_WORKFLOW):
        workflow_text = workflow.read_text(encoding="utf-8")
        assert "python3 .github/scripts/test_candy_release_check.py" in workflow_text
        assert "python3 .github/scripts/candy_release_check.py --entry-only" in workflow_text

    htaccess_workflow_text = HTACCESS_WORKFLOW.read_text(encoding="utf-8")
    assert "python3 .github/scripts/candy_release_check.py --access-control-only" in htaccess_workflow_text

    publisher_texts = [publisher.read_text(encoding="utf-8") for publisher in PUBLISHERS]
    assert "verify_entry_contract()" in publisher_texts[0]
    assert "shared.verify_entry_contract()" in publisher_texts[1]
    assert "release.verify_entry_contract()" in publisher_texts[2]
    assert all("EXPECTED_REDIRECT" not in text for text in publisher_texts)

    print("RELEASE_ENTRY_AND_ACCESS_CONTRACT_TESTS: passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
