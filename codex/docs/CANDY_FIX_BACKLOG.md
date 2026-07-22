# CANDY Fix and Decision Backlog

## 1. Responsibility

This document manages only unresolved issues that require a specification, fix, or owner decision and cannot be completed by machine evaluation. The generated documents are the canonical source for current per-page gaps, SEO state, links, images, and assets. Do not duplicate those counts here.

## 2. Unresolved Issues

| ID | Target | Verified fact | Required decision or next action | Status |
|---|---|---|---|---|
| HP-SPECIAL-PAGES | Public PHP without the normal source/dataset pairing | Some entries are `SPECIAL` or `PARTIAL` in the generated ledger and may be dynamic or operational entry points | The feature owner must decide whether each page has an intentional special structure or a missing component | `AWAITING_APPROVAL` |
| HP-SEO-CHANGE | Detected robots, canonical, and JSON-LD issues | `generated/CANDY_SEO_STATUS.md` lists issues in current actual files | Approve fix targets by category or URL, then change them in a separate task | `AWAITING_APPROVAL` |
| HP-ASSET-DELETE | Unconfirmed-reference, duplicate-hash, and possibly unused public assets | `generated/CANDY_CODE_ASSET_INVENTORY.md` detects candidates, but machine analysis cannot rule out dynamic references | Verify PHP, database, JavaScript, production references, and recovery methods, then obtain target-specific deletion approval | `AWAITING_APPROVAL` |
| AREA-SEIRYO | `seiryo` and `seiryou` image candidates | Existing management state treats them as complete duplicate candidates, and current Text references exist | Confirm the correct canonical slug across Text and public source before deciding whether to consolidate | `BLOCKED` |
| INDEX-PRODUCTION | `HP/index.php` | This protected target affects final production switchover | Before production deployment, show the target SHA, redirects, rollback method, and HTTP checks and obtain explicit approval | `AWAITING_APPROVAL` |

## 3. Registration and Completion Rules

- Do not copy individual rows that generated documents detect on every run into this backlog.
- Do not preserve historical snapshot counts as current values.
- Record an issue as fixed only after verifying the target actual-file diff and required validation.
- Do not change robots, canonical URLs, URLs, databases, authentication, payments, or production settings without explicit instruction.
- Move completed-task history to `project_management/TASK_LOG.md`; do not use the backlog as a history store.
