# AGENTS.md

## 1. Role

This is the Git-managed entrypoint and HP route map for the candy repository.

Repository root:

`\\192.168.1.3\disk1\FSG_SEO\candy\HP`

Site root inside this repository:

`HP/`

Keep this file short. Detailed rules belong in `README.md`, `管理体制/`, or the relevant HP runbook.

## 2. Required Start

Before work:

1. Read this file.
2. Read `README.md`.
3. Read only the management document or HP runbook required for the current task.
4. Verify the actual target paths, current files, Git branch, Git status, and affected files before editing.

If starting from `\\192.168.1.3\disk1\FSG_SEO\candy`, also read `../AGENTS.md` as the local outer entrypoint.

## 3. Document Routes

| Task | Required document |
|---|---|
| Management system change | `README.md` and `管理体制/DOCUMENT_RULES.md` |
| Current status or cleanup tracking | `管理体制/PROJECT_STATUS.md` and `管理体制/TASK_LOG.md` |
| Multi-Codex coordination | `管理体制/TASK_RESERVATIONS.md` and `管理体制/CODEX_COMMUNICATION.md` |
| Area page production | `HP/codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| Hotel page production | `HP/codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| Blog page production | `HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `HP/codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| Other HP specification or management work | `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Tool or script work | Relevant file under `HP/codex/scripts/` and the linked specification |

## 4. Core Rules

- Do not report unexecuted, unverified, partial, or interrupted work as complete.
- Do not expand the requested scope.
- Do not mix specifications, current state, reports, and task history.
- Do not create duplicate sources of truth.
- Do not append noisy reports to the end of documents.
- Do not overwrite another Codex task. Check reservations and active handoffs first.
- Preserve existing user work. A dirty tree alone is not a stop reason; conflicting overlap is.
- Do not copy secrets, personal information, or raw log contents into reports or documents.
- Stage only target files. Never use `git add -A`.
- Do not run `git reset --hard`, `git clean`, force push, unauthorized merge, or unauthorized rebase.

Explicit user instruction is required for Commit, Push, production operation, database operation, file deletion, file movement, file renaming, conflict resolution, index/noindex change, and manual GitHub Actions execution.

## 5. Upload Meaning

If the user says `アップ`, `アップしろ`, or `アップまで`, that authorizes the current target workflow only:

1. Verify the current target.
2. Stage only target files.
3. Commit once.
4. Reconfirm remote, branch, and push target.
5. Push to `main` once.
6. Track GitHub Actions when they run.
7. Verify production HTTP when deployment runs.
8. Report only performed states and URLs.

Stop at the exact failed stage if a STOP condition, conflict, Actions failure, or production verification failure occurs.

## 6. STOP

Stop before editing if:

- The target, branch, or completion criteria is unclear.
- Existing changes overlap and cannot be preserved safely.
- Required slug, image, input text, or publication condition is missing.
- A requested action would require unapproved deletion, movement, rename, DB operation, production operation, or index/noindex change.
- Redirect preservation for a production `index.php` change cannot be confirmed.

## 7. User-Facing Explanation

- Start with the plain conclusion.
- Explain from the user's point of view.
- Separate current result from unverified or unfinished work.
- Do not report unverified work as complete.