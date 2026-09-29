# 開発中の会員機能の公開範囲制限 — 2026-08-17の作業記録

- History: [20260817_PROBLEM_member-development-isolation.md](../20260817_PROBLEM_member-development-isolation.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-17
- 旧Task ID: `TASK-20260817-MEMBER-DEVELOPMENT-ISOLATION-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 31行目

**当時の依頼**

> Keep the unfinished member feature out of indexing and formal public navigation while preserving the existing public mypage

### 対応

> Registered atomic case `CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817`; made `mypage.php` load the member system only when its existing integration flag is enabled and otherwise render the legacy dataset path; added the common `X-Robots-Tag: noindex, nofollow` header; changed all three member source robots values and the common member layout to `noindex,nofollow`; removed both links to the absent `terms.php`; added a focused isolation regression; classified the development entries in generated SEO state without weakening their robots or sitemap requirements; updated the permanent other-page, SEO, and verification contracts and regenerated routed current state; explicitly staged 24 paths, committed them as `dd9588135158bb3ecba0e248ca602d5956a68bf1`, and pushed the unchanged `main` branch. No database, branch creation/switch, pull, merge, deployment, production, external-service, or Search Console operation was performed

### 結果

**旧記録の確認結果**

> Integration flag `false`; ten development page/API/cron entries use the protected bootstrap; three source robots declarations pass; formal public-source member/legal links are zero; `terms.php` links are zero; focused regression passes; Python syntax passes without bytecode writes; all ten changed or directly affected PHP files pass PHP 8.3 lint; sitemap preview/sync reports 140 URLs and zero changes; full site-state check passes with ten current-state outputs; second generation changes zero files; management audit and `git diff --check` pass; GitHub live `main` matched implementation Commit `dd9588135158bb3ecba0e248ca602d5956a68bf1` after Push

**旧記録の補足・未確認事項**

> Runtime rendering and response headers, authenticated/guest redirect behavior, database-backed member operations, Actions, deployment, production HTTP/browser state, and Search Console remain unperformed or unverified

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 39行目

旧状態表記: `Complete / GitHub Published`。

旧台帳の次対応:

> Separately authorize deployment, then confirm production HTTP headers, redirects, legacy mypage rendering, public-link isolation, and sitemap exclusion

**移行時の状態判定**: GitHub反映まで記録済み。展開、本番ヘッダー・描画、DB移行、実行時有効化、スケジューラ状態は未確認。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: GitHub反映まで記録済み。展開、本番ヘッダー・描画、DB移行、実行時有効化、スケジューラ状態は未確認。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
