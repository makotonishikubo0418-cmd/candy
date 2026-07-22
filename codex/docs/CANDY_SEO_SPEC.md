# CANDY Common SEO Specification

## 1. Responsibility and Change Gate

This is the canonical common SEO specification verified from current CANDY source HTML and category specifications. Per-page current state and inconsistencies belong in `generated/CANDY_SEO_STATUS.md`.

This specification describes current behavior. Management-document maintenance alone does not authorize configuration changes. Changes to robots index/noindex, canonical URLs, public URLs, redirects, or structured-data types require a separate task that identifies the affected scope and explicit user instruction.

## 2. Page-Type Fundamentals

| Type | Title and H1 | Canonical | Primary structured data |
|---|---|---|---|
| top | Brand and primary keywords. One H1 for the page subject | Site root | Site, FAQ, and ItemList types defined by the actual page |
| area index | Identifies the supported-area index | `/area.php` | BreadcrumbList and current page-description types |
| area detail | Reflects the region name, available delivery-health services, shops, hotels, and related source Text exactly | `/kagoshima-deliveryhealth-area-<slug>.php` | BreadcrumbList and shop ItemList types, synchronized with visible content |
| hotel index | Represents the actual list of callable hotels, fee estimates, and availability | `/hotel.php` | BreadcrumbList and the index's current structure |
| hotel detail | Reflects the hotel name and the Kagoshima delivery-health use page exactly as provided by source Text | `/kagoshima-deliveryhealth-hotel-<slug>.php` | BreadcrumbList, FAQPage, shop ItemList, and related types for visible content only |
| blog index | Identifies the official CANDY blog index | `/blog.php` | BreadcrumbList and the index's current structure |
| blog detail | Reflects the article topic and its relationship to CANDY exactly as provided by source Text | `/kagoshima-deliveryhealth-blog-<slug>.php` | BreadcrumbList, FAQPage, and introduction ItemList synchronized with body and table of contents |
| girls/system/other | Verify the target feature and runtime content in actual files | Matches the public URL | Do not apply category-wide assumptions because content may be database-generated |

Do not duplicate title, description, or H1 copy as fixed text in this document. Detail pages use their matching `Text_*_data`; indexes and dynamic pages use actual source and feature requirements.

## 3. Meta Description

- Each current public content page has one page-specific description.
- A detail page prioritizes the source Text description. Do not add a service, region, price, or availability absent from the body.
- An index description represents the index scope and content users can inspect; do not reuse one detail-page description.
- Treat a duplicate, empty, placeholder, or body-inconsistent description as an issue in `CANDY_SEO_STATUS.md`. Do not fix it automatically.

## 4. Canonical and URL

- Current canonical URLs use the absolute `https://www.55810.com/...` form.
- A detail page MUST match category and slug to the public PHP filename.
- Validate the OGP URL, final breadcrumb item, JSON-LD URL, index links, internal links, and sitemap together with the canonical URL.
- The current top-page structure uses the site-root canonical. Do not infer a change based on the presence or absence of `index.php`.
- When multiple pages share one canonical URL, category or slug differs, or a legacy URL appears, mark it `CONFLICT` or SEO `ISSUE`. Do not change it before the owner identifies the canonical URL.

## 5. Robots

- Current representative top, area/hotel/blog index, and detail pages contain `<meta name="robots" content="index">`.
- Verify templates, special entry points, and dynamic pages individually.
- Management scripts audit only the presence and value of robots; they do not rewrite it.
- A change between index and noindex requires an impact statement for publication scope, canonical URLs, the sitemap, and internal links and requires explicit approval.

## 6. Headings

- A public content page normally has one H1 for its subject. It MUST NOT conflict with the breadcrumb, title, or canonical target.
- H2 and lower headings follow visible order. Do not skip a level only for styling.
- `sceneN`, `subtitle_N`, and `description_N` on area, hotel, and blog details follow the category specification and remain synchronized with the table of contents, FAQ, and JSON-LD.
- Do not fix variable block counts to counts found in an existing page. Output only complete blocks from source Text.
- Duplicate IDs, numbering gaps, headings present only in the table of contents, and table-of-contents entries absent from the body are prohibited.

## 7. OGP

Current content pages use a structure containing `og:title`, `og:type`, `og:url`, `og:image`, `og:description`, and `og:site_name`.

- `og:title` and `og:description` MUST match the meaning of title and description.
- `og:url` MUST match the canonical URL.
- `og:image` MUST be an absolute URL for an existing public image. Verify category-image naming, rights, and rendering.
- Do not update OGP alone and leave the body, canonical URL, or image inconsistent.

## 8. JSON-LD

### 8.1 Common Rules

- `<script type="application/ld+json">` MUST parse as JSON.
- Do not add a name, URL, image, question, answer, or shop to structured data when it is absent from visible content.
- Synchronize URLs, images, visible order, and counts with body content.
- Do not standardize per-page schema differences from general assumptions. Check the category specification and actual page.

### 8.2 BreadcrumbList

- Apply this section to breadcrumbs currently implemented on index and detail pages other than top.
- Synchronize the visible breadcrumb, `position`, name, URL, and final page.
- Do not skip the category index in the path to a detail page; use the public URL.

### 8.3 FAQPage

- Generate FAQPage for hotel, blog, and related pages only when visible FAQ content exists.
- Questions, answers, order, and count MUST match visible content.
- Do not create an empty FAQPage when the FAQ count is zero.
- Automated audits verify syntax and static count. Semantic equivalence remains a manual review requirement.

### 8.4 ItemList

- Use the current schema corresponding to visible shop, girl, or related lists.
- `position`, names, URLs, images, and counts MUST match visible blocks.
- Do not add the nearby-area links to structured data unless a separately approved schema visibly represents the same list.

## 9. Internal Links, Related Articles, and Orphans

- Category indexes are the normal route to detail pages.
- For top category sections, breadcrumbs, nearby-area links, and cross-category links, verify both display names and public-PHP existence.
- Area detail pages use `周辺の対応エリア` with three to six exact links from `codex/data/CANDY_AREA_RELATED_LINKS.json`, normally four. Use descriptive link text, reject self-links and duplicates, and omit the block when fewer than three suitable completed targets exist.
- Placeholder link copy and `href="#"` are prohibited in generated public area sources.
- A page with no detected static-source references is an orphan candidate. Do not delete or redirect it until PHP-, database-, or JavaScript-generated links are excluded.
- Record broken internal links, different slugs, and legacy URLs as findings. Do not fix them automatically.

## 10. Image Alt Text

- A content image MUST have non-empty alt text describing its subject.
- Region, hotel, and article images use the target name from source Text and the category specification. Do not leave a placeholder.
- Decide whether a decorative image uses empty alt text from its actual role; do not insert copy mechanically.
- Validate OGP/JSON-LD images, HTML images, actual files, and desktop/mobile rendering together.

## 11. Sitemap, Legacy URLs, and Duplicates

- Determine whether a public URL belongs in `sitemap.xml` according to the category specification.
- Sitemap registration does not replace index and internal links.
- `HP/.htaccess` contains active rules that redirect HTTP, non-www, and explicit `index.php` or `index.html` URLs to the `https://www.55810.com` canonical form.
- The normal Push-triggered production workflow keeps `.htaccess` protected. Production publication requires the dedicated manual `.htaccess` preview/deploy workflow, an exact one-file plan, and live redirect verification.
- Creating or changing a legacy URL or redirect requires a separate task that verifies inbound traffic, canonical URLs, internal links, the sitemap, and production behavior.
- Automated audits detect duplicate titles/canonicals, partial builds, different slugs, and same-content candidates. Do not infer the canonical URL.

## 12. Validation

```powershell
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

After a change, validate the category runbook requirements and:

- Title, description, canonical, robots, H1, and heading hierarchy
- OGP consistency with canonical and images
- JSON-LD syntax and consistency of BreadcrumbList, FAQPage, and ItemList with visible content
- Internal links, related articles, indexes, and orphan candidates
- Image existence and alt text
- Sitemap and public URL
- Duplicate title/canonical, legacy URL, and different slug
- Successful `check` after `candy-site-state write`

Use `generated/CANDY_SEO_STATUS.md` for current findings, each area/hotel/blog specification for category-specific generation exceptions, and `CANDY_FIX_BACKLOG.md` for issues requiring fixes or owner decisions.
