from pathlib import Path
import sys


SCRIPT_DIR = Path(__file__).resolve().parent
ROOT = SCRIPT_DIR.parents[1]
sys.path.insert(0, str(SCRIPT_DIR))

import candy_site_state  # noqa: E402


EXPECTED_SPECIAL = {
    "girls",
    "member_logout",
    "member_password_reset",
    "movie_iframe",
    "privacy",
}
EXPECTED_REQUIRED_GROUPS = {
    frozenset({"HP/imgHtml/cdHr.png", "HP/imgHtml/pc/cdHr.png"}),
    frozenset({"HP/imgHtml/cdTtlDiary.png", "HP/imgHtml/pc/cdTtlDiary.png"}),
    frozenset({"HP/imgHtml/listShadow.png", "HP/imgHtml/pc/listShadow.png"}),
}


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


data = candy_site_state.collect()
rendered = candy_site_state.render_all(data)
intentional_pages = {
    page["stem"]
    for page in data["pages"]
    if page["special_classification"] == "INTENTIONAL"
}
unreviewed_pages = [
    page for page in data["pages"] if page["special_classification"] == "UNREVIEWED"
]
intentional_issues = [
    page
    for page in data["pages"]
    if page["special_classification"] == "INTENTIONAL" and page["issues"]
]
required_groups = {
    frozenset(candy_site_state.rel(path) for path in paths)
    for paths in data["required_same_content_groups"]
}
required_referrers_present = all(
    path.resolve() in data["referenced_by"]
    for paths in data["required_same_content_groups"]
    for path in paths
)

backlog = (ROOT / "codex" / "docs" / "CANDY_FIX_BACKLOG.md").read_text(
    encoding="utf-8"
)
document_rules = (
    ROOT / "codex" / "project_management" / "DOCUMENT_RULES.md"
).read_text(encoding="utf-8")
other_pages = (
    ROOT / "codex" / "docs" / "CANDY_OTHER_PAGES_MANAGEMENT.md"
).read_text(encoding="utf-8")
ledger = rendered["CANDY_SITE_PAGE_LEDGER.md"]
assets = rendered["CANDY_CODE_ASSET_INVENTORY.md"]

checks = [
    (intentional_pages == EXPECTED_SPECIAL, "the intentional special-page set changed"),
    (not unreviewed_pages, "an unreviewed special structure exists"),
    (not intentional_issues, "an intentional special structure has an active issue"),
    (required_groups == EXPECTED_REQUIRED_GROUPS, "the required same-content groups changed"),
    (required_referrers_present, "a required same-content path has no confirmed referrer"),
    (not data["duplicate_groups"], "an actionable duplicate candidate exists"),
    (not data["missing_by"], "a confirmed current asset reference is missing"),
    (
        len(data["template_placeholder_by"]) == 4,
        "the template-placeholder target classification changed",
    ),
    (
        len(data["intentional_publication_exceptions"]) == 8
        and not data["public_candidates"],
        "an intentional publication path is still an unreviewed candidate",
    ),
    ("HP-SPECIAL-PAGES" not in backlog, "the obsolete special-page backlog item remains"),
    (
        "MUST NOT be treated as a problem by" in document_rules,
        "the management-wide problem-classification rule is missing",
    ),
    (
        "special classification=INTENTIONAL" in other_pages,
        "the other-page intentional-special contract is missing",
    ),
    (
        "intentional=5, unreviewed=0" in ledger
        and "required same-content groups: 3 / duplicate candidates: 0" in assets,
        "the generated summaries do not separate normal exceptions from problems",
    ),
]

for condition, message in checks:
    require(condition, message)

print(f"EXPECTED_EXCEPTION_MANAGEMENT_OK assertions={len(checks)}")
