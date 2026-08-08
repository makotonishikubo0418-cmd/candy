# Candy Work Routing

## 5. Work Execution Method
- Scope: all work under `C:\Codex\FSG\Candy`.
- Higher authority: `C:\Codex\FSG\Candy\AGENTS.md`.
- For repository-related Candy work, review `docs/rules/GIT_RULES.md` and apply the initial Git-verification timing defined by root `AGENTS.md`: exactly once for each directly involved repository on the first repository-related work of the day, with another verification only for the exceptions stated there.
- `### 5.1 Management Document Structure` and `### 5.2 Work Routing` together constitute the sole `Management Document Index` used to route and perform work.
- Read information in the following order: `C:\Codex\FSG\Candy\AGENTS.md > codex/WORK_ROUTING.md > the management documents specified by ### 5.2 Work Routing > the target implementation, data, configuration, and environment`.
- When performing work, do not expand the reading scope to unrelated management documents, historical materials, records, reports, or reference materials. Review only the relevant portions of the management documents that apply to the task, comply with them, and proceed accordingly.
- Select routing rows from the user's objective, not from every internal step performed by an integrated runbook. When a routed runbook explicitly owns target selection, validation, generation, or another internal step, that step does not activate a separate confirmation or investigation route unless the user requested it as a separate objective or the runbook explicitly routes an exception there.
- When a canonical script provides a bounded command for selecting or checking one target, use that command instead of printing or reviewing a full generated current-state document. Open the generated document only when the selected route or a verified exception requires its contents.
- If no management document applies to the instructed work, the applicable document is unknown, or conflicting content prevents the required action from being determined, do not proceed based on assumptions. Perform the work only after obtaining the user’s approval.
- Add, consolidate, relocate, rename, or retire a management document only after the user individually approves the change. After execution, always update `### 5.1 Management Document Structure` and `### 5.2 Work Routing` to reflect the current state.

### 5.1 Management Document Structure

C:\Codex\FSG\Candy
├─ AGENTS.md
├─ docs/
│  └─ rules/
│     └─ GIT_RULES.md
└─ codex/
   ├─ WORK_ROUTING.md
   ├─ README.md
   ├─ MANAGEMENT_SYSTEM_OVERVIEW.md
   ├─ project_management/
   │  ├─ DOCUMENT_RULES.md
   │  ├─ PROJECT_STATUS.md
   │  ├─ SAFETY_PROTOCOL.md
   │  ├─ TASK_RESERVATIONS.md
   │  ├─ CODEX_COMMUNICATION.md
   │  └─ TASK_LOG.md
   └─ docs/
      ├─ CANDY_MASTER_DOC_INDEX.md
      ├─ CANDY_OPERATION_BASICS.md
      ├─ CANDY_CODE_FILE_STRUCTURE.md
      ├─ CANDY_HP_STRUCTURE_MAP.md
      ├─ CANDY_PAGE_GENERATION_GOVERNANCE.md
      ├─ CANDY_AREA_PAGE_GENERATION_SPEC.md
      ├─ CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md
      ├─ CANDY_AREA_105_PAGE_QUEUE.md
      ├─ CANDY_AREA_IMAGE_CREATION_RUNBOOK.md
      ├─ CANDY_AREA_IMAGE_CREATION_SPEC.md
      ├─ CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md
      ├─ CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md
      ├─ CANDY_HOTEL_PAGE_GENERATION_SPEC.md
      ├─ CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md
      ├─ CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md
      ├─ CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md
      ├─ CANDY_HOTEL_IMAGE_CREATION_SPEC.md
      ├─ CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md
      ├─ CANDY_BLOG_PAGE_GENERATION_SPEC.md
      ├─ CANDY_OTHER_PAGES_MANAGEMENT.md
      ├─ CANDY_SEO_SPEC.md
      ├─ CANDY_VERIFICATION_PLAN.md
      ├─ CANDY_PRODUCTION_MIGRATION_MASTER.md
      ├─ CANDY_FIX_BACKLOG.md
      ├─ CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md
      └─ generated/
         ├─ CANDY_SITE_PAGE_LEDGER.md
         ├─ CANDY_UPCOMING_PAGES.md
         ├─ CANDY_CODE_ASSET_INVENTORY.md
         └─ CANDY_SEO_STATUS.md

### 5.2 Work Routing

| Task | Required management documents |
|---|---|
| Select management documents or perform general website or implementation investigation | `codex/README.md`, `codex/docs/CANDY_MASTER_DOC_INDEX.md`, and `codex/docs/CANDY_OPERATION_BASICS.md` |
| Audit management-document structure, routing, responsibilities, consistency, or drift | `codex/README.md`, `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`, `codex/project_management/DOCUMENT_RULES.md`, `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, and the management documents being audited |
| Create or modify the management structure, instructions, specifications, or management documents | `codex/README.md`, `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`, `codex/project_management/DOCUMENT_RULES.md`, and the canonical document being changed |
| Confirm current status, issues, decisions, priorities, or next work | `codex/project_management/PROJECT_STATUS.md`; read `codex/project_management/CODEX_COMMUNICATION.md` only when required |
| Coordinate ownership, reservations, conflict prevention, or handoff among multiple Codex agents | `codex/project_management/TASK_RESERVATIONS.md` and `codex/project_management/CODEX_COMMUNICATION.md` |
| Record task history | `codex/project_management/TASK_LOG.md` |
| Perform Git operations, select or change a branch, Commit, or Push | `docs/rules/GIT_RULES.md` |
| Stage, Commit, or Push management-document changes | `docs/rules/GIT_RULES.md` and `codex/project_management/DOCUMENT_RULES.md` |
| Delete, move, rename, reorganize in bulk, or restore through Git | `codex/project_management/SAFETY_PROTOCOL.md` and `codex/project_management/TASK_RESERVATIONS.md` |
| Investigate the cause of a defect | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, the applicable category specification, and the applicable generated current-state document; read `codex/docs/CANDY_FIX_BACKLOG.md` only when an unresolved issue is directly relevant |
| Fix a defect or modify existing behavior | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, the applicable category specification, and the applicable generated current-state document |
| Add a new feature, modify shared processing, or change the structure | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`, and the applicable category specification |
| Produce and publish a standard area page | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Change the structure of an area page or handle an exception that the standard procedure cannot resolve | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`; also read `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` when publication is included |
| Explicitly list, compare, or explain the area-page production order or candidate pages | `codex/docs/CANDY_AREA_105_PAGE_QUEUE.md`, `codex/docs/generated/CANDY_UPCOMING_PAGES.md`, and `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`; normal automated target selection inside `publish-next` remains under the standard area-page production route and does not activate this row |
| Create an area image, edit image assets, or perform pre-adoption review | `codex/docs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`, `codex/docs/CANDY_AREA_IMAGE_CREATION_SPEC.md`, and `codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| Replace an approved area image while preserving the existing filename | `codex/docs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`, the target image, and the target page reference; read `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` only when an exception, recovery, or rollback is involved |
| Review, classify, or convert hotel text into the current format | `codex/docs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` and the applicable files under `Text_hotel_data/` |
| Confirm the hotel-page production order or candidate pages | `codex/docs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`, `codex/docs/generated/CANDY_UPCOMING_PAGES.md`, and `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| Research hotel information or access details and prepare page content | `codex/docs/CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md` and the applicable files under `Text_hotel_data/` |
| Produce and publish a standard hotel page | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`, `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`, and the applicable files under `Text_hotel_data/` |
| Change the structure of a hotel page or handle an exception that the standard procedure cannot resolve | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`; also read `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` when publication is included |
| Create or modify hotel images, or perform pre-adoption review | `codex/docs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md`, `codex/docs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, the target text, and the target images |
| Adopt, save, install, replace, or manage the publication status of hotel images | `codex/docs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, the target text, the adopted source image, and the published image; also read `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` when replacing an existing published image under the same filename or performing production work |
| Produce a blog page | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| Investigate or modify a page that is not an area, hotel, or blog page | `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, and the applicable generated current-state document; when the target is an area, blog, or hotel section in `HP/source/index.html`, also read `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` |
| Investigate or modify SEO | `codex/docs/CANDY_SEO_SPEC.md`, `codex/docs/generated/CANDY_SEO_STATUS.md`, the applicable category specification, and the affected pages, indexes, sitemaps, internal links, and images; also read `codex/docs/CANDY_OPERATION_BASICS.md` before making changes |
| Confirm the page structure of the entire website | `codex/docs/CANDY_HP_STRUCTURE_MAP.md` and `codex/docs/generated/CANDY_SITE_PAGE_LEDGER.md` |
| Confirm PHP files, source code, datasets, CSS, JavaScript, images, or reference relationships | `codex/docs/CANDY_CODE_FILE_STRUCTURE.md` and `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Review a generated current-state document | `codex/docs/CANDY_MASTER_DOC_INDEX.md` and the applicable file under `codex/docs/generated/` |
| Regenerate or update a generated current-state document | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/project_management/DOCUMENT_RULES.md`, the applicable file under `codex/docs/generated/`, and its generator |
| Perform read-only database investigation | `codex/docs/CANDY_OPERATION_BASICS.md`; when applicable, also read `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md` and inspect the relevant PHP, dataset, session, configuration, and external-integration sources |
| Modify the database | `codex/docs/CANDY_OPERATION_BASICS.md`; when applicable, also read `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, the actual database structure, and the rollback procedure |
| Modify Session, Cookie, GET parameters, authentication, payment, or external integrations | `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, and the relevant PHP, dataset, configuration, and external-integration sources |
| Perform production, server, GitHub Actions, deployment, recovery, or rollback work | `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`, and the exact `.github/workflows/` and `.github/scripts/` files used by the operation |
| Review logs | `codex/docs/CANDY_OPERATION_BASICS.md` and the target logs; read `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` only when the production incident of July 13, 2026 is directly relevant |
| Perform target-specific verification, behavior checks, or completion assessment | The applicable category specification and runbook, the target implementation, and only the generated current-state documents required for verification |
| Perform full-population verification of HP, generation source data, links, assets, test, or production | `codex/docs/CANDY_VERIFICATION_PLAN.md`, the target population, implementation, or environment, and only the generated current-state documents required for verification |

Files under `codex/docs/generated/` are current-state documents generated from actual project files. Do not edit them manually.

`codex/project_management/TASK_LOG.md`, `codex/project_management/CODEX_COMMUNICATION.md`, `codex/docs/CANDY_FIX_BACKLOG.md`, and `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` contain history or supporting information. Do not use any of them alone as the canonical source for determining the current specification, state, or implementation.
