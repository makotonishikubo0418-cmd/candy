# Candy Lower Management-System Repair

- Purpose: Preserve the scope, findings, decisions, and verification for the 2026-08-14 lower management-document repair
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Lower management-document routing, classification, ownership, source-attached technical references, history, and audit coverage
- Status / Lifecycle: Complete / Completed
- Source of Truth Responsibility: Case-specific evidence for `CANDY-MGMT-REPAIR-20260814`; permanent rules remain in their routed canonical owners
- Related Documents: [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), [`WORK_ROUTING.md`](../../WORK_ROUTING.md), [`README.md`](../../README.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: `codex/scripts/audit_candy_management_docs.py` and the seven source-attached technical Markdown files under `HP/docs/`; no HP implementation file was changed
- Case ID: `CANDY-MGMT-REPAIR-20260814`
- Updated: 2026-08-20

## Objective and Exclusions

Repair only the lower management-document system while preserving the user-restored, read-only repository-root `AGENTS.md` and the existing canonical specifications, runbooks, ledgers, and histories.

The case excludes changes to `AGENTS.md`, HP implementation, database state, server state, DNS, TLS, deployment, production, Stage, Commit, and Push.

## Actions Performed

- Audited the live lower-management hierarchy, Git history, repository-wide Markdown population, formal routes, ownership, and source-attached technical references.
- Reconciled lower-rule conflicts with the current highest authority and mapped the requested logical categories to existing owners.
- Classified the seven existing member technical references outside the formal management hierarchy without moving, renaming, duplicating, or treating live state as verified.
- Backfilled the missing case and task-history route for the existing `ee983ec` correction.
- Aligned the audit with the current direct-open rule, separated formal and source-attached Markdown populations, and reran the required structural and drift observations.

## Verified Findings

- The former audit population omitted seven tracked member implementation-reference Markdown files under `HP/docs/`.
- Those seven files contain intended architecture and Phase 1 through Phase 6 contracts but did not have a formal owner, direct-open identification, complete parent-child links, or repository-wide audit coverage.
- Two lower rules conflicted with the current highest-authority continuation rules for unavailable GitHub verification and unavailable management documents.
- Commit `ee983ecefc158f45a3eedfb0dfa3157a754b39b0` changed `HP/includefile/dataset_base.php` after the prior management rebuild but had no case or task-history route.

## Decisions and Repair

- Preserve the existing management hierarchy and route the user's requested logical classifications through existing owners; do not create parallel consultation, defect, change, or system-information ledgers.
- Retain the seven `HP/docs/` files in place as non-management source-attached technical references discoverable from the canonical owner `CANDY_OTHER_PAGES_MANAGEMENT.md`. They are not case records, formal management documents, or canonical proof of live environment state.
- Keep `MEMBER_ARCHITECTURE.md` as the technical index for the six Phase children without treating it as a case parent or management source.
- Audit every repository Markdown file while comparing only the formal management population and generated sidecars with both formal trees; report any other non-formal Markdown as unclassified.
- Record the existing `ee983ec` correction as an atomic historical case and task result without changing its implementation.

## Earlier Repair-Pass Changed Files

The earlier repair pass, later published as Commit `6a66137`, changed exactly these 22 files:

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

Repository-root `AGENTS.md` was excluded from that pass. It was subsequently published in a separate user-authorized operation as Commit `d327d93`; the final review below did not edit it or change its Git state.

## Final Review Correction

The user's final-review instruction identified the earlier common-header standardization as inappropriate for normal preexisting documents. Git established `9640d05` as the parent immediately before Commit `7464063`, where the uniform metadata and lifecycle blocks were introduced.

- Removed only those common header additions from 34 normal preexisting management documents while preserving their original titles, introductions, purposes, status lines, bodies, and later valid changes.
- Updated `DOCUMENT_RULES.md` so new or newly adopted material remains identifiable, while already clear normal documents do not receive uniform metadata or lifecycle lines merely for standardization.
- Added a minimal historical-evidence link from `CANDY_PRODUCTION_MIGRATION_MASTER.md` to the previously unlinked `CANDY_PRODUCTION_MIGRATION_INVENTORY.csv`; the CSV itself was not changed. Its static contents were inspected, but the operational and server state represented by all 1,764 rows remains unverified and unreviewed (`ServerVerified=False`, `ReviewStatus=NOT_REVIEWED`, and empty `Notes`).
- Preserved the requested logical routes for consultations, defects, modifications, and system or operational information without creating category-wide duplicate ledgers.
- Did not modify `AGENTS.md`, any program, generated output, HP source-attached document, implementation file, database, server, deployment, production state, Stage, Commit, Push, or branch.
- The owner subsequently approved both recorded candidates. The seven `HP/docs` files were reclassified as non-management source-attached technical references, and the management-audit program was aligned to the resulting formal and non-management populations.

### Authorized Execution A: Reclassify the Seven `HP/docs` Files

- Target files: `HP/docs/MEMBER_ARCHITECTURE.md` and `HP/docs/PHASE1_API.md` through `HP/docs/PHASE6_API.md`; dependent management references in `codex/README.md`, `codex/WORK_ROUTING.md`, `codex/project_management/DOCUMENT_RULES.md`, `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, `codex/docs/CANDY_MASTER_DOC_INDEX.md`, `codex/project_management/PROJECT_STATUS.md`, `codex/project_management/CASE_REGISTRY.md`, this case parent, and the related task record; the audit program is a separate candidate below.
- Current content: the seven files retain their member architecture, database, authentication, API, cron, and deployment-oriented technical bodies. Each now has a concise source-attached technical-reference warning instead of formal Parent, Lifecycle, Source-of-Truth, case, or formal-tree metadata.
- Former problem: Git showed that the files existed outside the formal management tree before the earlier repair and were promoted by the later common treatment of repository Markdown. Treating their extension as sufficient for formal-management status created overlapping responsibility with the existing canonical member, database, and deployment owners.
- Processing performed: after explicit owner authorization, classified them as non-management source-attached technical references, retained their technical bodies and unverified-state boundaries, removed them from both formal trees, and kept one discoverable link from `CANDY_OTHER_PAGES_MANAGEMENT.md` to `MEMBER_ARCHITECTURE.md`; that technical index links to the six Phase children.
- Current destination: unchanged physical paths under `HP/docs/`, outside the formal management-document population, with canonical member-page and operational responsibility remaining in `CANDY_OTHER_PAGES_MANAGEMENT.md` and the other routed owners.
- Information preserved: all useful architecture/API/cron/deployment observations, the distinction between static reference content and live state, the current member-page responsibility and verified implementation-path facts in `CANDY_OTHER_PAGES_MANAGEMENT.md`, the technical index-child links, and an `UNVERIFIED` warning in every technical file.
- Formal information intentionally removed: formal Parent/Lifecycle/Source-of-Truth labels, case-style metadata, direct management backlinks from every Phase file, and formal-tree membership. No technical body, file, or warning boundary was removed.
- Actual impact: both formal trees and counts, the router's member route, document-classification rules, member index/status/registry/task wording, hierarchy checks, and audit population were updated. No implementation, database, server, authentication, deployment, or production change was made.
- Judgment basis: Git comparison, live inspection of all seven files and their implementation paths, the final-review Markdown-classification rule, and the user's explicit approval of the recommended canonical owner → technical index → Phase child structure.

### Authorized Execution B: Align the Management Audit Program

- Target file: `codex/scripts/audit_candy_management_docs.py`; dependent status and execution records include `codex/project_management/PROJECT_STATUS.md`, this case parent, and `codex/project_management/task_history/TASK_LOG_2026_08.md`.
- Current content: the program enumerates all repository Markdown, separates the formal management population from the seven approved source-attached technical references, and reports any other non-formal Markdown as unclassified. Direct-open identity and declared-parent checks apply only when current rules require the identity contract.
- Former problem: the program required uniform fields that `DOCUMENT_RULES.md` forbids retrofitting into normal preexisting documents, producing `missing_identity=34`, `declared_parent=34`, and `case_relationship_unknown=34` even though the independent structural classes were clean.
- Processing performed: after explicit program-change authorization and the approved technical-reference decision, updated population, identity, parent, lifecycle, and relationship classification; added source-attached hierarchy/warning checks; and preserved separate failure reporting for every structural class.
- Current destination: the file remains the sole management validation program at `codex/scripts/audit_candy_management_docs.py`; no second checker or routing authority was created.
- Information preserved: broken-link, table, duplicate source-of-truth, router/README/filesystem tree, capacity, registry-parent, applicable parent-child, and formal implementation-reference checks; historical audit outputs remain unchanged as historical evidence.
- Contract change: older aggregate results used a different formal population and uniform-identity contract. The current output explicitly reports repository Markdown, formal Markdown, source-attached technical references, and unclassified Markdown so the changed coverage cannot be mistaken for a directly comparable old PASS.
- Actual impact: audit results and counts, future completion gates, project status, case/task evidence, and the technical-reference population were updated. Generated site outputs, HP implementation, databases, servers, deployment, and production were not changed.
- Judgment basis: the former live three-class failure result, the clean independent structural classes, the verified Git history of the old contract, the current document rules, and the user's explicit instruction to update the audit program to the latest classification.

### Earlier Final Review Changed Files

The earlier final correction changed exactly these 38 management documents before the two candidates were authorized:

- `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`
- `codex/README.md`
- `codex/WORK_ROUTING.md`
- `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`
- `codex/docs/CANDY_AREA_105_PAGE_QUEUE.md`
- `codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
- `codex/docs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`
- `codex/docs/CANDY_AREA_IMAGE_CREATION_SPEC.md`
- `codex/docs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`
- `codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`
- `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`
- `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`
- `codex/docs/CANDY_FIX_BACKLOG.md`
- `codex/docs/CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md`
- `codex/docs/CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`
- `codex/docs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md`
- `codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`
- `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`
- `codex/docs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`
- `codex/docs/CANDY_HP_STRUCTURE_MAP.md`
- `codex/docs/CANDY_MASTER_DOC_INDEX.md`
- `codex/docs/CANDY_OPERATION_BASICS.md`
- `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`
- `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
- `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`
- `codex/docs/CANDY_SEO_SPEC.md`
- `codex/docs/CANDY_VERIFICATION_PLAN.md`
- `codex/project_management/CASE_REGISTRY.md`
- `codex/project_management/CODEX_COMMUNICATION.md`
- `codex/project_management/DOCUMENT_RULES.md`
- `codex/project_management/PROJECT_STATUS.md`
- `codex/project_management/SAFETY_PROTOCOL.md`
- `codex/project_management/TASK_LOG.md`
- `codex/project_management/TASK_RESERVATIONS.md`
- `codex/project_management/cases/CANDY_MANAGEMENT_SYSTEM_REPAIR.md`
- `codex/project_management/task_history/TASK_LOG_2026_08.md`
- `docs/rules/GIT_RULES.md`

### Authorized Candidate Execution Changed Files

The approved technical-reference reclassification and audit-contract update changed exactly these 19 files:

- `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`
- `codex/README.md`
- `codex/WORK_ROUTING.md`
- `codex/docs/CANDY_MASTER_DOC_INDEX.md`
- `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`
- `codex/project_management/DOCUMENT_RULES.md`
- `codex/project_management/PROJECT_STATUS.md`
- `codex/project_management/CASE_REGISTRY.md`
- `codex/project_management/TASK_LOG.md`
- `codex/project_management/task_history/TASK_LOG_2026_08.md`
- `codex/project_management/cases/CANDY_MANAGEMENT_SYSTEM_REPAIR.md`
- `codex/scripts/audit_candy_management_docs.py`
- `HP/docs/MEMBER_ARCHITECTURE.md`
- `HP/docs/PHASE1_API.md`
- `HP/docs/PHASE2_API.md`
- `HP/docs/PHASE3_API.md`
- `HP/docs/PHASE4_API.md`
- `HP/docs/PHASE5_API.md`
- `HP/docs/PHASE6_API.md`

## Verification and Boundaries

- The updated audit reports `PASS` for 68 repository Markdown files classified as 61 formal management Markdown files and seven source-attached technical references, plus five generated sidecars and two matching 66-file formal trees. Unclassified Markdown and every reported failure class are zero.
- The global site-state observation continues to report the six preexisting member/privacy findings recorded in the task history; this management-only repair does not claim to fix them.
- Database migrations, runtime enablement, scheduler state, server placement, DNS, TLS, deployment, browser behavior, and production behavior remain `UNVERIFIED`.
- `AGENTS.md` preservation is verified separately by its starting and ending byte count and SHA-256.

## 2026-08-20 GitHub Publication-State Reconciliation

The follow-up task `TASK-20260820-GITHUB-PUBLICATION-STATE-RECONCILIATION-001` compared all 29 registered cases, the three category indexes, every routed individual detail, and all task-history rows with live GitHub `main`.

- Eleven cases had GitHub-published changes whose final Commit and Push state was missing or incomplete in one or more current or historical management owners.
- The corrected evidence uses only Commits verified as ancestors of live GitHub `main`: `9f71a703ccdd3f5552b7cf57100939a2e39a236c`, `66f199bd8f00c916c6d693fea00d1fa94557c7d3`, `5ea270eb4fd79d398c68e85a29e0d511ec338f29`, `19e22b4bf1ac4fecb4096e384fa32aab1f9f78dc`, `746406348848a4cacb41db343936563932dc3d70`, `6a66137d28bdd6aebbcd73b381a1a37464a54e79`, and `b25bd53050f57255576ad992627565e5a3b4c319`.
- Original task-time statements that Commit or Push had not yet occurred remain identified as original-time facts; each affected task row now states the later verified GitHub result.
- GitHub publication was not treated as production proof. Each affected runtime case retains an explicit `UNVERIFIED` production boundary, while management-only cases state that no production deployment is required.
- No new management document, case, category, implementation change, generated-state change, HP change, database operation, or production operation was introduced.

## Completion

The requested classifications remain routed through existing formal owners, the clearly identified erroneous common-header additions are removed from normal preexisting documents, the member technical set is separated from the formal management population, the audit contract matches that classification, and the formerly unlinked migration CSV remains connected as historical evidence. No parallel management standard or new category ledger was created.

- Remaining work within the authorized classification and audit-program scope: None.
- Next action: None. Any implementation, database, server, DNS, TLS, deployment, or production work requires its own routed case and authorization.
