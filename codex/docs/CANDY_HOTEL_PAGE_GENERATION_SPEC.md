# CANDY Hotel Page Generation Specification

- Updated: 2026-07-24
- Applies to: Normal new generation of CANDY hotel detail pages by Codex

## 1. Purpose and Scope

This is the canonical specification for generating hotel pages from source Text without damage. Use it for normal new-page generation, not for bug fixes, existing-feature changes, common-processing changes, or refactoring.

Apply `CANDY_PAGE_GENERATION_GOVERNANCE.md` first for common missing-input, variable-structure, and STOP rules.

Select one source route before production:

- `DIRECT_TEXT`: Staff already completed the production Text. Do not require
  Phase results. Reconcile the target's accepted and local-public image pairs,
  first-install a complete accepted pair when required, then run
  `candy-hotel.cmd direct-check`. Use the direct start in
  `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` only when no complete accepted or public
  pair exists, and execute through `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`.
- `PHASE_PREPARED`: Use `CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md` for Phases 1-3, `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` for Phase 4, and `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` for Phase 5.

Both routes converge on the same target Text, template, generator, target gate, validation, and publication implementation. Phase results are never page-content inputs.

A legacy Text is not a third source route. It MUST pass `legacy-check` and the migration contract in `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` before it can enter `DIRECT_TEXT`. Page generation accepts only the current Text format.

### 1.1 Responsibility and Page Structure

#### Responsibility

- Enable a user calling delivery health to a specific hotel to identify supported shops, estimated arrival time, and transportation fees.
- Combine hotel characteristics, official information, fees, access, and nearby spots so pre-use review can be completed on one page.
- Provide routes to official hotel details, supported-shop lists, shop details, nearby-spot details, and related articles.
- Keep hotel-name search body content consistent with breadcrumb, FAQ, and ItemList structured data.

#### Generation and Change Guidance

- When asked for the structure, use the following tree as the common-component checklist.
- Hotel pages do not have a fixed scene count. Preserve source-data order for known sections and normal article blocks after the H1 introduction; number only visible H2 elements sequentially from scene1.
- At least one shop is required. Normal articles, FAQs, fees, access, and nearby spots MAY have zero items; omit an optional section with zero items.
- Display the legacy option zero or one time only when `option`, `option_subtitle`, and `option_description` are all complete. Do not include it in scene numbering.
- Related articles have one fixed generated count: three current indexable blog-detail links and three current indexable area-detail links.
- Do not infer a value, image, URL, or hotel fact absent from source data. STOP on partial input instead of completing it.

#### Page Structure

The Japanese labels below are exact website display concepts and are preserved.

```text
ホテルページ
├ パンくずリンク
    ├ TOP
    ├ 対応ホテル一覧
    └ 鹿児島市でデリヘルが呼べるホテル「ホテル名」
├ 画像（メイン画像／img_1）
├ 鹿児島市でデリヘルが呼べるホテル「ホテル名」（H1）
    ├ 見出し・リード文（subtitle_h1）
    └ 本文（description_h1）
├ ホテル独自案内（旧option・任意0または1件）
    ├ 見出し（option）
    ├ リード文（option_subtitle）
    └ 本文（option_description）
├ ボタン（ホテル詳細／button_1）
├ 通常記事ブロック（任意0件以上・元データの位置と件数に従う）
    ├ 見出し（sceneN）
    ├ リード文（subtitle_N）
    └ 本文（description_N）
├ 「ホテル名」に呼べる「鹿児島の人気デリヘル店」情報（sceneN）
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
    ├ 対応状況に関する注記（description_N）
    └ ボタン（対応デリヘル店一覧）
├ よくあるご質問「FAQ」（任意0件以上・sceneN）
    ├ FAQ項目（入力件数分）
        ├ 質問（subtitle_N_M）
        └ 回答（description_N_M）
    └ ボタン（対応デリヘル店一覧・FAQ表示時）
├ 画像（ホテル基本情報側／img_2）
├ 基本情報（sceneN）
    ├ ホテル名・公式URL
    ├ 住所
    ├ 電話番号（任意）
    ├ 部屋・駐車場（任意）
    └ 支払方法（任意）
├ 料金情報（任意0件以上・sceneN）
    ├ 料金行（入力件数分）
        ├ 区分名
        └ 料金
    └ 料金補足文（元データにある場合）
├ アクセス情報（任意0または1件・sceneN）
    ├ 地図
    ├ 見出し・リード文（subtitle_N）
    └ 本文（description_N）
├ 「ホテル名」周辺スポット（任意0件以上・sceneN）
    ├ スポット情報（入力件数分）
        ├ スポット名
        ├ 住所
        ├ 電話番号（元データにある場合）
        └ ボタン（詳細はコチラ）
    └ 情報変更に関する注記（元データにある場合）
├ 関連記事
    ├ 公開ブログリンク1
    ├ 公開ブログリンク2
    ├ 公開ブログリンク3
    ├ 公開エリアリンク1
    ├ 公開エリアリンク2
    └ 公開エリアリンク3
└ 表示外の構造化データ
    ├ BreadcrumbList（必須）
    ├ FAQPage（FAQが1件以上ある場合）
    └ ItemList（周辺スポットがあればスポット、なければ店舗）
```

## 2. Mandatory Rules

- Use the target hotel text file under `Text_hotel_data` as source data.
- Require the current Text format. STOP on `旧形式要変換`; do not normalize legacy labels or numbered blocks during page generation.
- Use `HP/source/template_kagoshima-deliveryhealth-hotel.html` as the HTML template.
- Treat public entry PHP, source HTML, page-specific dataset PHP, and `dataset_base.php` registration as one set.
- Do not report completion after generating only HTML.
- Do not normally use `create.php` for Codex page generation.
- Match shops, normal article scenes, FAQs, optional basic-information rows, fee rows, access entries, and nearby spots to complete source-data blocks. Do not set a fixed maximum.
- Preserve input order for normal article scenes and known sections. STOP before generation on a partial block.
- Under `関連記事`, publish three distinct indexable blog-detail links and three distinct indexable area-detail links selected deterministically from current public files. Exclude the current page, duplicate destinations, placeholder text, and `href="#"`.
- Match JSON-LD to visible content.

Standard production and publication runs only:

```powershell
codex\scripts\candy-hotel.cmd publish --input "Text_hotel_data/対象ホテル.txt"
```

Direct staff-completed Text preflight:

```powershell
codex\scripts\candy-hotel.cmd legacy-check --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd direct-check --input "Text_hotel_data/対象ホテル.txt"
```

Only `DIRECT_TEXT_STATUS=READY_FOR_IMAGES` may enter direct image creation.
When a complete accepted pair exists and only its local-public copy is absent,
perform the page-authorized first installation instead of entering image
creation or reporting a missing-image STOP. Only
`DIRECT_TEXT_STATUS=READY_FOR_BUILD` may continue to the common target gate and
page generation.

The dedicated tool runs generation, validation, target-limited staging, one Commit, one Push, Actions, production HTTP validation, and URL output in sequence.

## 3. Current-State Source

Do not store hotel input, image, page, registration, or eligibility counts in this stable specification. Use actual files, `generated/CANDY_UPCOMING_PAGES.md`, and the dedicated commands:

```powershell
codex\scripts\candy-hotel.cmd audit-inputs
codex\scripts\candy-hotel.cmd audit-existing
codex\scripts\candy-hotel.cmd target-next
```

Inspect input classification through `BLOCKER_COUNTS_JSON` and do not hide simultaneous blockers such as missing images and untracked input.

For automatic candidate discovery, treat a complete accepted-source pair as
image availability. First-install the selected target's pair before the final
target gate. `BLOCKER_COUNTS_JSON` may report missing images only when neither
a complete accepted pair nor a complete local-public pair exists.

## 4. Required File Set

```text
Text_hotel_data/<ホテル名>.txt
HP/source/template_kagoshima-deliveryhealth-hotel.html

HP/kagoshima-deliveryhealth-hotel-<slug>.php
HP/source/kagoshima-deliveryhealth-hotel-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-hotel-<slug>.php
HP/includefile/dataset_base.php
```

Determine the slug by reconciling canonical in source data, image names, hotel name, and existing pages. Do not infer it when source data contains a placeholder.

## 5. Source-Data to HTML Mapping

| Source-data item | HTML target | Count |
|---|---|---|
| title, description, canonical | SEO and OGP | One each, required |
| img_1, img_2 | Main and basic-information-side images | One each, required |
| page_title_h1, subtitle_h1, description_h1 | Breadcrumb, H1, and introduction | One each, required |
| Complete option set | Legacy independent guidance | Zero or one |
| Normal scene H2 | Normal article blocks | Zero or more |
| Shop selection | Popular delivery-health shop blocks | One or more |
| FAQ | Visible FAQ and FAQPage JSON-LD | Zero or more |
| Basic information | Hotel name, official URL, address, and optional rows | Three required items plus optional rows |
| Fee information | Fee table and optional supplemental copy | Zero or more |
| Access information | Map, map title, subtitle, and description | Zero or one |
| Nearby spots | Multiple items and optional warning copy | Zero or more |
| Related articles | Deterministic public links | Exactly six: three blog details and three area details |

Do not add unnecessary visible line breaks when source data does not specify them.

## 6. Variable Structure and Numbering

Do not treat hotel pages as fixed at six scenes. Display only complete input blocks and preserve their order.

Required:

- SEO, OGP, img_1, img_2, and H1 introduction
- Hotel name, official URL, and address
- At least one shop present in `template_shop.html`
- Six deterministic related-article links: three blog details and three area details

Optional:

- Legacy option set: zero or one, displayed only when all three option fields are complete
- Normal article scenes, FAQ, fee rows, and nearby spots: zero or more
- Access: zero or one; when present, requires map URL, map title, subtitle, and description
- Basic-information telephone, room/parking, and payment rows
- Fee supplemental copy and nearby-spot warning copy

Numbering:

- Legacy option uses `id=option` and is excluded from scene numbering.
- Number every other visible H2 sequentially from scene1.
- Normal blocks use `subtitle_N` and `description_N`.
- FAQ and nearby spots use `subtitle_N_M` and `description_N_M`.
- Only the final FAQ-type item uses `class=faq-item bd_tb`; others use `class=faq-item bd_t`.
- Leave no gap or duplicate ID after adding or removing sections or items.

Preserve source-data order for known sections. Normal article scenes may occur before or after known sections.

## 7. Shop Blocks

- Base each shop on its matching block in `HP/source/template_shop.html`.
- Include only shops specified by source data.
- When Text contains travel time and transportation fees, use those values first.
- Only when Text omits them, select the nearest complete area page per shop from hotel-map coordinates and use its travel time and transportation fees.
- Include nearby reference sources in publication dependency files. When coordinates or a suitable complete page are unavailable, STOP instead of inferring.
- Do not infer changes to shop information, links, or measurement elements.

## 8. Missing-Input Handling

Distinguish absent input from partial input.

| State | Handling |
|---|---|
| No normal article scene | Do not generate it |
| No FAQ | Do not generate visible FAQ or FAQPage |
| No optional basic-information row | Do not generate the row |
| No fee row | Do not generate the fee section |
| No complete access set | Do not generate the access section |
| No nearby spot | Do not generate the spot section |
| Complete optional blocks exist | Generate all in input count and order |
| Only subtitle, only description, or partial access | STOP without inference |
| Only fee supplemental copy or spot warning | STOP because the target body is absent |

Omit an optional section with zero items without asking. Do not leave blanks, placeholders, meaningless headings, or empty containers. Renumber scenes and JSON-LD after omission.

## 9. JSON-LD

Do not fix the number of blocks.

- BreadcrumbList is required.
- Generate FAQPage only for one or more FAQs and match visible questions, answers, and count.
- When one or more nearby spots exist, ItemList represents spots.
- When no nearby spot exists, ItemList represents shops.
- ItemList count, order, name, and URL MUST match visible content.
- Leave no placeholder and parse all JSON.

## 10. Public Entry PHP and Dataset PHP

Public entry PHP uses the same base form as area and blog and loads `dataset_base.php`.

The three hotel dataset PHP files use:

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

Use this form for new generation. Separate existing-PHP structural changes as development work.

## 11. Registration in dataset_base.php, Hotel Index, and Sitemap

Register one target at a time:

```php
case 'kagoshima-deliveryhealth-hotel-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-hotel-<slug>.php');
    break;
```

```php
$source = str_replace(
    'kagoshima-deliveryhealth-hotel-<slug>.html',
    'kagoshima-deliveryhealth-hotel-<slug>.php',
    $source
);
```

Register the target slug in the hotel index and sitemap.

All three current hotel pages have the three page files plus dataset_base, hotel-index, and sitemap registration. Keep existing-page exceptions such as the Hotel M legacy IDs separate from new production.

STOP new production when the target slug already exists in public PHP, source HTML, dataset PHP, dataset_base, the hotel index, or sitemap.

## 12. Generation Algorithm

1. Verify Git state and target scope.
2. Verify required source-text items, canonical, slug, images, and
   placeholders. Treat a complete accepted pair as available and
   first-install it into the canonical local-public directory before the final
   target gate when the public pair is absent.
3. Check the same-name three-file set and shared registrations for duplication.
4. Parse input blocks by type and record the entire order, including normal article scenes.
5. Generate only complete blocks; omit optional sections with zero items.
6. Apply every specified shop from `template_shop.html`.
7. Only for Text-omitted travel time and transportation fees, derive values from hotel coordinates and nearby complete area pages.
8. Display a complete legacy option independently and do not mix it into normal scenes.
9. Generate FAQs, optional basic-information rows, fees, access, and nearby spots according to input count.
10. Renumber scenes, subtitles, and descriptions in visible order.
11. Synchronize FAQPage and ItemList to visible presence, count, and order.
12. Generate public entry PHP, source HTML, dataset PHP, shared registrations, hotel index, and sitemap for the target only.
13. Check placeholders, empty containers, duplicate IDs, gaps, and missing body content.
14. Check canonical, images, official URL, map, internal links, PHP, JSON, and diff.
15. For an explicitly authorized publish, run target-limited Commit, Push to main, Actions, and production HTTP validation.

## 13. Exceptions and Cautions

### 13.1 hotelm Uses a Legacy Structure

hotelm has no FAQ, three fee rows, and three nearby spots. IDs are scene1, scene2, scene3, scene4, and scene6, leaving a gap. Treat the available information as a valid pattern, but do not copy legacy IDs; number remaining scenes sequentially on a new page.

### 13.2 Source Text Contains Placeholders

The villacosta500 source Text uses the legacy numbered structure and retains placeholders for slug, images, URLs, and related values. Existing complete HTML is complete, but do not reuse or convert the source Text until `legacy-check` reports no unresolved issue.

### 13.3 Retired Update-Procedure Text

The former `Text_hotel_data/Cursor用更新手順.txt` was retired from the production-input directory after its applicable rules were reconciled into the canonical hotel documents. Do not restore or route work through it. Use `CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md`, this specification, the hotel image specification, and the hotel staff runbook.

### 13.4 Legacy Hotel M Source Text

`Text_hotel_data/Hotel M（旧レクサス）.txt` exists, but it uses legacy metadata, generic photo labels, and numbered scenes and contains unresolved required values and ambiguous image blocks. It is not current production input. Use `legacy-check` to identify exact shortages; do not fill them from the existing page automatically.

## 14. Completion Criteria

- [ ] `SOURCE_ROUTE` is exactly `DIRECT_TEXT` or `PHASE_PREPARED`, and evidence from the other route was not required.
- [ ] The source is current format; any legacy source passed conversion and current-parser validation before `direct-check`.
- [ ] Source Text, hotel name, slug, and canonical are confirmed.
- [ ] Missing-input handling is determined.
- [ ] Rows, FAQs, and sections were added or removed according to information quantity.
- [ ] No HTML, ID, or JSON-LD remains for an omitted section.
- [ ] Public PHP, source HTML, and dataset PHP exist.
- [ ] dataset_base case and link transformation exist.
- [ ] Placeholder count is zero.
- [ ] No heading or additional copy from a complete normal scene is missing.
- [ ] Scene, FAQ, and nearby-spot numbering is correct.
- [ ] Exactly three distinct current indexable blog-detail links and three distinct current indexable area-detail links exist, with no placeholder, self-link, duplicate, or missing target.
- [ ] Travel time and transportation fees prioritize Text and use map coordinates and nearby complete area pages only when Text omits them.
- [ ] Visible FAQ matches FAQPage JSON-LD.
- [ ] FAQPage JSON-LD is absent when no FAQ exists.
- [ ] Hotel name, official URL, address, and map correspond correctly.
- [ ] Images exist.
- [ ] When the accepted pair existed without a local-public pair, the exact
      accepted bytes were first-installed before the final target gate.
- [ ] Canonical, OGP, and internal links are correct.
- [ ] Robots agrees with publication policy.
- [ ] Hotel-index and sitemap registration requirements were checked.
- [ ] No duplicate ID exists.
- [ ] PHP syntax, JSON syntax, and `git diff --check` were validated.

## 15. Unchanged Scope

This investigation did not change existing hotel pages, PHP, dataset PHP, `dataset_base.php`, images, or source Text.
