# Candy Modification, Addition, and New-Creation History (改修・追加・新規作成等)

- Purpose: Route recorded modifications, additions, new creation, refactoring, migrations, and management-structure changes to canonical individual details
- Parent / Owner: [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Scope: Modification, Addition, and New-Creation History (改修・追加・新規作成等) category routing only
- Status / Lifecycle: Canonical Index / Historical Evidence
- Source of Truth Responsibility: Sole change-category index; each linked individual detail owns its substantive content
- Related Documents: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md), [`TASK_LOG.md`](../TASK_LOG.md), [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), and [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Related Implementation Files: None; linked individual details identify their own scope
- Updated: 2026-08-18

## 1. Recording Rule

Use this index for modifications, additions, new creation, refactoring, migrations, and management-structure work. Permanent behavior remains in the applicable specification or runbook, and completed execution remains in `TASK_LOG.md`. Do not duplicate either here.

## 2. Individual Details (個別詳細)

| Case ID | Subject | Individual detail owner | Boundary |
|---|---|---|---|
| CANDY-GIRL-INFORMATION-MANAGEMENT-20260818 | Manage woman information locally and publish only currently referenced images | Atomic case row in [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) | Canonical woman-information specification and local ledger, blog-generator migration, exact 22-pair local-only image relocation, obsolete public template removal, Git publication, automatic production deletion, and production verification; 34 referenced public image pairs remain protected |
| CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-REMOVAL-20260818 | Remove the verified 48 unpublished hotel-image public pairs while retaining accepted sources | Atomic case row in [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) | Exact 96 public image files, required generated state, Git publication, automatic production deletion, and production absence verification; accepted-source images remain protected |
| CANDY-MYPAGE-DEBUG-LOG-REMOVAL-20260818 | Stop raw Cookie debug logging and remove the generated runtime log | Atomic case row in [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) | `dataset_mypage.php` debug-only statements and production `includefile/debug_mypage.log`; favorite registration and removal behavior remain unchanged |
| CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-20260818 | Preserve accepted hotel images and permit removal of unpublished public copies | Atomic case row in [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) | Permanent hotel-image lifecycle rule only; no image deletion, Git publication, or production operation |
| CANDY-UNUSED-GIT-DATA-CLEANUP-20260818 | Remove 55 verified unused Git-managed files from the repository and production | Atomic case row in [`CASE_REGISTRY.md`](../CASE_REGISTRY.md) | Fixed 55-file deletion population, exact Git publication, automatic production deployment, and production absence verification |
| CANDY-RECORD-HISTORY-20260816 | Establish the required three-category management and record-history structure | [`CANDY_RECORD_HISTORY_STRUCTURE.md`](../cases/CANDY_RECORD_HISTORY_STRUCTURE.md) | Completed management-structure change detail |
| CANDY-MGMT-REPAIR-20260814 | Repair and organize the lower management-document system | [`CANDY_MANAGEMENT_SYSTEM_REPAIR.md`](../cases/CANDY_MANAGEMENT_SYSTEM_REPAIR.md) | Completed management repair detail |
| CANDY-MGMT-20260812 | Reconstruct the Candy management-document system | [`CANDY_MANAGEMENT_SYSTEM_REBUILD.md`](../cases/CANDY_MANAGEMENT_SYSTEM_REBUILD.md) | Completed management reconstruction detail |
| CANDY-HOTEL-HANDOFF-20260723 | Completed hotel-image normalization handoff | [`HANDOFF_README.md`](../../data/hotel-image-handoff-20260723/HANDOFF_README.md) | Completed handoff detail |
