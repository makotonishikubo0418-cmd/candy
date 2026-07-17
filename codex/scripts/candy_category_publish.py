#!/usr/bin/env python3
"""Publish one CANDY hotel/blog page with the area release safety gates."""

from __future__ import annotations

import argparse
import hashlib
import importlib
import json
import os
import re
import sys
import tempfile
from pathlib import Path
from urllib.parse import urljoin

import candy_area_publish as release
import candy_page_common as common


GITHUB_BASE = "https://github.com/makotonishikubo0418-cmd/candy"
EXPECTED_ORIGIN = GITHUB_BASE
EXPECTED_REDIRECT = "https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/"
ACTIVE_STATE: dict[str, str] = {}


class CategoryPublishError(RuntimeError):
    pass


def module_for(category: str):
    return importlib.import_module(f"candy_{category}_page")


def parse_data(category: str, input_path: Path):
    module = module_for(category)
    return getattr(module, f"parse_{category}_text")(input_path)


def relative(paths: list[Path]) -> list[str]:
    root = common.repo_root().resolve()
    result: list[str] = []
    for path in paths:
        try:
            result.append(path.resolve().relative_to(root).as_posix())
        except ValueError as exc:
            raise CategoryPublishError(f"path escaped repository: {path}") from exc
    return result


def paths_for(category: str, data) -> list[Path]:
    hp = common.hp_root()
    public_path, source_path, dataset_path = common.bundle_paths(category, data.slug)
    return [
        public_path,
        source_path,
        dataset_path,
        hp / "includefile" / "dataset_base.php",
        hp / "source" / f"{category}.html",
        hp / "source" / "index.html",
        hp / "sitemap.xml",
    ]


def dependency_paths(category: str, data, input_path: Path) -> list[Path]:
    root = common.REPO_ROOT
    hp = common.HP_ROOT
    dependencies = {
        input_path,
        hp / data.image1.removeprefix("./"),
        hp / data.image2.removeprefix("./"),
        hp / "source" / f"template_kagoshima-deliveryhealth-{category}.html",
        common.SCRIPTS_DIR / "candy_page_common.py",
        common.SCRIPTS_DIR / f"candy_{category}_page.py",
        common.SCRIPTS_DIR / "candy_category_publish.py",
        root / ".github" / "scripts" / "candy_ftp_deploy.py",
        root / ".github" / "scripts" / "candy_release_check.py",
        root / ".github" / "workflows" / "candy-production-deploy.yml",
    }
    if category == "hotel":
        dependencies.update(
            {
                hp / "source" / "template_shop.html",
                common.SCRIPTS_DIR / "candy_area_page.py",
            }
        )
    else:
        dependencies.add(hp / "source" / "template_girls.html")
        girls = module_for(category).resolve_girls(data)
        dependencies.update(hp / girl.image.removeprefix("./") for girl in girls)
    return sorted(dependencies, key=lambda path: path.as_posix())


def state_path(category: str, slug: str) -> Path:
    return common.repo_root() / ".codex" / "candy-publish" / f"{category}-{slug}.json"


def save_state(state: dict[str, str], phase: str, **values: str) -> None:
    state.update(values)
    state["phase"] = phase
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(state)
    path = state_path(state["category"], state["slug"])
    path.parent.mkdir(parents=True, exist_ok=True)
    handle, temporary = tempfile.mkstemp(prefix=f".{path.name}.", suffix=".tmp", dir=path.parent)
    try:
        with os.fdopen(handle, "w", encoding="utf-8", newline="\n") as stream:
            json.dump(state, stream, ensure_ascii=False, indent=2, sort_keys=True)
            stream.write("\n")
        os.replace(temporary, path)
    except Exception:
        try:
            os.unlink(temporary)
        except FileNotFoundError:
            pass
        raise


def load_state(category: str, slug: str) -> dict[str, str]:
    path = state_path(category, slug)
    if not path.is_file():
        raise CategoryPublishError(f"resume state not found: {path}")
    value = json.loads(path.read_text(encoding="utf-8"))
    if not isinstance(value, dict) or value.get("category") != category or value.get("slug") != slug:
        raise CategoryPublishError("resume state is invalid")
    result = {str(key): str(item) for key, item in value.items()}
    ACTIVE_STATE.clear()
    ACTIVE_STATE.update(result)
    return result


def normalized_origin(value: str) -> str:
    result = value.strip().rstrip("/")
    result = re.sub(r"^git@github\.com:", "https://github.com/", result)
    result = re.sub(r"^ssh://git@github\.com/", "https://github.com/", result)
    return result.removesuffix(".git")


def assert_preflight(category: str, data, allowed: list[Path], *, check_remote: bool) -> str:
    if release.git_value("branch", "--show-current") != "main":
        raise CategoryPublishError("current branch is not main")
    origin = release.git_value("remote", "get-url", "origin")
    if normalized_origin(origin) != EXPECTED_ORIGIN:
        raise CategoryPublishError(f"unexpected origin: {origin}")
    if release.git_value("diff", "--cached", "--name-only"):
        raise CategoryPublishError("staged changes already exist")
    status = release.git_value("status", "--porcelain=v1", "--", *relative(allowed))
    if status:
        raise CategoryPublishError("target/shared files already have changes:\n" + status)
    for path in allowed[:3]:
        if path.exists():
            raise CategoryPublishError(f"new page file already exists: {path.relative_to(common.repo_root())}")
    head = release.git_value("rev-parse", "HEAD")
    if check_remote:
        remote = release.fetch_remote_head()
        if remote != head:
            raise CategoryPublishError(f"local/remote main mismatch: local={head} remote={remote}")
    print(f"PREFLIGHT_OK category={category} slug={data.slug} head={head}")
    return head


def verify_production(category: str, data, commit: str) -> None:
    page_url = release.cache_bust(data.canonical, commit)
    status, final_url, _headers, body_bytes = release.http_fetch(page_url)
    body = body_bytes.decode("utf-8", errors="replace")
    checks = {
        "page": status == 200 and final_url == page_url,
        "title": f"<title>{data.title}</title>" in body,
        "canonical": f'href="{data.canonical}"' in body,
        "h1": data.page_title in body,
    }
    images = [data.image1, data.image2]
    if category == "blog":
        images.extend(item.image for item in module_for(category).resolve_girls(data))
    for image in images:
        image_url = urljoin(data.canonical, image.removeprefix("./"))
        requested = release.cache_bust(image_url, commit)
        image_status, image_final, image_headers, image_bytes = release.http_fetch(requested)
        local_image = common.hp_root() / image.removeprefix("./")
        checks[f"image:{Path(image).name}"] = (
            image_status == 200
            and image_final == requested
            and str(image_headers.get("Content-Type", "")).lower().startswith("image/")
            and hashlib.sha256(image_bytes).hexdigest() == hashlib.sha256(local_image.read_bytes()).hexdigest()
        )
    list_url = release.cache_bust(f"https://www.55810.com/{category}.php", commit)
    list_status, list_final, _list_headers, list_bytes = release.http_fetch(list_url)
    checks[f"{category}_list"] = list_status == 200 and list_final == list_url and f"-{data.slug}.php" in list_bytes.decode("utf-8", errors="replace")
    sitemap_url = release.cache_bust("https://www.55810.com/sitemap.xml", commit)
    sitemap_status, sitemap_final, _sitemap_headers, sitemap_bytes = release.http_fetch(sitemap_url)
    checks["sitemap"] = sitemap_status == 200 and sitemap_final == sitemap_url and data.canonical in sitemap_bytes.decode("utf-8", errors="replace")
    for label, url in (("root_redirect", "https://www.55810.com/"), ("index_redirect", "https://www.55810.com/index.php")):
        redirect_status, _final, redirect_headers, _body = release.http_fetch(url)
        checks[label] = redirect_status == 301 and redirect_headers.get("Location") == EXPECTED_REDIRECT
    if not all(checks.values()):
        raise CategoryPublishError("production verification failed: " + ", ".join(name for name, passed in checks.items() if not passed))
    print("PRODUCTION_CHECK_OK=" + ",".join(checks))


def publish(category: str, input_path: Path, *, dry_run: bool, resume_state: dict[str, str] | None = None) -> int:
    if input_path.is_symlink():
        raise CategoryPublishError(f"input is a symlink: {input_path}")
    input_path = input_path.resolve()
    input_roots = {
        "hotel": common.TEXT_HOTEL_DIR,
        "blog": common.TEXT_BLOG_DIR,
    }
    expected_parent = input_roots[category].resolve()
    try:
        input_path.relative_to(expected_parent)
    except ValueError as exc:
        raise CategoryPublishError(f"input must be under Text_{category}_data") from exc
    data = parse_data(category, input_path)
    allowed = paths_for(category, data)
    path_args = relative(allowed)
    statuses = {**{path: "A" for path in path_args[:3]}, **{path: "M" for path in path_args[3:]}}
    required = set(path_args)
    page_tool = common.SCRIPTS_DIR / f"candy_{category}_page.py"
    relative_input = input_path.relative_to(common.repo_root()).as_posix()
    if dry_run:
        assert_preflight(category, data, allowed, check_remote=False)
        release.assert_dependencies_clean(dependency_paths(category, data, input_path))
        release.run([sys.executable, str(page_tool), "build", "--input", relative_input, "--dry-run"])
        print(f"RESULT=DRY_RUN_OK category={category} slug={data.slug}")
        return 0
    if resume_state is None:
        before = assert_preflight(category, data, allowed, check_remote=True)
        release.assert_dependencies_clean(dependency_paths(category, data, input_path))
        state = {
            "category": category,
            "slug": data.slug,
            "input": relative_input,
            "before": before,
            "remote_state": before,
        }
        save_state(state, "PREFLIGHT")
    else:
        state = resume_state
        before = state["before"]
        if state.get("input") != relative_input or release.git_value("branch", "--show-current") != "main":
            raise CategoryPublishError("resume state or branch does not match")
    phase = state["phase"]
    if phase == "PREFLIGHT":
        release.run([sys.executable, str(page_tool), "build", "--input", relative_input] + (["--force"] if resume_state else []))
        release.run([sys.executable, str(page_tool), "check", "--input", relative_input])
        save_state(state, "BUILT")
        phase = "BUILT"
    if phase == "BUILT":
        if release.git_value("rev-parse", "HEAD") != before:
            raise CategoryPublishError("HEAD changed before commit")
        release.git("add", "--", *path_args)
        release.assert_staged_exact(statuses, required, f"{category} staged changes")
        release.git("commit", "-m", f"feat: add {data.slug} {category} page", stream=True)
        commit = release.git_value("rev-parse", "HEAD")
        release.assert_commit_exact(commit, statuses, required, f"{category} commit")
        save_state(state, "PAGE_COMMITTED", page_commit=commit)
        phase = "PAGE_COMMITTED"
    commit = state["page_commit"]
    if phase == "PAGE_COMMITTED":
        deploy = common.repo_root() / ".github" / "scripts" / "candy_ftp_deploy.py"
        release.run([sys.executable, str(deploy), "--before", before, "--after", commit, "--dry-run"])
        remote = release.fetch_remote_head()
        if remote == before:
            save_state(state, "PAGE_COMMITTED", remote_state="UNKNOWN_AFTER_PUSH_ATTEMPT")
            release.git("push", "origin", "main", stream=True)
            remote = release.fetch_remote_head()
        if remote != commit:
            raise CategoryPublishError(f"origin/main mismatch after push: {remote}")
        save_state(state, "PAGE_PUSHED", remote_state=commit)
        phase = "PAGE_PUSHED"
    if phase == "PAGE_PUSHED":
        checker = common.repo_root() / ".github" / "scripts" / "candy_release_check.py"
        expected = data.basic.name if category == "hotel" else data.page_title
        output = release.run([sys.executable, str(checker), "--sha", commit, "--url", data.canonical, "--expect-text", expected], stream=True)
        values = release.release_values(output)
        if values.production_url != data.canonical:
            raise CategoryPublishError("release checker production URL mismatch")
        save_state(state, "ACTIONS_SUCCESS", actions_url=values.actions_url, production_url=values.production_url)
        phase = "ACTIONS_SUCCESS"
    if phase == "ACTIONS_SUCCESS":
        verify_production(category, data, commit)
        save_state(state, "PRODUCTION_VERIFIED")
        phase = "PRODUCTION_VERIFIED"
    if phase == "PRODUCTION_VERIFIED":
        status = release.git_value("status", "--porcelain=v1", "--", *path_args)
        if status:
            raise CategoryPublishError("target files are not clean after publication:\n" + status)
        save_state(state, "COMPLETED", remote_state=commit)
        phase = "COMPLETED"
    if phase != "COMPLETED":
        raise CategoryPublishError(f"unsupported resume phase: {phase}")
    print("RESULT=PUBLISHED")
    print(f"CATEGORY={category}\nSLUG={data.slug}\nPRODUCTION_URL={data.canonical}")
    print(f"COMMIT_URL={GITHUB_BASE}/commit/{commit}\nACTIONS_URL={state['actions_url']}")
    return 0


def self_test(category: str) -> int:
    fake_allowed = {"HP/new.php": "A", "HP/shared.php": "M"}
    release.assert_exact_changes("A\tHP/new.php\nM\tHP/shared.php", fake_allowed, set(fake_allowed), "category self-test")
    if normalized_origin("git@github.com:makotonishikubo0418-cmd/candy.git") != EXPECTED_ORIGIN:
        raise AssertionError("origin normalization failed")
    state = {"category": category, "slug": "selftest", "input": f"Text_{category}_data/selftest.txt", "before": "a" * 40}
    original = globals()["state_path"]
    try:
        with tempfile.TemporaryDirectory() as directory:
            globals()["state_path"] = lambda selected, slug: Path(directory) / f"{selected}-{slug}.json"
            save_state(state, "PREFLIGHT")
            if load_state(category, "selftest")["phase"] != "PREFLIGHT":
                raise AssertionError("state round trip failed")
    finally:
        globals()["state_path"] = original
        ACTIVE_STATE.clear()
    print(f"PUBLISH_SELF_TEST=passed category={category}")
    return 0


def main() -> int:
    release.configure_output()
    parser = argparse.ArgumentParser(description="Publish one CANDY hotel/blog page")
    parser.add_argument("category", choices=("hotel", "blog"))
    commands = parser.add_subparsers(dest="command", required=True)
    publish_parser = commands.add_parser("publish")
    publish_parser.add_argument("--input", required=True)
    publish_parser.add_argument("--dry-run", action="store_true")
    resume = commands.add_parser("resume")
    resume.add_argument("--slug", required=True)
    commands.add_parser("publish-self-test")
    args = parser.parse_args()
    try:
        if args.command == "publish-self-test":
            return self_test(args.category)
        if args.command == "resume":
            state = load_state(args.category, args.slug)
            return publish(args.category, common.repo_root() / state["input"], dry_run=False, resume_state=state)
        input_path = Path(args.input)
        if not input_path.is_absolute():
            input_path = common.repo_root() / input_path
        return publish(args.category, input_path, dry_run=args.dry_run)
    except (CategoryPublishError, common.PageToolError, release.PublishError, OSError, json.JSONDecodeError) as exc:
        phase = ACTIVE_STATE.get("phase", "NOT_STARTED")
        slug = ACTIVE_STATE.get("slug", "UNKNOWN")
        recovery = f"codex\\scripts\\candy-{args.category}.cmd resume --slug {slug}" if slug != "UNKNOWN" else "Fix preflight error and rerun"
        print(f"RESULT=STOP\nREASON={exc}\nPHASE={phase}\nRECOVERY_COMMAND={recovery}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
