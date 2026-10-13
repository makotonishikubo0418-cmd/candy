#!/usr/bin/env python3
"""Wait for the automatic CANDY deployment and verify its production URL."""

from __future__ import annotations

import argparse
import html
import json
import re
import shutil
import subprocess
import time
import xml.etree.ElementTree as ET
from collections.abc import Callable
from concurrent.futures import ThreadPoolExecutor, as_completed
from html.parser import HTMLParser
from pathlib import Path
from urllib.error import HTTPError
from urllib.parse import parse_qs, quote, urljoin, urlsplit, urlunsplit
from urllib.request import HTTPRedirectHandler, Request, build_opener, urlopen


REPOSITORY = "makotonishikubo0418-cmd/candy"
WORKFLOW = "candy-production-deploy.yml"
USER_AGENT = "candy-release-check"
PUBLIC_ROOT = "https://www.55810.com/"
PUBLIC_INDEX = "https://www.55810.com/index.php"
CANONICAL_ROOT = "https://www.55810.com"
DIRECT_ROOT = "http://firststar.kir.jp/group/candy/"
TOP_TEXT = "鹿児島 デリヘル キャンディ"
SOURCE_DIRECTORY = f"{PUBLIC_ROOT}source/"
SOURCE_HTML = f"{PUBLIC_ROOT}source/mypage.html"
SOURCE_TEMPLATE = f"{PUBLIC_ROOT}source/template_kagoshima-deliveryhealth-blog.html"
SOURCE_STYLE = f"{PUBLIC_ROOT}source/style.css"
INCLUDE_DIRECTORY = f"{PUBLIC_ROOT}includefile/"
INCLUDE_FILE = f"{PUBLIC_ROOT}includefile/dataset_base.php"
INCLUDE_NESTED_FILE = f"{PUBLIC_ROOT}includefile/member/bootstrap.php"
REPOSITORY_ROOT = Path(__file__).resolve().parents[2]
LOCAL_SITEMAP = REPOSITORY_ROOT / "HP" / "sitemap.xml"
LOCAL_HP_ROOT = REPOSITORY_ROOT / "HP"
PUBLIC_SITEMAP = f"{PUBLIC_ROOT}sitemap.xml"
PUBLIC_GIRLS_LIST = f"{PUBLIC_ROOT}girls_list.php"
ACTIVE_NON_SITEMAP_ROOTS = {
    "girls.php",
    "index.php",
    "movie_iframe.php",
}
INACTIVE_DEVELOPMENT_ROOTS = {
    "member_login.php",
    "member_register.php",
    "member_mypage.php",
    "member_password_reset.php",
    "member_logout.php",
    "privacy.php",
}


class NoRedirect(HTTPRedirectHandler):
    def redirect_request(self, request, file_pointer, code, message, headers, new_url):
        return None


class SeoHtmlParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__(convert_charrefs=True)
        self.title_parts: list[str] = []
        self.title_count = 0
        self.title_has_markup = False
        self.h1_parts: list[list[str]] = []
        self.meta: list[dict[str, str]] = []
        self.canonicals: list[str] = []
        self.links: list[str] = []
        self.json_ld: list[str] = []
        self._in_title = False
        self._h1_depth = 0
        self._script_type = ""
        self._script_parts: list[str] = []

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        attributes = {name.lower(): value or "" for name, value in attrs}
        lowered = tag.lower()
        if self._in_title:
            self.title_has_markup = True
        if lowered == "title":
            self._in_title = True
            self.title_count += 1
        elif lowered == "h1":
            self._h1_depth += 1
            self.h1_parts.append([])
        elif lowered == "meta":
            self.meta.append(attributes)
        elif lowered == "link" and "canonical" in attributes.get("rel", "").lower().split():
            self.canonicals.append(attributes.get("href", "").strip())
        elif lowered == "a":
            href = attributes.get("href", "").strip()
            if href:
                self.links.append(href)
        elif lowered == "script":
            self._script_type = attributes.get("type", "").strip().lower()
            self._script_parts = []

    def handle_startendtag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        self.handle_starttag(tag, attrs)

    def handle_endtag(self, tag: str) -> None:
        lowered = tag.lower()
        if self._in_title and lowered != "title":
            self.title_has_markup = True
        if lowered == "title":
            self._in_title = False
        elif lowered == "h1" and self._h1_depth:
            self._h1_depth -= 1
        elif lowered == "script":
            if self._script_type == "application/ld+json":
                self.json_ld.append("".join(self._script_parts).strip())
            self._script_type = ""
            self._script_parts = []

    def handle_comment(self, data: str) -> None:
        if self._in_title:
            self.title_has_markup = True

    def handle_decl(self, decl: str) -> None:
        if self._in_title:
            self.title_has_markup = True

    def handle_pi(self, data: str) -> None:
        if self._in_title:
            self.title_has_markup = True

    def handle_data(self, data: str) -> None:
        if self._in_title:
            self.title_parts.append(data)
        if self._h1_depth and self.h1_parts:
            self.h1_parts[-1].append(data)
        if self._script_type == "application/ld+json":
            self._script_parts.append(data)


def normalized_text(parts: list[str]) -> str:
    return re.sub(r"\s+", " ", html.unescape("".join(parts))).strip()


def title_content_issues(value: str, has_markup: bool = False) -> list[str]:
    """Reject source-field leakage without treating normal punctuation as markup."""
    decoded = html.unescape(value)
    issues: list[str] = []
    if has_markup or re.search(
        r"<\s*(?:/?[a-z][a-z0-9:-]*(?=[\s/>])|!--|!doctype|\?|改行>)",
        decoded,
        flags=re.IGNORECASE,
    ):
        issues.append("contains_markup")
    if re.search(r"[-=━─_*]{5,}", decoded):
        issues.append("contains_separator")
    if re.search(
        r"^\s*(?:title|description|canonical|image|page_title_h1|subtitle_h1|description_h1)"
        r"\s*[:：]?\s*$",
        decoded,
        flags=re.IGNORECASE | re.MULTILINE,
    ):
        issues.append("contains_field_label")
    return issues


def header_value(headers: object, name: str) -> str:
    getter = getattr(headers, "get", None)
    return str(getter(name, "") if callable(getter) else "")


def canonical_key(url: str) -> str:
    parsed = urlsplit(url)
    path = parsed.path or "/"
    return urlunsplit((parsed.scheme.lower(), parsed.netloc.lower(), path, parsed.query, ""))


def meta_values(parser: SeoHtmlParser, key: str, value: str) -> list[str]:
    return [
        item.get("content", "").strip()
        for item in parser.meta
        if item.get(key, "").strip().lower() == value.lower()
    ]


def parse_sitemap_urls(source: bytes, label: str) -> tuple[list[str], list[str]]:
    try:
        root = ET.fromstring(source)
    except ET.ParseError as exc:
        return [], [f"{label}:invalid_xml:{exc}"]
    urls = [
        (node.text or "").strip()
        for node in root.findall(".//{*}loc")
        if (node.text or "").strip()
    ]
    issues: list[str] = []
    if not urls:
        issues.append(f"{label}:no_urls")
    duplicates = sorted({url for url in urls if urls.count(url) > 1})
    issues.extend(f"{label}:duplicate_url:{url}" for url in duplicates)
    return urls, issues


def discover_profile_urls(body: str) -> list[str]:
    parser = SeoHtmlParser()
    parser.feed(body)
    result: set[str] = set()
    for href in parser.links:
        url = urljoin(PUBLIC_GIRLS_LIST, html.unescape(href))
        parsed = urlsplit(url)
        values = parse_qs(parsed.query, keep_blank_values=True).get("no", [])
        if (
            parsed.scheme == "https"
            and parsed.netloc.lower() == "www.55810.com"
            and parsed.path == "/girls.php"
            and len(values) == 1
            and values[0].isdigit()
            and int(values[0]) > 0
        ):
            result.add(urlunsplit((parsed.scheme, parsed.netloc, parsed.path, f"no={int(values[0])}", "")))
    return sorted(result)


def validate_public_seo_page(
    url: str,
    response: tuple[int, str, object, bytes],
) -> tuple[list[str], str, str]:
    status, final_url, headers, raw_body = response
    issues: list[str] = []
    if status != 200:
        return [f"{url}:http_status:{status}"], "", ""
    if canonical_key(final_url) != canonical_key(url):
        issues.append(f"{url}:unexpected_final_url:{final_url}")
    if "noindex" in header_value(headers, "X-Robots-Tag").lower():
        issues.append(f"{url}:x_robots_noindex")
    body = raw_body.decode("utf-8", errors="replace")
    parser = SeoHtmlParser()
    parser.feed(body)
    title = normalized_text(parser.title_parts)
    if parser.title_count != 1 or not title:
        issues.append(f"{url}:title_count:{parser.title_count}")
    issues.extend(
        f"{url}:title_{issue}"
        for issue in title_content_issues("".join(parser.title_parts), parser.title_has_markup)
    )
    descriptions = [value for value in meta_values(parser, "name", "description") if value]
    if len(descriptions) != 1:
        issues.append(f"{url}:description_count:{len(descriptions)}")
    if len(parser.canonicals) != 1:
        issues.append(f"{url}:canonical_count:{len(parser.canonicals)}")
        canonical = ""
    else:
        canonical = parser.canonicals[0]
        if canonical_key(canonical) != canonical_key(url):
            issues.append(f"{url}:canonical_mismatch:{canonical}")
    robots = ",".join(meta_values(parser, "name", "robots")).lower()
    if not robots or "noindex" in robots:
        issues.append(f"{url}:robots_not_indexable:{robots or 'missing'}")
    h1_values = [normalized_text(parts) for parts in parser.h1_parts if normalized_text(parts)]
    if len(h1_values) != 1:
        issues.append(f"{url}:h1_count:{len(h1_values)}")
    required_ogp = ("og:title", "og:type", "og:url", "og:image", "og:description", "og:site_name")
    ogp = {key: meta_values(parser, "property", key) for key in required_ogp}
    for key, values in ogp.items():
        if key == "og:title":
            if len(values) != 1 or not values[0]:
                issues.append(f"{url}:{key}_count:{len(values)}")
        elif len([value for value in values if value]) != 1:
            issues.append(f"{url}:{key}_count:{len([value for value in values if value])}")
    for value in ogp["og:title"]:
        issues.extend(
            f"{url}:og:title_{issue}" for issue in title_content_issues(value)
        )
    if len(ogp["og:url"]) == 1 and canonical_key(ogp["og:url"][0]) != canonical_key(url):
        issues.append(f"{url}:og_url_mismatch:{ogp['og:url'][0]}")
    for index, source in enumerate(parser.json_ld, start=1):
        if not source:
            issues.append(f"{url}:json_ld_{index}:empty")
            continue
        try:
            json.loads(source)
        except json.JSONDecodeError as exc:
            issues.append(f"{url}:json_ld_{index}:invalid:{exc.msg}")
    if not parser.json_ld:
        issues.append(f"{url}:missing_json_ld")
    if re.search(r"rep\d+eot|aaaaaaaa", body, flags=re.IGNORECASE):
        issues.append(f"{url}:live_placeholder")
    return issues, title, canonical


def read_json(url: str) -> dict:
    gh = shutil.which("gh")
    if gh:
        result = subprocess.run(
            [gh, "api", url],
            capture_output=True,
            text=True,
            encoding="utf-8",
            errors="replace",
        )
        if result.returncode == 0:
            return json.loads(result.stdout)
    request = Request(
        url,
        headers={
            "Accept": "application/vnd.github+json",
            "User-Agent": USER_AGENT,
        },
    )
    with urlopen(request, timeout=20) as response:
        return json.load(response)


def wait_for_run(sha: str, timeout_seconds: int) -> str:
    api_url = (
        f"https://api.github.com/repos/{REPOSITORY}/actions/workflows/{WORKFLOW}/runs"
        f"?event=push&head_sha={quote(sha)}&per_page=5"
    )
    deadline = time.monotonic() + timeout_seconds
    last_state = "not_found"
    while time.monotonic() < deadline:
        payload = read_json(api_url)
        runs = [run for run in payload.get("workflow_runs", []) if run.get("head_sha") == sha]
        if runs:
            run = runs[0]
            state = f"{run.get('status')}:{run.get('conclusion')}"
            if state != last_state:
                print(f"ACTIONS_STATE={state}", flush=True)
                last_state = state
            if run.get("status") == "completed":
                run_url = str(run["html_url"])
                print(f"ACTIONS_URL={run_url}")
                if run.get("conclusion") != "success":
                    raise RuntimeError(
                        f"automatic production deployment failed: {run.get('conclusion')}"
                    )
                return run_url
        time.sleep(5)
    raise TimeoutError(f"automatic production deployment was not completed in {timeout_seconds}s")


def visible_text(body: str) -> str:
    without_hidden = re.sub(
        r"<(script|style|noscript|template)\b[^>]*>.*?</\1\s*>",
        " ",
        body,
        flags=re.IGNORECASE | re.DOTALL,
    )
    without_tags = re.sub(r"<[^>]+>", " ", without_hidden)
    return re.sub(r"\s+", " ", html.unescape(without_tags)).strip()


def verify_body_text(
    body: str,
    expected_text: list[str],
    expected_visible_text: list[str],
) -> None:
    for marker in expected_text:
        if marker not in body:
            raise RuntimeError(f"production URL is missing expected text: {marker}")
    rendered_text = visible_text(body)
    for marker in expected_visible_text:
        if marker not in rendered_text:
            raise RuntimeError(f"production URL is missing expected visible text: {marker}")


def verify_url(
    url: str,
    expected_text: list[str],
    expected_visible_text: list[str] | None = None,
) -> None:
    request = Request(url, headers={"User-Agent": USER_AGENT})
    with urlopen(request, timeout=30) as response:
        body = response.read().decode("utf-8", errors="replace")
        status = response.status
        final_url = response.geturl()
    if status != 200:
        raise RuntimeError(f"production URL returned HTTP {status}: {final_url}")
    verify_body_text(body, expected_text, expected_visible_text or [])
    print(f"PRODUCTION_URL={final_url}")
    print(f"HTTP_STATUS={status}")


def self_test() -> int:
    verify_body_text("<p>Relax&amp;Sleep</p>", [], ["Relax&Sleep"])
    verify_body_text("<p>鹿児島ホテル</p>", [], ["鹿児島ホテル"])
    for body in (
        '<script type="application/ld+json">{"name":"Relax&Sleep"}</script>',
        "<script>const hotel = 'Relax&Sleep';</script>",
    ):
        try:
            verify_body_text(body, [], ["Relax&Sleep"])
        except RuntimeError:
            pass
        else:
            raise AssertionError("hidden script text was accepted as visible text")
    verify_body_text("<script>marker</script><p>marker</p>", ["marker"], [])
    print("RELEASE_CHECK_SELF_TEST=passed")
    return 0


def http_fetch(url: str) -> tuple[int, str, object, bytes]:
    request = Request(
        url,
        headers={"User-Agent": USER_AGENT, "Cache-Control": "no-cache"},
    )
    opener = build_opener(NoRedirect)
    try:
        with opener.open(request, timeout=30) as response:
            return response.status, response.geturl(), response.headers, response.read()
    except HTTPError as exc:
        return exc.code, exc.geturl(), exc.headers, exc.read()


def normalized_element_text(body: str, tag: str) -> str:
    match = re.search(
        rf"<{tag}\b[^>]*>(.*?)</{tag}>",
        body,
        flags=re.IGNORECASE | re.DOTALL,
    )
    if not match:
        return ""
    value = re.sub(r"<[^>]+>", "", match.group(1))
    return re.sub(r"\s+", " ", html.unescape(value)).strip()


def verify_entry_contract(
    fetch: Callable[[str], tuple[int, str, object, bytes]] | None = None,
    announce: bool = True,
) -> None:
    fetcher = fetch or http_fetch
    checks: dict[str, bool] = {}

    root_status, root_final, root_headers, root_bytes = fetcher(PUBLIC_ROOT)
    root_body = root_bytes.decode("utf-8", errors="replace")
    checks["root_200"] = root_status == 200 and root_final == PUBLIC_ROOT
    checks["title"] = normalized_element_text(root_body, "title") == TOP_TEXT
    checks["canonical"] = bool(
        re.search(
            rf"<link\b[^>]*\brel=[\"']canonical[\"'][^>]*\bhref=[\"']{re.escape(CANONICAL_ROOT)}/?[\"']",
            root_body,
            flags=re.IGNORECASE,
        )
    )
    checks["h1"] = normalized_element_text(root_body, "h1") == TOP_TEXT
    checks["public_indexable"] = "noindex" not in str(root_headers.get("X-Robots-Tag", "")).lower()

    redirect_expectations = (
        ("index_redirect", PUBLIC_INDEX, PUBLIC_ROOT),
        ("http_www_redirect", "http://www.55810.com/", PUBLIC_ROOT),
        ("https_non_www_redirect", "https://55810.com/", PUBLIC_ROOT),
        ("http_non_www_redirect", "http://55810.com/", PUBLIC_ROOT),
    )
    for label, url, location in redirect_expectations:
        status, _final, headers, _body = fetcher(url)
        checks[label] = status == 301 and headers.get("Location") == location

    direct_status, direct_final, direct_headers, _direct_bytes = fetcher(DIRECT_ROOT)
    checks["direct_host_noindex"] = (
        direct_status == 200
        and direct_final == DIRECT_ROOT
        and "noindex" in str(direct_headers.get("X-Robots-Tag", "")).lower()
    )

    if not all(checks.values()):
        failed = ", ".join(name for name, passed in checks.items() if not passed)
        raise RuntimeError(f"production entry contract failed: {failed}")
    if announce:
        print("ENTRY_CONTRACT_OK=" + ",".join(checks))


def verify_internal_path_access_contract(
    fetch: Callable[[str], tuple[int, str, object, bytes]] | None = None,
    announce: bool = True,
) -> None:
    fetcher = fetch or http_fetch
    expectations = (
        ("source_directory_404", SOURCE_DIRECTORY, 404),
        ("source_html_404", SOURCE_HTML, 404),
        ("source_template_404", SOURCE_TEMPLATE, 404),
        ("source_style_200", SOURCE_STYLE, 200),
        ("include_directory_403", INCLUDE_DIRECTORY, 403),
        ("include_file_403", INCLUDE_FILE, 403),
        ("include_nested_file_403", INCLUDE_NESTED_FILE, 403),
    )
    checks: dict[str, bool] = {}
    for label, url, expected_status in expectations:
        status, final_url, _headers, _body = fetcher(url)
        checks[label] = status == expected_status and final_url == url

    if not all(checks.values()):
        failed = ", ".join(name for name, passed in checks.items() if not passed)
        raise RuntimeError(f"production internal-path access contract failed: {failed}")
    if announce:
        print("INTERNAL_PATH_ACCESS_OK=" + ",".join(checks))


class FullSeoAuditError(RuntimeError):
    def __init__(self, issues: list[str], summary: dict[str, int]) -> None:
        super().__init__(f"full SEO audit found {len(issues)} issue(s)")
        self.issues = issues
        self.summary = summary


def fetch_many(
    urls: list[str],
    fetch: Callable[[str], tuple[int, str, object, bytes]] | None = None,
) -> tuple[dict[str, tuple[int, str, object, bytes]], list[str]]:
    responses: dict[str, tuple[int, str, object, bytes]] = {}
    issues: list[str] = []
    if fetch is not None:
        for url in urls:
            try:
                responses[url] = fetch(url)
            except Exception as exc:  # test and caller supplied fetchers may use any transport
                issues.append(f"{url}:fetch_error:{type(exc).__name__}:{exc}")
        return responses, issues

    with ThreadPoolExecutor(max_workers=8) as executor:
        futures = {executor.submit(http_fetch, url): url for url in urls}
        for future in as_completed(futures):
            url = futures[future]
            try:
                responses[url] = future.result()
            except Exception as exc:
                issues.append(f"{url}:fetch_error:{type(exc).__name__}:{exc}")
    return responses, issues


def verify_full_seo_contract(
    fetch: Callable[[str], tuple[int, str, object, bytes]] | None = None,
    local_sitemap: Path = LOCAL_SITEMAP,
    local_hp_root: Path = LOCAL_HP_ROOT,
) -> dict[str, int]:
    issues: list[str] = []
    cache: dict[str, tuple[int, str, object, bytes]] = {}
    fetcher = fetch or http_fetch

    def cached_fetch(url: str) -> tuple[int, str, object, bytes]:
        if url not in cache:
            cache[url] = fetcher(url)
        return cache[url]

    try:
        verify_entry_contract(cached_fetch, announce=False)
    except Exception as exc:
        issues.append(f"entry_contract:{exc}")
    try:
        verify_internal_path_access_contract(cached_fetch, announce=False)
    except Exception as exc:
        issues.append(f"internal_path_contract:{exc}")

    if not local_sitemap.is_file():
        issues.append(f"local_sitemap:missing:{local_sitemap}")
        local_urls: list[str] = []
    else:
        local_urls, local_issues = parse_sitemap_urls(
            local_sitemap.read_bytes(), "local_sitemap"
        )
        issues.extend(local_issues)

    try:
        live_sitemap_response = cached_fetch(PUBLIC_SITEMAP)
    except Exception as exc:
        live_sitemap_response = (0, PUBLIC_SITEMAP, {}, b"")
        issues.append(f"{PUBLIC_SITEMAP}:fetch_error:{type(exc).__name__}:{exc}")
    live_status, live_final, _live_headers, live_sitemap_body = live_sitemap_response
    if live_status != 200 or canonical_key(live_final) != canonical_key(PUBLIC_SITEMAP):
        issues.append(f"{PUBLIC_SITEMAP}:unexpected_response:{live_status}:{live_final}")
        live_urls: list[str] = []
    else:
        live_urls, live_issues = parse_sitemap_urls(live_sitemap_body, "live_sitemap")
        issues.extend(live_issues)

    local_keys = {canonical_key(url): url for url in local_urls}
    live_keys = {canonical_key(url): url for url in live_urls}
    for key in sorted(local_keys.keys() - live_keys.keys()):
        issues.append(f"live_sitemap:missing_local_url:{local_keys[key]}")
    for key in sorted(live_keys.keys() - local_keys.keys()):
        issues.append(f"live_sitemap:unexpected_url:{live_keys[key]}")

    sitemap_php = {
        Path(urlsplit(url).path).name
        for url in local_urls
        if urlsplit(url).path.endswith(".php")
    }
    local_root_php = {path.name for path in local_hp_root.glob("*.php")}
    classified_non_sitemap_roots = (
        ACTIVE_NON_SITEMAP_ROOTS | INACTIVE_DEVELOPMENT_ROOTS
    )
    unclassified = local_root_php - sitemap_php - classified_non_sitemap_roots
    missing_known = ACTIVE_NON_SITEMAP_ROOTS - local_root_php
    issues.extend(f"local_php:unclassified_non_sitemap:{name}" for name in sorted(unclassified))
    issues.extend(f"local_php:missing_known_entry:{name}" for name in sorted(missing_known))

    try:
        girls_list_response = cached_fetch(PUBLIC_GIRLS_LIST)
    except Exception as exc:
        girls_list_response = (0, PUBLIC_GIRLS_LIST, {}, b"")
        issues.append(f"{PUBLIC_GIRLS_LIST}:fetch_error:{type(exc).__name__}:{exc}")
    girls_status, _girls_final, _girls_headers, girls_body = girls_list_response
    profile_urls = (
        discover_profile_urls(girls_body.decode("utf-8", errors="replace"))
        if girls_status == 200
        else []
    )
    if not profile_urls:
        issues.append(f"{PUBLIC_GIRLS_LIST}:no_profile_urls_discovered")

    public_urls = sorted(set(local_urls) | set(profile_urls))
    remaining_urls = [url for url in public_urls if url not in cache]
    responses, fetch_issues = fetch_many(remaining_urls, fetch)
    cache.update(responses)
    issues.extend(fetch_issues)

    titles: dict[str, list[str]] = {}
    canonicals: dict[str, list[str]] = {}
    checked_public = 0
    for url in public_urls:
        response = cache.get(url)
        if response is None:
            continue
        checked_public += 1
        page_issues, title, canonical = validate_public_seo_page(url, response)
        issues.extend(page_issues)
        if title:
            titles.setdefault(title, []).append(url)
        if canonical:
            canonicals.setdefault(canonical_key(canonical), []).append(url)
    for title, urls in sorted(titles.items()):
        if len(urls) > 1:
            issues.append(f"duplicate_title:{title}:{'|'.join(sorted(urls))}")
    for canonical, urls in sorted(canonicals.items()):
        if len(urls) > 1:
            issues.append(f"duplicate_canonical:{canonical}:{'|'.join(sorted(urls))}")

    special_urls = [
        urljoin(PUBLIC_ROOT, "girls.php"),
        urljoin(PUBLIC_ROOT, "movie_iframe.php"),
    ]
    remaining_special = [url for url in special_urls if url not in cache]
    special_responses, special_fetch_issues = fetch_many(remaining_special, fetch)
    cache.update(special_responses)
    issues.extend(special_fetch_issues)

    girls_url = urljoin(PUBLIC_ROOT, "girls.php")
    girls_response = cache.get(girls_url)
    if girls_response is not None:
        status, _final, headers, _body = girls_response
        location = urljoin(girls_url, header_value(headers, "Location"))
        if status != 301 or canonical_key(location) != canonical_key(PUBLIC_GIRLS_LIST):
            issues.append(f"{girls_url}:invalid_missing_no_redirect:{status}:{location}")

    movie_url = urljoin(PUBLIC_ROOT, "movie_iframe.php")
    movie_response = cache.get(movie_url)
    if movie_response is not None:
        status, _final, headers, _body = movie_response
        robots_header = header_value(headers, "X-Robots-Tag").lower()
        if status != 404:
            issues.append(f"{movie_url}:invalid_empty_request_status:{status}")
        if "noindex" not in robots_header or "nofollow" not in robots_header:
            issues.append(f"{movie_url}:missing_x_robots_noindex_nofollow")

    if checked_public != len(public_urls):
        issues.append(f"public_pages:incomplete:{checked_public}/{len(public_urls)}")
    summary = {
        "sitemap_pages": len(local_urls),
        "profile_pages": len(profile_urls),
        "public_pages": len(public_urls),
        "active_non_sitemap_routes": len(special_urls),
    }
    if issues:
        raise FullSeoAuditError(sorted(set(issues)), summary)
    return summary


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--sha")
    parser.add_argument("--url")
    parser.add_argument("--expect-text", action="append", default=[])
    parser.add_argument("--expect-visible-text", action="append", default=[])
    parser.add_argument("--timeout", type=int, default=300)
    parser.add_argument("--entry-only", action="store_true")
    parser.add_argument("--access-control-only", action="store_true")
    parser.add_argument("--full-seo", action="store_true")
    parser.add_argument("--self-test", action="store_true")
    args = parser.parse_args()
    if args.self_test:
        if (
            args.sha
            or args.url
            or args.expect_text
            or args.expect_visible_text
            or args.entry_only
            or args.access_control_only
            or args.full_seo
        ):
            parser.error("--self-test cannot be combined with network-check options")
        return self_test()
    if args.entry_only:
        if (
            args.sha
            or args.url
            or args.expect_text
            or args.expect_visible_text
            or args.access_control_only
            or args.full_seo
        ):
            parser.error(
                "--entry-only cannot be combined with other network-check options"
            )
        verify_entry_contract()
        return 0
    if args.access_control_only:
        if args.sha or args.url or args.expect_text or args.expect_visible_text or args.full_seo:
            parser.error(
                "--access-control-only cannot be combined with --sha, --url, --expect-text, or --expect-visible-text"
            )
        verify_internal_path_access_contract()
        return 0
    if args.full_seo:
        if args.sha or args.url or args.expect_text or args.expect_visible_text:
            parser.error(
                "--full-seo cannot be combined with --sha, --url, --expect-text, or --expect-visible-text"
            )
        try:
            result = verify_full_seo_contract()
        except FullSeoAuditError as exc:
            for issue in exc.issues:
                print(f"SEO_ISSUE={issue}")
            print(
                f"FULL_SEO_HTTP=ISSUE count={len(exc.issues)} "
                f"public_pages={exc.summary['public_pages']} "
                f"sitemap_pages={exc.summary['sitemap_pages']} "
                f"profile_pages={exc.summary['profile_pages']} "
                f"active_non_sitemap_routes={exc.summary['active_non_sitemap_routes']}"
            )
            return 1
        print(
            "FULL_SEO_HTTP=OK "
            f"public_pages={result['public_pages']} "
            f"sitemap_pages={result['sitemap_pages']} "
            f"profile_pages={result['profile_pages']} "
            f"active_non_sitemap_routes={result['active_non_sitemap_routes']}"
        )
        return 0
    if not args.sha:
        parser.error("--sha is required unless an independent verification mode is used")
    if len(args.sha) != 40 or any(character not in "0123456789abcdef" for character in args.sha):
        parser.error("--sha must be a lowercase 40-character commit SHA")
    if args.timeout < 30 or args.timeout > 600:
        parser.error("--timeout must be between 30 and 600 seconds")
    wait_for_run(args.sha, args.timeout)
    if args.url:
        verify_url(args.url, args.expect_text, args.expect_visible_text)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
