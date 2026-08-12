# CANDY Production Deployment and Migration Control

## 1. Purpose
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Production deployment, protected-entry publication, recovery, rollback, and migration boundary
- Lifecycle: Active
- Source of Truth Responsibility: Canonical production deployment and migration control
- Related Documents: `CANDY_OPERATION_BASICS.md`, `CANDY_VERIFICATION_PLAN.md`, and exact workflows
- Related Implementation Files: Eligible `HP/` targets and the exact `.github/workflows/` and `.github/scripts/` deployment files

Control current `HP/` deployment, protected-entry publication, recovery, and
the remaining migration-history boundary while preventing rendering damage,
broken links, unintended redirect changes, and obsolete-file retention.

This document contains current production-deployment decision criteria. See
`CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` only for detailed 2026-07-13
incident history and `CANDY_VERIFICATION_PLAN.md` for full-population
validation.

## 2. Environments

| Purpose | Path or location | Handling |
|---|---|---|
| Latest local | `HP/` | Current development and production-deployment source |
| Historical production snapshot | `Backup/HP_旧データ/` | Legacy comparison data from its acquisition time; not current production state |
| Production server | `/public_html/group/candy/` | Actual public destination |
| Test server | `/public_html/group_test/candy/` | Test version created by the user during production |

Local paths vary by computer. Use the current Git root. Do not confuse production and test.

## 3. Production Entry Contract

### 3.1 Public root and protected index.php

- `https://www.55810.com/` MUST return `200` and serve the current CANDY top page.
- `https://www.55810.com/index.php` MUST return `301` to `https://www.55810.com/`.
- HTTP and non-www requests MUST return `301` to `https://www.55810.com/`.
- Direct verification through `http://firststar.kir.jp/group/candy/` MUST remain
  accessible and return `X-Robots-Tag: noindex`; the public canonical response
  MUST NOT inherit that header.
- `HP/index.php` is the current site entry point and remains excluded from every
  normal Push deployment.
- Publish or recover `HP/index.php` only through the protected manual `index`
  mode, using the exact target Commit, one-operation plan, plan token, and
  `DEPLOY-CANDY-INDEX` confirmation. The equal before/after SHA pins the exact
  committed snapshot being deployed; it is not a pending publication
  switchover.
- After any production deployment, run the common entry-contract check and the
  target-specific HTTP and rendering checks required by the operation.

### 3.2 Normal deployment targets

Normal PHP, include, source, CSS, JavaScript, image, and movie deployment MUST
leave protected `HP/index.php` and `HP/.htaccess` unchanged. Verify the common
entry contract separately from target-page correctness.

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
- After Push, the same job generates a plan without FTP, determines automatic approval values, validates them before FTP, and deploys to production.
- Manual `workflow_dispatch` preview/deploy remains an exception route for incident investigation and reruns.
- The deploy job uses the `candy-production` environment.
- Preview times out after five minutes; deploy after 30 minutes.
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
- After deployment, run the common entry-contract check for root `200`, HTTP
  and non-www canonical redirects, explicit index removal, public indexability,
  and direct-host noindex.

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

After changing workflow/script, run syntax and integration tests, then verify
the automatic GitHub run if the applicable Git and production routes selected
from `codex/WORK_ROUTING.md` Section 5.2 authorize publication.

### 5.1 Same-Path Static Asset Replacement and Client Cache Safety

This section is the canonical production rule for replacing the bytes of an existing public image, CSS, JavaScript, font, movie, or other cacheable static asset while retaining its canonical public path.

For a normal existing area-image pair replacement, `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` is the self-contained execution route. Do not require this full production-migration document unless that runbook routes the task here for a deployment exception, failure recovery, rollback, workflow change, deletion, rename, or manual server operation.

The final production state MUST contain the new bytes at the existing canonical filename. The obsolete bytes MUST NOT remain as another live copy. Temporary upload and rollback files MAY exist only during the transactional deployment procedure in Section 5 and MUST be removed after successful verification.

Before staging a same-path replacement:

1. Compute the SHA-256 of the new canonical asset.
2. Replace the local canonical asset at the same path; do not create a second live filename solely to perform the replacement.
3. Find every controlled public reference to that asset, including HTML `src`, OGP, CSS, JavaScript, and template or generated-source references that produce the public URL.
4. Replace any bare or previous content-version query with `?v=<content-version>`, where `<content-version>` is at least the first seven lowercase hexadecimal characters of the new SHA-256.
5. Deploy the asset and every required reference update in the same authorized work unit.

The query parameter does not create or preserve another asset file. It keeps the canonical filename and causes clients that cached the previous URL to request the new bytes through a changed URL.

A previously nonexistent public path does not require a replacement version solely because it is new. When an existing public path changes bytes and a controlled reference cannot be updated in the same work unit, do not report immediate client-visible replacement as verified.

For existing public area images matching `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-<slug>_[12].jpg`, `.github/scripts/candy_area_image_replacement_guard.py` enforces the accepted/public SHA-256 match, pair difference, `1000 x 750` JPEG dimensions, current hash-based content versions, and removal of the previous content-version URL. `.github/scripts/candy_ftp_deploy.py` invokes this guard before producing any FTP plan, and the production workflow runs its integration tests. A guard failure stops before an FTP connection or production mutation.

After deployment, verify all of the following:

- The canonical production path has the same SHA-256 as the new local asset.
- The production page or generated response contains the new content-version query.
- The versioned URL returns the new asset.
- Required desktop and mobile views render the new content rather than the obsolete cached content.
- No obsolete live copy, temporary file, or rollback file remains.

## 6. Historical Evidence Boundary

The failed 2026-07-13 bulk deployment, cleanup counts, HTTP snapshot, and local
migration snapshot are retained only in
`CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`. They are not current production
state or current procedure.

## 7. Production-Specific Pre-Deployment Procedure

The applicable Git route in `codex/WORK_ROUTING.md` Section 5.2 owns branch, remote,
staging, Commit, and Push checks. This document adds only the
production-specific gates:

1. Inspect the exact workflow and deploy script used by the operation.
2. Run their syntax, self-test, and integration checks.
3. Confirm the expected trigger and absence of a full-deploy route.
4. Verify target SHA, lists, exclusions, deletion/rename state, operation count,
   uploaded size, and `PLAN_TOKEN`.
5. Require target PHP to pass `php -d short_open_tag=1 -l` before FTP.
6. After Actions succeeds, verify the common production entry contract,
   target-page HTTP, and production URLs.

Normal tracking command:

```powershell
python .github/scripts/candy_release_check.py --sha <40-character-Commit-SHA> --url <production-URL> --expect-text <target-page-specific-text>
```

Protected or manual deployment routes run the entry check directly:

```powershell
python .github/scripts/candy_release_check.py --entry-only
```

## 8. During Deployment

- Record Actions run number and commit SHA.
- Record and report the Actions run URL with progress.
- Normally query state through the GitHub API; do not search and operate the browser UI.
- Check actual log `DEPLOYED current/total`, failing target, and exit code.

## 9. After Deployment

1. Verify the final Actions result.
2. Verify production target existence and SHA-256 equality.
3. Check for remaining temporary and backup files.
4. Verify root `200`, `index.php` to root, canonical host and scheme redirects,
   public indexability, and direct-host noindex through the common entry check.
5. Check target-PHP HTTP.
6. Check CSS/JavaScript/images, internal links, and desktop/mobile browser rendering as required. For a same-path static-asset replacement, apply Section 5.1 and verify the new content-version reference and rendered content.
7. Add the target SHA, Actions run, deployed targets, production hashes, HTTP
   results, and rendering results to the response required by root `AGENTS.md`.

## 10. Rollback

- A single-target failure during deploy rolls back the failing target and every target already deployed in that run in reverse order. Do not delete backups before every target validates.
- After Actions completes, rollback is separate work requiring verification of the commit/file to restore, server path, index impact, and database/external dependencies.
- Do not bulk-delete server files or overwrite from a legacy snapshot without evidence.
- `Backup/HP_旧データ` is a production comparison snapshot from its acquisition time and is not assumed identical to current production.

## 11. Current-State Boundary

This document does not store current remaining work, deployment history, current
server inventory, or unresolved website defects. Obtain those from actual
workflow runs, production evidence, `PROJECT_STATUS.md`, and the applicable
generated current-state documents selected by `codex/WORK_ROUTING.md`.
