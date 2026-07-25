# AGENTS.md

## 1. Highest-Priority Principles and Prohibitions

- Do not begin any investigation, modification, or execution before reading `AGENTS.md` and every management document applicable to the current task as identified in Section 2, “Management Document Index.” Follow every applicable instruction without exception.
- Before starting the first task of the work session, perform this check once: compare the latest GitHub state with the local environment and report whether they are consistent.
- Low-effort execution is strictly prohibited. Do not reduce necessary reasoning, verification, or work merely to save time or tokens. Use the smallest sufficient scope and deliver the maximum correct result.
- Never report anything as confirmed, investigated, or executed unless it was actually confirmed, investigated, or executed.
- If the user’s latest explicit instruction conflicts with a written instruction, identify and verify the conflict, then follow the user’s latest explicit instruction.
- Do not perform work outside the user-approved scope, even when additional work appears necessary or a related issue is discovered. Report the reason, impact, and required action, and execute it only after receiving user approval.
- Do not perform unnecessary, unrelated, excessive, or duplicative reading, investigation, verification, modification, or other work.
- Maintain exactly one canonical source for each responsibility. Modify the existing canonical source instead of creating duplicate implementations, documents, settings, storage paths, state-management paths, or fallback paths.
- Instruction priority is `AGENTS.md` first, the routed common management document second, and the routed category-specific document third. A lower-level document MAY add only details specific to its own responsibility; it MUST NOT repeat, redefine, weaken, broaden, or override a higher-level rule.
- Common authority, required-document routing, user-response structure, and project-wide prohibitions belong only in this file. Lower-level documents MUST refer to these rules instead of restating them.
- When a lower-level document conflicts with or duplicates a higher-level rule, follow the higher-level rule, STOP before the affected operation, and report the exact conflict. Do not select the lower-level wording merely because it is more detailed.
- Do not Commit, Push, Merge, Rebase, rewrite Git history, deploy to production, or perform database operations without explicit user authorization.

## 2. Management Document Index

Use this section as the sole routing authority for determining which management documents to read. Read only the documents applicable to the current task. Do not expand the reading scope to unrelated specifications, histories, reports, or past materials.

If a required document does not exist, the canonical source is unclear, or documents conflict, do not proceed by assumption. Report the missing document, ambiguity, or conflict before continuing.

Task routes are cumulative. When one task includes more than one operation type, such as page publication plus Git and production work, combine the applicable rows from this table. A lower-level document cannot add another mandatory reading route.

### Reading Order

```text
AGENTS.md
  ↓
Management documents specified in the table below
  ↓
Target implementation, input data, assets, configuration, or environment
```

`C:\Codex\Candy\AGENTS.md` is the only permitted `AGENTS.md` in this project. Do not create `AGENTS.md` or `AGENTS.override.md` in any subfolder.

### Management Document Structure

```text
codex/
├─ README.md
├─ MANAGEMENT_SYSTEM_OVERVIEW.md
├─ project_management/
│  ├─ DOCUMENT_RULES.md
│  ├─ PROJECT_STATUS.md
│  ├─ SAFETY_PROTOCOL.md
│  ├─ TASK_RESERVATIONS.md
│  ├─ CODEX_COMMUNICATION.md
│  └─ TASK_LOG.md
├─ docs/
│  ├─ CANDY_MASTER_DOC_INDEX.md
│  ├─ CANDY_OPERATION_BASICS.md
│  ├─ CANDY_CODE_FILE_STRUCTURE.md
│  ├─ CANDY_HP_STRUCTURE_MAP.md
│  ├─ CANDY_PAGE_GENERATION_GOVERNANCE.md
│  ├─ CANDY_AREA_PAGE_GENERATION_SPEC.md
│  ├─ CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md
│  ├─ CANDY_AREA_105_PAGE_QUEUE.md
│  ├─ CANDY_AREA_IMAGE_CREATION_RUNBOOK.md
│  ├─ CANDY_AREA_IMAGE_CREATION_SPEC.md
│  ├─ CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md
│  ├─ CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md
│  ├─ CANDY_HOTEL_PAGE_GENERATION_SPEC.md
│  ├─ CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md
│  ├─ CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md
│  ├─ CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md
│  ├─ CANDY_HOTEL_IMAGE_CREATION_SPEC.md
│  ├─ CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md
│  ├─ CANDY_BLOG_PAGE_GENERATION_SPEC.md
│  ├─ CANDY_OTHER_PAGES_MANAGEMENT.md
│  ├─ CANDY_SEO_SPEC.md
│  ├─ CANDY_PRODUCTION_MIGRATION_MASTER.md
│  ├─ CANDY_FIX_BACKLOG.md
│  ├─ CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md
│  └─ generated/
│     ├─ CANDY_SITE_PAGE_LEDGER.md
│     ├─ CANDY_UPCOMING_PAGES.md
│     ├─ CANDY_CODE_ASSET_INVENTORY.md
│     └─ CANDY_SEO_STATUS.md
├─ scripts/
└─ data/
```

### Task Routing

| Task | Required management documents |
|---|---|
| Select management documents or perform general website or implementation investigation | `codex/README.md`, `codex/docs/CANDY_MASTER_DOC_INDEX.md`, and `codex/docs/CANDY_OPERATION_BASICS.md` |
| Create or modify the management structure, instructions, specifications, or management documents | `codex/README.md`, `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`, `codex/project_management/DOCUMENT_RULES.md`, and the canonical document being changed |
| Confirm current status, issues, decisions, priorities, or next work | `codex/project_management/PROJECT_STATUS.md`; read `codex/project_management/CODEX_COMMUNICATION.md` only when required |
| Coordinate ownership, reservations, conflict prevention, or handoff among multiple Codex agents | `codex/project_management/TASK_RESERVATIONS.md` and `codex/project_management/CODEX_COMMUNICATION.md` |
| Record task history | `codex/project_management/TASK_LOG.md` and `codex/project_management/TASK_RESERVATIONS.md` |
| Perform Git operations, Commit, or Push | `codex/project_management/DOCUMENT_RULES.md` |
| Delete, move, rename, reorganize in bulk, or restore through Git | `codex/project_management/SAFETY_PROTOCOL.md` and `codex/project_management/TASK_RESERVATIONS.md` |
| Investigate the cause of a defect | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, the applicable category specification, and the applicable generated current-state document; read `codex/docs/CANDY_FIX_BACKLOG.md` only when an unresolved issue is directly relevant |
| Fix a defect or modify existing behavior | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, the applicable category specification, and the applicable generated current-state document |
| Add a new feature, modify shared processing, or change the structure | `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`, and the applicable category specification |
| Produce and publish a standard area page | `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Change the structure of an area page or handle an exception that the standard procedure cannot resolve | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`; also read `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` when publication is included |
| Confirm the area-page production order or candidate pages | `codex/docs/CANDY_AREA_105_PAGE_QUEUE.md`, `codex/docs/generated/CANDY_UPCOMING_PAGES.md`, and `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Create an area image, edit image assets, or perform pre-adoption review | `codex/docs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`, `codex/docs/CANDY_AREA_IMAGE_CREATION_SPEC.md`, and `codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| Replace an approved area image while preserving the existing filename | `codex/docs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`, the target image, and the target page reference; read `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` only when an exception, recovery, or rollback is involved |
| Review, classify, or convert hotel text into the current format | `codex/docs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` and the applicable files under `Text_hotel_data/` |
| Research hotel information or access details and prepare page content | `codex/docs/CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md` and the applicable files under `Text_hotel_data/` |
| Produce and publish a standard hotel page | `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` and the applicable files under `Text_hotel_data/` |
| Change the structure of a hotel page or handle an exception that the standard procedure cannot resolve | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`; also read `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` when publication is included |
| Create or modify hotel images, or perform pre-adoption review | `codex/docs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md`, `codex/docs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, the target text, and the target images |
| Adopt, save, install, replace, or manage the publication status of hotel images | `codex/docs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, the target text, the adopted source image, and the published image; also read `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` when replacing an existing published image under the same filename or performing production work |
| Produce a blog page | `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| Investigate or modify a page that is not an area, hotel, or blog page | `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, `codex/docs/CANDY_OPERATION_BASICS.md`, and the applicable generated current-state document |
| Investigate or modify SEO | `codex/docs/CANDY_SEO_SPEC.md`, `codex/docs/generated/CANDY_SEO_STATUS.md`, the applicable category specification, and the affected pages, indexes, sitemaps, internal links, and images; also read `codex/docs/CANDY_OPERATION_BASICS.md` before making changes |
| Confirm the page structure of the entire website | `codex/docs/CANDY_HP_STRUCTURE_MAP.md` and `codex/docs/generated/CANDY_SITE_PAGE_LEDGER.md` |
| Confirm PHP files, source code, datasets, CSS, JavaScript, images, or reference relationships | `codex/docs/CANDY_CODE_FILE_STRUCTURE.md` and `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Review or update a generated current-state document | `codex/docs/CANDY_MASTER_DOC_INDEX.md` and the applicable file under `codex/docs/generated/` |
| Perform read-only database investigation | `codex/docs/CANDY_OPERATION_BASICS.md`; when applicable, also read `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md` and inspect the relevant PHP, dataset, session, configuration, and external-integration sources |
| Modify the database | `codex/docs/CANDY_OPERATION_BASICS.md`; when applicable, also read `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, the actual database structure, and the rollback procedure |
| Modify Session, Cookie, GET parameters, authentication, payment, or external integrations | `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, and the relevant PHP, dataset, configuration, and external-integration sources |
| Perform production, server, GitHub Actions, deployment, recovery, or rollback work | `codex/docs/CANDY_OPERATION_BASICS.md`, `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`, and the exact `.github/workflows/` and `.github/scripts/` files used by the operation |
| Review logs | `codex/docs/CANDY_OPERATION_BASICS.md` and the target logs; read `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` only when the production incident of July 13, 2026 is directly relevant |
| Perform verification, behavior checks, or completion assessment | The applicable category specification and runbook, the target implementation, and only the generated current-state documents required for verification |

Files under `codex/docs/generated/` are current-state documents generated from actual project files. Do not edit them manually.

`codex/project_management/TASK_LOG.md`, `codex/project_management/CODEX_COMMUNICATION.md`, `codex/docs/CANDY_FIX_BACKLOG.md`, and `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` contain history or supporting information. Do not use any of them alone as the canonical source for determining the current specification, state, or implementation.

## 3. User Communication and Response Rules

- Begin every response with a concise summary that makes the conclusion, rationale, key points, and required action immediately clear. Provide details afterward.
- Use technical terminology only when it is necessary for the detailed explanation. Otherwise, explain the matter in concrete language the user can understand.
- If a response is complex or long, end with a section titled `簡単に要約すると` and restate the conclusion and required action in even simpler words and shorter sentences than the opening summary.
- Address only the scope specified by the user. Do not add unnecessary prefaces, generic discussion, unsolicited proposals, supplemental commentary, or restatements.
- When information is unknown, state `分かりません`. When it has not been verified, state `確認していません`. When execution is not possible, state `申し訳ございません。実行できません。`
- Clearly separate verified facts, unverified matters, and inferences. Do not mix them together.
- When a problem or error exists, clearly identify the affected target, cause, impact, and required action. Do not conceal uncertainty with vague wording.
- If it is clearly faster, more reliable, or more efficient for the user to perform an action than for the AI, ask the user immediately. Do not offload work that the AI can reasonably perform. When requesting user action, state only the exact target, required steps, and expected result.
- Do not add generic warnings about passwords, personal information, or similar matters when they are unrelated to the actual task. Explain only constraints or risks that materially apply to the work.
