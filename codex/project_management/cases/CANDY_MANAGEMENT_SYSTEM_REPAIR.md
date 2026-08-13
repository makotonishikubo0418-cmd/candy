# Candy Lower Management-System Repair

- Purpose: Preserve the scope, findings, decisions, and verification for the 2026-08-14 lower management-document repair
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Lower management-document routing, classification, ownership, retained implementation references, history, and audit coverage
- Status / Lifecycle: Complete / Completed
- Source of Truth Responsibility: Case-specific evidence for `CANDY-MGMT-REPAIR-20260814`; permanent rules remain in their routed canonical owners
- Related Documents: [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), [`WORK_ROUTING.md`](../../WORK_ROUTING.md), [`README.md`](../../README.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: `codex/scripts/audit_candy_management_docs.py`; no HP implementation file was changed
- Case ID: `CANDY-MGMT-REPAIR-20260814`

## Objective and Exclusions

Repair only the lower management-document system while preserving the user-restored, read-only repository-root `AGENTS.md` and the existing canonical specifications, runbooks, ledgers, and histories.

The case excludes changes to `AGENTS.md`, HP implementation, database state, server state, DNS, TLS, deployment, production, Stage, Commit, and Push.

## Actions Performed

- Audited the live lower-management hierarchy, Git history, repository-wide Markdown population, formal routes, ownership, and retained implementation references.
- Reconciled lower-rule conflicts with the current highest authority and mapped the requested logical categories to existing owners.
- Adopted the seven existing member references into the formal hierarchy without moving, renaming, duplicating, or treating live state as verified.
- Backfilled the missing case and task-history route for the existing `ee983ec` correction.
- Expanded the audit and reran the required structural and drift observations.

## Verified Findings

- The former audit population omitted seven tracked member implementation-reference Markdown files under `HP/docs/`.
- Those seven files contain intended architecture and Phase 1 through Phase 6 contracts but did not have a formal owner, direct-open identification, complete parent-child links, or repository-wide audit coverage.
- Two lower rules conflicted with the current highest-authority continuation rules for unavailable GitHub verification and unavailable management documents.
- Commit `ee983ecefc158f45a3eedfb0dfa3157a754b39b0` changed `HP/includefile/dataset_base.php` after the prior management rebuild but had no case or task-history route.

## Decisions and Repair

- Preserve the existing management hierarchy and route the user's requested logical classifications through existing owners; do not create parallel consultation, defect, change, or system-information ledgers.
- Retain the seven `HP/docs/` files in place as implementation references owned by `CANDY_OTHER_PAGES_MANAGEMENT.md`. They are not case records or canonical proof of live environment state.
- Keep `MEMBER_ARCHITECTURE.md` as the navigation parent for the six phase references without treating it as a case parent.
- Extend the management audit to every repository Markdown file and compare both formal trees with the filesystem.
- Record the existing `ee983ec` correction as an atomic historical case and task result without changing its implementation.

## Changed Files

The lower-management repair changed exactly these 22 files:

- `docs/rules/GIT_RULES.md`
- `codex/WORK_ROUTING.md`
- `codex/README.md`
- `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`
- `codex/docs/CANDY_MASTER_DOC_INDEX.md`
- `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`
- `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`
- `codex/project_management/DOCUMENT_RULES.md`
- `codex/project_management/PROJECT_STATUS.md`
- `codex/project_management/CASE_REGISTRY.md`
- `codex/project_management/TASK_LOG.md`
- `codex/project_management/task_history/TASK_LOG_2026_08.md`
- `codex/project_management/cases/CANDY_MANAGEMENT_SYSTEM_REBUILD.md`
- `codex/project_management/cases/CANDY_MANAGEMENT_SYSTEM_REPAIR.md`
- `codex/scripts/audit_candy_management_docs.py`
- `HP/docs/MEMBER_ARCHITECTURE.md`
- `HP/docs/PHASE1_API.md`
- `HP/docs/PHASE2_API.md`
- `HP/docs/PHASE3_API.md`
- `HP/docs/PHASE4_API.md`
- `HP/docs/PHASE5_API.md`
- `HP/docs/PHASE6_API.md`

Repository-root `AGENTS.md` is excluded from this list and from the repair. Its preexisting working-tree difference is the user's manual restoration and was neither edited nor placed in Git state by this case.

## Verification and Boundaries

- The final repository-wide management audit result and exact counts are recorded in the 2026-08 task-history row for this case.
- The global site-state observation continues to report the six preexisting member/privacy findings recorded in the task history; this management-only repair does not claim to fix them.
- Database migrations, runtime enablement, scheduler state, server placement, DNS, TLS, deployment, browser behavior, and production behavior remain `UNVERIFIED`.
- `AGENTS.md` preservation is verified separately by its starting and ending byte count and SHA-256.

## Completion

The requested classifications are routed through the existing formal owners, every retained Markdown file is connected to both formal trees, the orphan audit blind spot is removed, and the repair is recorded without creating a parallel management standard.

- Remaining work for this case: None.
- Next action for this case: None. Any future implementation, database, server, DNS, TLS, deployment, or production work requires its own routed case and authorization.
