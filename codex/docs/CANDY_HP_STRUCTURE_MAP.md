# CANDY HP Structure Map

## 1. Responsibility

This is the canonical document for stable CANDY site-page structure and relationships. It does not store current page counts, every page name, Git state, or HTTP results. Use `generated/CANDY_SITE_PAGE_LEDGER.md` for current state.

## 2. Overall Structure

```text
top `index.php`
├─ indexes
│  ├─ `area.php`  ── area detail `kagoshima-deliveryhealth-area-<slug>.php`
│  ├─ `hotel.php` ── hotel detail `kagoshima-deliveryhealth-hotel-<slug>.php`
│  ├─ `blog.php`  ── blog detail `kagoshima-deliveryhealth-blog-<slug>.php`
│  └─ index and dynamic pages such as girls, schedule, news, and movie
├─ common navigation and category routes
├─ related-article and related-page routes
└─ `sitemap.xml`

public PHP
└─ `includefile/dataset_base.php`
   ├─ `source/<same-name>.html`
   ├─ `includefile/dataset_<same-name>.php`
   ├─ `includefile/class.hpgcoder2.php`
   └─ `includefile/funcs.php`
```

Public PHP is a thin entry point. Static body content, SEO, and layout are primarily in the matching source HTML; datasets and common PHP apply runtime data and placeholders. Verify exceptions in actual files and the ledger.

## 3. Page Types

| Type | Primary entry point | Responsibility | Canonical content or generation source |
|---|---|---|---|
| top | `index.php` / `source/index.html` | Site entry point and routes to primary categories, shops, and articles | Actual files and `CANDY_OTHER_PAGES_MANAGEMENT.md` |
| area index | `area.php` / `source/area.html` | Routes from supported regions to area details | Actual files and `CANDY_AREA_PAGE_GENERATION_SPEC.md` |
| area detail | `kagoshima-deliveryhealth-area-<slug>.php` | Region-specific shops, region information, hotels, and nearby information | `Text_area_data` and the area specification/runbook |
| hotel index | `hotel.php` / `source/hotel.html` | Routes from available hotels to hotel details | Actual files and the hotel specification |
| hotel detail | `kagoshima-deliveryhealth-hotel-<slug>.php` | Hotel-specific shops, FAQ, basic information, fees, transportation, and nearby information | `Text_hotel_data` and the hotel specification/runbook |
| blog index | `blog.php` / `source/blog.html` | Routes from staff articles to blog details | Actual files and the blog specification |
| blog detail | `kagoshima-deliveryhealth-blog-<slug>.php` | Topic-specific articles, introductions, comments, FAQ, and summary | `Text_blog_data` and the blog specification |
| girls | `girls*.php`, `schedule.php`, and related files | Database- and session-backed roster, detail, and schedule functions | Actual files and `CANDY_OTHER_PAGES_MANAGEMENT.md` |
| system | `confirm.php`, `mypage.php`, and related files | Dynamic input, confirmation, member, and related processing | Actual files and `CANDY_OTHER_PAGES_MANAGEMENT.md` |
| special | `create.php`, `makeSitemap.php`, and related files | Special generation and operational entry points | Explicit approval and actual files. Do not use for normal page production |
| template | `source/template_*.html` | Skeleton for a new detail page | Category specification. Do not count as a public page |

## 4. Indexes, Common Routes, and Sitemap

- The area, hotel, and blog indexes are the normal routes to their detail pages.
- Blog and hotel may also have category sections on top, so confirm the category specification's change unit.
- Synchronize both the visible breadcrumb and `BreadcrumbList`.
- Related articles use the reserved slots or actual links defined by the category specification. Verify URL, display name, and existence together.
- `sitemap.xml` registers search-facing URLs. It does not replace an index link; check both when adding a detail page or changing a URL.
- A common-navigation change requires desktop/mobile impact checks on top, each index, representative details, and dynamic pages.

## 5. Coupled Validation Targets

| Change | Validate together |
|---|---|
| Detail-page body or SEO | Public PHP, source, dataset, dataset_base, source Text, required images, category index, and sitemap |
| Area detail | Area index, related shops, repeated hotels/spots, and area queue |
| Hotel detail | Hotel index, top hotel section, FAQ, fees, transportation, and nearby information |
| Blog detail | Blog index, top blog section, table of contents, scenes, FAQPage, and ItemList |
| Index | Index links, matching details, structured data, top routes, and sitemap |
| Common PHP | dataset_base registrations, representative static and dynamic pages, and database/session/GET/Cookie dependencies |
| Common CSS/JavaScript | All loading sources, desktop/mobile, DOM IDs/classes, external communication, Cookie, and localStorage dependencies |
| Image | Source/CSS references, alt/OGP/JSON-LD, desktop/mobile, and separation of accepted and public assets |
| URL, canonical, or robots | Indexes, internal links, OGP, JSON-LD, sitemap, and legacy URLs/redirects |

## 6. Page-Specific Exceptions

Do not append each existing page that differs from the common structure to this document. Store current structural differences in `generated/CANDY_SITE_PAGE_LEDGER.md` and `generated/CANDY_SEO_STATUS.md`, permanent generation exceptions in the category specification, and issues requiring an owner decision in `CANDY_FIX_BACKLOG.md`.

## 7. Current-State Sources

| Question | Source |
|---|---|
| All existing pages and structural files | `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Unbuilt pages, eligible production targets, and blockers | `generated/CANDY_UPCOMING_PAGES.md` |
| Current PHP, CSS, JavaScript, and image state | `generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Per-page SEO state | `generated/CANDY_SEO_STATUS.md` |

Update generated documents with `codex\scripts\candy-site-state.cmd write` and use `check` to verify agreement with actual files.
