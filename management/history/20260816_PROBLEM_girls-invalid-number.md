# 女性番号不正時の別人物表示の修正

- Type: PROBLEM
- Start Date: 2026-08-16

## 目的

女性番号不正時の別人物表示の修正を行う。

## 対象範囲

girls.phpのno省略・空値・非スカラー・存在しない番号。

## 完了条件

採用した301・404・正常人物応答を実装し、本番HTTPの分岐を確認する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260816-GIRLS-INVALID-NO-RECORD-001`（2026-08-16）。

当時の依頼:

> Record the separately discovered problem in which a nonexistent girls number returns HTTP 200 and renders another woman's profile, without treating it as part of the approved girls-profile SEO correction

出典: [TASK_LOG_2026_08.md](履歴/TASK_LOG_2026_08.md) 37行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。

旧案件ID: `CANDY-GIRLS-INVALID-NO-20260816`。
