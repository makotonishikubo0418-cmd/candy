# CANDY Blog Page Generation Specification

- Updated: 2026-10-14
- Applies to: Normal new generation of CANDY blog detail pages by Codex
- Operational sequence: `BLOG_CREATION.md`
- Common image requirements: `IMAGE_MANAGEMENT.md`

## 1. Purpose and Scope

This is the canonical specification for generating blog pages from source Text without damage. Use it for normal new-page generation, not for bug fixes, existing-feature changes, common-processing changes, or refactoring.

This document owns only blog-specific page structure, source mapping, variable
blocks, and validation requirements. Common generation rules, Git, publication,
and routing remain in the applicable documents selected from `codex/WORK_ROUTING.md`
Section 5.2.

## 2. Mandatory Rules

- Use the target text file under `Text_blog_data` as source data.
- Use `HP/source/template_kagoshima-deliveryhealth-blog.html` as the HTML template.
- Load woman information from `codex/data/CANDY_GIRL_INFORMATION.json` through `candy_girl_information.py`, and select only records whose image state is `PUBLIC`.
- Adjust the table of contents, normal scenes, girl introductions, customer comments, FAQ, and summary to source-data counts.
- Keep JSON-LD, visible content, and the table of contents consistent in content and count.
- Apply the common collision, incomplete-input, change-boundary, and completion
  gates from `CANDY_PAGE_GENERATION_GOVERNANCE.md`.

### 2.1 Official Editable Blog Template

The official editable template is [`HP/source/template_kagoshima-deliveryhealth-blog.html`](../../HP/source/template_kagoshima-deliveryhealth-blog.html). Its staff-blog layout uses the shared `HP/css/blog.css`, `blog-page` components, and the `blog-theme-deep-pink` theme. Maintain future template changes in this file. `kagoshima-deliveryhealth-blog-candy_dominant_girls.html` is a separate page reserved for another purpose; do not use it as the template authority or synchronize subsequent template edits into it.

The template intentionally contains sample text, a placeholder SVG, sample links, `noindex, nofollow`, and sample canonical/OGP/JSON-LD URLs. When creating a publishable article, replace these with the approved article content, images, destinations, indexing directive, and canonical URL. Do not publish the template unchanged or register the template itself in the blog index or sitemap.

For this editable staff-blog layout, `sceneN` belongs to the section containing its H2; the table of contents targets those sections. The table-of-contents and related-content headings are outside the numbered article scenes. Introductory text uses `blog-lead`; article bodies use the `blog-section` components rather than requiring the legacy `subtitle_N` / `description_N` wrappers. Keep scene order, visible headings, and table-of-contents links consistent. The sample has Article, BreadcrumbList, and FAQPage JSON-LD. Update Article to the actual article and author, and add ItemList only when a corresponding girl-introduction block is present. Sample block counts are not fixed requirements. These are the staff-template exceptions to the legacy structure in Sections 4–7; source-content and visible/structured-data consistency requirements still apply.

`candy_blog_page.py` currently reads this template as a shell, then replaces the main content and JSON-LD with its own legacy renderers. It does not reproduce the editable staff-blog layout and does not replace the template's robots directive. Its successful execution alone therefore does not verify staff-template design or publication readiness. Apply the official template directly for that layout and verify the article's robots directive before publication; do not treat the existing generator as supporting the new layout.

### 2.2 Blog CSS Ownership

- Put all blog-specific styles, components, and theme variations in [`HP/css/blog.css`](../../HP/css/blog.css). Do not create, restore, or reference per-article CSS files such as `blog-<slug>.css`.
- Reuse the site's existing [`HP/source/style.css`](../../HP/source/style.css) and [`HP/css/default.css`](../../HP/css/default.css) for site-wide components, including the header and footer. Do not duplicate site-wide styling in blog.css.
- Scope blog-only styling under `.blog-page`, and use shared `blog-*` components and `blog-theme-*` classes in article HTML and the official template. Do not move per-article styling into HTML style blocks or style attributes to bypass this rule. Existing articles that use only site-wide components need no artificial blog-only styles.
- When blog.css changes, use the same SHA-256 prefix (first eight lowercase hexadecimal characters) as the `v` query value in every HTML file that references it, including the official template. Preserve unrelated article content.
- Verify that the old per-article CSS files are absent, no implementation references them, and each article/template references existing CSS files. When style declarations or markup change, verify desktop and mobile display for the affected pages.

## 3. Required File and Public-Route Change Unit

```text
Text_blog_data/<対象記事>.txt
HP/source/template_kagoshima-deliveryhealth-blog.html
codex/data/CANDY_GIRL_INFORMATION.json

HP/kagoshima-deliveryhealth-blog-<slug>.php
HP/source/kagoshima-deliveryhealth-blog-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php
HP/includefile/dataset_base.php
HP/source/blog.html
HP/source/index.html
HP/sitemap.xml
```

Determine the slug by reconciling canonical in source Text, image names, and filenames.

## 4. Source-Data to HTML Mapping

| Source-data item | HTML target |
|---|---|
| title | Title and OGP title when applicable |
| description | Meta description and OGP description |
| canonical | Canonical and OGP URL |
| img_1 | Main image, OGP image, and alt |
| page_title_h1 | Breadcrumb and H1 |
| subtitle_h1 | `subtitle_h1` |
| description_h1 | `description_h1` |
| img_2 | Article image and alt |
| Normal articles | Scenes, subtitles, and descriptions |
| Manager recommendations | Girl-introduction blocks |
| Customer comments | Multiple items under one scene |
| FAQ | Multiple FAQ items |
| Summary | Final scene, subtitle, and description |
| Entire page | Table of contents and three JSON-LD blocks |

Do not add unnecessary visible line breaks when source data does not specify them.

## 5. Scenes and Table of Contents

Base elements:

1. Normal article scenes
2. Manager-recommended girls
3. Customer comments
4. FAQ
5. Summary

Order is not fixed. Prioritize display order in source data and number H2
elements in actual display order. Do not assume a fixed
customer-comments-before-FAQ order.

Rules:

- Number H2 elements sequentially from `scene1` through `sceneN`.
- Normal content uses `subtitle_N` and `description_N`.
- Include every H2 in the table of contents and match each `href="#sceneN"` to the actual H2 ID.
- Renumber every following scene after a scene is added or removed.
- For customer comments under parent scene S, use `sceneS_1`, `subtitle_S_1`, and `description_S_1`.
- For FAQ under parent scene S, use `subtitle_S_1` and `description_S_1`.
- Duplicate IDs, numbering gaps, and scenes that exist only in the table of contents are prohibited.
- Under `関連記事`, publish three distinct indexable blog-detail links and three distinct indexable area-detail links selected deterministically from current public files. Exclude the current page, duplicate destinations, placeholder text, and `href="#"`.

### 5.1 Coupled Blog Synchronization

A single block change affects multiple locations. Update together:

- Main H2 scene numbers
- Child scene/subtitle/description numbers for customer comments
- Child subtitle/description numbers for FAQ
- Table-of-contents copy, order, and href values
- FAQPage JSON-LD questions, answers, order, and count
- Girl ItemList names, images, URLs, positions, and count
- Summary scene number

## 6. Variable Blocks

Add or remove customer comments, normal articles, FAQs, and table-of-contents
entries according to source data; none of these counts is fixed.

For girl introductions, compare the local woman-information ledger and current complete pages and include only specified `PUBLIC` records. Match name, image, profile URL, and JSON-LD ItemList. Do not infer girl information or render a `LOCAL_ONLY` record.

## 7. JSON-LD

A blog page uses three JSON-LD blocks:

- BreadcrumbList
- FAQPage
- Girl-introduction ItemList type

Validate:

- Breadcrumb and canonical agreement
- FAQ visible content against FAQPage questions, answers, and count
- Girl-introduction visible content against ItemList names, images, URLs, and count
- Zero placeholders
- Valid JSON syntax

## 8. Public Entry PHP and Dataset PHP

Public entry PHP uses the same base form as area and loads `dataset_base.php`.

Normal blog dataset PHP uses:

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

As an exception, `dataset_kagoshima-deliveryhealth-blog-slendergirl.php` only loads source and does not replace `$waku0`. Use the standard form for a new page and do not carry this exception into normal generation. Handle an existing-exception fix as a separate development change.

## 9. dataset_base.php Registration

Always add for a new page:

```php
case 'kagoshima-deliveryhealth-blog-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-blog-<slug>.php');
    break;
```

```php
$source = str_replace(
    'kagoshima-deliveryhealth-blog-<slug>.html',
    'kagoshima-deliveryhealth-blog-<slug>.php',
    $source
);
```

Normal generation requires one matching current-name case and link
transformation. It does not permit omitted registration.

## 10. Blog-Specific Generation Sequence

The common generation gates remain in
`CANDY_PAGE_GENERATION_GOVERNANCE.md`. Within those gates:

1. Verify required source-text items, canonical, slug, and images.
2. Compare the blog template, the local woman-information ledger, and a current complete page.
3. Generate source HTML and apply SEO, OGP, H1, body, and images.
4. Build normal scenes, girl introductions, customer comments, and FAQs
   according to source-data counts.
5. Number scenes from the top and synchronize the table of contents.
6. Synchronize all three JSON-LD blocks to visible content.
7. Generate public entry PHP and dataset PHP.
8. Register the `dataset_base.php` case and link transformation.
9. Check placeholders, duplicate IDs, numbering gaps, table-of-contents
   mismatches, canonical, images, internal links, and girl numbers.
10. Synchronize the complete blog index, its JSON-LD, the top-page latest 15
    blog entries, and `sitemap.xml` under Section 10.1 of
    `CANDY_PAGE_GENERATION_GOVERNANCE.md`.

## 11. Exceptions and Cautions

- Scene count is not fixed.
- Do not fix FAQ, customer-comment, or normal-article counts to template defaults.
- Use a complete page without source Text only as a structural reference.
- Only the slendergirl dataset PHP differs from the standard form.
- Do not mix existing-exception fixes into new-page generation.
- Follow source-data order for FAQ and customer comments instead of copying an existing-page order mechanically.

## 12. Blog-Specific Completion Criteria

- [ ] Source Text, slug, and canonical are confirmed.
- [ ] Scenes and table of contents agree.
- [ ] Source H2 order matches actual scene order.
- [ ] Customer-comment and FAQ numbering is correct.
- [ ] Visible and JSON-LD counts agree for customer comments, FAQs, and girl introductions.
- [ ] Visible FAQ matches FAQPage JSON-LD.
- [ ] Visible girl content matches ItemList JSON-LD.
- [ ] The blog index contains every public blog once, and the top-page blog
      section contains its final 15 registrations, or all when fewer than 15
      exist, in the same order and with the same names.
- [ ] Sitemap registration requirements satisfy the common public-route
      synchronization contract.
- [ ] No duplicate ID exists.

## 13. Specification Boundary

Current page counts, individual defects, source availability, Git state, and
production state are intentionally not stored here. Obtain them from actual
files and the generated current-state documents selected by `codex/WORK_ROUTING.md`.
