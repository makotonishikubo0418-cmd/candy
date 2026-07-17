# Task・ファイル予約

- 目的: 複数Codexが同じファイルを同時に変更しないようにする
- 状態: 正本
- 更新日: 2026-07-17

## 1. 予約ルール

- 編集前に予約する。
- 予約は最小範囲にする。
- 終了時に解除または完了へ移す。
- 予約中のファイルを触る必要がある場合は停止して調整する。
- 予約なしで既存の未整理差分へ上書きしない。

## 2. 予約中

| Task ID | Codex | 開始 | 対象ファイル/範囲 | 目的 | 状態 |
|---|---|---|---|---|---|
| TASK-20260717-GITHUB-SYNC-001 | current | 2026-07-17 | `.gitignore`, Git index, `.github/`, `AGENTS.md`, `HP/`, `codex/`, `Text_area_data/`, `Text_blog_data/`, `Text_hotel_data/` | 現在の正本だけをGitHubへ同期し、旧ルート構成をGit管理から除外 | 作業中 |

## 3. 完了・解除済み

| Task ID | Codex | 期間 | 対象 | 結果 |
|---|---|---|---|---|
| TASK-20260717-STRUCTURE-DOCS-001 | current | 2026-07-17 | フォルダ再編後の管理資料26ファイル | 新配置へ導線を統一し、実体・UTF-8・見出し・表を確認。スクリプト未移行はSTOPとして記録 |
| TASK-20260717-AREA-HOTEL-GUIDE-001 | current | 2026-07-17 | area・hotel生成仕様、管理資料ルーター、予約表 | 両ページの役割、詳細構成ツリー、可変件数、即時参照導線を正本化 |
| TASK-20260717-SUMMARY-RULE-001 | current | 2026-07-17 | `管理体制/DOCUMENT_RULES.md`, `管理体制/TASK_RESERVATIONS.md` | `要約:` の必須情報、状態別書き分け、禁止例、正常完了・STOP例を正本化 |
| TASK-20260716-MGMT-001 | current | 2026-07-16 | 外側 `AGENTS.md`, `README.md`, `管理体制/*`, `HP/AGENTS.md` | 管理体制の初期整備 |
| TASK-20260716-MGMT-002 | current | 2026-07-16 | `AGENTS.md`, `README.md`, `管理体制_概要説明書.md`, `管理体制/*`, 外側入口文書 | 監査指摘を受け、candy用正本をGit作業場内へ設置し、外側文書を入口へ降格 |
| TASK-20260716-MGMT-003 | current | 2026-07-16 | `管理体制/TASK_LOG.md`, `管理体制/PROJECT_STATUS.md`, `管理体制/TASK_RESERVATIONS.md` | 再監査残件を修正 |
| TASK-20260716-MGMT-004 | current | 2026-07-16 | AGENTS.md, 管理体制/DOCUMENT_RULES.md, 管理体制/CODEX_COMMUNICATION.md, 管理体制/TASK_LOG.md, 管理体制/TASK_RESERVATIONS.md | Git Commit/Push高速実行ルールと監査項目を追加 |
| TASK-20260716-MGMT-005 | current | 2026-07-16 | area runbook, page generation spec, DOCUMENT_RULES, CODEX_COMMUNICATION, TASK_LOG, TASK_RESERVATIONS | txt分類と新規制作可否の分離、新規制作対象ゲートを追加 |
| TASK-20260716-MGMT-006 | current | 2026-07-16 | area target gate script, area runbook, page generation spec, DOCUMENT_RULES, CODEX_COMMUNICATION, TASK_LOG, TASK_RESERVATIONS | area一覧同一地域・別slugを対象ゲートで事前除外する管理へ更新 |
| TASK-20260716-MGMT-007 | current | 2026-07-16 | area target gate script, area runbook, page generation spec, DOCUMENT_RULES, CODEX_COMMUNICATION, TASK_LOG, TASK_RESERVATIONS | area一覧リンクを必要条件として扱い、同一地域・別slugのみ除外するよう修正 |
| TASK-20260716-MGMT-008 | current | 2026-07-16 | hotel target gate, hotel runbook, hotel spec, hotel text/image docs, management docs | hotel制作対象管理、既存hotel分解、publish-next標準化、停止理由集計を整備 |
| TASK-20260716-MGMT-009 | current | 2026-07-16 | 外側AGENTS/README/管理体制、HP AGENTS/README、HP側重複管理文書 | 外側を管理正本へ一本化し、重複を削除 |
| TASK-20260716-MGMT-010 | current | 2026-07-16 | `.git`, `HP/HP`, `HP`直下非サイト物、管理導線 | HP階層を一本化し、GitHub作業場を外側へ移動 |
| TASK-20260716-AREA-IMAGE-SPEC-001 | Codex | 2026-07-16 | area画像制作仕様と関連導線 | 正本化、権利STOP、他Codex導線を整備 |
| TASK-20260716-MGMT-011 | current | 2026-07-16 | `AGENTS.md`, `README.md`, `管理体制/*` | 削除・移動・Git復旧安全プロトコルを正本化 |
