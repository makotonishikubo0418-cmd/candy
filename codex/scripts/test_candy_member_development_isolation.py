#!/usr/bin/env python3
"""Regression checks for development-only member-page isolation."""

from __future__ import annotations

import re
from pathlib import Path


REPO_ROOT = Path(__file__).resolve().parents[2]
HP_ROOT = REPO_ROOT / "HP"
DEVELOPMENT_STEMS = {
    "member_login",
    "member_logout",
    "member_mypage",
    "member_password_reset",
    "member_register",
    "privacy",
}
DEVELOPMENT_ENTRY_PATHS = {
    *(Path(f"{stem}.php") for stem in DEVELOPMENT_STEMS),
    Path("customers/index.php"),
    Path("member/api.php"),
    Path("member/cron_notify_favorite_schedule.php"),
    Path("member/cron_notify_info.php"),
}
SOURCE_STEMS = {"member_login", "member_mypage", "member_register"}
DEVELOPMENT_TARGET_RE = re.compile(
    r'href=["\'][^"\']*(?:member_(?:login|logout|mypage|password_reset|register)|privacy|terms)\.php',
    re.I,
)


def read(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


def main() -> int:
    config = read(HP_ROOT / "includefile/member/config.php")
    require(
        bool(re.search(r"define\(\s*['\"]MEMBER_SITE_INTEGRATION_ENABLED['\"]\s*,\s*false\s*\)", config)),
        "member integration flag must remain false while the feature is development-only",
    )

    bootstrap = read(HP_ROOT / "includefile/member/bootstrap.php")
    require(
        "X-Robots-Tag: noindex, nofollow" in bootstrap,
        "member bootstrap must emit noindex,nofollow for direct and redirect responses",
    )

    for relative_path in DEVELOPMENT_ENTRY_PATHS:
        public_php = read(HP_ROOT / relative_path)
        require(
            "includefile/member/bootstrap.php" in public_php,
            f"{relative_path.as_posix()} must use the protected member bootstrap",
        )

    for stem in SOURCE_STEMS:
        source = read(HP_ROOT / "source" / f"{stem}.html")
        require(
            bool(re.search(r'<meta\s+name=["\']robots["\'][^>]+content=["\']noindex\s*,\s*nofollow["\']', source, re.I)),
            f"source/{stem}.html must contain noindex,nofollow",
        )

    mypage = read(HP_ROOT / "mypage.php")
    gate_pos = mypage.find("if (MEMBER_SITE_INTEGRATION_ENABLED)")
    bootstrap_pos = mypage.find("includefile/member/bootstrap.php")
    legacy_pos = mypage.find("includefile/dataset_base.php")
    require(gate_pos >= 0, "mypage.php must gate development integration")
    require(bootstrap_pos > gate_pos, "member bootstrap must run only inside the enabled integration branch")
    require(legacy_pos > bootstrap_pos, "mypage.php must retain the legacy public rendering fallback")

    dataset_base = read(HP_ROOT / "includefile/dataset_base.php")
    integration_guard = dataset_base.find(
        "defined('MEMBER_SITE_INTEGRATION_ENABLED') && MEMBER_SITE_INTEGRATION_ENABLED"
    )
    member_navigation = dataset_base.find("member_login.php", integration_guard)
    require(integration_guard >= 0, "dataset_base.php must guard member-site integration")
    require(member_navigation > integration_guard, "member navigation replacement must remain inside the integration guard")

    public_source_links: list[str] = []
    for source_path in sorted((HP_ROOT / "source").glob("*.html")):
        if source_path.stem in SOURCE_STEMS:
            continue
        if DEVELOPMENT_TARGET_RE.search(read(source_path)):
            public_source_links.append(source_path.relative_to(REPO_ROOT).as_posix())
    require(
        not public_source_links,
        "public source HTML must not link to development member pages: " + ",".join(public_source_links),
    )

    linked_terms = []
    for path in [HP_ROOT / "source/member_register.html", HP_ROOT / "includefile/member/layout.php"]:
        if re.search(r'href=["\'][^"\']*terms\.php', read(path), re.I):
            linked_terms.append(path.relative_to(REPO_ROOT).as_posix())
    require(not linked_terms, "terms.php must not be linked while it is unavailable: " + ",".join(linked_terms))

    print("MEMBER_DEVELOPMENT_ISOLATION=PASS")
    print("integration=false")
    print("development_entries=10")
    print("source_meta_noindex_nofollow=3")
    print("public_source_links=0")
    print("terms_links=0")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
