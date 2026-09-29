# サイトマップ更新日の信頼性改善 — 2026-07-25の作業記録

- History: [20260725_PROBLEM_sitemap-lastmod.md](../20260725_PROBLEM_sitemap-lastmod.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-25
- 旧Task ID: `TASK-20260725-SITEMAP-LASTMOD-RELIABILITY-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 28行目

**当時の依頼**

> Correct all existing `HP/sitemap.xml` `lastmod` values against the latest Git change date of each matching `HP/source/<stem>.html`; add deterministic preview/sync and stale-date validation to `candy-site-state`; update only the canonical SEO/operation/router documents, regression tests, generated current-state documents, and this reservation; preserve URL membership, order, priority, changefreq, existing unrelated working-tree changes, and exclude Commit, Push, Actions, production, database, deletion, and rename operations

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 47行目

- 担当表記: current
- 期間表記: 2026-07-25
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Correct all existing `HP/sitemap.xml` `lastmod` values against the latest Git change date of each matching `HP/source/<stem>.html`; add deterministic preview/sync and stale-date validation to `candy-site-state`; update only the canonical SEO/operation/router documents, regression tests, generated current-state documents, and this reservation; preserve URL membership, order, priority, changefreq, existing unrelated working-tree changes, and exclude Commit, Push, Actions, production, database, deletion, and rename operations

### 対応

> Historical result migrated from the former completed-reservation record: Compared all 118 sitemap URLs with their matching source Git dates, corrected 108 stale `lastmod` values while preserving ten current values and every non-`lastmod` sitemap byte, added deterministic preview/sync commands and mandatory stale-date failure to `candy-site-state`, documented the canonical rule, and added regression coverage for exact replacement, mapping, duplicate rejection, and invalid dates. XML parsing, 118 unique URLs, zero invalid dates, zero post-sync drift, generated-state reproducibility, and Git-diff checks passed. Commit, Push, Actions, and production deployment were not performed.

### 結果

**旧記録の確認結果**

> The former reservation ledger recorded status `COMPLETE`; no additional substantive verification was performed during this migration.

**旧記録の補足・未確認事項**

> Any detail not explicit in the preserved historical result remains UNVERIFIED.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
