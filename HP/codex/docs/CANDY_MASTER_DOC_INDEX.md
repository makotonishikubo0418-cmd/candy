# CANDY 管理資料インデックス

CANDYプロジェクトのHP構成、ページ内容、ページ内の作り方構成をたどる入口です。
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

## 最初に見る順

| 順 | 資料 | 役割 |
|---:|---|---|
| 1 | `HP/AGENTS.md` | 最上位ルール |
| 2 | `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` | 管理資料の入口 |
| 3 | `HP/codex/docs/CANDY_OPERATION_BASICS.md` | 改修前に確認する運用情報 |
| 4 | `HP/codex/docs/CANDY_HP_STRUCTURE_MAP.md` | HP全体構成 |
| 5 | `HP/codex/docs/CANDY_FOLDER_ROLE_MAP.md` | フォルダ別役割 |
| 6 | `HP/codex/docs/CANDY_FULL_FILE_CODE_INVENTORY.md` | 全フォルダ/全ファイル台帳 |
| 7 | `HP/codex/docs/CANDY_CODE_FILE_STRUCTURE.md` | PHP/HTML/dataset/CSS/JS構成 |
| 8 | `HP/codex/docs/CANDY_NON_CODE_ASSET_INVENTORY.md` | 画像/Text/ログ/DB等の非コード台帳 |
| 9 | `HP/codex/docs/CANDY_PAGE_CATEGORY_STRUCTURE.md` | カテゴリ別ページ構成 |
| 10 | `HP/codex/docs/CANDY_PAGE_SPEC_INDEX.md` | ページごとの役割と構成 |
| 11 | `HP/codex/docs/CANDY_FIX_BACKLOG.md` | 要確認差分の処理バックログ |
| 12 | `HP/codex/docs/CANDY_CODEX_BACKUP_REMARKS.md` | codex_backup由来の備考 |
| 13 | `HP/codex/docs/CANDY_EXISTING_DOCS_INVENTORY.md` | 残存資料/削除済み旧資料の説明 |
| 14 | `HP/codex/docs/CANDY_PHASE_RECHECK.md` | フェーズ再確認と未実施フェーズ |

## 管理階層

```text
AGENTS.md
  └─ 全体構成
      └─ ページ内容
          └─ 各ページごとのページ内構成
```

## 運用注意

- 管理資料の数値は共通集計の時点で統一する。
- PHP/HTML/CSS/JS/画像/ログ/DB/Text_* 本体は、この整理では変更しない。
- 認証値、DB値、ログ本文、hidden値は資料へ転記しない。
