# CANDY Area Staff Production Runbook

- Updated: 2026-07-14
- Applies to: Normal production of one area page
- Start condition: Explicit instruction to produce or publish an area page
- Completion criteria: Dedicated validation succeeds and the authorized local or publication scope completes

## 1. Standard Execution

For the exact user request to create the next page, upload it, and report the production URL, run only:

```powershell
codex\scripts\candy-area.cmd publish-next
```

Explicit target:

```powershell
codex\scripts\candy-area.cmd publish --input "Text_area_data/対象.txt"
```

Without production operations:

```powershell
codex\scripts\candy-area.cmd build --input "Text_area_data/対象.txt"
codex\scripts\candy-area.cmd check --input "Text_area_data/対象.txt"
```

When required images are absent, read `CANDY_AREA_IMAGE_CREATION_SPEC.md` before running these commands. Produce images only when storage, modification, commercial-publication, and attribution conditions for their source are verified. Otherwise STOP and do not proceed to page generation or publication.

Run these commands only when investigating exceptions across the full input population:

```powershell
codex\scripts\candy-area.cmd audit-inputs
codex\scripts\candy-area.cmd audit-inputs --render
```

For the normal path, do not add preliminary `build`, `check`, full-document rereading, or intermediate questions.

### 1.1 Production Order and New-Page Target Gate

Do not select the target manually. In this order, use only the first target for which the dedicated gate returns `NEW_PAGE_TARGET_OK=<slug>`:

1. Review direct text files under `Text_area_data` in filename order.
2. Review the latest `Text_area_data/分類_*/01_間違い無し` in filename order.
3. Automatically exclude a slug with an existing public PHP file, source HTML, dataset PHP file, `dataset_base.php` registration, or sitemap registration.
4. Require exactly one target-slug link in the area index. When the area index has the same region name under another slug, automatically exclude it as a same-region/different-slug candidate.
5. Automatically exclude a slug without both required images.
6. Produce only the first target that passes.

`01_間違い無し` classifies text-file content; it does not mean a new page is eligible for production.

```powershell
codex\scripts\candy-area.cmd target-next
```

When actually producing a candidate from the classification folder, restore only one file to the normal location:

```powershell
codex\scripts\candy-area.cmd target-next --restore
```

Validate an explicit target:

```powershell
codex\scripts\candy-area.cmd target-check --input "Text_area_data/対象.txt"
```

Do not run `publish` for a target that does not return `NEW_PAGE_TARGET_OK=<slug>`. If an existing file, existing registration, same-region/different-slug value, legacy slug, or similar slug appears, do not proceed; restart target selection.

## 2. Integrated Workflow

`publish-next` runs:

1. Select the first `READY_CANDIDATE` queue row.
2. Validate Text, slug, images, existing files, shared registrations, Git, and remote.
3. Generate the complete page set from templates.
4. Run static validation and synchronize generated management documents with `candy-site-state write` and `check`.
5. Verify the stage allowlist.
6. Commit and Push only the target, once each.
7. Verify Actions and production HTTP.
8. Output the production URL, Commit URL, and Actions URL.

Do not update `.md` solely to record publication after the fact, create a management-document-only Commit, or Push a second time. GitHub, Actions, and production HTTP are the canonical publication evidence.

## 3. Generation Rules

- Input: `Text_area_data/対象.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-area.html`
- Shops: `HP/source/template_shop.html`
- Give shops, travel times, and transportation fees from source Text highest priority.
- When shops are unspecified, use a combination with low frequency among existing pages.
- When a value is unspecified, use map coordinates and settings for the same shop from nearby complete pages.
- Match shops, articles, hotels, spots, and telephone numbers to input counts.
- Do not infer a value, image, or URL absent from source data.
- Preserve the eight template dummy related-article entries until actual links are configured.
- Add known exceptions to the dedicated tool; do not create page-specific improvised handling.

Created or updated targets:

```text
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php
HP/includefile/dataset_base.php
HP/source/area.html
HP/sitemap.xml
one target row in codex/docs/CANDY_AREA_105_PAGE_QUEUE.md
```

After generation or a fix and before staging, run `codex\scripts\candy-site-state.cmd write` and `check`. Treat the queue update and generated-document update as the same work unit.

## 4. Validation

The dedicated tool validates the following. Do not repeat successful checks manually.

- Required input, canonical value, slug, and two images
- Three page files and shared registrations
- Shop order, travel times, and transportation fees
- Scenes, IDs, FAQ, and JSON-LD
- Eight reserved dummy related articles or actual links
- Area index, sitemap, and internal links
- PHP lint, JSON, images, and diff
- Stage targets, deletion, rename, and unauthorized changes
- Remote, Push, Actions, production page and images, index, sitemap, and redirects

When local PHP CLI is absent, use `PHP_LINT=UNAVAILABLE`. Production publication requires successful pre-FTP PHP lint in Actions.

## 5. Queue Rules

- Use the queue only for production order and duplication prevention.
- Use one row per slug and do not create separate batch history.
- `publish-next` selects only `READY_CANDIDATE`.
- After build, set the target row to `LOCAL_COMPLETE` or `IN_PROGRESS`.
- Do not add publication results to the queue after publication.
- Verify actual publication state through Commit, Actions, and production HTTP.

## 6. STOP Conditions

- The branch is not `main`, the remote differs, fast-forward is impossible, or a conflict exists.
- Existing changes to the target or a shared file cannot be preserved.
- Input or images are missing, a slug differs, or a same-name file conflicts.
- A legacy slug, similar slug, or typo would require automatic replacement.
- A shop is unknown, a shared registration is duplicated, or the area index or sitemap is inconsistent.
- PHP, JSON, stage allowlist, Actions, or production HTTP validation fails.
- Deletion, rename, database access, a secret value, or production switchover of `index.php` is required.

On STOP, output the stopping point, completed state, unexecuted state, and rerun command. Do not replace with another slug automatically.

## 7. User Report

On success, report only:

```text
結論: 本番反映済み
作成ページ:
本番URL:
Commit URL:
Actions URL:
未確認:
```

If browser rendering was not performed, state that it is unverified. The target for one normal page is no more than five minutes from instruction to production-URL report.
