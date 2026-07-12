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
| 1 | `AGENTS.md` | リポジトリ全体・全PC共通の正本ルール |
| 2 | `HP/AGENTS.md` | HP配下の補足ルール |
| 3 | `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` | 管理資料の入口 |
| 4 | `HP/codex/docs/CANDY_OPERATION_BASICS.md` | 改修前に確認する運用情報 |
| 5 | `HP/codex/docs/CANDY_HP_STRUCTURE_MAP.md` | HP全体構成 |
| 6 | `HP/codex/docs/CANDY_FOLDER_ROLE_MAP.md` | フォルダ別役割 |
| 7 | `HP/codex/docs/CANDY_FULL_FILE_CODE_INVENTORY.md` | 全フォルダ/全ファイル台帳 |
| 8 | `HP/codex/docs/CANDY_CODE_FILE_STRUCTURE.md` | PHP/HTML/dataset/CSS/JS構成 |
| 9 | `HP/codex/docs/CANDY_NON_CODE_ASSET_INVENTORY.md` | 画像/Text/ログ/DB等の非コード台帳 |
| 10 | `HP/codex/docs/CANDY_PAGE_CATEGORY_STRUCTURE.md` | カテゴリ別ページ構成 |
| 11 | `HP/codex/docs/CANDY_PAGE_SPEC_INDEX.md` | ページごとの役割と構成 |
| 12 | `HP/codex/docs/CANDY_FIX_BACKLOG.md` | 要確認差分の処理バックログ |
| 13 | `HP/codex/docs/CANDY_CODEX_BACKUP_REMARKS.md` | codex_backup由来の備考 |
| 14 | `HP/codex/docs/CANDY_EXISTING_DOCS_INVENTORY.md` | 残存資料/削除済み旧資料の説明 |
| 15 | `HP/codex/docs/CANDY_PHASE_RECHECK.md` | フェーズ再確認と未実施フェーズ |
| 16 | `HP/codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md` | Codexによるarea新規ページ生成の正本仕様、基本・例外・検証手順 |
| 17 | `HP/codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md` | Codexによるblog新規ページ生成の正本仕様、基本・例外・検証手順 |
| 18 | `HP/codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md` | Codexによるhotel新規ページ生成の正本仕様、基本・例外・検証手順 |
| 19 | `HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` | area・blog・hotel生成に共通する入力不足、可変構造、例外時停止ルール |
| 20 | `HP/codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` | area画像の準備用・公開用区分、照合、slug例外、Git管理 |
| 21 | `HP/codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` | スタッフ・別Codex向けarea分割制作手順、共有ファイル、検証、STOP条件 |
| 22 | `HP/codex/docs/CANDY_AREA_105_PAGE_QUEUE.md` | 未作成105ページの制作候補、画像不足5件、進捗・引継ぎ台帳 |

## 管理階層

```text
AGENTS.md
  └─ HP/AGENTS.md
      └─ 全体構成
          └─ ページ内容
              └─ 各ページごとのページ内構成
```

## 運用注意

- 管理資料の数値は共通集計の時点で統一する。
- PHP/HTML/CSS/JS/画像/ログ/DB/Text_* 本体は、この整理では変更しない。
- 認証値、DB値、ログ本文、hidden値は資料へ転記しない。
