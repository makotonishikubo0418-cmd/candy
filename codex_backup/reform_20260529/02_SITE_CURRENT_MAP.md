# CANDY HP 現在構成マップ

作成日: 2026-06-09
対象: `H:\Data\01_CTI\candy_HP`
状態: 読み取り専用確認のみ。HP本体は未修正。

## 結論

CANDY HPは、直下の公開PHPが `includefile\dataset_base.php` を読み込み、`source\*.html` と `includefile\dataset_*.php` を組み合わせて出力する独自テンプレート生成型サイトである。

FSG改修案を反映する場合、1ページ単位のHTML修正に見えても、公開PHP、`source`、`dataset_base.php`、個別 `dataset_*.php`、CSS、JS、画像、SEOメタ、sitemap へ影響する可能性がある。

## ファイル数の確認結果

| 区分 | 数 |
|---|---:|
| 直下ファイル | 101 |
| 直下PHP | 98 |
| `source` ファイル | 90 |
| `source` 内エリアページHTML | 61 |
| `source` 内ブログHTML | 6 |
| `source` 内ホテルHTML | 3 |
| `includefile` ファイル | 102 |
| `includefile` 内エリアdataset | 71 |

## 主要ディレクトリ

| パス | 役割 |
|---|---|
| `H:\Data\01_CTI\candy_HP` | 公開PHP・ルートファイル |
| `H:\Data\01_CTI\candy_HP\source` | HTMLテンプレート本体 |
| `H:\Data\01_CTI\candy_HP\includefile` | dataset、変換クラス、共通関数 |
| `H:\Data\01_CTI\candy_HP\css` | 共通CSS |
| `H:\Data\01_CTI\candy_HP\js` | 共通JS・UI・計測系 |
| `H:\Data\01_CTI\candy_HP\imgHtml` | HTML内画像 |
| `H:\Data\01_CTI\candy_HP\imgCss` | CSS背景等の画像 |
| `H:\Data\01_CTI\candy_HP\Text_area_data` | エリアページ用原稿 |
| `H:\Data\01_CTI\candy_HP\codex` | Codex管理資料 |

## 生成構造

| 層 | 確認内容 |
|---|---|
| 公開PHP | 直下PHPは `/home/firststar/public_html/group_test/candy/includefile/dataset_base.php` を include する形式 |
| 共通生成 | `dataset_base.php` が現在URLから対応する `source\*.html` を判定し、必要な `dataset_*.php` を include する |
| HTML本体 | `source\*.html` にメタ情報、ヘッダー、本文、フッター、JSON-LD、計測タグなどが入っている |
| DB/動的表示 | `dataset_girls.php`、`dataset_girls_list.php`、`dataset_schedule.php` などがDB由来表示に関係する可能性が高い |
| 変換 | `class.hpgcoder2.php` がテンプレート置換処理に関係 |

## 主要ページ対応表

| 公開ページ | テンプレート | dataset | 主な意味 |
|---|---|---|---|
| `index.php` | `source\index.html` | `dataset_index.php` | TOP |
| `girls_list.php` | `source\girls_list.html` | `dataset_girls_list.php` | 女の子一覧 |
| `girls.php` | `source\girls.html` | `dataset_girls.php` | 女の子詳細 |
| `schedule.php` | `source\schedule.html` | `dataset_schedule.php` | 出勤情報 |
| `system.php` | `source\system.html` | `dataset_system.php` | 料金・システム |
| `mypage.php` | `source\mypage.html` | `dataset_mypage.php` | マイページ系入口候補 |
| `area.php` | `source\area.html` | `dataset_area.php` | 対応エリア一覧 |
| `hotel.php` | `source\hotel.html` | `dataset_hotel.php` | ホテル一覧 |
| `blog.php` | `source\blog.html` | `dataset_blog.php` | ブログ一覧 |
| `news.php` | `source\news.html` | `dataset_news.php` | NEWS |
| `movie.php` | `source\movie.html` | `dataset_movie.php` | 動画 |
| `contact.php` | `source\contact.html` | `dataset_contact.php` | 問い合わせ候補。ただしplaceholderが残る |

## 既に見えている注意点

| 注意点 | 内容 |
|---|---|
| noindex | 複数 `source\*.html` に `meta name="robots" content="noindex"` がある。公開前保護として扱い、公開判断までは外さない |
| placeholder | `source\contact.html`、`source\create.html` などに `aaaaaaaa...` が残る |
| 直下PHPのinclude | ローカル相対ではなく本番系絶対パスを include しているため、ローカル実行検証には別確認が必要 |
| エリア数差分 | 直下PHP/datasetエリア数と `source` エリアHTML数に差がある。既存資料のエリア監査と併用が必要 |
| 共通部の複製 | ヘッダー・フッター・電話導線・ナビが複数HTMLに直接書かれている可能性があり、修正漏れリスクが高い |
| 外部計測 | Google tag `G-0VBTBPHDD2` と `amadareAcRec` らしき電話計測が存在 |
| 電話導線 | `tel:0992266956` が複数ページに存在 |
| JSON-LD | 一部ページに `application/ld+json` が存在 |

## FSG改修で特に確認すべき既存ファイル

| 改修観点 | 最初に確認するファイル |
|---|---|
| キャンディマイページ | `mypage.php`、`source\mypage.html`、`includefile\dataset_mypage.php` |
| 会員登録/ログイン導線 | `source\mypage.html`、`source\index.html`、ナビがある全HTML |
| AI受付導線 | `source\index.html`、`source\contact.html`、問い合わせ/電話導線周辺 |
| 女の子一覧/詳細 | `girls_list.php`、`girls.php`、`source\girls_list.html`、`source\girls.html`、`dataset_girls_list.php`、`dataset_girls.php` |
| 出勤情報 | `schedule.php`、`source\schedule.html`、`dataset_schedule.php` |
| 料金/コース/支払方法 | `system.php`、`source\system.html`、`dataset_system.php` |
| ホテル/案内先 | `hotel.php`、`source\hotel.html`、ホテル詳細HTML、関連dataset |
| エリア | `area.php`、`source\area.html`、エリア詳細HTML、`dataset_base.php` |
| SEO公開制御 | 全 `source\*.html`、`sitemap.xml`、`makeSitemap.php`、`.htaccess` |

## まだ判断しないこと

| 判断しないこと | 理由 |
|---|---|
| noindex解除 | 公開タイミング・対象ページの判断が必要 |
| `mypage.php` 改修 | 既存用途とFSG要件の差分確認が必要 |
| AI受付導線追加 | 入口URL・API・認証方式が未確認 |
| 料金/ホテル/エリア文言修正 | 現行営業情報との照合が必要 |
| sitemap更新 | 公開対象ページ確定後に行うべき |

