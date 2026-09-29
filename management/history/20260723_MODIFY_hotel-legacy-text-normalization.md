# Hotel M・Villaの旧入力の正規化

- Type: MODIFY
- Start Date: 2026-07-23

## 目的

Hotel M・Villaの旧入力の正規化を行う。

## 対象範囲

Hotel M・Villaの旧ホテルText。

## 完了条件

指定された旧入力を変換し、検査結果を確認する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260723-HOTEL-LEGACY-NORMALIZATION-001`（2026-07-23）。

当時の依頼:

> Normalize only `Text_hotel_data/Hotel M（旧レクサス）.txt` and `Text_hotel_data/ヴィラコスタ500.txt` from their legacy layouts into the validated current hotel input format; use only target-confirmed existing values, update only generated current-state documents required by the canonical audit and this reservation record; preserve the accumulated 40-path working tree; no image/page generation, Commit, Push, Actions, database, or production operation

出典: [TASK_LOG_2026_07_21_31.md](履歴/TASK_LOG_2026_07_21_31.md) 38行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
