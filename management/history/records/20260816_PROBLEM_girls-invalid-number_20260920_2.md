# 女性番号不正時の別人物表示の修正 — 2026-08-19の作業記録

- History: [20260816_PROBLEM_girls-invalid-number.md](../20260816_PROBLEM_girls-invalid-number.md)
- Record Date: 2026-09-20
- Sequence: 2
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-19
- 旧Task ID: `TASK-20260819-GIRLS-INVALID-NO-FIX-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 23行目

**当時の依頼**

> Make `girls.php` without a woman number return the girls list and stop invalid numbers from rendering another woman's profile

### 決定

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_INVALID_NO_BEHAVIOR.md](../履歴/CANDY_GIRLS_INVALID_NO_BEHAVIOR.md) 40–48行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 5. Adopted Response Contract
> 
> On 2026-08-19, the user approved and instructed implementation of this exact behavior:
> 
> - `girls.php` with missing or empty `no` returns `301` to the same-host `girls_list.php` route.
> - A non-scalar `no` returns HTTP `404` before the common dataset is loaded.
> - A scalar `no` that does not resolve to an active woman returns HTTP `404` from `dataset_girls.php` and renders the existing noindex 404 body.
> - A resolved active woman keeps the existing indexable profile response and woman-specific canonical URL.
> - Do not apply `noindex` to `girls.php` as a whole.

### 対応

> Added the missing/empty-`no` `301` response in `HP/girls.php`; added malformed and unresolved-`no` `404` responses using the existing 404 body; removed the first-active-woman fallback from `dataset_girls.php`; added one focused five-assertion regression; updated the registered case, permanent other-page and SEO behavior, defect route, backlog, and task history; sitemap membership was unchanged

### 結果

**旧記録の確認結果**

> Local HTTP returned `301` with `Location: girls_list.php` for the bare entry and `404` with `noindex,follow` for a non-scalar number; both changed PHP files pass PHP 8.3 lint; the existing 53-assertion girls-profile SEO test and the new 5-assertion invalid-number test pass; sitemap preview/synchronization reported zero changes

**旧記録の補足・未確認事項**

> At original task completion, Commit, Push, Actions, deployment, and production verification were unperformed. The complete scope was subsequently pushed in Commit `66f199bd8f00c916c6d693fea00d1fa94557c7d3`, which is contained in live GitHub `main`. Production database-backed responses, production HTTP, access logs, external inbound links, and Search Console remain `UNVERIFIED`

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 42行目

旧状態表記: `GitHub Published / Active`。

旧台帳の次対応:

> Verify production missing, valid, malformed, and unresolved-number responses. Deployment, production database-backed behavior, production HTTP, access logs, external inbound links, and Search Console remain `UNVERIFIED`

**関連する別の作業単位**

- 女性プロフィールのSEO出力改善: [20260815_PROBLEM_girls-profile-seo_20260920_2.md](20260815_PROBLEM_girls-profile-seo_20260920_2.md)

**移行時の状態判定**: GitHub反映まで記録済み。本番の301・404・有効人物応答と関連影響は未確認。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: GitHub反映まで記録済み。本番の301・404・有効人物応答と関連影響は未確認。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
