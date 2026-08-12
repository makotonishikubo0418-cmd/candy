# CANDY Area Staff Production Runbook
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Normal production and publication sequence for one area page
- Lifecycle: Active
- Source of Truth Responsibility: Canonical normal area-page execution runbook
- Related Documents: `CANDY_PAGE_GENERATION_GOVERNANCE.md`, `CANDY_AREA_105_PAGE_QUEUE.md`, and area image documents
- Related Implementation Files: Area production wrapper, generator, validator, target Text/images, HP page, indexes, and sitemap
- Purpose: Define the normal production and publication sequence for one area page

- Updated: 2026-07-26
- Applies to: Normal production of one area page
- Start condition: Explicit instruction to produce or publish an area page
- Completion criteria: Dedicated validation succeeds and the authorized local or publication scope completes

## 1. Standard Execution

For the exact user request to create the next page, upload it, and report the production URL, run only:

Before running any build or publication command, confirm that the dedicated
workflow plans and validates the top-page area-section update required by
Section 10.1 of `CANDY_PAGE_GENERATION_GOVERNANCE.md`. If it does not, STOP
before generation.

```powershell
codex\scripts\candy-area.cmd publish-next
```

`publish-next` is the complete normal target-selection route. Its dedicated
gate reads `READY_CANDIDATE` rows from `CANDY_AREA_105_PAGE_QUEUE.md` and checks
the actual target files and registrations. Do not read, print, or extract the
full `generated/CANDY_UPCOMING_PAGES.md` to choose the normal target, and do not
activate the separate candidate-reporting route merely because `publish-next`
selects a target internally. Use the generated current-state document only
when the user explicitly requests a candidate report or a verified exception
requires it.

Explicit target:

```powershell
codex\scripts\candy-area.cmd publish --input "Text_area_data/対象.txt"
```

Without production operations:

```powershell
codex\scripts\candy-area.cmd build --input "Text_area_data/対象.txt"
codex\scripts\candy-area.cmd check --input "Text_area_data/対象.txt"
```

Before target selection, treat a complete pair under
`Text_area_data/画像データ/` as available production input. After selecting
the target and before the final target gate, copy exact accepted bytes to
`HP/imgHtml/new_202601/area/` when both same-name public files are absent. A
pending first installation is not a missing-image failure when the applicable
authorized routes selected from `codex/WORK_ROUTING.md` Section 5.2 include
installation. When the accepted pair itself is absent, image creation and
acceptance belong to the applicable image route selected from `codex/WORK_ROUTING.md`
Section 5.2.

Run these commands only when investigating exceptions across the full input population:

```powershell
codex\scripts\candy-area.cmd audit-inputs
codex\scripts\candy-area.cmd audit-inputs --render
```

For the normal path, do not add redundant preliminary `build` or `check`
commands when `publish-next` already performs the same authoritative gates.

### 1.1 Production Order and New-Page Target Gate

Do not select the target manually. Work through `READY_CANDIDATE` rows in
`CANDY_AREA_105_PAGE_QUEUE.md` from the smallest queue number. Candidate
discovery MUST recognize preparation states that the same page-production
workflow can resolve target-locally. Use the first row that passes the
following ordered preparation and final-gate process:

1. Resolve the queue row to exactly one tracked input, using a direct Text under `Text_area_data` or the latest `Text_area_data/分類_*/01_間違い無し`.
2. Automatically exclude a slug with an existing public PHP file, source HTML, dataset PHP file, `dataset_base.php` registration, or sitemap registration.
3. Exclude an area index entry for the same region under another slug. A
   missing target-slug link in either the area index or the matching top-page
   service-area name is not a blocker; mark both for one target-limited
   addition during generation. Preserve one existing correct target-slug link
   in each location.
4. Accept either a complete accepted-source pair or a complete local-public
   pair as image availability. After target selection, first-install the
   accepted pair when the local-public pair is absent. Exclude only a target
   with no complete pair or with a partial, slug-conflicting, or same-name
   hash-conflicting pair.
5. Run the final target gate after image first installation and the planned
   category-index and top-page additions are fixed for the target. Require
   `NEW_PAGE_TARGET_OK=<slug>` at that point.
6. Produce only the first target that passes. Once production starts for that
   target, STOP on a later failure and do not substitute another slug.

`01_間違い無し` classifies text-file content; it does not mean a new page is eligible for production.

```powershell
codex\scripts\candy-area.cmd target-next
```

Use the bounded `target-next` output as the authoritative normal candidate
result. If it stops, investigate only the reported queue row, slug, source,
and reasons. Do not replace that bounded result with a full generated-document
dump.

Keep a classified input at its tracked source path. `publish-next` passes that source directly to the publisher; do not restore or copy it to the Text root before publication.

Validate an explicit target:

```powershell
codex\scripts\candy-area.cmd target-check --input "Text_area_data/対象.txt"
```

Do not run `publish` for a target that does not return
`NEW_PAGE_TARGET_OK=<slug>` after the authorized preparation above. Do not
interpret a pending first image installation or a missing target-slug index
link as a final STOP. If an existing file, existing registration,
same-region/different-slug value, legacy slug, similar slug, partial image
pair, or same-name image hash conflict appears, do not proceed; restart target
selection or use the applicable exception route.

## 2. Integrated Workflow

`publish-next` runs:

1. Select the first `READY_CANDIDATE` queue row that can pass after the
   target-limited preparation in Section 1.1.
2. First-install a complete accepted image pair when required, and plan one
   missing target-slug area-index link plus the matching top-page area link as
   normal generation output.
3. Validate Text, slug, images, existing files, and shared registrations.
4. Generate the complete page set from templates, including the category-index
   and top-page links.
5. Run static validation and synchronize sitemap dates and generated management documents with `candy-site-state preview-sitemap-lastmod`, `sync-sitemap-lastmod`, `write`, and `check`.
6. When publication is included, continue through the applicable Git and
   production routes selected from `codex/WORK_ROUTING.md` Section 5.2 and verify
   Actions and production HTTP.

## 3. Generation Rules

- Input: `Text_area_data/対象.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-area.html`
- Shops: `HP/source/template_shop.html`
- Give shops, travel times, and transportation fees from source Text highest priority.
- When shops are unspecified, use a combination with low frequency among existing pages.
- When a value is unspecified, use map coordinates and settings for the same shop from nearby complete pages.
- Match shops, articles, hotels, spots, and telephone numbers to input counts.
- Do not infer a value, image, or URL absent from source data.
- Configure `周辺の対応エリア` from `codex/data/CANDY_AREA_RELATED_LINKS.json`. Use three to six verified nearby published area links, normally four, with link text `鹿児島市{地域名}で呼べるデリヘル`; omit the block when fewer than three suitable targets exist.
- Add known exceptions to the dedicated tool; do not create page-specific improvised handling.

Created or updated targets:

```text
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php
HP/includefile/dataset_base.php
HP/source/area.html
HP/source/index.html
HP/sitemap.xml
one target row in codex/docs/CANDY_AREA_105_PAGE_QUEUE.md
one target entry in codex/data/CANDY_AREA_RELATED_LINKS.json
the accepted/public image pair when first local installation is required
```

After generation or a fix and before staging, run `codex\scripts\candy-site-state.cmd preview-sitemap-lastmod`, `sync-sitemap-lastmod`, `write`, and `check`. Treat the queue update and generated-document update as the same work unit.

## 4. Validation

The dedicated tool validates the following. Do not repeat successful checks manually.

- Required input, canonical value, slug, and two images
- Three page files and shared registrations
- Shop order, travel times, and transportation fees
- Scenes, IDs, FAQ, and JSON-LD
- Exact nearby-area links from the canonical mapping, with no dummy, self-link, duplicate, missing target, or unapproved link text
- Area index, top-page area section, sitemap, and internal links under the
  common public-route synchronization contract
- PHP lint, JSON, images, and diff
- Production page and images, area index, top page, sitemap, and redirects when
  publication is included

When local PHP CLI is absent, use `PHP_LINT=UNAVAILABLE`. Production publication requires successful pre-FTP PHP lint in Actions.

## 5. Queue Rules

- Use the queue only for production order and duplication prevention.
- Use one row per slug and do not create separate batch history.
- `publish-next` selects only `READY_CANDIDATE`.
- After build, set the target row to `LOCAL_COMPLETE` or `IN_PROGRESS`.
- Do not add publication results to the queue after publication.

## 6. STOP Conditions

- Existing changes to the target or a shared file cannot be preserved.
- Input is incomplete, no complete accepted or public image pair exists, an
  image pair is partial or same-name hashes conflict, a slug differs, or a
  same-name page file conflicts. Public-image absence alone is not a STOP when
  a complete accepted pair exists.
- A legacy slug, similar slug, or typo would require automatic replacement.
- A shop is unknown, a shared registration is duplicated, the area index
  or top-page area section contains the same region under another slug, or
  another area-index, top-page, or sitemap inconsistency cannot be resolved
  within the target change unit.
- PHP, JSON, Actions, or production HTTP validation fails.

On STOP, add the stopping point, completed state, unexecuted state, and rerun command to the response required by root `AGENTS.md`. Do not replace with another slug automatically.

## 7. User Report

In addition to the common response structure in root `AGENTS.md`, report these
area-publication facts:

```text
作成ページ:
本番URL:
Commit URL:
Actions URL:
未確認:
```

If browser rendering was not performed, include that page-specific fact in the
unverified field.
