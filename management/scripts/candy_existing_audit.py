"""Audit every public area/hotel PHP independently of available Text inputs."""
from __future__ import annotations

import argparse
from collections import Counter, defaultdict
import json
import re
import sys

import candy_area_page as area
import candy_hotel_page as hotel
import candy_page_common as common


def collect(category: str) -> list[dict]:
    hp = common.HP_ROOT
    module = area if category == "area" else hotel
    parse = area.parse_area_text if category == "area" else hotel.parse_hotel_text
    inputs = defaultdict(list)
    invalid = defaultdict(list)
    for path in common.text_input_paths(common.REPO_ROOT / f"Text_{category}_data"):
        try:
            data = parse(path)
            inputs[data.slug].append(data)
        except (RuntimeError, OSError, ValueError) as exc:
            # Canonical identity only; do not turn malformed Text into generation input.
            try:
                text = common.read_utf8(path)
            except (common.PageToolError, OSError, UnicodeError) as read_error:
                print(f"INPUT_STOP={path}: {read_error}; identity unavailable; continuing public-page audit")
                continue
            slugs = set(re.findall(rf'https://www\.55810\.com/kagoshima-deliveryhealth-{category}-([a-z0-9-]+)\.php', text))
            if len(slugs) == 1:
                invalid[next(iter(slugs))].append({"path": str(path.relative_to(common.REPO_ROOT)), "reason": str(exc)})
    templates = area.load_shop_templates(hp / "source" / "template_shop.html")
    rows = []
    for php in sorted(hp.glob(f"kagoshima-deliveryhealth-{category}-*.php")):
        slug = php.stem.removeprefix(f"kagoshima-deliveryhealth-{category}-")
        source_path = hp / "source" / f"{php.stem}.html"
        dataset = hp / "includefile" / f"dataset_{php.stem}.php"
        core = [f"missing artifact: {path.relative_to(common.REPO_ROOT)}" for path in (source_path, dataset) if not path.is_file()]
        try:
            source = common.read_utf8(source_path) if source_path.is_file() else ""
        except (common.PageToolError, OSError, UnicodeError) as exc:
            source = ""
            core.append(f"unreadable source: {source_path}: {exc}")
        canonical = f"https://www.55810.com/{php.name}"
        images = sorted(set(re.findall(rf'\./imgHtml/new_202601/{category}/[^\s"\'<>]+', source)))
        if len(images) < 2:
            core.append(f"fewer than two category image references: {len(images)}")
        for value in images:
            if not (hp / value.removeprefix("./").split("?", 1)[0]).is_file():
                core.append(f"missing image: {value}")
        if source:
            core.extend(common.validate_json_ld(source))
            if not re.search(r'<link\b[^>]*rel=["\']canonical["\'][^>]*href=["\']' + re.escape(canonical) + r'["\']', source):
                core.append("canonical mismatch")
            ids = re.findall(r'\bid=["\']([^"\']+)', source)
            if len(ids) != len(set(ids)):
                core.append("duplicate HTML IDs")
            parser = area.BalanceParser()
            parser.feed(source)
            core.extend(parser.errors)
            if parser.stack:
                core.append("unclosed HTML tags: " + ",".join(parser.stack))
        try:
            core.extend(common.shared_validation(category, slug, canonical))
        except (OSError, ValueError) as exc:
            core.append(str(exc))
        php_status, php_errors = common.php_lint([path for path in (php, dataset) if path.is_file()])
        core.extend(php_errors)
        if php_status == "UNAVAILABLE":
            core.append("PHP_LINT=UNAVAILABLE")
        matches = inputs[slug]
        input_status = "DUPLICATE" if len(matches) + len(invalid[slug]) > 1 else ("MATCHED" if len(matches) == 1 else ("INVALID" if invalid[slug] else "MISSING_OR_UNIDENTIFIABLE"))
        strict = []
        if input_status == "MATCHED" and source:
            try:
                resolved = module.resolve_shops(matches[0], hp, templates)
                strict = module.validate_rendered(matches[0], resolved, source, hp)
                strict.extend(module.shared_validation(matches[0], hp))
            except (RuntimeError, OSError, ValueError) as exc:
                strict.append(str(exc))
        status = "CORE_ISSUE" if core else ("INPUT_REVIEW" if input_status != "MATCHED" else ("CURRENT_CONTRACT_MISMATCH" if strict else "PASS"))
        rows.append({"slug": slug, "status": status, "input_status": input_status,
                     "input_issues": invalid[slug], "core_issues": core,
                     "current_contract_issues": strict, "php_lint": php_status,
                     "next_action": "none" if status == "PASS" else "review exact findings; do not regenerate or fill Text automatically",
                     "production": "NOT_VERIFIED"})
    if not rows:
        raise common.PageToolError(f"no public {category} PHP pages found")
    return rows


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("command", choices=("audit-existing",))
    parser.add_argument("--category", choices=("area", "hotel"), required=True)
    args = parser.parse_args()
    rows = collect(args.category)
    print("AUDIT=COMPLETED; SCOPE=ALL_PUBLIC_PHP; LEGACY_MISMATCH_IS_NOT_PROOF_OF_PRODUCTION_FAILURE")
    print(f"EXISTING_{args.category.upper()}_COUNT={len(rows)}")
    print("COUNTS_JSON=" + json.dumps(dict(Counter(row["status"] for row in rows)), ensure_ascii=False))
    for row in rows:
        print("ROW=" + json.dumps(row, ensure_ascii=False))
    return 1 if any(row["status"] != "PASS" for row in rows) else 0


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except (common.PageToolError, OSError, ValueError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        raise SystemExit(2)
