# CANDY Master Document Router

## 1. Purpose

Use this entry point to select only the canonical document required for HP work. Priority order is root `AGENTS.md`, `HP/AGENTS.md`, this router, the target specification/runbook, and target actual files.

Do not mix stable specifications with current state. Regenerate `generated/` to verify counts, structural state, SEO state, and asset references.

## 2. Shortest Routes

| Task | Required route |
|---|---|
| Review all site pages | `CANDY_HP_STRUCTURE_MAP.md` → `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Review page-structure files | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Review unbuilt pages or production candidates | `generated/CANDY_UPCOMING_PAGES.md` → target category queue/classification → runbook |
| Review PHP, CSS, JavaScript, or image impact | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Review SEO state or make an SEO change | `CANDY_SEO_SPEC.md` → `generated/CANDY_SEO_STATUS.md` → target category specification |
| Check management-document drift | `codex\scripts\candy-site-state.cmd check` |
| Fix an existing page | `CANDY_OPERATION_BASICS.md` → target category specification → ledger/SEO status |

## 3. Category Routes

| Task | Canonical source |
|---|---|
| Normal area production and publication | `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Area structure or unknown exception | `CANDY_AREA_PAGE_GENERATION_SPEC.md` |
| Area-image production and management | `CANDY_AREA_IMAGE_CREATION_RUNBOOK.md` → `CANDY_AREA_IMAGE_CREATION_SPEC.md` → `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| Area production order | `CANDY_AREA_105_PAGE_QUEUE.md` and `generated/CANDY_UPCOMING_PAGES.md` |
| Normal hotel production and publication | `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| Hotel structure or unknown exception | `CANDY_PAGE_GENERATION_GOVERNANCE.md` and `CANDY_HOTEL_PAGE_GENERATION_SPEC.md` |
| Hotel-input classification and production order | `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` and `generated/CANDY_UPCOMING_PAGES.md` |
| Hotel images | `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` |
| Normal blog production or unknown exception | `CANDY_PAGE_GENERATION_GOVERNANCE.md` and `CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| Pages outside area, hotel, and blog | `CANDY_OTHER_PAGES_MANAGEMENT.md` and `CANDY_OPERATION_BASICS.md` |

Do not use fixed-count examples in a category specification as current state. Compare the target Text's complete blocks, actual files, and generated documents.

## 4. Common Management Documents

| Document | Responsibility |
|---|---|
| `CANDY_HP_STRUCTURE_MAP.md` | Stable page types and index/detail/dynamic-page structure |
| `CANDY_CODE_FILE_STRUCTURE.md` | Stable PHP/source/dataset, CSS, JavaScript, and asset structure |
| `CANDY_SEO_SPEC.md` | Common SEO specification and change gates |
| `CANDY_OPERATION_BASICS.md` | Standard existing-page investigation and fix procedure |
| `CANDY_FIX_BACKLOG.md` | Only unresolved issues requiring a specification, fix, or owner decision |
| `CANDY_VERIFICATION_PLAN.md` | Additional full-population, link, and image validation |
| `CANDY_PRODUCTION_MIGRATION_MASTER.md` | Actions, FTP, and production foundation |
| `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` | Incident context. Do not use as a substitute for current specifications |

## 5. Generated Current State

`codex/docs/generated/` contains only output from `candy_site_state.py`. Manual editing is prohibited.

| Document | Contents |
|---|---|
| `generated/CANDY_SITE_PAGE_LEDGER.md` | Public-page pairing with PHP, source, dataset, Text, indexes, and sitemap |
| `generated/CANDY_UPCOMING_PAGES.md` | Text candidates, input, images, existing pages, gates, and blockers |
| `generated/CANDY_CODE_ASSET_INVENTORY.md` | PHP, CSS, JavaScript, images, movies, fonts, references, missing files, and duplicate candidates |
| `generated/CANDY_SEO_STATUS.md` | Measured per-page SEO state and issues |

Standard entry points:

```powershell
codex\scripts\candy-site-state.cmd audit
codex\scripts\candy-site-state.cmd preview
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

## 6. Completion Gate for Page Additions and Fixes

1. Before the change, run `check --target "<slug>"` to verify agreement with the ledger.
2. Change and validate only the target according to the category runbook/specification.
3. Before staging, run `write` and `check`.
4. Include required category queue/classification updates and generated documents in the same work unit.
5. Do not report completion or make the work a Commit candidate while `check` fails.

## 7. Excluded from the Normal Route

- NAS `\\192.168.1.3\disk1\FSG_SEO\candy\Backup/` is storage-only and MUST NOT be used as the basis for a current specification.
- Do not use `.git-backups/` or legacy investigation snapshots for current decisions.
- Historical counts in `CANDY_PAGE_SPEC_INDEX.md`, `CANDY_PAGE_CATEGORY_STRUCTURE.md`, and legacy inventory documents are not current values.
- Normal work routes through legacy management documents are deprecated. Use generated documents for current state and the canonical stable documents routed here for specifications.

## 8. Update Principles

- Do not duplicate the same specification across documents.
- Do not store page counts, file counts, or Git/HTTP results in a stable document.
- Do not store task history, body copy, or owner decisions in a generated document.
- Do not duplicate machine-detectable issues in the backlog.
- Apply a specification change to its existing canonical document and include it in the same work unit as the page change.
- Commit, Push, and production operations require explicit user instruction.
