# CANDY Area Page Generation Specification

- Updated: 2026-07-18
- Applies to: Normal new generation of CANDY area detail pages by Codex

## 1. Purpose

This is the canonical specification for generating area pages without damage and with a consistent structure.

Use it for normal new-page generation. Development changes such as bug fixes, existing-feature changes, common-processing changes, and refactoring are out of scope and follow `AGENTS.md` and `HP/AGENTS.md`.

Apply `CANDY_PAGE_GENERATION_GOVERNANCE.md` first for common missing-input, variable-structure, and STOP rules.

Review `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` for area-image acceptance, slug reconciliation, placement in the canonical public source, and Git management.

For distributed staff production of unbuilt area pages, also review `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` and `CANDY_AREA_105_PAGE_QUEUE.md`.

Legacy area-production history and investigation snapshots are not current canonical sources. Normal generation uses this specification, the common governance document, and generated ledgers.

### 1.1 Responsibility and Page Structure

#### Responsibility

- Enable users to identify delivery-health shops that can dispatch to the region, travel times, and transportation fees.
- Combine basic regional information, nearby hotels, meeting places, and nearby spots so users can evaluate where to use the service.
- Provide routes to supported-shop lists, shop details, hotel/spot details, and verified nearby area pages.
- Keep region-search body content consistent with breadcrumb and shop-list structured data.

#### Generation and Change Guidance

- When asked for the structure, use the following tree as the visible-order basis.
- Do not fix shop, hotel, or spot counts. Repeat the same item structure for complete blocks in source data only.
- `周辺の対応エリア` uses three to six verified nearby published area links, normally four. Omit the entire block when fewer than three suitable completed targets exist.
- Do not infer an item absent from source data. When an optional item count is zero, do not create an empty heading or container.
- Number scene, subtitle, description, and FAQ item IDs in visible order without gaps or duplicates.

#### Page Structure

The Japanese labels below are exact website display concepts and are preserved.

```text
エリアページ
├ パンくずリンク
    ├ TOP
    ├ 対応エリア一覧
    └ 鹿児島市「地域名」で呼べるデリヘル
├ 画像（メイン画像／img_1）
├ 鹿児島市「地域名」で呼べるデリヘル（H1）
    ├ 見出し・リード文（subtitle_h1）
    └ 本文（description_h1）
├ ボタン（対応デリヘル店一覧／button_1）
├ 画像（人気デリヘル店セクション用／titleimg_1）
├ 鹿児島「地域名」に呼べる「人気デリヘル店」情報（scene1）
    ├ 店舗情報（1件以上・入力件数分）
        ├ 店舗画像（PC用・SP用）
        ├ 店舗名
        ├ 電話番号
        ├ 営業時間
        ├ 移動時間
        ├ 交通費
        ├ キャッチコピー
        ├ 店舗紹介文
        └ ボタン（店舗詳細）
    └ 対応状況に関する注記（description_1）
├ ボタン（対応デリヘル店一覧／button_2）
├ 画像（地域紹介画像／img_2）
├ 鹿児島市「地域名」について（scene2）
    ├ 見出し・リード文（subtitle_2）
    └ 本文（description_2）
├ 鹿児島市「地域名」基本情報（scene3）
    ├ 地図
    ├ 人口
    ├ 面積
    ├ 設置年月日
    └ 情報更新時期に関する注記（description_3）
├ 鹿児島市「地域名」近辺にあるホテル・宿泊施設情報（scene4）
    ├ ホテル情報（入力件数分）
        ├ ホテル名
        ├ 住所
        ├ 電話番号（元データにある場合）
        └ ボタン（詳細はコチラ）
    └ 利用可否・施設情報に関する注記
├ 鹿児島市「地域名」待ち合わせ・周辺スポット（scene5）
    ├ スポット情報（入力件数分）
        ├ スポット名
        ├ 住所
        ├ 電話番号（元データにある場合）
        └ ボタン（詳細はコチラ）
    └ 情報変更に関する注記
├ 周辺の対応エリア（適切な完成済みリンクが3件以上ある場合のみ）
    ├ 周辺エリアリンク1
    ├ 周辺エリアリンク2
    ├ 周辺エリアリンク3
    ├ 周辺エリアリンク4（基本件数）
    ├ 周辺エリアリンク5（周辺地域が多い場合）
    └ 周辺エリアリンク6（最大件数）
├ ボタン（対応デリヘル店一覧／button_3）
└ 表示外の構造化データ
    ├ BreadcrumbList
    └ 店舗情報ItemList
```

## 2. Mandatory Rules

- Use the target-region text under `Text_area_data` as source data.
- Use `HP/source/template_kagoshima-deliveryhealth-area.html` as the HTML template.
- Treat public entry PHP, source HTML, page-specific dataset PHP, and `dataset_base.php` registration as one set.
- Do not report completion after generating only HTML.
- Do not normally use `HP/create.php` for Codex page generation.
- When any same-name file exists, do not overwrite it; verify the existing three-file set and registrations.
- `dataset_base.php` is a high-impact common file. Present the target and diff first, then change it minimally only after user instruction or approval.

## 3. Verified Population and Counts

All actual files were read on 2026-07-12 and yielded:

| Target | Count | Notes |
|---|---:|---|
| Text files under `Text_area_data` | 169 | 135 direct, 32 under Completion, and 2 under Backup |
| Text files with a page URL | 168 | Excludes one update-procedure text file |
| Area public-candidate source HTML | 61 | 34 complete and 27 with remaining placeholders |
| Area public entry PHP | 71 | Includes 10 without source |
| Area page-specific dataset PHP | 71 | Includes 10 without source |
| Area cases in `dataset_base.php` | 39 | 31 of 61 source files are unregistered |
| Area link transformations in `dataset_base.php` | 39 | 31 of 61 source files are unregistered |

Counts may change; recalculate them for new production.

## 4. File Pairing

For page identifier `<slug>`, the required structure is:

```text
source data
Text_area_data/.../<地域名>_テンプレート.txt

HTML template
HP/source/template_kagoshima-deliveryhealth-area.html

three generated files
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php

additional registration
HP/includefile/dataset_base.php
```

Do not determine a slug by inference alone. Reconcile canonical in source data, the filename, and existing page names. When one region has a legacy slug or alternate spelling, obtain user confirmation.

### 4.1 Separation of Text Classification and New-Production Eligibility

Text classifications such as `間違い無し`, `画像無し`, and `情報足りない` describe input content only. They do not determine new-page eligibility.

New production is eligible only after confirming that the canonical slug has no existing public PHP, source HTML, page-specific dataset PHP, `dataset_base.php` registration, or sitemap registration; that the area index has exactly one target-slug link; and that it has no different slug for the same region name. Do not select a target from a successful classification alone.

## 5. Source-Data to HTML Mapping

| Source-data item | HTML target |
|---|---|
| title | `<title>` and OGP title when applicable |
| description | Meta description and OGP description |
| canonical | Canonical and OGP URL |
| image | OGP image |
| img_1 | Main-image `src` and alt |
| page_title_h1 / breadcrumb | Breadcrumb and H1 |
| subtitle_h1 | `id="subtitle_h1"` |
| description_h1 | `id="description_h1"` |
| Shop list | Shop blocks in `scene1` |
| img_2 | Regional-introduction image `src` and alt |
| Regional introduction | Scene, subtitle, and description |
| Map URL and title | iframe `src` and title |
| Population, area, and establishment date | Basic-information table |
| Hotel information | FAQ blocks |
| Meeting and nearby spots | FAQ blocks |
| Nearby supported areas | Render the exact target order from `codex/data/CANDY_AREA_RELATED_LINKS.json`; link text is `鹿児島市{リンク先地域名}で呼べるデリヘル` |
| Page-wide information | Two JSON-LD blocks |

Do not add line breaks to body copy except where explicitly present in source data. Distinguish line breaks required by HTML markup from visible line breaks in copy.

## 6. Scene, Subtitle, and Description Numbering

Verified complete pages have five base scenes:

1. `scene1`: Popular delivery-health shop information
2. `scene2`: Regional introduction
3. `scene3`: Basic regional information
4. `scene4`: Nearby hotels and lodging
5. `scene5`: Meeting and nearby spots

Normal blocks use:

```text
scene1 / description_1
scene2 / subtitle_2 / description_2
scene3 / description_3
```

Multi-item FAQs use:

```text
scene4
subtitle_4_1 / description_4_1
subtitle_4_2 / description_4_2

scene5
subtitle_5_1 / description_5_1
subtitle_5_2 / description_5_2
```

- Number scenes and FAQ items sequentially from the top.
- Duplicate IDs, gaps, and an ID from another scene are prohibited.
- Add or remove FAQ blocks according to hotel and spot counts.
- Only the final item in each FAQ section uses `class="faq-item bd_tb"`; other items use `class="faq-item bd_t"`.

All 34 verified complete pages have five scenes. FAQ counts were five on two pages, six on 31 pages, and seven on one page, so FAQ count is variable.

## 7. Shop Blocks

- Base shop information on the matching block in `HP/source/template_shop.html`.
- Include only shops specified by source data.
- Match travel time and transportation fees to source data.
- Do not change common shop-block structure, links, or measurement elements without cause.
- Do not infer a shop absent from source data.

### 7.1 Nearby Supported Areas

- The canonical mapping is `codex/data/CANDY_AREA_RELATED_LINKS.json`. Do not maintain another page-by-page mapping.
- Use the heading `周辺の対応エリア` and the link text `鹿児島市{リンク先地域名}で呼べるデリヘル`.
- Normally output four links. Output five or six only when multiple strong nearby candidates exist, and stop at three when only three suitable candidates exist.
- Do not add an unrelated target to reach six. Self-links, duplicate links, incomplete targets, non-public targets, `href="#"`, and placeholder copy are prohibited.
- A target is eligible only when its public PHP and complete source/dataset structure exist and its title, description, canonical, robots, H1, OGP, JSON-LD, internal links, and image alt checks are normal.
- Select candidates from actual geographic proximity, then review city area and life-zone relevance before fixing their order. The current mapping uses the Geospatial Information Authority of Japan's GSI Maps place/address search as the geographic source.
- When fewer than three eligible nearby targets exist, omit the entire block; do not render an empty heading or container.
- The area template contains only the `AREA_RELATED_LINKS` generation marker. A generated public source HTML must not retain the marker.

## 8. JSON-LD

The area template and every area source HTML contain two JSON-LD blocks:

- BreadcrumbList
- An ItemList-type block for shops

During generation, match region name, URL, position, shop name, telephone, shop URL, and description to visible HTML.

Validation:

- Leave no placeholder.
- Use numeric positions.
- Verify JSON parsing.
- Match breadcrumb hierarchy and URLs to the visible breadcrumb.
- Match shop count to ItemList element count.

The 34 currently complete pages parse successfully. Both JSON-LD blocks are incomplete on the 27 pages with remaining placeholders.

## 9. Public Entry PHP

The 71 existing area public entry PHP files share this base form. The Japanese code comment is preserved as an exact code value.

```php
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


?>
```

For new generation, recheck a current complete page in the same category and use the same form. Do not infer a server-path change.

## 10. Page-Specific Dataset PHP

The 71 existing area dataset PHP files share this base form:

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

Use a current complete same-category page as the reference. Do not mix development changes such as replacing short opening tags with normal tags into new-page generation.

## 11. Required dataset_base.php Registration

Normal new-page generation registers two locations.

### 11.1 Dataset Routing

```php
case 'kagoshima-deliveryhealth-area-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-<slug>.php');
    break;
```

### 11.2 HTML-to-PHP Link Transformation

```php
$source = str_replace(
    'kagoshima-deliveryhealth-area-<slug>.html',
    'kagoshima-deliveryhealth-area-<slug>.php',
    $source
);
```

Case, source HTML, dataset PHP, and public PHP slugs MUST match exactly.

`dataset_default.php` may load source HTML when registration is absent, but normal generation does not permit omitted registration.

## 12. Generation Algorithm

1. Verify Git branch, worktree, and remote state.
2. Verify region name, slug, canonical, images, and every input item in the target text file.
3. Check for same-name public PHP, source HTML, dataset PHP, and dataset_base registration.
4. Compare at least one complete same-category page.
5. Copy the area template into new source HTML.
6. Apply SEO, OGP, breadcrumb, H1, images, body, map, and basic information.
7. Apply specified shop blocks from `template_shop.html`.
8. Add or remove FAQ blocks according to hotel and spot counts.
9. Renumber scenes, subtitles, and descriptions from the top.
10. Match the two JSON-LD blocks to body content.
11. Generate public entry PHP.
12. Generate page-specific dataset PHP.
13. Register both locations in `dataset_base.php`.
14. Validate internal links, images, canonical, OGP, and slug.
15. Check all placeholders, the exact nearby-area mapping, incomplete input, legacy slugs, and duplicate IDs.
16. Validate PHP syntax, JSON syntax, `git diff --check`, and changed targets.
17. Determine whether `source/area.html` index links and JSON-LD and `sitemap.xml` require registration.
18. When browser validation was not performed, report browser rendering as unverified.
19. Commit and Push only after user confirmation and explicit instruction.

## 13. Exceptions

Do not force area source-data quantity into template fixed counts. Match hotel, spot, and shop counts to source data and synchronize IDs, final FAQ classes, and JSON-LD ItemList after additions or deletions.

### 13.1 Variable FAQ Count

Hotels and spots are not fixed at three. Add or delete according to source data and set only the final FAQ in each section to `bd_tb`.

### 13.2 Multiple Slugs for One Region

Existing content may contain alternate slugs for the same region. During new production, verify canonical, filenames, existing links, and user instruction. Do not consolidate, delete, or rename automatically.

Examples:

- `hananohikarigaoka` and `kenohikarigaoka`
- `kiireikkuracho` and `kiirehitokuracho`

### 13.3 Source-Data Location Does Not Equal Completion State

- Source HTML may contain placeholders even when source data is under `Completion`.
- Complete source HTML may exist for direct source data.
- Do not determine completion from the folder name alone.

### 13.4 Missing Images

Some complete HTML references missing images. During generation, verify actual `_1` and `_2` files.

When required images are absent for a new area-page request, STOP and request images using:

```text
kagoshima-deliveryhealth-area-<slug>_1.jpg
kagoshima-deliveryhealth-area-<slug>_2.jpg
```

Without user approval, do not reuse an existing image, use a dummy image, infer an image name, or publish without images. After receipt, verify format, dimensions, slug, both-file completeness, and duplication before use.

### 13.5 Partial Existing Three-File Set or Registration

Treat this as an existing-inconsistency fix, not new production. Keep it separate and obtain approval after presenting the affected scope.

## 14. Currently Verified Inconsistencies

### 14.1 Source HTML with Remaining Placeholders: 27

```text
gionnosucho, gofukucho, gokabeppucho, hananohikarigaoka,
kasugacho, kibougaokacho, kiirecho, kiireikkuracho,
kiirenakamyocho, kinkodai, kinseicho, koraicho,
korimoto, korimotocho, koriyamacho, koriyamadakecho,
kotsukicho, koutokujidai, koyamadacho, koyo,
oroshihonmachi, sakamotocho, sakanoue, sakuragaoka,
sanwacho, shimofukumotocho, shimotatsuocho
```

Each page retains 79 primary placeholders and both JSON-LD blocks have invalid syntax.

### 14.2 Source HTML Without dataset_base Registration: 31

```text
ariyadacho, hananohikarigaoka, ikenouecho, inaricho,
inusakocho, irisacho, ishidanicho, ishiki, ishikidai,
izumicho, kajiyacho, kamoike, kamoikeshinmachi,
kenohikarigaoka, kiirehitokuracho, kiireikemicho,
kiireikkuracho, kiiremaenohamacho, kiirenakamyocho,
kiiresesekushicho, obaracho, ogawacho, okanoharacho,
ono, oroshihonmachi, uearatacho, uenosonocho,
uomicho, usuki, yakushi, yasuicho
```

The same 31 entries also lack HTML-to-PHP link transformations.

### 14.3 Public PHP and Dataset PHP Without Source HTML: 0

The nine broken partial page pairs were removed on 2026-07-20 together with their dataset-base registrations and area-index links. Source Text and image assets were retained for any future normal production.



### 14.4 Other Findings

- `oroshihonmachi`: Source data exists under Completion, but source HTML retains placeholders.
- `shimotacho`: Source data is direct, but source HTML is complete.
- `arata` and `kinkocho`: Duplicate `id="button_1"`.
- Complete-HTML missing-image candidates: `_1` and `_2` for `inusakocho` and `kenohikarigaoka`.

These are verified existing conditions and were not fixed by the specification investigation.

## 15. Completion Criteria

- [ ] Every required source-data item is complete.
- [ ] Region name and slug are confirmed.
- [ ] Same-name files, legacy slugs, and similar slugs are checked.
- [ ] Public PHP, source HTML, and dataset PHP exist.
- [ ] dataset_base case registration exists.
- [ ] dataset_base link transformation exists.
- [ ] The nearby-area block exactly matches the canonical mapping, contains three to six approved links when present, and is absent when the mapping has fewer than three targets.
- [ ] Placeholder count is zero, including related-link placeholder copy and `href="#"`.
- [ ] Scenes, subtitles, and descriptions have no duplicate or gap.
- [ ] Visible shop, hotel, and spot counts match JSON-LD counts.
- [ ] No shop, hotel, or spot absent from source data was inferred.
- [ ] The final FAQ item class is correct.
- [ ] Canonical, OGP URL, images, breadcrumb, and H1 agree.
- [ ] Both JSON-LD blocks match visible content and parse.
- [ ] Images `_1` and `_2` exist.
- [ ] No existing, dummy, or inferred image was used to bypass missing images.
- [ ] Internal links point to public PHP.
- [ ] Robots agrees with publication policy.
- [ ] Area-index and sitemap registration requirements were checked.
- [ ] PHP syntax is verified.
- [ ] `git diff --check` succeeds.
- [ ] No out-of-scope file changed.
- [ ] Missing browser validation is reported.
- [ ] Source text was not moved or deleted without instruction.
- [ ] Local completion and production deployment are reported separately.

## 16. Unchanged Scope

This specification investigation did not change public PHP, source HTML, dataset PHP, `dataset_base.php`, images, or source Text.
