# CANDY AREA PAGE GENERATION SPEC

更新日: 2026-07-14
対象: 鹿児島キャンディのarea詳細ページをCodexが通常運用で新規生成する場合

## 1. この資料の目的

areaページを壊さず、毎回同じ構成で生成するための正本仕様です。

この資料は通常の新規ページ生成に使用します。不具合修正、既存機能変更、共通処理変更、リファクタなどの開発改修は対象外です。開発改修では `AGENTS.md` と `HP/AGENTS.md` の調査・変更・修正ルールを使用してください。

共通の入力不足・可変構造・停止条件は `CANDY_PAGE_GENERATION_GOVERNANCE.md` を先に適用します。

area画像の受入、slug照合、公開用正本への配置、Git管理は `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` を必ず確認してください。

スタッフが未作成areaページを分割制作する場合は、`CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` と `CANDY_AREA_105_PAGE_QUEUE.md` も必ず確認してください。

`HP/codex/area/AREA_PAGE_CREATION_WORKFLOW.md`、`AREA_PAGE_MASTER.md`、`AREA_NEXT_ACTIONS.md` は2026-06-05時点の履歴・調査資料です。現在の通常生成では、この仕様書と共通ガバナンスを正本とします。

## 2. 絶対ルール

- 元データは `HP/Text_area_data` 配下の対象地域テキストを使用する
- HTMLテンプレートは `HP/source/template_kagoshima-deliveryhealth-area.html` を使用する
- 公開入口PHP、source HTML、ページ別dataset PHP、`dataset_base.php`への登録を1セットとして扱う
- HTMLだけを生成して完了としてはいけない
- `HP/create.php` は通常のCodexページ生成では原則使用しない
- 既存の同名ファイルが1つでもある場合は上書きせず、既存3点セットと登録状態を確認する
- `dataset_base.php` は共通重要ファイルのため、対象と差分を事前提示し、ユーザーの実行指示または承認後に最小差分で変更する

## 3. 確認した対象と件数

2026-07-12に実ファイルを全件読み取り、次を確認しました。

| 対象 | 件数 | 備考 |
|---|---:|---|
| `Text_area_data` 配下txt | 169 | 直下135、Completion 32、Backup 2 |
| ページURLを取得できたtxt | 168 | 更新手順txt 1件を除く |
| area公開候補source HTML | 61 | 完成34、placeholder残存27 |
| area公開入口PHP | 71 | sourceがないもの10件を含む |
| areaページ別dataset PHP | 71 | sourceがないもの10件を含む |
| `dataset_base.php` のarea case登録 | 39 | source 61件中31件が未登録 |
| `dataset_base.php` のareaリンク変換 | 39 | source 61件中31件が未登録 |

件数は将来変わるため、新規作成時に再集計してください。

## 4. ファイル対応

ページ識別子を `<slug>` とした場合、必須構成は次です。

```text
元データ
HP/Text_area_data/.../<地域名>_テンプレート.txt

HTMLテンプレート
HP/source/template_kagoshima-deliveryhealth-area.html

生成する3ファイル
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php

追加登録
HP/includefile/dataset_base.php
```

slugは推測だけで決めません。元データのcanonical、ファイル名、既存ページ名を照合してください。同一地域に旧slugや別表記がある場合はユーザー確認が必要です。


### 4.1 txt分類と新規制作可否の分離

`間違い無し`、`画像無し`、`情報足りない` などのtxt分類は、入力内容の状態だけを示す。新規ページとして制作可能かどうかは別判定である。

新規制作可否は、canonical slugに対して公開PHP、source HTML、ページ別dataset PHP、`dataset_base.php`、sitemapに既存ファイルまたは既存登録がないこと、area一覧に対象slugのリンクが1件あること、area一覧に同一地域名で別slugのリンクがないことを確認して初めて成立する。分類OKだけで対象を選ばない。
## 5. 元データからHTMLへの基本対応
| 元データ項目 | HTML反映先 |
|---|---|
| title | `<title>`、必要に応じてOGP title |
| description | meta description、OGP description |
| canonical | canonical、OGP URL |
| image | OGP image |
| img_1 | メイン画像src、alt |
| page_title_h1 / パンくず | breadcrumb、h1 |
| subtitle_h1 | `id="subtitle_h1"` |
| description_h1 | `id="description_h1"` |
| 店舗一覧 | `scene1`内の店舗ブロック |
| img_2 | 地域紹介画像src、alt |
| 地域紹介 | scene、subtitle、description |
| 地図URL・地図タイトル | iframeのsrc・title |
| 人口・面積・設置年月日 | 基本情報表 |
| ホテル情報 | FAQブロック |
| 待ち合わせ・周辺スポット | FAQブロック |
| 関連記事 | 将来利用する予約領域としてテンプレートのダミーリンク8件を保持 |
| ページ全体情報 | JSON-LD 2ブロック |

本文は、元データに明示された改行以外は不要な改行を追加しません。HTMLタグとして必要な改行と、表示文中の改行を混同しないでください。

## 6. scene・subtitle・descriptionの採番

完成ページで確認した基本構造はscene 5個です。

1. `scene1`: 人気デリヘル店情報
2. `scene2`: 地域紹介
3. `scene3`: 地域基本情報
4. `scene4`: 近辺ホテル・宿泊施設
5. `scene5`: 待ち合わせ・周辺スポット

通常ブロックは次の形式です。

```text
scene1 / description_1
scene2 / subtitle_2 / description_2
scene3 / description_3
```

複数項目を持つFAQは次の形式です。

```text
scene4
subtitle_4_1 / description_4_1
subtitle_4_2 / description_4_2

scene5
subtitle_5_1 / description_5_1
subtitle_5_2 / description_5_2
```

- sceneは上から連番にする
- FAQ内の項目番号も上から連番にする
- 重複ID、欠番、別scene番号の混入を禁止する
- ホテルやスポットの件数に応じてFAQブロックを増減する
- 各FAQセクションの最終項目だけ `class="faq-item bd_tb"` とする
- 最終項目以外は `class="faq-item bd_t"` とする

完成34ページではscene数はすべて5でした。FAQ数は5件のページが2件、6件が31件、7件が1件であり、FAQ件数は固定ではありません。

## 7. 店舗ブロック

- 店舗情報は `HP/source/template_shop.html` の対応店舗ブロックを基準にする
- 元データが指定する店舗だけを配置する
- 移動時間と交通費は元データへ合わせる
- 店舗ブロックの共通構造、リンク、計測要素を不用意に変更しない
- 元データにない店舗情報を推測で追加しない

### 7.1 関連記事の予約領域

- `関連記事` セクションは将来実リンクへ置換するため、生成時に削除しない
- 現段階ではテンプレートのダミーリンク8件を、文言・`href="#"`・順序を変えずに残す
- この8件だけを許可済みplaceholderとし、同じダミーが予約領域外にあれば検証エラーとする
- 実関連記事が設定済みのページは、ダミー8件の代わりに実リンクが1件以上あれば正常とする
- 実リンクの選定・置換ルールが確定するまでは、推測した関連記事を設定しない

## 8. JSON-LD

areaテンプレートと全area source HTMLにはJSON-LDが2ブロックあります。

- BreadcrumbList
- 店舗一覧を表すItemList系ブロック

生成時は地域名、URL、position、店舗名、電話、店舗URL、descriptionをHTML本文と一致させます。

確認手順:

- placeholderを残さない
- positionを数値にする
- JSONとして構文解析できることを確認する
- breadcrumbの階層とURLを本文のパンくずに合わせる
- 店舗数とItemList要素数を合わせる

現在の完成34ページはJSON-LDを構文解析できました。placeholder残存27ページは両JSON-LDブロックが未完成です。

## 9. 公開入口PHP

既存area公開入口PHP 71件は同一の基本形です。

```php
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データセット基本ファイル読込
include("/home/firststar/public_html/group_test/candy/includefile/dataset_base.php");


?>
```

新規生成時は同カテゴリの現行完成ページを再確認し、同じ形式で作成します。サーバーパスを推測で変更しません。

## 10. ページ別dataset PHP

既存area dataset PHP 71件は同一の基本形です。

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

新規生成時は同カテゴリの現行完成ページを複製元として確認します。短縮開始タグを通常タグへ変更するなどの開発改修は、新規ページ生成へ混ぜません。

## 11. dataset_base.phpへの必須登録

通常の新規ページ生成では、次の2箇所を登録します。

### 11.1 dataset振り分け

```php
case 'kagoshima-deliveryhealth-area-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-<slug>.php');
    break;
```

### 11.2 HTMLリンクからPHPリンクへの変換

```php
$source = str_replace(
    'kagoshima-deliveryhealth-area-<slug>.html',
    'kagoshima-deliveryhealth-area-<slug>.php',
    $source
);
```

case、source HTML、dataset PHP、公開PHPのslugは完全一致させます。

未登録でも `dataset_default.php` がsource HTMLを読み込むため表示できる可能性がありますが、通常生成仕様として登録省略を許可しません。

## 12. 基本生成アルゴリズム

1. Gitのブランチ、作業ツリー、リモート状態を確認する
2. 対象txtの地域名、slug、canonical、画像、全入力項目を確認する
3. 同名の公開PHP、source HTML、dataset PHP、dataset_base登録の有無を確認する
4. 同カテゴリの完成ページを1件以上比較対象にする
5. areaテンプレートを新しいsource HTMLとして複製する
6. SEO、OGP、パンくず、h1、画像、本文、地図、基本情報を反映する
7. `template_shop.html`から指定店舗ブロックを反映する
8. ホテル・スポット件数に合わせてFAQブロックを増減する
9. scene、subtitle、descriptionを上から再採番する
10. JSON-LD 2ブロックを本文と一致させる
11. 公開入口PHPを生成する
12. ページ別dataset PHPを生成する
13. `dataset_base.php`の2箇所へ登録する
14. 内部リンク、画像、canonical、OGP、slugを確認する
15. 関連記事の予約ダミー8件を除くplaceholder、未入力、旧slug、重複IDを検査する
16. PHP構文、JSON構文、`git diff --check`、変更対象を確認する
17. `source/area.html` の一覧リンク・JSON-LDと `sitemap.xml` への登録要否を確認する
18. 実ブラウザ確認を行っていない場合は、ブラウザ表示未確認と報告する
19. ユーザー確認後、明示指示がある場合だけCommit・Pushする

## 13. 例外処理

areaでも元データの情報量をテンプレートの固定件数へ合わせてはいけません。ホテル、スポット、店舗の件数を元データに合わせ、追加・削除後にID、最終FAQ class、JSON-LD ItemListを同期します。

### 13.1 FAQ件数が異なる

ホテル・スポットは固定3件ではありません。元データの件数に合わせて追加・削除し、各セクションの最終FAQだけ `bd_tb` にします。

### 13.2 同一地域に複数slugがある

既存には、同じ地域を指す可能性がある別slugが存在します。新規作成時はcanonical、ファイル名、既存リンク、ユーザー指定を確認し、勝手に統合・削除・リネームしません。

確認対象例:

- `hananohikarigaoka` と `kenohikarigaoka`
- `kiireikkuracho` と `kiirehitokuracho`

### 13.3 元データの保管場所と完成状態が一致しない

- `Completion`内でもsource HTMLにplaceholderが残る例がある
- 直下の元データでも完成source HTMLが存在する例がある
- フォルダ名だけで完成・未完成を判定しない

### 13.4 画像が存在しない

完成HTMLでも参照画像が見つからない例があります。生成時は `_1` と `_2` の実ファイル存在を確認します。

新規areaページの依頼時に必要画像がない場合は、ページ作成を停止してユーザーへ報告し、次の正式名で画像提供を依頼します。

```text
kagoshima-deliveryhealth-area-<slug>_1.jpg
kagoshima-deliveryhealth-area-<slug>_2.jpg
```

ユーザー承認なしに、既存画像の流用、ダミー画像の使用、画像名の推測、画像なしでの公開をしてはいけません。受領後に形式、サイズ、slug、2枚の揃い、重複を確認してから適用します。

### 13.5 既存3点セットの一部または登録だけが欠ける

新規作成ではなく既存不整合の修正として扱います。通常生成と混ぜず、影響範囲を提示して承認を得ます。

## 14. 現在確認済みの不整合

### 14.1 placeholder残存source HTML 27件

```text
gionnosucho, gofukucho, gokabeppucho, hananohikarigaoka,
kasugacho, kibougaokacho, kiirecho, kiireikkuracho,
kiirenakamyocho, kinkodai, kinseicho, koraicho,
korimoto, korimotocho, koriyamacho, koriyamadakecho,
kotsukicho, koutokujidai, koyamadacho, koyo,
oroshihommachi, sakamotocho, sakanoue, sakuragaoka,
sanwacho, shimofukumotocho, shimotatsuocho
```

各ページで主要placeholderが79件残り、JSON-LD 2ブロックも構文不正です。

### 14.2 source HTMLはあるがdataset_base登録がない31件

```text
ariyadacho, hananohikarigaoka, ikenouecho, inaricho,
inusakocho, irisacho, ishidanicho, ishiki, ishikidai,
izumicho, kajiyacho, kamoike, kamoikeshinmachi,
kenohikarigaoka, kiirehitokuracho, kiireikemicho,
kiireikkuracho, kiiremaenohamacho, kiirenakamyocho,
kiiresesekushicho, obaracho, ogawacho, okanoharacho,
ono, oroshihommachi, uearatacho, uenosonocho,
uomicho, usuki, yakushi, yasuicho
```

同じ31件はHTMLリンクからPHPリンクへの変換登録もありません。

### 14.3 source HTMLがない公開PHP・dataset PHP 10件

```text
hirakawacho, kamifukumotocho, kamihonmachi,
kamitaniguchicho, kamitatsuocho, kawadacho,
kawakamicho, kiirenakamyoch（誤記候補）,
komatsubara, shimizucho
```

実ファイル名には `kiirenakamyoch` が存在します。`kiirenakamyocho` と混同せず、削除・統合はユーザー承認なしに行いません。

### 14.4 その他

- `oroshihommachi`: Completion元データがあるがsource HTMLにplaceholderが残る
- `shimotacho`: 直下元データだがsource HTMLは完成状態
- `arata` と `kinkocho`: `id="button_1"` が重複
- 完成HTMLの画像参照不足候補: `inusakocho`、`kenohikarigaoka` の `_1`・`_2`

これらは確認済みの現状であり、この仕様書作成作業では修正していません。

## 15. 完了判定チェックリスト

- [ ] 元データの全必須項目が入力済み
- [ ] 地域名とslugが確定済み
- [ ] 同名ファイル・旧slug・類似slugを確認済み
- [ ] 公開PHP、source HTML、dataset PHPが存在する
- [ ] dataset_baseのcase登録が存在する
- [ ] dataset_baseのリンク変換登録が存在する
- [ ] 関連記事領域に予約ダミー8件または実リンクがあり、予約ダミーが領域外にない
- [ ] 関連記事予約ダミー以外のplaceholderが0件
- [ ] scene、subtitle、descriptionに重複・欠番がない
- [ ] 店舗・ホテル・スポットの本文件数とJSON-LD件数が一致する
- [ ] 元データにない店舗・ホテル・スポットを推測で追加していない
- [ ] FAQ最終項目のclassが正しい
- [ ] canonical、OGP URL、画像、パンくず、h1が一致する
- [ ] JSON-LD 2ブロックが本文と一致し、構文解析できる
- [ ] 画像 `_1`・`_2` が実在する
- [ ] 画像不足時に既存画像、ダミー画像、推測した画像名を使用していない
- [ ] 内部リンクが公開PHPを指す
- [ ] robotsが公開方針と一致する
- [ ] area一覧とsitemapへの登録要否を確認した
- [ ] PHP構文確認済み
- [ ] `git diff --check` 成功
- [ ] 変更対象外ファイルを変更していない
- [ ] ブラウザ未確認の場合、その旨を報告した
- [ ] 元txtを勝手に移動・削除していない
- [ ] ローカル完成と本番反映を分けて報告した

## 16. 変更していないもの

この仕様調査では、公開PHP、source HTML、dataset PHP、`dataset_base.php`、画像、元テキストを変更していません。
