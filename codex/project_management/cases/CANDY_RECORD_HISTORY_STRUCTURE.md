# Candy Record-History Structure

- Purpose: Implement the user-required management and record-history hierarchy with three categories and reachable individual details
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Record-history routing, category indexes, individual-detail ownership, management rules and trees, catalog validation, and the current invalid-number defect detail
- Status / Lifecycle: Complete / Completed
- Source of Truth Responsibility: Case-specific design, implementation scope, verification, and completion evidence for `CANDY-RECORD-HISTORY-20260816`
- Related Documents: [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), [`WORK_ROUTING.md`](../../WORK_ROUTING.md), [`README.md`](../../README.md), [`MANAGEMENT_SYSTEM_OVERVIEW.md`](../../MANAGEMENT_SYSTEM_OVERVIEW.md), [`CASE_HISTORY.md`](../records/CASE_HISTORY.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: Management Markdown and `codex/scripts/audit_candy_management_docs.py`; no HP runtime implementation
- Case ID: `CANDY-RECORD-HISTORY-20260816`
- Updated: 2026-08-16

## 1. Objective

Establish this required logical hierarchy:

```text
Management and record history
├─ Consultation History (相談履歴)
│  └─ Individual Detail (個別相談内容)
├─ Defect and Response History (不具合・対応履歴)
│  └─ Individual Detail (個別詳細)
└─ Modification, Addition, and New-Creation History (改修・追加・新規作成等)
   └─ Individual Detail (個別詳細)
```

## 2. Architecture Decision

- `records/CASE_HISTORY.md` is the single category-history entrypoint.
- The three category indexes classify every registered case exactly once and route to its canonical individual detail.
- `CASE_REGISTRY.md` remains the lifecycle and case-parent map; it is not duplicated by the category indexes.
- Existing case parents, dated task records, and retained historical evidence remain individual-detail owners when they already contain the required detail.
- Create a new individual detail only when no complete existing detail owner exists.
- Category indexes contain routing metadata only. They do not duplicate specifications, current generated state, findings, or execution evidence.

## 3. Authorized Scope

- Add the history entrypoint and three category indexes under `codex/project_management/records/`.
- Add the individual detail for `CANDY-GIRLS-INVALID-NO-20260816`.
- Synchronize `README.md`, `WORK_ROUTING.md`, `MANAGEMENT_SYSTEM_OVERVIEW.md`, `DOCUMENT_RULES.md`, `CASE_REGISTRY.md`, affected task history, and the management audit.
- Validate that every registered case appears in exactly one category and every detail link resolves.

## 4. Exclusions

Do not change HP runtime code, database state, public URLs, response behavior, SEO output, generated site-state documents, deployment, production, Git branch, Stage, Commit, or Push.

## 5. Changed Files

The completed management-only change added or updated:

- `codex/project_management/records/CASE_HISTORY.md`
- `codex/project_management/records/CONSULTATION_HISTORY.md`
- `codex/project_management/records/DEFECT_RESPONSE_HISTORY.md`
- `codex/project_management/records/CHANGE_HISTORY.md`
- `codex/project_management/cases/CANDY_GIRLS_INVALID_NO_BEHAVIOR.md`
- `codex/project_management/cases/CANDY_RECORD_HISTORY_STRUCTURE.md`
- `codex/project_management/CASE_REGISTRY.md`
- `codex/project_management/DOCUMENT_RULES.md`
- `codex/project_management/TASK_LOG.md`
- `codex/project_management/task_history/TASK_LOG_2026_08.md`
- `codex/project_management/cases/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md`
- `codex/README.md`
- `codex/WORK_ROUTING.md`
- `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`
- `codex/docs/CANDY_FIX_BACKLOG.md`
- `codex/scripts/audit_candy_management_docs.py`

## 6. Verification

- Python syntax validation for the management audit passed.
- The management audit passed 68 formal Markdown files, seven source-attached technical references, five generated sidecars, and matching 73-file README/router trees.
- All 13 registered cases appear exactly once: consultation 3, defect-response 6, and change 4.
- Missing or duplicate category IDs, unregistered category IDs, missing individual-detail links, broken Markdown links, invalid tables, duplicate source-of-truth declarations, parent failures, unclassified Markdown, and over-limit files are all zero.
- `candy-site-state audit` returned `AUDIT=OK`.
- The full site-state check retains only the six preexisting member/privacy findings and introduced no new finding from this management-only change.
- `git diff --check` passed.

## 7. Completion State

The required three-category route and individual-detail structure are complete. No HP runtime code, database, public URL, response behavior, SEO output, generated current-state document, deployment, production state, Git branch, Stage, Commit, or Push was changed.
