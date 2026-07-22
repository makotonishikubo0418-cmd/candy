# Project Status

- Purpose: Provide one location for the overall plan, current state, problems, and next work
- Status: canonical document
- Updated: 2026-07-23

## 1. Current State

| Type | Canonical source or work location | Status |
|---|---|---|
| Local Git working repository | `C:\Codex\candy` | The only working repository synchronized with GitHub. Run Git operations only here |
| GitHub synchronization hub | `makotonishikubo0418-cmd/candy` | Commit and Push require explicit instruction |
| Canonical Codex management source | `codex/` | README, project management, HP specifications, and scripts |
| Project management | `codex/project_management/` | Rules, current state, reservations, history, safety, and handoff |
| Stable HP specifications | `codex/docs/` | Structure, SEO, and category specifications and runbooks. Do not store current counts here |
| Generated HP current state | `codex/docs/generated/` | Four documents generated from actual files. Manual editing is prohibited |
| Actual site | `HP/` | Public PHP, source, includefile, CSS, JavaScript, images, movies, and logs |
| Production inputs | `Text_area_data/`, `Text_blog_data/`, and `Text_hotel_data/` | Source data not published directly to HP |
| Accepted area images | `Text_area_data/画像データ/` | Git-managed local source assets used before public placement |
| Accepted hotel images | `Text_hotel_data/画像データ/` | Git-managed accepted source pairs; the directory may remain absent until the first accepted pair |
| NAS | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only for backups. Git operations are prohibited |

## 2. Completed Management Foundation

- Root `AGENTS.md` and `codex/README.md` are the entry points. HP work routes from `HP/AGENTS.md` to the category runbook.
- Page structure, code and asset structure, and the common SEO specification are separated into stable canonical documents.
- Current page, production-candidate, code/asset, and SEO state can be regenerated into four documents with `candy-site-state`.
- `audit`, `preview`, `write`, `check`, and `check --target` are implemented as the standard entry points.
- The pre-stage gate regenerates and validates the generated documents after area, hotel, and blog changes.
- Hotel production now separates staff-completed Text from Phase-prepared Text, validates legacy formats before conversion, and manages accepted/public image pairs through one canonical lifecycle specification.
- Legacy documents that no longer receive updates are physically isolated in NAS `Backup/` and removed from the normal work route.

## 3. Current Problems and Remaining Work

| Type | Canonical source | Handling |
|---|---|---|
| Dated repository-wide SEO audit | `CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md` | Use this as the 2026-07-18 repository evidence snapshot and remediation handoff. Reverify volatile repository and production state before implementing a finding |
| Machine-detected page-structure, Text-candidate, SEO, and asset issues | `codex/docs/generated/` | Regenerate after actual-file changes. Detection alone MUST NOT trigger automatic fixes or deletion |
| Issues requiring specification or owner decisions | `codex/docs/CANDY_FIX_BACKLOG.md` | Handle in a separate task after an explicit decision for the target |
| Area production order | `CANDY_AREA_105_PAGE_QUEUE.md` and generated upcoming pages | Handle only one target that passes the target gate |
| Hotel input and production order | Hotel classification, hotel content/image runbooks, and generated upcoming pages | Run `legacy-check` for a legacy Text, use `direct-check` for a staff-completed current Text, and keep Phase preparation independent; resolve the reported image, input, and existing-registration blockers before production |
| Existing blog exceptions | Blog specification and generated ledger/SEO status | Keep separate from new production and use a dedicated fix task |

## 4. Git and Production

- At the start of work, run `git fetch origin` and `git status --short --branch`. When behind, pull safely before editing.
- Dirty state, HEAD, Actions, HTTP, database, and browser results are volatile and MUST NOT be stored as fixed values in this document.
- Commit, Push, Actions, production, and database operations require explicit user instruction.
- Before staging an HP change, run generated-document `write` and `check`, then treat the page changes and required management documents as one commit candidate.

## 5. Candidate Next Actions

1. For further repository-wide SEO remediation, treat `CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md` as a dated snapshot, recheck each finding against the generated current state, and skip the completed area-placeholder, obsolete-contact, category-index, internal-link, sitemap, and public-wrapper runtime-path work recorded in `TASK_LOG.md`.
2. Resolve category-specific blockers in `codex/docs/generated/CANDY_UPCOMING_PAGES.md`, then use the dedicated target gate to select an eligible production target.
3. Handle issues explicitly selected by the owner from `codex/docs/generated/CANDY_SEO_STATUS.md` and `CANDY_FIX_BACKLOG.md` in separate tasks.
4. For missing, unconfirmed-reference, and duplicate candidates in `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md`, verify dynamic references and recovery methods before requesting target-specific deletion approval.
5. For future GitHub synchronization tasks, freeze the target list and obtain explicit instruction before Commit and Push.

## 6. Update Rules

- Store detailed execution results in `TASK_LOG.md`, specifications in the applicable specification, and inter-Codex requests in `CODEX_COMMUNICATION.md`.
- Keep dated audit evidence in the dated audit report. Do not copy its volatile counts into stable specifications or generated current-state documents.
- Do not duplicate current counts here. Regenerate the generated documents instead.
- Do not append completed historical work in chronological order.
