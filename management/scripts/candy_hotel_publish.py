#!/usr/bin/env python3
"""Publish one CANDY hotel page with the same gates as the area pipeline."""

from __future__ import annotations

import argparse
import contextlib
import hashlib
import io
import json
import os
import re
import shutil
import sys
import tempfile
from pathlib import Path
from urllib.parse import urljoin

import candy_area_publish as shared
import candy_hotel_page
import candy_hotel_target_gate
import candy_page_common as path_config


PublishError = shared.PublishError
GITHUB_BASE = shared.GITHUB_BASE
ACTIVE_STATE: dict[str, str] = {}
VALID_PHASES = {"PREFLIGHT", "BUILT", "PAGE_COMMITTED", "PAGE_PUSHED", "ACTIONS_SUCCESS", "PRODUCTION_VERIFIED", "COMPLETED"}
MAX_SEQUENTIAL_PUBLISH_COUNT = 20
DEFAULT_BATCH_COUNT = 1


def root() -> Path:
    return path_config.REPO_ROOT


def relative(paths: list[Path]) -> list[str]:
    return [path.relative_to(root()).as_posix() for path in paths]


def state_path(slug: str) -> Path:
    git_directory = Path(shared.git_value("rev-parse", "--git-dir"))
    if not git_directory.is_absolute():
        git_directory = root() / git_directory
    return git_directory / f"candy-hotel-publish-{slug}.json"


def lock_path() -> Path:
    return state_path("lock").with_name("candy-hotel-publish.lock")


@contextlib.contextmanager
def publish_lock():
    path = lock_path()
    path.parent.mkdir(parents=True, exist_ok=True)
    handle = path.open("a+b")
    handle.seek(0, os.SEEK_END)
    if handle.tell() == 0:
        handle.write(b"0")
        handle.flush()
    handle.seek(0)
    locked = False
    try:
        try:
            if os.name == "nt":
                import msvcrt

                msvcrt.locking(handle.fileno(), msvcrt.LK_NBLCK, 1)
            else:
                import fcntl

                fcntl.flock(handle.fileno(), fcntl.LOCK_EX | fcntl.LOCK_NB)
        except (OSError, BlockingIOError) as exc:
            raise PublishError("another hotel publish process is active") from exc
        locked = True
        yield
    finally:
        if locked:
            handle.seek(0)
            if os.name == "nt":
                import msvcrt

                msvcrt.locking(handle.fileno(), msvcrt.LK_UNLCK, 1)
            else:
                import fcntl

                fcntl.flock(handle.fileno(), fcntl.LOCK_UN)
        handle.close()


def file_snapshot(paths: list[Path]) -> str:
    values: dict[str, str] = {}
    for path in paths:
        resolved = path.resolve()
        if not resolved.is_file():
            raise PublishError(f"snapshot target is not a file: {path}")
        values[resolved.relative_to(root().resolve()).as_posix()] = hashlib.sha256(resolved.read_bytes()).hexdigest()
    return json.dumps(values, ensure_ascii=True, sort_keys=True, separators=(",", ":"))


def assert_snapshot(state: dict[str, str], key: str, paths: list[Path]) -> None:
    expected = state.get(key)
    if not expected:
        raise PublishError(f"resume state is missing {key}")
    actual = file_snapshot(paths)
    if actual != expected:
        raise PublishError(f"{key} changed after preflight; restart with a clean target")


def save_state(state: dict[str, str], phase: str, **values: str) -> None:
    if phase not in VALID_PHASES:
        raise PublishError(f"invalid publish phase: {phase}")
    state.update(values)
    state["phase"] = phase
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(state)
    path = state_path(state["slug"])
    candy_hotel_page.common.atomic_write(path, json.dumps(state, ensure_ascii=False, indent=2) + "\n")
    print(f"PHASE={phase}", flush=True)


def load_state(slug: str) -> dict[str, str]:
    path = state_path(slug)
    if not path.is_file():
        raise PublishError(f"resume state not found: {path}")
    state = json.loads(path.read_text(encoding="utf-8"))
    required = {"slug", "hotel", "input", "before", "phase", "dependency_snapshot"}
    if not isinstance(state, dict) or not required.issubset(state):
        raise PublishError("resume state is incomplete")
    if state.get("phase") not in VALID_PHASES:
        raise PublishError(f"resume state phase is invalid: {state.get('phase')}")
    if any(not isinstance(key, str) or not isinstance(value, str) for key, value in state.items()):
        raise PublishError("resume state must contain string values only")
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(state)
    return state


def input_paths() -> list[Path]:
    return sorted(path_config.TEXT_HOTEL_DIR.glob("*.txt"))


def validate_batch_count(count: int) -> None:
    if count < 1 or count > MAX_SEQUENTIAL_PUBLISH_COUNT:
        raise PublishError(f"count must be between 1 and {MAX_SEQUENTIAL_PUBLISH_COUNT}")


def select_ready_inputs(
    results: list[candy_hotel_target_gate.Result],
    count: int,
    *,
    verbose: bool,
) -> list[candy_hotel_target_gate.Result]:
    validate_batch_count(count)
    print("CANDIDATE_COUNTS_JSON=" + json.dumps(candy_hotel_target_gate.counts(results), ensure_ascii=False))
    print(
        "BLOCKER_COUNTS_JSON="
        + json.dumps(candy_hotel_target_gate.blocker_counts(results), ensure_ascii=False)
    )
    selected = [result for result in results if result.category == candy_hotel_target_gate.READY][
        :count
    ]
    for result in selected:
        print(f"CANDIDATE_SELECTED={result.path.name}|{result.slug}")
    if verbose:
        for result in results:
            if result.category in (candy_hotel_target_gate.READY, candy_hotel_target_gate.ADMIN_DOC):
                continue
            reason = " / ".join(result.reasons)
            print(f"CANDIDATE_SKIP={result.path.name}|{result.category}|{reason}")
    return selected


def next_ready_input() -> Path:
    selected = select_ready_inputs(candy_hotel_target_gate.scan_inputs(), 1, verbose=False)
    if selected:
        return selected[0].path
    raise PublishError("no eligible new hotel page target; run codex\\scripts\\candy-hotel.cmd audit-inputs")


def registry_target_checks(
    hotel_source: str,
    top_source: str,
    *,
    slug: str,
    hotel_name: str,
) -> dict[str, bool]:
    target_href = f"kagoshima-deliveryhealth-hotel-{slug}.php"
    hotel_entries = candy_hotel_page.hotel_registry_links(hotel_source, top_page=False)
    top_entries = candy_hotel_page.hotel_registry_links(top_source, top_page=True)
    hotel_matches = [name for href, name in hotel_entries if href == target_href]
    top_matches = [name for href, name in top_entries if href == target_href]
    return {
        "hotel_registry_target": hotel_matches == [hotel_name],
        "top_registry_target": top_matches == [hotel_name],
        "hotel_top_alignment": not candy_hotel_page.hotel_registry_alignment_errors(
            hotel_source,
            top_source,
        ),
    }


def paths_for(data: candy_hotel_page.HotelData) -> list[Path]:
    hp = path_config.HP_ROOT
    return [
        hp / f"kagoshima-deliveryhealth-hotel-{data.slug}.php",
        hp / "source" / f"kagoshima-deliveryhealth-hotel-{data.slug}.html",
        hp / "includefile" / f"dataset_kagoshima-deliveryhealth-hotel-{data.slug}.php",
        hp / "includefile" / "dataset_base.php",
        hp / "source" / "hotel.html",
        hp / "source" / "index.html",
        hp / "sitemap.xml",
    ] + path_config.site_state_output_paths()


def dependency_paths(input_path: Path, data: candy_hotel_page.HotelData) -> list[Path]:
    hp = path_config.HP_ROOT
    paths = {
        input_path,
        hp / data.image1.removeprefix("./").split("?", 1)[0],
        hp / data.image2.removeprefix("./").split("?", 1)[0],
        hp / "source" / "template_shop.html",
        hp / "source" / "template_kagoshima-deliveryhealth-hotel.html",
        path_config.SCRIPTS_DIR / "candy_area_page.py",
        path_config.SCRIPTS_DIR / "candy_area_publish.py",
        path_config.SCRIPTS_DIR / "candy_hotel_page.py",
        path_config.SCRIPTS_DIR / "candy_hotel_publish.py",
        path_config.SCRIPTS_DIR / "candy_site_state.py",
        root() / ".github" / "scripts" / "candy_ftp_deploy.py",
        root() / ".github" / "scripts" / "candy_release_check.py",
        root() / ".github" / "workflows" / "candy-production-deploy.yml",
    }
    templates = candy_hotel_page.common.load_shop_templates(hp / "source" / "template_shop.html")
    resolved = candy_hotel_page.resolve_shops(data, hp, templates)
    paths.update(hp / "source" / item.reference for item in resolved if item.reference)
    return sorted(paths, key=lambda path: path.as_posix())


def assert_preflight(data: candy_hotel_page.HotelData, allowed: list[Path], *, check_remote: bool) -> str:
    if not shutil.which("git"):
        raise PublishError("Git executable is unavailable")
    for key in ("user.name", "user.email"):
        try:
            value = shared.git_value("config", "--get", key)
        except PublishError as exc:
            raise PublishError(f"Git identity is missing: {key}") from exc
        if not value.strip():
            raise PublishError(f"Git identity is missing: {key}")
    if shared.git_value("branch", "--show-current") != "main":
        raise PublishError("current branch is not main")
    remote_url = shared.git_value("remote", "get-url", "origin").strip().rstrip("/")
    normalized = re.sub(r"^git@github\.com:", "https://github.com/", remote_url)
    normalized = re.sub(r"^ssh://git@github\.com/", "https://github.com/", normalized).removesuffix(".git")
    if normalized != GITHUB_BASE:
        raise PublishError(f"unexpected origin: {remote_url}")
    if shared.git_value("diff", "--cached", "--name-only"):
        raise PublishError("staged changes already exist")
    target_status = shared.git_value("status", "--porcelain=v1", "--", *relative(allowed))
    if target_status:
        raise PublishError("target/shared files already have changes:\n" + target_status)
    for path in allowed[:3]:
        if path.exists():
            raise PublishError(f"new page file already exists: {path.relative_to(root())}")
    head = shared.git_value("rev-parse", "HEAD")
    if check_remote:
        remote_head = shared.fetch_remote_head()
        if remote_head != head:
            raise PublishError(f"local/remote main mismatch: local={head} remote={remote_head}")
        shared.git("push", "--dry-run", "--porcelain", "origin", "HEAD:refs/heads/main")
        print("PUSH_AUTH_DRY_RUN=OK")
    print(f"PREFLIGHT_OK hotel={data.hotel_name} slug={data.slug} head={head}")
    return head


def expected_title_tag(title: str) -> str:
    return f"<title>{candy_hotel_page.common.htext(title)}</title>"


def verify_production(data: candy_hotel_page.HotelData, commit: str) -> None:
    shared.verify_entry_contract()
    page_url = shared.cache_bust(data.canonical, commit)
    status, final_url, _headers, page_bytes = shared.http_fetch(page_url)
    body = page_bytes.decode("utf-8", errors="replace")
    hp = path_config.HP_ROOT
    h1_match = re.search(r'(?s)<h1\b[^>]*id="page_title_h1"[^>]*>(.*?)</h1>', body)
    h1_text = candy_hotel_page.common.strip_tags(h1_match.group(1)) if h1_match else ""
    json_values: list[dict[str, object]] = []
    json_valid = True
    for block in re.findall(r'(?s)<script type="application/ld\+json">\s*(.*?)\s*</script>', body):
        try:
            value = json.loads(block)
        except json.JSONDecodeError:
            json_valid = False
            continue
        if not isinstance(value, dict):
            json_valid = False
            continue
        json_values.append(value)
    faq_json = next((value for value in json_values if value.get("@type") == "FAQPage"), None)
    item_json = next((value for value in json_values if value.get("@type") == "ItemList"), None)
    actual_item_names: list[object] = []
    if item_json and isinstance(item_json.get("itemListElement"), list):
        for entity in item_json["itemListElement"]:
            item = entity.get("item", {}) if isinstance(entity, dict) else {}
            actual_item_names.append(item.get("name") if isinstance(item, dict) else None)
    actual_faqs: list[tuple[object, object]] = []
    if faq_json and isinstance(faq_json.get("mainEntity"), list):
        for entity in faq_json["mainEntity"]:
            answer = entity.get("acceptedAnswer", {}) if isinstance(entity, dict) else {}
            actual_faqs.append(
                (
                    entity.get("name") if isinstance(entity, dict) else None,
                    answer.get("text") if isinstance(answer, dict) else None,
                )
            )
    expected_faqs = [(item.title, item.description.replace("\n", " ")) for item in data.faqs]
    checks: dict[str, bool] = {
        "page_direct_http": status == 200 and final_url == page_url,
        "title": expected_title_tag(data.title) in body,
        "canonical": f'<link rel="canonical" href="{data.canonical}">' in body,
        "h1": data.hotel_name in h1_text,
        "shops": body.count('class="campaign-item"') == len(data.shops),
        "related": not candy_hotel_page.related_validation(body, data.canonical),
        "json_ld": json_valid and len(json_values) == (3 if data.faqs else 2),
        "item_list": bool(
            item_json
            and item_json.get("numberOfItems") == (len(data.spots) if data.spots else len(data.shops))
            and actual_item_names
            == ([item.title for item in data.spots] if data.spots else [item.name for item in data.shops])
        ),
        "faq": actual_faqs == expected_faqs if data.faqs else faq_json is None,
    }
    for image in (data.image1, data.image2):
        image_url = urljoin(data.canonical, image.removeprefix("./"))
        requested = shared.cache_bust(image_url, commit)
        image_status, image_final, image_headers, image_bytes = shared.http_fetch(requested)
        local_image = hp / image.removeprefix("./").split("?", 1)[0]
        checks[f"image:{Path(image.split('?', 1)[0]).name}"] = (
            image_status == 200
            and image_final == requested
            and str(image_headers.get("Content-Type", "")).lower().startswith("image/")
            and bool(image_bytes)
            and hashlib.sha256(image_bytes).hexdigest() == hashlib.sha256(local_image.read_bytes()).hexdigest()
        )
    hotel_url = shared.cache_bust("https://www.55810.com/hotel.php", commit)
    hotel_status, hotel_final, _hotel_headers, hotel_bytes = shared.http_fetch(hotel_url)
    top_source_url = shared.cache_bust("https://www.55810.com/source/", commit)
    top_status, top_final, _top_headers, top_bytes = shared.http_fetch(top_source_url)
    sitemap_url = shared.cache_bust("https://www.55810.com/sitemap.xml", commit)
    sitemap_status, sitemap_final, _sitemap_headers, sitemap_bytes = shared.http_fetch(sitemap_url)
    hotel_body = hotel_bytes.decode("utf-8", errors="replace")
    top_body = top_bytes.decode("utf-8", errors="replace")
    sitemap_body = sitemap_bytes.decode("utf-8", errors="replace")
    registry_checks = registry_target_checks(
        hotel_body,
        top_body,
        slug=data.slug,
        hotel_name=data.hotel_name,
    )
    checks["hotel_registry_target"] = (
        hotel_status == 200 and hotel_final == hotel_url and registry_checks["hotel_registry_target"]
    )
    checks["top_registry_target"] = (
        top_status == 200 and top_final == top_source_url and registry_checks["top_registry_target"]
    )
    checks["hotel_top_alignment"] = registry_checks["hotel_top_alignment"]
    checks["sitemap"] = (
        sitemap_status == 200
        and sitemap_final == sitemap_url
        and f"<loc>{data.canonical}</loc>" in sitemap_body
    )
    if not all(checks.values()):
        failed = ", ".join(name for name, passed in checks.items() if not passed)
        raise PublishError(f"production verification failed: {failed}")
    print("PRODUCTION_CHECK_OK=" + ",".join(checks))


def publish(
    input_path: Path,
    *,
    dry_run: bool,
    resume_state: dict[str, str] | None = None,
    batch_item: bool = False,
) -> int:
    if input_path.is_symlink():
        raise PublishError(f"input file is a symlink: {input_path}")
    input_path = input_path.resolve()
    try:
        input_path.relative_to(path_config.TEXT_HOTEL_DIR.resolve())
    except ValueError as exc:
        raise PublishError("input must be under Text_hotel_data") from exc
    data = candy_hotel_page.parse_hotel_text(input_path)
    allowed = paths_for(data)
    path_arguments = relative(allowed)
    page_paths = relative(allowed[:3])
    expected = {**{path: "A" for path in page_paths}, **{path: "M" for path in path_arguments[3:]}}
    required = set(path_arguments)
    page_tool = path_config.SCRIPTS_DIR / "candy_hotel_page.py"
    dependencies = dependency_paths(input_path, data)
    relative_input = input_path.relative_to(root()).as_posix()

    if dry_run:
        assert_preflight(data, allowed, check_remote=False)
        shared.assert_dependencies_clean(dependencies)
        shared.run([sys.executable, str(page_tool), "build", "--input", relative_input, "--dry-run"])
        result_key = "BATCH_ITEM_RESULT" if batch_item else "RESULT"
        print(f"{result_key}=DRY_RUN_OK hotel={data.hotel_name} slug={data.slug}")
        return 0

    if resume_state is None:
        before = assert_preflight(data, allowed, check_remote=True)
        shared.assert_dependencies_clean(dependencies)
        state = {
            "slug": data.slug,
            "hotel": data.hotel_name,
            "input": relative_input,
            "before": before,
            "remote_state": before,
            "dependency_snapshot": file_snapshot(dependencies),
        }
        save_state(state, "PREFLIGHT")
    else:
        state = resume_state
        if state.get("slug") != data.slug or state.get("input") != relative_input:
            raise PublishError("resume state does not match input data")
        assert_snapshot(state, "dependency_snapshot", dependencies)
        if shared.git_value("branch", "--show-current") != "main":
            raise PublishError("current branch is not main")
        normalized_origin = shared.git_value("remote", "get-url", "origin").strip().rstrip("/").removesuffix(".git")
        normalized_origin = re.sub(r"^git@github\.com:", "https://github.com/", normalized_origin)
        normalized_origin = re.sub(r"^ssh://git@github\.com/", "https://github.com/", normalized_origin)
        if normalized_origin != GITHUB_BASE:
            raise PublishError(f"unexpected origin during resume: {normalized_origin}")
        before = state["before"]

    phase = state["phase"]
    if phase == "PREFLIGHT":
        if shared.git_value("rev-parse", "HEAD") != before:
            raise PublishError("HEAD changed after preflight")
        if resume_state is not None and shared.fetch_remote_head() != before:
            raise PublishError("origin/main changed after preflight")
        print("STEP=build", flush=True)
        command = [sys.executable, str(page_tool), "build", "--input", relative_input]
        if resume_state is not None:
            command.append("--force")
        shared.run(command)
        shared.run([sys.executable, str(page_tool), "check", "--input", relative_input])
        site_state_tool = path_config.SCRIPTS_DIR / "candy_site_state.py"
        shared.run([sys.executable, str(site_state_tool), "preview-sitemap-lastmod"])
        shared.run([sys.executable, str(site_state_tool), "sync-sitemap-lastmod"])
        shared.run([sys.executable, str(site_state_tool), "write"])
        shared.run([sys.executable, str(site_state_tool), "check", "--target", data.slug])
        save_state(state, "BUILT", output_snapshot=file_snapshot(allowed))
        phase = "BUILT"

    if phase == "BUILT":
        assert_snapshot(state, "dependency_snapshot", dependencies)
        assert_snapshot(state, "output_snapshot", allowed)
        current_head = shared.git_value("rev-parse", "HEAD")
        if current_head == before:
            if shared.fetch_remote_head() != before:
                raise PublishError("origin/main changed before page commit")
            shared.git("add", "--", *path_arguments)
            shared.assert_staged_exact(expected, required, "hotel staged changes")
            shared.git("commit", "-m", f"feat: add {data.slug} hotel page", stream=True)
            page_commit = shared.git_value("rev-parse", "HEAD")
            shared.assert_commit_exact(page_commit, expected, required, "hotel commit")
            if shared.git_value("diff", "--cached", "--name-only"):
                raise PublishError("staged changes remain after page commit")
        else:
            parent = shared.git_value("rev-parse", f"{current_head}^")
            subject = shared.git_value("show", "-s", "--format=%s", current_head)
            if parent != before or subject != f"feat: add {data.slug} hotel page":
                raise PublishError("HEAD changed to an unrelated commit before state save")
            shared.assert_commit_exact(current_head, expected, required, "recovered hotel commit")
            if shared.git_value("status", "--porcelain=v1", "--", *path_arguments):
                raise PublishError("target files changed after recovered hotel commit")
            page_commit = current_head
            print(f"RECOVERED_PAGE_COMMIT={page_commit}")
        save_state(state, "PAGE_COMMITTED", page_commit=page_commit, output_snapshot=file_snapshot(allowed))
        phase = "PAGE_COMMITTED"

    page_commit = state["page_commit"]
    if phase == "PAGE_COMMITTED":
        assert_snapshot(state, "dependency_snapshot", dependencies)
        assert_snapshot(state, "output_snapshot", allowed)
        if shared.git_value("rev-parse", "HEAD") != page_commit:
            raise PublishError("HEAD does not match page commit")
        deploy_script = root() / ".github" / "scripts" / "candy_ftp_deploy.py"
        shared.run([sys.executable, str(deploy_script), "--before", before, "--after", page_commit, "--dry-run"])
        remote = shared.fetch_remote_head()
        if remote == before:
            save_state(state, "PAGE_COMMITTED", remote_state="UNKNOWN_AFTER_PAGE_PUSH_ATTEMPT")
            shared.git("push", "origin", "main", stream=True)
            remote = shared.fetch_remote_head()
        if remote != page_commit:
            raise PublishError(f"origin/main is neither expected hotel commit nor base: {remote}")
        save_state(state, "PAGE_PUSHED", remote_state=page_commit)
        phase = "PAGE_PUSHED"

    if phase == "PAGE_PUSHED":
        assert_snapshot(state, "dependency_snapshot", dependencies)
        assert_snapshot(state, "output_snapshot", allowed)
        print("STEP=actions", flush=True)
        release_script = root() / ".github" / "scripts" / "candy_release_check.py"
        release_output = shared.run(
            [
                sys.executable,
                str(release_script),
                "--sha",
                page_commit,
                "--url",
                data.canonical,
                "--expect-visible-text",
                data.hotel_name,
            ],
            stream=True,
        )
        release = shared.release_values(release_output)
        if release.production_url != data.canonical:
            raise PublishError(f"release checker production URL mismatch: {release.production_url}")
        save_state(state, "ACTIONS_SUCCESS", actions_url=release.actions_url, production_url=release.production_url)
        phase = "ACTIONS_SUCCESS"

    if phase == "ACTIONS_SUCCESS":
        assert_snapshot(state, "dependency_snapshot", dependencies)
        assert_snapshot(state, "output_snapshot", allowed)
        print("STEP=production_http", flush=True)
        if state.get("production_url") != data.canonical:
            raise PublishError("saved production URL does not match canonical")
        if not shared.ACTIONS_PATTERN.fullmatch(state.get("actions_url", "")):
            raise PublishError("saved Actions URL is invalid")
        verify_production(data, page_commit)
        save_state(state, "PRODUCTION_VERIFIED")
        phase = "PRODUCTION_VERIFIED"

    if phase == "PRODUCTION_VERIFIED":
        assert_snapshot(state, "output_snapshot", allowed)
        final_status = shared.git_value("status", "--porcelain=v1", "--", *path_arguments)
        if final_status:
            raise PublishError("target files are not clean after publication:\n" + final_status)
        save_state(state, "COMPLETED", remote_state=page_commit)
        phase = "COMPLETED"

    if phase != "COMPLETED":
        raise PublishError(f"unsupported resume phase: {phase}")
    print(("BATCH_ITEM_RESULT" if batch_item else "RESULT") + "=PUBLISHED")
    print(f"HOTEL={data.hotel_name}")
    print(f"SLUG={data.slug}")
    print(f"PRODUCTION_URL={data.canonical}")
    print(f"COMMIT_URL={GITHUB_BASE}/commit/{page_commit}")
    print(f"ACTIONS_URL={state['actions_url']}")
    return 0


def emit_batch_item(
    index: int,
    result: candy_hotel_target_gate.Result,
    *,
    dry_run: bool,
) -> None:
    production_url = ACTIVE_STATE.get("production_url", "NOT_EXECUTED")
    page_commit = ACTIVE_STATE.get("page_commit")
    commit_url = f"{GITHUB_BASE}/commit/{page_commit}" if page_commit else "NOT_EXECUTED"
    actions_url = ACTIVE_STATE.get("actions_url", "NOT_EXECUTED")
    if dry_run:
        production_url = commit_url = actions_url = "NOT_EXECUTED"
    print(f"BATCH_ITEM_INDEX={index}")
    print(f"HOTEL={result.hotel_name}")
    print(f"SLUG={result.slug}")
    print(f"PRODUCTION_URL={production_url}")
    print(f"COMMIT_URL={commit_url}")
    print(f"ACTIONS_URL={actions_url}")
    print("DESKTOP_MOBILE_RENDERING=NOT_EXECUTED")


def publish_next_batch(count: int, *, dry_run: bool, verbose_candidates: bool) -> int:
    validate_batch_count(count)
    results = candy_hotel_target_gate.scan_inputs()
    selected = select_ready_inputs(results, count, verbose=verbose_candidates)
    print(f"BATCH_REQUESTED={count}")
    print(f"BATCH_SELECTED={len(selected)}")
    if len(selected) < count:
        print("BATCH_COMPLETED=0")
        print("BATCH_FAILED_TARGET=NONE")
        print(f"BATCH_UNEXECUTED={count}")
        print("BATCH_RESULT=STOP")
        raise PublishError(
            f"only {len(selected)} eligible hotel targets are available; {count} were requested"
        )

    completed = 0
    for index, result in enumerate(selected, start=1):
        ACTIVE_STATE.clear()
        try:
            return_code = publish(result.path, dry_run=dry_run, batch_item=True)
            if return_code != 0:
                raise PublishError(f"hotel publisher returned {return_code}")
        except Exception:
            print(f"BATCH_COMPLETED={completed}")
            print(f"BATCH_FAILED_TARGET={result.slug or result.path.name}")
            print(f"BATCH_UNEXECUTED={count - completed - 1}")
            print("BATCH_RESULT=STOP")
            raise
        completed += 1
        emit_batch_item(index, result, dry_run=dry_run)

    print(f"BATCH_COMPLETED={completed}")
    print("BATCH_FAILED_TARGET=NONE")
    print("BATCH_UNEXECUTED=0")
    print("BATCH_RESULT=COMPLETED")
    return 0


def recovery_details(exc: Exception, phase: str, slug: str) -> tuple[str, str, str]:
    message = str(exc).lower()
    deterministic_production_failure = (
        "production verification failed" in message
        or "production url mismatch" in message
        or "saved production url does not match" in message
    )
    transient_communication_failure = any(
        marker in message
        for marker in (
            "timed out",
            "timeout",
            "temporary failure",
            "connection reset",
            "remote end closed",
            "name resolution",
        )
    )
    if deterministic_production_failure:
        return (
            "CAUSE_MUST_BE_RESOLVED",
            "NONE",
            "The production verifier or actual production state must be corrected before retry.",
        )
    if phase in {"PAGE_PUSHED", "ACTIONS_SUCCESS"} and transient_communication_failure and slug != "UNKNOWN":
        return (
            "RESUME_ALLOWED",
            f"codex\\scripts\\candy-hotel.cmd resume --slug {slug}",
            "A transient communication failure may be retried only while saved snapshots remain unchanged.",
        )
    if phase in {"NOT_STARTED", "PREFLIGHT"}:
        return "RESTART_REQUIRED", "NONE", "Correct the reported cause and restart from a clean target."
    return "MANUAL_REVIEW", "NONE", "Safe automatic continuation cannot be proven for this saved state."


def self_test() -> int:
    actions_url = f"{GITHUB_BASE}/actions/runs/12345"
    canonical = "https://www.55810.com/kagoshima-deliveryhealth-hotel-selftest.php"
    values = shared.release_values(f"ACTIONS_URL={actions_url}\nPRODUCTION_URL={canonical}\n")
    assert values.actions_url == actions_url and values.production_url == canonical
    assert expected_title_tag("HOTEL&RESIDENCE南洲館") == "<title>HOTEL&amp;RESIDENCE南洲館</title>"

    def registry_source(entries: list[tuple[str, str]], *, top_page: bool, extra: str = "") -> str:
        links = "".join(f'<a href="./{href}">{name}</a>' for href, name in entries)
        if top_page:
            return f"<!-- 対応ホテル情報 START -->{links}<!-- 対応ホテル情報 END -->{extra}"
        return links + extra

    target_href = "kagoshima-deliveryhealth-hotel-relax.php"
    amp_hotel = registry_source([(target_href, "Relax&amp;Sleep")], top_page=False)
    amp_top = registry_source([(target_href, "Relax&amp;Sleep")], top_page=True)
    assert all(registry_target_checks(amp_hotel, amp_top, slug="relax", hotel_name="Relax&Sleep").values())
    wrong_with_json = registry_source(
        [(target_href, "Wrong Hotel")],
        top_page=False,
        extra='<script type="application/ld+json">{"name":"Relax&Sleep"}</script>',
    )
    assert not registry_target_checks(
        wrong_with_json,
        amp_top,
        slug="relax",
        hotel_name="Relax&Sleep",
    )["hotel_registry_target"]
    duplicate = registry_source(
        [(target_href, "Relax&amp;Sleep"), (target_href, "Relax&amp;Sleep")],
        top_page=False,
    )
    assert not registry_target_checks(
        duplicate,
        amp_top,
        slug="relax",
        hotel_name="Relax&Sleep",
    )["hotel_registry_target"]
    wrong_top = registry_source([(target_href, "Wrong Hotel")], top_page=True)
    wrong_top_checks = registry_target_checks(
        amp_hotel,
        wrong_top,
        slug="relax",
        hotel_name="Relax&Sleep",
    )
    assert not wrong_top_checks["top_registry_target"]
    assert not wrong_top_checks["hotel_top_alignment"]
    different_names = registry_target_checks(
        registry_source([(target_href, "Hotel A")], top_page=False),
        registry_source([(target_href, "Hotel B")], top_page=True),
        slug="relax",
        hotel_name="Hotel A",
    )
    assert different_names["hotel_registry_target"]
    assert not different_names["top_registry_target"]
    assert not different_names["hotel_top_alignment"]
    jp_href = "kagoshima-deliveryhealth-hotel-kagoshima.php"
    jp_hotel = registry_source([(jp_href, "鹿児島ホテル")], top_page=False)
    jp_top = registry_source([(jp_href, "鹿児島ホテル")], top_page=True)
    assert all(
        registry_target_checks(jp_hotel, jp_top, slug="kagoshima", hotel_name="鹿児島ホテル").values()
    )
    shared.assert_exact_changes(
        "A\tHP/new.php\nM\tHP/shared.php",
        {"HP/new.php": "A", "HP/shared.php": "M"},
        {"HP/new.php", "HP/shared.php"},
        "test",
    )
    state = {
        "slug": "selftest",
        "hotel": "test",
        "input": "Text_hotel_data/test.txt",
        "before": "a" * 40,
        "dependency_snapshot": "{}",
    }
    original_state_path = globals()["state_path"]
    original_lock_path = globals()["lock_path"]
    original_scan_inputs = candy_hotel_target_gate.scan_inputs
    original_publish = globals()["publish"]
    try:
        with tempfile.TemporaryDirectory() as directory:
            globals()["state_path"] = lambda slug: Path(directory) / f"{slug}.json"
            globals()["lock_path"] = lambda: Path(directory) / "publish.lock"
            for phase in ("PREFLIGHT", "BUILT", "PAGE_COMMITTED", "PAGE_PUSHED", "ACTIONS_SUCCESS", "PRODUCTION_VERIFIED", "COMPLETED"):
                save_state(state, phase)
                assert load_state("selftest")["phase"] == phase
            with publish_lock():
                try:
                    with publish_lock():
                        raise AssertionError("nested lock was accepted")
                except PublishError:
                    pass
            blocked = argparse.Namespace(
                path=Path(directory) / "blocked.txt",
                category=candy_hotel_target_gate.INPUT_ERROR,
                reasons=("test blocker",),
                blockers=("test blocker",),
                slug="blocked",
                hotel_name="blocked",
            )
            ready = argparse.Namespace(
                path=Path(directory) / "ready.txt",
                category=candy_hotel_target_gate.READY,
                reasons=(),
                blockers=(),
                slug="ready",
                hotel_name="ready",
            )
            candy_hotel_target_gate.scan_inputs = lambda: [blocked, ready]
            assert next_ready_input() == ready.path
            candy_hotel_target_gate.scan_inputs = lambda: [blocked]
            try:
                next_ready_input()
            except PublishError as exc:
                assert "no eligible new hotel page target" in str(exc)
            else:
                raise AssertionError("candidate selection accepted an all-blocked input set")

            def result(number: int, category: str = candy_hotel_target_gate.READY):
                return argparse.Namespace(
                    path=Path(directory) / f"{number:02d}.txt",
                    category=category,
                    reasons=("test blocker",) if category != candy_hotel_target_gate.READY else (),
                    blockers=("test blocker",) if category != candy_hotel_target_gate.READY else (),
                    slug=f"hotel-{number}",
                    hotel_name=f"ホテル{number}",
                )

            ready_results = [result(number) for number in range(1, 5)]
            assert DEFAULT_BATCH_COUNT == 1
            assert len(select_ready_inputs(ready_results, DEFAULT_BATCH_COUNT, verbose=False)) == 1
            assert [item.slug for item in select_ready_inputs(ready_results, 3, verbose=False)] == [
                "hotel-1",
                "hotel-2",
                "hotel-3",
            ]
            invalid_results = [
                result(number, candy_hotel_target_gate.INPUT_ERROR) for number in range(1, 38)
            ] + [result(number) for number in range(38, 41)]
            normal_output = io.StringIO()
            with contextlib.redirect_stdout(normal_output):
                select_ready_inputs(invalid_results, 1, verbose=False)
            assert "CANDIDATE_SKIP=" not in normal_output.getvalue()
            verbose_output = io.StringIO()
            with contextlib.redirect_stdout(verbose_output):
                select_ready_inputs(invalid_results, 1, verbose=True)
            assert verbose_output.getvalue().count("CANDIDATE_SKIP=") == 37
            for invalid_count in (0, MAX_SEQUENTIAL_PUBLISH_COUNT + 1):
                try:
                    validate_batch_count(invalid_count)
                except PublishError:
                    pass
                else:
                    raise AssertionError(f"invalid batch count was accepted: {invalid_count}")
            with contextlib.redirect_stderr(io.StringIO()):
                try:
                    argument_parser().parse_args(["publish-next", "--count", "abc"])
                except SystemExit as exc:
                    assert exc.code == 2
                else:
                    raise AssertionError("non-integer batch count was accepted")

            calls: list[str] = []

            def successful_publish(
                path: Path,
                *,
                dry_run: bool,
                resume_state=None,
                batch_item: bool = False,
            ) -> int:
                assert not ACTIVE_STATE
                assert batch_item
                slug = path.stem.replace("00", "")
                calls.append(path.name)
                ACTIVE_STATE.update(
                    {
                        "page_commit": str(len(calls)) * 40,
                        "actions_url": f"{GITHUB_BASE}/actions/runs/{len(calls)}",
                        "production_url": f"https://www.55810.com/{path.stem}.php",
                    }
                )
                return 0

            candy_hotel_target_gate.scan_inputs = lambda: ready_results[:3]
            globals()["publish"] = successful_publish
            batch_output = io.StringIO()
            with contextlib.redirect_stdout(batch_output):
                assert publish_next_batch(3, dry_run=False, verbose_candidates=False) == 0
            assert calls == ["01.txt", "02.txt", "03.txt"]
            assert "BATCH_RESULT=COMPLETED" in batch_output.getvalue()

            calls.clear()
            dry_run_output = io.StringIO()
            with contextlib.redirect_stdout(dry_run_output):
                assert publish_next_batch(3, dry_run=True, verbose_candidates=False) == 0
            assert calls == ["01.txt", "02.txt", "03.txt"]
            assert dry_run_output.getvalue().count("DESKTOP_MOBILE_RENDERING=NOT_EXECUTED") == 3

            calls.clear()

            def fail_second(
                path: Path,
                *,
                dry_run: bool,
                resume_state=None,
                batch_item: bool = False,
            ) -> int:
                assert not ACTIVE_STATE
                assert batch_item
                calls.append(path.name)
                if len(calls) == 2:
                    raise PublishError("production verification failed: hotel_registry_target")
                ACTIVE_STATE.update(
                    {
                        "page_commit": "a" * 40,
                        "actions_url": f"{GITHUB_BASE}/actions/runs/1",
                        "production_url": "https://www.55810.com/first.php",
                    }
                )
                return 0

            globals()["publish"] = fail_second
            stopped_output = io.StringIO()
            try:
                with contextlib.redirect_stdout(stopped_output):
                    publish_next_batch(3, dry_run=False, verbose_candidates=False)
            except PublishError:
                pass
            else:
                raise AssertionError("batch continued after the second selected target failed")
            assert calls == ["01.txt", "02.txt"]
            assert "BATCH_ITEM_INDEX=1" in stopped_output.getvalue()
            assert "BATCH_COMPLETED=1" in stopped_output.getvalue()
            assert "BATCH_UNEXECUTED=1" in stopped_output.getvalue()

            publish_called = False

            def forbidden_publish(
                path: Path,
                *,
                dry_run: bool,
                resume_state=None,
                batch_item: bool = False,
            ) -> int:
                nonlocal publish_called
                publish_called = True
                raise AssertionError("publish was called despite an insufficient ready count")

            globals()["publish"] = forbidden_publish
            candy_hotel_target_gate.scan_inputs = lambda: ready_results[:2]
            try:
                with contextlib.redirect_stdout(io.StringIO()):
                    publish_next_batch(3, dry_run=False, verbose_candidates=False)
            except PublishError:
                pass
            else:
                raise AssertionError("insufficient batch count was accepted")
            assert not publish_called

            mode, command, reason = recovery_details(
                PublishError("production verification failed: hotel_registry_target"),
                "ACTIONS_SUCCESS",
                "hotel-1",
            )
            assert mode == "CAUSE_MUST_BE_RESOLVED" and command == "NONE" and reason
    finally:
        globals()["state_path"] = original_state_path
        globals()["lock_path"] = original_lock_path
        candy_hotel_target_gate.scan_inputs = original_scan_inputs
        globals()["publish"] = original_publish
        ACTIVE_STATE.clear()
    print("PUBLISH_SELF_TEST=passed")
    return 0


def argument_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Create and publish CANDY hotel pages")
    commands = parser.add_subparsers(dest="command", required=True)
    publish_next = commands.add_parser("publish-next")
    publish_next.add_argument("--dry-run", action="store_true")
    publish_next.add_argument("--count", type=int, default=DEFAULT_BATCH_COUNT)
    publish_next.add_argument("--verbose-candidates", action="store_true")
    publish_input = commands.add_parser("publish")
    publish_input.add_argument("--input", required=True)
    publish_input.add_argument("--dry-run", action="store_true")
    resume = commands.add_parser("resume")
    resume.add_argument("--slug", required=True)
    commands.add_parser("publish-self-test")
    return parser


def main() -> int:
    shared.configure_output()
    parser = argument_parser()
    args = parser.parse_args()
    try:
        if args.command == "publish-self-test":
            return self_test()
        if args.command == "resume":
            with publish_lock():
                state = load_state(args.slug)
                return publish(root() / state["input"], dry_run=False, resume_state=state)
        if args.command == "publish-next":
            with publish_lock():
                return publish_next_batch(
                    args.count,
                    dry_run=args.dry_run,
                    verbose_candidates=args.verbose_candidates,
                )
        else:
            input_path = Path(args.input)
            if not input_path.is_absolute():
                input_path = root() / input_path
        if args.dry_run:
            return publish(input_path, dry_run=args.dry_run)
        with publish_lock():
            return publish(input_path, dry_run=False)
    except (PublishError, candy_hotel_page.HotelToolError, OSError, json.JSONDecodeError) as exc:
        phase = ACTIVE_STATE.get("phase", "NOT_STARTED")
        page_commit = ACTIVE_STATE.get("page_commit", "NONE")
        remote_state = ACTIVE_STATE.get("remote_state", "UNVERIFIED")
        slug = ACTIVE_STATE.get("slug", "UNKNOWN")
        recovery_mode, recovery, recovery_reason = recovery_details(exc, phase, slug)
        print(
            f"RESULT=STOP\nREASON={exc}\nPHASE={phase}\nPAGE_COMMIT={page_commit}"
            f"\nREMOTE_STATE={remote_state}\nRECOVERY_MODE={recovery_mode}"
            f"\nRECOVERY_COMMAND={recovery}\nRECOVERY_REASON={recovery_reason}",
            file=sys.stderr,
        )
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
