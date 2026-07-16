# AGENTS.md

## 1. Role

This is the local parent entrypoint for `\\192.168.1.3\disk1\FSG_SEO\candy`.

Keep this file short. Do not turn it into a runbook.

The management source of truth is this shared folder root:

`\\192.168.1.3\disk1\FSG_SEO\candy`

The GitHub-connected working repository is:

`\\192.168.1.3\disk1\FSG_SEO\candy`

Do not create a second management source of truth under `HP/管理体制`.

## 2. Required Start

Before work:

1. Read this file.
2. Read `README.md`.
3. Read only the management document or HP runbook required for the current task.
4. Verify real paths, current files, and Git state before editing.

For HP page, script, Git, or production work, also read `HP/AGENTS.md`.

## 3. Core Rules

- Do not report unexecuted, unverified, partial, or interrupted work as complete.
- Do not expand the requested scope.
- Do not mix specifications, current state, reports, and task history.
- Do not create duplicate sources of truth. Update the existing authoritative document.
- Do not append noisy reports to the end of documents. Put information in the correct section.
- Do not overwrite another Codex task. Check `管理体制/TASK_RESERVATIONS.md` first.
- Preserve existing user work. A dirty tree alone is not a stop reason; conflicting overlap is.
- Do not copy secrets, personal information, or raw log contents into reports or documents.
- Do not run `git reset --hard`, `git clean`, force push, unauthorized merge, or unauthorized rebase.
- For deletion, movement, bulk cleanup, Git repair, or other high-risk operations, follow 管理体制/SAFETY_PROTOCOL.md before execution.

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

For multi-Codex work, check `管理体制/TASK_RESERVATIONS.md` before editing shared files.

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
