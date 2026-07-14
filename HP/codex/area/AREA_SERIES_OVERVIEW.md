# AREA_SERIES_OVERVIEW

> **旧資料:** 現行正本ではありません。area作業は `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` が指定する現行資料を使用してください。固定パス・件数・手順は現在値として使用しません。

作成日: 2026-06-05
対象: H:\Data\01_CTI\candy_HP

## 結論

kagoshima-deliveryhealth-area- シリーズは、公開 PHP 71 件、source HTML 61 件です。PHP/HTML対応ありが 61 件、source HTMLなしが 10 件あります。2026-06-05に永吉ページを新規作成し、dataset_base.phpへルーティングを追加しました。

## 対象ファイル規則

| 項目 | 内容 |
| --- | --- |
| 対象シリーズ | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-*.php / H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-*.html |
| テンプレート | H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-area.html |
| 公開PHP件数 | 71 |
| source HTML件数 | 61 |
| PHP/HTML対応あり | 61 |
| source HTMLなし | 10 |
| 公開PHPなし | 0 |
| 共通CSS | H:\Data\01_CTI\candy_HP\css\default.css / H:\Data\01_CTI\candy_HP\source\style.css |
| 共通JS | H:\Data\01_CTI\candy_HP\js\common.js / 外部jQuery / Google tag |
| 画像フォルダ | H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area / H:\Data\01_CTI\candy_HP\imgHtml\new_202601\shop |
| 生成関係 | ルート直下PHP -> H:\Data\01_CTI\candy_HP\includefile\dataset_base.php -> source HTML -> dataset処理 -> HTML出力 |

## 必須資料確認

| 資料 | 確認結果 | 備考 |
|---|---|---|
| H:\Data\01_CTI\candy_HP\AGENTS.md | 未確認 | 物理ファイルは存在しませんでした。チャット冒頭のAGENTS指示と H:\Data\01_CTI\candy_HP\codex\docs\AGENTS.md は確認済みです。 |
| H:\Data\01_CTI\candy_HP\codex\docs\CODEX_SITE_OVERVIEW.md | 確認済み | PHPテンプレート生成型、公開前noindex前提を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\SITE_STRUCTURE.md | 確認済み | フォルダ構成、PHP/HTML対応候補を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\PAGE_LIST.md | 確認済み | エリアページ一覧と既存の問題候補を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\TECHNICAL_ANALYSIS.md | 確認済み | dataset_base.php中心の生成構造を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\CONTENT_ANALYSIS.md | 確認済み | placeholder、公開前noindex、営業時間不一致等を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\CODEX_MANAGEMENT_GUIDE.md | 確認済み | 既存ファイル非変更、秘密値非転記を確認 |
| H:\Data\01_CTI\candy_HP\codex\docs\UNKNOWN_AND_RISK_LIST.md | 確認済み | 重要リスク、未確認事項を確認 |

## ページ生成の関係

1. 公開URLはルート直下の kagoshima-deliveryhealth-area-*.php に対応します。
2. PHPは H:\Data\01_CTI\candy_HP\includefile\dataset_base.php をincludeします。
3. dataset_base.php が実行PHP名から H:\Data\01_CTI\candy_HP\source\*.html を読み込みます。
4. source HTML内の固定文言、画像、リンク、JSON-LDが出力の基礎になります。
5. DB置換やdataset側処理は今回未実行です。

## source HTMLなしの公開PHP

| slug | 公開PHP | source HTML | 状態 |
| --- | --- | --- | --- |
| hirakawacho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-hirakawacho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-hirakawacho.html | source HTMLなし |
| kamifukumotocho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamifukumotocho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamifukumotocho.html | source HTMLなし |
| kamihonmachi | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamihonmachi.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamihonmachi.html | source HTMLなし |
| kamitaniguchicho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamitaniguchicho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamitaniguchicho.html | source HTMLなし |
| kamitatsuocho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamitatsuocho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamitatsuocho.html | source HTMLなし |
| kawadacho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kawadacho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kawadacho.html | source HTMLなし |
| kawakamicho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kawakamicho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kawakamicho.html | source HTMLなし |
| kiirenakamyoch | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiirenakamyoch.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiirenakamyoch.html | source HTMLなし |
| komatsubara | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-komatsubara.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-komatsubara.html | source HTMLなし |
| shimizucho | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimizucho.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimizucho.html | source HTMLなし |

## 2026-06-05 追加済みページ

| エリア | slug | 公開PHP | source HTML | dataset | 状態 |
| --- | --- | --- | --- | --- | --- |
| 永吉 | nagayoshi | H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-nagayoshi.php | H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-nagayoshi.html | H:\Data\01_CTI\candy_HP\includefile\dataset_kagoshima-deliveryhealth-area-nagayoshi.php | ローカル作成済み。dataset_base.php反映済み。公開URLは反映前404を確認 |

## 管理上の注意点

- 2026-06-05の永吉ページ作成では、ルートPHP、source HTML、area datasetを新規作成し、dataset_base.phpのみ既存ファイルとして更新しました。
- CSS、JS、画像、create.php、.htaccess、sitemap.xml は未変更です。
- placeholderありページは公開方針の判断なしに修正しないでください。
- noindex は公開前保護の可能性があるため、公開時のみ解除対象を判断してください。
- secret、決済hidden値、DB接続情報、ログ本文は転記していません。

## 確認済み

- H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-area.html を確認しました。
- kagoshima-deliveryhealth-area-*.php と source\kagoshima-deliveryhealth-area-*.html を照合しました。
- 画像、リンク、SEO、JSON-LD、Map、placeholder を静的解析しました。
- 永吉ページのローカル画像2点、店舗画像、JSON-LD、placeholderなし、dataset_base.php case/str_replace を確認しました。

## 未確認

- PHP実行結果、DB接続、実ブラウザ表示、外部リンク稼働、Google Map実表示は未確認です。
- 永吉ページと永吉area画像2点は、ローカルには存在しますが、https://www.55810.com/ 側では 2026-06-05 時点で404でした。ローカル作成と本番反映は別工程として扱ってください。

## 推測

- source HTMLがない公開PHPはテンプレート読み込み時に問題になる可能性があります。
