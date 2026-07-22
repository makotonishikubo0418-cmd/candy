# CANDY Production Migration and Automated Deployment

## 1. Purpose

Deploy the latest `HP/` to KAGOYA production in phases while preventing rendering damage, broken links, unintended redirect removal, and obsolete-file retention.

This document contains production-migration decision criteria. See `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` for detailed 2026-07-13 context and incident history and `CANDY_VERIFICATION_PLAN.md` for full-population validation.

## 2. Environments

| Purpose | Path or location | Handling |
|---|---|---|
| Latest local | `HP/` | Development and production data for future deployment |
| Production snapshot | `Backup/HP_旧データ/` | Legacy data downloaded again from production by the user at the acquisition time |
| Production server | `/public_html/group/candy/` | Actual public destination |
| Test server | `/public_html/group_test/candy/` | Test version created by the user during production |

Local paths vary by computer. Use the current Git root. Do not confuse production and test.

## 3. Primary Publication-Switchover Rules

### 3.1 Production index.php

- Production-snapshot `index.php` sends a `301` redirect to シティヘブン.
- Latest `HP/index.php` is the new-site entry point and has a different responsibility.
- Preserve the production redirecting `index.php` during phased migration.
- Exclude latest `HP/index.php` from Push, preview, and deploy.
- Deploy latest `HP/index.php` alone only after all preparation completes and the user explicitly instructs final publication switchover.
- After deployment, verify that the top redirect ended, HTTP, rendering, desktop/mobile, and primary routes.

### 3.2 Targets Other Than index

PHP, include, source, CSS, JavaScript, images, and movies may be deployed before the final switchover while preserving the redirecting index. Verify redirect preservation separately from other-page correctness.

## 4. Current GitHub Actions Design

Workflow:

```text
.github/workflows/candy-production-deploy.yml
.github/workflows/candy-htaccess-deploy.yml
```

Deploy script:

```text
.github/scripts/candy_ftp_deploy.py
```

### 4.1 Trigger

- A Push to `main` containing deploy targets starts production processing automatically.
- Use the explicit authority rules in root `AGENTS.md`; do not infer upload authority from another instruction.
- After Push, the same job generates a plan without FTP, determines automatic approval values, validates them before FTP, and deploys to production.
- Manual `workflow_dispatch` preview/deploy remains an exception route for incident investigation and reruns.
- The deploy job uses the `candy-production` environment.
- Preview times out after five minutes; deploy after ten minutes.
- Concurrency prohibits simultaneous deploys.
- Use the GitHub API as the normal Actions start and monitoring route; do not depend on browser UI or an expired GitHub CLI session.

### 4.2 Automatic Approval Gate

Push-triggered Actions generates:

- 40-character comparison-source commit SHA
- 40-character target commit SHA
- Deploy and exclusion lists
- Deletion and rename presence
- Deployment-operation count, including uploads and approved deletions
- `PLAN_TOKEN` containing SHA-256 for each target

FTP connection is permitted only when the following exactly match the plan from the same Actions run:

- Comparison-source and target SHAs
- Deployment-operation count
- `PLAN_TOKEN`
- Automatic confirmation phrase `DEPLOY-CANDY-PRODUCTION`

Any mismatch fails before FTP connection. The normal route applies this safety gate mechanically without waiting for manual plan confirmation.

### 4.3 Hard Limits and Prohibited Routes

- One deploy is limited to 125 upload-and-delete operations and 50 MiB of uploaded data. Approved deletions and rename-source removals require reversible server-side staging and rollback support.
- After changing deployment automation, prove the behavior with a small real batch before using larger batches; every batch remains within the 125-operation and 50 MiB limits.
- No full-deploy route exists.
- A Git deletion or rename stops before FTP unless the exact removal is included in the plan token and the transactional deletion gate passes.
- Do not delete files that exist only on the server.
- Target SHA MUST exactly match checked-out HEAD.
- Comparison-source SHA MUST be an ancestor of target SHA.

### 4.4 Protection and Exclusion

Primary exclusions verified from actual workflow/script:

- `HP/index.php`
- `HP/.htaccess` in every normal Push or general manual deployment
- `HP/AGENTS.md`
- `codex/`
- `HP/log/`
- `Text_area_data/`
- `Text_blog_data/`
- `Text_hotel_data/`
- `HP/.well-known/`
- Markdown
- `.env`
- `.bak`, `.backup`, and `.zip`
- `.candy-backup-*` and `.candy-upload-*`

Do not infer this list for a future workflow; recheck actual preview output.

### 4.5 Protected `.htaccess` Exception

`.github/workflows/candy-htaccess-deploy.yml` is the only approved exception for production `.htaccess` publication.

- It runs only through `workflow_dispatch`; Push MUST NOT start it.
- Preview MUST run before deploy and MUST use exact 40-character comparison SHAs.
- The comparison MUST contain exactly one modified HP target: `HP/.htaccess`.
- `HP/index.php` remains protected and MUST NOT be accepted by this exception.
- Deploy requires operation count `1`, the exact preview `PLAN_TOKEN`, and confirmation `DEPLOY-CANDY-HTACCESS`.
- The normal transactional upload, SHA-256 verification, backup, and automatic rollback procedure remains mandatory.
- After deployment, verify HTTP, non-www, canonical HTTPS, explicit index removal, and preservation of the intentional top-page redirect.

## 5. FTP Deployment Safety Requirements

Before FTP connection, validate 40-character SHAs, ancestor relationship, checked-out HEAD, deployment-operation count, 125-operation maximum, 50 MiB of uploaded data, `PLAN_TOKEN`, and confirmation phrase. On failure, STOP without using FTP secrets.

For each target, retain backups until every target validates:

1. Upload with a temporary name.
2. Download the temporary file and compare SHA-256.
3. When an existing file exists, rename it to a temporary backup name.
4. Promote the temporary file to the final name.
5. Download the final name and compare SHA-256 again.
6. Delete backups only after every target final name and SHA-256 validates.
7. Output `current-count/total-count` immediately.

An approved deletion-only plan is valid. Each existing target is first renamed to a run-specific backup, every deletion is staged before any backup is removed, and failures restore staged targets. After successful backup removal, only directories emptied by those approved deletions are removed, deepest first; the production root is never removed.

On failure:

- Restore the failing target and roll back every target already deployed by the same run in reverse order.
- Do not report production deployment successful before rollback completes.
- Report failure position, target, and rollback result.
- Verify on the actual server that no temporary or backup file remains.

After changing workflow/script, run syntax and integration tests, Commit and Push, and verify the automatic GitHub run before reporting new behavior.

## 6. Production Work Results on 2026-07-13

### 6.1 Automated Full Deploy: Failed and Deprecated

- The legacy bulk method ran for an excessive time.
- Many backup and temporary files remained, and actual progress differed from reports.
- Controlled completion could not be verified; the user manually deployed root PHP through WinSCP.
- Therefore, do not use that Actions run as evidence of successful full deployment.

### 6.2 Cleanup

After the user's manual deployment, these confirmed unnecessary production targets were deleted:

| Target | Count |
|---|---:|
| `.candy-backup-*` | 319 |
| Server `.gitignore` | 1 |
| FTP smoke test | 1 |
| Total | 321 |

After deletion:

- Production root PHP: 100
- Production `index.php`: redirect preserved
- Production inventory: 1,428 files and 29 directories

This is not a record that verifies SHA-256 equality for every inventory file. Reacquire when required.

### 6.3 HTTP

- 99 of 100 public PHP files returned `200`.
- `index.php` returned the intended `301`.
- Unexpected PHP statuses: 0.

This is a 2026-07-13 snapshot and requires revalidation for current state.

## 7. Local Migration State on 2026-07-13

At record time:

| Target | State |
|---|---|
| PHP directly under HP | 100 |
| Production `group/candy` include references | 97 |
| Test `group_test/candy` include references | 2 |
| PHP without dataset include | `makeSitemap.php` |

Entry points retaining test references:

- `HP/kagoshima-deliveryhealth-petitegirl.php`
- `HP/kagoshima-deliveryhealth-slendergirl.php`

Do not bulk-replace these before determining whether they are intended exceptions or incomplete migration. Recheck absolute paths, session, control, and source transformation in actual `dataset_base.php` and related files.

## 8. Required Pre-Deployment Procedure

1. Review root and HP `AGENTS.md`.
2. Verify branch, remote, status, HEAD, and `origin/main`.
3. Check overlap between target and existing changes.
4. Reconcile the planned work with related `.md` records.
5. Compare staged `name-status` to the allowlist and exclude out-of-scope changes, deletions, renames, copies, and type changes.
6. Run workflow/deploy-script syntax, self-test, and `test_candy_ftp_deploy.py` integration tests.
7. Verify a Push trigger exists and no full-deploy route exists.
8. On explicit upload instruction, stage and Commit only current targets.
9. After Fetch, Push to `main` only if the remote has no leading update.
10. Retrieve the Push-triggered Actions run through the GitHub API.
11. Verify target SHA, lists, exclusions, deletion/rename, count, and `PLAN_TOKEN`.
12. Verify no more than 125 upload-and-delete operations, no more than 50 MiB of uploaded data, and protected targets such as `index.php` are excluded. Oversize MUST stop before FTP. Deletion or rename MUST stop unless the exact plan is explicitly approved and the transactional rollback gate passes.
13. Proceed to FTP only after target PHP succeeds with `php -d short_open_tag=1 -l`.
14. After Actions succeeds, verify target-page HTTP and production URLs.

Normal tracking command:

```powershell
python .github/scripts/candy_release_check.py --sha <40-character-Commit-SHA> --url <production-URL> --expect-text <target-page-specific-text>
```

## 9. During Deployment

- Record Actions run number and commit SHA.
- Record and report the Actions run URL with progress.
- Normally query state through the GitHub API; do not search and operate the browser UI.
- Check actual log `DEPLOYED current/total`, failing target, and exit code.
- Do not report unsupported remaining time. Estimate only from measured rate and remaining count.
- When the user orders a stop, verify both the stop operation and actual stopped state.
- Do not report monitoring while only watching a GUI.

## 10. After Deployment

1. Verify the final Actions result.
2. Verify production target existence and SHA-256 equality.
3. Check for remaining temporary and backup files.
4. Verify preservation of the production `index.php` redirect.
5. Check target-PHP HTTP.
6. Check CSS/JavaScript/images, internal links, and desktop/mobile browser rendering as required.
7. Report local, GitHub, and production results separately.
8. Include the GitHub Commit URL, Actions run URL, and every deployed production page URL in one report. Do not infer an unverified URL.

## 11. Rollback

- A single-target failure during deploy rolls back the failing target and every target already deployed in that run in reverse order. Do not delete backups before every target validates.
- After Actions completes, rollback is separate work requiring verification of the commit/file to restore, server path, index impact, and database/external dependencies.
- Do not bulk-delete server files or overwrite from a legacy snapshot without evidence.
- `Backup/HP_旧データ` is a production comparison snapshot from its acquisition time and is not assumed identical to current production.

## 12. Current Remaining Work

- After changing the Push-triggered workflow, verify success of the automatic run for the target Commit.
- Verify whether the GitHub `candy-production` environment has required protection rules.
- Confirm intent for the two remaining test includes.
- Perform complete hash reconciliation between production inventory and Git-tracked targets.
- Decide fixes for missing internal links, images, and external URLs recorded in `CANDY_VERIFICATION_PLAN.md`.
- Approve the final switchover date and standalone deployment procedure for latest `HP/index.php`.

Do not report production migration 100% complete while any item remains.
