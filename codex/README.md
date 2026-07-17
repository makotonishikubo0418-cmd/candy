# candy 管理入口

このREADMEは、`\\192.168.1.3\disk1\FSG_SEO\candy\codex\README.md` にある管理資料の入口です。

## 1. 正本と作業場所

| 種類 | 場所 | 扱い |
|---|---|---|
| GitHub作業場 | `\\192.168.1.3\disk1\FSG_SEO\candy` | GitHub `makotonishikubo0418-cmd/candy` へつながるリポジトリルート |
| Codex管理正本 | `\\192.168.1.3\disk1\FSG_SEO\candy\codex` | 管理入口、管理文書、HP制作仕様、作業ツールを置く |
| プロジェクト管理 | `\\192.168.1.3\disk1\FSG_SEO\candy\codex\project_management` | ルール、現在地、予約、履歴、安全手順の正本 |
| 実サイト配下 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP` | PHP、source、includefile、画像、log、movieなどHPデータ |
| 制作入力 | ルート直下の `Text_area_data`、`Text_blog_data`、`Text_hotel_data` | HPへ直接公開しないページ制作元データ |
| 退避・旧資料 | `\\192.168.1.3\disk1\FSG_SEO\candy\Backup` | 旧Codex資料、旧HPデータ、除外リストなど。現行正本ではない |

## 2. 最初に読むもの

1. ルート `AGENTS.md`
2. この `codex/README.md`
3. `codex/project_management/TASK_RESERVATIONS.md`
4. 今回必要な管理文書またはHP runbookだけ
5. HP作業なら `HP/AGENTS.md`

## 3. フォルダの役割

| フォルダ | 役割 |
|---|---|
| `codex/` | Codex向け管理資料、制作仕様、スクリプト、旧調査資料 |
| `codex/project_management/` | 管理ルール、構成、進捗、連絡、Task予約・履歴、安全手順 |
| `codex/docs/` | area・hotel・blogなどHP制作の現行runbookと仕様 |
| `codex/scripts/` | ページ生成・検証・公開用スクリプトの配置先 |
| `HP/` | 公開サイト本体。`includefile`、`log`、`movie`もHPデータとしてここに置く |
| `Text_area_data/` | areaページの制作入力。area用の受入画像は `Text_area_data/画像データ/` |
| `Text_blog_data/` | blogページの制作入力 |
| `Text_hotel_data/` | hotelページの制作入力 |
| `Backup/` | バックアップと退避。`HP_旧データ/`、`除外リスト/`、旧Codex資料を含む |

## 4. 正本一覧

| 目的 | 正本 |
|---|---|
| 管理体制の概要 | `codex/管理体制_概要説明書.md` |
| 文書分割・更新ルール | `codex/project_management/DOCUMENT_RULES.md` |
| 全体計画・現在地・問題 | `codex/project_management/PROJECT_STATUS.md` |
| Codex間の連絡・引継ぎ | `codex/project_management/CODEX_COMMUNICATION.md` |
| Taskとファイル予約 | `codex/project_management/TASK_RESERVATIONS.md` |
| 個別Task履歴 | `codex/project_management/TASK_LOG.md` |
| コード・フォルダ構成 | `codex/project_management/CODE_STRUCTURE.md` |
| 削除・移動・一括操作安全手順 | `codex/project_management/SAFETY_PROTOCOL.md` |
| HP制作・生成仕様 | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| 更新停止した旧ページ制作記録 | `codex/ページ作成用.md` |

## 5. 作業ルート

| 作業 | 読む導線 |
|---|---|
| 管理体制変更 | `AGENTS.md` → `codex/README.md` → `codex/管理体制_概要説明書.md` → `codex/project_management/DOCUMENT_RULES.md` |
| 複数Codexの調整 | `AGENTS.md` → `codex/README.md` → `codex/project_management/TASK_RESERVATIONS.md` → `codex/project_management/CODEX_COMMUNICATION.md` |
| 全体状況確認 | `AGENTS.md` → `codex/README.md` → `codex/project_management/PROJECT_STATUS.md` |
| HPページ制作 | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → 該当runbook |
| HP管理資料更新 | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| 削除・移動・一括整理 | `AGENTS.md` → `codex/README.md` → `codex/project_management/SAFETY_PROTOCOL.md` |

## 6. 現在の実行制限

- `codex/scripts/` は移動済みだが、内部に旧階層を前提とするパス計算と入力制限が残っている。
- スクリプト移行と検証が完了するまで、area・hotel・blogの生成・公開コマンドは実行停止とする。
- HP本体、制作入力、管理資料の配置変更と、生成スクリプトが正常に動くことは別状態として扱う。

## 7. 重複禁止

- 管理正本を共有ルート直下や `HP/` 配下へ複製しない。
- `HP/HP/` を作らない。
- `HP/README.md` を作らない。HP作業導線は `HP/AGENTS.md` に集約する。
- `Backup/` 内の旧資料を現行仕様として使わない。使用時は現行ファイルと再照合する。
- 仕様、現在地、Task履歴、報告を同じ文書へ混在させない。
