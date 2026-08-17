# Candy Defect and Response History (不具合・対応履歴)

- Purpose: Route recorded defects and their responses to canonical individual details
- Parent / Owner: [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Scope: Defect and Response History (不具合・対応履歴) category routing only
- Status / Lifecycle: Canonical Index / Historical Evidence
- Source of Truth Responsibility: Sole defect-response category index; linked case, backlog, and task documents retain their existing responsibilities
- Related Documents: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md), [`CANDY_FIX_BACKLOG.md`](../../docs/CANDY_FIX_BACKLOG.md), [`TASK_LOG.md`](../TASK_LOG.md), and [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Related Implementation Files: None; linked individual details identify their own scope
- Updated: 2026-08-16

## 1. Recording Rule

Use this index to reach one individual detail for each defect case. Unresolved owner decisions remain in `CANDY_FIX_BACKLOG.md`; completed execution remains in `TASK_LOG.md`; current cross-case blockers remain in `PROJECT_STATUS.md`. Do not duplicate those contents here.

## 2. Individual Details (個別詳細)

| Case ID | Subject | Individual detail owner | Boundary |
|---|---|---|---|
| CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817 | Isolate development-only member pages from public navigation and indexing | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817`) | Local implementation and verification complete; publication, deployment, and production confirmation remain separate |
| CANDY-INTERNAL-PATH-ACCESS-20260817 | Restrict direct HTTP access to source HTML and server-side include files | [`CANDY_INTERNAL_PATH_ACCESS_CONTROL.md`](../cases/CANDY_INTERNAL_PATH_ACCESS_CONTROL.md) | Completed through separated GitHub publication, protected one-file deployment, SHA-256 verification, and production HTTP validation |
| CANDY-GIRLS-INVALID-NO-20260816 | A nonexistent girls number returns HTTP 200 and renders another woman's profile | [`CANDY_GIRLS_INVALID_NO_BEHAVIOR.md`](../cases/CANDY_GIRLS_INVALID_NO_BEHAVIOR.md) | Active separate system URL-behavior problem; no remedy adopted |
| CANDY-BREADCRUMB-CLOSURE-20260815 | Close all remaining visible breadcrumb and BreadcrumbList inconsistencies | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260815-BREADCRUMB-CLOSURE-001`) | Completed execution detail |
| CANDY-BREADCRUMB-SYNC-20260815 | Synchronize six detail-page BreadcrumbList names with visible breadcrumbs | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260815-BREADCRUMB-SYNC-001`) | Completed execution detail |
| CANDY-GIRLS-SEO-20260815 | Correct dynamic girls-profile SEO and structured data | [`CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md`](../cases/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) | Five approved corrections complete and audited locally; live verification and publication remain pending in the case detail |
| CANDY-TEST-PATH-20260813 | Correct the group-test template paths in `dataset_base.php` | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260813-DATASET-BASE-GROUP-TEST-PATH-001`) | Completed static correction detail; runtime remains unverified |
| CANDY-INCIDENT-20260713 | Preserve the 2026-07-13 incident context and prevention evidence | [`CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`](../../docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md) | Historical incident and response evidence |
