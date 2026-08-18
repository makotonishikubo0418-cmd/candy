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
from collections.abc import Callable
from urllib.error import HTTPError
from urllib.parse import quote
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


class NoRedirect(HTTPRedirectHandler):
    def redirect_request(self, request, file_pointer, code, message, headers, new_url):
        return None


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
    print("ENTRY_CONTRACT_OK=" + ",".join(checks))


def verify_internal_path_access_contract(
    fetch: Callable[[str], tuple[int, str, object, bytes]] | None = None,
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
    print("INTERNAL_PATH_ACCESS_OK=" + ",".join(checks))


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--sha")
    parser.add_argument("--url")
    parser.add_argument("--expect-text", action="append", default=[])
    parser.add_argument("--expect-visible-text", action="append", default=[])
    parser.add_argument("--timeout", type=int, default=300)
    parser.add_argument("--entry-only", action="store_true")
    parser.add_argument("--access-control-only", action="store_true")
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
        ):
            parser.error(
                "--entry-only cannot be combined with other network-check options"
            )
        verify_entry_contract()
        return 0
    if args.access_control_only:
        if args.sha or args.url or args.expect_text or args.expect_visible_text:
            parser.error(
                "--access-control-only cannot be combined with --sha, --url, --expect-text, or --expect-visible-text"
            )
        verify_internal_path_access_contract()
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
