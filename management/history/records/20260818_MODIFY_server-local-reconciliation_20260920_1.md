# サーバーとローカルのファイル照合・復元 — 2026-08-18の作業記録

- History: [20260818_MODIFY_server-local-reconciliation.md](../20260818_MODIFY_server-local-reconciliation.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-18
- 旧Task ID: `TASK-20260818-SERVER-LOCAL-RECONCILIATION-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 25行目

**当時の依頼**

> Reconcile the supplied production snapshot, canonical local `HP`, and current production without restoring obsolete or local-only data

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 29行目

- 担当表記: current
- 期間表記: 2026-08-18
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Compare every file in `Candy_Server Data_20260818` with canonical `HP`; distinguish semantic changes from line-ending-only differences; inspect current production through authorized HTTP/static-asset evidence; recover production-confirmed newer content into `HP/source/system.html`; correct the petitegirl OGP and JSON-LD image reference to its existing public blog image; preserve create retirement, public/local-only woman-image placement, protected files, branch `main`, and the supplied snapshot; publish and verify only the fixed plan; perform no database operation, snapshot deletion, branch operation, or unplanned production deletion

### 対応

> Enumerated 1,041 snapshot files and 1,057 HP files; separated 115 line-ending-only differences from four semantic differences; retained the newer local create retirement, recognized the CA bundle as byte-equivalent after line-ending normalization, recovered the production-confirmed `source/system.html` content from the snapshot, and corrected the petitegirl OGP and JSON-LD image to the existing blog asset. Regenerated deterministic current state and sitemap dates; combined the authorized reconciliation and create retirement into Commit `98836964a3cd0ab06be30e9d03f067b9e1786662`, pushed unchanged `main`, and completed production Run `32111154454`. The supplied snapshot was not modified or deleted

### 結果

**旧記録の確認結果**

> Before deployment, all 520 public static targets were checked; 519 agreed and only the intentionally newer local `robots.txt` differed. All 148 root PHP routes returned 145 HTTP `200` and three intended redirects, with zero error statuses. All 68 public woman images agreed with production, all 44 local-only image URLs returned `404`, and both `moca` hashes matched. After deployment, `robots.txt`, `sitemap.xml`, both `moca` images, and the petitegirl image matched local hashes; `system.php` and the petitegirl page returned `200` with corrected content; old OGP reference was absent. Site-state generation/check, woman ledger check, blog input audit, PHP lint, four JSON-LD parses, deployment tests, management audit, and Git checks passed

**旧記録の補足・未確認事項**

> Protected source/include bytes cannot be independently downloaded through HTTP; Actions performed final-name SHA-256 verification for every upload. Database content, access logs, external inbound links, Search Console, and browser rendering were not inspected

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 32行目

旧状態表記: `Complete / Completed`。

旧台帳の次対応:

> None

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
