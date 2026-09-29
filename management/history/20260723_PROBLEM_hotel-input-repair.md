# ホテル入力34件とテンプレート分類の修正

- Type: PROBLEM
- Start Date: 2026-07-23

## 目的

ホテル入力34件とテンプレート分類の修正を行う。

## 対象範囲

ホテル入力34件およびテンプレート分類。

## 完了条件

対象入力と分類の不整合を修正し、検査を完了する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260723-HOTEL-INPUT-REPAIR-001`（2026-07-23）。

当時の依頼:

> Repair only the 35 `Text_hotel_data/*.txt` records classified as `入力不備`; classify `01_対応ホテル_テンプレート.txt` as a management Text instead of a production candidate; change `codex/scripts/candy_hotel_target_gate.py` only for that classification; regenerate only the required generated current-state documents; validate every affected input with the canonical audit and direct preflight; no legacy-Text conversion, image creation or installation, page generation, Commit, Push, Actions, database, or production operation

出典: [TASK_LOG_2026_07_21_31.md](履歴/TASK_LOG_2026_07_21_31.md) 39行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
