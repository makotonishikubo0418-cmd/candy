# Safety Protocol for Deletion, Movement, and Bulk Operations

- Purpose: Prevent damage to the working repository during deletion, movement, bulk cleanup, or Git recovery
- Status: canonical document
- Updated: 2026-07-17

## 1. Scope

This document MUST be applied to:

- File deletion
- Removal from Git tracking
- File movement, renaming, and folder cleanup
- Recursive operations such as `Get-ChildItem -Recurse`
- `git add`, `git rm`, Commit, and Push
- `.git` damage and Git working-repository recovery
- Bulk cleanup of generated files, logs, caches, or untracked files

## 2. Prohibited Operations

The following operations are prohibited:

- Deletion, movement, staging, Commit, or Push before the target list is fixed
- Execution while targets are described only with vague labels such as "junk" or "unorganized"
- `git add .`, `git add -A`, `git clean`, or `git reset --hard`
- Including `.git` contents in deletion candidates
- Piping recursive search results directly into `Remove-Item`
- Treating a target as safe only because it is under the repository root
- Continuing analysis after producing uncontrolled large output
- Ignoring the user's question and proceeding with execution

## 3. Always-Protected Targets

Unless explicitly included in the authorized target list, the following targets MUST NOT be deleted, moved, or staged:

| Target | Reason |
|---|---|
| `.git/` | Git management data |
| `.git-backups/` | Recovery storage |
| `AGENTS.md` | Common-rule entry point |
| `codex/README.md` | Management entry point |
| `codex/project_management/` | Canonical project-management source |
| `codex/docs/` | Canonical HP production specifications |
| `codex/scripts/` | Generation, validation, and publishing tools |
| `HP/AGENTS.md` | HP work route |
| `HP/index.php` | Actual site entry point |
| `Text_area_data/`, `Text_blog_data/`, and `Text_hotel_data/` | Page-production inputs |
| NAS `Backup/` | Verification location for legacy and isolated data. It is not a Git working repository |
| `HP/HP/` | Prohibited duplicate hierarchy. STOP and report if it exists |

## 4. Pre-Execution Classification

Before deletion, movement, or bulk cleanup, classify every target as follows:

| Classification | Meaning | Execution condition |
|---|---|---|
| Approved for deletion | Runtime log, cache, or clearly unnecessary file | Present the exact target list and count, then execute only after approval |
| Remove from Git tracking | The file is unnecessary and should be removed from the next commit | Use only `git add -u -- <explicit-target>` |
| Relocated | Original removal and new location have a verified one-to-one match | Stage only when missing count is zero and duplicate count is zero |
| Register in Git | Management table, classification result, required image, or production input | Confirm purpose and destination before staging |
| Recovery | Incorrect deletion, damage, or missing required file | Restore from the latest GitHub commit or verified isolated copy |
| AWAITING_APPROVAL | Necessity or canonical responsibility is unclear | Do not execute; request a user decision |

## 5. Pre-Execution Checks

Before execution, verify at minimum:

1. The Git working repository is `C:\Codex\candy`. The NAS is storage-only; do not run Git operations there.
2. Evidence has been provided for reading `AGENTS.md`, `codex/README.md`, and the required management document.
3. The target is reserved in `codex/project_management/TASK_RESERVATIONS.md`.
4. `git fetch origin` and `git status --short --branch` have confirmed Git state. Pull before editing when behind.
5. The target list is fixed.
6. The target list does not include an unauthorized protected target.
7. Deletion, movement, Commit, Push, and production operations have explicit user authorization.

## 6. Git Rules

- Run Git operations only in `C:\Codex\candy`; never on the NAS.
- Specify every staging target explicitly.
- Use `git add -u -- <target>` and `git add -- <target>` according to target state.
- After staging, use `git diff --cached --name-status` to verify that no out-of-scope target is included.
- Before Commit, `git diff --cached --check` MUST succeed.
- Before Commit, review remaining changes with `git status --porcelain=v1 -uall`.
- Before Push, verify the existence of `.git/HEAD`, `.git/config`, `.git/index`, `AGENTS.md`, `codex/README.md`, `HP/AGENTS.md`, and `HP/index.php`.
- After Push, verify the GitHub commit with `git ls-remote origin refs/heads/main`.

## 7. Git Damage STOP Conditions

STOP when any of the following occurs:

- `.git/HEAD` is missing.
- `.git/config` is missing.
- `.git/index` is missing.
- `git status` reports `not a git repository`.
- HEAD is unknown.
- The branch unexpectedly becomes `master`.
- Any of `AGENTS.md`, `codex/README.md`, `HP/AGENTS.md`, or `HP/index.php` is missing.

After stopping, report the affected scope, latest GitHub commit, available isolated copy, and recovery proposal. Do not perform recovery without explicit approval.

## 8. Git Recovery Procedure

Perform Git recovery only after explicit approval:

1. Stop writes to the damaged local working repository and inspect uncommitted and untracked files without modifying them.
2. Confirm the latest `origin/main` commit and any local-only changes required for recovery.
3. Prepare a recovery plan that clones GitHub into a separate empty directory without deleting, overwriting, or moving the damaged working repository.
4. After explicit approval, clone from GitHub into the local directory and verify the branch, upstream, remote, and `core.autocrlf`.
5. Restore only required local-only changes to the new clone. Do not copy the old `.git` directory into the new clone.
6. Verify `git status --short --branch`, HEAD against `origin/main`, and the protected targets.
7. Do not use an isolated NAS `.git` directory as a recovery source or Git working repository.
8. Report the recovery result, restored targets, and unresolved targets.

## 9. User Reporting Rules

Do not report only technical status codes to the user.

- `D`: tracked by GitHub but currently treated as deleted in the working repository
- `??`: not registered in GitHub
- `M`: modified
- Stage: prepared for inclusion in the next Commit
- Commit: recorded in local Git
- Push: synchronized to GitHub

Every problem report MUST state what is affected, how many items are affected, where they are, and the next required action.

## 10. Fixed Lessons from the Incident

- When instructed to delete "junk," classify targets first.
- Always exclude `.git` from deletion-candidate searches.
- Do not print an uncontrolled deletion list. Report the count and representative examples, and save the list only when required.
- Never treat "under the repository root" as sufficient evidence of safety.
- Distinguish physical deletion, removal from Git tracking, Stage, Commit, and Push.
- When the correct action is unclear, do not execute; answer the user's question and request the required decision.
