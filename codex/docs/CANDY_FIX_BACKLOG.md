# CANDY Fix and Decision Backlog

## 1. Responsibility

This document manages only unresolved issues that require a specification, fix, or owner decision and cannot be completed by machine evaluation. `codex/project_management/records/DEFECT_RESPONSE_HISTORY.md` is the defect-category route and links each case to its individual detail. The generated documents are the canonical source for current per-page gaps, SEO state, links, images, and assets. Do not duplicate those counts here.

## 2. Unresolved Issues

| ID | Target | Verified fact | Required decision or next action | Status |
|---|---|---|---|---|
| GIRLS-INVALID-NO-200 | `girls.php?no={id}` with a nonexistent woman number; case `CANDY-GIRLS-INVALID-NO-20260816` | The shared dataset falls back to the first active woman when `no` does not resolve. On 2026-08-16, the constructed production test URL `girls.php?no=999999999` returned HTTP 200 with `robots=index`, rendered ユリ, and declared the canonical URL `girls.php?no=1479`. The test URL was not discovered from a current internal link or sitemap entry, and current search-engine discovery or direct SEO impact was not established. | Preserve this as a separately discovered system URL-behavior problem. Decide whether to change the response only when the owner separately authorizes a behavior change; no 404, redirect, noindex, or other remedy is adopted by this record. | `AWAITING_APPROVAL` |
| HP-SPECIAL-PAGES | Public PHP without the normal source/dataset pairing | Some entries are `SPECIAL` or `PARTIAL` in the generated ledger and may be dynamic or operational entry points | The feature owner must decide whether each page has an intentional special structure or a missing component | `AWAITING_APPROVAL` |
| HP-SEO-CHANGE | Detected robots, canonical, and JSON-LD issues | `generated/CANDY_SEO_STATUS.md` lists issues in current actual files | The owner selects fix targets by category or URL for a separate task | `AWAITING_APPROVAL` |
| HP-ASSET-DELETE | Unconfirmed-reference, duplicate-hash, and possibly unused public assets | `generated/CANDY_CODE_ASSET_INVENTORY.md` detects candidates, but machine analysis cannot rule out dynamic references | Verify PHP, database, JavaScript, production references, and recovery methods, then obtain the target decision through the applicable deletion route selected from `codex/WORK_ROUTING.md` Section 5.2 | `AWAITING_APPROVAL` |
| AREA-SEIRYO | `seiryo` and `seiryou` image candidates | Existing management state treats them as complete duplicate candidates, and current Text references exist | Confirm the correct canonical slug across Text and public source before deciding whether to consolidate | `BLOCKED` |
| HOTEL-ACCEPTED-IMAGE-PATH | Automatic hotel publication from an accepted-source-only image pair | The canonical hotel runbook and image-asset lifecycle require first local installation, same-name SHA-256 verification, image-asset Git registration, Actions deployment, production-byte verification, and only then page publication. The current `publish-next` route does not execute this complete path. | Implement the complete canonical path and validate it with an accepted-source-only target. Completion requires matching accepted/public hashes, `INSTALLED_LOCAL`, `REGISTERED_GIT`, `DEPLOYED_ASSET`, successful page publication, matching production bytes, and no manual bypass. | `NOT_STARTED` |

## 3. Registration and Completion Rules

- Do not copy individual rows that generated documents detect on every run into this backlog.
- Do not preserve historical snapshot counts as current values.
- Record an issue as fixed only after verifying the target actual-file diff and required validation.
- Move completed-task history to `codex/project_management/TASK_LOG.md`; do not use the backlog as a history store.
