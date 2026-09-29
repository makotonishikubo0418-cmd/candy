# パンくず全体の整合性修正 — 2026-08-15の作業記録

- History: [20260815_PROBLEM_breadcrumb-closure.md](../20260815_PROBLEM_breadcrumb-closure.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-15
- 旧Task ID: `TASK-20260815-BREADCRUMB-CLOSURE-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 39行目

**当時の依頼**

> Resolve every remaining indexable-page inconsistency between visible breadcrumbs and BreadcrumbList, including the approved PC/SP placement and dynamic girls-profile identity

### 対応

> Corrected the final BreadcrumbList names in `area.html`, `blog.html`, and `hotel.html`; replaced the legacy PC-only English labels or missing visible breadcrumbs on `girls_list.html`, `schedule.html`, `movie.html`, and `news.html` with Japanese paths matching their JSON-LD; added the same shared presentation to dynamic `girls.html`; made the girls final breadcrumb label, H1, and final BreadcrumbList URL use the resolved woman's name and canonical URL; added scoped `breadcrumb.css`; synchronized seven newly affected sitemap dates; regenerated routed current-state documents; registered the atomic closure case; updated the girls SEO case so the later approved breadcrumb decision supersedes its former exclusion

### 結果

**旧記録の確認結果**

> PHP lint passed; special characters including quotes, ampersand, angle brackets, newline, and `</script>` round-tripped through JSON without a literal script terminator; the public ledger contains 149 pages, of which 146 use source HTML and three direct PHP routes are member/privacy special pages; all 138 indexable pages with breadcrumbs parse and have exact visible/JSON name equality, with zero mismatches, JSON-only pages, visible-only pages, or JSON parse errors; the only three indexable pages without breadcrumbs are the intended top and two system entry pages; PC and SP browser checks passed on all five newly presented page types with one visible breadcrumb, no horizontal overflow, PC below the menu, and SP immediately above the black footer; target state rows report `seo=OK`, and sitemap synchronization changed exactly seven URLs

**旧記録の補足・未確認事項**

> The global site-state check still fails only for the six preexisting member/privacy findings outside this task; the noindex `create.html` placeholder mismatch is excluded from the public indexable breadcrumb contract; the local dynamic girls test used a deterministic rendered fixture rather than live database data; production HTTP after this local change, Commit, Push, deployment, Actions, Schema Markup Validator, Rich Results Test, Search Console, and the remaining girls-profile SEO case were not performed

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 43行目

旧状態表記: `Complete / GitHub Published`。

旧台帳の次対応:

> Production deployment, production HTTP, external validators, and Search Console require separate execution; production remains unchanged

旧台帳が追加で記録する公開Commit: `b8adf4fa8219c3cf12d7daab04004d380fbbe9ce`。記載された到達点は旧台帳を根拠とする。

**関連する別の作業単位**

- 詳細6ページのパンくず同期: [20260815_PROBLEM_breadcrumb-detail-sync_20260920_1.md](20260815_PROBLEM_breadcrumb-detail-sync_20260920_1.md)
- パンくず修正・プロフィール計画等のGitHub反映: [20260815_OPERATION_aug15-github-publication_20260920_1.md](20260815_OPERATION_aug15-github-publication_20260920_1.md)

**移行時の状態判定**: ローカル検証とGitHub反映は記録済み。本番の動的出力・展開状態は未確認。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: ローカル検証とGitHub反映は記録済み。本番の動的出力・展開状態は未確認。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
