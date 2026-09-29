# トップページバナー幅の修正 — 2026-08-04の作業記録

- History: [20260804_PROBLEM_index-banner-width.md](../20260804_PROBLEM_index-banner-width.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-04
- 旧Task ID: `TASK-20260804-INDEX-BANNER-WIDTH-FIX-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 52行目

**当時の依頼**

> Correct the desktop width regression in the newly installed manager-recommendation and discount-information banners by replacing the inherited fixed-width `img_1` class with one target-specific full-width class; update only `HP/source/index.html`, `HP/source/style.css`, required generated current-state documents, and this reservation; preserve all image bytes, unrelated classes, PHP, datasets, database, Commit, Push, Actions, and production

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 40行目

- 担当表記: current
- 期間表記: 2026-08-04
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Correct the desktop width regression in the newly installed manager-recommendation and discount-information banners by replacing the inherited fixed-width `img_1` class with one target-specific full-width class; update only `HP/source/index.html`, `HP/source/style.css`, required generated current-state documents, and this reservation; preserve all image bytes, unrelated classes, PHP, datasets, database, Commit, Push, Actions, and production

### 対応

> Historical result migrated from the former completed-reservation record: Replaced the inherited `img_1` class on the two new banners with the target-specific `index-section-banner` class, set it to block-level `width: 100%` with automatic height for desktop and mobile, and updated the index stylesheet content version to `aa8796d`. Preserved all image bytes and the shared `img_1` rule; sitemap had zero changes. Target index structure, SEO, image, global generated-state, deterministic second-write, and Git-diff checks passed. Browser automation was unavailable; Commit, Push, Actions, production, PHP, datasets, and database were untouched.

### 結果

**旧記録の確認結果**

> The former reservation ledger recorded status `COMPLETE`; no additional substantive verification was performed during this migration.

**旧記録の補足・未確認事項**

> Any detail not explicit in the preserved historical result remains UNVERIFIED.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
