# CANDY Full-Population Verification Plan

- Updated: 2026-07-25
- Applies to: `HP`, generation source data, test, and production
- Position: Canonical method for full-population evidence classification

This plan owns population enumeration, evidence fields, result
classifications, and revalidation. It does not define routing, authority, Git,
deployment limits, workflow behavior, or current defect counts.

## 1. Definition of Full-Population Verification

Full-population verification means enumerating every target, separating normal cases, exceptions, dynamic generation, and incomplete data, and recording actual validation results. There is no count limit; more than 100 files or 1,000 URLs remain fully in scope.

Do not report full verification, 100%, or completion when:

- Target count was not measured.
- Only some PHP, HTML, CSS, or related files were inspected.
- Only static existence was checked and generated HTML was not reviewed.
- Templates and production output were not distinguished.
- 403, 429, timeout, or TLS errors were treated as 404.
- A dynamic URL was classified as broken from machine detection alone.
- Unverified, pending, or manual-review states were hidden.
- Changed targets were not revalidated.

## 2. Verification Scope

Obtain current counts from actual files and servers on each run. Do not use only fixed counts from historical documents.

### 2.1 Public and Generation Code

- Public entry `*.php` directly under `HP`
- `HP/source/*.html`
- `HP/includefile/*.php`
- `HP/css/*.css`
- `HP/js/*.js`
- Inline styles, JSON-LD, canonical, and OGP

### 2.2 Public Assets

- `HP/imgHtml`
- `HP/imgCss`
- `HP/font`
- `HP/movie`
- Other images, movies, fonts, and icons referenced from public pages

### 2.3 Generation Source Data

- `Text_area_data`
- `Text_blog_data`
- `Text_hotel_data`

Separate current public links from preparation links for future use. Classify URLs for ungenerated pages as requiring pre-generation validation, not current broken public links.

### 2.4 Environments

- Local `HP`
- Test `/public_html/group_test/candy/`
- Production `/public_html/group/candy/`
- Actual public URLs

Local existence does not prove production verification. FTP existence does not prove HTTP rendering.

### 2.5 Excluded Scope

- Raw `log` contents
- Authentication values, database connection values, and payment hidden values
- `.git` and GitHub management data
- Example URLs in management Markdown

Record excluded scope and reasons. Do not copy secrets or raw logs into reports.

## 3. Required Verification Order

### 3.1 Full Enumeration

1. Enumerate all local targets with relative paths.
2. Enumerate all server targets with relative paths.
3. Enumerate all public entry PHP.
4. Extract references from each file.
5. Record extracted total, unique total, and excluded count.

Extract `href`, `src`, `srcset`, `action`, `poster`, CSS and inline `url()`, canonical, OGP, JSON-LD, JavaScript-generated URLs, and URLs in Text data.

### 3.2 Public Entry PHP

Check every public entry PHP over HTTP:

- 200: Response exists.
- 301, 302, 307, 308: Verify destination and intent.
- 404, 410: Broken-link candidate.
- 500 or higher: Runtime error or transient failure.
- Refused connection, DNS, TLS, timeout: `UNVERIFIED`.

Record the public root `200` separately from the intended `index.php` to root,
HTTP to HTTPS, and non-www to www redirects. Also record that the direct
verification host returns `200` with `X-Robots-Tag: noindex` while the public
canonical response remains indexable.

### 3.3 Generated HTML

Obtain generated HTML from PHP HTTP responses and extract browser-facing internal PHP, images, movies, fonts, CSS, JavaScript, anchors, external URLs, form actions, canonical, OGP, JSON-LD, and placeholders. Static inspection of `source/*.html` is insufficient.

For the internal-path access contract, verify separately that direct requests to `source/` and representative `source/*.html` files return `404`, direct requests to the `includefile/` directory and representative top-level and nested include files return `403`, and `source/style.css` returns `200`. These results do not replace generated-HTML checks through public PHP.

### 3.4 Internal Links

- Separate query and fragment, then normalize relative paths.
- Resolve `./`, `../`, and leading `/`.
- Use production server case sensitivity.
- Report file existence separately from HTTP response.
- Do not confuse same-name HTML with public PHP.
- Verify HTML-to-PHP transformation from `dataset_base.php`.
- Match each anchor to an `id` or `name` at the destination.
- For a development-only route, distinguish links inside its isolated feature from links in formal public source navigation. Confirm that formal public sources do not link to the development route and that an unavailable development destination is not linked at all.

### 3.5 Images, Movies, Fonts, and CSS

- Match HTML references to actual files.
- Resolve CSS `url()` relative to the CSS file.
- Remove query and fragment before file matching.
- Validate every `srcset` candidate.
- Separate desktop/mobile, normal/retina, and background assets.
- Separate active and inactive CSS.
- Record missing references in unused CSS as inactive references.

Do not treat font `?#iefix` or SVG `#id` as part of the filename.

### 3.6 External URLs

Follow redirects and record final responses.

| Result | Classification |
|---|---|
| 200–399 | Reachable; record final URL after redirect |
| 400, 404, 410 | Broken candidate; confirm purpose |
| 401, 403 | Restricted; do not classify as broken |
| 405 | GET may be prohibited; check form-action use |
| 429 | Rate limited; retry later |
| 500 or higher | Remote failure candidate; retry |
| DNS, TLS, refused connection, timeout | `UNVERIFIED`; use another method or manual review |

A form action returning 400 to GET alone is not broken. Do not classify Google Maps 429 responses as broken.

### 3.7 Templates, Dynamic Generation, and Placeholders

Separate:

- Replacement values in `template_*.html`
- Future URLs in Text data
- URLs completed through JavaScript concatenation
- Replacement tokens such as `rep...eot`
- Placeholders such as `aaaaaaaa...`

A template-only value is not a current public failure. A value remaining in generated HTML or a public page is a defect. Do not evaluate literal JavaScript `' + variable + '` as a URL.

### 3.8 Production Deployment Route

When production deployment is inside the verified scope, validate the exact
workflow and deploy script selected by the applicable production route in root
`codex/WORK_ROUTING.md` Section 5.2. This plan requires only that the following evidence
states remain
separate:

- local workflow/script checks
- GitHub workflow syntax
- Actions plan and deployment
- production SHA-256
- HTTP
- browser rendering

The production migration document and actual workflow/scripts own their
constraints. This verification plan must not duplicate them.

## 4. Result Classifications

| Status | Meaning |
|---|---|
| `OK` | Verified normal |
| `EXPECTED_REDIRECT` | Intended redirect |
| `BROKEN_INTERNAL` | Internal destination absent or 404/410 |
| `BROKEN_ASSET` | Image, movie, font, or related asset absent |
| `BROKEN_EXTERNAL` | External URL verified as 404/410 or equivalent |
| `LIVE_PLACEHOLDER` | Unreplaced value remains in public output |
| `TEMPLATE_ONLY` | Replacement value exists only in a template |
| `FUTURE_DATA` | Preparation data for an ungenerated page |
| `RESTRICTED` | Automation cannot verify because of 401/403/405/429 or equivalent |
| `TRANSIENT_ERROR` | Candidate transient failure such as 500 or higher |
| `UNVERIFIED` | DNS, TLS, timeout, or related unverified state |
| `FALSE_POSITIVE` | Machine false positive such as dynamic URL or fragment |

## 5. Required Record Fields

- Verification date and time
- Environment, base URL, and server root
- Git branch and HEAD
- Target-file and extracted-link counts
- Source and destination
- HTTP state or existence result
- Result classification
- Automated or manual verification
- Fix target, future generation target, or excluded scope
- Revalidation result

Do not infer an unverified field.

## 6. Fix Priority

1. Live internal links, images, CSS, and JavaScript
2. Placeholders and unreplaced tokens in public output
3. Live external 404/410
4. Canonical, OGP, and JSON-LD
5. Future links in source data
6. References only in unused CSS or templates

## 7. Post-Fix Revalidation

- Target-page HTTP response
- Every page using the same common template
- Actual image, CSS, and JavaScript retrieval
- In-page anchors
- Canonical, OGP, and JSON-LD
- Desktop/mobile rendering
- JavaScript console
- Production or test deployment state

Do not report a local fix as a production fix.

## 8. Completion Criteria

- Included and excluded scopes are explicit.
- Every target was enumerated.
- Static and generated references were checked.
- Internal, external, asset, anchor, and placeholder findings were classified.
- Machine false positives have documented exclusion evidence.
- Pending decisions are reported as unverified.
- Every fix was revalidated.
- When internal-path access control changed, source HTML and directory requests are `404`, include requests are `403`, `source/style.css` is `200`, and public PHP rendering remains valid.
- When development-only member isolation changed, all member entries emit `noindex,nofollow`, the public legacy mypage fallback remains selected while integration is disabled, public source links and sitemap membership are zero, and unavailable legal destinations are unlinked.
- Unexecuted browser, database, and external-service checks are not reported complete.

When pending decisions remain, report that the full population was scanned but
unverified items remain. Do not report everything normal until the unverified
count is zero.
