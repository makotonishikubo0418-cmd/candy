# HP/AGENTS.md

## 1. Applicability

- Work under `HP/` is governed by root `AGENTS.md` and this file.
- For normal area production, additionally read only `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`.
- For area-image creation, material editing, or pre-acceptance validation, additionally read `codex/docs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`.
- For an existing approved area-image replacement under the same canonical filenames, read only `codex/docs/CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` and the actual target files. Use the exception routes named in that runbook only when their conditions apply.
- For normal hotel production, additionally read only `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`.
- For normal blog production, additionally read only `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` and `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`.
- Use `codex/docs/CANDY_MASTER_DOC_INDEX.md` only for an unknown exception or another task type.

## 2. HP Structure

```text
public PHP
  → includefile/dataset_base.php
  → matching source HTML
  → includefile/dataset_*.php
  → includefile/class.hpgcoder2.php
```

Verify page-specific exceptions in actual files.

## 3. Normal Area, Hotel, and Blog Production

Internal path migration under `codex/scripts/` and read-only dry runs are verified. Recheck target selection, input, images, Git, and safety gates in the applicable runbook. Publish includes Commit, Push, Actions, and production operations, so execute it only with explicit user instruction.

Standard area entry point:

```powershell
codex\scripts\candy-area.cmd publish-next
```

Area production with an explicit target:

```powershell
codex\scripts\candy-area.cmd publish --input "Text_area_data/対象.txt"
```

Area production without production operations:

```powershell
codex\scripts\candy-area.cmd build --input "Text_area_data/対象.txt"
codex\scripts\candy-area.cmd check --input "Text_area_data/対象.txt"
```

Normal hotel production:

```powershell
codex\scripts\candy-hotel.cmd publish --input "Text_hotel_data/対象ホテル.txt"
```

Automatically select a complete unpublished hotel input:

```powershell
codex\scripts\candy-hotel.cmd publish-next
```

Normal blog production:

```powershell
codex\scripts\candy-blog.cmd publish --input "Text_blog_data/対象記事.txt"
codex\scripts\candy-blog.cmd build --input "Text_blog_data/対象記事.txt"
codex\scripts\candy-blog.cmd check --input "Text_blog_data/対象記事.txt"
```

The publish tools treat generation, validation, generated-document updates, target-limited staging, one Commit, one Push, Actions, production HTTP checks, and URL reporting as one work unit. Do not create a separate management-document-only Commit and Push after publication.

## 4. Production Rules

- Area production uses `Text_area_data` and `source/template_kagoshima-deliveryhealth-area.html`.
- Blog and hotel production use the matching category Text and template.
- Do not use `create.php` for normal production.
- Give the source Text values for shops, travel time, and transportation fees highest priority.
- Only when a value is unspecified, use shop-combination frequency, map coordinates, or nearby complete pages.
- Area pages use the canonical mapping in `codex/data/CANDY_AREA_RELATED_LINKS.json` for the `周辺の対応エリア` block. Output three to six verified nearby published area links, normally four; omit the whole block when fewer than three suitable completed targets exist.
- Do not fix item counts by default. Match shops, normal article scenes, FAQs, optional basic-information rows, fee rows, access entries, and nearby spots to the number of complete input blocks.
- Normal article scenes, FAQs, fee rows, access entries, and nearby spots MAY contain zero items. Do not generate an empty section. A hotel page requires at least one shop.
- Include a legacy hotel option as an independent item only when all three required input fields are present. Do not merge it into a normal scene.
- Fixed-count exceptions require an explicit specification. Area related links are the approved variable-count exception: three to six links, with no dummy, self-link, duplicate, or incomplete target.
- Do not infer a value, image, or URL absent from the source data.

Normal area change unit:

- Public PHP
- Source HTML
- Dataset PHP
- dataset_base registration
- Area index
- Sitemap
- One production-queue row
- Canonical related-link mapping when the target slug or its nearby-area links change

Normal hotel change unit:

- Public PHP
- Source HTML
- Dataset PHP
- dataset_base registration
- Hotel index
- Sitemap

Normal blog change unit:

- Public PHP
- Source HTML
- Dataset PHP
- dataset_base registration
- Blog index
- Index-page blog section
- Sitemap

All-category change unit:

- Generated management documents, reviewed and updated when required
- Successful `codex\scripts\candy-site-state.cmd check`

The dedicated tools are the canonical validation method. Do not duplicate the same checks manually.

## 5. Publication Safety Conditions

- Before Commit, compare staged targets against the allowlist.
- STOP on an unauthorized deletion, rename, copy, type change, or file.
- Actions MUST lint target PHP before FTP operations.
- When a multi-file deployment partially fails, roll back files already deployed by that run.
- One production deployment may contain at most 125 files.
- Production verification MUST check the target page, required images, target category index, sitemap, and redirects over HTTP.
- Check Actions state through the API. Do not use the browser UI for the normal route.

## 6. Change Gates

Prior approval is required for:

- `create.php`, `log/`, `.well-known/`, and `.htaccess`
- Deletion or replacement under `movie/`
- noindex/index, authentication, databases, payments, and production settings
- Production deployment of `index.php`

Show the affected scope before changing:

- `includefile/dataset_base.php`
- `includefile/class.hpgcoder2.php`
- `includefile/funcs.php`
- `includefile/dataset_*.php`
- `source/system.html`
- `css/default.css` and `js/common.js`
- `sitemap.xml`

The normal area, hotel, and blog integrated tools may handle `dataset_base.php`, the category index, the applicable index-page category section, and the sitemap when the tool limits them to the explicitly authorized target.

## 7. STOP Conditions

- The branch is not `main`, the remote differs, fast-forward is impossible, or a conflict exists.
- Input is incomplete, images are missing, a slug does not match, or an existing file conflicts.
- A shared registration is duplicated, or an old slug or typo requires automatic replacement.
- JSON, PHP, the stage allowlist, Actions, or production HTTP validation fails.
- A STOP condition in root `AGENTS.md` applies.
