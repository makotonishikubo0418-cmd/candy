# Task and File Reservations

- Purpose: Prevent multiple Codex tasks from changing the same files concurrently.
- Status: Canonical document
- Last updated: 2026-07-18

## 1. Reservation Rules

- Reserve files before editing them.
- Keep each reservation to the minimum required scope.
- Release the reservation or move it to Completed when work ends.
- STOP and coordinate before changing a file covered by another active reservation.
- Do not overwrite existing unorganized differences without a reservation.

## 2. Active Reservations

No active reservations.

## 3. Completed and Released Reservations

| Task ID | Codex | Period | Scope | Result | Status |
|---|---|---|---|---|---|
| TASK-20260718-AREA-JIGENJI-JIYUGAOKA-PUBLISH-001 | current | 2026-07-18 | `jigenjicho` and `jiyugaoka` area page change units, shared area registrations, queue, generated current-state documents, final Text classification synchronization, and this reservation record | Published `jigenjicho` and `jiyugaoka` in queue order with successful Actions and production HTTP verification, then moved both verified inputs to `09_作成済み` and synchronized the classification inventory. | COMPLETE |
| TASK-20260718-AREA-TWO-PAGE-PUBLISH-001 | current | 2026-07-18 | Area target/publish scripts, area production runbook, two selected page change units, generated current-state documents, Text classification records, and this reservation record | Unified `target-next` and `publish-next` on queue order plus the new-page target gate, rejected untracked or ineligible inputs before publication, and verified the first eligible targets as queue 16 `jigenjicho` and queue 17 `jiyugaoka`. Page publication continues as separate integrated work units. | COMPLETE |
| TASK-20260718-AREA-09-COMPLETED-001 | current | 2026-07-18 | `Text_area_data/分類_20260716_115215/**/*.txt`, `分類結果.tsv`, area Text classification document, generated current-state documents, and this reservation record | Created `09_作成済み`, moved the 38 exact complete page-bundle inputs without content changes, synchronized the TSV and current-state documents, and verified the 156-file inventory, category counts, UTF-8, SHA-256 uniqueness, parser and pre-render totals, and generated `EXISTING` set. | COMPLETE |
| TASK-20260718-AREA-TEXT-FULL-INVENTORY-001 | current | 2026-07-18 | Area Text files, classification reports, source-image preparation files, canonical classification document, and generated current-state documents | Audited all 541 original files, retained 156 classified Text files, 342 verified JPEG assets, and one exact classification TSV, relocated 37 unnecessary Text files, three stale TSV reports, one cache file, and one unreferenced nonstandard image to verified NAS storage, and synchronized the canonical and generated management documents. | COMPLETE |
| CANDY-MARKDOWN-COMMIT-PUSH-20260718 | current | 2026-07-18 | Accumulated working-tree changes, related active Markdown, generated documents, and Git synchronization | Reconciled the current-state wording, validated and committed the fixed 78-file target set as `eb54399`, pushed it to origin/main, and confirmed the remote ref match. | COMPLETE |
| CANDY-MARKDOWN-ENGLISH-STANDARDIZATION-20260718 | current | 2026-07-18 | `AGENTS.md`, `HP/AGENTS.md`, `codex/README.md`, active `codex/**/*.md`, and `codex/scripts/candy_site_state.py` | Audited 46 active Markdown files, renamed one tracked canonical document, translated 41 manual documents, updated the generator, and regenerated four documents. Current references, names, headings, generated reproducibility, and unstaged Git state passed validation. | COMPLETE |
| CANDY-HP-MD-MANAGEMENT-20260718 | current | 2026-07-18 | Management entry point, stable and generated documents, management and publish scripts, and four specified legacy document groups | Separated stable specifications from generated current state, implemented `candy-site-state` and the pre-stage gate, isolated 20 legacy files on the NAS with matching SHA-256 values, and deleted the four local target groups. Commit, Push, and HP content changes were not performed. | COMPLETE |
| TASK-20260718-AREA-ARATA-BACKUP-001 | current | 2026-07-18 | 荒田 input and `Text_area_data/Backup/` | Updated the 荒田 input to the current format and passed dry-run validation. Deleted two unnecessary backup files and the empty folder. | COMPLETE |
| TASK-20260717-LOCAL-WORKSPACE-DOCS-001 | current | 2026-07-17 | Thirteen management documents | Updated the documents for the local workspace, GitHub synchronization, and NAS storage-only model. NAS references were limited to 10 storage references and five historical references. Scripts, Commit, Push, and NAS operations were not performed. | COMPLETE |
| TASK-20260717-GITHUB-SYNC-001 | current | 2026-07-17 | GitHub structure synchronization | Pushed Commit `7d23c91` to main and matched the remote tree to the eight intended target groups. Excluded `Backup/`; no physical NAS deletion was performed. | COMPLETE |
| TASK-20260717-STRUCTURE-DOCS-001 | current | 2026-07-17 | Twenty-six management documents after folder reorganization | Unified routing to the new locations and verified actual files, UTF-8, headings, and tables. Recorded the unmigrated scripts as a STOP condition. | COMPLETE |
| TASK-20260717-AREA-HOTEL-GUIDE-001 | current | 2026-07-17 | Area and hotel generation specifications, management document routers, and reservation table | Made the page roles, detailed structure trees, variable counts, and direct reference routes canonical for both categories. | COMPLETE |
| TASK-20260717-SUMMARY-RULE-001 | current | 2026-07-17 | `管理体制/DOCUMENT_RULES.md`, `管理体制/TASK_RESERVATIONS.md` | Made the required contents of `要約:`, status-specific wording, prohibited examples, and successful-completion and STOP examples canonical. | COMPLETE |
| TASK-20260716-MGMT-001 | current | 2026-07-16 | Outer `AGENTS.md`, `README.md`, `管理体制/*`, and `HP/AGENTS.md` | Established the initial management system. | COMPLETE |
| TASK-20260716-MGMT-002 | current | 2026-07-16 | `AGENTS.md`, `README.md`, the former management overview, `管理体制/*`, and outer entry documents | In response to audit findings, placed the CANDY canonical management documents inside the Git workspace and downgraded the outer documents to entry points. | COMPLETE |
| TASK-20260716-MGMT-003 | current | 2026-07-16 | `管理体制/TASK_LOG.md`, `管理体制/PROJECT_STATUS.md`, `管理体制/TASK_RESERVATIONS.md` | Corrected remaining re-audit findings. | COMPLETE |
| TASK-20260716-MGMT-004 | current | 2026-07-16 | `AGENTS.md`, `管理体制/DOCUMENT_RULES.md`, `管理体制/CODEX_COMMUNICATION.md`, `管理体制/TASK_LOG.md`, `管理体制/TASK_RESERVATIONS.md` | Added fast Git Commit and Push rules and audit checks. | COMPLETE |
| TASK-20260716-MGMT-005 | current | 2026-07-16 | Area runbook, page generation specification, `DOCUMENT_RULES.md`, `CODEX_COMMUNICATION.md`, `TASK_LOG.md`, and `TASK_RESERVATIONS.md` | Separated Text classification from new-page eligibility and added the new-production target gate. | COMPLETE |
| TASK-20260716-MGMT-006 | current | 2026-07-16 | Area target gate script, area runbook, page generation specification, `DOCUMENT_RULES.md`, `CODEX_COMMUNICATION.md`, `TASK_LOG.md`, and `TASK_RESERVATIONS.md` | Updated management so that an area index entry with the same region name under a different slug is excluded before the target gate. | COMPLETE |
| TASK-20260716-MGMT-007 | current | 2026-07-16 | Area target gate script, area runbook, page generation specification, `DOCUMENT_RULES.md`, `CODEX_COMMUNICATION.md`, `TASK_LOG.md`, and `TASK_RESERVATIONS.md` | Corrected the gate so that the target-slug area index link is required and only the same region name under a different slug is excluded. | COMPLETE |
| TASK-20260716-MGMT-008 | current | 2026-07-16 | Hotel target gate, hotel runbook, hotel specification, hotel Text and image documents, and management documents | Established hotel production target management, existing-hotel analysis, `publish-next` standardization, and blocker aggregation. | COMPLETE |
| TASK-20260716-MGMT-009 | current | 2026-07-16 | Outer `AGENTS.md`, `README.md`, management documents, HP `AGENTS.md` and `README.md`, and duplicate HP management documents | Unified the outer management source of truth and removed duplicates. | COMPLETE |
| TASK-20260716-MGMT-010 | current | 2026-07-16 | `.git`, `HP/HP`, non-site items directly under HP, and management routing | Unified the HP hierarchy and moved the GitHub workspace to the outer directory. | COMPLETE |
| TASK-20260716-AREA-IMAGE-SPEC-001 | Codex | 2026-07-16 | Area image production specification and related routing | Made the specification canonical and established the rights STOP condition and routing for other Codex tasks. | COMPLETE |
| TASK-20260716-MGMT-011 | current | 2026-07-16 | `AGENTS.md`, `README.md`, and `管理体制/*` | Made the deletion, movement, bulk cleanup, and Git repair safety protocol canonical. | COMPLETE |
