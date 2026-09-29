# 女性プロフィールのSEO出力改善 — 2026-08-16の作業記録

- History: [20260815_PROBLEM_girls-profile-seo.md](../20260815_PROBLEM_girls-profile-seo.md)
- Record Date: 2026-09-20
- Sequence: 2
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-16
- 旧Task ID: `TASK-20260816-GIRLS-PROFILE-SEO-IMPLEMENTATION-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 35行目

**当時の依頼**

> Implement, audit, correct, and re-audit the five approved girls-profile corrections without changing invalid-number behavior or production

### 対応

> Replaced legacy title/description/OGP tokens and generic structured data with one woman-specific SEO result; centralized deterministic visibility, description, image selection, JSON-LD, and token application helpers; kept the seven-day schedule visible even when all rows are off; added the focused PHP regression harness; taught the exact girls static audit token to recognize the dynamic JSON-LD insertion; transferred the proven contract to the common SEO specification and current case records. No CSS, database query/schema/value, URL, robots, invalid-number behavior, branch, Stage, Commit, Push, deployment, or production state was changed

### 結果

**旧記録の確認結果**

> PHP 8.3 lint passes for all three PHP files; the focused harness passes 53 assertions covering exact metadata, optional-content visibility, image branches, all-off schedules, special characters, and JSON failure; Python no-write syntax compile passes; target girls state is `seo=OK`; generated-state audit passes; the common OGP fallback returns HTTPS 200 with JPEG content; local PC 1280 x 900 and SP 390 x 844 rendering show the approved title/H1, one parseable ProfilePage/BreadcrumbList JSON-LD block, seven visible all-off rows, no horizontal overflow, and zero browser warnings; Schema Markup Validator reports two items with zero errors/warnings and Google Rich Results Test reports one valid BreadcrumbList plus one valid ProfilePage for local rendered HTML

**旧記録の補足・未確認事項**

> At original task completion, Commit, Push, Actions, deployment, and production verification were unperformed. The five-item implementation was subsequently pushed in Commit `5ea270eb4fd79d398c68e85a29e0d511ec338f29`, which is contained in live GitHub `main`. Live database-backed comparison, production profile-image responses, production PHP/logs, production validators, production HTML/HTTP, and Search Console remain `UNVERIFIED`

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) 229–235行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 9. Current Position
> 
> - Plan and static impact analysis: Complete.
> - Local implementation: Complete for the approved title/OG title, deterministic description/OG description, woman-specific ProfilePage/Person, real-profile/common-OGP image branching, and always-visible seven-day schedule. The prior visible PC/SP breadcrumb and H1 subset remains intact. One shared SEO object and one shared visibility result now drive the corresponding outputs without new database queries.
> - Local audit: The focused PHP harness passes 53 assertions covering exact metadata, deterministic optional-content order, ProfilePage/Person/Breadcrumb identity, real-image/no-image/video-first/dummy rejection, all-off schedule rendering, actual section visibility, special characters, and JSON-encoding failure. PHP lint, static source audit, generated-state audit, PC 1280 x 900 rendering, SP 390 x 844 rendering, seven all-off rows, zero horizontal overflow, JSON parsing, and zero browser warnings pass. The verified common OGP fallback returns HTTPS 200 with `Content-Type: image/jpeg`. Schema Markup Validator reports two items with zero errors and zero warnings, and Google Rich Results Test reports one valid BreadcrumbList and one valid ProfilePage for the deterministic local rendered HTML.
> - Remaining verification: Live database-backed comparison for Emi and at least two other women, real production profile-image HTTP checks, production PHP-version/log verification, validator reruns against the deployed production URL, deployment, production HTTP, Actions, and Search Console remain `UNVERIFIED`. Invalid-`no` behavior remains owned by the separate case `CANDY-GIRLS-INVALID-NO-20260816`, whose implementation is also GitHub-published but still awaits production verification.
> - Publication state: The earlier breadcrumb/H1 subset is published in Commit `b8adf4fa8219c3cf12d7daab04004d380fbbe9ce`. The five-item implementation, focused regression, specification, generated state, and case records are published in Commit `5ea270eb4fd79d398c68e85a29e0d511ec338f29`. Both Commits are contained in live GitHub `main`. No production deployment, production mutation, database operation, production-URL validator run, or Search Console operation is established by this GitHub publication evidence.

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 45行目

旧状態表記: `GitHub Published / Active`。

旧台帳の次対応:

> Verify live database-backed women, real production profile images, production PHP and logs, production URL validators, deployment result, production HTTP, and Search Console. These production and live-data items remain `UNVERIFIED`

旧台帳が追加で記録する公開Commit: `b8adf4fa8219c3cf12d7daab04004d380fbbe9ce`。記載された到達点は旧台帳を根拠とする。

**関連する別の作業単位**

- 女性番号不正時の別人物表示の修正: [20260816_PROBLEM_girls-invalid-number_20260920_2.md](20260816_PROBLEM_girls-invalid-number_20260920_2.md)
- パンくず修正・プロフィール計画等のGitHub反映: [20260815_OPERATION_aug15-github-publication_20260920_1.md](20260815_OPERATION_aug15-github-publication_20260920_1.md)

**移行時の状態判定**: 本番DBを使った人物比較、実画像HTTP、PHP稼働状態、デプロイ・本番URL検証等が未確認。Search Consoleは利用可否を含め明示が必要。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: 本番DBを使った人物比較、実画像HTTP、PHP稼働状態、デプロイ・本番URL検証等が未確認。Search Consoleは利用可否を含め明示が必要。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
