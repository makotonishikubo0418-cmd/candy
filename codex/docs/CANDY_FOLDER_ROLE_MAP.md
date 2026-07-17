# CANDY フォルダ役割マップ

全フォルダを役割別に管理する資料です。
## 共通集計

> 重要: 以下は 2026-07-10 の生成スナップショットであり、現在値ではありません。現在の件数・構成・状態は実ファイルから再集計してください。

集計時点: 2026-07-10 04:52

| 項目 | 件数 |
|---|---:|
| 全フォルダ | 37 |
| 全ファイル | 1683 |
| コード/設定ファイル | 328 |
| 非コードファイル | 1355 |
| codex配下ファイル | 34 |
| codex/docs配下ファイル | 13 |
| 管理MD(CANDY_*.md) | 13 |
| ルート直下PHP | 98 |
| source HTML | 89 |
| includefile PHP | 101 |
| dataset_*.php | 99 |
| Text_*_data配下ファイル | 175 |
| log配下ファイル | 74 |

注記: `全フォルダ` は `[root]` を除く実フォルダ数。フォルダ台帳は管理行として `[root]` を追加するため、台帳行数は `全フォルダ + 1`。

## フォルダ台帳

| No | フォルダ | 親 | 直下ファイル | 直下フォルダ | 配下ファイル | 役割 |
|---:|---|---|---:|---:|---:|---|
| 1 | [root] | - | 102 | 15 | 1683 | 公開HPルート |
| 2 | .vscode | [root] | 1 | 0 | 1 | 編集環境設定 |
| 3 | .well-known | [root] | 1 | 1 | 34 | 公開検証用隠し領域 |
| 4 | .well-known/acme-challenge | .well-known | 33 | 0 | 33 | 公開検証用隠し領域 |
| 5 | codex | [root] | 1 | 4 | 34 | Codex管理資料 |
| 6 | codex/area | codex | 10 | 1 | 12 | エリアページ管理資料 |
| 7 | codex/area/backups | codex/area | 2 | 0 | 2 | 改修前バックアップ保管 |
| 8 | codex/docs | codex | 13 | 0 | 13 | 現行CANDY管理MD |
| 9 | codex/reform_20260529 | codex | 6 | 0 | 6 | 過去改修資料 |
| 10 | codex/scripts | codex | 2 | 0 | 2 | 管理資料生成スクリプト |
| 11 | css | [root] | 14 | 0 | 14 | 公開CSS |
| 12 | font | [root] | 50 | 0 | 50 | 子階層資産 |
| 13 | imgCss | [root] | 0 | 2 | 43 | 画像資産 |
| 14 | imgCss/pc | imgCss | 20 | 0 | 20 | 画像資産 |
| 15 | imgCss/s | imgCss | 23 | 0 | 23 | 画像資産 |
| 16 | imgHtml | [root] | 91 | 3 | 931 | 画像資産 |
| 17 | imgHtml/new_202601 | imgHtml | 7 | 7 | 529 | 画像資産 |
| 18 | imgHtml/new_202601/adsite | imgHtml/new_202601 | 1 | 0 | 1 | 画像資産 |
| 19 | imgHtml/new_202601/area | imgHtml/new_202601 | 346 | 0 | 346 | 画像資産 |
| 20 | imgHtml/new_202601/banner | imgHtml/new_202601 | 23 | 0 | 23 | 画像資産 |
| 21 | imgHtml/new_202601/blog | imgHtml/new_202601 | 12 | 0 | 12 | 画像資産 |
| 22 | imgHtml/new_202601/girl | imgHtml/new_202601 | 112 | 0 | 112 | 画像資産 |
| 23 | imgHtml/new_202601/hotel | imgHtml/new_202601 | 6 | 0 | 6 | 画像資産 |
| 24 | imgHtml/new_202601/shop | imgHtml/new_202601 | 22 | 0 | 22 | 画像資産 |
| 25 | imgHtml/pc | imgHtml | 69 | 2 | 225 | 画像資産 |
| 26 | imgHtml/pc/pc | imgHtml/pc | 69 | 0 | 69 | 画像資産 |
| 27 | imgHtml/pc/s | imgHtml/pc | 87 | 0 | 87 | 画像資産 |
| 28 | imgHtml/s | imgHtml | 86 | 0 | 86 | 画像資産 |
| 29 | includefile | [root] | 102 | 0 | 102 | 共通PHPとdataset |
| 30 | js | [root] | 18 | 0 | 18 | 公開JavaScript |
| 31 | log | [root] | 74 | 0 | 74 | ログ |
| 32 | movie | [root] | 15 | 0 | 15 | 動画資産 |
| 33 | source | [root] | 90 | 0 | 90 | HTMLテンプレート |
| 34 | Text_area_data | [root] | 135 | 2 | 169 | Textデータ |
| 35 | Text_area_data/Backup | Text_area_data | 2 | 0 | 2 | Textデータ |
| 36 | Text_area_data/Completion | Text_area_data | 32 | 0 | 32 | Textデータ |
| 37 | Text_blog_data | [root] | 3 | 0 | 3 | Textデータ |
| 38 | Text_hotel_data | [root] | 3 | 0 | 3 | Textデータ |
