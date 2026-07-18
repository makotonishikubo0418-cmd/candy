# CANDY Repository SEO Audit

- Document type: dated audit report
- Status: IMPLEMENTATION_VERIFIED for repository facts; UNVERIFIED for production behavior
- Audit date: 2026-07-18
- Repository root: `C:\Codex\candy`
- Branch: `main`
- Audited commit: `2a1d4df1b246222024f0e05feacfd6b718a8a699`
- Scope: repository-wide technical SEO, duplicate area content, internal links, structured data, canonical URLs, index control, and performance-related static assets
- Excluded operations: file remediation, Commit, Push, deployment, production HTTP checks, Search Console, analytics, and Lighthouse

## 1. Conclusion

The repository requires major SEO remediation. No repository evidence showed a site-wide `noindex`, a global canonical to the wrong domain, or another confirmed Critical condition. Four High findings affect large page groups or route discovery, five Medium findings affect resource delivery, index governance, page semantics, and performance, and three Low findings concern HTML consistency, optional structured-data improvements, and asset maintenance.

| Severity | Count |
|---|---:|
| Critical | 0 |
| High | 4 |
| Medium | 5 |
| Low | 3 |

The first required action is to remove the 27 unresolved area pages from the indexable publication set or complete them with region-specific content and valid JSON-LD. The next actions are to complete `contact.php`, reconcile the category indexes and sitemap with existing files, and remove the external `group_test` rendering dependency from public wrappers.

Production HTTP behavior remains UNVERIFIED because this audit did not access production, Search Console, analytics, or a browser performance laboratory.

## 2. Audited Population and Method

### 2.1 Repository Population

| Population | Count |
|---|---:|
| Repository files | 2,053 |
| Files under `HP/` | 1,402 |
| Direct public PHP files | 115 |
| Source HTML files | 106 |
| Non-template source HTML files | 101 |
| Source templates | 5 |
| Area source pages | 78 |
| PHP data files | 116 |
| CSS files | 15 |
| JavaScript files | 16 |
| Image files | 974 |
| Font files | 49 |
| Static sitemap URLs | 61 |

### 2.2 Checks Performed

- Confirmed the repository root, branch, clean starting state, local HEAD, and `origin/main` equality.
- Enumerated HTML, PHP, templates, data files, JavaScript, CSS, images, fonts, `.htaccess`, sitemap files, and the absence of `robots.txt`.
- Parsed all 101 non-template source HTML files for title, meta description, heading structure, canonical, robots, viewport, language, anchors, image attributes, and JSON-LD.
- Parsed 198 JSON-LD blocks and checked JSON syntax, list positions, `numberOfItems`, and BreadcrumbList final-URL consistency.
- Built a static internal-link graph and resolved relative PHP paths against actual files.
- Compared all 78 area pages in all 3,003 possible pairs.
- Ran `codex\scripts\candy-area.cmd related-check`.
- Ran `codex\scripts\candy-site-state.cmd check` and `audit`.
- Measured repository and referenced asset sizes, missing local targets, image attributes, script loading flags, and approximate DOM element counts.

### 2.3 Area Similarity Method

Exact comparisons covered title, meta description, H1, lead, regional description, hotel entries, nearby-spot entries, and the combined regional core. Pairwise content similarity used whitespace-normalized character 3-gram frequency vectors and cosine similarity.

Shared navigation, footer content, and the four shared shop cards were excluded from the regional-core similarity score because those sections are valid common templates. The analysis therefore distinguishes shared layout from duplicate local content.

## 3. Finding Register

| ID | Severity | Category | Finding | Affected Scope | Code Evidence | Recommended Action |
|---|---|---|---|---|---|---|
| SEO-01 | High | Area duplication and structured data | Twenty-seven area sources retain unresolved `aaaaaaaa...` values. Their title, description, H1, lead, regional body, and venue blocks are identical, and both JSON-LD blocks per page are invalid. | 27 pages; 351 exact duplicate pairs; 54 invalid JSON-LD blocks | `HP/source/kagoshima-deliveryhealth-area-gionnosucho.html:7-18`, `:34`, `:60` and the 26 matching sources listed in section 5 | Keep these URLs out of the indexable publication set until content, images, internal links, and JSON-LD are complete. Use `noindex`, 404, or 410 according to the intended URL state. |
| SEO-02 | High | Canonical and page content | `contact.php` is represented by a placeholder source, uses a nonexistent placeholder canonical, and is already present in the sitemap. | One public page | `HP/source/contact.html:7-18`, `:98-110`; `HP/sitemap.xml:97` | Replace the placeholder page with the approved contact content and set the canonical to `https://www.55810.com/contact.php`. |
| SEO-03 | High | Internal links and sitemap | Manually maintained route inventories do not match actual public files. Static HTML contains 132 references to missing PHP targets, and 15 content pages have no incoming static link. The sitemap contains a missing `snews.php` URL and omits 14 non-placeholder indexable sources. | Three category pages, sitemap, 15 orphaned content pages | `HP/source/area.html:133`, `HP/source/blog.html:133-134`, `HP/source/hotel.html:155-171`, `HP/sitemap.xml:41`, `HP/makeSitemap.php:5-26` | Generate or validate category and sitemap inventories from one approved public-page ledger. Add appropriate incoming links to orphaned content. |
| SEO-04 | High | Rendering and deployment traceability | Ninety-six of 113 direct rendering wrappers include an out-of-repository `group_test` dataset path. Thirteen wrappers have no local source HTML. Repository content therefore cannot prove the final output for most wrappers. | 96 wrappers, approximately 85% of direct render wrappers | `HP/kagoshima-deliveryhealth-area-arata.php:4`, `HP/create.php:70`, `HP/includefile/dataset_base.php:47-50` | Use one repository-controlled production include path and provide one traceable source for each public wrapper. Verify production output after migration. |
| SEO-05 | Medium | Missing resources | Referenced images, JavaScript, and video files are absent from the repository. Two otherwise non-placeholder area pages have broken image references. `fav.js` is referenced by six pages but is absent. | Area pages, dynamic pages, and media features | `HP/source/kagoshima-deliveryhealth-area-inusakocho.html:178`, `HP/source/kagoshima-deliveryhealth-area-kenohikarigaoka.html:179`, `HP/source/girls.html:760`, `HP/source/index.html:497` | Correct filenames and paths, restore approved assets, and make asset-existence validation a publication gate. |
| SEO-06 | Medium | Index control and URL normalization | `robots.txt` is absent. HTTPS, host, and `index.php` normalization rules are commented out. The custom 404 rule is also disabled. Operational or helper pages do not have a consistent explicit `noindex` policy. | Site-wide URL governance and helper pages | `HP/.htaccess:2`, `:9-19`; `HP/create.php:146-337`; `HP/source/movie_iframe.html:5` | Define the canonical host and path policy, activate the approved 301 rules, implement a local 404 response, add `robots.txt`, and explicitly exclude operational/helper pages where appropriate. |
| SEO-07 | Medium | Headings and head metadata | Seven source-rendered pages have no H1. `movie_iframe.html` also lacks title, meta description, canonical, and viewport. | `girls`, `girls_list`, `movie`, `movie_iframe`, `mypage`, `schedule`, and `system` | `HP/source/girls.html`, `HP/source/movie_iframe.html:1-15`, `HP/source/system.html` | Add one meaningful H1 to indexable pages. Prefer `noindex` over artificial SEO markup for iframe or helper output that should not rank independently. |
| SEO-08 | Medium | Images and layout performance | Of 526 `img` tags, 256 lack either width or height. Forty-two lack an alt attribute and five have an empty alt. Area content accounts for 156 dimension omissions. | Multiple page categories | `HP/source/kagoshima-deliveryhealth-area-arata.html:178`, `HP/source/girls.html:312` | Add intrinsic dimensions in templates, preserve intentional decorative empty alt values, and provide descriptive alt text for content images. |
| SEO-09 | Medium | Rendering performance | Eight pages load multiple scripts without `defer` or `async`. The common PHP renderer writes two debug log entries on every request. Repository `.htaccess` contains no cache or compression policy. | Top page, dynamic pages, and common PHP rendering | `HP/source/index.html:21-23`, `HP/source/girls.html:21-25`, `HP/includefile/dataset_base.php:1167-1169`, `HP/.htaccess` | Defer scripts after dependency testing, remove production debug logging, and verify server-level caching and compression before adding duplicate rules. |
| SEO-10 | Low | HTML consistency | Basic parsing found duplicate IDs, an extra closing `div`, unescaped ampersands, and heading-level skips. | 11 source files; 16 parser events including the unused `source/create.html` event | `HP/source/girls.html:693-719`, `HP/source/kagoshima-deliveryhealth-area-arata.html:346`, `HP/source/index.html:1572`, `HP/source/contact.html:284` | Add an HTML validation check and correct unique IDs, tag balance, escaping, and heading order. |
| SEO-11 | Low | Structured-data completeness | `hanaomachi` declares an incomplete FAQPage microdata wrapper. The six editorial blog-detail pages use BreadcrumbList, FAQPage, and ItemList but no Article type. | One area page and six blog pages | `HP/source/kagoshima-deliveryhealth-area-hanaomachi.html:380`; six `HP/source/kagoshima-deliveryhealth-blog-*.html` sources | Remove or complete the isolated microdata declaration. Evaluate Article JSON-LD for the six editorial pages; its absence alone is not a validation failure. |
| SEO-12 | Low | Asset maintenance | The repository audit reported 710 unreferenced candidates and 165 duplicate-hash groups. Two large JavaScript files, approximately 2.0 MB and 1.2 MB, have no static source reference. | Asset repository | `HP/js/candyKissDijest.js`, `HP/js/movieSum.js`; `candy-site-state audit` | Confirm dynamic and production references before any separate cleanup task. Do not delete based on static-reference absence alone. |

## 4. Category Details

### 4.1 Technical SEO

- All 101 non-template HTML sources declare `lang="ja"`.
- No page contains multiple H1 elements.
- Seven source-rendered pages have no H1.
- `movie_iframe.html` is the only source missing title, description, canonical, and viewport together.
- The normal page body is present in server-rendered HTML. Area content does not require client-side JavaScript to become crawlable.
- The former PC/mobile source split is disabled in `HP/includefile/dataset_base.php:54-75`. The repository uses one source with responsive classes, so no repository evidence showed separate PC and smartphone text bodies.
- Basic HTML recovery parsing produced 16 actionable events in 11 sources after excluding expected HTML5-tag noise. These include duplicate IDs, one extra closing tag in `contact.html`, one event in the unused `source/create.html`, and unescaped ampersands.

### 4.2 Area Page Duplication

- Population: 78 area sources.
- Pair comparisons: 3,003.
- Exact duplicate group: 27 unresolved sources.
- Exact pairs inside the unresolved group: 351.
- Remaining completed or partly completed sources: 51.
- Pairs at or above 70% regional-core similarity outside the unresolved group: 0.
- Maximum non-exact similarity: 51.45%, `arata` versus `kinkocho`.

The common title and heading pattern targets one local query per page and is not, by itself, a duplicate-content defect. The common shop cards are also a valid service template. The High finding is limited to the 27 pages whose regional values and regional content were not replaced at all.

### 4.3 Internal Links

The parser inspected 5,674 anchors across the 101 normal source files and resolved 3,379 relative PHP route references.

| Link Condition | Count |
|---|---:|
| References to missing PHP targets | 132 |
| Unique missing PHP targets | 124 |
| Missing area-detail targets | 120 |
| Missing old blog-detail targets | 2 |
| Placeholder hotel-detail references | 5 occurrences to 1 target |
| Missing `shopinfo.php` references | 5 |
| Malformed bare telephone href | 1 |

The area index contains 194 unique detail links: 74 resolve to a public PHP file and 120 do not. The blog index links to the old non-`blog-` slugs at `HP/source/blog.html:133-134`. The hotel index repeats one placeholder detail URL at `HP/source/hotel.html:155-171`.

Content pages with no incoming static link:

- Area: `kamoike`, `kamoikeshinmachi`, `kenohikarigaoka`, `kiirehitokuracho`, `kiirenakamyocho`, `kiiresesekushicho`, `koraicho`, `oroshihommachi`, `sanwacho`, and `shimotatsuocho`.
- Blog: `glamourgirl`, `poccharigirl`, `shiroutogirl`, and `tallbeautygirl`.
- Hotel: `greenrichkagoshimatenmonkan`.

The nearby-area subsystem itself is correct. `related-check` returned `RELATED_CHECK_OK` for 78 sources, 71 blocks, seven approved omissions, 361 links, and 24 eligible targets. Link-count distribution was 3 links on four pages, 4 links on 23 pages, 5 links on seven pages, and 6 links on 37 pages.

No inappropriate `nofollow` use was found. Nine `javascript:` anchors are user-interface controls for age confirmation or schedule tabs rather than primary crawl navigation.

### 4.4 Structured Data

| Structured-Data Result | Count |
|---|---:|
| JSON-LD blocks | 198 |
| Sources without JSON-LD | 8 |
| Sources with invalid JSON-LD | 27 |
| Invalid JSON-LD blocks | 54 |
| Sources whose JSON-LD blocks all parsed | 66 |

Sources without JSON-LD are `girls.html`, `girls_list.html`, `movie.html`, `movie_iframe.html`, `mypage.html`, `news.html`, `schedule.html`, and `system.html`. Absence alone is not classified as an error because several are utility, dynamic, or listing pages.

Top-level valid types include BreadcrumbList on 65 pages, ItemList on 62, FAQPage on 11, WebPage on four, Hotel on one, and Organization on one. Valid lists passed the implemented checks for sequential positions, declared item counts, and final breadcrumb URL consistency. No RDFa implementation was found.

LocalBusiness is not required merely because an area page lists businesses. The area page is a service-area and comparison page rather than a physical local branch. Article remains a reasonable optional enhancement for the six editorial blog-detail pages.

### 4.5 Canonical URLs

The repository contains 100 source files that are paired with rendering wrappers.

- Static self-referencing canonical confirmed: 97.
- Incorrect canonical: `contact.html` points to `https://www.55810.com/aaaaaaaaaaaaaaa.php`.
- Missing canonical: `movie_iframe.html`.
- Runtime canonical: `girls.html` uses `rep03010092eot`; its database-resolved value is UNVERIFIED.
- All 78 area sources use absolute HTTPS, `www`, and self-referencing canonical URLs.
- No area source points its canonical to another area.
- No source contains multiple canonical tags.

The unresolved 27 area pages also use self-referencing canonicals. Self-canonical is the correct eventual pattern for unique pages, but it does not mitigate their present identical placeholder content.

### 4.6 Index Control and Sitemap

- `HP/robots.txt` does not exist.
- No effective repository `noindex`, `nofollow`, or X-Robots-Tag policy was found.
- All 78 area sources declare `index`.
- The static sitemap has 61 unique URLs and no duplicate `<loc>` entry.
- `https://www.55810.com/snews.php` is listed but no matching file exists.
- Forty-three normal sources are absent from the sitemap. Twenty-seven are unresolved area pages, two are operational/helper sources, and 14 are non-placeholder indexable sources: 13 area sources plus `news.php`.
- `main.php` and `page.php` are in the sitemap but have no local source HTML.

The sitemap crawler derives the scheme and host from the request, accepts every response except one exact HTTP/1.1 404 string, disables TLS certificate verification, and does not evaluate canonical or robots directives. This process can reproduce host, status, and route inconsistencies.

The repository `.htaccess` has commented examples for HTTPS normalization, `www` removal, and index filename removal. The example `www` removal direction would conflict with the current `www` canonical policy if enabled without revision.

### 4.7 Performance-Related Static Findings

| Asset Measurement | Result |
|---|---:|
| Repository images | 974 |
| Repository image size | 116.9 MB |
| JPEG / PNG / GIF / WebP / SVG / AVIF | 824 / 135 / 10 / 3 / 2 / 0 |
| Statically referenced unique images | 304 |
| Referenced image size | 32.28 MB |
| Referenced images over 200 KB | 29 |
| Referenced images over 500 KB | 4 |
| Referenced images over 1 MB | 1 |
| `img` tags | 526 |
| Missing width or height | 256 |
| Missing alt attribute | 42 |
| Empty alt | 5 |
| Explicit lazy-loading behavior | 137 |

Hero images commonly use `nolazy`, which is reasonable for likely LCP content. The issue is the missing intrinsic size rather than the lack of lazy loading on those hero images.

Eight dynamic or top-level pages include blocking scripts. The top source contains approximately 1,053 start tags and is a DOM-reduction candidate, but no Core Web Vitals value is inferred from that static count. The common renderer also executes two unconditional debug `error_log()` calls for every rendered request.

No repository cache, gzip, or Brotli directive was found. This does not prove that production lacks those features because they can be configured above the repository `.htaccess` level.

## 5. Area Duplicate Groups and Representative Pairs

| Page A | Page B or Group | Compared Content | Similarity | Classification | Evidence |
|---|---|---|---:|---|---|
| `gionnosucho` | `gofukucho` | Title, description, H1, lead, regional body, and venue entries | 100.00% | Exact unresolved duplicate | Repeated `aaaaaaaa...` values |
| Unresolved 27-page group | Every pair in the group | All regional fields | 100.00% | High-risk duplicate | 351 pairs |
| `arata` | `kinkocho` | Regional core | 51.45% | Not high similarity | Highest non-exact pair |
| `yoshino` | `yoshinocho` | Regional core | 51.13% | Acceptable | Distinct local body and venues |
| `hikariyama` | `nanatsujima` | Regional core | 50.87% | Acceptable | Distinct local body and venues |
| `yamadacho` | `yamashitacho` | Regional core | 50.73% | Acceptable | Distinct local body and venues |
| All other non-exact pairs | — | Regional core | Below 51.45% | No high-similarity finding | Zero pairs at or above 70% |

The unresolved group contains these 27 slugs:

`gionnosucho`, `gofukucho`, `gokabeppucho`, `hananohikarigaoka`, `kasugacho`, `kibougaokacho`, `kiirecho`, `kiireikkuracho`, `kiirenakamyocho`, `kinkodai`, `kinseicho`, `koraicho`, `korimoto`, `korimotocho`, `koriyamacho`, `koriyamadakecho`, `kotsukicho`, `koutokujidai`, `koyamadacho`, `koyo`, `oroshihommachi`, `sakamotocho`, `sakanoue`, `sakuragaoka`, `sanwacho`, `shimofukumotocho`, and `shimotatsuocho`.

## 6. Confirmed Correct Implementations

- All 78 area sources have a title, meta description, H1, absolute canonical, visible breadcrumb, and `index` robots value.
- The 51 non-placeholder area sources have no exact regional-core duplicate and no pair at or above 70% similarity.
- No source has multiple H1 elements.
- Every normal source declares Japanese language metadata.
- Ninety-seven wrapper-paired sources have a statically verifiable self-referencing canonical.
- Sixty-six sources contain only parseable JSON-LD, and the implemented list and breadcrumb consistency checks passed.
- The 61 sitemap entries are unique.
- Nearby-area links passed the dedicated validator with 71 blocks, seven approved omissions, and 361 valid links.
- No incorrect `nofollow` use was found.
- Area body content is present in server-rendered source rather than being created only by browser JavaScript.
- `candy-site-state check` returned `CHECK=OK documents=4`.

## 7. Remediation Priority

| Priority | Action | Target | Reason | Expected Effect |
|---:|---|---|---|---|
| 1 | Complete or exclude unresolved pages | 27 area pages | Large exact-duplicate group and invalid JSON-LD | Removes the largest low-quality indexable population |
| 2 | Complete the contact page and correct its canonical | `contact.php` | Sitemap-listed placeholder with wrong canonical | Restores one key general-content route |
| 3 | Reconcile category indexes, sitemap, and orphan links | `area.html`, `blog.html`, `hotel.html`, `sitemap.xml`, 15 orphan pages | 132 broken PHP references and weak discovery | Improves crawl paths and route accuracy |
| 4 | Replace the `group_test` rendering dependency | 96 wrappers and 13 source gaps | Repository cannot prove most rendered output | Restores reproducible, auditable publication |
| 5 | Repair missing image, JavaScript, and video targets | Area and dynamic pages | Broken local resources affect output and likely LCP candidates | Restores visible assets and page features |
| 6 | Establish index and URL-normalization policy | `.htaccess`, `robots.txt`, helper pages | Redirect, 404, and exclusion policy is incomplete | Reduces duplicate and accidental helper-page indexing |
| 7 | Correct H1, HTML, and minor schema defects | Seven no-H1 pages and validation findings | Improves semantics and validation stability | Improves document interpretation and maintainability |
| 8 | Add image dimensions and optimize loading | Shared templates and assets | Static CLS and render-blocking risks | Improves performance potential |

## 8. Unverified and External Checks

### 8.1 Production HTTP Verification Required

- Actual 200, 301, 302, 404, and 410 responses.
- Actual HTTPS, `www`, index filename, path-case, and trailing-slash normalization.
- Effective X-Robots-Tag, cache, compression, and security headers.
- Contents and behavior of the out-of-repository `group_test` renderer.
- Whether repository-missing images, scripts, or videos exist only on the production server.
- Final database-resolved canonical for `girls.php`.

### 8.2 Search Console Required

- Actual indexed URL population.
- Google-selected canonical URLs.
- Crawled-not-indexed, duplicate, soft-404, and broken-route reports.
- Submitted and fetched sitemap status.

### 8.3 Analytics Required

- Organic traffic and conversion impact by affected URL.
- User traffic to orphaned pages.
- Business impact of broken content and media routes.

### 8.4 Lighthouse or Browser Laboratory Required

- LCP, CLS, INP, TTFB, and resource waterfall measurements.
- Effective unused CSS and JavaScript after runtime execution.
- Actual image decode, caching, compression, and transfer behavior.

## 9. Change and Operation Record

This section records the audit execution itself. The later Commit and GitHub synchronization of this management report are recorded separately in `TASK_LOG.md` under `TASK-20260718-SEO-AUDIT-GITHUB-SYNC-001` and do not change the audit evidence.

- HP content changes: NOT_PERFORMED.
- File deletion, movement, or renaming: NOT_PERFORMED.
- Commit: NOT_PERFORMED.
- Push: NOT_PERFORMED.
- Deployment or production operation: NOT_PERFORMED.
- Production HTTP or browser check: NOT_PERFORMED.
- Audit report creation: COMPLETE.
