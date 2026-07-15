# CANDY HOTEL PAGE GENERATION SPEC

更新日: 2026-07-15
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
- 店舗、通常記事scene、FAQ、基本情報の任意行、料金行、アクセス、周辺スポットは元データの完成ブロック数に合わせ、固定上限を設けない
- 通常記事sceneと既知セクションは入力順で保持し、部分入力ブロックは生成前に停止する
- 「関連記事」は実リンク設定まで予約ダミー8件を保持する
- JSON-LDと本文を一致させる

標準制作・公開は次の専用コマンドだけを実行します。

```powershell
HP\codex\scripts\candy-hotel.cmd publish --input "HP/Text_hotel_data/対象ホテル.txt"
```

専用ツールは生成、検証、対象限定stage、1 Commit、1 Push、Actions、本番HTTP確認、URL出力を連続実行します。

## 3. 全件確認結果

2026-07-15にhotel関連の実ファイルを全件確認しました。

| 対象 | 件数 | 備考 |
|---|---:|---|
| Text_hotel_data txt | 3 | 更新手順1、ホテル元データ2 |
| hotel source HTML | 3 | 全件placeholderなし |
| hotel公開入口PHP | 3 | 全件同じ基本形式 |
| hotelページ別dataset PHP | 3 | 全件同じ基本形式 |
| dataset_base.php の現行hotel名case | 1 | villacosta500のみ |
| dataset_base.php の現行hotelリンク変換 | 1 | villacosta500のみ |

完成HTMLの情報量は同一ではありません。

| 完成HTML | 旧option | 通常記事scene | 店舗 | FAQ | 料金行 | アクセス | スポット | 関連記事 | ItemList |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---|
| greenrichkagoshimatenmonkan | 0 | 0 | 4 | 4 | 2 | あり | 4 | 8 | スポット |
| hotelm | 0 | 0 | 4 | 0 | 3 | あり | 3 | 8 | 店舗 |
| villacosta500 | 1 | 0 | 4 | 4 | 4 | あり | 3 | 8 | 店舗 |

この件数は実物の観測値であり、新規ページの固定要件ではありません。共通して固定されていたのは関連記事ダミー8件です。hotelmの元テキストは存在せず、greenrichとvillacosta500だけに元テキストがあります。

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

| 元データ項目 | HTML反映先 | 件数 |
|---|---|---|
| title、description、canonical | SEO、OGP | 各1、必須 |
| img_1、img_2 | メイン画像、基本情報側画像 | 各1、必須 |
| page_title_h1、subtitle_h1、description_h1 | パンくず、h1、導入文 | 各1、必須 |
| option一式 | 旧型の独立案内 | 0または1 |
| 通常のscene（h2） | 通常記事ブロック | 0件以上 |
| 店舗指定 | 人気デリヘル店ブロック | 1件以上 |
| FAQ | FAQ本文、FAQPage JSON-LD | 0件以上 |
| 基本情報 | ホテル名、公式URL、住所と任意行 | 必須3項目＋任意行 |
| 料金情報 | 料金表と任意の補足文 | 0件以上 |
| アクセス情報 | 地図、地図title、subtitle、description | 0または1 |
| 周辺スポット | 複数項目と任意の注意文 | 0件以上 |
| 関連記事 | 予約ダミー | 8件固定 |

本文は元データに改行指定がない場合、不要な表示改行を追加しません。

## 6. 可変構造と採番

hotelページを6scene固定として扱いません。入力に存在する完成ブロックだけを表示し、表示順をそのまま保持します。

必須:

- SEO、OGP、img_1、img_2、h1導入
- ホテル名、公式URL、住所
- template_shop.htmlに存在する店舗を1件以上
- 関連記事ダミー8件

任意:

- 旧option一式は0または1。option、option_subtitle、option_descriptionの3項目がそろう場合だけ表示する
- 通常記事scene、FAQ、料金行、周辺スポットは0件以上
- アクセスは0または1。存在する場合は地図URL、地図title、subtitle、descriptionの全項目を必要とする
- 基本情報の電話、部屋・駐車場、支払方法
- 料金補足文、周辺スポット注意文

採番:

- 旧optionはid=optionを使い、scene採番に含めない
- それ以外のh2は表示順にscene1から連番にする
- 通常ブロックはsubtitle_N、description_N
- FAQと周辺スポットはsubtitle_N_M、description_N_M
- FAQ型の最終項目だけclass=faq-item bd_tb、それ以外はclass=faq-item bd_t
- セクションや項目の増減後に欠番・重複IDを残さない

既知セクションは、入力内での出現順を保持します。通常記事sceneは既知セクションの前後に置けます。

## 7. 店舗ブロック

- `HP/source/template_shop.html` の対応店舗ブロックを基準にする
- 元データが指定する店舗だけを設置する
- Textに移動時間と交通費がある場合は、その値を最優先する
- Textで未指定の場合だけ、ホテル地図の座標から店舗別に最も近い完成areaページを選び、その移動時間と交通費を使用する
- 近隣参照元は公開時の依存ファイルへ含め、座標または参照可能な完成ページがなければ推測せずSTOPする
- 店舗情報、リンク、計測要素を推測で変更しない

## 8. 未入力項目の処理

未入力と部分入力を区別します。

| 状態 | 処理 |
|---|---|
| 通常記事sceneなし | 生成しない |
| FAQなし | FAQ本文とFAQPageを生成しない |
| 基本情報の任意行なし | その行を生成しない |
| 料金行なし | 料金セクションを生成しない |
| アクセス一式なし | アクセスセクションを生成しない |
| 周辺スポットなし | スポットセクションを生成しない |
| 完成した任意ブロックあり | 入力件数・入力順で全件生成 |
| subtitleだけ、descriptionだけ、アクセス一部だけ | 推測せずSTOP |
| 料金補足文だけ、スポット注意文だけ | 対象本体がないためSTOP |

0件の任意セクションは、ユーザー確認を挟まず省略します。空欄、placeholder、意味のない見出し、空コンテナは残しません。省略後はscene番号とJSON-LDを自動で振り直します。

## 9. JSON-LD

ブロック数を固定しません。

- BreadcrumbListは必須
- FAQが1件以上ある場合だけFAQPageを生成し、本文と質問・回答・件数を一致させる
- 周辺スポットが1件以上ある場合、ItemListはスポットを使用する
- 周辺スポットが0件の場合、ItemListは店舗を使用する
- ItemListの件数、順序、name、URLは本文と一致させる
- placeholderを残さず、全JSONを構文解析する

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

現在はvillacosta500だけ登録済みです。greenrichとhotelmはcase・リンク変換とも未登録です。通常生成ではcaseとリンク変換の登録省略を許可せず、未登録ページを公開可能とは判定しません。

## 12. 基本生成アルゴリズム

1. Git状態と対象範囲を確認する
2. 元txtの必須項目、canonical、slug、画像、placeholderを確認する
3. 同名3ファイルと共有登録の重複を確認する
4. 入力ブロックを種類別に解析し、通常記事sceneを含む全体順序を記録する
5. 完成ブロックだけを生成し、0件の任意セクションは生成しない
6. template_shop.htmlから指定店舗を全件反映する
7. Text未指定の移動時間・交通費だけ、ホテル座標と近隣完成areaページから設定する
8. 旧optionが完全なら独立表示し、通常sceneへ混ぜない
9. FAQ、基本情報任意行、料金、アクセス、周辺スポットを入力件数で生成する
10. scene、subtitle、descriptionを表示順に再採番する
11. FAQPageとItemListを本文の有無・件数・順序に同期する
12. 公開入口PHP、source HTML、dataset PHP、共有登録、hotel一覧、sitemapを対象限定で生成する
13. placeholder、空コンテナ、重複ID、欠番、本文欠落を検査する
14. canonical、画像、公式URL、地図、内部リンク、PHP、JSON、差分を検査する
15. 明示されたpublishでは対象限定Commit、main Push、Actions、本番HTTP確認まで実行する

## 13. 例外・注意事項

### 13.1 hotelmは旧型

hotelmはFAQなし、料金3行、周辺スポット3件の旧型構造です。IDはscene1, scene2, scene3, scene4, scene6と欠番があります。この情報量は有効なパターンとして扱いますが、旧IDは複製せず、新規ページでは残ったsceneを連番にします。

### 13.2 元テキスト自体にplaceholderがある

villacosta500元テキストにはslug、画像、URL等のplaceholderが残ります。既存完成HTMLは完成していますが、元テキストをそのまま新規生成へ流用してはいけません。

### 13.3 更新手順txt

Text_hotel_data/Cursor用更新手順.txtもhotel可変構造へ更新し、この仕様と一致させます。件数の判断は対象Textの完成ブロックを正とします。

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
- [ ] 完成した通常sceneの見出し・追加文章が欠落していない
- [ ] scene、FAQ、周辺スポットの採番が正しい
- [ ] 関連記事の予約ダミーが正確に8件あり、予約領域外に残っていない
- [ ] 移動時間・交通費はText優先、未指定時だけ地図座標と近隣完成areaページを参照している
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
