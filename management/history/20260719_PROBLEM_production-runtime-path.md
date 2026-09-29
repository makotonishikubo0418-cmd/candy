# 本番ページの共通処理読み込み先の修正

- Type: PROBLEM
- Start Date: 2026-07-19

## 目的

本番ページの共通処理読み込み先の修正を行う。

## 対象範囲

公開ラッパーのgroup_test参照と共通データ読み込み先。

## 完了条件

本番用読み込み先へ修正し、記録された本番応答確認を完了する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260719-PRODUCTION-RUNTIME-PATH-001`（2026-07-19）。

当時の依頼:

> Remove the development `group_test` dataset dependency from public rendering wrappers without changing unrelated test-environment handling

出典: [TASK_LOG_2026_07_01_20.md](履歴/TASK_LOG_2026_07_01_20.md) 17行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
