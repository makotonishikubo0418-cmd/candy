# CANDY Woman Information and Image Management

- Purpose: Define the canonical local woman-information source and control which woman images may be published
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Woman records used by page-generation tools, local-only image retention, public-image installation, removal, and validation
- Status / Lifecycle: Canonical / Active
- Source of Truth Responsibility: Stable woman-information and image-publication rules; exact records and image state are owned by `../data/CANDY_GIRL_INFORMATION.json`
- Related Documents: `CANDY_BLOG_PAGE_GENERATION_SPEC.md`, `CANDY_CODE_FILE_STRUCTURE.md`, `CANDY_OPERATION_BASICS.md`, and `CANDY_PRODUCTION_MIGRATION_MASTER.md`
- Related Implementation Files: `../scripts/candy_girl_information.py`, `../scripts/candy_blog_page.py`, and `../scripts/candy_category_publish.py`

## 1. Canonical Information Source

`codex/data/CANDY_GIRL_INFORMATION.json` is the sole structured ledger for woman information used by local page-generation tools. It retains every migrated woman record regardless of whether her images are currently published.

Each record contains:

- Stable template key
- Profile number when one exists and the exact profile URL
- Display name and heading
- Introductory summary and full description
- Style and hobby information
- Desktop and mobile image filenames and alt text
- Image-loading behavior
- Image-publication state

`HP/source/template_girls.html` is retired. Do not recreate or deploy a second woman-information source under `HP/source/`.

## 2. Storage Responsibilities

| Location | Responsibility | Publication handling |
|---|---|---|
| `codex/data/CANDY_GIRL_INFORMATION.json` | Complete local woman-information ledger | Never deployed as an HP file |
| `HP/imgHtml/new_202601/girl/` | Images referenced by current public pages | Git-managed and deployed to production |
| `Text_girl_data/画像データ/` | Images retained locally but not referenced by current public pages | Git-managed local source; never deployed to production |

Do not keep the same canonical image in both public and local-only locations. A desktop/mobile pair moves together.

## 3. Image-Publication States

| State | Meaning | Required placement |
|---|---|---|
| `PUBLIC` | At least one current formal public page or verified runtime-generated response requires the image pair | Both images exist under `HP/imgHtml/new_202601/girl/` and are absent from `Text_girl_data/画像データ/` |
| `LOCAL_ONLY` | No current formal public page or verified runtime-generated response requires the image pair | Both images exist under `Text_girl_data/画像データ/` and are absent from `HP/` and production |

Presence in the local ledger does not make an image `PUBLIC`. A generation template, historical page, candidate record, direct image URL, or filename alone is not evidence of current public use.

Database references, access logs, external inbound links, and third-party caches are separate evidence classes. Label them `UNVERIFIED` until checked through an authorized route; do not report them as current internal use.

### 3.1 Profile-Link Availability

When a specific `girls.php?no=<number>` route is verified as HTTP `404`, set that ledger record's `profile_no` to `null` and `profile_url` to `./girls_list.php`. A public historical introduction may retain the woman's text and images, but its image and detail button MUST NOT link to the unavailable profile, and its `Person` structured data MUST omit `url`. Image publication state is decided separately from profile-route availability.

## 4. Installing a Local-Only Pair for Publication

Before changing `LOCAL_ONLY` to `PUBLIC`:

1. Identify the exact woman key and desktop/mobile pair.
2. Confirm that an authorized current page will reference both images.
3. Copy the exact pair from `Text_girl_data/画像データ/` to `HP/imgHtml/new_202601/girl/`.
4. Remove the local-only copies only after the public copies have matching SHA-256 values.
5. Change the ledger state to `PUBLIC` in the same work unit.
6. Validate the page, image references, generated state, Git diff, deployment plan, production responses, and image hashes.

Partial installation or mismatched desktop/mobile state is prohibited.

## 5. Returning a Public Pair to Local-Only Storage

Before changing `PUBLIC` to `LOCAL_ONLY`:

1. Verify that no current formal public page or verified runtime-generated response references either image.
2. Fix or remove every controlled public reference in the same authorized work unit.
3. Move both images to `Text_girl_data/画像データ/` while preserving their bytes.
4. Change the ledger state to `LOCAL_ONLY`.
5. Commit and Push the exact removal so the approved deployment deletes the production pair transactionally.
6. Verify both production image URLs return `404` and every retained `PUBLIC` image required by affected pages still returns `200`.

Do not delete the local-only pair. Local retention is mandatory unless the user separately authorizes permanent data deletion.

## 6. Page-Generation Contract

`candy_blog_page.py` MUST load woman records through `candy_girl_information.py` and MUST render only `PUBLIC` records. A requested `LOCAL_ONLY` or unknown key is a STOP condition.

Generated visible blocks and structured data MUST use the same record for name, image, and profile URL. Existing completed public pages remain independent generated outputs; changing the ledger does not silently rewrite them.

`candy_category_publish.py` MUST include the ledger and its loader in the blog publication dependency set. It MUST include every selected public image in target validation.

## 7. Validation

Run:

```powershell
python codex/scripts/candy_girl_information.py check
python codex/scripts/candy_blog_page.py audit-inputs --render
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check
```

Completion requires:

- Every woman key, profile route, content field, and image filename is valid and unique where required.
- Every `PUBLIC` pair exists only in the public directory.
- Every `LOCAL_ONLY` pair exists only in the local-only directory.
- `HP/source/template_girls.html` is absent.
- Blog inputs resolve only to `PUBLIC` women.
- Generated asset state contains no missing reference created by the change.
- An authorized production removal passes the transactional deployment and target URL checks.

## 8. Prohibitions

- Do not treat local retention as permission to publish.
- Do not upload every locally retained woman image.
- Do not delete local-only images merely because they are absent from current pages.
- Do not use `template_girls.html` as a second ledger or deploy it for direct viewing.
- Do not change employment status, database records, or live profile behavior from this ledger alone.
