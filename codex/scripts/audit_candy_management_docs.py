#!/usr/bin/env python3
"""Audit the complete formal Candy management-document population."""

from __future__ import annotations

import argparse
import json
import re
import sys
from collections import Counter
from pathlib import Path
from urllib.parse import unquote


REPO_ROOT = Path(__file__).resolve().parents[2]
ROUTER = REPO_ROOT / "codex" / "WORK_ROUTING.md"
README = REPO_ROOT / "codex" / "README.md"
REGISTRY = REPO_ROOT / "codex" / "project_management" / "CASE_REGISTRY.md"
MAX_MARKDOWN_BYTES = 70_000
NORMAL_MARKDOWN_BYTES = 60_000

REQUIRED_IDENTITY = {
    "purpose": re.compile(r"^(?:>\s*)?-\s*Purpose:|^>\s*Purpose:|^##\s+\d*\.?\s*Purpose", re.MULTILINE | re.IGNORECASE),
    "parent": re.compile(r"^(?:>\s*)?-\s*Parent / Owner:|^>\s*Parent / Owner:", re.MULTILINE | re.IGNORECASE),
    "scope": re.compile(r"^(?:>\s*)?-\s*(?:Canonical scope|Scope):|^>\s*Scope:", re.MULTILINE | re.IGNORECASE),
    "lifecycle": re.compile(r"^(?:>\s*)?-\s*(?:Status / Lifecycle|Lifecycle):|^>\s*Status / Lifecycle:", re.MULTILINE | re.IGNORECASE),
    "sot": re.compile(r"^(?:>\s*)?-\s*Source of Truth Responsibility:|^>\s*Source of Truth Responsibility:", re.MULTILINE | re.IGNORECASE),
    "related_documents": re.compile(r"^(?:>\s*)?-\s*Related Documents:|^>\s*Related Documents:", re.MULTILINE | re.IGNORECASE),
    "implementation": re.compile(r"^(?:>\s*)?-\s*Related Implementation Files:|^>\s*Related Implementation Files:", re.MULTILINE | re.IGNORECASE),
}

LINK_RE = re.compile(r"(?<!!)\[[^\]]+\]\(([^)]+)\)")
SOT_RE = re.compile(r"^(?:>\s*)?-\s*Source of Truth Responsibility:\s*(.+)$|^>\s*Source of Truth Responsibility:\s*(.+)$", re.MULTILINE | re.IGNORECASE)
LIFECYCLE_RE = re.compile(r"^(?:>\s*)?-\s*(?:Status / Lifecycle|Lifecycle):\s*(.+)$|^>\s*Status / Lifecycle:\s*(.+)$", re.MULTILINE | re.IGNORECASE)
PARENT_RE = re.compile(r"^(?:>\s*)?-\s*Parent / Owner:\s*(.+)$|^>\s*Parent / Owner:\s*(.+)$", re.MULTILINE | re.IGNORECASE)


def relative(path: Path) -> str:
    return path.relative_to(REPO_ROOT).as_posix()


def markdown_population() -> list[Path]:
    files = [
        path
        for path in REPO_ROOT.rglob("*.md")
        if ".git" not in path.relative_to(REPO_ROOT).parts
    ]
    return sorted(set(files), key=lambda path: relative(path).casefold())


def generated_sidecars() -> list[Path]:
    return sorted((REPO_ROOT / "codex" / "docs" / "generated").glob("*.tsv"))


def parse_tree_lines(lines: list[str]) -> set[str]:
    stack: dict[int, Path] = {0: Path()}
    files: set[str] = set()
    for line in lines:
        positions = [position for token in ("├─", "└─") if (position := line.find(token)) >= 0]
        if not positions:
            continue
        marker = min(positions)
        level = marker // 3
        name = line[marker + 2 :].strip()
        name = re.sub(r"\s+\[[^\]]+\]$", "", name)
        parent = stack.get(level)
        if parent is None:
            raise ValueError(f"Tree level has no parent: {line}")
        if name.endswith("/"):
            stack[level + 1] = parent / name[:-1]
            for old_level in tuple(stack):
                if old_level > level + 1:
                    del stack[old_level]
        else:
            files.add((parent / name).as_posix())
    return files


def parse_router_tree() -> set[str]:
    lines = ROUTER.read_text(encoding="utf-8-sig").splitlines()
    start = next(index for index, line in enumerate(lines) if line == "### 5.1 Management Document Structure")
    end = next(index for index, line in enumerate(lines[start + 1 :], start + 1) if line == "### 5.2 Work Routing")
    return parse_tree_lines(lines[start + 1 : end])


def parse_readme_tree() -> set[str]:
    lines = README.read_text(encoding="utf-8-sig").splitlines()
    heading = next(index for index, line in enumerate(lines) if line == "## 5. Formal Management-Document Tree")
    start = next(index for index, line in enumerate(lines[heading + 1 :], heading + 1) if line == "```text")
    end = next(index for index, line in enumerate(lines[start + 1 :], start + 1) if line == "```")
    return parse_tree_lines(lines[start + 1 : end])


def identity_failures(path: Path, text: str) -> list[str]:
    if relative(path) == "AGENTS.md":
        return []
    return [name for name, pattern in REQUIRED_IDENTITY.items() if not pattern.search(text)]


def link_failures(path: Path, text: str) -> list[str]:
    failures: list[str] = []
    for raw_target in LINK_RE.findall(text):
        target = raw_target.strip().split()[0].strip("<>")
        if target.startswith(("http://", "https://", "mailto:", "#")):
            continue
        target = unquote(target.split("#", 1)[0])
        if not target:
            continue
        resolved = (path.parent / target).resolve()
        try:
            resolved.relative_to(REPO_ROOT.resolve())
        except ValueError:
            failures.append(raw_target)
            continue
        if not resolved.exists():
            failures.append(raw_target)
    return failures


def table_failures(text: str) -> list[str]:
    failures: list[str] = []
    expected: int | None = None
    in_fence = False
    for number, line in enumerate(text.splitlines(), 1):
        if line.lstrip().startswith("```"):
            in_fence = not in_fence
            expected = None
            continue
        if in_fence or not line.startswith("|"):
            expected = None
            continue
        columns = len(re.findall(r"(?<!\\)\|", line)) - 1
        if expected is None:
            expected = columns
        elif columns != expected:
            failures.append(f"line={number}:expected={expected}:actual={columns}")
    return failures


def first_match_value(pattern: re.Pattern[str], text: str) -> str:
    match = pattern.search(text)
    if not match:
        return "UNVERIFIED"
    return next((value.strip() for value in match.groups() if value is not None), "UNVERIFIED")


def case_relationship(path: Path, text: str, lifecycle: str) -> str:
    rel_path = relative(path)
    if rel_path == "AGENTS.md":
        return "Authority"
    if "Generated Current State" in lifecycle:
        return "Generated Current State"
    if "Implementation Reference" in lifecycle:
        return "Implementation Reference"
    if "Deprecated Compatibility" in lifecycle:
        return "Deprecated Compatibility"
    if "Historical Evidence" in lifecycle or "Completed" in lifecycle:
        if "task_history/" in rel_path or rel_path.endswith("TASK_LOG.md"):
            return "Task History"
        if "CASE_REGISTRY.md" in text:
            return "Registered Case"
    if rel_path.endswith("CASE_REGISTRY.md"):
        return "Case Registry"
    if "cases/" in rel_path and "Case ID:" in text and "CASE_REGISTRY.md" in text:
        return "Registered Case"
    if "Canonical" in lifecycle or "Active" in lifecycle:
        return "Canonical Responsibility"
    return "UNVERIFIED"


def resolve_reference(source: Path, target: str, by_name: dict[str, list[Path]]) -> Path | None:
    target = unquote(target.strip().split()[0].strip("<>").split("#", 1)[0])
    if not target or target.startswith(("http://", "https://", "mailto:")):
        return None
    candidate = Path(target.replace("\\", "/"))
    if target == "AGENTS.md" or target.startswith(("codex/", "docs/", "HP/")):
        resolved = REPO_ROOT / candidate
    else:
        resolved = source.parent / candidate
    if resolved.exists():
        return resolved.resolve()
    matches = by_name.get(candidate.name.casefold(), [])
    return matches[0].resolve() if len(matches) == 1 else None


def declared_parent_failures(paths: list[Path], texts: dict[str, str]) -> list[str]:
    by_name: dict[str, list[Path]] = {}
    for path in paths:
        by_name.setdefault(path.name.casefold(), []).append(path)
    failures: list[str] = []
    for path in paths:
        rel_path = relative(path)
        if rel_path == "AGENTS.md":
            continue
        match = PARENT_RE.search(texts[rel_path])
        if not match:
            failures.append(f"parent_field_missing:{rel_path}")
            continue
        value = next((group.strip() for group in match.groups() if group is not None), "")
        references = LINK_RE.findall(value) or re.findall(r"`([^`]+\.md)`", value, re.IGNORECASE)
        if not references:
            failures.append(f"parent_reference_missing:{rel_path}")
            continue
        parent = resolve_reference(path, references[0], by_name)
        if parent is None:
            failures.append(f"parent_unresolved:{rel_path}:{references[0]}")
            continue
        parent_rel = relative(parent)
        if parent_rel == "AGENTS.md":
            continue
        if path.name not in texts[parent_rel]:
            failures.append(f"parent_missing_child:{parent_rel}->{rel_path}")
    return failures


def registry_parent_failures(texts: dict[str, str]) -> list[str]:
    registry = texts[relative(REGISTRY)]
    by_name: dict[str, list[Path]] = {}
    for rel_path in texts:
        path = REPO_ROOT / rel_path
        by_name.setdefault(path.name.casefold(), []).append(path)
    failures: list[str] = []
    for line in registry.splitlines():
        if not line.startswith("| CANDY-"):
            continue
        cells = [cell.strip() for cell in line.strip().strip("|").split("|")]
        if len(cells) != 10:
            failures.append(f"registry_invalid_columns:{cells[0] if cells else 'UNKNOWN'}")
            continue
        case_id, parent_cell = cells[0], cells[4]
        if parent_cell.startswith("This registry row"):
            if "atomic case" not in parent_cell:
                failures.append(f"registry_row_parent_not_atomic:{case_id}")
            continue
        links = LINK_RE.findall(parent_cell)
        if len(links) != 1:
            failures.append(f"registry_parent_link_count:{case_id}:{len(links)}")
            continue
        parent = resolve_reference(REGISTRY, links[0], by_name)
        if parent is None:
            failures.append(f"registry_parent_unresolved:{case_id}:{links[0]}")
            continue
        parent_text = texts[relative(parent)]
        if case_id not in parent_text:
            failures.append(f"registry_parent_missing_case_id:{case_id}:{relative(parent)}")
        if "CASE_REGISTRY.md" not in parent_text:
            failures.append(f"registry_parent_missing_backlink:{case_id}:{relative(parent)}")
    return failures


def implementation_reference_failures(texts: dict[str, str], lifecycles: dict[str, str]) -> list[str]:
    failures: list[str] = []
    for rel_path, lifecycle in lifecycles.items():
        if "Implementation Reference" not in lifecycle:
            continue
        text = texts[rel_path]
        if "Verification boundary" not in text or "UNVERIFIED" not in text:
            failures.append(f"implementation_reference_boundary_missing:{rel_path}")
    return failures


def audit() -> tuple[dict[str, object], list[str]]:
    markdown = markdown_population()
    sidecars = generated_sidecars()
    texts = {relative(path): path.read_text(encoding="utf-8-sig") for path in markdown}
    sizes = {relative(path): path.stat().st_size for path in markdown}
    router_files = parse_router_tree()
    readme_files = parse_readme_tree()
    actual_files = set(texts) | {relative(path) for path in sidecars}
    failures: list[str] = []

    missing_identity: dict[str, list[str]] = {}
    broken_links: dict[str, list[str]] = {}
    invalid_tables: dict[str, list[str]] = {}
    lifecycle_values: Counter[str] = Counter()
    lifecycles: dict[str, str] = {}
    relationship_values: Counter[str] = Counter()
    sot_values: dict[str, list[str]] = {}
    for path in markdown:
        rel_path = relative(path)
        text = texts[rel_path]
        missing = identity_failures(path, text)
        if missing:
            missing_identity[rel_path] = missing
        broken = link_failures(path, text)
        if broken:
            broken_links[rel_path] = broken
        tables = table_failures(text)
        if tables:
            invalid_tables[rel_path] = tables
        lifecycle = "Authority / Active" if rel_path == "AGENTS.md" else first_match_value(LIFECYCLE_RE, text)
        lifecycles[rel_path] = lifecycle
        lifecycle_values[lifecycle] += 1
        relationship = case_relationship(path, text, lifecycle)
        relationship_values[relationship] += 1
        sot = first_match_value(SOT_RE, text)
        if sot != "UNVERIFIED" and "No current source-of-truth responsibility" not in sot and "Deterministic current-state view" not in sot:
            sot_values.setdefault(sot.casefold(), []).append(rel_path)

    duplicate_sot_declarations = {key: paths for key, paths in sot_values.items() if len(paths) > 1}
    router_tree_missing_actual = sorted(router_files - actual_files)
    actual_missing_router_tree = sorted(actual_files - router_files)
    readme_tree_missing_actual = sorted(readme_files - actual_files)
    actual_missing_readme_tree = sorted(actual_files - readme_files)
    router_missing_readme_tree = sorted(router_files - readme_files)
    readme_missing_router_tree = sorted(readme_files - router_files)
    over_limit = sorted(path for path, size in sizes.items() if size > MAX_MARKDOWN_BYTES)
    capacity = Counter(
        "0-60000" if size <= NORMAL_MARKDOWN_BYTES else "60001-70000" if size <= MAX_MARKDOWN_BYTES else "over-70000"
        for size in sizes.values()
    )
    declared_parents = declared_parent_failures(markdown, texts)
    registry_parents = registry_parent_failures(texts)
    implementation_references = implementation_reference_failures(texts, lifecycles)
    relationship_unknown = relationship_values["UNVERIFIED"]

    if missing_identity:
        failures.append(f"missing_identity={len(missing_identity)}")
    if broken_links:
        failures.append(f"broken_links={sum(len(values) for values in broken_links.values())}")
    if invalid_tables:
        failures.append(f"invalid_tables={sum(len(values) for values in invalid_tables.values())}")
    if duplicate_sot_declarations:
        failures.append(f"duplicate_sot_declaration={len(duplicate_sot_declarations)}")
    if router_tree_missing_actual:
        failures.append(f"router_tree_missing_actual={len(router_tree_missing_actual)}")
    if actual_missing_router_tree:
        failures.append(f"actual_missing_router_tree={len(actual_missing_router_tree)}")
    if readme_tree_missing_actual:
        failures.append(f"readme_tree_missing_actual={len(readme_tree_missing_actual)}")
    if actual_missing_readme_tree:
        failures.append(f"actual_missing_readme_tree={len(actual_missing_readme_tree)}")
    if router_missing_readme_tree:
        failures.append(f"router_missing_readme_tree={len(router_missing_readme_tree)}")
    if readme_missing_router_tree:
        failures.append(f"readme_missing_router_tree={len(readme_missing_router_tree)}")
    if over_limit:
        failures.append(f"markdown_over_70000={len(over_limit)}")
    if declared_parents:
        failures.append(f"declared_parent={len(declared_parents)}")
    if registry_parents:
        failures.append(f"registry_parent={len(registry_parents)}")
    if implementation_references:
        failures.append(f"implementation_reference_boundary={len(implementation_references)}")
    if relationship_unknown:
        failures.append(f"case_relationship_unknown={relationship_unknown}")
    if "DOCUMENT_RULES.md" not in ROUTER.read_text(encoding="utf-8-sig") or "CASE_REGISTRY.md" not in ROUTER.read_text(encoding="utf-8-sig"):
        failures.append("router_document_or_case_route_missing=1")

    result: dict[str, object] = {
        "result": "PASS" if not failures else "FAIL",
        "markdown_count": len(markdown),
        "generated_sidecar_count": len(sidecars),
        "formal_tree_file_count": len(router_files),
        "router_tree_file_count": len(router_files),
        "readme_tree_file_count": len(readme_files),
        "capacity": dict(sorted(capacity.items())),
        "max_markdown": max(sizes.items(), key=lambda item: item[1]),
        "lifecycle_counts": dict(sorted(lifecycle_values.items())),
        "case_relationship_counts": dict(sorted(relationship_values.items())),
        "missing_identity": missing_identity,
        "broken_links": broken_links,
        "invalid_tables": invalid_tables,
        "duplicate_sot_declarations": duplicate_sot_declarations,
        "router_tree_missing_actual": router_tree_missing_actual,
        "actual_missing_router_tree": actual_missing_router_tree,
        "readme_tree_missing_actual": readme_tree_missing_actual,
        "actual_missing_readme_tree": actual_missing_readme_tree,
        "router_missing_readme_tree": router_missing_readme_tree,
        "readme_missing_router_tree": readme_missing_router_tree,
        "over_limit": over_limit,
        "declared_parent_failures": declared_parents,
        "registry_parent_failures": registry_parents,
        "implementation_reference_failures": implementation_references,
        "failures": failures,
    }
    return result, failures


def main() -> int:
    parser = argparse.ArgumentParser(description="Audit Candy management-document structure")
    parser.add_argument("--json", action="store_true", help="emit the complete audit result as JSON")
    args = parser.parse_args()
    result, failures = audit()
    if args.json:
        print(json.dumps(result, ensure_ascii=False, indent=2))
    else:
        print(f"MANAGEMENT_AUDIT={result['result']}")
        print(
            f"markdown={result['markdown_count']} sidecars={result['generated_sidecar_count']} "
            f"tree_files={result['formal_tree_file_count']} capacity={result['capacity']}"
        )
        print(f"max_markdown={result['max_markdown'][0]} bytes={result['max_markdown'][1]}")
        print(f"lifecycle={result['lifecycle_counts']}")
        print(f"case_relationship={result['case_relationship_counts']}")
        if failures:
            print("failures=" + ",".join(failures), file=sys.stderr)
    return 0 if not failures else 1


if __name__ == "__main__":
    raise SystemExit(main())
