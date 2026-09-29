# Candy Management Document Index

Updated: 2026-09-29

Parent document: [`AGENTS.md`](../AGENTS.md)

## 1. Role

This document is the index that manages the official locations, responsibilities, and reference destinations of all currently effective Candy management documents and managed items.

The managed locations below are relative to the `Candy/` workspace root. Markdown links are relative to this file, `management/INDEX.md`.

Common management documents and the `specs/`, `history/`, `scripts/`, `data/`, and `image/` folders are all located under `management/`. The current locations are `management/specs/`, `management/history/`, `management/scripts/`, `management/data/`, and `management/image/`; this index does not prescribe a future relocation.

The highest-authority management document, `AGENTS.md`, is located at the root of the Candy workspace.

### 1.1 Current Folder Structure

```text
Candy/
├─ AGENTS.md
├─ .gitignore
├─ .git/                         Git metadata
├─ .github/
│  ├─ workflows/
│  └─ scripts/
├─ management/
│  ├─ INDEX.md                   Common documents are listed in Section 4
│  ├─ specs/
│  │  └─ generated/
│  ├─ history/
│  │  ├─ records/
│  │  └─ 履歴/
│  ├─ scripts/
│  ├─ data/
│  └─ image/
│     └─ hotel-image-handoff-20260723/
├─ HP/
│  ├─ .well-known/
│  ├─ source/
│  ├─ includefile/
│  │  └─ member/
│  ├─ member/
│  ├─ customers/
│  ├─ docs/
│  ├─ sql/
│  ├─ css/
│  ├─ js/
│  ├─ font/
│  ├─ imgCss/
│  ├─ imgHtml/
│  └─ movie/
├─ Text_area_data/
│  ├─ 分類_20260716_115215/
│  └─ 画像データ/
├─ Text_blog_data/
├─ Text_girl_data/
│  └─ 画像データ/
└─ Text_hotel_data/
   └─ 画像データ/
```

The outline groups descendants by responsibility. Sections 4–8 provide file or directory links for every group. Individual pages, assets, inputs, and history records are reached through their owning directory or dedicated index; this file does not duplicate their inventories or contents.

## 2. Reference Principles

1. Use `INDEX.md` to identify the official management location for the target information.
2. Review the responsible management document.
3. For page and asset specifications, start with the main document and review the supporting runbook and generated current-state document when necessary.
4. For past background, decisions, actions, and results, use `management/HISTORY_LIST.md` to locate the applicable case overview, then review its progress records under `management/history/records/`.
5. If the official management location cannot be identified, do not guess. Review the related management documents, and if it still cannot be identified, ask the user.
6. Use this index for current locations. References to the former `codex/` or `docs/rules/` management layout in linked documents do not define current file locations. A file's listing identifies its responsibility, not verification of its internal references, execution readiness, or production state.

## 3. Rules

### 3.1 Scope of INDEX.md

- Do not record detailed specifications, operating procedures, implementation details, investigation results, verification evidence, or history content in `INDEX.md`. Manage them in the responsible management document or history record.
- Every currently effective management document MUST remain identifiable through `INDEX.md` and reachable through its registered file reference.
- Responsibility descriptions MUST be limited to the information required to determine the correct management location.

### 3.2 INDEX.md Updates When Management Documents Are Created or Changed

- When a new management document is created, register its location, responsibility, and reference destination in `INDEX.md` within the same change.
- If a management document's responsibility, managed scope, location, file name, or reference relationship changes, update the corresponding entry in `INDEX.md` at the same time.
- If only the document content changes and none of the information managed by `INDEX.md` changes, do not modify `INDEX.md`.
- When a management document is renamed, moved, split, merged, deprecated, or deleted, update the affected `INDEX.md` entries and references at the same time.

### 3.3 Responsibility Boundaries Between Management Documents

- Common management documents under `management/` manage information shared across Candy. Page-specific and asset-specific specifications are managed by the responsible documents under `management/specs/`.
- Main documents manage the currently effective page structures, generation requirements, asset rules, and decision criteria for changes.
- Supporting documents manage execution procedures, input preparation, target selection, implementation relationships, and verification methods that support the main document.
- Generated documents under `management/specs/generated/` manage reproducible current-state views of pages, production candidates, code, assets, references, and SEO. They do not replace stable specifications.
- History records under `management/history/` and image-creation records under `management/image/` retain background and evidence. They do not define current specifications. Retained non-current documents are indexed separately in Section 8.

## 4. Common Management Documents

Common management documents are stored under `management/`.

| Management Document | Responsibility |
|---|---|
| [`INDEX.md`](INDEX.md) | Manages the locations, responsibilities, and reference destinations of currently effective management documents and managed items. |
| [`DOCUMENT_RULES.md`](DOCUMENT_RULES.md) | Manages rules for creating, modifying, placing, naming, splitting, merging, referencing, size limits, and post-change verification of management documents. |
| [`HISTORY.md`](HISTORY.md) | Manages case overviews, append-only progress records, types, statuses, recording conditions, and completion criteria. |
| [`HISTORY_LIST.md`](HISTORY_LIST.md) | Manages the case list and links to case overviews. Current status, remaining work, and next action belong to each case's latest progress record. |
| [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) | Manages repository and branch verification, Local/GitHub comparison, Git-operation permissions, management-document publication, and unverifiable-state handling. |
| [`DB_MANAGEMENT.md`](DB_MANAGEMENT.md) | Manages Candy production DB structure, dependencies, READ-ONLY verification, change criteria, backup, and restoration. |
| [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) | Manages Candy production server configuration, connections, permissions, publication and execution environments, scheduled processing, communications, logs, and READ-ONLY checks. |
| [`MANAGEMENT_SYSTEM_OVERVIEW.md`](MANAGEMENT_SYSTEM_OVERVIEW.md) | Describes the management system's purpose, responsibility separation, and design principles; its former-layout descriptions are not the current location index. |
| [`SAFETY_PROTOCOL.md`](SAFETY_PROTOCOL.md) | Manages target classification, protected files, pre-execution checks, and recovery conditions for deletion, movement, and bulk operations. |

## 5. Feature Management Documents

Feature management documents are stored under `management/specs/`.

### 5.1 Management Locations by Feature

| Feature | Main Document | Supporting Document | External-Media Document | Primary Managed Scope |
|---|---|---|---|---|
| Specification Lookup | [`CANDY_MASTER_DOC_INDEX.md`](specs/CANDY_MASTER_DOC_INDEX.md) | None | None | Topic-based lookup of Candy page, asset, operation, and generated-state document responsibilities |
| Existing-Site Operations | [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) | [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md) | None | Existing-page investigation, change-impact checks, validation, generated-state maintenance, and environment verification boundaries |
| Site and Code Structure | [`CANDY_HP_STRUCTURE_MAP.md`](specs/CANDY_HP_STRUCTURE_MAP.md) | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md) | None | Page types, PHP and source HTML relationships, shared processing, CSS, JavaScript, and public assets |
| Common Page Generation | [`CANDY_PAGE_GENERATION_GOVERNANCE.md`](specs/CANDY_PAGE_GENERATION_GOVERNANCE.md) | Category specifications and runbooks below | None | Shared area, blog, and hotel generation rules and related top-page section changes |
| Area Page Management | [`CANDY_AREA_PAGE_GENERATION_SPEC.md`](specs/CANDY_AREA_PAGE_GENERATION_SPEC.md) | [`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md)<br>[`CANDY_AREA_105_PAGE_QUEUE.md`](specs/CANDY_AREA_105_PAGE_QUEUE.md) | None | Area-page structure, source mapping, surrounding-area links, production sequence, fixed target cohort, and validation |
| Area Image Management | [`CANDY_AREA_IMAGE_CREATION_SPEC.md`](specs/CANDY_AREA_IMAGE_CREATION_SPEC.md) | [`CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`](specs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md)<br>[`CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md)<br>[`CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`](specs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md) | None | Two-image creation, visual acceptance, accepted-source storage, public-image lifecycle, and approved same-name replacement |
| Hotel Page Management | [`CANDY_HOTEL_PAGE_GENERATION_SPEC.md`](specs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md) | [`CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md) | None | Hotel-page structure, source routes, production, publication, and validation |
| Hotel Input Preparation | [`CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md) | [`CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md`](specs/CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md) | None | Input-format classification, legacy conversion requirements, hotel research, access information, and page-text preparation |
| Hotel Image Management | [`CANDY_HOTEL_IMAGE_CREATION_SPEC.md`](specs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md) | [`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md) | None | Hotel image-pair creation, acceptance, source retention, local installation, replacement, and publication states |
| Blog Page Management | [`CANDY_BLOG_PAGE_GENERATION_SPEC.md`](specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md) | [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) | None | Blog-page generation, source mapping, manager-recommended girl blocks, and validation |
| Girl Information Management | [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md) | None | Structured girl information, blog-generation use, local-only image retention, and public-image states |
| Other Page Management | [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md) | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) (source-attached technical reference) | None | Pages outside area, hotel, and blog, including dynamic profiles, member pages, authentication, APIs, and related technical references |
| SEO Management | [`CANDY_SEO_SPEC.md`](specs/CANDY_SEO_SPEC.md) | Generated SEO documents in Section 5.2 | None | Common SEO requirements, canonical URLs, metadata, structured data, and per-page verification |
| Full-Population Verification | [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md) | Category specifications and generated documents in Section 5.2 | None | Complete target enumeration, evidence classification, exceptions, incomplete data, and revalidation |
| Production Deployment Management | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) | [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md)<br>[`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) | None | HP deployment, GitHub Actions, protected-entry publication, same-path asset replacement, recovery, and migration-history boundaries |
| Unresolved Issues and Decisions | [`CANDY_FIX_BACKLOG.md`](specs/CANDY_FIX_BACKLOG.md) | Applicable category specifications | None | Unresolved defects, specification decisions, and owner actions that require individual handling |

### 5.2 Supporting Documents for Generated Current State

The generated supporting documents under `management/specs/generated/` are separated by the following responsibilities.

| Management Document | Responsibility |
|---|---|
| [`CANDY_SITE_PAGE_LEDGER.md`](specs/generated/CANDY_SITE_PAGE_LEDGER.md)<br>[`CANDY_SITE_PAGE_LEDGER.tsv`](specs/generated/CANDY_SITE_PAGE_LEDGER.tsv) | Site-page structure summary and complete per-page detail |
| [`CANDY_UPCOMING_PAGES.md`](specs/generated/CANDY_UPCOMING_PAGES.md)<br>[`CANDY_UPCOMING_AREA_PAGES.tsv`](specs/generated/CANDY_UPCOMING_AREA_PAGES.tsv)<br>[`CANDY_UPCOMING_BLOG_PAGES.tsv`](specs/generated/CANDY_UPCOMING_BLOG_PAGES.tsv)<br>[`CANDY_UPCOMING_HOTEL_PAGES.tsv`](specs/generated/CANDY_UPCOMING_HOTEL_PAGES.tsv) | Production-candidate summary and category-specific candidate detail |
| [`CANDY_CODE_ASSET_INVENTORY.md`](specs/generated/CANDY_CODE_ASSET_INVENTORY.md) | Code and asset summaries, image and file references, missing assets, and classified duplicate or publication candidates |
| [`CANDY_CODE_REFERENCE_INVENTORY.md`](specs/generated/CANDY_CODE_REFERENCE_INVENTORY.md) | Detailed public PHP, shared PHP, CSS, and JavaScript reference relationships |
| [`CANDY_SEO_STATUS.md`](specs/generated/CANDY_SEO_STATUS.md)<br>[`CANDY_SEO_STATUS.tsv`](specs/generated/CANDY_SEO_STATUS.tsv) | SEO-state summary and complete per-page SEO detail |

## 6. Scripts

Page-production and management tools are stored under `management/scripts/`. Deployment workflows and their supporting tools are stored under `.github/`. The tables identify their purposes and rule owners, not execution instructions.

### 6.1 Page-Production and Management Tools

| Script | Purpose | Execution Rule Owner |
|---|---|---|
| [`candy-area.cmd`](scripts/candy-area.cmd)<br>[`Invoke-CandyAreaPage.ps1`](scripts/Invoke-CandyAreaPage.ps1) | Entry points for area-page tooling | [`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md), applicable area specification |
| [`candy_area_page.py`](scripts/candy_area_page.py) | Builds and validates area pages, audits inputs, and applies or checks surrounding-area links | [`CANDY_AREA_PAGE_GENERATION_SPEC.md`](specs/CANDY_AREA_PAGE_GENERATION_SPEC.md) |
| [`candy_area_target_gate.py`](scripts/candy_area_target_gate.py) | Selects and validates area-production targets | [`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md) |
| [`candy-python.cmd`](scripts/candy-python.cmd)<br>[`candy_tool.py`](scripts/candy_tool.py) | Compatible installed runtime selection, command routing, help and read-only diagnostics | [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) |
| [`candy_existing_audit.py`](scripts/candy_existing_audit.py) | Full public area/hotel PHP audit independent of Text inputs | [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md) |
| [`candy_image_assets.py`](scripts/candy_image_assets.py) | Image-pair reconciliation and explicitly authorized first local hotel-image installation | [`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md), [`CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md) |
| [`test_candy_tooling_recovery.py`](scripts/test_candy_tooling_recovery.py) | Offline entry-point, image-state, dry-run and limited-write rollback regressions | [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md) |
| [`candy_area_publish.py`](scripts/candy_area_publish.py) | Coordinates area-page production, publication, and resumption | [`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md), [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |
| [`candy_area_image_replace.py`](scripts/candy_area_image_replace.py) | Replaces an approved area-image pair and controlled image URL versions | [`CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`](specs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md) |
| [`candy-hotel.cmd`](scripts/candy-hotel.cmd) | Entry point for hotel-page, input, image, target-selection, and publication tooling | [`CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md), applicable hotel specification |
| [`candy_hotel_page.py`](scripts/candy_hotel_page.py) | Builds and validates hotel pages from source text | [`CANDY_HOTEL_PAGE_GENERATION_SPEC.md`](specs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md) |
| [`candy_hotel_target_gate.py`](scripts/candy_hotel_target_gate.py) | Classifies hotel inputs and validates production eligibility | [`CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md), [`CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md) |
| [`candy_hotel_text_migration.py`](scripts/candy_hotel_text_migration.py) | Checks and converts legacy hotel text | [`CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md) |
| [`candy_hotel_image.py`](scripts/candy_hotel_image.py) | Plans, renders, and validates hotel image candidates | [`CANDY_HOTEL_IMAGE_CREATION_SPEC.md`](specs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md), [`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md) |
| [`candy_hotel_publish.py`](scripts/candy_hotel_publish.py) | Coordinates hotel-page production, publication, and resumption | [`CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md), [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |
| [`candy-blog.cmd`](scripts/candy-blog.cmd)<br>[`candy_blog_page.py`](scripts/candy_blog_page.py) | Entry point, builder, and validator for blog pages | [`CANDY_BLOG_PAGE_GENERATION_SPEC.md`](specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md) |
| [`candy_category_publish.py`](scripts/candy_category_publish.py) | Provides category publication and resumption processing for hotel and blog pages | Applicable category specification, [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md), [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |
| [`candy_girl_information.py`](scripts/candy_girl_information.py) | Imports and validates structured girl information and image-publication state | [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) |
| [`candy_page_common.py`](scripts/candy_page_common.py) | Provides shared page-generation, validation, path, and asset helpers | [`CANDY_PAGE_GENERATION_GOVERNANCE.md`](specs/CANDY_PAGE_GENERATION_GOVERNANCE.md), [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) |
| [`candy-site-state.cmd`](scripts/candy-site-state.cmd)<br>[`candy_site_state.py`](scripts/candy_site_state.py)<br>[`candy_site_state_render.py`](scripts/candy_site_state_render.py) | Collects, renders, writes, and checks generated current-state documents and supports sitemap maintenance | [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) |
| [`Invoke-CandyDocsMaintenance.ps1`](scripts/Invoke-CandyDocsMaintenance.ps1)<br>[`generate_candy_management_docs.py`](scripts/generate_candy_management_docs.py) | Provides maintenance and compatibility entry points for current-state generation | [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) |
| [`audit_candy_management_docs.py`](scripts/audit_candy_management_docs.py) | Management-document audit implementation targeting the former `codex/` layout; not a validator of this current index | [`DOCUMENT_RULES.md`](DOCUMENT_RULES.md), [`INDEX.md`](INDEX.md) |
| [`Invoke-LiveDbRead.ps1`](scripts/Invoke-LiveDbRead.ps1) | Performs bounded READ-ONLY DB checks | [`DB_MANAGEMENT.md`](DB_MANAGEMENT.md) |
| [`Invoke-LiveServerRead.ps1`](scripts/Invoke-LiveServerRead.ps1) | Performs predefined READ-ONLY server checks | [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) |
| [`test_candy_expected_exception_management.py`](scripts/test_candy_expected_exception_management.py)<br>[`test_candy_final_seo_remediation.py`](scripts/test_candy_final_seo_remediation.py) | Checks expected-exception classification and SEO remediation behavior | [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md), [`CANDY_SEO_SPEC.md`](specs/CANDY_SEO_SPEC.md) |
| [`test_candy_girls_invalid_no_behavior.py`](scripts/test_candy_girls_invalid_no_behavior.py)<br>[`test_candy_girls_profile_seo.php`](scripts/test_candy_girls_profile_seo.php)<br>[`test_candy_member_development_isolation.py`](scripts/test_candy_member_development_isolation.py)<br>[`test_candy_movie_iframe_behavior.py`](scripts/test_candy_movie_iframe_behavior.py) | Checks profile input handling, profile SEO, member-development isolation, and movie iframe behavior | [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md), [`CANDY_VERIFICATION_PLAN.md`](specs/CANDY_VERIFICATION_PLAN.md) |

### 6.2 GitHub Workflows and Deployment Tools

| File | Purpose | Management Owner |
|---|---|---|
| [`candy-production-deploy.yml`](../.github/workflows/candy-production-deploy.yml) | Production deployment workflow | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md), [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) |
| [`candy-htaccess-deploy.yml`](../.github/workflows/candy-htaccess-deploy.yml) | Protected `.htaccess` deployment workflow | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md), [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) |
| [`candy-ftp-test.yml`](../.github/workflows/candy-ftp-test.yml) | FTP end-to-end test workflow | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md), [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) |
| [`candy_ftp_deploy.py`](../.github/scripts/candy_ftp_deploy.py) | Deployment planning and FTP file operations | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) |
| [`candy_release_check.py`](../.github/scripts/candy_release_check.py) | Automatic deployment monitoring and production URL verification | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) |
| [`candy_area_image_replacement_guard.py`](../.github/scripts/candy_area_image_replacement_guard.py) | Validation of same-path area-image replacements | [`CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`](specs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md) |
| [`test_candy_ftp_deploy.py`](../.github/scripts/test_candy_ftp_deploy.py)<br>[`test_candy_release_check.py`](../.github/scripts/test_candy_release_check.py) | Deployment and release-check regression tests | [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) |
| [`test_candy_area_image_replace.py`](../.github/scripts/test_candy_area_image_replace.py)<br>[`test_candy_area_image_replacement_guard.py`](../.github/scripts/test_candy_area_image_replacement_guard.py) | Area-image replacement and guard regression tests | [`CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`](specs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md) |
| [`test_candy_site_state_metadata.py`](../.github/scripts/test_candy_site_state_metadata.py) | Generated-state metadata regression tests | [`CANDY_OPERATION_BASICS.md`](specs/CANDY_OPERATION_BASICS.md) |

## 7. Workspace Locations and Managed Items

### 7.1 Workspace and Management Locations

| Item | Responsibility | Management Owner |
|---|---|---|
| [`AGENTS.md`](../AGENTS.md) | Highest-authority project instructions at the Candy workspace root | `AGENTS.md` |
| [`management/`](./) | Common management documents | [`INDEX.md`](INDEX.md), [`DOCUMENT_RULES.md`](DOCUMENT_RULES.md) |
| [`management/specs/`](specs/)<br>[`management/specs/generated/`](specs/generated/) | Specifications and supporting documents; generated current-state views have their own subdirectory | Documents in Section 5; retained non-current sources in Section 8 |
| [`management/history/`](history/) | Case overview files, located through the case list | [`HISTORY.md`](HISTORY.md), [`HISTORY_LIST.md`](HISTORY_LIST.md) |
| [`management/history/records/`](history/records/) | Append-only progress records; the latest record for each case owns its current status, remaining work, and next action | [`HISTORY.md`](HISTORY.md), the corresponding case overview |
| [`management/history/履歴/`](history/履歴/) | Retained legacy registries, case details, task logs, reservations, and investigation materials | Historical evidence only; current history rules are in [`HISTORY.md`](HISTORY.md) |
| [`management/scripts/`](scripts/) | Page, image, data, verification, and maintenance tools | Execution rule owners in Section 6.1 |
| [`management/data/`](data/) | Structured source data used by page-generation tools | Responsible specifications in the following two rows |
| [`management/data/CANDY_AREA_RELATED_LINKS.json`](data/CANDY_AREA_RELATED_LINKS.json) | Source data for creating the area-page surrounding-area section (周辺エリア) | [`CANDY_AREA_PAGE_GENERATION_SPEC.md`](specs/CANDY_AREA_PAGE_GENERATION_SPEC.md) |
| [`management/data/CANDY_GIRL_INFORMATION.json`](data/CANDY_GIRL_INFORMATION.json) | Source data for creating the blog-page manager-recommended girl section (店長おすすめの女の子) | [`CANDY_BLOG_PAGE_GENERATION_SPEC.md`](specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md), [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) |
| [`management/image/`](image/)<br>[`management/image/hotel-image-handoff-20260723/`](image/hotel-image-handoff-20260723/) | Retained hotel-image handoff evidence, including candidate pairs and manifests, source captures, contact sheets, JSON records, checksums, and `.gitattributes` | [`HANDOFF_README.md`](image/hotel-image-handoff-20260723/HANDOFF_README.md); current image rules remain in Section 5 |
| [`HP/`](../HP/) | Website PHP, source HTML, shared code, CSS, JavaScript, images, and other public assets | [`CANDY_HP_STRUCTURE_MAP.md`](specs/CANDY_HP_STRUCTURE_MAP.md), [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md), applicable category specifications |
| [`Text_area_data/`](../Text_area_data/) | Area-page text inputs and accepted source images | [`CANDY_AREA_PAGE_GENERATION_SPEC.md`](specs/CANDY_AREA_PAGE_GENERATION_SPEC.md), [`CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md) |
| [`Text_blog_data/`](../Text_blog_data/) | Blog-page text inputs | [`CANDY_BLOG_PAGE_GENERATION_SPEC.md`](specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md) |
| [`Text_girl_data/`](../Text_girl_data/) | Retained local-only girl image pairs | [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) |
| [`Text_hotel_data/`](../Text_hotel_data/) | Hotel-page text inputs and accepted source images | [`CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md), [`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md) |
| [`.github/`](../.github/)<br>[`.github/workflows/`](../.github/workflows/)<br>[`.github/scripts/`](../.github/scripts/) | GitHub Actions workflows and deployment or release-check scripts | Files and owners in Section 6.2; [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |
| [`.gitignore`](../.gitignore) | Configuration of files and directories excluded from Git management | [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |
| `.git/` | Git metadata for the Candy repository | [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |

### 7.2 Website Implementation, Configuration, and Technical References

| Location | Managed Contents | Management Owner |
|---|---|---|
| [`HP/`](../HP/) | Public PHP entry points, `404.html`, and `favicon.ico`; per-page details are indexed in the page ledger | [`CANDY_HP_STRUCTURE_MAP.md`](specs/CANDY_HP_STRUCTURE_MAP.md), [`CANDY_SITE_PAGE_LEDGER.md`](specs/generated/CANDY_SITE_PAGE_LEDGER.md) |
| [`HP/source/`](../HP/source/) | Page and template HTML plus the shared article stylesheet `style.css` | Category specifications in Section 5, [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md) |
| [`HP/includefile/`](../HP/includefile/) | Shared PHP processing and page datasets | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md), [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md) |
| [`HP/includefile/member/`](../HP/includefile/member/)<br>[`HP/member/`](../HP/member/)<br>[`HP/customers/`](../HP/customers/) | Member shared processing, API and notification entry points, and customer entry files | [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md), [`DB_MANAGEMENT.md`](DB_MANAGEMENT.md), [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md) |
| [`HP/css/`](../HP/css/)<br>[`HP/js/`](../HP/js/) | Stylesheets, JavaScript, related libraries, and PHP-delivered JavaScript | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md) |
| [`HP/imgHtml/`](../HP/imgHtml/)<br>[`HP/imgCss/`](../HP/imgCss/)<br>[`HP/movie/`](../HP/movie/)<br>[`HP/font/`](../HP/font/) | Page images, category and device-specific images, CSS assets, videos, and fonts | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md), category asset documents in Section 5 |
| [`HP/docs/`](../HP/docs/) | Source-attached member technical references listed below; separate from formal management documents | [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md) |
| [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) | Member architecture and technical-reference index | [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md) |
| [`PHASE1_API.md`](../HP/docs/PHASE1_API.md) | Registration, authentication, and foundational member APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`PHASE2_API.md`](../HP/docs/PHASE2_API.md) | Usage-history and girl-evaluation APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`PHASE3_API.md`](../HP/docs/PHASE3_API.md) | Favorite APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`PHASE4_API.md`](../HP/docs/PHASE4_API.md) | Member notice APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`PHASE5_API.md`](../HP/docs/PHASE5_API.md) | Email verification and notification APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`PHASE6_API.md`](../HP/docs/PHASE6_API.md) | Member-profile, telephone, email, and account extension APIs | [`MEMBER_ARCHITECTURE.md`](../HP/docs/MEMBER_ARCHITECTURE.md) |
| [`HP/sql/`](../HP/sql/) | Stored schema changes and seed SQL for member phases, evaluations, notice media, and favorite-schedule notifications; stored files do not establish live DB application status | [`DB_MANAGEMENT.md`](DB_MANAGEMENT.md), [`CANDY_OTHER_PAGES_MANAGEMENT.md`](specs/CANDY_OTHER_PAGES_MANAGEMENT.md) |
| [`HP/.htaccess`](../HP/.htaccess)<br>[`HP/.well-known/`](../HP/.well-known/)<br>[`HP/.well-known/.htaccess`](../HP/.well-known/.htaccess) | Web-server access and routing configuration, including the `.well-known` directory configuration | [`SERVER_MANAGEMENT.md`](SERVER_MANAGEMENT.md), [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) |
| [`HP/robots.txt`](../HP/robots.txt)<br>[`HP/sitemap.xml`](../HP/sitemap.xml) | Crawler directives and sitemap | [`CANDY_SEO_SPEC.md`](specs/CANDY_SEO_SPEC.md) |
| [`HP/.gitignore`](../HP/.gitignore) | HP-specific Git exclusion rules | [`GIT_MANAGEMENT.md`](GIT_MANAGEMENT.md) |

### 7.3 Page Inputs and Retained Source Images

| Location | Managed Contents | Management Owner |
|---|---|---|
| [`Text_area_data/分類_20260716_115215/`](../Text_area_data/分類_20260716_115215/) | Area Text inputs in classification subfolders and the accompanying `分類結果.tsv`; current production eligibility belongs to the target-selection runbook | [`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md), [`CANDY_AREA_PAGE_GENERATION_SPEC.md`](specs/CANDY_AREA_PAGE_GENERATION_SPEC.md) |
| [`Text_area_data/画像データ/`](../Text_area_data/画像データ/) | Retained area-image source assets | [`CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md) |
| [`Text_blog_data/`](../Text_blog_data/) | Blog Text inputs | [`CANDY_BLOG_PAGE_GENERATION_SPEC.md`](specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md) |
| [`Text_girl_data/画像データ/`](../Text_girl_data/画像データ/) | Retained local-only girl image pairs | [`CANDY_GIRL_INFORMATION_MANAGEMENT.md`](specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) |
| [`Text_hotel_data/`](../Text_hotel_data/) | Hotel Text inputs, the input template, and stored classification TSV reports | [`CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md), [`CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`](specs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md) |
| [`Text_hotel_data/画像データ/`](../Text_hotel_data/画像データ/) | Accepted hotel-image source pairs | [`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`](specs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md) |

## 8. Retained Non-Current Documents and Evidence

These entries remain discoverable without treating them as current specifications, current-state inventories, or additional execution rules. Current topic owners are listed in Sections 4–7.

### 8.1 Historical and Completed Sources

| File or Directory | Classification and Responsibility |
|---|---|
| [`CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`](specs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md) | Historical record of the 2026-07-13 incident and decisions |
| [`CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md`](specs/CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md) | Historical area-input classification snapshot; not current production eligibility |
| [`CANDY_PRODUCTION_MIGRATION_INVENTORY.csv`](specs/CANDY_PRODUCTION_MIGRATION_INVENTORY.csv) | Historical migration-comparison data; interpretation is owned by [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) |
| [`HANDOFF_README.md`](image/hotel-image-handoff-20260723/HANDOFF_README.md) | Completed hotel-image handoff and entry point to its retained evidence |
| [`management/history/履歴/`](history/履歴/) | Legacy case registries, history indexes, task logs, reservations, case details, and investigations; the current history entry point is [`HISTORY_LIST.md`](HISTORY_LIST.md) |

### 8.2 Deprecated Compatibility Documents

| Retained File | Current Responsibility Lookup |
|---|---|
| [`CANDY_AI_EXECUTION_DISCIPLINE.md`](specs/CANDY_AI_EXECUTION_DISCIPLINE.md) | [`AGENTS.md`](../AGENTS.md) and [`INDEX.md`](INDEX.md) |
| [`CANDY_CODEX_BACKUP_REMARKS.md`](specs/CANDY_CODEX_BACKUP_REMARKS.md) | [`INDEX.md`](INDEX.md) for current document locations |
| [`CANDY_EXISTING_DOCS_INVENTORY.md`](specs/CANDY_EXISTING_DOCS_INVENTORY.md) | [`INDEX.md`](INDEX.md) for the current document index |
| [`CANDY_FOLDER_ROLE_MAP.md`](specs/CANDY_FOLDER_ROLE_MAP.md) | Section 7 of this index for current folder responsibilities |
| [`CANDY_FULL_FILE_CODE_INVENTORY.md`](specs/CANDY_FULL_FILE_CODE_INVENTORY.md) | [`CANDY_CODE_FILE_STRUCTURE.md`](specs/CANDY_CODE_FILE_STRUCTURE.md) and [`CANDY_CODE_ASSET_INVENTORY.md`](specs/generated/CANDY_CODE_ASSET_INVENTORY.md) |
| [`CANDY_NON_CODE_ASSET_INVENTORY.md`](specs/CANDY_NON_CODE_ASSET_INVENTORY.md) | [`CANDY_CODE_ASSET_INVENTORY.md`](specs/generated/CANDY_CODE_ASSET_INVENTORY.md) |
| [`CANDY_PAGE_CATEGORY_STRUCTURE.md`](specs/CANDY_PAGE_CATEGORY_STRUCTURE.md) | [`CANDY_HP_STRUCTURE_MAP.md`](specs/CANDY_HP_STRUCTURE_MAP.md) |
| [`CANDY_PAGE_SPEC_INDEX.md`](specs/CANDY_PAGE_SPEC_INDEX.md) | [`CANDY_MASTER_DOC_INDEX.md`](specs/CANDY_MASTER_DOC_INDEX.md) |
| [`CANDY_PHASE_RECHECK.md`](specs/CANDY_PHASE_RECHECK.md) | [`HISTORY_LIST.md`](HISTORY_LIST.md) and each case's progress records |
