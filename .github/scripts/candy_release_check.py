#!/usr/bin/env python3
"""Wait for the automatic CANDY deployment and verify its production URL."""

from __future__ import annotations

import argparse
import json
import shutil
import subprocess
import time
from urllib.parse import quote
from urllib.request import Request, urlopen


REPOSITORY = "makotonishikubo0418-cmd/candy"
WORKFLOW = "candy-production-deploy.yml"
USER_AGENT = "candy-release-check"


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


def verify_url(url: str, expected_text: list[str]) -> None:
    request = Request(url, headers={"User-Agent": USER_AGENT})
    with urlopen(request, timeout=30) as response:
        body = response.read().decode("utf-8", errors="replace")
        status = response.status
        final_url = response.geturl()
    if status != 200:
        raise RuntimeError(f"production URL returned HTTP {status}: {final_url}")
    for marker in expected_text:
        if marker not in body:
            raise RuntimeError(f"production URL is missing expected text: {marker}")
    print(f"PRODUCTION_URL={final_url}")
    print(f"HTTP_STATUS={status}")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--sha", required=True)
    parser.add_argument("--url")
    parser.add_argument("--expect-text", action="append", default=[])
    parser.add_argument("--timeout", type=int, default=300)
    args = parser.parse_args()
    if len(args.sha) != 40 or any(character not in "0123456789abcdef" for character in args.sha):
        parser.error("--sha must be a lowercase 40-character commit SHA")
    if args.timeout < 30 or args.timeout > 600:
        parser.error("--timeout must be between 30 and 600 seconds")
    wait_for_run(args.sha, args.timeout)
    if args.url:
        verify_url(args.url, args.expect_text)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
