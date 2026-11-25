# CANDY Blog Page Generation Specification

- Updated: 2026-11-05
- Applies to: CANDY blog-detail structure and validation within authorized creation or modification work
- Operational sequence: `BLOG_CREATION.md`
- Common image requirements: `IMAGE_MANAGEMENT.md`

## 1. Purpose and Scope

This is the canonical specification for blog-detail content mapping, fixed components, variable blocks, and validation. Apply it to the article being created or to the explicitly authorized existing-page modification. It does not authorize changes to other articles or common processing.

Use [INDEX.md](../INDEX.md) for common generation, existing-site operation, Git, and production responsibilities. [BLOG_CREATION.md](BLOG_CREATION.md) owns the completed-article execution sequence; this specification owns the required result.

For a URL/title-only request to prepare a template before the user writes the article HTML, use [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md). That document owns the executable scaffold code, minimal inputs, local PHP/registry/sitemap preparation, and the separate scaffold completion state. The source-Text requirements below apply to completed-article generation, not to that preparation route.

## 2. Mandatory Rules

- Use the user-designated article manuscript as source data. For structured Text generation, use its target file under `Text_blog_data`; do not invent missing content to satisfy a legacy input format.
- Use `HP/source/template_kagoshima-deliveryhealth-blog.html` as the HTML template.
- When a girl-introduction block is required, load information from `management/data/CANDY_GIRL_INFORMATION.json` through `candy_girl_information.py`, following [the girl-information specification](CANDY_GIRL_INFORMATION_MANAGEMENT.md). Select only specified records whose image state is `PUBLIC`.
- Adjust the table of contents, normal scenes, girl introductions, customer comments, FAQ, and summary to source-data counts.
- Keep JSON-LD, visible content, and the table of contents consistent in content and count.
- Apply the common collision, incomplete-input, change-boundary, and completion
  gates from `CANDY_PAGE_GENERATION_GOVERNANCE.md`.

### 2.1 Official Editable Blog Template

The official editable template is [`HP/source/template_kagoshima-deliveryhealth-blog.html`](../../HP/source/template_kagoshima-deliveryhealth-blog.html). Its staff-blog layout uses the shared `HP/css/blog.css`, `blog-page` components, and the `blog-theme-deep-pink` theme. Maintain future template changes in this file. `kagoshima-deliveryhealth-blog-candy_dominant_girls.html` is a separate page reserved for another purpose; do not use it as the template authority or synchronize subsequent template edits into it.

The template contains sample article text, a placeholder SVG, sample related links, `noindex, nofollow`, and sample canonical/OGP/JSON-LD URLs. Replace those article-specific placeholders with approved content and publication metadata. Fixed site-navigation components follow Section 2.4 and are not article-specific placeholders. Do not publish or register the template itself in the blog index or sitemap.

The staff-blog structure is defined in Sections 4–7. Sample counts for source-derived article scenes, comments, FAQs, and girl introductions are variable; fixed navigation and related-link requirements remain mandatory. Existing articles are references for appearance, not authorities for changing these requirements.

`candy_blog_page.py` currently reads this template as a shell, then replaces the main content and JSON-LD with its own legacy renderers. It does not reproduce the editable staff-blog layout and does not replace the template's robots directive. Its successful execution alone therefore does not verify staff-template design or publication readiness. Apply the official template directly for that layout and verify the article's robots directive before publication; do not treat the existing generator as supporting the new layout.

### 2.2 Blog CSS Ownership

- Put all blog-specific styles, components, and theme variations in [`HP/css/blog.css`](../../HP/css/blog.css). Do not create, restore, or reference per-article CSS files such as `blog-<slug>.css`.
- Reuse the site's existing [`HP/source/style.css`](../../HP/source/style.css) and [`HP/css/default.css`](../../HP/css/default.css) for site-wide components, including the header and footer. Do not duplicate site-wide styling in blog.css.
- Scope blog-only styling under `.blog-page`, and use shared `blog-*` components and `blog-theme-*` classes in article HTML and the official template. Do not move per-article styling into HTML style blocks or style attributes to bypass this rule. Existing articles that use only site-wide components need no artificial blog-only styles.
- When blog.css changes, use the same SHA-256 prefix (first eight lowercase hexadecimal characters) as the `v` query value in every HTML file that references it, including the official template. Preserve unrelated article content.
- Verify that the old per-article CSS files are absent, no implementation references them, and each article/template references existing CSS files. When style declarations or markup change, verify desktop and mobile display for the affected pages.

### 2.3 URL/Title-Only Template Code

The management-side code authority for URL/title-only scaffolds is [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md), consumed directly by [candy_blog_template.py](../scripts/candy_blog_template.py). It prepares HTML, public PHP, dataset PHP, shared routing, category/top links, and sitemap together. The editable staff template in Section 2.1 remains the full article component/design reference. Scaffold-specific handling of unfinished body content, metadata, images, and JSON-LD is defined only in the management-side template document. Once the user supplies completed HTML, apply this specification's relevant content, CSS, SEO, and display checks.

### 2.4 Fixed Article CTA

Every completed blog detail has one `.blog-cta` after the article body and before `.blog-related`. Its `.blog-cta-actions` contains exactly two links. Select the right link from the user's explicit `ブログカテゴリ` instruction; the only accepted values are `一般` and `求人`. If the value is missing, ambiguous, or unsupported, confirm it before finalizing the CTA. Do not infer it from the title, manuscript, URL, or an older article, and do not silently default to `一般`.

| ブログカテゴリ | Position | Exact visible label | href | Link class |
|---|---|---|---|---|
| Both | Left; first in DOM / when stacked | 女の子一覧 | `./girls_list.php` | `blog-cta-link is-primary` |
| 一般 | Right; second in DOM / when stacked | 本日の出勤情報 | `./schedule.php` | `blog-cta-link is-secondary` |
| 求人 | Right; second in DOM / when stacked | 求人情報はコチラ | `./kagoshima-deliveryhealth-blog-privacy-measures.php` | `blog-cta-link is-secondary` |

The recruitment destination is `https://www.55810.com/kagoshima-deliveryhealth-blog-privacy-measures.php`. This category selection controls the CTA only; it does not create separate blog registries or change the article URL, breadcrumb, or JSON-LD type.

Do not omit, add, reverse, rename, or substitute the selected buttons based on article content or supplied body links. Source-derived links elsewhere in the article remain separate. The introductory CTA sentence and article theme may vary, but the sentence must describe the selected destinations and use no invented facts. For `求人`, replace any invitation to view today's schedule with an invitation to view recruitment information. Shared CSS owns layout; preserve both buttons on desktop and mobile.

The editable template and scaffold code contain the `一般` initial example, not a category decision. Apply the explicitly selected category to the article's right link and introductory sentence, and preserve that result when article content is replaced. Record the selected category with CTA evidence under [the verification plan](CANDY_VERIFICATION_PLAN.md#39-fixed-page-components); a successful SEO, registry, or scaffold check alone does not establish CTA compliance.

## 3. Required File and Public-Route Change Unit

```text
HP/kagoshima-deliveryhealth-blog-<slug>.php
HP/source/kagoshima-deliveryhealth-blog-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php
HP/includefile/dataset_base.php
HP/source/blog.html
HP/source/index.html
HP/sitemap.xml
```

The manuscript, editable template, and conditional girl-information ledger are inputs, not files to modify for every article. Determine the slug from the approved canonical, image names, and filenames. Include necessary assets and generated-state updates through [BLOG_CREATION.md](BLOG_CREATION.md#3-標準作成単位).

## 4. Source-Data to HTML Mapping

| Source-data item | HTML target |
|---|---|
| title | Title and OGP title when applicable |
| description | Meta description and OGP description |
| canonical | Canonical and OGP URL |
| img_1 | Main image, OGP image, and alt |
| page_title_h1 | Breadcrumb and H1 |
| Introductory copy | `blog-lead` within `blog-intro` |
| img_2 | Article image and alt |
| Normal articles | `blog-section` sections, H2/H3 headings, and body content |
| Manager recommendations | Girl-introduction blocks |
| Customer comments | Multiple items under one scene |
| FAQ | Multiple FAQ items |
| Summary | Final scene, subtitle, and description |
| Site navigation | Fixed CTA in Section 2.4; independent of manuscript link count |
| Entire page | Table of contents and applicable JSON-LD in Section 7 |

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

- Number article sections sequentially from `scene1` through `sceneN`; put the ID on the `blog-section` containing its H2.
- Use `blog-section` body components. Legacy `subtitle_N` / `description_N` wrappers are not required for this layout.
- Include each article H2 in the table of contents and match each `href="#sceneN"` to its section ID. The table-of-contents and related-content headings are outside article numbering.
- Renumber every following scene after a scene is added or removed.
- Where child IDs are used, keep them unique and tied to their parent scene; do not add legacy wrappers solely to satisfy the old renderer.
- Duplicate IDs, numbering gaps, and scenes that exist only in the table of contents are prohibited.
- Under `関連記事`, publish three distinct indexable blog-detail links and three distinct indexable area-detail links selected deterministically from current public files. Exclude the current page, duplicate destinations, placeholder text, and `href="#"`.

### 5.1 Coupled Blog Synchronization

A single block change affects multiple locations. Update together:

- Article section IDs
- Child IDs for customer comments and FAQs, when present
- Table-of-contents copy, order, and href values
- FAQPage JSON-LD questions, answers, order, and count
- Girl ItemList names, images, URLs, positions, and count
- Summary scene number

## 6. Variable Blocks

Add or remove customer comments, normal articles, FAQs, and table-of-contents
entries according to source data; none of these counts is fixed.

For girl introductions, compare the local woman-information ledger and current complete pages and include only specified `PUBLIC` records. Match name, image, profile URL, and JSON-LD ItemList. Do not infer girl information or render a `LOCAL_ONLY` record.

## 7. JSON-LD

A completed staff blog uses the following applicable JSON-LD. Do not force a fixed total block count.

| Type | Condition |
|---|---|
| Article | Required; use actual approved article metadata and omit unprovided author or date values |
| BreadcrumbList | Required; match the visible breadcrumb and canonical |
| FAQPage | Only when a visible FAQ exists; omit when there is none |
| Girl-introduction ItemList | Only when the corresponding approved girl-introduction block is visible |

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

Follow [BLOG_CREATION.md Section 4](BLOG_CREATION.md#4-作成順序) for completed articles and [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md) for URL/title-only preparation. Common generation gates remain in [CANDY_PAGE_GENERATION_GOVERNANCE.md](CANDY_PAGE_GENERATION_GOVERNANCE.md). Tool limitations in Section 2.1 must be considered before selecting a build command.

## 11. Existing Pages

Older articles may use legacy structures or navigation. Their existence does not define an exception to the current contract. Apply the applicable requirements to the authorized target while preserving unrelated body content and assets. An unrelated article's migration or an existing dataset exception remains separately scoped work under [CANDY_OPERATION_BASICS.md](CANDY_OPERATION_BASICS.md).

## 12. Blog-Specific Completion Criteria

The execution checklist is [BLOG_CREATION.md Section 8](BLOG_CREATION.md#8-完了確認). It must establish compliance with this specification's fixed components, source-derived content, conditional JSON-LD, CSS, and public-route requirements before staging or publication. [CANDY_VERIFICATION_PLAN.md](CANDY_VERIFICATION_PLAN.md) owns evidence and result classification. A checklist, tool result, and actual browser observation must not be treated as interchangeable evidence.

## 13. Specification Boundary

Current page counts, individual defects, source availability, Git state, and
production state are intentionally not stored here. Obtain them from actual
files and the generated current-state documents selected by [INDEX.md](../INDEX.md).
