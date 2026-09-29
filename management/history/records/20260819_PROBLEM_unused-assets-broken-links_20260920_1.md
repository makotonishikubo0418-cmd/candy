# 未使用公開資産4件の整理とリンク不整合の修正 — 2026-08-19の作業記録

- History: [20260819_PROBLEM_unused-assets-broken-links.md](../20260819_PROBLEM_unused-assets-broken-links.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-19
- 旧Task ID: `TASK-20260819-UNUSED-ASSET-BROKEN-LINK-FIX-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 18行目

**当時の依頼**

> Remove four unused public assets and correct the Yamadacho and top-page broken-link sources

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 26行目

- 担当表記: Primary Codex
- 期間表記: 2026-08-19
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Returned `ria_1.jpg` / `ria_1_sp.jpg` from the public woman-image folder to Git-managed local-only storage and changed Ria's ledger state to `LOCAL_ONLY`; deleted `HP/js/fav_ka.js` and `HP/imgCss/pc/newsClose.png`; removed the disabled `fav_ka.js` branch; corrected Yamadacho telephone/map values in the canonical Text and page source; removed three placeholder hrefs while preserving generated woman links; regenerated required state; committed and pushed on `main`; verified Actions upload/deletion and production HTTP/DOM; excluded `HP/index.php`, `.htaccess`, database work, branch operations, unrelated assets, and unrelated fixes

### 対応

> Moved `ria_1.jpg` and `ria_1_sp.jpg` from `HP/imgHtml/new_202601/girl/` to `Text_girl_data/画像データ/` without changing bytes and changed only Ria's ledger state to `LOCAL_ONLY`; deleted `HP/js/fav_ka.js` and `HP/imgCss/pc/newsClose.png`; removed the disabled `fav_ka.js` branch; corrected the reversed telephone/map values in the canonical Yamadacho Text and page source; removed three placeholder hrefs from the top templates and made `candyTile.js` attach each generated profile URL after cloning; synchronized the sitemap and deterministic generated state; committed as `5a4892539b1e8c1b0ce1dd4ea30542c4ab1d3cc8` and pushed `main`; Actions run `32207751580` uploaded five files and deleted four files from production

### 結果

**旧記録の確認結果**

> PHP and JavaScript syntax, girl-information check, target/full site-state checks, deterministic second write, deployment self-test/integration, diff check, and management audit passed; Actions completed successfully with SHA-256 verification for all five uploads and four explicit deletion records; production returned HTTP `404` for both Ria images, `fav_ka.js`, and PC `newsClose.png`, and HTTP `200` for the retained smartphone close image, root, and Yamadacho page; production Yamadacho contains the correct phone and map link; the top DOM contains no `____link____`, all three hidden template anchors have no href, and all 21 generated woman boxes have valid profile links

**旧記録の補足・未確認事項**

> Access logs, database-held strings, and external inbound links were not inspected; they do not change the verified deployment or current internal-reference result

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 26行目

旧状態表記: `Complete / Completed`。

旧台帳の次対応:

> None

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
