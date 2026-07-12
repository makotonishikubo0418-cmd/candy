# CANDY HOTEL PAGE GENERATION SPEC

更新日: 2026-07-12
対象: 鹿児島キャンディのhotel詳細ページをCodexが通常運用で新規生成する場合

## 1. 目的と適用範囲

hotelページを元テキストから壊さず生成するための正本仕様です。通常の新規ページ生成に使用し、不具合修正、既存機能変更、共通処理変更、リファクタには使用しません。

共通の入力不足・可変構造・停止条件は `CANDY_PAGE_GENERATION_GOVERNANCE.md` を先に適用します。

## 2. 絶対ルール

- 元データは `HP/Text_hotel_data` の対象ホテルtxtを使用する
- HTMLテンプレートは `HP/source/template_kagoshima-deliveryhealth-hotel.html` を使用する
- 公開入口PHP、source HTML、ページ別dataset PHP、`dataset_base.php`登録を1セットとする
- HTMLだけを生成して完了としてはいけない
- `create.php`は通常のCodexページ生成では原則使用しない
- ホテル情報、店舗、FAQ、料金、アクセス、周辺スポットを元データに合わせて増減する
- JSON-LDと本文を一致させる

## 3. 全件確認結果

2026-07-12にhotel関連の実ファイルを全件確認しました。

| 対象 | 件数 | 備考 |
|---|---:|---|
| `Text_hotel_data` txt | 3 | 更新手順1、ホテル元データ2 |
| hotel source HTML | 3 | 全件placeholderなし |
| hotel公開入口PHP | 3 | 全件同じ基本形式 |
| hotelページ別dataset PHP | 3 | 全件同じ基本形式 |
| `dataset_base.php` の現行hotel名case | 1 | villacosta500のみ |
| `dataset_base.php` の現行hotelリンク変換 | 1 | villacosta500のみ |

現在のsource HTML:

```text
greenrichkagoshimatenmonkan
hotelm
villacosta500
```

元テキストはgreenrichとvillacosta500の2件です。hotelmの元テキストは存在しません。

## 4. 必須ファイル構成

```text
HP/Text_hotel_data/<ホテル名>.txt
HP/source/template_kagoshima-deliveryhealth-hotel.html

HP/kagoshima-deliveryhealth-hotel-<slug>.php
HP/source/kagoshima-deliveryhealth-hotel-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-hotel-<slug>.php
HP/includefile/dataset_base.php
```

slugは元データのcanonical、画像名、ホテル名、既存ページを照合して決定します。元データにplaceholderがある場合は推測で確定しません。

## 5. 元データからHTMLへの対応

| 元データ項目 | HTML反映先 |
|---|---|
| title | title、OGP title |
| description | meta description、OGP description |
| canonical | canonical、OGP URL |
| img_1 | メイン画像、OGP image、alt |
| page_title_h1 | パンくず、h1 |
| subtitle_h1 | `subtitle_h1` |
| description_h1 | `description_h1` |
| 店舗指定 | 人気デリヘル店ブロック |
| FAQ | FAQブロック、FAQPage JSON-LD |
| img_2 | 基本情報側画像、alt |
| 基本情報 | ホテル名、公式URL、住所、電話、設備等の表 |
| 料金情報 | 料金表。未入力項目は削除判断 |
| アクセス情報 | 地図、交通、アクセス説明 |
| 周辺スポット | FAQ型の複数項目 |
| ページ全体 | JSON-LD 2～3ブロック |

本文は元データに改行指定がない場合、不要な表示改行を追加しません。

## 6. 新型の基本構造

greenrichとvillacosta500で確認した新型構造はscene 6個です。

1. 人気デリヘル店
2. よくあるご質問
3. 基本情報
4. 料金情報
5. アクセス情報
6. 周辺スポット

採番ルール:

- h2は上から `scene1` ～ `scene6`
- 通常ブロックは `subtitle_N`、`description_N`
- FAQは `subtitle_2_1`、`description_2_1` の形式
- 周辺スポットは `subtitle_6_1`、`description_6_1` の形式
- 各FAQ型セクションの最終項目だけ `class="faq-item bd_tb"`
- 最終項目以外は `class="faq-item bd_t"`
- 項目数に応じてHTMLを増減し、連番を振り直す

## 7. 店舗ブロック

- `HP/source/template_shop.html` の対応店舗ブロックを基準にする
- 元データが指定する店舗だけを設置する
- 移動時間と交通費を元データへ合わせる
- 店舗情報、リンク、計測要素を推測で変更しない

## 8. 未入力項目の処理

hotelでは情報が存在しない項目があります。

- FAQ項目が未入力なら該当FAQ項目を削除する
- 基本情報表の未入力行は、元データの指示と完成ページを確認して削除する
- 料金情報の未入力行は空欄で残さず、該当行を削除する
- セクション全体に情報がなければ、見出しを含めた削除可否をユーザーへ確認する
- 削除後はscene番号とJSON-LDを振り直す

### 8.1 情報量に応じたページ構成

hotelは、ホテルごとに情報量が違うことを前提とします。テンプレートの全項目を必ず表示する仕様ではありません。

| 情報の状態 | 新規ページでの処理 |
|---|---|
| FAQあり | FAQセクションを生成し、FAQPage JSON-LDも生成 |
| FAQなし | FAQセクションとFAQPage JSON-LDを削除 |
| 基本情報の一部なし | 該当表行を削除 |
| 料金情報の一部なし | 該当料金行を削除 |
| 料金情報が全てなし | セクション全体の省略をユーザー確認 |
| アクセス説明あり・地図あり | 両方を表示 |
| 地図情報なし | 推測で地図を作らず確認依頼 |
| 周辺スポットあり | 件数に合わせて生成 |
| 周辺スポットなし | セクション全体の省略をユーザー確認 |

セクションを省略した場合、新規ページでは残ったh2を表示順に `scene1` から振り直します。旧型ページの欠番は引き継ぎません。

空欄、placeholder、意味のない見出し、空のFAQコンテナを残してはいけません。

## 9. JSON-LD

新型2ページはJSON-LD 3ブロック、旧型hotelmは2ブロックです。全ブロックは構文解析できました。

新規ページでは新型を基準にします。

主な構造:

- BreadcrumbList
- FAQPage
- 店舗または周辺情報を表す構造化データ

確認事項:

- canonical、パンくず、ホテル名の一致
- FAQ本文とFAQPageの質問・回答・件数の一致
- 店舗・周辺スポット本文と構造化データの一致
- placeholderが0件
- JSON構文が正常

## 10. 公開入口PHPとdataset PHP

公開入口PHPはarea・blogと同じ基本形で、`dataset_base.php`を読み込みます。

hotel dataset PHP 3件は次の基本形です。

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

新規生成時はこの形式を使用します。既存PHPの構造変更は開発改修として分離します。

## 11. dataset_base.phpへの登録

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

現在はvillacosta500だけ登録済みです。greenrichとhotelmはcase・リンク変換とも未登録です。未登録でもdefault処理で表示できる可能性はありますが、通常生成では登録省略を許可しません。

## 12. 基本生成アルゴリズム

1. Git状態と対象範囲を確認する
2. 元txtの必須項目、未入力項目、canonical、slug、画像を確認する
3. 同名3ファイルとdataset_base登録の有無を確認する
4. hotelテンプレートと新型完成ページを比較する
5. source HTMLを生成し、SEO、OGP、h1、画像、導入文を反映する
6. `template_shop.html`から指定店舗ブロックを反映する
7. FAQを元データの件数に合わせて構成する
8. 基本情報、料金、アクセスを反映し、未入力行を処理する
9. 周辺スポットを件数に合わせて構成する
10. scene、subtitle、descriptionを上から再採番する
11. JSON-LDを本文と同期する
12. 公開入口PHPとdataset PHPを生成する
13. dataset_baseのcaseとリンク変換を登録する
14. placeholder、重複ID、欠番、空欄項目を検査する
15. canonical、画像、公式URL、地図、内部リンクを検査する
16. `source/hotel.html` の一覧リンク・JSON-LDと `sitemap.xml` への登録要否を確認する
17. PHP構文、JSON構文、差分を確認する
18. ブラウザ未確認の場合は明記する
19. 明示指示がある場合だけCommit・Pushする

## 13. 例外・注意事項

### 13.1 hotelmは旧型

hotelmはmain sceneが5個、JSON-LDが2ブロックで、FAQセクションがない旧型構造です。IDは `scene1, scene2, scene3, scene4, scene6` となっており欠番があります。新規ページの複製元には使用せず、greenrichまたはvillacosta500の新型を基準にします。FAQを省略する新規ページでも、残ったsceneは連番にします。

### 13.2 元テキスト自体にplaceholderがある

villacosta500元テキストにはslug、画像、URL等のplaceholderが残ります。既存完成HTMLは完成していますが、元テキストをそのまま新規生成へ流用してはいけません。

### 13.3 更新手順txtは旧情報を含む可能性がある

`Text_hotel_data/Cursor用更新手順.txt` は存在しますが、Codex管理の正本はこの資料です。両者が矛盾する場合は作業を停止してユーザーへ報告します。

### 13.4 元テキストがない完成ページ

hotelmは元テキストがありません。構造参考は可能ですが、内容生成の根拠にはしません。

## 14. 完了チェック

- [ ] 元テキスト、ホテル名、slug、canonicalが確定している
- [ ] 未入力項目の処理が確定している
- [ ] 情報量に応じて行・FAQ・セクションを増減した
- [ ] 省略したセクションのHTML・ID・JSON-LDが残っていない
- [ ] 公開PHP、source HTML、dataset PHPが存在する
- [ ] dataset_baseのcaseとリンク変換が存在する
- [ ] placeholderが0件
- [ ] scene、FAQ、周辺スポットの採番が正しい
- [ ] FAQ本文とFAQPage JSON-LDが一致する
- [ ] FAQがない場合、FAQPage JSON-LDも存在しない
- [ ] ホテル名、公式URL、住所、地図の対応が正しい
- [ ] 画像が実在する
- [ ] canonical、OGP、内部リンクが正しい
- [ ] robotsが公開方針と一致する
- [ ] hotel一覧とsitemapへの登録要否を確認した
- [ ] 重複IDがない
- [ ] PHP構文、JSON構文、`git diff --check`を確認した

## 15. 変更していないもの

この調査では、既存hotelページ、PHP、dataset PHP、`dataset_base.php`、画像、元テキストを変更していません。
