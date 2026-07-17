# CANDY Hotel Staff Production Runbook

- Updated: 2026-07-16
- Applies to: Normal production of one hotel page
- Start condition: Explicit instruction to produce or publish a hotel page
- Completion criteria: Dedicated validation succeeds and the authorized local or publication scope completes

## 1. Standard Execution

For the exact user request to create the next page, upload it, and report the production URL, run only the following. `publish-next` applies the target gate internally and publishes only one target that returns `NEW_HOTEL_TARGET_OK`.

```powershell
codex\scripts\candy-hotel.cmd publish-next
```

Validate an explicit target:

```powershell
codex\scripts\candy-hotel.cmd target-check --input "Text_hotel_data/対象ホテル.txt"
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

### 1.1 Shortage Check for a Production Request

When the user requests a hotel page, one hotel page, or publication through upload, check for missing requirements before page generation or publish:

```powershell
codex\scripts\candy-hotel.cmd target-next
```

When `NEW_HOTEL_TARGET_OK` is absent, do not proceed. First report:

- Eligible production count
- Missing-image count
- Invalid-input count
- Untracked-input count
- Whether an existing page or shared registration exists
- The first blocked candidate and its missing requirements

Do not execute first and ask the user afterward when a required item is missing. Report the shortage and STOP before execution.

### 1.2 Production Order and New-Page Target Gate

Do not select the target manually. In this order, use only the first target for which the dedicated gate returns `NEW_HOTEL_TARGET_OK=<slug>`:

1. Review direct text files under `Text_hotel_data` in filename order.
2. Exclude management text files, invalid input, missing images, already-built pages, existing shared registrations, untracked input, and duplicate slugs.
3. Exclude a slug with an existing public PHP file, source HTML, dataset PHP file, `dataset_base.php` registration, hotel-index registration, or sitemap registration.
4. Exclude a slug without two images.
5. Exclude an input text file absent from Git HEAD.
6. Check blockers through `BLOCKER_COUNTS_JSON`, not only the primary classification. Do not hide simultaneous blockers such as missing images and untracked input.
7. Produce only the first target that passes.

Do not run `publish` for a target that does not return `NEW_HOTEL_TARGET_OK=<slug>`. When missing images, invalid input, an existing registration, untracked input, or an unregistered shop appears, do not proceed; restart candidate preparation.

## 2. Integrated Workflow

1. Acquire the single-run lock and validate required fields, URLs, slugs, images, duplicates, and incomplete fields under `Text_hotel_data`.
2. Validate the three existing page files, shared registrations, Git identity, remote, and Push dry run, then freeze dependency hashes.
3. Generate the complete page set from the hotel template and `template_shop.html`. Only for travel time and transportation fees absent from Text, use map coordinates and the nearest complete area page for each shop.
4. Validate the order and count of every input block, eight dummy related articles, scenes, JSON-LD, and images.
5. Register only the target in `dataset_base.php`, the hotel index, and sitemap, then freeze hashes for the six output files.
6. Synchronize generated management documents with `candy-site-state write` and `check`.
7. Verify the stage allowlist, then Commit and Push only the target once each. When execution stops immediately after Commit, verify content equality and reuse the existing Commit.
8. Track pre-FTP PHP lint and deployment in Actions through the API.
9. Verify the production page, H1, JSON-LD, images, hotel index, sitemap, and redirects over HTTP, then output URLs.

Do not create a post-publication record Commit, Push a second time, or move the input Text.

After generation or a fix and before staging, run `codex\scripts\candy-site-state.cmd write` and `check`. Treat required input-classification updates and generated-document updates as the same work unit.

## 3. Input and Generation Unit

- Input: `Text_hotel_data/対象ホテル.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-hotel.html`
- Shops: `HP/source/template_shop.html`
- Give source Text highest priority. Do not infer a missing value, image, URL, or hotel fact.
- When images are missing, review `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`. Do not produce an image when storage, modification, and commercial-publication conditions for its source cannot be verified.
- `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` is the canonical input classification.
- Only when travel time or transportation fees are absent from Text, use hotel-map coordinates and the nearest complete area page for each shop. Include the reference source in dependency hashes.
- Preserve normal article scenes and known sections in input order. Treat a legacy option as an independent block.
- Match shops, normal article scenes, FAQs, optional basic-information rows, fee rows, access entries, and nearby spots to complete input blocks. Do not set a maximum count.
- When normal article scenes, FAQs, fees, access entries, or nearby spots contain zero items, omit the whole section without asking.
- STOP on a partially entered item. Do not generate blanks, placeholders, or empty containers.
- The only fixed count is eight reserved dummy related articles. A hotel page requires at least one shop.
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

## 4. STOP Conditions

- The branch is not `main`, the remote differs, fast-forward is impossible, or a conflict exists.
- Git identity or Push dry run fails, or another hotel publication process is running.
- Required input is missing; a placeholder, unsafe URL, missing image, slug mismatch, duplicate, partial input block, or existing-file conflict exists.
- `target-next` does not return `NEW_HOTEL_TARGET_OK`.
- A shop is unknown, or travel time/transportation fees are unspecified and cannot be derived from hotel coordinates or a nearby complete area page.
- A target registration is duplicated in dataset_base, the hotel index, or sitemap, or the hotel index has no reserved slot.
- Deletion, rename, an unauthorized file, or deployment of more than 25 files is required.
- Dependency/output hash, PHP, JSON, stage allowlist, Actions, or production HTTP validation fails.

On STOP, report the stopped phase, completed state, unexecuted state, and emitted `RECOVERY_COMMAND`.

## 5. User Report

```text
結論: 本番反映済み
作成ページ:
本番URL:
Commit URL:
Actions URL:
未確認:
```

If browser rendering was not inspected, state that it is unverified. The target for one normal page is no more than five minutes from instruction to production-URL report.
