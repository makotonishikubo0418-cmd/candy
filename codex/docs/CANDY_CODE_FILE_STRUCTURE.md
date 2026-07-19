# CANDY Code and Asset Structure

## 1. Responsibility

This is the canonical document for stable HP code and public-asset structure, dependencies, and change impact. The full file inventory and current counts are separated into `generated/CANDY_CODE_ASSET_INVENTORY.md`.

## 2. PHP and Source HTML

### 2.1 Primary Route

```text
HP/<page>.php
  → includes `/group/candy/includefile/dataset_base.php` on the server
  → selects `source/<page>.html` from SCRIPT_FILENAME
  → loads the matching `includefile/dataset_<page>.php`
  → applies runtime data through `class.hpgcoder2.php` and `funcs.php`
  → emits HTML
```

The local counterparts are under `HP/includefile/`. Public rendering wrappers use the production `/group/candy/` include path; `/group_test/candy/` is reserved for the separate test environment. Compare the ledger and actual files when adding or changing a page.

### 2.2 Primary Files

| File | Responsibility | Primary dependencies and cautions |
|---|---|---|
| `HP/*.php` | Thin entry point for a public URL | Most only include `dataset_base.php`. Some special entry points have no source file |
| `HP/source/*.html` | Static body, metadata, headings, links, images, JSON-LD, and placeholders | Normally shares a stem with public PHP. A template is not a public page |
| `HP/includefile/dataset_base.php` | Common entry point for source selection, external configuration loading, page-specific dataset routing, HTML link transformation, and final output | A change may affect every page. It requires external session and database settings |
| `HP/includefile/dataset_*.php` | Page-specific or feature-specific data acquisition and replacement | Pairing is not uniform. Check both cases and link transformations |
| `HP/includefile/class.hpgcoder2.php` | Extracts `rep...eot` placeholders and assigns each token to a function | Contains a debug branch using GET `no` and calls to `error_log` |
| `HP/includefile/funcs.php` | Common functions for HTML generation from database results, file or external acquisition, headers, and redirects | May affect external communication, databases, and responses |

`dataset_base.php` requires external session and database settings on the production server. Do not copy those values into management documents. Actual database, session, and external-include targets are unverified by local static inspection.

### 2.3 Placeholders and Runtime Dependencies

- `rep...eot` in source HTML is subject to transformation.
- The switch in `class.hpgcoder2.php` is the implementation for mapping tokens to replacement functions.
- A dataset combines source loading, database results, and common HTML fragments.
- `dataset_base.php` and common classes and functions depend on `$_SERVER`, GET, Cookie, session, databases, and file loading.
- JavaScript also depends on GET, Cookie, localStorage, AJAX, and fetch.
- Management audits MUST NOT read secret values or raw `log/` contents. Record only the existence and location of dependencies.

### 2.4 Special Entry Points

- `create.php` affects authentication and file generation. Do not use it for normal page production. Execution or modification requires separate explicit approval.
- `makeSitemap.php` is a special sitemap-generation entry point. Do not execute it during a normal page change; freeze the affected scope and output, then obtain explicit approval.
- Do not automatically classify a public PHP file without source or dataset as missing. Mark it `SPECIAL` in the ledger and confirm implementation intent.

## 3. CSS

### 3.1 Responsibility and Loading

- `css/default.css` is the center of common layout, utilities, and desktop/mobile display.
- `css/girls.css`, `girls_list.css`, `schedule.css`, `news.css`, `movie.css`, `mypage.css`, `system.css`, and related files supplement feature- and page-type styles.
- `source/style.css` contains article styles referenced from area, hotel, and blog source files.
- Treat `colorbox.css`, `jquery.fs.boxer.css`, and related files as pairs with their JavaScript plugins.
- The `<link>` elements in source HTML are the canonical source for actual load order and load origins. Use the generated inventory for current state.

### 3.2 Responsive Rules, Variables, and Overrides

- The primary breakpoints are `max-width: 768px` and `min-width: 769px`.
- Desktop/mobile-specific classes and responsive rules that share the same DOM coexist. Validate both widths after a change.
- CSS variables are limited to some layout values and do not centrally control the entire theme. Verify the declaration source and fallback in actual files.
- Existing CSS contains many `!important` declarations. Do not suppress conflicts with more overrides; verify existing specificity and load order.
- A common-CSS change requires desktop/mobile impact checks on top, each index, representative area/hotel/blog details, girls, and system pages.

## 4. JavaScript

| Family | Responsibility | Dependencies |
|---|---|---|
| `common.js` / `commonLite.js` | Common UI and responsive DOM operations | `window.matchMedia`, DOM IDs/classes, load, and scroll |
| `amadareWebApp2.6.js` / `mdrwbpp2.4.js` | Image deferral, DOM helpers, and communication/storage/cookie helpers | XMLHttpRequest, DOM, localStorage, and Cookie |
| `js/amadare_webapp2.4.php` | PHP delivery entry point loaded as JavaScript from source | Verify the response after PHP execution and server dependencies in the actual environment |
| `amadareAccess.1.0.js` | Access and telephone measurement | location, referrer, fetch/image submission, and external communication |
| `love2.js` | Favorites and related operations | jQuery AJAX, iframe, and DOM |
| `candyTile.js` | Tile placement and resizing | DOM dimensions, resize, and dynamic elements |
| `jquery.*` plugins | Modals, galleries, and related features | jQuery and matching CSS |

The `<script>` elements in source HTML are the canonical loading source. Before changing common JavaScript, verify the target DOM, GET parameters, Cookie names, storage, communication endpoints, and failure behavior. Changes to external endpoints or submitted fields require a separate task and MUST NOT be made automatically from static management documents.

## 5. Images, Movies, and Fonts

| Area | Responsibility |
|---|---|
| `HP/imgHtml/` | Public images referenced from HTML. Category assets are stored under locations such as `new_202601/area`, `hotel`, and `blog` |
| `HP/imgCss/` and images adjacent to CSS | Assets referenced by CSS, including backgrounds |
| `HP/movie/` | Public movies and related assets. Deletion or replacement requires approval |
| `HP/font/` and related locations | Fonts referenced from CSS |
| `Text_area_data/画像データ/` | Git-managed accepted or candidate source assets before production. Do not treat them as public assets; public HTML uses the copied files under `HP/imgHtml/new_202601/area/` |

For detail-page images, use the category specification's naming and required count, then synchronize source, OGP, JSON-LD, and alt values. The generated inventory identifies missing, unconfirmed-reference, and same-hash candidates, but machine evaluation alone MUST NOT trigger deletion or replacement.

Before deletion or replacement, verify HTML, CSS, dynamic PHP/JavaScript references, database references, desktop/mobile display, OGP/JSON-LD, accepted-asset rights, and recovery methods.

## 6. Current State and Validation

```powershell
codex\scripts\candy-site-state.cmd audit
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

Use `generated/CANDY_CODE_ASSET_INVENTORY.md` for current PHP/source/dataset pairings, dataset_base registrations, CSS/JavaScript load origins, asset counts, missing references, and unreferenced or duplicate candidates.
