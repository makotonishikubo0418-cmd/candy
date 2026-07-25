# Safety Protocol for Deletion, Movement, and Bulk Operations

- Purpose: Prevent damage to the working repository during deletion, movement, bulk cleanup, or Git recovery
- Status: canonical document
- Updated: 2026-07-25

## 1. Scope

This document MUST be applied to:

- File deletion
- Removal from Git tracking
- File movement, renaming, and folder cleanup
- Recursive operations such as `Get-ChildItem -Recurse`
- Git staging, Commit, or Push that includes deletion, movement, cleanup, or recovery
- `.git` damage and Git working-repository recovery
- Bulk cleanup of generated files, logs, caches, or untracked files

## 2. Prohibited Operations

The following operations are prohibited:

- Deletion, movement, staging, Commit, or Push before the target list is fixed
- Execution while targets are described only with vague labels such as "junk" or "unorganized"
- `git clean` or `git reset --hard`
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

1. The cumulative `AGENTS.md` routes for the operation are satisfied.
2. The target list is fixed.
3. Every target has one classification from Section 4.
4. The target list does not include an unauthorized protected target.
5. Existing changes and recovery evidence are understood.

## 6. Deletion-Specific Git Checks

- Follow the Git procedure in `DOCUMENT_RULES.md`; this section adds only deletion-specific checks.
- After staging, use `git diff --cached --name-status` to verify that no out-of-scope target is included.
- Before Push, verify the existence of `.git/HEAD`, `.git/config`, `.git/index`, root `AGENTS.md`, `codex/README.md`, and `HP/index.php`.

## 7. Git Damage STOP Conditions

STOP when any of the following occurs:

- `.git/HEAD` is missing.
- `.git/config` is missing.
- `.git/index` is missing.
- `git status` reports `not a git repository`.
- HEAD is unknown.
- The branch unexpectedly becomes `master`.
- Any of root `AGENTS.md`, `codex/README.md`, or `HP/index.php` is missing.

After stopping, report the affected scope, latest GitHub commit, available
isolated copy, and recovery proposal. Recovery proceeds only through the
cumulative root route.

## 8. Git Recovery Procedure

Perform Git recovery only when it is included by the cumulative root route:

1. Stop writes to the damaged local working repository and inspect uncommitted and untracked files without modifying them.
2. Confirm the latest `origin/main` commit and any local-only changes required for recovery.
3. Prepare a recovery plan that clones GitHub into a separate empty directory without deleting, overwriting, or moving the damaged working repository.
4. Clone from GitHub into the selected local directory and verify the branch,
   upstream, remote, and `core.autocrlf`.
5. Restore only required local-only changes to the new clone. Do not copy the old `.git` directory into the new clone.
6. Verify `git status --short --branch`, HEAD against `origin/main`, and the protected targets.
7. Do not use an isolated NAS `.git` directory as a recovery source or Git working repository.
8. Report the recovery result, restored targets, and unresolved targets.

## 9. Deletion-Specific Lessons

- When instructed to delete "junk," classify targets first.
- Always exclude `.git` from deletion-candidate searches.
- Do not print an uncontrolled deletion list. Report the count and representative examples, and save the list only when required.
- Never treat "under the repository root" as sufficient evidence of safety.
- Distinguish physical deletion from removal from Git tracking.
