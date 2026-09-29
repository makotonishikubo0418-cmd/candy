# 移設後のスクリプト内部パスの修正

- Type: PROBLEM
- Start Date: 2026-07-17

## 目的

移設後のスクリプト内部パスの修正を行う。

## 対象範囲

codex/scripts内の旧HP配下参照と固定階層参照。

## 完了条件

内部パスを新しい配置へ変更し、dry-runと旧参照の残存確認を完了する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260717-SCRIPTS-LOCAL-PATHS-001`（2026-07-17）。

当時の依頼:

> Migrate internal paths in `codex/scripts/` to the new local layout and validate without writes

出典: [TASK_LOG_2026_07_01_20.md](履歴/TASK_LOG_2026_07_01_20.md) 24行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
