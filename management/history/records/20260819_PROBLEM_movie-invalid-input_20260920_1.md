# 動画iframeの不正入力応答の修正 — 2026-08-19の作業記録

- History: [20260819_PROBLEM_movie-invalid-input.md](../20260819_PROBLEM_movie-invalid-input.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-19
- 旧Task ID: `TASK-20260819-MOVIE-IFRAME-INVALID-INPUT-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 22行目

**当時の依頼**

> Keep `movie_iframe.php` noindex and render only a correctly selected playable movie, returning 404 otherwise

### 対応

> Added `X-Robots-Tag: noindex, nofollow` to every helper response; required exactly one positive numeric `mids` or `midg`; required an active mp4, ogv, or webm filename before rendering; returned the existing 404 body for missing, empty, malformed, both-selector, unresolved, poster-only, and non-playable requests; prevented failed queries from reaching row-count handling; added one focused eight-assertion regression; recorded the permanent other-page and SEO contract, atomic defect route, generated current state, and task history; sitemap membership was unchanged

### 結果

**旧記録の確認結果**

> Both changed PHP files pass PHP 8.3 lint; the focused eight-assertion test passes; six local invalid-request classes return HTTP `404`, the existing noindex body, and `X-Robots-Tag: noindex, nofollow`; sitemap preview/synchronization reported zero changes

**旧記録の補足・未確認事項**

> At original task completion, Commit, Push, Actions, deployment, and production verification were unperformed. The complete scope was subsequently pushed in Commit `66f199bd8f00c916c6d693fea00d1fa94557c7d3`, which is contained in live GitHub `main`. Production database-backed records, physical video availability, production HTTP, and browser playback remain `UNVERIFIED`

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 30行目

旧状態表記: `GitHub Published / Active`。

旧台帳の次対応:

> Verify production playback for valid shop and woman videos and production HTTP `404` for invalid, unresolved, and non-playable requests. Deployment, database-backed behavior, physical video availability, production HTTP, and browser playback remain `UNVERIFIED`

**移行時の状態判定**: ローカル検証とGitHub反映は記録済み。本番のHTTP・再生分岐は未確認。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: ローカル検証とGitHub反映は記録済み。本番のHTTP・再生分岐は未確認。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
