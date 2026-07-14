#!/usr/bin/env python3
"""One-command CANDY area page creation and production publication."""

from __future__ import annotations

import argparse
import hashlib
import json
import os
import re
import subprocess
import sys
from dataclasses import dataclass
from pathlib import Path
from urllib.error import HTTPError
from urllib.parse import urlencode, urljoin, urlsplit, urlunsplit
from urllib.request import HTTPRedirectHandler, Request, build_opener, urlopen

import candy_area_page


REPOSITORY = "makotonishikubo0418-cmd/candy"
ORIGIN_URL_SUFFIX = f"{REPOSITORY}.git"
GITHUB_BASE = f"https://github.com/{REPOSITORY}"
USER_AGENT = "candy-area-publish"
EXPECTED_REDIRECT = "https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/"
BATCH_START = "<!-- CANDY_AREA_BATCH_HISTORY_START -->"
BATCH_END = "<!-- CANDY_AREA_BATCH_HISTORY_END -->"
ACTIONS_PATTERN = re.compile(
    rf"^https://github\.com/{re.escape(REPOSITORY)}/actions/runs/(?P<run_id>\d+)$"
)
ACTIVE_STATE: dict[str, str] = {}


class PublishError(RuntimeError):
    pass


class NoRedirect(HTTPRedirectHandler):
    def redirect_request(self, request, file_pointer, code, message, headers, new_url):
        return None


@dataclass
class ReleaseResult:
    actions_url: str
    production_url: str


def root() -> Path:
    return Path(__file__).resolve().parents[3]


def configure_output() -> None:
    for stream in (sys.stdout, sys.stderr):
        if hasattr(stream, "reconfigure"):
            stream.reconfigure(encoding="utf-8", errors="backslashreplace")


def run(command: list[str], *, check: bool = True, stream: bool = False) -> str:
    printable = " ".join(command)
    print(f"COMMAND={printable}", flush=True)
    environment = os.environ.copy()
    environment["PYTHONUTF8"] = "1"
    if stream:
        process = subprocess.Popen(
            command,
            cwd=root(),
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            text=True,
            encoding="utf-8",
            errors="replace",
            env=environment,
        )
        lines: list[str] = []
        assert process.stdout is not None
        for line in process.stdout:
            print(line, end="", flush=True)
            lines.append(line)
        return_code = process.wait()
        output = "".join(lines)
    else:
        completed = subprocess.run(
            command,
            cwd=root(),
            capture_output=True,
            text=True,
            encoding="utf-8",
            errors="replace",
            env=environment,
        )
        return_code = completed.returncode
        output = completed.stdout + completed.stderr
        if output:
            print(output, end="" if output.endswith("\n") else "\n", flush=True)
    if check and return_code != 0:
        raise PublishError(f"command failed ({return_code}): {printable}")
    return output


def git(*arguments: str, check: bool = True, stream: bool = False) -> str:
    return run(["git", "-c", "core.quotepath=false", *arguments], check=check, stream=stream)


def git_value(*arguments: str) -> str:
    completed = subprocess.run(
        ["git", "-c", "core.quotepath=false", *arguments],
        cwd=root(),
        capture_output=True,
        text=True,
        encoding="utf-8",
        errors="replace",
    )
    if completed.returncode != 0:
        raise PublishError(completed.stderr.strip() or f"git {' '.join(arguments)} failed")
    return completed.stdout.strip()


def queue_path() -> Path:
    return root() / "HP" / "codex" / "docs" / "CANDY_AREA_105_PAGE_QUEUE.md"


def notes_path() -> Path:
    return root() / "ページ作成用.md"


def find_input(region: str, slug: str) -> Path:
    candidates = list((root() / "HP" / "Text_area_data").rglob(f"{region}_テンプレート.txt"))
    matches: list[Path] = []
    for path in candidates:
        try:
            if candy_area_page.parse_area_text(path).slug == slug:
                matches.append(path)
        except candy_area_page.AreaToolError:
            continue
    if len(matches) != 1:
        raise PublishError(f"input file count is not 1 for {region}/{slug}: {len(matches)}")
    return matches[0]


def next_ready_input(queue_text: str) -> Path:
    for line in queue_text.splitlines():
        parts = line.split("|")
        if len(parts) < 7 or not parts[1].strip().isdigit():
            continue
        if parts[4].strip() != "READY_CANDIDATE":
            continue
        region = parts[2].strip()
        slug = parts[3].strip().strip("`")
        return find_input(region, slug)
    raise PublishError("READY_CANDIDATE is not present in the area queue")


def paths_for(data: candy_area_page.AreaData) -> list[Path]:
    hp = root() / "HP"
    return [
        hp / f"kagoshima-deliveryhealth-area-{data.slug}.php",
        hp / "source" / f"kagoshima-deliveryhealth-area-{data.slug}.html",
        hp / "includefile" / f"dataset_kagoshima-deliveryhealth-area-{data.slug}.php",
        hp / "includefile" / "dataset_base.php",
        hp / "source" / "area.html",
        hp / "sitemap.xml",
        queue_path(),
        notes_path(),
    ]


def relative(paths: list[Path]) -> list[str]:
    return [path.relative_to(root()).as_posix() for path in paths]


def state_path(slug: str) -> Path:
    git_directory = Path(git_value("rev-parse", "--git-dir"))
    if not git_directory.is_absolute():
        git_directory = root() / git_directory
    return git_directory / f"candy-area-publish-{slug}.json"


def save_state(state: dict[str, str], phase: str, **values: str) -> None:
    updated = {**state, **values, "phase": phase}
    state.clear()
    state.update(updated)
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(updated)
    path = state_path(state["slug"])
    temporary = path.with_suffix(path.suffix + ".tmp")
    temporary.write_text(json.dumps(updated, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
    os.replace(temporary, path)
    print(f"PHASE={phase}", flush=True)


def load_state(slug: str) -> dict[str, str]:
    path = state_path(slug)
    if not path.is_file():
        raise PublishError(f"resume state does not exist: {path.name}")
    state = json.loads(path.read_text(encoding="utf-8"))
    if state.get("slug") != slug or not state.get("phase") or not state.get("input"):
        raise PublishError("resume state is invalid")
    normalized = {str(key): str(value) for key, value in state.items()}
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(normalized)
    return normalized


def dependency_paths(
    input_path: Path,
    data: candy_area_page.AreaData,
) -> list[Path]:
    hp = root() / "HP"
    dependencies = {
        input_path,
        hp / data.image1.removeprefix("./"),
        hp / data.image2.removeprefix("./"),
        hp / "source" / "template_shop.html",
        hp / "source" / "template_kagoshima-deliveryhealth-area.html",
        root() / "HP" / "codex" / "scripts" / "candy_area_page.py",
        root() / "HP" / "codex" / "scripts" / "candy_area_publish.py",
        root() / ".github" / "scripts" / "candy_ftp_deploy.py",
        root() / ".github" / "scripts" / "candy_release_check.py",
        root() / ".github" / "workflows" / "candy-production-deploy.yml",
    }
    area_sources = list((hp / "source").glob("kagoshima-deliveryhealth-area-*.html"))
    if not data.shops or any(
        not request.time_text or not request.fee_text or candy_area_page.suspicious_fee(request.fee_text)
        for request in data.shops
    ):
        dependencies.update(area_sources)
    templates = candy_area_page.load_shop_templates(hp / "source" / "template_shop.html")
    for item in candy_area_page.resolve_shops(data, hp, templates):
        if item.reference:
            dependencies.add(hp / "source" / item.reference)
    return sorted(dependencies, key=lambda path: path.as_posix())


def assert_dependencies_clean(paths: list[Path]) -> None:
    repository_root = root().resolve()
    rels: list[str] = []
    for path in paths:
        if path.is_symlink():
            raise PublishError(f"dependency is a symlink: {path}")
        resolved = path.resolve()
        try:
            resolved.relative_to(repository_root)
        except ValueError as exc:
            raise PublishError(f"dependency escaped repository: {path}") from exc
        if not resolved.is_file():
            raise PublishError(f"dependency is not a regular file: {path}")
        rel = resolved.relative_to(repository_root).as_posix()
        if subprocess.run(
            ["git", "cat-file", "-e", f"HEAD:{rel}"],
            cwd=root(),
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL,
            check=False,
        ).returncode:
            raise PublishError(f"dependency is not tracked in HEAD: {rel}")
        rels.append(rel)
    status = git_value("status", "--porcelain=v1", "--", *rels)
    if status:
        raise PublishError("dependency files are not clean:\n" + status)
    for rel in rels:
        print(f"DEPENDENCY={rel}")


def parse_name_status(output: str) -> dict[str, str]:
    result: dict[str, str] = {}
    for line in output.splitlines():
        fields = line.split("\t")
        if len(fields) != 2 or fields[0] not in {"A", "M"}:
            raise PublishError(f"unsupported staged/committed status: {line}")
        if fields[1] in result:
            raise PublishError(f"duplicate staged/committed path: {fields[1]}")
        result[fields[1]] = fields[0]
    return result


def assert_exact_changes(output: str, allowed: dict[str, str], required: set[str], label: str) -> None:
    changes = parse_name_status(output)
    unexpected = sorted(set(changes) - set(allowed))
    wrong = sorted(path for path, status in changes.items() if allowed.get(path) != status)
    missing = sorted(required - set(changes))
    if unexpected or wrong or missing:
        raise PublishError(
            f"{label} allowlist mismatch: unexpected={unexpected} wrong_status={wrong} missing={missing}"
        )


def assert_staged_exact(allowed: dict[str, str], required: set[str], label: str) -> None:
    assert_exact_changes(git_value("diff", "--cached", "--name-status"), allowed, required, label)
    git("diff", "--cached", "--check")


def assert_commit_exact(commit: str, allowed: dict[str, str], required: set[str], label: str) -> None:
    output = git_value("diff-tree", "--no-commit-id", "--name-status", "-r", f"{commit}^", commit)
    assert_exact_changes(output, allowed, required, label)


def assert_preflight(data: candy_area_page.AreaData, allowed: list[Path], *, check_remote: bool) -> str:
    if git_value("branch", "--show-current") != "main":
        raise PublishError("current branch is not main")
    remote_url = git_value("remote", "get-url", "origin")
    normalized = remote_url.strip().rstrip("/")
    normalized = re.sub(r"^git@github\.com:", "https://github.com/", normalized)
    normalized = re.sub(r"^ssh://git@github\.com/", "https://github.com/", normalized)
    normalized = normalized.removesuffix(".git")
    if normalized != GITHUB_BASE:
        raise PublishError(f"unexpected origin: {remote_url}")
    if git_value("diff", "--cached", "--name-only"):
        raise PublishError("staged changes already exist")
    target_status = git_value("status", "--porcelain=v1", "--", *relative(allowed))
    if target_status:
        raise PublishError("target/shared files already have changes:\n" + target_status)
    for path in allowed[:3]:
        if path.exists():
            raise PublishError(f"new page file already exists: {path.relative_to(root())}")
    head = git_value("rev-parse", "HEAD")
    if check_remote:
        remote_head = fetch_remote_head()
        if remote_head != head:
            raise PublishError(f"local/remote main mismatch: local={head} remote={remote_head}")
    print(f"PREFLIGHT_OK region={data.region} slug={data.slug} head={head}")
    return head


def fetch_remote_head() -> str:
    fetch = subprocess.run(
        ["git", "fetch", "origin", "main"],
        cwd=root(),
        capture_output=True,
        text=True,
        encoding="utf-8",
        errors="replace",
    )
    if fetch.returncode == 0:
        return git_value("rev-parse", "origin/main")
    print("FETCH_FALLBACK=git ls-remote", flush=True)
    remote = git_value("ls-remote", "origin", "refs/heads/main")
    match = re.fullmatch(r"([0-9a-f]{40})\s+refs/heads/main", remote)
    if not match:
        raise PublishError("origin/main could not be verified")
    return match.group(1)


def assert_remote_equals(expected: str) -> None:
    actual = fetch_remote_head()
    if actual != expected:
        raise PublishError(f"origin/main moved: expected={expected} actual={actual}")
    print(f"REMOTE_BASE_OK={actual}")


def release_values(output: str) -> ReleaseResult:
    actions = re.findall(r"^ACTIONS_URL=(https://\S+)$", output, re.M)
    production = re.findall(r"^PRODUCTION_URL=(https://\S+)$", output, re.M)
    if len(actions) != 1 or len(production) != 1:
        raise PublishError("release checker did not return one Actions URL and production URL")
    if not ACTIONS_PATTERN.fullmatch(actions[0]):
        raise PublishError(f"unexpected Actions URL: {actions[0]}")
    return ReleaseResult(actions[0], production[0])


def cache_bust(url: str, commit: str) -> str:
    parts = urlsplit(url)
    query = parts.query + ("&" if parts.query else "") + urlencode({"candy_verify": commit})
    return urlunsplit((parts.scheme, parts.netloc, parts.path, query, parts.fragment))


def http_fetch(url: str, *, no_redirect: bool = True) -> tuple[int, str, object, bytes]:
    request = Request(url, headers={"User-Agent": USER_AGENT, "Cache-Control": "no-cache"})
    opener = build_opener(NoRedirect) if no_redirect else build_opener()
    try:
        with opener.open(request, timeout=30) as response:
            return response.status, response.geturl(), response.headers, response.read()
    except HTTPError as exc:
        return exc.code, exc.geturl(), exc.headers, exc.read()


def verify_production(data: candy_area_page.AreaData, commit: str) -> None:
    page_url = cache_bust(data.canonical, commit)
    status, final_url, _headers, page_bytes = http_fetch(page_url)
    body = page_bytes.decode("utf-8", errors="replace")
    hp = root() / "HP"
    templates = candy_area_page.load_shop_templates(hp / "source" / "template_shop.html")
    shop_count = len(candy_area_page.resolve_shops(data, hp, templates))
    checks = {
        "page_direct_http": status == 200 and final_url == page_url,
        "title": f"<title>{data.title}</title>" in body,
        "canonical": f'href="{data.canonical}"' in body,
        "h1": data.page_title in body,
        "shops": body.count('class="campaign-item"') == shop_count,
    }
    for image in (data.image1, data.image2):
        image_url = urljoin(data.canonical, image.removeprefix("./"))
        requested = cache_bust(image_url, commit)
        image_status, image_final, image_headers, image_bytes = http_fetch(requested)
        local_image = hp / image.removeprefix("./")
        checks[f"image:{Path(image).name}"] = (
            image_status == 200
            and image_final == requested
            and str(image_headers.get("Content-Type", "")).lower().startswith("image/")
            and bool(image_bytes)
            and hashlib.sha256(image_bytes).hexdigest() == hashlib.sha256(local_image.read_bytes()).hexdigest()
        )
    area_url = cache_bust("https://www.55810.com/area.php", commit)
    area_status, area_final, _area_headers, area_bytes = http_fetch(area_url)
    sitemap_url = cache_bust("https://www.55810.com/sitemap.xml", commit)
    sitemap_status, sitemap_final, _sitemap_headers, sitemap_bytes = http_fetch(sitemap_url)
    area_body = area_bytes.decode("utf-8", errors="replace")
    sitemap_body = sitemap_bytes.decode("utf-8", errors="replace")
    checks["area"] = (
        area_status == 200
        and area_final == area_url
        and f"kagoshima-deliveryhealth-area-{data.slug}.php" in area_body
    )
    checks["sitemap"] = (
        sitemap_status == 200 and sitemap_final == sitemap_url and data.canonical in sitemap_body
    )
    for label, redirect_url in (
        ("root_redirect", "https://www.55810.com/"),
        ("index_redirect", "https://www.55810.com/index.php"),
    ):
        redirect_status, _redirect_final, redirect_headers, _redirect_body = http_fetch(redirect_url)
        checks[label] = redirect_status == 301 and redirect_headers.get("Location") == EXPECTED_REDIRECT
    if not all(checks.values()):
        failed = ", ".join(name for name, passed in checks.items() if not passed)
        raise PublishError(f"production verification failed: {failed}")
    print("PRODUCTION_CHECK_OK=" + ",".join(checks))


def update_queue_publication(source: str, data: candy_area_page.AreaData, commit: str, run_id: str) -> str:
    if source.count(BATCH_START) != 1 or source.count(BATCH_END) != 1:
        raise PublishError("batch history markers must each occur exactly once")
    batch_start = source.index(BATCH_START)
    batch_end = source.index(BATCH_END)
    if batch_start >= batch_end:
        raise PublishError("batch history markers are reversed")
    lines = source.splitlines()
    matched = 0
    for index, line in enumerate(lines):
        parts = line.split("|")
        if len(parts) < 7 or not parts[1].strip().isdigit():
            continue
        if parts[3].strip() != f"`{data.slug}`":
            continue
        parts[4] = " PUBLISHED "
        parts[5] = (
            f" Codex / {candy_area_page.date.today().isoformat()} / Commit `{commit[:7]}` / "
            f"Actions `{run_id}` / 本番HTTP確認済み "
        )
        lines[index] = "|".join(parts)
        matched += 1
    if matched != 1:
        raise PublishError(f"queue publication row count is not 1: {matched}")
    batch_pattern = re.compile(rf"^\| TEST-(\d+) \| (\d+) / `{re.escape(data.slug)}` \|.*$", re.M)
    current = "\n".join(lines)
    batch_matches = list(batch_pattern.finditer(current))
    if len(batch_matches) > 1:
        raise PublishError("batch publication row count is greater than 1")
    queue_number = next(
        line.split("|")[1].strip()
        for line in lines
        if len(line.split("|")) >= 7 and line.split("|")[3].strip() == f"`{data.slug}`"
    )
    if batch_matches:
        batch_number = int(batch_matches[0].group(1))
    else:
        numbers = [int(value) for value in re.findall(r"^\| TEST-(\d+) \|", current, re.M)]
        batch_number = max(numbers, default=0) + 1
    batch_row = (
        f"| TEST-{batch_number:02d} | {queue_number} / `{data.slug}` | Codex | PUBLISHED | "
        f"{candy_area_page.date.today().isoformat()} | `{commit[:7]}` | Actions `{run_id}`、"
        "本番HTTP 200・title・canonical・h1・店舗・画像SHA・一覧・sitemap・redirect確認済み |"
    )
    updated = "\n".join(lines).rstrip() + "\n"
    if batch_matches:
        updated, count = batch_pattern.subn(batch_row, updated, count=1)
        if count != 1:
            raise PublishError("existing batch row could not be updated")
    else:
        insertion = updated.index(BATCH_END)
        updated = updated[:insertion] + batch_row + "\n" + updated[insertion:]
    return updated


def update_notes_publication(
    source: str,
    data: candy_area_page.AreaData,
    commit: str,
    actions_url: str,
) -> str:
    marker = f"<!-- CANDY_AREA_TOOL_RECORD:{data.slug} -->"
    marker_at = source.find(marker)
    if marker_at < 0 or source.find(marker, marker_at + 1) >= 0:
        raise PublishError("page note marker count is not 1")
    heading = f"### 自動生成記録 {candy_area_page.date.today().isoformat()} {data.region} / {data.slug}"
    if source.count(heading) != 1:
        raise PublishError("exact page note heading count is not 1")
    section_start = source.index(heading)
    if not section_start <= marker_at:
        raise PublishError("page note marker is outside the exact section")
    next_section = source.find("\n### ", marker_at)
    section_end = len(source) if next_section < 0 else next_section
    section = source[section_start:section_end].rstrip()
    if section.count(marker) != 1:
        raise PublishError("page note marker is not unique inside the exact section")
    prefixes = ("- Commit `", "- Actions Run `", "- 本番URL `", "- ブラウザ表示とローカルPHP構文")
    kept = [line for line in section.splitlines() if not line.startswith(prefixes)]
    run_id = actions_url.rstrip("/").rsplit("/", 1)[-1]
    kept.extend(
        [
            f"- Commit `{commit[:7]}` でGitHub mainへ反映。",
            f"- Actions Run `{run_id}` で本番へ反映。",
            f"- 本番URL `{data.canonical}` はHTTP 200、title、canonical、h1、店舗{len(data.shops)}件、画像2枚、area一覧、sitemapを確認済み。",
            "- ブラウザ表示とローカルPHP構文は未確認。",
        ]
    )
    return source[:section_start] + "\n".join(kept) + "\n" + source[section_end:].lstrip("\n")


def atomic_write(path: Path, source: str) -> None:
    candy_area_page.atomic_write(path, source)


def publish(
    input_path: Path,
    *,
    dry_run: bool,
    resume_state: dict[str, str] | None = None,
) -> int:
    if input_path.is_symlink():
        raise PublishError(f"input file is a symlink: {input_path}")
    input_path = input_path.resolve()
    try:
        input_path.relative_to((root() / "HP" / "Text_area_data").resolve())
    except ValueError as exc:
        raise PublishError("input must be under HP/Text_area_data") from exc
    data = candy_area_page.parse_area_text(input_path)
    allowed = paths_for(data)
    path_arguments = relative(allowed)
    page_paths = relative(allowed[:3])
    page_allowed = {
        **{path: "A" for path in page_paths},
        **{path: "M" for path in path_arguments[3:]},
    }
    page_required = set(page_paths)
    docs = [queue_path().relative_to(root()).as_posix(), notes_path().relative_to(root()).as_posix()]
    docs_allowed = {path: "M" for path in docs}
    page_tool = root() / "HP" / "codex" / "scripts" / "candy_area_page.py"
    relative_input = input_path.relative_to(root()).as_posix()

    if dry_run:
        assert_preflight(data, allowed, check_remote=False)
        assert_dependencies_clean(dependency_paths(input_path, data))
        run([sys.executable, str(page_tool), "build", "--input", relative_input, "--dry-run"])
        print(f"RESULT=DRY_RUN_OK region={data.region} slug={data.slug}")
        return 0

    if resume_state is None:
        before = assert_preflight(data, allowed, check_remote=True)
        assert_dependencies_clean(dependency_paths(input_path, data))
        state = {
            "slug": data.slug,
            "region": data.region,
            "input": relative_input,
            "before": before,
            "remote_state": before,
        }
        save_state(state, "PREFLIGHT")
    else:
        state = resume_state
        if state.get("slug") != data.slug or state.get("input") != relative_input:
            raise PublishError("resume state does not match input data")
        if git_value("branch", "--show-current") != "main":
            raise PublishError("current branch is not main")
        before = state["before"]

    phase = state["phase"]
    if phase == "PREFLIGHT":
        print("STEP=build", flush=True)
        command = [sys.executable, str(page_tool), "build", "--input", relative_input]
        if resume_state is not None:
            command.append("--force")
        run(command)
        run([sys.executable, str(page_tool), "check", "--input", relative_input])
        save_state(state, "BUILT")
        phase = "BUILT"

    if phase == "BUILT":
        if git_value("rev-parse", "HEAD") != before:
            raise PublishError("HEAD changed before page commit")
        git("add", "--", *path_arguments)
        assert_staged_exact(page_allowed, page_required, "page staged changes")
        git("commit", "-m", f"feat: add {data.slug} area page", stream=True)
        page_commit = git_value("rev-parse", "HEAD")
        assert_commit_exact(page_commit, page_allowed, page_required, "page commit")
        if git_value("diff", "--cached", "--name-only"):
            raise PublishError("staged changes remain after page commit")
        save_state(state, "PAGE_COMMITTED", page_commit=page_commit)
        phase = "PAGE_COMMITTED"

    page_commit = state["page_commit"]
    if phase == "PAGE_COMMITTED":
        if git_value("rev-parse", "HEAD") != page_commit:
            raise PublishError("HEAD does not match page commit")
        deploy_script = root() / ".github" / "scripts" / "candy_ftp_deploy.py"
        run([sys.executable, str(deploy_script), "--before", before, "--after", page_commit, "--dry-run"])
        remote = fetch_remote_head()
        if remote == before:
            save_state(state, "PAGE_COMMITTED", remote_state="UNKNOWN_AFTER_PAGE_PUSH_ATTEMPT")
            git("push", "origin", "main", stream=True)
            remote = fetch_remote_head()
        if remote != page_commit:
            raise PublishError(f"origin/main is neither expected page commit nor base: {remote}")
        save_state(state, "PAGE_PUSHED", remote_state=page_commit)
        phase = "PAGE_PUSHED"

    if phase == "PAGE_PUSHED":
        print("STEP=actions", flush=True)
        release_script = root() / ".github" / "scripts" / "candy_release_check.py"
        release_output = run(
            [
                sys.executable,
                str(release_script),
                "--sha",
                page_commit,
                "--url",
                data.canonical,
                "--expect-text",
                data.region,
            ],
            stream=True,
        )
        release = release_values(release_output)
        if release.production_url != data.canonical:
            raise PublishError(f"release checker production URL mismatch: {release.production_url}")
        save_state(
            state,
            "ACTIONS_SUCCESS",
            actions_url=release.actions_url,
            production_url=release.production_url,
        )
        phase = "ACTIONS_SUCCESS"

    if phase == "ACTIONS_SUCCESS":
        print("STEP=production_http", flush=True)
        if state.get("production_url") != data.canonical:
            raise PublishError("saved production URL does not match canonical")
        if not ACTIONS_PATTERN.fullmatch(state.get("actions_url", "")):
            raise PublishError("saved Actions URL is invalid")
        verify_production(data, page_commit)
        save_state(state, "PRODUCTION_VERIFIED")
        phase = "PRODUCTION_VERIFIED"

    if phase == "PRODUCTION_VERIFIED":
        print("STEP=publication_records", flush=True)
        run_id = ACTIONS_PATTERN.fullmatch(state["actions_url"]).group("run_id")  # type: ignore[union-attr]
        queue_source = candy_area_page.read_utf8(queue_path())
        notes_source = candy_area_page.read_utf8(notes_path())
        atomic_write(queue_path(), update_queue_publication(queue_source, data, page_commit, run_id))
        atomic_write(
            notes_path(),
            update_notes_publication(notes_source, data, page_commit, state["actions_url"]),
        )
        git("add", "--", *docs)
        assert_staged_exact(docs_allowed, set(docs), "publication docs staged changes")
        git("commit", "-m", f"docs: record {data.slug} publication", stream=True)
        docs_commit = git_value("rev-parse", "HEAD")
        assert_commit_exact(docs_commit, docs_allowed, set(docs), "publication docs commit")
        if git_value("diff", "--cached", "--name-only"):
            raise PublishError("staged changes remain after docs commit")
        save_state(state, "DOCS_COMMITTED", docs_commit=docs_commit)
        phase = "DOCS_COMMITTED"

    docs_commit = state.get("docs_commit", "")
    if phase == "DOCS_COMMITTED":
        if git_value("rev-parse", "HEAD") != docs_commit:
            raise PublishError("HEAD does not match docs commit")
        remote = fetch_remote_head()
        if remote == page_commit:
            save_state(state, "DOCS_COMMITTED", remote_state="UNKNOWN_AFTER_DOCS_PUSH_ATTEMPT")
            git("push", "origin", "main", stream=True)
            remote = fetch_remote_head()
        if remote != docs_commit:
            raise PublishError(f"final origin/main does not match docs commit: {remote}")
        final_status = git_value("status", "--porcelain=v1", "--", *path_arguments)
        if final_status:
            raise PublishError("target files are not clean after publication:\n" + final_status)
        save_state(state, "COMPLETED", remote_state=docs_commit)
        phase = "COMPLETED"

    if phase != "COMPLETED":
        raise PublishError(f"unsupported resume phase: {phase}")
    print("RESULT=PUBLISHED")
    print(f"REGION={data.region}")
    print(f"SLUG={data.slug}")
    print(f"PRODUCTION_URL={data.canonical}")
    print(f"COMMIT_URL={GITHUB_BASE}/commit/{page_commit}")
    print(f"ACTIONS_URL={state['actions_url']}")
    print(f"DOCS_COMMIT_URL={GITHUB_BASE}/commit/{docs_commit}")
    return 0


def self_test() -> int:
    today = candy_area_page.date.today().isoformat()
    queue = (
        "| No. | 地域名 | slug | 状態 | 記録 |\n"
        "|---:|---|---|---|---|\n"
        "| 4 | 吉野町 | `yoshinocho` | IN_PROGRESS | old |\n"
        f"{BATCH_START}\n"
        f"| TEST-04 | 4 / `oldslug` | Codex | PUBLISHED | {today} | `aaaaaaa` | old |\n"
        f"{BATCH_END}\n"
    )
    data = candy_area_page.AreaData(
        Path("HP/Text_area_data/吉野町_テンプレート.txt"), "吉野町", "yoshinocho", "title", "description",
        "https://www.55810.com/kagoshima-deliveryhealth-area-yoshinocho.php", "image", "image1", "image2",
        "page", "subtitle", "description", "shops", "shop description", [], [],
        candy_area_page.BasicInfo("basic", "map", "src", "1", "1", "date", "description"),
        None, [], None, [],
    )
    updated_queue = update_queue_publication(queue, data, "b" * 40, "12345")
    assert "| 4 | 吉野町 | `yoshinocho` | PUBLISHED |" in updated_queue
    assert "| TEST-05 | 4 / `yoshinocho` |" in updated_queue
    repeated_queue = update_queue_publication(updated_queue, data, "c" * 40, "67890")
    assert repeated_queue.count("4 / `yoshinocho`") == 1
    assert "`ccccccc` | Actions `67890`" in repeated_queue
    notes = (
        f"### 自動生成記録 {today} 吉野 / yoshino\n<!-- CANDY_AREA_TOOL_RECORD:yoshino -->\n- old\n"
        f"### 自動生成記録 {today} 吉野町 / yoshinocho\n<!-- CANDY_AREA_TOOL_RECORD:yoshinocho -->\n- generated\n"
    )
    actions_url = f"{GITHUB_BASE}/actions/runs/12345"
    updated_notes = update_notes_publication(notes, data, "b" * 40, actions_url)
    first, second = updated_notes.split(f"### 自動生成記録 {today} 吉野町 / yoshinocho", 1)
    assert "12345" not in first
    assert "12345" in second and data.canonical in second
    values = release_values(
        f"ACTIONS_URL={actions_url}\n"
        f"PRODUCTION_URL={data.canonical}\n"
    )
    assert values.actions_url.endswith("12345") and values.production_url == data.canonical
    assert_exact_changes("A\tHP/new.php\nM\tHP/shared.php", {"HP/new.php": "A", "HP/shared.php": "M"}, {"HP/new.php"}, "test")
    for unsafe in ("D\tHP/new.php", "R100\tHP/a.php\tHP/b.php", "T\tHP/new.php"):
        try:
            parse_name_status(unsafe)
        except PublishError:
            pass
        else:
            raise AssertionError(f"unsafe status was accepted: {unsafe}")
    state = {
        "slug": "selftest-state",
        "region": "試験",
        "input": "HP/Text_area_data/selftest.txt",
        "before": "a" * 40,
        "remote_state": "a" * 40,
    }
    phases = (
        "BUILT",
        "PAGE_COMMITTED",
        "PAGE_PUSHED",
        "ACTIONS_SUCCESS",
        "PRODUCTION_VERIFIED",
        "DOCS_COMMITTED",
        "COMPLETED",
    )
    try:
        for phase in phases:
            save_state(state, phase)
            assert load_state("selftest-state")["phase"] == phase
    finally:
        state_path("selftest-state").unlink(missing_ok=True)
        ACTIVE_STATE.clear()
    print("PUBLISH_SELF_TEST=passed")
    return 0


def main() -> int:
    configure_output()
    parser = argparse.ArgumentParser(description="Create and publish one CANDY area page in one command")
    subparsers = parser.add_subparsers(dest="command", required=True)
    publish_next = subparsers.add_parser("publish-next")
    publish_next.add_argument("--dry-run", action="store_true")
    publish_input = subparsers.add_parser("publish")
    publish_input.add_argument("--input", required=True)
    publish_input.add_argument("--dry-run", action="store_true")
    resume = subparsers.add_parser("resume")
    resume.add_argument("--slug", required=True)
    subparsers.add_parser("publish-self-test")
    args = parser.parse_args()
    try:
        if args.command == "publish-self-test":
            return self_test()
        if args.command == "resume":
            state = load_state(args.slug)
            input_path = root() / state["input"]
            return publish(input_path, dry_run=False, resume_state=state)
        if args.command == "publish-next":
            input_path = next_ready_input(candy_area_page.read_utf8(queue_path()))
        else:
            input_path = Path(args.input)
            if not input_path.is_absolute():
                input_path = root() / input_path
        return publish(input_path, dry_run=args.dry_run)
    except (PublishError, candy_area_page.AreaToolError, OSError, json.JSONDecodeError) as exc:
        phase = ACTIVE_STATE.get("phase", "NOT_STARTED")
        page_commit = ACTIVE_STATE.get("page_commit", "NONE")
        remote_state = ACTIVE_STATE.get("remote_state", "UNVERIFIED")
        slug = ACTIVE_STATE.get("slug", "UNKNOWN")
        recovery = (
            f"HP\\codex\\scripts\\candy-area.cmd resume --slug {slug}"
            if slug != "UNKNOWN"
            else "Fix the reported preflight error, then rerun the original command"
        )
        print(
            f"RESULT=STOP\nREASON={exc}\nPHASE={phase}\nPAGE_COMMIT={page_commit}"
            f"\nREMOTE_STATE={remote_state}\nRECOVERY_COMMAND={recovery}",
            file=sys.stderr,
        )
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
