# サイトマップ更新日の信頼性改善

- Type: PROBLEM
- Start Date: 2026-07-25

## 目的

サイトマップ更新日の信頼性改善を行う。

## 対象範囲

サイトマップlastmodと生成・検証手順。

## 完了条件

実変更と更新日の対応を修正し、再実行時の安定性を確認する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260725-SITEMAP-LASTMOD-RELIABILITY-001`（2026-07-25）。

当時の依頼:

> Correct all existing `HP/sitemap.xml` `lastmod` values against the latest Git change date of each matching `HP/source/<stem>.html`; add deterministic preview/sync and stale-date validation to `candy-site-state`; update only the canonical SEO/operation/router documents, regression tests, generated current-state documents, and this reservation; preserve URL membership, order, priority, changefreq, existing unrelated working-tree changes, and exclude Commit, Push, Actions, production, database, deletion, and rename operations

出典: [TASK_LOG_2026_07_21_31.md](履歴/TASK_LOG_2026_07_21_31.md) 28行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。
