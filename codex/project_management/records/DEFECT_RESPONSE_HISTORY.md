# Candy Defect and Response History (不具合・対応履歴)

- Purpose: Route recorded defects and their responses to canonical individual details
- Parent / Owner: [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Scope: Defect and Response History (不具合・対応履歴) category routing only
- Status / Lifecycle: Canonical Index / Historical Evidence
- Source of Truth Responsibility: Sole defect-response category index; linked case, backlog, and task documents retain their existing responsibilities
- Related Documents: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md), [`CANDY_FIX_BACKLOG.md`](../../docs/CANDY_FIX_BACKLOG.md), [`TASK_LOG.md`](../TASK_LOG.md), and [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Related Implementation Files: None; linked individual details identify their own scope
- Updated: 2026-08-19

## 1. Recording Rule

Use this index to reach one individual detail for each defect case. Unresolved owner decisions remain in `CANDY_FIX_BACKLOG.md`; completed execution remains in `TASK_LOG.md`; current cross-case blockers remain in `PROJECT_STATUS.md`. Do not duplicate those contents here.

## 2. Individual Details (個別詳細)

| Case ID | Subject | Individual detail owner | Boundary |
|---|---|---|---|
| CANDY-FINAL-SEO-REMEDIATION-20260819 | Correct confirmed final-audit SEO, link, image, debug, and audit-tool defects | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-FINAL-SEO-REMEDIATION-20260819`) | Local implementation and validation complete; Commit, Push, deployment, production HTTP, database, access logs, and Search Console remain separate |
| CANDY-UNUSED-ASSET-BROKEN-LINK-FIX-20260819 | Remove four unused public assets and correct two broken-link sources | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-UNUSED-ASSET-BROKEN-LINK-FIX-20260819`) | Completed by Commit `5a4892539b1e8c1b0ce1dd4ea30542c4ab1d3cc8`; Actions run `32207751580` uploaded five files and deleted four files; production HTTP and DOM checks passed |
| CANDY-EXPECTED-EXCEPTION-CLASSIFICATION-20260819 | Separate intentional exceptions from actionable problems | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-EXPECTED-EXCEPTION-CLASSIFICATION-20260819`) | Local management and generated-state correction complete; Commit and Push remain separate; no HP or production change was made |
| CANDY-MOVIE-IFRAME-INVALID-INPUT-20260819 | Return 404 for invalid or non-playable movie helper requests | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-MOVIE-IFRAME-INVALID-INPUT-20260819`) | Local implementation and validation complete; Commit, Push, deployment, and production verification remain separate |
| CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817 | Isolate development-only member pages from public navigation and indexing | [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) (`CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817`) | Implementation Commit `dd9588135158bb3ecba0e248ca602d5956a68bf1` is GitHub-published; deployment and production confirmation remain separate |
| CANDY-INTERNAL-PATH-ACCESS-20260817 | Restrict direct HTTP access to source HTML and server-side include files | [`CANDY_INTERNAL_PATH_ACCESS_CONTROL.md`](../cases/CANDY_INTERNAL_PATH_ACCESS_CONTROL.md) | Completed through separated GitHub publication, protected one-file deployment, SHA-256 verification, and production HTTP validation |
| CANDY-GIRLS-INVALID-NO-20260816 | Correct missing and invalid girls-number responses | [`CANDY_GIRLS_INVALID_NO_BEHAVIOR.md`](../cases/CANDY_GIRLS_INVALID_NO_BEHAVIOR.md) | Local implementation and validation complete; Commit, Push, deployment, and production verification remain separate |
| CANDY-BREADCRUMB-CLOSURE-20260815 | Close all remaining visible breadcrumb and BreadcrumbList inconsistencies | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260815-BREADCRUMB-CLOSURE-001`) | Completed execution detail |
| CANDY-BREADCRUMB-SYNC-20260815 | Synchronize six detail-page BreadcrumbList names with visible breadcrumbs | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260815-BREADCRUMB-SYNC-001`) | Completed execution detail |
| CANDY-GIRLS-SEO-20260815 | Correct dynamic girls-profile SEO and structured data | [`CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md`](../cases/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) | Five approved corrections complete and audited locally; live verification and publication remain pending in the case detail |
| CANDY-TEST-PATH-20260813 | Correct the group-test template paths in `dataset_base.php` | [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md) (`TASK-20260813-DATASET-BASE-GROUP-TEST-PATH-001`) | Completed static correction detail; runtime remains unverified |
| CANDY-INCIDENT-20260713 | Preserve the 2026-07-13 incident context and prevention evidence | [`CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`](../../docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md) | Historical incident and response evidence |
