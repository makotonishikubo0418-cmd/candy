# CANDY Page Generation Governance

- Updated: 2026-07-18
- Applies to: Normal new-page generation for area, blog, and hotel by Codex

## 1. Position

This document contains common execution rules above the category generation specifications.

- Common rules: this document
- Area detail: `CANDY_AREA_PAGE_GENERATION_SPEC.md`
- Blog detail: `CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- Hotel detail: `CANDY_HOTEL_PAGE_GENERATION_SPEC.md`

Apply it only to normal new-page generation. Do not apply it to development changes, bug fixes, common-structure changes, or refactoring.

## 2. Primary Principles

- Do not infer content absent from source data.
- Do not force the template's block count onto the completed page.
- Area pages render three to six verified nearby-area links from `codex/data/CANDY_AREA_RELATED_LINKS.json`, or omit the block when fewer than three suitable targets exist. Hotel pages continue to preserve eight template dummy links until their actual-link rules are configured.
- Add or remove items, blocks, and sections according to available information.
- After a structural change, synchronize IDs, table of contents, JSON-LD, links, and counts.
- Treat public PHP, source HTML, dataset PHP, and dataset_base registration as one change unit.
- Do not report completion when any component is missing.
- Do not mix an existing inconsistency fix with new-page generation.

## 3. Input Classification

Classify source data into these three types.

### 3.1 Required Items

STOP generation when any required item is missing:

- Page type
- Page name
- Slug
- Title
- Meta description
- Canonical
- H1
- Main image or an explicitly defined image policy
- Source information that identifies the public page

### 3.2 Optional Items

Do not infer a missing optional item. Use the category specification and completed pages to decide whether to omit its row or section.

- Subtitle
- Supplemental description
- Some basic-information fields
- Individual fee-table rows
- Facility information
- FAQ section
- Nearby information

### 3.3 Repeated Items

Do not fix these counts:

- Shops
- Normal article scenes
- Customer comments
- FAQs
- Hotels
- Nearby spots
- Girl introductions

## 4. Pre-Generation Input Audit

Before generation, record for the target:

```text
Category:
Page name:
Slug:
Source data:
Template:
Missing required items:
Missing optional items:
Repeated items and counts:
Existing same-name files:
Similar or legacy slugs:
Images:
Required user decisions:
```

When a required item is missing, a slug conflicts, a same-name file exists, or an unexplained image shortage exists, STOP and report before creating files.

## 5. Reference-Page Selection

Select a reference by structural similarity, not name similarity.

Priority:

1. Same category
2. Same section structure
3. Similar repeated-item counts
4. New-format page close to the current template
5. No placeholder, numbering gap, or missing registration

Do not use only a legacy page, a page with remaining placeholders, a page without source data, or a page absent from dataset_base registration as the copy source.

## 6. Missing-Information Handling

| Condition | Handling |
|---|---|
| Required item is missing | STOP generation and request user confirmation |
| Optional table item is missing | Remove the row; do not leave an empty row |
| Repeated-item count is zero | Decide whether the entire section is required |
| Repeated-item count is below the template count | Remove extra blocks |
| Repeated-item count exceeds the template count | Add blocks with the same structure and reset numbering |
| Image is missing | Do not use an inferred image; STOP or request an alternate policy |
| URL, telephone, address, or related value is unverified | Do not infer a value |
| Source data contradicts an existing page | Do not change; request a user decision |

When removing content, inspect the impact on opening and closing tags, spacing classes, separators, scene numbers, and JSON-LD.

## 7. Variable-Structure Synchronization

When adding, deleting, or reordering blocks, update together:

- H2/H3 scene IDs
- Subtitle IDs
- Description IDs
- Table-of-contents order, copy, and href values
- Final FAQ item class
- JSON-LD questions, answers, ItemList, and positions
- Breadcrumb
- Canonical and OGP
- Image `src` and alt text
- Internal links
- Border, padding, and margin classes around the section

Do not update only part of this set.

## 8. Scene Numbering

- Number a new page sequentially from 1 in visible order.
- Do not leave a gap after deleting a section.
- Child items inside a parent scene use `<parent-number>_<sequence>`.
- Duplicate IDs are prohibited.
- A blog table of contents MUST match every main scene exactly.
- Do not copy numbering gaps from a legacy page to a new page.

## 9. JSON-LD

Treat JSON-LD as duplicated data for visible HTML content, not as independent content.

- Remove FAQPage when no FAQ exists.
- Match FAQ questions, answers, order, and count to visible content.
- Match ItemList items, URLs, images, and positions to visible content.
- Match breadcrumb structured data to the visible breadcrumb.
- Do not leave a deleted item in JSON-LD.
- JSON parsing is required.

## 10. Complete File Set and Registration

Normal generation requires:

1. Public entry PHP
2. Source HTML
3. Page-specific dataset PHP
4. Case registration in `dataset_base.php`
5. HTML-to-PHP link transformation in `dataset_base.php`

When only part of a same-name set exists, switch from new generation to an existing-inconsistency investigation.

### 10.1 Integration into Public Routes

Even when page files are generated, publication integration is incomplete until the page is reachable from indexes, sitemap, and required related links.

Check by category:

- Area: `HP/source/area.html`
- Blog: `HP/source/blog.html`
- Hotel: `HP/source/hotel.html`
- Common: `HP/sitemap.xml`
- Related-page links and JSON-LD ItemList when required

For a normal public page, determine:

- Whether the category index requires a link, name, description, or related entry
- Whether the category-index JSON-LD requires an entry
- Whether the sitemap requires the URL
- Whether related pages require internal links
- Whether the index count or order requires an update

`sitemap.xml` requires approval. Present the target and added URL before changing it and obtain user approval.

When the normal integrated hotel tool limits changes to the target, `dataset_base.php`, the hotel index, and sitemap are within an explicit hotel-production and publication instruction.

Update `sitemap.xml` only through the applicable canonical category workflow after user approval. Preserve its current XML fields, diff the exact URL change, and do not replace it with an independently collected URL list.

When only detail-page files are created and the index or sitemap is not registered, report file-generation completion separately from public-route integration completion.

### 10.2 Robots

Current area, blog, and hotel templates use `<meta name="robots" content="index">`. Preserve the current template setting for normal public-page generation.

- Do not change it to noindex.
- Do not omit robots.
- Obtain user instruction when a pre-publication draft requires noindex.
- Keep robots, canonical, and sitemap policies consistent.

## 11. Post-Generation Machine Validation

- Every generated target file exists.
- Unauthorized placeholder count is zero.
- The area nearby-link block exactly matches its canonical mapping, contains three to six links when present, and contains no dummy, self-link, duplicate, or incomplete target.
- The hotel related-article region contains eight reserved dummy entries or actual links, and no reserved dummy is outside that region.
- No required item is empty.
- Scene IDs have no duplicate or gap.
- Subtitle and description IDs match parent scenes.
- Blog table of contents matches main scenes.
- The final FAQ block class is correct.
- HTML and JSON-LD counts agree.
- JSON syntax is valid.
- Canonical and file slug agree.
- Images exist.
- Internal links point to public PHP.
- Robots agrees with publication policy.
- Category-index registration requirements were checked.
- Sitemap registration requirements were checked.
- PHP syntax is valid.
- `git diff --check` succeeds.
- No out-of-scope file changed.

### 11.1 File Format

- Verify the existing file's encoding and line endings.
- Match a new file to current files in the same category.
- Do not bulk-convert BOM, line endings, or indentation.
- Verify that Japanese website text, symbols, hearts, and related characters are not mojibake.
- Do not change existing conventions such as PHP short opening tags as part of new generation.

### 11.2 Source Text Handling

- Do not delete, move, or rename the source text file after generation without explicit instruction.
- Do not automatically move an area input to `Completion` based only on generation completion.
- Before moving to a completion folder, show validation results and obtain user instruction.
- Do not create a completion folder for blog or hotel when none exists.

### 11.3 Local Completion and Production Deployment

Report these as separate completion states:

- Local file generation
- Local image placement
- Git Commit and Push
- Server deployment
- Production-page HTTP validation
- Production-image HTTP validation
- Actual browser rendering validation

The presence of local files or images does not mean production deployment is complete.

## 12. Required Human Review

- Meaning matches source data.
- No typographical error exists.
- Copy from another region, article, or hotel is absent.
- No old shop name or other category name remains.
- Removing information did not make copy or layout unnatural.
- Table of contents and headings have matching meaning.
- Existing business information, fees, telephone numbers, and addresses were not assumed correct without verification.

## 13. Exception STOP Conditions

Codex MUST NOT decide and complete the page automatically when:

- Required information is missing.
- A placeholder remains in source data.
- Multiple slugs exist for the same target.
- Only part of the existing three-file set exists.
- Source data contradicts a completed page.
- An image, URL, shop, or girl cannot be identified.
- Template and source-data section structures differ materially.
- Information required for JSON-LD is missing.
- An existing dataset_base registration conflicts.
- Category-index or sitemap addition policy is unclear.
- It is unclear whether to move source Text to `Completion` or another location.
- The production deployment method is unclear.

## 14. Completion Report

Use the explicit authority rules in root `AGENTS.md` and the publication procedure in `CANDY_PRODUCTION_MIGRATION_MASTER.md`. Check Actions through the GitHub API; browser UI interaction is not the normal route.

Report only:

```text
作成ページ:
作成ファイル:
可変項目数:
省略した項目:
確認済み:
未確認:
Commit・Push:
確認用URL:
```

After Push, include the GitHub Commit URL; after Actions, include the run URL; after production publication, include every created page's production URL. For local-only production, report `確認用URL: 未取得（本番未反映）`. Do not infer an unverified URL.

## 15. Separation from Existing Inconsistencies

Existing pages may have missing registrations, legacy structures, placeholders, absent source data, or duplicate IDs. Do not reproduce these in new generation; review the category specification's current inconsistency list.

Handle an existing inconsistency as a separately authorized development or existing-feature fix, not normal generation.
