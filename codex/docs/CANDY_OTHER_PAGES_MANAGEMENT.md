# CANDY Other Pages Management

## 1. Purpose

Centralize responsibility, internal structure, coupled-change scope, validation, and STOP conditions for pages outside area, hotel, and blog.

This is not a fixed current-state ledger. Recheck target actual files, counts, and Git state at task start.

## 2. Scope

This document covers public entry points, dynamic pages, management/generation entry points, and `sitemap.xml` outside area, hotel, and blog. Use `generated/CANDY_SITE_PAGE_LEDGER.md` for the complete population and current structural state.

Primary classes:

- top: `index.php`
- girls: `girls.php`, `girls_list.php`, `schedule.php`, and related files
- system/other: `movie.php`, `movie_iframe.php`, `mypage.php`, `news.php`, `system.php`, and related files
- special: `create.php`, `makeSitemap.php`, and scaffolds requiring purpose confirmation
- public generated output: `sitemap.xml`

Excluded:

- `area.php` and area details
- `hotel.php` and hotel details
- `blog.php` and blog details
- Full CSS, JavaScript, image, and font inventories
- Contents under `log/` and `movie/`

When an excluded target is referenced by a changed page, include it in the impact review.

## 3. Base Internal Structure

A normal public entry point emits in this order:

```text
root PHP
  -> includefile/dataset_base.php
      -> external session, settings, and database connection
      -> includefile/class.hpgcoder2.php
      -> includefile/funcs.php
      -> same-name source HTML existence check
      -> same-name includefile/dataset PHP
      -> placeholder transformation
      -> HTML output
```

Important:

- Root PHP mostly loads `dataset_base.php`; visible content belongs in source and dataset.
- `dataset_base.php` is common to all pages and can cause a site-wide failure even for an intended one-page change.
- For an entry point without the normal source/dataset pair, inspect `SPECIAL/PARTIAL` in the ledger and do not infer intent.
- `create.php` and `makeSitemap.php` do not use the normal route.

## 4. Page Management Table

| Page | Responsibility | Internal structure and input | Coupled checks | Change gate or special caution |
|---|---|---|---|---|
| `index.php` | Top and entry point to all primary routes and categories | `source/index.html` + `dataset_index.php`; generates girls, schedules, banners, movies, and shops from the database | Area/blog/hotel sections, common navigation, images, JSON-LD, and `sitemap.xml` | Normal route exists. Production deployment of `index.php` requires prior approval |
| `girls_list.php` | Girl index | `source/girls_list.html` + `dataset_girls_list.php`; girls, images, schedules, order, and Cookie favorites | `girls.php?no=...`, images, schedules, and common navigation | Normal route exists |
| `girls.php` | Girl profile | `source/girls.html` + `dataset_girls.php`; GET girl number, girl, images, movie, schedule, and Cookie favorites | Return routes to indexes/schedules/movies, canonical and structured data, images/movies | Normal route exists. Validate GET and zero-result behavior |
| `schedule.php` | Daily and weekly schedules | `source/schedule.html` + `dataset_schedule.php`; girls, images, schedule, date switching, and Cookie favorites | Girl detail, date tabs, zero-result display, and common navigation | Normal route exists |
| `news.php` | News index | `source/news.html` + `dataset_news.php`; displays `newstopics` | Date, images, zero-result display, and common navigation | Normal route exists |
| `system.php` | Fees, system, and terms | `source/system.html` + `dataset_system.php`; includes hotel coupon display and an external payment form | Fees, terms, external endpoint, hidden values, and common navigation | Normal route exists. External submission and authentication values are gated |
| `movie.php` | Shop and girl movie index | `source/movie.html` + `dataset_movie.php`; shop/girl movies, device-specific display, and iframe links | `movie_iframe.php`, movie files, thumbnails, and zero-result display | Normal route exists |
| `movie_iframe.php` | Noindex movie-playback helper | `source/movie_iframe.html` + `dataset_movie_iframe.php`; selects a shop/girl movie from GET | Caller `movie.php`, movie formats, invalid GET, and direct access | Keep `noindex,nofollow`; exclude canonical, H1, OGP, JSON-LD, breadcrumb, sitemap, and orphan requirements; no direct common-navigation route |
| `mypage.php` | Favorite-girl review | `source/mypage.html` + `dataset_mypage.php`; Cookie, girls, images, schedules, and my-page information | Favorite add/remove, absent/expired Cookie, and girl detail | Normal route exists. Primarily Cookie-based, not member-ID/password based |
| `main.php` | Candidate post-age-verification main according to a dataset_base comment | A branch uses `dataset_index.php`, but `source/main.html` is absent | Relationship to `index.php`, external routes, and `sitemap.xml` | STOP on source existence in the repository structure |
| `page.php` | Legacy generic-page scaffold candidate | `dataset_page.php` exists but `source/page.html` does not | External routes, `sitemap.xml`, and purpose confirmation | STOP on source existence in the repository structure |
| `test.php` | Test scaffold candidate | `dataset_test.php` exists but `source/test.html` does not | Publication requirement, noindex, and deletion eligibility | STOP on source existence in the repository structure |
| `create.php` | Noindex authenticated page-generation feature | Standalone; accepts a page name by POST, creates root PHP, dataset, and source, and appends a case and transformation to `dataset_base.php` | Authentication, three generated files, shared-PHP diff, rollback, and noindex | Exclude from public-page SEO requirements; prohibited for normal production. Do not copy authentication values into documents or logs |
| `makeSitemap.php` | Recursive site-link collection and XML response | Crawls links from the current host, dumps in test mode, and returns XML over HTTP normally | Seed, 404 behavior, external exclusion, infinite traversal, SSL, and output diff | Does not save `sitemap.xml`. Prohibited as the normal update method |
| `sitemap.xml` | Public URL list for search engines | Static XML; use the generated ledger for current URLs | New/changed/removed URLs, canonical, HTTP state, and index eligibility | Confirm intent before addition, deletion, or redirect |

## 5. Common-Navigation Impact

Many `source/*.html` files contain their own common navigation. Recalculate the current reference population from the generated inventory and actual source files.

- `girls_list.php`
- `schedule.php`
- `system.php`
- `movie.php`
- `mypage.php`
- `news.php`

Do not update every source for a body-only change. Only when changing a URL, navigation label, common header, or common footer, treat every source as the population and count references, changes, exclusions, and failures.

## 6. Change Units

### 6.1 Existing Static Content

Targets:

- `source/<target>.html`
- `includefile/dataset_<target>.php` only when required
- Referenced targets for changed links, images, and structured data

Do not change root PHP or `dataset_base.php` when routing does not change.

### 6.2 Dynamic Display

Targets:

- Placeholder region in `source/<target>.html`
- Acquisition, ordering, and zero-result handling in `includefile/dataset_<target>.php`
- GET, Cookie, date, device, external form, and related input conditions
- Paired detail/index/iframe pages

STOP rather than including database writes, authentication, payments, or external-submission changes in a normal page fix.

### 6.3 New URL or URL Change

Validate together:

1. Root PHP
2. Same-name `source` HTML
3. Same-name `includefile/dataset` PHP
4. Case in `includefile/dataset_base.php`
5. `.html` to `.php` transformation in HTML
6. Entry page and common navigation
7. Canonical and structured data
8. `sitemap.xml`
9. Production HTTP and legacy URL

Do not generate with `create.php`. For a new page outside a dedicated category tool, prepare the impact table and stage allowlist first.

### 6.4 Top Change

Limit changes to the target sections in `source/index.html` and `dataset_index.php`. Do not change production `index.php`, redirects, age verification, or the root URL without prior approval.

### 6.5 Noindex Movie-Playback Helper

`movie_iframe.php` is an embedded playback helper for `movie.php`, not an independent search-entry page.

- Keep its existing `noindex,nofollow` directive.
- Do not add it to `sitemap.xml`.
- Canonical, H1, OGP, JSON-LD, BreadcrumbList, and orphan-page requirements are `NOT_APPLICABLE`.
- Validate playback from `movie.php`, valid and invalid GET handling, movie format behavior, and direct-access safety.
- A future change from noindex to index requires a separate explicit decision and full SEO impact review.

### 6.6 Noindex Authenticated Page-Generation Feature

`create.php` is an authenticated operational feature, not a public search-entry page.

- Keep an `X-Robots-Tag: noindex, nofollow` response header on both authenticated and unauthenticated responses.
- Keep it excluded from `sitemap.xml` and normal public navigation.
- Public-page title, description, canonical, H1, OGP, JSON-LD, breadcrumb, internal-link, image-alt, sitemap, and orphan-page requirements are `NOT_APPLICABLE`.
- Keep authentication and operational safety in scope; SEO exclusion MUST NOT be treated as exclusion from security or maintenance controls.
- Do not use it for normal production. A future return to normal use requires a separate explicit decision and review of its generation behavior.

### 6.7 Sitemap Change

Do not use `makeSitemap.php` output directly as `sitemap.xml`. Diff against the current sitemap, classify each URL as add, preserve, or delete, and verify HTTP, canonical, and index eligibility.

## 7. Validation

Run only checks required for the target and do not duplicate them.

| Type | Required validation |
|---|---|
| Every change | Target-limited diff, `git diff --check`, and no deletion, rename, or unauthorized file |
| PHP | Lint changed PHP, include target, undefined variables, zero-result and invalid-input behavior |
| Source | Title, H1, canonical, robots, internal links, images, and desktop/mobile |
| Dataset | Matching case, placeholder count, database zero results, ordering, escaping, Cookie/GET |
| Index/detail | Index-to-detail and detail-to-index routes, nonexistent IDs, and missing images/movies |
| External submission | Action, submitted fields, no exposed authentication values, and failure display; submission tests require separate approval |
| Sitemap | Valid XML, no duplicate URLs, target HTTP, canonical, and no unintended management URL |
| Production | After Actions succeeds, validate target/related URLs, assets, console, and HTTP |

## 8. Change Gates

Prior approval is required for:

- `create.php`
- Production deployment of `index.php`
- Authentication, database writes, payments, external submission, and noindex/index
- `.htaccess`, `log/`, and `.well-known/`
- File deletion, movement, or rename

Show the affected scope before changing:

- `includefile/dataset_base.php`
- `includefile/class.hpgcoder2.php`
- `includefile/funcs.php`
- Each `includefile/dataset_*.php`
- `source/system.html`
- `css/default.css` and `js/common.js`
- `makeSitemap.php` and `sitemap.xml`

## 9. STOP Conditions

- Actual files cannot establish the target page's responsibility, URL, or publication requirement.
- `main.php`, `page.php`, or `test.php` would need to be treated as a normal page while source is absent.
- Shared PHP, authentication, database, payment, external submission, or production `index.php` change lacks approval.
- A common-navigation change cannot establish the complete source population and diff.
- Sitemap deletion, URL retirement, or redirect is required without approval.
- Existing dirty changes overlap and cannot be separated safely.

## 10. Procedure

1. Read root `AGENTS.md` and `HP/AGENTS.md`.
2. Route here from `CANDY_MASTER_DOC_INDEX.md`.
3. Verify Git branch, remote, and status.
4. Inspect target PHP, source, dataset, `dataset_base.php` case, incoming references, and sitemap.
5. Determine responsibility and change unit from the page table.
6. Change only the target and execute the validation table.
7. Synchronize generated documents with `candy-site-state write` and `check`.
8. Only when upload is explicitly authorized, run target-limited Commit, Push to main, Actions, and production HTTP validation.

## 11. User Report

```text
対象ページ:
役割:
変更ファイル:
同時確認先:
検証結果:
Commit:
Push:
Actions:
本番URL:
未確認・未実施:
```

Do not report unexecuted Commit, Push, Actions, or production checks as completed.
