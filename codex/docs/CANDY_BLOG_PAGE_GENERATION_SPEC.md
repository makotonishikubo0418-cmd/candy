# CANDY Blog Page Generation Specification

- Updated: 2026-07-12
- Applies to: Normal new generation of CANDY blog detail pages by Codex

## 1. Purpose and Scope

This is the canonical specification for generating blog pages from source Text without damage. Use it for normal new-page generation, not for bug fixes, existing-feature changes, common-processing changes, or refactoring.

Apply `CANDY_PAGE_GENERATION_GOVERNANCE.md` first for common missing-input, variable-structure, and STOP rules.

## 2. Mandatory Rules

- Use the target text file under `Text_blog_data` as source data.
- Use `HP/source/template_kagoshima-deliveryhealth-blog.html` as the HTML template.
- Treat public entry PHP, source HTML, page-specific dataset PHP, and `dataset_base.php` registration as one set.
- Do not report completion after generating only HTML.
- Do not normally use `create.php` for Codex page generation.
- Adjust the table of contents, normal scenes, girl introductions, customer comments, FAQ, and summary to source-data counts.
- Keep JSON-LD, visible content, and the table of contents consistent in content and count.

## 3. Full-Population Verification Results

Every blog-related actual file was reviewed on 2026-07-12.

| Target | Count | Notes |
|---|---:|---|
| Text files under `Text_blog_data` | 3 | ぽっちゃり, 素人, and 長身 |
| Blog source HTML | 6 | No remaining placeholders |
| Blog public entry PHP | 6 | Same base form in every file |
| Blog page-specific dataset PHP | 6 | One processing exception |
| Current-name blog cases in `dataset_base.php` | 0 | All six are unregistered |
| Current-name blog link transformations in `dataset_base.php` | 0 | All six are unregistered |

Current source HTML:

```text
glamourgirl
petitegirl
poccharigirl
shiroutogirl
slendergirl
tallbeautygirl
```

Current source Text remains for `poccharigirl`, `shiroutogirl`, and `tallbeautygirl`. The other three complete HTML pages may be used as structural references, but their source Text is unverified.

## 4. Required File Set

```text
Text_blog_data/<対象記事>.txt
HP/source/template_kagoshima-deliveryhealth-blog.html

HP/kagoshima-deliveryhealth-blog-<slug>.php
HP/source/kagoshima-deliveryhealth-blog-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php
HP/includefile/dataset_base.php
```

Determine the slug by reconciling canonical in source Text, image names, and filenames.

## 5. Source-Data to HTML Mapping

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

## 6. Scenes and Table of Contents

The six complete pages have eight or nine scenes; this is not a fixed count.

Base elements:

1. Normal article scenes
2. Manager-recommended girls
3. Customer comments
4. FAQ
5. Summary

Order is not fixed. Prioritize display order in source data and number H2 elements in actual display order. Existing `petitegirl` places customer comments after FAQ, so do not assume a fixed customer-comments-before-FAQ order.

Rules:

- Number H2 elements sequentially from `scene1` through `sceneN`.
- Normal content uses `subtitle_N` and `description_N`.
- Include every H2 in the table of contents and match each `href="#sceneN"` to the actual H2 ID.
- Renumber every following scene after a scene is added or removed.
- For customer comments under parent scene S, use `sceneS_1`, `subtitle_S_1`, and `description_S_1`.
- For FAQ under parent scene S, use `subtitle_S_1` and `description_S_1`.
- Duplicate IDs, numbering gaps, and scenes that exist only in the table of contents are prohibited.
- Under `関連記事`, publish three distinct indexable blog-detail links and three distinct indexable area-detail links selected deterministically from current public files. Exclude the current page, duplicate destinations, placeholder text, and `href="#"`.

### 6.1 Coupled Blog Synchronization

A single block change affects multiple locations. Update together:

- Main H2 scene numbers
- Child scene/subtitle/description numbers for customer comments
- Child subtitle/description numbers for FAQ
- Table-of-contents copy, order, and href values
- FAQPage JSON-LD questions, answers, order, and count
- Girl ItemList names, images, URLs, positions, and count
- Summary scene number

Across the six complete pages, main scenes number eight or nine, FAQ items five through nine, customer comments four or five, and the girl ItemList five. These are existing observations, not fixed values for a new page.

## 7. Variable Blocks

FAQ count varies from five through nine on complete pages. Add or remove customer comments, normal articles, FAQs, and table-of-contents entries according to source data.

For girl introductions, compare the template and current complete pages and include only specified girls. Match name, image, `girls.php?no=`, and JSON-LD ItemList. Do not infer girl information.

## 8. JSON-LD

All six complete blog pages have three JSON-LD blocks that parse successfully:

- BreadcrumbList
- FAQPage
- Girl-introduction ItemList type

Validate:

- Breadcrumb and canonical agreement
- FAQ visible content against FAQPage questions, answers, and count
- Girl-introduction visible content against ItemList names, images, URLs, and count
- Zero placeholders
- Valid JSON syntax

## 9. Public Entry PHP and Dataset PHP

Public entry PHP uses the same base form as area and loads `dataset_base.php`.

Normal blog dataset PHP uses:

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

As an exception, `dataset_kagoshima-deliveryhealth-blog-slendergirl.php` only loads source and does not replace `$waku0`. Use the standard form for a new page and do not carry this exception into normal generation. Handle an existing-exception fix as a separate development change.

## 10. dataset_base.php Registration

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

Current `dataset_base.php` retains legacy-name cases and legacy dataset references without `blog-`. Each of the six current `kagoshima-deliveryhealth-blog-*` pages also has one matching current-name case and link transformation. Normal generation does not permit omitted registration.

## 11. Generation Algorithm

1. Verify Git state and target scope.
2. Verify required source-text items, canonical, slug, and images.
3. Check same-name three-file set and dataset_base registration.
4. Compare the blog template and complete pages.
5. Generate source HTML and apply SEO, OGP, H1, body, and images.
6. Build normal scenes according to source data.
7. Build girl introductions, customer comments, and FAQs according to counts.
8. Number scenes from the top and synchronize the table of contents.
9. Synchronize all three JSON-LD blocks to visible content.
10. Generate public entry PHP and dataset PHP.
11. Register the dataset_base case and link transformation.
12. Check placeholders, duplicate IDs, gaps, and table-of-contents mismatches.
13. Check canonical, images, internal links, and girl numbers.
14. Determine whether `source/blog.html` index links and JSON-LD and `sitemap.xml` require registration.
15. Validate PHP syntax, JSON syntax, and the diff.
16. Synchronize generated management documents with `candy-site-state write` and `check`.
17. State when browser validation is unverified.
18. Commit and Push only with explicit instruction.

## 12. Exceptions and Cautions

- Scene count is eight or nine and is not fixed.
- Do not fix FAQ, customer-comment, or normal-article counts to template defaults.
- Use a complete page without source Text only as a structural reference.
- Source Text under `Text_blog_data` refers to a nonexistent `Cursor用更新手順.txt`; that reference alone does not prove the procedure was reviewed.
- All six current blog pages have current-name dataset_base case and link-transformation registration.
- Only the slendergirl dataset PHP differs from the standard form.
- Do not mix existing-exception fixes into new-page generation.
- The `glamourgirl` summary heading retains a different topic name; do not copy its wording.
- Follow source-data order for FAQ and customer comments instead of copying an existing-page order mechanically.

## 13. Completion Criteria

- [ ] Source Text, slug, and canonical are confirmed.
- [ ] Public PHP, source HTML, and dataset PHP exist.
- [ ] dataset_base case and link transformation exist.
- [ ] Placeholder count is zero.
- [ ] Scenes and table of contents agree.
- [ ] Source H2 order matches actual scene order.
- [ ] Customer-comment and FAQ numbering is correct.
- [ ] Visible and JSON-LD counts agree for customer comments, FAQs, and girl introductions.
- [ ] Visible FAQ matches FAQPage JSON-LD.
- [ ] Visible girl content matches ItemList JSON-LD.
- [ ] Images exist.
- [ ] Canonical, OGP, and internal links are correct.
- [ ] Robots agrees with publication policy.
- [ ] Blog-index and sitemap registration requirements were checked.
- [ ] No duplicate ID exists.
- [ ] PHP syntax, JSON syntax, and `git diff --check` were validated.
- [ ] Before staging, `codex\scripts\candy-site-state.cmd write` and `check` succeeded.

## 14. Unchanged Scope

This investigation did not change existing blog pages, PHP, dataset PHP, `dataset_base.php`, images, or source Text.
