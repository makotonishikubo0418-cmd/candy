# CANDY Other Pages Management

## 1. Purpose

Centralize responsibility, internal structure, coupled-change scope, validation, and STOP conditions for pages outside area, hotel, and blog.

This is not a fixed current-state ledger. Recheck target actual files and
counts for each task.

## 2. Scope

This document covers public entry points, dynamic pages, management/generation entry points, and `sitemap.xml` outside area, hotel, and blog. Use `generated/CANDY_SITE_PAGE_LEDGER.md` for the complete population and current structural state.

Primary classes:

- top: `index.php`
- girls: `girls.php`, `girls_list.php`, `schedule.php`, and related files
- system/other: `movie.php`, `movie_iframe.php`, `mypage.php`, `news.php`, `system.php`, and related files
- member: `member_login.php`, `member_register.php`, `member_mypage.php`, `member_password_reset.php`, `member_logout.php`, `member/api.php`, member cron entry points, and their source-attached technical references under `HP/docs/`
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
- For an entry point without the normal source/dataset pair, inspect its generated special classification and issue field. `INTENTIONAL` with `issues=NONE` is a confirmed design and MUST NOT be reported as a problem.
- The retired `create.php` generator is not a supported route and MUST NOT be restored as a second page-generation path.

The intentional special-structure set is `girls.php`, `movie_iframe.php`,
`member_logout.php`, `member_password_reset.php`, and `privacy.php`. Their roles
and required behavior are defined in the page table and Sections 6.2, 6.5, and
6.9. They are not deletion, missing-file, or unresolved-structure candidates
while the generated ledger reports `special classification=INTENTIONAL` and
`issues=NONE`. A new `UNREVIEWED` special entry or any non-`NONE` issue is the
only special-structure state that belongs in a problem report.

## 4. Page Management Table

| Page | Responsibility | Internal structure and input | Coupled checks | Change gate or special caution |
|---|---|---|---|---|
| `index.php` | Top and entry point to all primary routes and categories | `source/index.html` + `dataset_index.php`; generates girls, schedules, banners, movies, and shops from the database | Area/blog/hotel sections follow the canonical synchronization contract in `CANDY_PAGE_GENERATION_GOVERNANCE.md`; also check common navigation, images, JSON-LD, and `sitemap.xml` | Normal runtime route exists. Production publication or recovery of this protected file uses the manual index-only deployment route |
| `girls_list.php` | Girl index | `source/girls_list.html` + `dataset_girls_list.php`; girls, images, schedules, order, and Cookie favorites | `girls.php?no=...`, images, schedules, and common navigation | Normal route exists |
| `girls.php` | Girl profile | `source/girls.html` + `dataset_girls.php`; GET girl number, girl, images, movie, schedule, and Cookie favorites | Return routes to indexes/schedules/movies, canonical and structured data, images/movies | Missing or empty `no` returns `301` to `girls_list.php`; malformed or unresolved `no` returns `404`; a resolved active woman keeps the normal indexable profile response |
| `schedule.php` | Daily and weekly schedules | `source/schedule.html` + `dataset_schedule.php`; girls, images, schedule, date switching, and Cookie favorites | Girl detail, date tabs, zero-result display, and common navigation | Normal route exists |
| `news.php` | News index | `source/news.html` + `dataset_news.php`; displays `newstopics` | Date, images, zero-result display, and common navigation | Normal route exists |
| `system.php` | Fees, system, and terms | `source/system.html` + `dataset_system.php`; includes hotel coupon display and an external payment form | Fees, terms, external endpoint, hidden values, and common navigation | Normal route exists. External submission and authentication values are gated |
| `movie.php` | Shop and girl movie index | `source/movie.html` + `dataset_movie.php`; shop/girl movies, device-specific display, and iframe links | `movie_iframe.php`, movie files, thumbnails, and zero-result display | Normal route exists |
| `movie_iframe.php` | Noindex movie-playback helper | `source/movie_iframe.html` + `dataset_movie_iframe.php`; accepts exactly one positive numeric `mids` or `midg` and selects an active shop/girl movie | Caller `movie.php`, movie formats, invalid GET, unresolved or non-playable records, and direct access | Display only when an active mp4, ogv, or webm record exists; otherwise return `404`; keep every response `noindex,nofollow`; exclude canonical, H1, OGP, JSON-LD, breadcrumb, sitemap, and orphan requirements; no direct common-navigation route |
| `mypage.php` | Public compatibility entry | Renders the existing Cookie-favorite mypage while `MEMBER_SITE_INTEGRATION_ENABLED` is `false`; only the enabled branch loads the member bootstrap and redirects to `member_mypage.php` or `member_login.php` | Integration flag, legacy dataset rendering, member bootstrap, session authentication, redirect result, and incoming links | Development-only member pages MUST NOT replace or receive a route from this public entry while integration is disabled. Live database, session, and production behavior remain `UNVERIFIED` |
| `member_login.php`, `member_register.php`, `member_mypage.php`, `privacy.php` | Development-only member authentication, account, and legal UI | Member source/dataset pairs or standalone member rendering plus `includefile/member/`, `js/member.js`, and member CSS | Development isolation, Session, Cookie, database, SMS, email, CTI, legal text, and zero/failure paths | Keep `noindex,nofollow`, exclude from sitemap and public navigation, and do not treat these as formal public pages while integration is disabled. Authentication and database operations require their routed scope and permission |
| `member_password_reset.php`, `member_logout.php`, `customers/index.php` | Development-only password-reset, logout, and compatibility entry points | Standalone member bootstrap and authentication or redirect logic | Development isolation, token/session invalidation, redirect, SMS, and failure behavior | Keep the common member `X-Robots-Tag: noindex, nofollow` response. Generated structure may classify root entries as `SPECIAL`; verify the implementation directly |
| `member/api.php` | JSON member API entry | `includefile/member/bootstrap.php` and `MemberApi.php` dispatch by `fno` | Authentication, validation, database writes, CTI reads, SMS, email, and error serialization | Do not invoke write-capable API operations during a read-only investigation |
| `member/cron_notify_info.php`, `member/cron_notify_favorite_schedule.php` | Member notification batch entry points | Member configuration, mail, notification, favorite, schedule, and database classes | Scheduler, duplicate prevention, mail mode, database state, logs, and deployment environment | Schedule and live execution state remain `UNVERIFIED` until checked in the authorized environment |
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

The common `求人情報` navigation link in `HP/source/*.html` MUST point to
`https://kyusyu-okinawa.qzin.jp/candy98/?v=official`. Its title MUST identify
Vanilla, and generation templates MUST retain the same target so future pages
do not reintroduce the legacy route.

This common-navigation contract does not replace or remove independently
identified related-site banners or portal-specific FAQ links on the top page.
The legacy external `new-cast.com` redirect is a compatibility route outside
the Candy HP repository; verify its live redirect separately and do not add
either external job URL to Candy canonical, JSON-LD, or `sitemap.xml`.

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

Runtime diagnostics MUST NOT persist the complete `$_COOKIE` array, session or
authentication values, or equivalent browser secrets. A target-limited
diagnostic may record only the minimum approved non-secret fields and must be
removed when the investigation ends.

STOP rather than including database writes, authentication, payments, or external-submission changes in a normal page fix.

For `girls.php`, apply the following input boundary before favorites, media, schedule, or profile rendering:

- A missing or empty `no` returns `301` to the same-host `girls_list.php` route.
- A non-scalar or active-woman-unresolved `no` returns HTTP `404` and the existing noindex 404 body. It MUST NOT fall back to another woman.
- A resolved active woman continues through the existing profile render and woman-specific canonical path.

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

The retired `create.php` generator MUST NOT be recreated. For a new page outside a dedicated category tool, prepare the impact table and stage allowlist first.

### 6.4 Top Change

The area, blog, and hotel sections are public-route integrations, not
independently maintained summaries. Apply the category-index/top-page
synchronization contract in Section 10.1 of
`CANDY_PAGE_GENERATION_GOVERNANCE.md`.

Limit changes to the target sections in `source/index.html` and
`dataset_index.php`. Do not change production `index.php`, redirects, age
verification, or the root URL without prior approval.

### 6.5 Noindex Movie-Playback Helper

`movie_iframe.php` is an embedded playback helper for `movie.php`, not an independent search-entry page.

- Keep the HTML `noindex,nofollow` directive and return `X-Robots-Tag: noindex, nofollow` on every response, including errors.
- Accept exactly one selector: positive numeric `mids` for a shop movie or positive numeric `midg` for a woman movie.
- Render the player only when the selected active record contains at least one non-empty mp4, ogv, or webm filename. A poster image alone is not a playable movie.
- Return HTTP `404` with the existing noindex 404 body when the selector is missing, empty, malformed, non-scalar, duplicated through both selector types, unresolved, or has no playable movie record.
- Do not add it to `sitemap.xml`.
- Canonical, H1, OGP, JSON-LD, BreadcrumbList, and orphan-page requirements are `NOT_APPLICABLE`.
- Validate playback from `movie.php`, both valid selector types, every invalid-selector class, unresolved and non-playable records, movie format behavior, response robots, and direct-access safety.
- A future change from noindex to index requires a separate explicit decision and full SEO impact review.

### 6.6 Retired Authenticated Page-Generation Feature

The former `create.php` page-generation feature is retired.

- `create.php`, `source/create.html`, `includefile/dataset_create.php`, and its create-only `includefile/dataset_test.php` scaffold MUST remain absent.
- The `create.html` and `test.html` cases and link transformations MUST remain absent from `includefile/dataset_base.php`.
- Do not retain a robots exclusion for the absent URL and do not add it to `sitemap.xml` or normal public navigation.
- Normal page creation uses only the applicable Codex-managed category workflow or an explicitly reviewed file bundle.
- Reintroducing a web generator requires a new explicit decision and a fresh authentication, filesystem-write, shared-PHP, rollback, and production-security review.

### 6.7 Sitemap Change

Update `sitemap.xml` only through the applicable canonical category workflow. Diff against the current sitemap, classify each URL as add, preserve, or delete, and verify HTTP, canonical, and index eligibility.

### 6.8 Member-System Source-Attached Technical References

The existing member technical set remains under `HP/docs/` as non-management source-attached reference material. This canonical document links only to [`MEMBER_ARCHITECTURE.md`](../../HP/docs/MEMBER_ARCHITECTURE.md), which is the technical index and links to the six Phase children. The set is outside the formal management-document tree, is not a case record or canonical specification, and does not prove live environment state.

The technical files describe intended implementation contracts and provide navigation to related code and SQL. They MUST NOT be used alone to establish the live database schema, executed migrations, credentials, scheduler, SMS or mail mode, enabled integration state, deployment state, or production behavior. For each task, compare the applicable technical reference with the exact implementation and use the database or production route when live evidence is required.

Do not create another phase document solely because work is described as a new phase. Update an existing reference only when it owns that implementation responsibility; otherwise register the case and update the canonical specification or actual implementation selected by `WORK_ROUTING.md`.

### 6.9 Development-Only Member Isolation

While `MEMBER_SITE_INTEGRATION_ENABLED` is `false`, the member authentication, account, password-reset, logout, legal, `customers/` compatibility, member API, and member cron entries are development-only routes.

- `mypage.php` MUST render the existing public Cookie-favorite page and MUST NOT redirect a visitor to a development-only member entry.
- Every development-only member response, including redirects, MUST use the common `X-Robots-Tag: noindex, nofollow` header. Source-backed and layout-rendered HTML MUST also contain `noindex,nofollow` robots metadata.
- Development-only routes MUST remain absent from `sitemap.xml` and normal public source navigation. Links inside the isolated development feature MAY remain for feature testing, but they do not authorize public integration.
- A legal route that is not implemented MUST NOT be linked. Specifically, keep `terms.php` unlinked while the file is absent; do not create a placeholder solely to satisfy a link.
- Enabling the integration, exposing a public navigation route, or changing noindex to index requires a separate explicit decision, runtime/database verification, sitemap and internal-link review, and the applicable Git and production routes.

## 7. Validation

Run only checks required for the target and do not duplicate them.

| Type | Required validation |
|---|---|
| Every change | Target-limited changed-file and reference review |
| PHP | Lint changed PHP, include target, undefined variables, zero-result and invalid-input behavior |
| Source | Title, H1, canonical, robots, internal links, images, and desktop/mobile |
| Dataset | Matching case, placeholder count, database zero results, ordering, escaping, Cookie/GET |
| Member system | Applicable source-attached `HP/docs/` technical reference versus actual API dispatch, member classes, source/dataset, SQL, integration flag, and authorized live database or environment evidence; keep unverified state explicit |
| Development-only member isolation | Integration flag remains false; public `mypage.php` retains the legacy render fallback; common response header and source/layout robots are `noindex,nofollow`; public source links and sitemap entries to development routes are zero; unavailable legal routes are unlinked |
| Index/detail | Index-to-detail and detail-to-index routes, nonexistent IDs, and missing images/movies |
| External submission | Action, submitted fields, no exposed authentication values, and failure display; submission tests require separate approval |
| Sitemap | Valid XML, no duplicate URLs, target HTTP, canonical, and no unintended management URL |
| Production | After Actions succeeds, validate target/related URLs, assets, console, and HTTP |

## 8. Change Gates

Prior approval is required for:

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
- `sitemap.xml`

## 9. STOP Conditions

- Actual files cannot establish the target page's responsibility, URL, or publication requirement.
- Shared PHP, authentication, database, payment, external submission, or production `index.php` change lacks approval.
- A common-navigation change cannot establish the complete source population and diff.
- Sitemap deletion, URL retirement, or redirect is required without approval.
- Existing dirty changes overlap and cannot be separated safely.

## 10. Procedure

1. Inspect target PHP, source, dataset, `dataset_base.php` case, incoming references, and sitemap.
2. Determine responsibility and change unit from the page table.
3. Change the target and execute the validation table.
4. Synchronize sitemap dates and generated documents with
   `candy-site-state preview-sitemap-lastmod`,
   `sync-sitemap-lastmod`, `write`, and `check`.
5. When publication is included, continue through the applicable Git and
   production routes selected from `codex/WORK_ROUTING.md` Section 5.2.

## 11. User Report

In addition to the common response structure in root `AGENTS.md`, report these
target-page facts:

Report only target facts that affect the user's decision or require action.
Do not place an intentional special structure with `issues=NONE` in a problem
list. If it must be mentioned for scope accounting, label it separately as
confirmed normal and no action required.

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
