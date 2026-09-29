# 直接ホスト名アクセスの検索登録抑制 — 2026-07-22の作業記録

- History: [20260722_MODIFY_direct-host-index-control.md](../20260722_MODIFY_direct-host-index-control.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-22
- 旧Task ID: `TASK-20260722-SEO-HOST-BOUNDARY-RECORD-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 25行目

**当時の依頼**

> Keep the direct server URL available while excluding it from search indexing and preserving public-domain indexing

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 71行目

- 担当表記: current
- 期間表記: 2026-07-22
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> `HP/.htaccess`, `codex/docs/CANDY_SEO_SPEC.md`, `codex/docs/CANDY_FIX_BACKLOG.md`, `codex/project_management/TASK_LOG.md`, and this reservation record

### 対応

> Added a `firststar.kir.jp` host marker and conditional `X-Robots-Tag: noindex` response header to `HP/.htaccess`; committed the exact one-file implementation as `7106d83`; pushed `main`; deployed it through protected Actions Run `29881578419`; removed the resolved backlog entry; and made the direct-host rule canonical in the SEO specification. HTML, PHP, `robots.txt`, canonical URLs, and the sitemap were unchanged.

### 結果

**旧記録の確認結果**

> The protected preview verified one upload and zero deletions. Production deployment SHA-256-verified `HP/.htaccess`. `news.php` and `kagoshima-deliveryhealth-area-arata.php` returned HTTP 200 with exactly one `X-Robots-Tag: noindex` header on the direct host and HTTP 200 with no such header on the public host. Existing public-host normalization and cached-path recovery redirects remained correct.

**旧記録の補足・未確認事項**

> Google recrawl timing, Search Console indexing state, and Google-selected canonicals were not verified.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
