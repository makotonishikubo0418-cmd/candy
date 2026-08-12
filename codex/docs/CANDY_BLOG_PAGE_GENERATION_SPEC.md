# CANDY Blog Page Generation Specification
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Normal generation of CANDY blog detail pages
- Lifecycle: Active
- Source of Truth Responsibility: Canonical blog-page generation specification
- Related Documents: `CANDY_PAGE_GENERATION_GOVERNANCE.md` and generated current state
- Related Implementation Files: Blog Text, generator, PHP, source HTML, dataset, indexes, images, and sitemap

- Updated: 2026-07-26
- Applies to: Normal new generation of CANDY blog detail pages by Codex

## 1. Purpose and Scope

This is the canonical specification for generating blog pages from source Text without damage. Use it for normal new-page generation, not for bug fixes, existing-feature changes, common-processing changes, or refactoring.

This document owns only blog-specific page structure, source mapping, variable
blocks, and validation requirements. Common generation rules, Git, publication,
and routing remain in the applicable documents selected from `codex/WORK_ROUTING.md`
Section 5.2.

## 2. Mandatory Rules

- Use the target text file under `Text_blog_data` as source data.
- Use `HP/source/template_kagoshima-deliveryhealth-blog.html` as the HTML template.
- Adjust the table of contents, normal scenes, girl introductions, customer comments, FAQ, and summary to source-data counts.
- Keep JSON-LD, visible content, and the table of contents consistent in content and count.
- Apply the common collision, incomplete-input, change-boundary, and completion
  gates from `CANDY_PAGE_GENERATION_GOVERNANCE.md`.

## 3. Required File and Public-Route Change Unit

```text
Text_blog_data/<対象記事>.txt
HP/source/template_kagoshima-deliveryhealth-blog.html

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

For girl introductions, compare the template and current complete pages and include only specified girls. Match name, image, `girls.php?no=`, and JSON-LD ItemList. Do not infer girl information.

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
2. Compare the blog template and a current complete page.
3. Generate source HTML and apply SEO, OGP, H1, body, and images.
4. Build normal scenes, girl introductions, customer comments, and FAQs
   according to source-data counts.
5. Number scenes from the top and synchronize the table of contents.
6. Synchronize all three JSON-LD blocks to visible content.
7. Generate public entry PHP and dataset PHP.
8. Register the `dataset_base.php` case and link transformation.
9. Check placeholders, duplicate IDs, numbering gaps, table-of-contents
   mismatches, canonical, images, internal links, and girl numbers.
10. Synchronize the blog index, its JSON-LD, the top-page blog section, and
    `sitemap.xml` under Section 10.1 of
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
- [ ] Blog-index, top-page blog-section, and sitemap registration requirements
      satisfy the common public-route synchronization contract.
- [ ] No duplicate ID exists.

## 13. Specification Boundary

Current page counts, individual defects, source availability, Git state, and
production state are intentionally not stored here. Obtain them from actual
files and the generated current-state documents selected by `codex/WORK_ROUTING.md`.
