# CANDY Hotel Staff Production Runbook

- Updated: 2026-07-24
- Applies to: Normal production of one hotel page from either staff-completed Text or Phase-prepared Text
- Start condition: Explicit instruction to produce or publish a hotel page
- Completion criteria: Dedicated validation succeeds and the authorized local or publication scope completes

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
  absent, first-install it automatically as page-production preparation; do
  not report `READY_FOR_IMAGES` or ask the user again.
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

Candidate discovery MUST recognize a complete accepted-source pair as image
availability. After selecting the target and before the final target gate,
copy exact accepted bytes to `HP/imgHtml/new_202601/hotel/` when both
same-name public files are absent. A page-production request authorizes this
target-limited first installation. Continue the same task after hash
verification.

Validation covers:

- The six-file hotel change unit in Section 4
- The two route-approved accepted-source images and their two same-hash local-public copies when the pair was created under the current lifecycle
- The target input Text and required classification update
- The four generated current-state documents
- Visible content, SEO, OGP, JSON-LD, links, map, desktop/mobile rendering, and production HTTP state when publication is authorized

## 2. Standard Execution

For the exact user request to create the next page from the available staff-completed Text population, upload it, and report the production URL, run only the following. `publish-next` is the automatic `DIRECT_TEXT` selection route: it applies the target gate internally and publishes only one target that returns `NEW_HOTEL_TARGET_OK`. It does not select or validate Phase evidence.

```powershell
codex\scripts\candy-hotel.cmd publish-next
```

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

For the normal path, do not add preliminary `build`, `check`, full-document rereading, or intermediate questions.

Do not run the final image-dependent target gate before reconciling the
selected target's accepted and local-public image pairs. Public absence alone
is preparation work when a complete accepted pair exists.

### 2.1 Shortage Check for an Automatic `DIRECT_TEXT` Production Request

When the user requests a hotel page, one hotel page, or publication through upload, check for missing requirements before page generation or publish:

```powershell
codex\scripts\candy-hotel.cmd target-next
```

Run candidate discovery with complete accepted-source pairs treated as
available. First-install the selected target's pair when required, then run
the final target gate. Only when `NEW_HOTEL_TARGET_OK` remains absent after
that preparation, do not proceed. First report:

- Eligible production count
- Missing-image count
- Invalid-input count
- Untracked-input count
- Whether an existing page or shared registration exists
- The first blocked candidate and its missing requirements

Do not execute first and ask the user afterward when a required item is missing. Report the shortage and STOP before execution.

### 2.2 `DIRECT_TEXT` Production Order and New-Page Target Gate

Do not select the target manually. In this order, use only the first target for which the dedicated gate returns `NEW_HOTEL_TARGET_OK=<slug>`:

1. Review direct text files under `Text_hotel_data` in filename order.
2. Exclude management text files, invalid input, genuinely missing image pairs,
   already-built pages, existing shared registrations, untracked input, and
   duplicate slugs. A complete accepted pair is not missing merely because its
   local-public copy is pending.
3. Exclude a slug with an existing public PHP file, source HTML, dataset PHP file, `dataset_base.php` registration, hotel-index registration, or sitemap registration.
4. Accept either a complete accepted pair or a complete local-public pair.
   After selection, first-install the accepted pair when the local-public pair
   is absent. Exclude only a target with neither complete pair or with a
   partial, slug-conflicting, or same-name hash-conflicting pair.
5. Exclude an input text file absent from Git HEAD.
6. Check blockers through `BLOCKER_COUNTS_JSON`, not only the primary classification. Do not hide simultaneous blockers such as missing images and untracked input.
7. Produce only the first target that passes.

Do not run `publish` for a target that does not return
`NEW_HOTEL_TARGET_OK=<slug>` after the authorized preparation above. When the
accepted pair itself is missing, input is invalid, a registration exists,
input is untracked, an image pair is partial or conflicting, or a shop is
unregistered, do not proceed; restart candidate preparation. Do not stop only
because a complete accepted pair still requires first local installation.

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

1. Acquire the single-run lock and validate required fields, URLs, slugs,
   accepted/public image states, duplicates, and incomplete fields under
   `Text_hotel_data`.
2. Select the target using either its complete accepted pair or complete
   local-public pair, first-install exact accepted bytes when required, and
   verify same-name hashes before the final target gate.
3. Validate the three existing page files, shared registrations, Git identity, remote, and Push dry run, then freeze dependency hashes.
4. Generate the complete page set from the hotel template and `template_shop.html`. Only for travel time and transportation fees absent from Text, use map coordinates and the nearest complete area page for each shop.
5. Validate the order and count of every input block, three deterministic blog links, three deterministic area links, scenes, JSON-LD, and images.
6. Register only the target in `dataset_base.php`, the hotel index, and sitemap, then freeze hashes for the six output files.
7. Synchronize generated management documents with `candy-site-state write` and `check`.
8. Verify the stage allowlist, then Commit and Push only the target once each. When execution stops immediately after Commit, verify content equality and reuse the existing Commit.
9. Track pre-FTP PHP lint and deployment in Actions through the API.
10. Verify the production page, H1, JSON-LD, images, hotel index, sitemap, and redirects over HTTP, then output URLs.

Do not create a post-publication record Commit, Push a second time, or move the input Text.

After generation or a fix and before staging, run `codex\scripts\candy-site-state.cmd write` and `check`. Treat required input-classification updates and generated-document updates as the same work unit.

## 4. Input and Generation Unit

- Input: `Text_hotel_data/対象ホテル.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-hotel.html`
- Shops: `HP/source/template_shop.html`
- Give source Text highest priority. Do not infer a missing value, image, URL, or hotel fact.
- When no complete accepted or local-public image pair exists, review
  `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`, then accept and locally install both
  required images through `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`. When the
  accepted pair already exists, first-install its exact bytes automatically
  before the final target gate.
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
HP/sitemap.xml
```

Route image validation unit:

```text
Text_hotel_data/画像データ/<slug>_1.jpg
Text_hotel_data/画像データ/<slug>_2.jpg
HP/imgHtml/new_202601/hotel/<slug>_1.jpg
HP/imgHtml/new_202601/hotel/<slug>_2.jpg
```

When either source route created or first-installed a new image pair, complete
the target-limited image-asset Commit, Push, Actions deployment, and
production-byte verification in `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` before
invoking page publication. An explicit page-publication request authorizes
this required image unit and the following page unit; execute both
sequentially without ending the task for another approval. The current hotel
publication tool treats public images as tracked, clean dependencies and does
not stage new image files. Do not include new or modified image files in the
later page Commit, and do not re-edit them during page generation.

Existing public images without accepted-source counterparts are `LEGACY_PUBLIC_ONLY`. Preserve them and do not create accepted copies by assumption. A same-name byte replacement follows the explicit replacement route in `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`; it is not a normal new-page first installation.

## 5. STOP Conditions

- The branch is not `main`, the remote differs, fast-forward is impossible, or a conflict exists.
- Git identity or Push dry run fails, or another hotel publication process is running.
- Required input is missing; an unconverted legacy format, placeholder, unsafe
  URL, no complete accepted or public image pair, a partial or conflicting
  image pair, slug mismatch, duplicate, partial input block, or existing-file
  conflict exists. Public-image absence alone is not a STOP when a complete
  accepted pair exists.
- `target-next` does not return `NEW_HOTEL_TARGET_OK`.
- A shop is unknown, or travel time/transportation fees are unspecified and cannot be derived from hotel coordinates or a nearby complete area page.
- A target registration is duplicated in dataset_base, the hotel index, or sitemap, or the hotel index has no reserved slot.
- An unauthorized file, deployment of more than 125 files, deployment over 50 MiB, or deletion/rename without explicit approval and rollback protection is required.
- Dependency/output hash, PHP, JSON, stage allowlist, Actions, or production HTTP validation fails.
- For `PHASE_PREPARED`, a Phase 1-4 result is not `PASS`, the target Text hash chain is broken, or a Phase 4 image hash differs.
- The production route would require reference-HTML copying, direct HTML editing, an independent upload method, or an unverified public path.

On STOP, report the stopped phase, completed state, unexecuted state, and emitted `RECOVERY_COMMAND`.

## 6. User Report

```text
要約:
結論:
SOURCE_ROUTE: DIRECT_TEXT / PHASE_PREPARED
PRODUCTION: PASS / REVIEW / STOP
PHASE 5 (PHASE_PREPARED only): PASS / REVIEW / STOP / NOT_APPLICABLE
対象ホテル:
TARGET_TEXT_PATH:
CANONICAL_SLUG:
生成・検査した6ファイル:
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

If browser rendering was not inspected, state that it is unverified. The five-minute target applies only when the target Text and every dependency, including the required image lifecycle, are already publication-ready. Research, image creation, acceptance, first image-asset deployment, and human review are preparation work outside that five-minute page-publication target.
