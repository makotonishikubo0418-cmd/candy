# CANDY Canonical Responsibility Index

## 1. Purpose

`codex/WORK_ROUTING.md` Section 5.2 is the sole routing authority. This file is a
non-authoritative ownership lookup used only when that route names it. It
identifies which HP document owns a topic but cannot add mandatory reading,
change priority, grant authority, or define an execution sequence.

Do not mix stable specifications with current state. Regenerate `generated/` to verify counts, structural state, SEO state, and asset references.

## 2. HP Responsibility Lookup

| Topic | Canonical sources |
|---|---|
| Review all site pages | `CANDY_HP_STRUCTURE_MAP.md` → `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Review page-structure files | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Review unbuilt pages or production candidates | `generated/CANDY_UPCOMING_PAGES.md` → target category queue/classification → runbook |
| Review PHP, CSS, JavaScript, or image impact | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Replace an existing approved area-image pair under the same canonical filenames | `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` → actual target files |
| Replace another existing public image, CSS, JavaScript, or static asset at the same path | `CANDY_OPERATION_BASICS.md` → applicable asset/category specification → `CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| Review SEO state or make an SEO change | `CANDY_SEO_SPEC.md` → `generated/CANDY_SEO_STATUS.md` → target category specification |
| Check management-document drift | `codex\scripts\candy-site-state.cmd check` |
| Fix an existing page | `CANDY_OPERATION_BASICS.md` → target category specification → ledger/SEO status |

## 3. Category Responsibility Lookup

| Task | Canonical source |
|---|---|
| Normal area production and publication | `CANDY_PAGE_GENERATION_GOVERNANCE.md` and `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Area structure or unknown exception | `CANDY_AREA_PAGE_GENERATION_SPEC.md` |
| Area-image creation and pre-acceptance validation | `CANDY_AREA_IMAGE_CREATION_RUNBOOK.md` → `CANDY_AREA_IMAGE_CREATION_SPEC.md` → `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| Existing approved area-image replacement | `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`; use only the conditional exception routes named there |
| Area production order | `CANDY_AREA_105_PAGE_QUEUE.md` and `generated/CANDY_UPCOMING_PAGES.md` |
| Legacy hotel Text inspection or conversion | `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` and the exact target Text |
| Production from a staff-completed hotel Text | `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` and the exact target Text; use `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` and `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` only when `direct-check` returns `READY_FOR_IMAGES` |
| Hotel identity, access, copy, FAQ, SEO-input, shop, and nearby-spot preparation through the Phase route | `CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md` and the exact target Text |
| Normal hotel production and publication | `CANDY_PAGE_GENERATION_GOVERNANCE.md` and `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| Hotel structure or unknown exception | `CANDY_PAGE_GENERATION_GOVERNANCE.md` and `CANDY_HOTEL_PAGE_GENERATION_SPEC.md` |
| Hotel-input classification and production order | `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` and `generated/CANDY_UPCOMING_PAGES.md` |
| Hotel-image creation and pre-acceptance validation | `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` → `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` |
| Hotel-image acceptance, accepted-source storage, first local public installation, replacement, and publication state | `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`; use `CANDY_PRODUCTION_MIGRATION_MASTER.md` for existing same-name replacement, production deployment exceptions, recovery, or rollback |
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
| `CANDY_PRODUCTION_MIGRATION_MASTER.md` | Actions, FTP, production foundation, and same-path static-asset replacement and client-cache safety |
| `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` | Incident context. Do not use as a substitute for current specifications |

## 5. Generated Current State

`codex/docs/generated/` contains only output from `candy_site_state.py`. Manual editing is prohibited.

| Document | Contents |
|---|---|
| `generated/CANDY_SITE_PAGE_LEDGER.md` | Public-page pairing with PHP, source, dataset, Text, indexes, and sitemap |
| `generated/CANDY_UPCOMING_PAGES.md` | Text candidates, input, images, existing pages, gates, and blockers |
| `generated/CANDY_CODE_ASSET_INVENTORY.md` | PHP, CSS, JavaScript, images, movies, fonts, references, missing files, and duplicate candidates |
| `generated/CANDY_SEO_STATUS.md` | Measured per-page SEO state and issues |

Generation, checking, sitemap synchronization, metadata behavior, and
completion gates are owned by `CANDY_OPERATION_BASICS.md` and the generator
itself; they are not redefined here.

## 6. Non-Current Sources

- NAS `\\192.168.1.3\disk1\FSG_SEO\candy\Backup/` is storage-only and MUST NOT be used as the basis for a current specification.
- Do not use `.git-backups/` or legacy investigation snapshots for current decisions.
- Historical counts in `CANDY_PAGE_SPEC_INDEX.md`, `CANDY_PAGE_CATEGORY_STRUCTURE.md`, and legacy inventory documents are not current values.
- Legacy management documents and dated investigation snapshots are not current
  specification or current-state sources.
