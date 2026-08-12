# CANDY Hotel Staff Production Runbook
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Normal production and publication sequence for standard hotel pages
- Lifecycle: Active
- Source of Truth Responsibility: Canonical normal hotel-page execution runbook
- Related Documents: Hotel input, image, page specification, and common generation governance
- Related Implementation Files: Hotel production wrapper, generator, validator, target Text/images, HP pages, indexes, and sitemap
- Purpose: Define the normal production and publication sequence for standard hotel pages

- Updated: 2026-08-09
- Applies to: Normal production of one or more standard hotel pages from either staff-completed Text or Phase-prepared Text
- Start condition: Explicit instruction to produce or publish one or more hotel pages
- Completion criteria: Every requested target completes its dedicated validation and authorized local or publication scope; otherwise the run stops with completed, failed, and unexecuted targets distinguished

## 1. Independent Source Routes

This runbook has two independent source routes. Select exactly one route for the target and do not require the other route's evidence.

### 1.1 `DIRECT_TEXT`

Use `DIRECT_TEXT` when staff already completed the production input under `Text_hotel_data/`. Phase 1-4 results and Phase hash records are not required.

Before `direct-check`, inspect the Text format:

```powershell
codex\scripts\candy-hotel.cmd legacy-check --input "Text_hotel_data/対象ホテル.txt"
```

- `CURRENT_TEXT_STATUS=VALID` means no format conversion is required.
- `LEGACY_TEXT_STATUS=READY_TO_CONVERT` means the source can be converted through the contract in `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`, then checked again.
- `LEGACY_TEXT_STATUS=STOP` means missing, conflicting, ambiguous, or placeholder data must be corrected before conversion. Do not infer it.

A successfully converted legacy Text remains `SOURCE_ROUTE: DIRECT_TEXT`; migration is not a Phase result or a third source route.

Required inputs:

```text
SOURCE_ROUTE: DIRECT_TEXT
TARGET_TEXT_PATH: Text_hotel_data/<hotel-name>.txt
Authorized scope: local build / publication
```

Run:

```powershell
codex\scripts\candy-hotel.cmd direct-check --input "Text_hotel_data/対象ホテル.txt"
```

- `DIRECT_TEXT_STATUS=READY_FOR_IMAGES` means that the Text, slug, Git tracking,
  and new-page state passed but no complete accepted or local-public image pair
  exists. Create and validate the pair through the `DIRECT_TEXT` start route in
  `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`, accept and install it through
  `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`, then rerun `direct-check`. When a
  complete accepted pair already exists and only the local-public pair is
  absent, treat it as upstream input for the separate image-asset route, not
  as page-publication readiness. Complete first installation and the required
  image lifecycle before invoking automatic page publication.
- `DIRECT_TEXT_STATUS=READY_FOR_BUILD` means that the completed Text and both locally installed public images are ready for the normal target gate and local build. For publication, a newly accepted pair must first reach `DEPLOYED_ASSET` through `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`; an unchanged legacy public-only pair must already be tracked and clean.
- `DIRECT_TEXT_STATUS=STOP` means that the Text is incomplete, untracked, duplicated, already registered, or otherwise ineligible. Do not invoke a Phase solely to bypass that blocker.

### 1.2 `PHASE_PREPARED`

Use `PHASE_PREPARED` when the hotel input is being researched and completed through the Phase instructions.

Required inputs:

```text
SOURCE_ROUTE: PHASE_PREPARED
PHASE 1 result:
PHASE 2 result:
PHASE 3 result:
PHASE 4 result:
TARGET_TEXT_PATH: Text_hotel_data/<hotel-name>.txt
CANONICAL_SLUG:
PUBLIC_URL:
Authorized scope: local build / publication
```

Start local build only when Phases 1-4 are all `PASS`, Phase 4 records `READY_FOR_PHASE_5: YES`, the Text hash chain is intact, the image lifecycle is at least `INSTALLED_LOCAL`, and both accepted/public same-name hashes match the Phase 4 result. Start publication only after a newly accepted pair reaches `DEPLOYED_ASSET`.

### 1.3 Common Production Contract

For either route, the target Text MUST contain every required field and no placeholder or partial block. `TARGET_TEXT_PATH`, canonical slug, image paths, public URL, and actual image files MUST agree, and `target-check` MUST return `NEW_HOTEL_TARGET_OK=<slug>` before build or publication.

The target Text is the only page-content input. Phase result files are evidence only for `PHASE_PREPARED`. The dedicated tool generates and validates the complete page set and performs only the authorized local or publication scope.

The production route MUST NOT copy a reference HTML file, edit HTML manually from a Phase result, guess a public path, or upload individual files by an independent method.

A complete accepted-source pair is valid upstream image input, but it is not
page-publication readiness by itself. Before automatic page publication,
complete the canonical image lifecycle in this order: `ACCEPTED` →
`INSTALLED_LOCAL` → `REGISTERED_GIT` → `DEPLOYED_ASSET`, including same-name
hash and production-byte verification. The page publication transaction MUST
NOT perform or bypass those image-asset operations.

Validation covers:

- The seven-file hotel change unit in Section 4
- The two route-approved accepted-source images and their two same-hash local-public copies when the pair was created under the current lifecycle
- The target input Text and required classification update
- The complete generated current-state output set
- Visible content, SEO, OGP, JSON-LD, links, map, and production HTTP state when publication is authorized; desktop/mobile rendering when inspected, otherwise `NOT_EXECUTED`

## 2. Standard Execution

For an exact request to create the next eligible page from the available staff-completed Text population, upload it, and report the production URL, run only the following. `publish-next` is the automatic `DIRECT_TEXT` selection route. It scans, classifies, selects, checks, and publishes one target by default; it does not select or validate Phase evidence.

Before running any build or publication command, confirm that the dedicated
workflow plans and validates the top-page hotel-section update required by
Section 10.1 of `CANDY_PAGE_GENERATION_GOVERNANCE.md`. If it does not, STOP
before generation.

```powershell
codex\scripts\candy-hotel.cmd publish-next
```

For an exact request to publish multiple next eligible pages, specify the count. The count must be from 1 through 20.

```powershell
codex\scripts\candy-hotel.cmd publish-next --count 3
```

Do not run `target-next`, `build`, or `check` before this normal automatic route. `publish-next` owns those internal decisions and checks. Use `--verbose-candidates` only when candidate-level skip details are explicitly required.

Each selected page remains an independent transaction. When publication is included, complete its build, checks, Commit, Push, Actions, and production verification before beginning the next page. Never combine multiple pages into one Commit, Push, or Actions run.

Scan and classify the input population once, select `READY` candidates in filename order, and acquire one publication lock around the whole batch. Invalid, legacy, missing-image, already-built, registered, untracked, duplicate-slug, and management candidates may be skipped only before selection. Normal output reports aggregate classification and blocker counts plus the selected targets.

When fewer eligible targets exist than the requested count, STOP before the first publication. Once a selected target enters preflight, any build, check, shared-file, generated-state, snapshot, Git, Commit, Push, Actions, or production-verification failure stops every remaining selected target. Without a verified rollback and clean-state mechanism, do not continue the batch after that failure.

Report the completed target count, failed target, and unexecuted target count separately. A previously completed page remains completed when a later page fails.

Preflight an explicit staff-completed Text target before image creation or build:

```powershell
codex\scripts\candy-hotel.cmd legacy-check --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd direct-check --input "Text_hotel_data/対象ホテル.txt"
```

Classify the full input population:

```powershell
codex\scripts\candy-hotel.cmd audit-inputs
codex\scripts\candy-hotel.cmd audit-inputs --write-report
codex\scripts\candy-hotel.cmd audit-existing
```

Without production operations:

```powershell
codex\scripts\candy-hotel.cmd build --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd check --input "Text_hotel_data/対象ホテル.txt"
```

For the normal path, do not add redundant preliminary `build` or `check`
commands when the publish command already performs the same authoritative
gates.

Do not run the final image-dependent target gate before reconciling the
selected target's accepted and local-public image pairs. When only a complete
accepted pair exists, finish the separate image-asset route before invoking
`publish-next`.

### 2.1 Automatic Selection and Shortage Handling

`publish-next` performs the authoritative automatic candidate scan. It MUST complete the full population classification once before beginning the first selected target.

- Treat an accepted-source-only pair as pending image-asset work, not as `READY` for automatic page publication.
- Select only `READY` candidates in filename order.
- When fewer `READY` candidates exist than the requested count, publish no page and return `BATCH_RESULT=STOP`.
- Report aggregate classification counts, aggregate blocker counts, and the selected targets. Do not print every skipped candidate unless `--verbose-candidates` was specified.
- Do not ask for confirmation after discovering a shortage; report the shortage and STOP.

Use `target-next` only for a separate, explicit request to inspect the next candidate. It is not a prerequisite for `publish-next` and MUST NOT be added to the normal automatic production route.

### 2.2 `DIRECT_TEXT` Production Order and New-Page Target Gate

Do not select an automatic target manually. `publish-next` MUST apply the following order to the candidate count established by Section 2.1:

1. Review direct text files under `Text_hotel_data` in filename order.
2. Exclude management text files, invalid input, targets whose image lifecycle
   is not ready for the requested page-publication scope, already-built pages,
   existing shared registrations, untracked input, and duplicate slugs.
3. Exclude a slug with an existing public PHP file, source HTML, dataset PHP
   file, `dataset_base.php` registration, hotel-index registration, top-page
   hotel-section registration, or sitemap registration.
4. For automatic publication, require the accepted/public relationship and
   image lifecycle required by Section 1.3 to be complete before selection.
   Exclude an accepted-source-only, partial, slug-conflicting, or same-name
   hash-conflicting pair from page publication.
5. Exclude an input text file absent from Git HEAD.
6. Check blockers through `BLOCKER_COUNTS_JSON`, not only the primary classification. Do not hide simultaneous blockers such as missing images and untracked input.
7. Freeze only the required number of passing targets in filename order, then process them independently and sequentially.

Do not run `publish` for a target that does not return
`NEW_HOTEL_TARGET_OK=<slug>` after the authorized preparation above. When the
accepted pair itself is missing, input is invalid, a registration exists,
input is untracked, an image pair is partial or conflicting, the required
image lifecycle is incomplete, or a shop is unregistered, do not proceed;
complete the applicable preparation route first.

### 2.3 Explicit Target Execution

Select `SOURCE_ROUTE: DIRECT_TEXT` or `SOURCE_ROUTE: PHASE_PREPARED` according to Section 1. For `DIRECT_TEXT`, require `DIRECT_TEXT_STATUS=READY_FOR_BUILD`. For `PHASE_PREPARED`, require the completed Phase evidence. Then run the common target gate:

```powershell
codex\scripts\candy-hotel.cmd target-check --input "Text_hotel_data/対象ホテル.txt"
```

For an authorized local-only build:

```powershell
codex\scripts\candy-hotel.cmd build --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd check --input "Text_hotel_data/対象ホテル.txt"
```

For an explicit instruction to create, publish, and report the production URL:

```powershell
codex\scripts\candy-hotel.cmd publish --input "Text_hotel_data/対象ホテル.txt"
```

Do not run manual HTML creation or a separate FTP upload before or after these commands. Do not use `publish-next` when the user explicitly selected the target through either source route.

## 3. Integrated Workflow

### 3.1 Batch Plan

1. Acquire one publication lock for the complete command.
2. Scan and classify the input population once.
3. Select the requested number of `READY` targets in filename order. If the count is insufficient, STOP before changing any page.
4. Freeze the selected order and clear per-target active state before each target begins.

### 3.2 Independent Page Transaction

For each selected target, complete the following transaction before beginning the next target:

1. Re-run the target-specific branch, remote, working-file, dependency, existing-file, and shared-registration preflight against the state left by the previously completed target.
2. Verify that the separate image-asset route already completed the accepted/public reconciliation, required lifecycle, and same-name hash checks. Do not install, register, deploy, or replace images inside the page transaction.
3. Freeze dependency hashes, generate the complete page set from the hotel template and `template_shop.html`, and validate all input blocks, related links, scenes, JSON-LD, and images.
4. Register only the target in `dataset_base.php`, the hotel index, the top-page hotel section, and sitemap as the seven-file page change unit.
5. Synchronize sitemap dates and the complete generated current-state output set with `candy-site-state preview-sitemap-lastmod`, `sync-sitemap-lastmod`, `write`, and `check`, then freeze hashes for the complete authorized output set.
6. When publication is included, create the target's own Commit, Push it, wait for its own Actions run, and complete its production verification before beginning the next target. Never combine targets into one Commit, Push, or Actions run.
7. Verify the production page, H1, JSON-LD, images, sitemap, and redirects. In both the hotel index and the top-page hotel region, require the target URL exactly once, require its visible link name to equal the hotel name exactly, and require the complete URL/name registries to align.
8. Mark the target completed only after all required checks pass.

### 3.3 Batch Completion Boundary

- Begin the next selected target only after the current target is marked completed.
- On any failure after an independent page transaction begins, record the completed targets, failed target, and unexecuted targets, then STOP.
- Report `BATCH_RESULT=COMPLETED` only when every requested target is completed. Otherwise report `BATCH_RESULT=STOP`.

After generation or a fix and before staging, run `codex\scripts\candy-site-state.cmd preview-sitemap-lastmod`, `sync-sitemap-lastmod`, `write`, and `check`. Treat required input-classification updates and generated-document updates as the same work unit.

## 4. Input and Generation Unit

- Input: `Text_hotel_data/対象ホテル.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-hotel.html`
- Shops: `HP/source/template_shop.html`
- Give source Text highest priority. Do not infer a missing value, image, URL, or hotel fact.
- When no complete accepted or local-public image pair exists, review
  `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`, then accept both required images
  through `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`. When only the accepted pair
  exists, complete its separate first-installation, registration, deployment,
  and production-byte verification route before page publication.
- `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` is the canonical input classification.
- Only when travel time or transportation fees are absent from Text, use hotel-map coordinates and the nearest complete area page for each shop. Include the reference source in dependency hashes.
- Preserve normal article scenes and known sections in input order. Treat a legacy option as an independent block.
- Match shops, normal article scenes, FAQs, optional basic-information rows, fee rows, access entries, and nearby spots to complete input blocks. Do not set a maximum count.
- When normal article scenes, FAQs, fees, access entries, or nearby spots contain zero items, omit the whole section without asking.
- STOP on a partially entered item. Do not generate blanks, placeholders, or empty containers.
- The only fixed generated link count is six related articles: three current indexable blog details and three current indexable area details. A hotel page requires at least one shop.
- Do not mix missing registrations or legacy IDs from existing hotel pages into new production.

Change unit:

```text
HP/kagoshima-deliveryhealth-hotel-<slug>.php
HP/source/kagoshima-deliveryhealth-hotel-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-hotel-<slug>.php
HP/includefile/dataset_base.php
HP/source/hotel.html
HP/source/index.html
HP/sitemap.xml
```

Route image validation unit:

```text
Text_hotel_data/画像データ/<slug>_1.jpg
Text_hotel_data/画像データ/<slug>_2.jpg
HP/imgHtml/new_202601/hotel/<slug>_1.jpg
HP/imgHtml/new_202601/hotel/<slug>_2.jpg
```

When either source route created or first-installed a new image pair and the
applicable authorized routes selected from `codex/WORK_ROUTING.md` Section 5.2 include
publication, complete the image-asset registration, Actions deployment, and
production-byte verification in
`CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` before invoking page publication. The
current hotel publication tool treats public images as tracked, clean
dependencies and does not stage new image files; do not re-edit them during
page generation.

Existing public images without accepted-source counterparts are `LEGACY_PUBLIC_ONLY`. Preserve them and do not create accepted copies by assumption. A same-name byte replacement follows the explicit replacement route in `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`; it is not a normal new-page first installation.

## 5. STOP Conditions

- Another hotel publication process overlaps the target.
- The requested automatic count is outside 1 through 20, or fewer `READY` candidates exist than the requested count. STOP before beginning the first target.
- Required input is missing; an unconverted legacy format, placeholder, unsafe
  URL, no complete accepted or public image pair, a partial or conflicting
  image pair, slug mismatch, duplicate, partial input block, or existing-file
  conflict exists. For page publication, public-image absence remains a STOP
  until the accepted-source pair completes the separate image-asset route.
- An explicit `target-check` does not return `NEW_HOTEL_TARGET_OK=<slug>` for the selected target.
- A shop is unknown, or travel time/transportation fees are unspecified and cannot be derived from hotel coordinates or a nearby complete area page.
- A target registration is duplicated in dataset_base, the hotel index, the
  top-page hotel section, or sitemap, or the hotel index has no reserved slot.
- The target URL is absent, duplicated, or paired with a different visible name in the hotel index or top-page hotel region, or the complete hotel registries do not align.
- Dependency/output hash, PHP, JSON, Actions, or production HTTP validation fails.
- For `PHASE_PREPARED`, a Phase 1-4 result is not `PASS`, the target Text hash chain is broken, or a Phase 4 image hash differs.
- The production route would require reference-HTML copying, direct HTML editing, an independent upload method, or an unverified public path.
- After a selected target begins its independent transaction, any preflight, build, check, shared-registration, generated-state, snapshot, Commit, Push, Actions, or production-verification failure stops every remaining selected target. Do not continue without a verified rollback and clean-state proof.

On STOP, add the stopped phase, completed state, unexecuted state, and emitted
`RECOVERY_COMMAND` to the response required by root `AGENTS.md`.

Recovery output MUST classify the next action as one of `RESUME_ALLOWED`, `CAUSE_MUST_BE_RESOLVED`, `RESTART_REQUIRED`, or `MANUAL_REVIEW`. A deterministic production mismatch is `CAUSE_MUST_BE_RESOLVED` with `RECOVERY_COMMAND=NONE`; do not repeat the same resume command without resolving the cause. Resume is permitted only for a specifically classified transient communication failure whose saved state and snapshots remain valid. When safe continuation cannot be proven, use `MANUAL_REVIEW` and do not provide a generic resume command.

## 6. User Report

In addition to the common response structure in root `AGENTS.md`, report these
batch facts whenever `publish-next` is used, including its default one-target form:

```text
BATCH_REQUESTED=<requested count>
BATCH_SELECTED=<selected count>
BATCH_COMPLETED=<completed count>
BATCH_FAILED_TARGET=<failed slug or NONE>
BATCH_UNEXECUTED=<unexecuted count>
BATCH_RESULT=COMPLETED / STOP
```

For every completed target, report:

```text
BATCH_ITEM_INDEX=<1-based selected order>
BATCH_ITEM_RESULT=PUBLISHED / DRY_RUN_OK
HOTEL=<hotel name>
SLUG=<slug>
PRODUCTION_URL=<URL or NOT_EXECUTED>
COMMIT_URL=<URL or NOT_EXECUTED>
ACTIONS_URL=<URL or NOT_EXECUTED>
DESKTOP_MOBILE_RENDERING=NOT_EXECUTED
```

Do not report a failed or unexecuted target as completed. A later failure MUST NOT change an earlier completed target to failed.

For an explicit target or for each completed automatic target, report these hotel-production facts:

```text
SOURCE_ROUTE: DIRECT_TEXT / PHASE_PREPARED
PRODUCTION: PASS / REVIEW / STOP
PHASE 5 (PHASE_PREPARED only): PASS / REVIEW / STOP / NOT_APPLICABLE
対象ホテル:
TARGET_TEXT_PATH:
CANONICAL_SLUG:
生成・検査した7ファイル:
検査した画像2枚:
画像ライフサイクル: ACCEPTED / INSTALLED_LOCAL / REGISTERED_GIT / DEPLOYED_ASSET / PUBLISHED / LEGACY_PUBLIC_ONLY / REVIEW / STOP
受入原本・公開用コピーの同名ハッシュ一致: PASS / FAIL / NOT_APPLICABLE
ローカル生成: PASS / FAIL / 未実行
画像Commit・Push・Actions・本番バイト確認: PASS / FAIL / 未実行
ページCommit・Push・Actions・本番反映: PASS / FAIL / 未実行
本番URL:
画像Commit URL:
画像Actions URL:
ページCommit URL:
ページActions URL:
公開ページ・画像・リンク・地図・JSON-LD:
PC表示・モバイル表示:
未確認・未解決:
```

If browser rendering was not inspected, include that page-specific fact in the
unverified field and report `PC表示・モバイル表示: NOT_EXECUTED`. This is not by itself a publication failure, but do not report the image lifecycle as `PUBLISHED`; report only the highest state established by the completed checks.

This runbook is the source for the operating procedure, the publication program implements it, and the offline self-tests prove their alignment. A change is incomplete when any of those three disagree.
