# CANDY フェーズ再確認

ここまでの作業をフェーズ単位で再確認します。
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

## 完了フェーズ

| フェーズ | 内容 | 成果物 |
|---|---|---|
| Phase 0 | AGENTS.mdをCANDY前提に確認し、管理資料作成ルールを確定 | `HP/AGENTS.md` |
| Phase A | 既存資料とcodex_backupを確認し、使える情報を備考化 | `CANDY_CODEX_BACKUP_REMARKS.md` |
| Phase B | HP全体構成、フォルダ、全ファイル、コード/非コード台帳を整理 | `CANDY_HP_STRUCTURE_MAP.md`, `CANDY_FOLDER_ROLE_MAP.md`, `CANDY_FULL_FILE_CODE_INVENTORY.md`, `CANDY_CODE_FILE_STRUCTURE.md`, `CANDY_NON_CODE_ASSET_INVENTORY.md` |
| Phase C | ページカテゴリとページ別役割を整理 | `CANDY_PAGE_CATEGORY_STRUCTURE.md`, `CANDY_PAGE_SPEC_INDEX.md` |
| Phase D | 監査不合格箇所を修正し、生成スクリプトから資料を再生成 | `HP/codex/scripts/generate_candy_management_docs.py` と `CANDY_*.md` |
| Phase E | 改修前の運用基礎と差分バックログを新規作成 | `CANDY_OPERATION_BASICS.md`, `CANDY_FIX_BACKLOG.md` |

## 未実施フェーズ

| フェーズ | 内容 | 前提条件 |
|---|---|---|
| Phase F | 本番URL/公開方式/PHPバージョン/Webサーバー確認 | オーナーまたはサーバー管理者から運用情報を得る |
| Phase G | ブラウザ表示・PHP実行・DB接続を含む検証 | テスト環境または本番確認手順の承認 |
| Phase H | CANDY_FIX_BACKLOG.md の各行を処理 | 処理案についてオーナー判断を得る |
| Phase I | 実改修 | 変更対象・バックアップ・反映手順の承認 |

## 件数差分の記録

| 記録 | 内容 |
|---|---|
| 再集計時点 | 2026-07-10 04:52 |
| 現在統一値 | 全ファイル 1683 / コード・設定 328 / codex配下 34 |
| 旧資料差分1件の正体 | 旧CANDY_HP_STRUCTURE_MAPは出力資料自身または後続CANDY管理資料の作成前に集計されており、後続資料では管理MDが1件増えた状態だった。現在は新規2資料と生成スクリプトを含め同一時点で再集計済み。 |

## 削除済み

| 対象 | 状態 |
|---|---|
| 旧docs資料9件 | HP/codex/docsから削除済み。codex_backup/docsに旧資料あり。 |
