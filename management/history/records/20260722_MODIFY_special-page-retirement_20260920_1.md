# 旧specialページの廃止 — 2026-07-22の作業記録

- History: [20260722_MODIFY_special-page-retirement.md](../20260722_MODIFY_special-page-retirement.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-22
- 旧Task ID: `TASK-20260722-SPECIAL-PAGE-RETIREMENT-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 24行目

**当時の依頼**

> Retire the four obsolete public special pages, delete their page-only remnants, preserve the internal generation scaffold still required by `create.php`, and complete GitHub and production synchronization

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 70行目

- 担当表記: current
- 期間表記: 2026-07-22
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Retire `HP/main.php`, `HP/test.php`, `HP/page.php`, and `HP/makeSitemap.php`; reconcile and delete production-only `HP/source/page.html`; remove page-only dataset and routing; preserve the `create.php`-required test scaffold with noindex on the remaining public operational page; synchronize directly related specifications, generator output, task records, GitHub, Actions, and production

### 対応

> Recovered production-only `HP/source/page.html` byte-for-byte into Commit `da893de` before deletion. Deleted `HP/main.php`, `HP/test.php`, `HP/page.php`, `HP/makeSitemap.php`, `HP/source/page.html`, and `HP/includefile/dataset_page.php`; removed only the corresponding `main.html` and `page.html` shared routing; retained `HP/includefile/dataset_test.php` and the `test.html` routing anchors used by `create.php`; kept `create.php` protected by `X-Robots-Tag: noindex, nofollow`; removed obsolete robots exclusions; aligned active specifications, the generator, and all four generated current-state documents; committed the implementation as `5e3b317`; and fixed the deployment self-test's obsolete physical `main.php` dependency as `fc11083`. Pushed all commits to `origin/main`.

### 結果

**旧記録の確認結果**

> Recovery Run `29884125049` succeeded. Initial implementation Run `29884834565` stopped in pre-FTP validation because the self-test tried to stat the deleted `main.php`; no production operation had started. After the fix, Run `29884998849`, exact eight-operation preview Run `29885034558`, and production Run `29885071809` succeeded. The production run SHA-256-verified `dataset_base.php` and `robots.txt` and deleted all six approved files. Those six URLs return HTTP 404 on both `www.55810.com` and the direct host. `create.php` returns HTTP 200 with `noindex, nofollow` on the public host and both the host-level and page-level noindex headers on the direct host. `robots.txt`, `sitemap.xml`, `news.php`, `girls_list.php`, and the Arata area page return HTTP 200, and the sitemap contains no retired URL. PHP lint, deployment self-test, deletion/rollback integration tests, XML parsing, generated-document audit/check, Markdown-table checks, and Git diff checks passed.

**旧記録の補足・未確認事項**

> Search-engine recrawl timing and Search Console removal state were not verified. The authenticated `create.php` generation operation, database behavior, and manual browser rendering were not executed because they were outside this retirement task.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
