# Codex Communication

- Purpose: Record handoffs, requests, and cautions between Codex tasks.
- Status: Canonical document
- Last updated: 2026-07-18

## 1. Usage

- Record only information that another Codex task needs.
- Do not mix specifications or task history into this document.
- Move resolved communications to Completed.

## 2. Open Communications

| ID | Date | Recipient | Message | Scope | Status |
|---|---|---|---|---|---|
| COMM-20260718-016 | 2026-07-18 | All Codex tasks | Use `codex/project_management/CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md` as the dated repository-wide SEO remediation handoff. Its repository findings are implementation-verified for the audited commit, while production HTTP, Search Console, analytics, and Lighthouse remain unverified. Reserve exact files and address one approved root cause per task; do not bulk-fix all findings automatically. | Repository-wide SEO remediation | IN_PROGRESS |
| COMM-20260716-001 | 2026-07-16 | All Codex tasks | Before work, check the target-file reservations in `codex/project_management/TASK_RESERVATIONS.md`. | Entire project | IN_PROGRESS |
| COMM-20260716-003 | 2026-07-16 | All Codex tasks | The existing 76 deletion entries resulted from a relocation operation. Confirm the intended scope before including them in a Commit. | Git state | IN_PROGRESS |
| COMM-20260716-004 | 2026-07-16 | All Codex tasks | When area images are missing, read `CANDY_AREA_IMAGE_CREATION_SPEC.md`. STOP production and publication if the storage, modification, commercial-publication, and attribution conditions for Google Maps or another source cannot be confirmed. | Area image production | IN_PROGRESS |
| COMM-20260716-005 | 2026-07-16 | All Codex tasks | Run Git operations only in `C:\Codex\candy`. At the start, run `git fetch origin` and `git status --short --branch`; pull first if the branch is behind. Push only with explicit instruction. Do not run Git on the NAS. | Git synchronization | IN_PROGRESS |
| COMM-20260716-006 | 2026-07-16 | All Codex tasks | In area production, `01_間違い無し` does not authorize a new page. Before publish, use the canonical slug to check for an existing public PHP file, source HTML, dataset PHP, dataset_base registration, area index entry, and sitemap entry. Proceed only for a target that returns `NEW_PAGE_TARGET_OK`. | Area target selection | IN_PROGRESS |
| COMM-20260716-007 | 2026-07-16 | All Codex tasks | The area production gate MUST NOT return `NEW_PAGE_TARGET_OK` when the area index contains the same region name under a different slug. Exclude `area list same-region slug mismatch` before publish. | Area target selection | IN_PROGRESS |
| COMM-20260716-008 | 2026-07-16 | All Codex tasks | One target-slug link in the area index is required for new area production. Exclude a candidate only when the area index has the same region name under a different slug. Do not treat the required target link itself as proof that the page already exists. | Area target selection | IN_PROGRESS |
| COMM-20260716-009 | 2026-07-16 | All Codex tasks | For hotel production, proceed with only one item that returns `NEW_HOTEL_TARGET_OK` from `candy-hotel.cmd target-next` or `target-check`. Exclude missing-image, invalid-input, completed, untracked, and unregistered-shop candidates before production. | Hotel target selection | IN_PROGRESS |
| COMM-20260716-010 | 2026-07-16 | All Codex tasks | Use `publish-next` as the standard hotel-production entry point. On STOP, review `BLOCKER_COUNTS_JSON` as well as `COUNTS_JSON`, and distinguish missing images from untracked inputs. | Hotel target selection | IN_PROGRESS |
| COMM-20260716-012 | 2026-07-16 | All Codex tasks | The Git working repository is `C:\Codex\candy`. GitHub is the synchronization hub. The NAS path `\\192.168.1.3\disk1\FSG_SEO\candy` is storage-only for `Backup/` and accepted source assets. Do not create `HP/HP/` or `HP/README.md`. | Workspace and HP hierarchy | IN_PROGRESS |
| COMM-20260716-013 | 2026-07-16 | All Codex tasks | For deletion, movement, bulk cleanup, or Git repair, read `codex/project_management/SAFETY_PROTOCOL.md`. Do not proceed without classifying the targets, excluding protected items, and fixing the exact target list. | High-risk operations | IN_PROGRESS |
| COMM-20260717-014 | 2026-07-17 | All Codex tasks | The management entry point is `codex/README.md`, canonical project-management documents are under `codex/project_management/`, and HP production specifications are under `codex/docs/`. Do not duplicate a management source of truth at the repository root, under HP, or on the NAS. | Management documents | IN_PROGRESS |

## 3. Completed

| ID | Date | Message | Result | Status |
|---|---|---|---|---|
| COMM-20260716-002 | 2026-07-16 | Former policy that placed management documents directly under the shared root | The user instruction on 2026-07-17 changed management to the `codex/` tree. Replaced by COMM-20260717-014. | COMPLETE |
| COMM-20260716-011 | 2026-07-16 | Former policy that treated the outer `candy` directory as the management source of truth | The user instruction on 2026-07-17 moved the canonical project-management documents to `codex/project_management/`. Removed from current routing. | COMPLETE |
| COMM-20260717-015 | 2026-07-17 | STOP while the internal paths in `codex/scripts/` were not migrated | `TASK-20260717-SCRIPTS-LOCAL-PATHS-001` migrated and dry-run-verified the internal paths. On 2026-07-18, the current scripts were rechecked and contained zero old `HP/codex/`, `HP/Text_*_data`, or fixed `parents[3]` references. | COMPLETE |
