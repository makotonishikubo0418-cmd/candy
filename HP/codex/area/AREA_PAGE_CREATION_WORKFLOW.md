# AREA_PAGE_CREATION_WORKFLOW

作成日: 2026-06-05
対象: H:\Data\01_CTI\candy_HP

## 結論

対応エリアページの新規作成は、create.phpをブラウザで実行するのではなく、Codexが同等処理を直接行います。ローカル作成、画像設置、JSON-LD検証、dataset_base.php反映、本番URL確認は別工程として必ず分けて確認してください。

## ユーザーからの最小依頼形式

今後、ユーザーからは次の3点だけ提供されれば対応可能です。それ以外の詳細手順、確認項目、禁止事項、報告形式はCodex側で保持・適用します。

```text
下田町_テンプレート.txt
https://www.55810.com/kagoshima-deliveryhealth-area-shimotacho.php
新規作成
```

この形式で依頼された場合、Codexはテンプレートファイル名から `Text_area_data\下田町_テンプレート.txt` を参照し、URLからslugを確定し、以降の作成・検証・報告を本資料の手順で行います。

## 使う元データ

| 種別 | 確認先 | 例 |
| --- | --- | --- |
| エリア本文 | H:\Data\01_CTI\candy_HP\Text_area_data\*_テンプレート.txt | Text_area_data\永吉_テンプレート.txt |
| HTMLテンプレート | H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-area.html | 対応エリア詳細ページの基礎 |
| 店舗ブロック | H:\Data\01_CTI\candy_HP\source\template_shop.html または既存完成ページ | CANDY、ラブ♡エル、after5、CANDY BELOVEDなど |
| 既存完成例 | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-*.html | 構造・class・FAQ・関連リンクの確認 |
| 画像 | H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area | {slug}_1.jpg / {slug}_2.jpg |

## 作成するファイル

| 役割 | パス |
| --- | --- |
| 公開PHP | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-{slug}.php |
| source HTML | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-{slug}.html |
| area dataset | H:\Data\01_CTI\candy_HP\includefile\dataset_kagoshima-deliveryhealth-area-{slug}.php |
| 共通ルーティング | H:\Data\01_CTI\candy_HP\includefile\dataset_base.php |

## 実行前チェック

1. 対象slugを確定する。例: 永吉 -> nagayoshi。
2. 対象URLを確定する。例: https://www.55810.com/kagoshima-deliveryhealth-area-nagayoshi.php。
3. 同名の公開PHP、source HTML、area datasetが既に存在しないか確認する。
4. 同じURLに対応する公開PHP、source HTML、dataset_base.php caseが既に存在する場合は、新規作成を中止してユーザーへ報告する。既存URLがある状態では上書き・再作成しない。
5. Text_area_data内の対象テンプレートが存在するか確認する。
6. area画像2点がローカルに存在するか確認する。
7. source\area.html から対象ページへリンクがあるか確認する。
8. dataset_base.phpに同じcaseやstr_replaceが既にないか確認する。
9. dataset_base.phpを編集する前にバックアップを作る。

## 既存URLがある場合の対応

対象URLに対応するルートPHP、source HTML、area dataset、またはdataset_base.phpのcase/str_replaceが既にある場合は、作成しません。次の形式で報告します。

```text
作成しません。既に同URLのページが存在します。

確認済み：
- 公開PHP: あり/なし
- source HTML: あり/なし
- area dataset: あり/なし
- dataset_base.php case: あり/なし
- dataset_base.php .html→.php変換: あり/なし

既存URL：
https://www.55810.com/kagoshima-deliveryhealth-area-{slug}.php
```

既存URLがある場合でも、source HTMLの中身まで確認します。`aaaaaaaa`、`href="#"`、`ここにはリンク先`、Map placeholder、JSON-LD placeholder が残っている場合は「既存だが未完成」と報告し、重複作成はしません。

ユーザーから「上書き」「修正」「既存ページを更新」、または「内容を見て完成されていない」と明示された場合のみ、別作業として既存ファイルの完成更新を行います。この場合も公開PHP、area dataset、dataset_base.php が既に正しいなら再作成せず、未完成の source HTML だけをテンプレートデータで完成させます。

## 作成手順

1. 公開PHPは既存の薄いラッパー形式に合わせ、dataset_base.phpをincludeする。
2. area datasetは既存エリアdatasetと同じ最小処理にする。
3. source HTMLは source\template_kagoshima-deliveryhealth-area.html を基礎にする。
4. Text_area_dataのtitle、description、canonical、画像、h1、本文、店舗、Map、ホテル、スポットをHTMLへ流し込む。
5. 店舗ブロックはtemplate_shop.htmlまたは既存完成ページから取得し、対象エリアの移動時間・交通費へ差し替える。
6. JSON-LDはplaceholderを部分置換せず、BreadcrumbListとItemListを完成形として作り直す。
7. 関連リンクは `#` や `ここにはリンク先` を残さず、実在する内部リンクへ置換する。
8. dataset_base.phpに `case '{page_name}.html'` と `.html` -> `.php` のstr_replaceを追加する。

## 画像チェック

ローカルに画像があることと、source HTMLにimgがあることは別確認です。

| 確認 | 判定 |
| --- | --- |
| H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area\{slug}_1.jpg が存在する | ローカル画像あり |
| H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area\{slug}_2.jpg が存在する | ローカル画像あり |
| source HTML内に `{slug}_1.jpg` と `{slug}_2.jpg` がある | HTML設置済み |
| https://www.55810.com/imgHtml/new_202601/area/{slug}_1.jpg が200 | 本番画像反映済み |
| https://www.55810.com/imgHtml/new_202601/area/{slug}_2.jpg が200 | 本番画像反映済み |

重要: ローカル画像が存在しても、本番URLが404なら本番サーバーへの反映・アップロードが未完了です。コード上のimg設置漏れとは分けて扱います。

## JSON-LDチェック

1. source HTML内の `application/ld+json` をすべて抽出する。
2. JSON.parse相当で構文検証する。
3. BreadcrumbListの3階層を確認する。
4. ItemListの店舗数、店舗名、電話番号、URL、descriptionを確認する。
5. `aaaaaaaa`、未入力、position placeholderを残さない。

## placeholder・リンクチェック

最低限、次の文字列が新規source HTMLに残っていないことを確認します。

```text
aaaaaaaa
____link____
ここにはリンク先
TODO
sample
dummy
./#shopinfo
href="#"
```

ボタンリンクは、同一ページ内店舗ブロックなら `#scene1`、対応エリア一覧へ戻すなら `./area.php` を使います。

## 公開反映チェック

ローカル作成後に、公開URLは必ず別途確認します。

| URL | 意味 |
| --- | --- |
| https://www.55810.com/kagoshima-deliveryhealth-area-{slug}.php | ページ本体の本番反映 |
| https://www.55810.com/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-{slug}_1.jpg | メイン画像の本番反映 |
| https://www.55810.com/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-{slug}_2.jpg | エリア説明画像の本番反映 |

404の場合は、ローカル作成済みでも本番未反映です。FTP、同期、サーバー側配置、公開ドキュメントルートのどこで反映するかを別途確認してください。

## noindex

source HTMLの `<meta name="robots" content="noindex">` は既存テンプレートに合わせて維持します。公開時に解除するかはユーザー指示がある場合のみ判断します。

## 永吉ページで確認した実例

| 項目 | 結果 |
| --- | --- |
| slug | nagayoshi |
| 公開PHP | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-nagayoshi.php |
| source HTML | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-nagayoshi.html |
| area dataset | H:\Data\01_CTI\candy_HP\includefile\dataset_kagoshima-deliveryhealth-area-nagayoshi.php |
| dataset_base.php | case / str_replace 追加済み |
| ローカル画像 | _1.jpg / _2.jpg とも存在 |
| HTML内画像 | _1.jpg / _2.jpg とも設置済み |
| JSON-LD | BreadcrumbList / ItemList の2件、構文確認済み |
| placeholder | なし |
| 本番URL | 2026-06-05時点でページ本体と画像2点が404 |

## 完了条件

1. 公開PHP、source HTML、area datasetが存在する。
2. dataset_base.phpにcaseとstr_replaceがある。
3. source HTMLにplaceholderが残っていない。
4. area画像2点と店舗画像がローカルに存在する。
5. JSON-LDが構文エラーなし。
6. canonical、OGP、title、description、h1が対象エリアと一致する。
7. Map title/srcが対象エリアと一致する。
8. 関連リンクとボタンリンクにplaceholderや不自然な `#` がない。
9. 本番URL確認結果を、ローカル作成結果とは別に記録する。
