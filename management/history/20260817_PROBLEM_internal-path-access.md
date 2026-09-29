# 内部ディレクトリへのHTTPアクセス制御

- Type: PROBLEM
- Start Date: 2026-08-17

## 目的

内部ディレクトリへのHTTPアクセス制御を行う。

## 対象範囲

source・includefileの直接アクセス、必要な公開CSSと既存資産。

## 完了条件

sourceの404、includefileの403、必要CSSの200と既存公開応答を本番で確認する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260817-INTERNAL-PATH-ACCESS-CONTROL-001`（2026-08-17）。

当時の依頼:

> Execute local Phases 1 through 3 for direct HTTP access control of generation-source HTML and server-side include files

出典: [TASK_LOG_2026_08.md](履歴/TASK_LOG_2026_08.md) 34行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。

旧案件ID: `CANDY-INTERNAL-PATH-ACCESS-20260817`。
