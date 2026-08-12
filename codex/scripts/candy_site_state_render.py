#!/usr/bin/env python3
"""Render deterministic Candy site-state Markdown summaries and detail sidecars."""

from __future__ import annotations

import hashlib
from collections import Counter, defaultdict
from pathlib import Path

from candy_page_common import HP_ROOT, REPO_ROOT


SCRIPT_REL = "codex/scripts/candy_site_state.py"
RENDERER_REL = "codex/scripts/candy_site_state_render.py"


def rel(path: Path) -> str:
    try:
        return path.resolve().relative_to(REPO_ROOT.resolve()).as_posix()
    except ValueError:
        return path.as_posix()


def md(value: object) -> str:
    text = str(value) if value not in (None, "") else "-"
    return text.replace("|", "\\|").replace("\r", " ").replace("\n", "<br>")


def tsv(value: object) -> str:
    text = str(value) if value not in (None, "") else "-"
    return text.replace("\t", " ").replace("\r", " ").replace("\n", " ")


def header(
    data: dict[str, object],
    purpose: str,
    parent: str,
    scope: str,
    population: str,
    result: str,
    unverified: str,
    related: str,
) -> list[str]:
    return [
        "> **Automatically generated. Manual editing is prohibited.**",
        ">",
        f"> Purpose: {purpose}",
        f"> Parent / Owner: {parent}",
        f"> Scope: {scope}",
        "> Status / Lifecycle: Generated Current State / Active",
        "> Source of Truth Responsibility: Deterministic current-state view; actual repository files are the underlying source",
        f"> Related Documents: {related}",
        f"> Related Implementation Files: `{SCRIPT_REL}`, `{RENDERER_REL}`, and the actual files represented by this output",
        f"> Generated at: {data['generation_time']} (reproducible generation baseline)",
        f"> Branch: {data['branch']}",
        f"> Commit: {data['head']}",
        f"> State fingerprint: sha256:{data['state_fingerprint']}",
        f"> Population: {population}",
        f"> Generator: `{SCRIPT_REL}` with `{RENDERER_REL}`",
        f"> Result: {result}",
        f"> Unverified scope: {unverified}",
    ]


def render_ledger_summary(data: dict[str, object]) -> str:
    pages = data["pages"]
    categories = Counter(page["category"] for page in pages)
    structures = Counter(page["structure"] for page in pages)
    lines = ["# CANDY SITE PAGE LEDGER", ""]
    lines += header(
        data,
        "Route current public-page structure to a deterministic row-level detail file",
        "`../CANDY_MASTER_DOC_INDEX.md`",
        "Public PHP files directly under HP and their source, dataset, Text, index, sitemap, SEO, and image relationships",
        f"Public PHP files: {len(pages)}",
        " / ".join(f"{key}={structures[key]}" for key in sorted(structures)),
        "Production HTTP, database state, and external include targets",
        "`CANDY_SITE_PAGE_LEDGER.tsv`, stable structure specifications, and category specifications",
    )
    lines += [
        "",
        "The Markdown parent owns scope, provenance, and summary. The complete one-page-per-row population is in [CANDY_SITE_PAGE_LEDGER.tsv](CANDY_SITE_PAGE_LEDGER.tsv).",
        "",
        "| category | pages |",
        "|---|---:|",
    ]
    for category, count in sorted(categories.items()):
        lines.append(f"| {md(category)} | {count} |")
    lines += ["", "| structure | pages |", "|---|---:|"]
    for structure, count in sorted(structures.items()):
        lines.append(f"| {md(structure)} | {count} |")
    return "\n".join(lines) + "\n"


def render_ledger_tsv(data: dict[str, object]) -> str:
    fields = (
        "page ID", "category", "page name", "slug", "role", "public PHP", "source HTML",
        "dataset PHP", "dataset_base", "source Text", "template", "index registrations",
        "sitemap entries", "SEO", "images", "structure", "issues", "verification source",
    )
    lines = ["\t".join(fields)]
    for page in data["pages"]:
        texts = "; ".join(rel(record.path) for record in page["texts"]) or "NOT_APPLICABLE"
        template = rel(page["template"]) if page["template"] and page["template"].is_file() else "NOT_APPLICABLE"
        values = (
            page["page_id"], page["category"], page["page_name"], page["slug"], page["role"],
            rel(page["public"]), rel(page["source"]) if page["source"] else "MISSING",
            rel(page["dataset"]) if page["dataset"] else "MISSING",
            f"case {page['case_count']} / conversions {page['conversion_count']}", texts, template,
            page["list_count"] if page["list_count"] is not None else "NOT_APPLICABLE",
            page["sitemap_count"], page["seo"], page["image_status"], page["structure"],
            "; ".join(page["issues"]) or "NONE", f"{data['head']} / {data['generation_time']}",
        )
        lines.append("\t".join(tsv(value) for value in values))
    return "\n".join(lines) + "\n"


def upcoming_name(category: str) -> str:
    return f"CANDY_UPCOMING_{category.upper()}_PAGES.tsv"


def render_upcoming_summary(data: dict[str, object]) -> str:
    rows = data["upcoming"]
    gates = Counter(row["gate"] for row in rows)
    categories = Counter(row["category"] for row in rows)
    lines = ["# CANDY UPCOMING PAGES", ""]
    lines += header(
        data,
        "Route current production-candidate state to category-specific deterministic detail files",
        "`../CANDY_MASTER_DOC_INDEX.md`",
        "Text_area_data, Text_hotel_data, Text_blog_data, current pages, images, indexes, and sitemap entries",
        f"Unique candidates: {len(rows)} / Text records: {len(data['texts'])}",
        " / ".join(f"{key}={gates[key]}" for key in ("READY", "BLOCKED", "EXISTING", "CONFLICT")),
        "Text accuracy, Git tracking, and the owner's publication decision",
        "Category queue/classification documents and the three TSV children listed below",
    )
    lines += [
        "",
        "This Markdown parent owns cross-category scope and summary. Complete candidate rows are split by data class, not arbitrary parts:",
        "",
        "| category | candidates | detail child |",
        "|---|---:|---|",
    ]
    for category, count in sorted(categories.items()):
        name = upcoming_name(category)
        lines.append(f"| {md(category)} | {count} | [{name}]({name}) |")
    lines += ["", "| gate | candidates |", "|---|---:|"]
    for gate in ("READY", "BLOCKED", "EXISTING", "CONFLICT"):
        lines.append(f"| {gate} | {gates[gate]} |")
    return "\n".join(lines) + "\n"


def render_upcoming_tsv(data: dict[str, object], category: str) -> str:
    fields = (
        "category", "source Text", "page name", "slug", "input status", "image status",
        "existing page", "index registrations", "sitemap entries", "target gate", "blocker",
        "next action", "operational source",
    )
    lines = ["\t".join(fields)]
    for row in data["upcoming"]:
        if row["category"] != category:
            continue
        values = (
            row["category"], "; ".join(rel(record.path) for record in row["texts"]), row["page_name"],
            row["slug"], row["input"], row["image"], row["existing"], row["list"], row["sitemap"],
            row["gate"], row["blocker"], row["next"], row["source"],
        )
        lines.append("\t".join(tsv(value) for value in values))
    return "\n".join(lines) + "\n"


def refs_for_extension(data: dict[str, object], extension: str) -> list[tuple[Path, list[str]]]:
    rows: list[tuple[Path, list[str]]] = []
    referenced_by = data["referenced_by"]
    paths = list(HP_ROOT.rglob(f"*{extension}"))
    if extension == ".js":
        paths.extend(
            path
            for path in referenced_by
            if path.suffix.lower() == ".php" and path.parent == (HP_ROOT / "js").resolve()
        )
    for path in sorted(set(paths), key=lambda item: rel(item).casefold()):
        rows.append((path, sorted(rel(item) for item in referenced_by.get(path.resolve(), set()))))
    return rows


def render_code_reference(data: dict[str, object]) -> str:
    pages = data["pages"]
    lines = ["# CANDY CODE REFERENCE INVENTORY", ""]
    lines += header(
        data,
        "Preserve detailed public PHP, shared PHP, CSS, and JavaScript reference relationships",
        "[`CANDY_CODE_ASSET_INVENTORY.md`](CANDY_CODE_ASSET_INVENTORY.md)",
        "Public PHP structure files and shared PHP, CSS, and JavaScript referrers",
        f"Public PHP files: {len(pages)}",
        "OK",
        "Runtime-generated references, database-derived references, and external URLs",
        "`CANDY_CODE_ASSET_INVENTORY.md` and `../CANDY_CODE_FILE_STRUCTURE.md`",
    )
    lines += ["", "## Public PHP and Structure Files", "", "| public PHP | source | dataset | case | link conversions |", "|---|---|---|---:|---:|"]
    for page in pages:
        lines.append(
            f"| {md(rel(page['public']))} | {md(rel(page['source']) if page['source'] else 'MISSING')} | "
            f"{md(rel(page['dataset']) if page['dataset'] else 'MISSING')} | {page['case_count']} | {page['conversion_count']} |"
        )
    common = {
        "HP/includefile/dataset_base.php": "Included by public PHP files. Common entry for source selection, external session and database settings, dataset branching, and HTML link conversion.",
        "HP/includefile/class.hpgcoder2.php": "Loaded by dataset_base. Assigns rep...eot placeholders to their functions.",
        "HP/includefile/funcs.php": "Loaded by dataset_base and the class file. Provides shared database, HTML, header, and related functions.",
        "HP/create.php": "Special file-generation entry point. MUST NOT be used during ordinary production.",
    }
    lines += ["", "## Shared PHP", "", "| path | role and impact |", "|---|---|"]
    for path, role in common.items():
        state = "OK" if (REPO_ROOT / path).is_file() else "UNVERIFIED"
        lines.append(f"| `{path}` | {role} Status={state} |")
    lines += ["", "Only external session and database configuration references are checked. Secret values are neither collected nor output.", ""]
    for label, extension in (("CSS", ".css"), ("JavaScript", ".js")):
        lines += [f"## {label} Files and Referrers", "", "| file | referrers |", "|---|---|"]
        for path, referrers in refs_for_extension(data, extension):
            lines.append(f"| {md(rel(path))} | {md('<br>'.join(referrers) if referrers else 'UNVERIFIED')} |")
        lines.append("")
    return "\n".join(lines).rstrip() + "\n"


def render_asset_summary(data: dict[str, object]) -> str:
    pages = data["pages"]
    assets: list[Path] = data["assets"]
    extensions = Counter(path.suffix.lower() for path in assets)
    folders = Counter(rel(path.parent) for path in assets)
    lines = ["# CANDY CODE ASSET INVENTORY", ""]
    lines += header(
        data,
        "Summarize current assets, page relationships, missing targets, and review candidates",
        "`../CANDY_MASTER_DOC_INDEX.md`",
        "Images, videos, fonts, asset counts, missing references, unconfirmed referrers, duplicate hashes, and publication candidates",
        f"Public PHP files: {len(pages)} / assets: {len(assets)}",
        f"Missing references: {len(data['missing_by'])} / duplicate hash groups: {len(data['duplicate_groups'])}",
        "Runtime-generated references, database-derived references, external URLs, and log contents",
        "`CANDY_CODE_REFERENCE_INVENTORY.md` and `../CANDY_CODE_FILE_STRUCTURE.md`",
    )
    lines += [
        "",
        "Detailed public PHP, shared PHP, CSS, and JavaScript relationships are owned by [CANDY_CODE_REFERENCE_INVENTORY.md](CANDY_CODE_REFERENCE_INVENTORY.md).",
        "",
        "## Asset Summary",
        "",
        "### By Extension",
        "",
        "| extension | count |",
        "|---|---:|",
    ]
    for extension, count in sorted(extensions.items()):
        lines.append(f"| {md(extension)} | {count} |")
    lines += ["", "### By Folder", "", "| folder | count |", "|---|---:|"]
    for folder, count in sorted(folders.items()):
        lines.append(f"| {md(folder)} | {count} |")
    lines += ["", "## Assets by Page", "", "| page ID | referenced images | missing | status |", "|---|---:|---|---|"]
    for page in pages:
        lines.append(f"| {md(page['page_id'])} | {len(page['images'])} | {md('<br>'.join(page['missing_images']) or 'NONE')} | {page['image_status']} |")
    lines += ["", "## Missing Reference Targets", "", "| target | referrers |", "|---|---|"]
    if not data["missing_by"]:
        lines.append("| NONE | - |")
    else:
        for path, referrers in sorted(data["missing_by"].items(), key=lambda item: rel(item[0]).casefold()):
            lines.append(f"| {md(rel(path))} | {md('<br>'.join(sorted(rel(item) for item in referrers)))} |")
    by_folder = defaultdict(list)
    for path in data["unreferenced"]:
        by_folder[rel(path.parent)].append(path)
    lines += [
        "", "## Assets Without a Confirmed Referrer", "",
        "These candidates may be referenced dynamically and are not deletion decisions.", "",
        "| folder | count | examples (first five) |", "|---|---:|---|",
    ]
    for folder, paths in sorted(by_folder.items()):
        lines.append(f"| {md(folder)} | {len(paths)} | {md(', '.join(path.name for path in paths[:5]))} |")
    lines += ["", "## Duplicate Hash Candidates", "", "| SHA-256 | files |", "|---|---|"]
    if not data["duplicate_groups"]:
        lines.append("| NONE | - |")
    else:
        for paths in sorted(data["duplicate_groups"], key=lambda group: rel(group[0]).casefold()):
            digest = hashlib.sha256(paths[0].read_bytes()).hexdigest()
            lines.append(f"| `{digest}` | {md('<br>'.join(rel(path) for path in paths))} |")
    lines += ["", "## Candidates That May Not Require Publication", "", "| path | assessment |", "|---|---|"]
    if not data["public_candidates"]:
        lines.append("| NONE | No automatic assessment |")
    else:
        for path in sorted(data["public_candidates"], key=lambda item: rel(item).casefold()):
            lines.append(f"| {md(rel(path))} | Candidate based only on extension and name. MUST NOT be deleted before the owner decides. |")
    return "\n".join(lines) + "\n"


def render_seo_summary(data: dict[str, object]) -> str:
    rows = data["seo"]
    overall = Counter(row["overall"] for row in rows)
    lines = ["# CANDY SEO STATUS", ""]
    lines += header(
        data,
        "Summarize current static SEO checks and route the complete page population to deterministic detail",
        "`../CANDY_MASTER_DOC_INDEX.md`",
        "Source HTML corresponding to public PHP files directly under HP",
        f"Pages: {len(rows)}",
        " / ".join(f"{key}={overall[key]}" for key in ("OK", "ISSUE", "UNVERIFIED")),
        "Production HTTP, search-engine index state, redirects, and database-generated HTML",
        "`CANDY_SEO_STATUS.tsv` and `../CANDY_SEO_SPEC.md`",
    )
    lines += [
        "",
        "The complete per-page SEO population is in [CANDY_SEO_STATUS.tsv](CANDY_SEO_STATUS.tsv). Detected issues are not corrected automatically.",
        "",
        "| result | pages |",
        "|---|---:|",
    ]
    for key in ("OK", "ISSUE", "UNVERIFIED"):
        lines.append(f"| {key} | {overall[key]} |")
    lines += [
        "", "## Assessment Boundaries", "",
        "- FAQ matching compares static FAQ-item counts with FAQPage entities; semantic equivalence is UNVERIFIED.",
        "- Orphan candidates use inbound static PHP and HTML links; database- or JavaScript-generated links are UNVERIFIED.",
        "- Robots, canonical, URL, and structured-data values are not changed automatically.",
    ]
    return "\n".join(lines) + "\n"


def render_seo_tsv(data: dict[str, object]) -> str:
    fields = (
        "page ID", "title", "description", "canonical", "robots", "H1", "H1 count", "OGP",
        "JSON-LD", "BreadcrumbList", "FAQPage match", "ItemList", "internal links", "image alt",
        "sitemap", "URL=canonical", "duplicate title", "duplicate canonical", "orphan candidate", "SEO", "issues",
    )
    lines = ["\t".join(fields)]
    for row in data["seo"]:
        values = (
            row["page_id"], row["title"], row["description"], row["canonical"], row["robots"], row["h1"],
            row["h1_count"], row["ogp"], row["json_ld"], row["breadcrumb"], row["faq"], row["item_list"],
            row["internal_links"], row["image_alt"], row["sitemap"], row["url_canonical"], row["duplicate_title"],
            row["duplicate_canonical"], row["orphan"], row["overall"], "; ".join(row["issues"]) or "NONE",
        )
        lines.append("\t".join(tsv(value) for value in values))
    return "\n".join(lines) + "\n"


def render_all(data: dict[str, object]) -> dict[str, str]:
    return {
        "CANDY_SITE_PAGE_LEDGER.md": render_ledger_summary(data),
        "CANDY_SITE_PAGE_LEDGER.tsv": render_ledger_tsv(data),
        "CANDY_UPCOMING_PAGES.md": render_upcoming_summary(data),
        "CANDY_UPCOMING_AREA_PAGES.tsv": render_upcoming_tsv(data, "area"),
        "CANDY_UPCOMING_HOTEL_PAGES.tsv": render_upcoming_tsv(data, "hotel"),
        "CANDY_UPCOMING_BLOG_PAGES.tsv": render_upcoming_tsv(data, "blog"),
        "CANDY_CODE_ASSET_INVENTORY.md": render_asset_summary(data),
        "CANDY_CODE_REFERENCE_INVENTORY.md": render_code_reference(data),
        "CANDY_SEO_STATUS.md": render_seo_summary(data),
        "CANDY_SEO_STATUS.tsv": render_seo_tsv(data),
    }
