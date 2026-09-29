# 項目が少ないホテルTextの検証追加

- Type: MODIFY
- Start Date: 2026-07-23

## 目的

項目が少ないホテルTextの検証追加を行う。

## 対象範囲

ホテルTextの省略項目と自己テスト。

## 完了条件

省略入力の扱いを固定し、自己テストを通す。

## 初期情報

旧資料での最初の関連作業: `TASK-20260723-HOTEL-SPARSE-SELF-TEST-001`（2026-07-23）。

当時の依頼:

> Fix the existing hotel page generator self-test for a hotel with sparse optional information; change only `codex/scripts/candy_hotel_page.py` and this reservation record; validate the focused sparse case and the full hotel self-test plus generated-state and diff checks; preserve all hotel Text, images, pages, and accumulated worktree differences; no Commit, Push, Actions, or production operation

出典: [TASK_LOG_2026_07_21_31.md](履歴/TASK_LOG_2026_07_21_31.md) 41行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
