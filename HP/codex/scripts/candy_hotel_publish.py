#!/usr/bin/env python3
"""Publish one CANDY hotel page with the same gates as the area pipeline."""

from __future__ import annotations

import argparse
import contextlib
import hashlib
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


PublishError = shared.PublishError
GITHUB_BASE = shared.GITHUB_BASE
ACTIVE_STATE: dict[str, str] = {}
VALID_PHASES = {"PREFLIGHT", "BUILT", "PAGE_COMMITTED", "PAGE_PUSHED", "ACTIONS_SUCCESS", "PRODUCTION_VERIFIED", "COMPLETED"}


def root() -> Path:
    return shared.root()


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
    return sorted((root() / "HP" / "Text_hotel_data").glob("*.txt"))


def next_ready_input() -> Path:
    candidates: list[tuple[Path, str]] = []
    slug_paths: dict[str, list[Path]] = {}
    for path in input_paths():
        if path.name.lower().startswith("cursor"):
            continue
        try:
            data = candy_hotel_page.parse_hotel_text(path)
        except candy_hotel_page.HotelToolError as exc:
            print(f"CANDIDATE_SKIP={path.name}|{exc}")
            continue
        slug_paths.setdefault(data.slug, []).append(path)
        if any(value.exists() for value in candy_hotel_page.bundle_paths(root() / "HP", data.slug)):
            print(f"CANDIDATE_SKIP={path.name}|page files already exist")
            continue
        candidates.append((path, data.slug))
    duplicate_slugs = {slug: paths for slug, paths in slug_paths.items() if len(paths) > 1}
    if duplicate_slugs:
        details = ", ".join(f"{slug}={len(paths)}" for slug, paths in sorted(duplicate_slugs.items()))
        raise PublishError(f"duplicate hotel input slugs: {details}")
    if not candidates:
        raise PublishError("no complete unpublished hotel input")
    if len(candidates) > 1:
        names = ", ".join(path.name for path, _slug in candidates)
        raise PublishError(f"multiple unpublished hotel inputs; use publish --input: {names}")
    selected, slug = candidates[0]
    print(f"CANDIDATE_SELECTED={selected.name}|{slug}")
    return selected


def paths_for(data: candy_hotel_page.HotelData) -> list[Path]:
    hp = root() / "HP"
    return [
        hp / f"kagoshima-deliveryhealth-hotel-{data.slug}.php",
        hp / "source" / f"kagoshima-deliveryhealth-hotel-{data.slug}.html",
        hp / "includefile" / f"dataset_kagoshima-deliveryhealth-hotel-{data.slug}.php",
        hp / "includefile" / "dataset_base.php",
        hp / "source" / "hotel.html",
        hp / "sitemap.xml",
    ]


def dependency_paths(input_path: Path, data: candy_hotel_page.HotelData) -> list[Path]:
    hp = root() / "HP"
    paths = {
        input_path,
        hp / data.image1.removeprefix("./"),
        hp / data.image2.removeprefix("./"),
        hp / "source" / "template_shop.html",
        hp / "source" / "template_kagoshima-deliveryhealth-hotel.html",
        hp / "codex" / "scripts" / "candy_area_page.py",
        hp / "codex" / "scripts" / "candy_area_publish.py",
        hp / "codex" / "scripts" / "candy_hotel_page.py",
        hp / "codex" / "scripts" / "candy_hotel_publish.py",
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


def verify_production(data: candy_hotel_page.HotelData, commit: str) -> None:
    page_url = shared.cache_bust(data.canonical, commit)
    status, final_url, _headers, page_bytes = shared.http_fetch(page_url)
    body = page_bytes.decode("utf-8", errors="replace")
    hp = root() / "HP"
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
        "title": f"<title>{data.title}</title>" in body,
        "canonical": f'<link rel="canonical" href="{data.canonical}">' in body,
        "h1": data.hotel_name in h1_text,
        "shops": body.count('class="campaign-item"') == len(data.shops),
        "related": not candy_hotel_page.related_validation(body),
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
        local_image = hp / image.removeprefix("./")
        checks[f"image:{Path(image).name}"] = (
            image_status == 200
            and image_final == requested
            and str(image_headers.get("Content-Type", "")).lower().startswith("image/")
            and bool(image_bytes)
            and hashlib.sha256(image_bytes).hexdigest() == hashlib.sha256(local_image.read_bytes()).hexdigest()
        )
    hotel_url = shared.cache_bust("https://www.55810.com/hotel.php", commit)
    hotel_status, hotel_final, _hotel_headers, hotel_bytes = shared.http_fetch(hotel_url)
    sitemap_url = shared.cache_bust("https://www.55810.com/sitemap.xml", commit)
    sitemap_status, sitemap_final, _sitemap_headers, sitemap_bytes = shared.http_fetch(sitemap_url)
    hotel_body = hotel_bytes.decode("utf-8", errors="replace")
    sitemap_body = sitemap_bytes.decode("utf-8", errors="replace")
    checks["hotel"] = (
        hotel_status == 200
        and hotel_final == hotel_url
        and f'./kagoshima-deliveryhealth-hotel-{data.slug}.php' in hotel_body
        and data.hotel_name in hotel_body
    )
    checks["sitemap"] = (
        sitemap_status == 200
        and sitemap_final == sitemap_url
        and f"<loc>{data.canonical}</loc>" in sitemap_body
    )
    for label, redirect_url in (
        ("root_redirect", "https://www.55810.com/"),
        ("index_redirect", "https://www.55810.com/index.php"),
    ):
        redirect_status, _redirect_final, redirect_headers, _redirect_body = shared.http_fetch(redirect_url)
        checks[label] = redirect_status == 301 and redirect_headers.get("Location") == shared.EXPECTED_REDIRECT
    if not all(checks.values()):
        failed = ", ".join(name for name, passed in checks.items() if not passed)
        raise PublishError(f"production verification failed: {failed}")
    print("PRODUCTION_CHECK_OK=" + ",".join(checks))


def publish(input_path: Path, *, dry_run: bool, resume_state: dict[str, str] | None = None) -> int:
    if input_path.is_symlink():
        raise PublishError(f"input file is a symlink: {input_path}")
    input_path = input_path.resolve()
    try:
        input_path.relative_to((root() / "HP" / "Text_hotel_data").resolve())
    except ValueError as exc:
        raise PublishError("input must be under HP/Text_hotel_data") from exc
    data = candy_hotel_page.parse_hotel_text(input_path)
    allowed = paths_for(data)
    path_arguments = relative(allowed)
    page_paths = relative(allowed[:3])
    expected = {**{path: "A" for path in page_paths}, **{path: "M" for path in path_arguments[3:]}}
    required = set(path_arguments)
    page_tool = root() / "HP" / "codex" / "scripts" / "candy_hotel_page.py"
    dependencies = dependency_paths(input_path, data)
    relative_input = input_path.relative_to(root()).as_posix()

    if dry_run:
        assert_preflight(data, allowed, check_remote=False)
        shared.assert_dependencies_clean(dependencies)
        shared.run([sys.executable, str(page_tool), "build", "--input", relative_input, "--dry-run"])
        print(f"RESULT=DRY_RUN_OK hotel={data.hotel_name} slug={data.slug}")
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
                "--expect-text",
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
    print("RESULT=PUBLISHED")
    print(f"HOTEL={data.hotel_name}")
    print(f"SLUG={data.slug}")
    print(f"PRODUCTION_URL={data.canonical}")
    print(f"COMMIT_URL={GITHUB_BASE}/commit/{page_commit}")
    print(f"ACTIONS_URL={state['actions_url']}")
    return 0


def self_test() -> int:
    actions_url = f"{GITHUB_BASE}/actions/runs/12345"
    canonical = "https://www.55810.com/kagoshima-deliveryhealth-hotel-selftest.php"
    values = shared.release_values(f"ACTIONS_URL={actions_url}\nPRODUCTION_URL={canonical}\n")
    assert values.actions_url == actions_url and values.production_url == canonical
    shared.assert_exact_changes(
        "A\tHP/new.php\nM\tHP/shared.php",
        {"HP/new.php": "A", "HP/shared.php": "M"},
        {"HP/new.php", "HP/shared.php"},
        "test",
    )
    state = {
        "slug": "selftest",
        "hotel": "test",
        "input": "HP/Text_hotel_data/test.txt",
        "before": "a" * 40,
        "dependency_snapshot": "{}",
    }
    original_state_path = globals()["state_path"]
    original_lock_path = globals()["lock_path"]
    original_input_paths = globals()["input_paths"]
    original_parse_hotel_text = candy_hotel_page.parse_hotel_text
    original_bundle_paths = candy_hotel_page.bundle_paths
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
            first = Path(directory) / "first.txt"
            second = Path(directory) / "second.txt"
            first.write_text("test", encoding="utf-8")
            second.write_text("test", encoding="utf-8")
            globals()["input_paths"] = lambda: [first, second]
            candy_hotel_page.bundle_paths = lambda _hp, slug: (
                Path(directory) / f"{slug}.php",
                Path(directory) / f"{slug}.html",
                Path(directory) / f"{slug}-dataset.php",
            )
            for slugs, expected_message in (
                ({"first.txt": "first", "second.txt": "second"}, "multiple unpublished hotel inputs"),
                ({"first.txt": "same", "second.txt": "same"}, "duplicate hotel input slugs"),
            ):
                candy_hotel_page.parse_hotel_text = lambda candidate, values=slugs: argparse.Namespace(
                    slug=values[candidate.name]
                )
                try:
                    next_ready_input()
                except PublishError as exc:
                    assert expected_message in str(exc)
                else:
                    raise AssertionError(f"candidate selection accepted: {expected_message}")
    finally:
        globals()["state_path"] = original_state_path
        globals()["lock_path"] = original_lock_path
        globals()["input_paths"] = original_input_paths
        candy_hotel_page.parse_hotel_text = original_parse_hotel_text
        candy_hotel_page.bundle_paths = original_bundle_paths
        ACTIVE_STATE.clear()
    print("PUBLISH_SELF_TEST=passed")
    return 0


def main() -> int:
    shared.configure_output()
    parser = argparse.ArgumentParser(description="Create and publish one CANDY hotel page")
    commands = parser.add_subparsers(dest="command", required=True)
    publish_next = commands.add_parser("publish-next")
    publish_next.add_argument("--dry-run", action="store_true")
    publish_input = commands.add_parser("publish")
    publish_input.add_argument("--input", required=True)
    publish_input.add_argument("--dry-run", action="store_true")
    resume = commands.add_parser("resume")
    resume.add_argument("--slug", required=True)
    commands.add_parser("publish-self-test")
    args = parser.parse_args()
    try:
        if args.command == "publish-self-test":
            return self_test()
        with publish_lock():
            if args.command == "resume":
                state = load_state(args.slug)
                return publish(root() / state["input"], dry_run=False, resume_state=state)
            if args.command == "publish-next":
                input_path = next_ready_input()
            else:
                input_path = Path(args.input)
                if not input_path.is_absolute():
                    input_path = root() / input_path
            return publish(input_path, dry_run=args.dry_run)
    except (PublishError, candy_hotel_page.HotelToolError, OSError, json.JSONDecodeError) as exc:
        phase = ACTIVE_STATE.get("phase", "NOT_STARTED")
        page_commit = ACTIVE_STATE.get("page_commit", "NONE")
        remote_state = ACTIVE_STATE.get("remote_state", "UNVERIFIED")
        slug = ACTIVE_STATE.get("slug", "UNKNOWN")
        recovery = (
            f"HP\\codex\\scripts\\candy-hotel.cmd resume --slug {slug}"
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
