# CANDY HP 全体構成

HP配下を、公開入口、テンプレート、dataset、資産、管理資料に分けて管理します。
## 共通集計

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

## 主要構成

| 階層 | 役割 | 件数/状態 |
|---|---|---:|
| [root] | 公開入口PHPとルート設定 | 102 |
| source | HTMLテンプレート | 90 |
| includefile | 共通PHPとdataset | 102 |
| css | 公開CSS | 14 |
| js | 公開JavaScript | 18 |
| imgHtml | HTML用画像 | 931 |
| imgCss | CSS用画像 | 43 |
| movie | 動画資産 | 15 |
| Text_*_data | テキストデータ | 175 |
| log | ログ。本文転記禁止 | 74 |
| codex | Codex管理資料 | 34 |

## 基本生成構造

```text
ルート直下PHP
  -> includefile/dataset_base.php
  -> source/同名.html
  -> includefile/dataset_同名.php
  -> includefile/class.hpgcoder2.php
  -> HTML出力
```

## 要確認

- PHP実行、DB接続、ブラウザ表示は未確認。
- 公開方式と本番URLは CANDY_OPERATION_BASICS.md で未確認項目として管理する。
