# CANDY Operation Basics

## 1. Responsibility

This is the short common procedure for investigating, fixing, and validating the existing HP site. For new-page generation, prioritize `CANDY_PAGE_GENERATION_GOVERNANCE.md`; for production work, prioritize `CANDY_PRODUCTION_MIGRATION_MASTER.md`.

## 2. Preflight

1. Read root `AGENTS.md` and `HP/AGENTS.md`.
2. Use `CANDY_MASTER_DOC_INDEX.md` to select the canonical document for the task.
3. Verify the Git root, branch, remote, and status.
4. Check overlap between target files and existing changes.
5. State the included work, excluded work, and completion evidence briefly.
6. When a target page exists, verify agreement between the generated ledger and actual files.

```powershell
git remote -v
git branch --show-current
git status --short --branch
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

Run Fetch or Pull only when the worktree and history make it safe. Commit and Push are not automatic end-of-task steps; run them only with explicit user instruction.

## 3. Investigation Unit

As required, review the following as one unit:

- Public PHP directly under HP
- Matching HTML under `HP/source/`
- `HP/includefile/dataset_*.php`
- `dataset_base.php`, `class.hpgcoder2.php`, and `funcs.php`
- CSS, JavaScript, images, and movies
- Source Text data
- Indexes, related pages, internal links, and sitemap
- Database, session, and external-integration dependencies

File and reference counts change. Count actual files instead of using fixed values in this document. Do not infer a specification from one file.

## 4. Before a Change

Confirm:

- Conclusion and reason for the change
- Changed and unchanged files
- Affected pages, desktop/mobile, and common processing
- Impact on databases, production, secrets, logs, and payments
- Validation method
- Unverified items and required user decisions

Obtain approval when an `AGENTS.md` change gate applies.

When `check --target` fails because of drift, identify the cause of the existing inconsistency first. Do not mix an existing inconsistency fix with separate new production or a feature change.

Do not create mechanical `.before` copies beside Git-tracked files. Use Git and the explicitly defined production rollback method. When an untracked asset or production file requires preservation, identify the target, destination, and recovery method before acting.

## 5. During a Change

- Change only the authorized scope.
- Do not overwrite existing changes.
- Validate replacement tokens, datasets, includes, links, and image references together.
- Do not set a fixed maximum file count.
- For a common-processing change, check impact on out-of-scope pages.
- Do not copy authentication values, database connection values, payment values, raw logs, or personal information.

## 6. After a Change

After changing an HP page, PHP, source, dataset, CSS, JavaScript, image, or SEO, update the generated documents and verify agreement before staging.

```powershell
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check
```

Then run at minimum:

```powershell
git status --short
git diff --stat
git diff --check
```

Add as required:

- PHP, HTML, or JavaScript syntax validation
- Generated-output validation
- Internal links and referenced images
- Desktop/mobile rendering
- JavaScript console
- Database, session, and external service behavior
- HTTP responses

Report every unexecuted check as unverified.

## 7. Production and Test

| Purpose | Path |
|---|---|
| Production | `/public_html/group/candy/` |
| Test | `/public_html/group_test/candy/` |

- The test environment is verified to exist.
- During phased migration, production `index.php` retains the 301 redirect to シティヘブン.
- Deploying the latest `HP/index.php` to production is the final public switchover and requires explicit approval.
- Use the explicit authority rules in root `AGENTS.md` and the publication procedure in `CANDY_PRODUCTION_MIGRATION_MASTER.md`. Do not infer upload authority from another instruction.
- A Push to `main` that contains deploy targets starts production Actions automatically. Actions generates the target SHA, target list, count, and `PLAN_TOKEN`, then verifies the same values before FTP connection.
- One deployment may contain at most 125 files and 50 MiB. Full deployment remains prohibited. Deletion and rename-source removal are permitted only inside an explicitly approved plan and MUST use reversible server-side staging with rollback before final cleanup.
- Do not infer included or excluded targets without inspecting the actual workflow and deploy script.
- Start and inspect normal Actions through Push and the GitHub API; do not require browser interaction. Manual preview/deploy is an exception route for incidents.
- Every operation excluded by root `AGENTS.md` requires separate explicit instruction.

See `CANDY_PRODUCTION_MIGRATION_MASTER.md` for details.

## 8. Unverified Scope

Treat the production PHP version, web-server type, actual database, and external-service settings as unverified until rechecked. Do not treat path candidates or values from old documents as current values. For information close to secrets, report only its location and not the value.

## 9. Completion Report

Separate only the applicable states:

- Local changes
- Syntax and static validation
- Commit
- Push
- Actions
- Production files
- HTTP
- Browser rendering
- Verification URLs

Do not report only "complete." State targets, counts, failures, unverified items, and unexecuted work.

Separate verification URLs by state. After Push, include the GitHub Commit URL; after Actions, include the run URL; after production deployment, include every target production URL in the same report. For multiple pages, list every target URL. When a URL is unavailable or unverified, do not infer it; report that state explicitly.
