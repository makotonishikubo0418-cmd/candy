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


def relative(path: Path) -> str:
    return path.relative_to(REPO_ROOT).as_posix()


def markdown_population() -> list[Path]:
    files = [REPO_ROOT / "AGENTS.md", REPO_ROOT / "docs" / "rules" / "GIT_RULES.md"]
    files.extend((REPO_ROOT / "codex").rglob("*.md"))
    return sorted(set(files), key=lambda path: relative(path).casefold())


def generated_sidecars() -> list[Path]:
    return sorted((REPO_ROOT / "codex" / "docs" / "generated").glob("*.tsv"))


def parse_router_tree() -> set[str]:
    lines = ROUTER.read_text(encoding="utf-8-sig").splitlines()
    start = next(index for index, line in enumerate(lines) if line == "### 5.1 Management Document Structure")
    end = next(index for index, line in enumerate(lines[start + 1 :], start + 1) if line == "### 5.2 Work Routing")
    stack: dict[int, Path] = {0: Path()}
    files: set[str] = set()
    for line in lines[start + 1 : end]:
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


def parent_child_failures(texts: dict[str, str]) -> list[str]:
    pairs = [
        ("codex/project_management/CASE_REGISTRY.md", "codex/project_management/cases/CANDY_MANAGEMENT_SYSTEM_REBUILD.md"),
        ("codex/project_management/TASK_LOG.md", "codex/project_management/task_history/TASK_LOG_2026_07_01_20.md"),
        ("codex/project_management/TASK_LOG.md", "codex/project_management/task_history/TASK_LOG_2026_07_21_31.md"),
        ("codex/project_management/TASK_LOG.md", "codex/project_management/task_history/TASK_LOG_2026_08.md"),
        ("codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md", "codex/docs/generated/CANDY_CODE_REFERENCE_INVENTORY.md"),
    ]
    failures: list[str] = []
    for parent, child in pairs:
        child_name = Path(child).name
        parent_name = Path(parent).name
        if child_name not in texts[parent]:
            failures.append(f"parent_missing_child:{parent}->{child}")
        if parent_name not in texts[child]:
            failures.append(f"child_missing_parent:{child}->{parent}")
    registry = texts["codex/project_management/CASE_REGISTRY.md"]
    registered_parents = (
        "CANDY_MANAGEMENT_SYSTEM_REBUILD.md",
        "CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md",
        "CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md",
        "CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md",
        "HANDOFF_README.md",
        "指示書監査.md",
    )
    for name in registered_parents:
        if name not in registry:
            failures.append(f"registry_missing_parent:{name}")
    return failures


def audit() -> tuple[dict[str, object], list[str]]:
    markdown = markdown_population()
    sidecars = generated_sidecars()
    texts = {relative(path): path.read_text(encoding="utf-8-sig") for path in markdown}
    sizes = {relative(path): path.stat().st_size for path in markdown}
    router_files = parse_router_tree()
    actual_files = set(texts) | {relative(path) for path in sidecars}
    failures: list[str] = []

    missing_identity: dict[str, list[str]] = {}
    broken_links: dict[str, list[str]] = {}
    invalid_tables: dict[str, list[str]] = {}
    lifecycle_values: Counter[str] = Counter()
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
        lifecycle_values[lifecycle] += 1
        relationship = case_relationship(path, text, lifecycle)
        relationship_values[relationship] += 1
        sot = first_match_value(SOT_RE, text)
        if sot != "UNVERIFIED" and "No current source-of-truth responsibility" not in sot and "Deterministic current-state view" not in sot:
            sot_values.setdefault(sot.casefold(), []).append(rel_path)

    duplicate_sot = {key: paths for key, paths in sot_values.items() if len(paths) > 1}
    tree_missing_actual = sorted(router_files - actual_files)
    actual_missing_tree = sorted(actual_files - router_files)
    over_limit = sorted(path for path, size in sizes.items() if size > MAX_MARKDOWN_BYTES)
    capacity = Counter(
        "0-60000" if size <= NORMAL_MARKDOWN_BYTES else "60001-70000" if size <= MAX_MARKDOWN_BYTES else "over-70000"
        for size in sizes.values()
    )
    parent_child = parent_child_failures(texts)
    relationship_unknown = relationship_values["UNVERIFIED"]

    if missing_identity:
        failures.append(f"missing_identity={len(missing_identity)}")
    if broken_links:
        failures.append(f"broken_links={sum(len(values) for values in broken_links.values())}")
    if invalid_tables:
        failures.append(f"invalid_tables={sum(len(values) for values in invalid_tables.values())}")
    if duplicate_sot:
        failures.append(f"duplicate_sot={len(duplicate_sot)}")
    if tree_missing_actual:
        failures.append(f"tree_missing_actual={len(tree_missing_actual)}")
    if actual_missing_tree:
        failures.append(f"actual_missing_tree={len(actual_missing_tree)}")
    if over_limit:
        failures.append(f"markdown_over_70000={len(over_limit)}")
    if parent_child:
        failures.append(f"parent_child={len(parent_child)}")
    if relationship_unknown:
        failures.append(f"case_relationship_unknown={relationship_unknown}")
    if "CASE_REGISTRY.md" not in README.read_text(encoding="utf-8-sig") or "Formal Management-Document Tree" not in README.read_text(encoding="utf-8-sig"):
        failures.append("readme_tree_or_registry_route_missing=1")
    if "DOCUMENT_RULES.md" not in ROUTER.read_text(encoding="utf-8-sig") or "CASE_REGISTRY.md" not in ROUTER.read_text(encoding="utf-8-sig"):
        failures.append("router_document_or_case_route_missing=1")

    result: dict[str, object] = {
        "result": "PASS" if not failures else "FAIL",
        "markdown_count": len(markdown),
        "generated_sidecar_count": len(sidecars),
        "formal_tree_file_count": len(router_files),
        "capacity": dict(sorted(capacity.items())),
        "max_markdown": max(sizes.items(), key=lambda item: item[1]),
        "lifecycle_counts": dict(sorted(lifecycle_values.items())),
        "case_relationship_counts": dict(sorted(relationship_values.items())),
        "missing_identity": missing_identity,
        "broken_links": broken_links,
        "invalid_tables": invalid_tables,
        "duplicate_sot": duplicate_sot,
        "tree_missing_actual": tree_missing_actual,
        "actual_missing_tree": actual_missing_tree,
        "over_limit": over_limit,
        "parent_child_failures": parent_child,
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
