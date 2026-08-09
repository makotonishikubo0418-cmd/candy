# Project Status

- Purpose: Provide one location for the overall plan, current state, problems, and next work
- Status: canonical document
- Updated: 2026-08-09

## 1. Current State

- Management locations and document responsibilities are defined in `codex/README.md`.
- Current page, candidate, code/asset, and SEO facts are defined by the four generated documents under `codex/docs/generated/`.
- This document contains only current project-level problems, remaining work, and next actions that cannot be derived from those generated documents.
- Git, production, authority, and execution procedures are not defined here.

## 2. Completed Management Foundation

- `codex/WORK_ROUTING.md` Section 5.2 is the required-document routing authority.
  `codex/README.md` owns canonical management locations and document
  responsibilities only; it no longer adds a second operational route.
- Page structure, code and asset structure, and the common SEO specification are separated into stable canonical documents.
- Current page, production-candidate, code/asset, and SEO state can be regenerated into four documents with `candy-site-state`.
- `audit`, `preview-sitemap-lastmod`, `sync-sitemap-lastmod`, `preview`, `write`, `check`, and `check --target` are implemented as the standard entry points.
- The pre-stage gate synchronizes sitemap `lastmod`, regenerates the generated documents, and validates both after area, hotel, and blog changes.
- Hotel production now separates staff-completed Text from Phase-prepared Text, validates legacy formats before conversion, and manages accepted/public image pairs through one canonical lifecycle specification.
- Legacy documents that no longer receive updates are physically isolated in NAS `Backup/` and removed from the normal work route.

## 3. Current Problems and Remaining Work

| Type | Reference or evidence source | Handling |
|---|---|---|
| Dated repository-wide SEO audit | `CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md` | Use this as the 2026-07-18 repository evidence snapshot and remediation handoff. Reverify volatile repository and production state before implementing a finding |
| Machine-detected page-structure, Text-candidate, SEO, and asset issues | `codex/docs/generated/` | Regenerate after actual-file changes. Detection alone MUST NOT trigger automatic fixes or deletion |
| Issues requiring specification or owner decisions | `codex/docs/CANDY_FIX_BACKLOG.md` | Handle in a separate task after an explicit decision for the target |
| Area production order | `CANDY_AREA_105_PAGE_QUEUE.md` and generated upcoming pages | Handle only one target that passes the target gate |
| Hotel input and production order | Hotel classification, hotel content/image runbooks, and generated upcoming pages | Run `legacy-check` for a legacy Text, use `direct-check` for a staff-completed current Text, and keep Phase preparation independent; resolve the reported image, input, and existing-registration blockers before production |
| Hotel accepted-source-only publication gap | `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`, `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, and `codex/docs/CANDY_FIX_BACKLOG.md` item `HOTEL-ACCEPTED-IMAGE-PATH` | The current automatic `publish-next` route does not complete first local installation, same-name hash verification, image-asset registration and deployment, production-byte verification, and subsequent page publication from an accepted-source-only pair. Accepted-source-only automatic publication remains unavailable until the backlog item is `COMPLETE` |
| Existing blog exceptions | Blog specification and generated ledger/SEO status | Keep separate from new production and use a dedicated fix task |

## 4. Candidate Next Actions

1. For further repository-wide SEO remediation, treat `CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md` as a dated snapshot, recheck each finding against the generated current state, and skip the completed area-placeholder, obsolete-contact, category-index, internal-link, sitemap, and public-wrapper runtime-path work recorded in `TASK_LOG.md`.
2. Resolve category-specific blockers in `codex/docs/generated/CANDY_UPCOMING_PAGES.md`, then use the dedicated target gate to select an eligible production target.
3. Handle issues explicitly selected by the owner from `codex/docs/generated/CANDY_SEO_STATUS.md` and `CANDY_FIX_BACKLOG.md` in separate tasks.
4. For missing, unconfirmed-reference, and duplicate candidates in `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md`, verify dynamic references and recovery methods before requesting target-specific deletion approval.

## 5. Update Rules

- Store detailed execution results in `TASK_LOG.md`, specifications in the applicable specification, and inter-Codex requests in `CODEX_COMMUNICATION.md`.
- Keep dated audit evidence in the dated audit report. Do not copy its volatile counts into stable specifications or generated current-state documents.
- Do not duplicate current counts here. Regenerate the generated documents instead.
- Do not append completed historical work in chronological order.
