# AGENTS.md

## 1. Role

This is the local parent entrypoint for `C:\Codex\candy`.

Keep this file short. Do not turn it into a runbook.

The management source of truth is the `codex` folder in this local clone:

`C:\Codex\candy\codex`

The project-management documents are:

`C:\Codex\candy\codex\project_management`

The GitHub-connected working repository is:

`C:\Codex\candy`

GitHub is the synchronization hub. Accepted area-image source assets are stored in the Git-managed local folder `C:\Codex\candy\Text_area_data\画像データ`. The NAS path `\\192.168.1.3\disk1\FSG_SEO\candy` is storage-only for `Backup/`; it is not a Git working repository, and Git commands must not be run there.

Do not create a second management source of truth under `HP/`, the repository root, the NAS, or another folder.

## 2. Required Start

Before work:

1. Read this file.
2. Read `codex/README.md`.
3. Read only the management document or HP runbook required for the current task.
4. Verify real paths, current files, and Git state before editing.

For HP page, script, Git, or production work, also read `HP/AGENTS.md`.

## 3. Core Rules

- Do not report unexecuted, unverified, partial, or interrupted work as complete.
- Do not expand the requested scope.
- Do not mix specifications, current state, reports, and task history.
- Do not create duplicate sources of truth. Update the existing authoritative document.
- Do not append noisy reports to the end of documents. Put information in the correct section.
- Do not overwrite another Codex task. Check `codex/project_management/TASK_RESERVATIONS.md` first.
- Preserve existing user work. A dirty tree alone is not a stop reason; conflicting overlap is.
- Do not copy secrets, personal information, or raw log contents into reports or documents.
- HP page, PHP, source, dataset, CSS, JavaScript, image, or SEO work is not complete until `candy-site-state check` confirms that generated management data matches the repository. Use `codex/README.md` for the route.
- At the start of work, run `git fetch origin` and `git status --short --branch`; if `main` is behind `origin/main`, pull before editing.
- Run Git commands only in `C:\Codex\candy`; Git operations on the NAS are prohibited.
- Do not run `git reset --hard`, `git clean`, force push, unauthorized merge, or unauthorized rebase.
- For deletion, movement, bulk cleanup, Git repair, or other high-risk operations, follow `codex/project_management/SAFETY_PROTOCOL.md` before execution.

Explicit user instruction is required for:

- Commit
- Push
- Production operation
- Database operation
- File deletion
- File movement
- File renaming
- Conflict resolution
- Manual GitHub Actions execution

## 4. Task Gate

Before editing, state briefly:

```text
AGENTS.md check:
- Route used
- Task type
- Work included
- Work excluded
```

For multi-Codex work, check `codex/project_management/TASK_RESERVATIONS.md` before editing shared files.

## 5. Reporting

Report only verified facts.

Reports must be understandable to Makoto without guessing. Do not make the summary so short that the reason, shortage, target, or next action becomes unclear.

When reporting a problem or STOP, include:

- What happened
- Why it stopped
- What is missing or wrong
- Which file, page, slug, or count is affected
- What must happen next

Use this shape:

```text
結論:
確認済み:
変更ファイル:
確認用URL:
未確認・未実施:
次に必要な操作:
要約:
```

Do not include Commit, Push, Actions, production, HTTP, or browser evidence unless that step was actually performed.

# BEGIN NAS STORAGE OPERATION RULES

## Windows NAS storage operation rules

These rules apply only when directly reading or writing storage data under:

\\192.168.1.3\disk1\FSG_SEO\candy

- The NAS storage is directly readable and writable through PowerShell and .NET.
- The NAS is storage-only. Do not treat it as a Git repository, and do not run Git commands there.
- Do not conclude that the NAS is not writable merely because `apply_patch` or a built-in patch operation fails.
- Do not use `apply_patch` or built-in patch editing for files in NAS storage.
- Use PowerShell or .NET direct file operations for NAS edits.
- Read every NAS text file explicitly as UTF-8.
- Never analyze, summarize, or edit mojibake output.
- Preserve UTF-8 without BOM when modifying existing UTF-8 files.
- Before editing, verify the exact file, target section, and intended replacement count.
- For targeted replacement, require exactly one intended match.
- If the match count is zero or greater than one, stop without changing the file.
- Change only the requested section.
- After editing, reread the changed section as UTF-8.
- Run `git diff -- <target-file>` after every change.
- Verify that no unrelated file or line changed.
- Run Git commands only with:
  `git -C "C:\Codex\candy" ...`
- Use a local working directory and address NAS storage files by absolute UNC path only when a storage operation is explicitly requested.
- Do not propose reopening the Git project from the NAS or as `K:`.

# END NAS STORAGE OPERATION RULES
