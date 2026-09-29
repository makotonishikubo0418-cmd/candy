# 内部ディレクトリへのHTTPアクセス制御 — 2026-08-17の作業記録

- History: [20260817_PROBLEM_internal-path-access.md](../20260817_PROBLEM_internal-path-access.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-17
- 旧Task ID: `TASK-20260817-INTERNAL-PATH-ACCESS-CONTROL-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 34行目

**当時の依頼**

> Execute local Phases 1 through 3 for direct HTTP access control of generation-source HTML and server-side include files

### 決定

**詳細資料に記録された判断・根拠** — [CANDY_INTERNAL_PATH_ACCESS_CONTROL.md](../履歴/CANDY_INTERNAL_PATH_ACCESS_CONTROL.md) 17–91行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 2. Implementation-Verified Structure
> 
> The current rendering route is:
> 
> ```text
> HP/<page>.php
>   -> HP/includefile/dataset_base.php
>   -> HP/source/<page>.html
>   -> HP/includefile/dataset_<page>.php and common processing
>   -> completed HTML response
> ```
> 
> `HP/source/*.html` is generation input, not an independent formal page. `HP/includefile/**` is server-side implementation. `HP/source/style.css` is a public stylesheet referenced by formal pages and is not generation-only HTML.
> 
> ## 3. Verified Baseline
> 
> The 2026-08-17 production HTTP investigation established the following pre-change behavior:
> 
> - `/source/`, `/source/mypage.html`, and `/source/template_girls.html` returned HTTP `200`.
> - Direct source HTML exposed unreplaced `rep...eot` tokens and source metadata.
> - `/source/style.css` returned HTTP `200` and is referenced by public source templates.
> - `/includefile/` returned HTTP `403`, but directly requested PHP files including `dataset_base.php`, `dataset_mypage.php`, `class.hpgcoder2.php`, `funcs.php`, and `member/bootstrap.php` returned HTTP `200`.
> - The sitemap contains no `/source/` URL, and the repository contains no browser-facing direct reference to `/includefile/`.
> 
> External clients not represented by repository references remain `UNVERIFIED` because production access logs are outside the authorized scope.
> 
> ## 4. Adopted Access Contract
> 
> | Request target | Required result | Reason |
> |---|---:|---|
> | `/source/` | `404` | The generation-source directory is not a public route |
> | `/source/*.html` including templates | `404` | Source HTML is not an independent formal page |
> | `/source/style.css` | `200` | Formal public pages load this stylesheet |
> | `/includefile/` and `/includefile/**` | `403` | Server-side implementation must not be directly retrievable |
> | Public PHP, CSS, JavaScript, images, and other existing public assets | Existing contract unchanged | They remain the formal public response and its assets |
> 
> The rules MUST run after canonical scheme/host redirects and before explicit `index.php` or `index.html` removal. This order makes `/source/index.html` return `404` instead of redirecting to `/source/`.
> 
> ## 5. Scope and Exclusions
> 
> Included in the current instruction:
> 
> - Case registration and permanent access-boundary specifications
> - Deterministic tests for source HTML `404`, include paths `403`, and `source/style.css` `200`
> - A minimal `HP/.htaccess` rule change
> - Local syntax, regression, management, and site-state checks required by the routed documents
> 
> Excluded from the completed Phase 1 through 3 instruction:
> 
> - Changes to public PHP, source HTML contents, datasets, shared PHP processing, JSON-LD, canonical, robots, sitemap, CSS bytes, JavaScript, images, or database behavior
> - Git Stage, Commit, Push, branch changes, GitHub publication, Actions execution, FTP, production deployment, Search Console, and production mutation
> - Access-log investigation and compatibility decisions for unknown external callers
> 
> The 2026-08-17 Phase 4 instruction separately authorizes Stage, separated Commits, Push to the existing verified branch, the protected `.htaccess` workflow preview, and the one-file production deployment. It does not authorize a branch change, database work, Search Console changes, access-log investigation, or unrelated production changes.
> 
> ## 6. Phases
> 
> | Phase | Purpose | Start condition | Completion condition | Status | Deliverables | Transition condition |
> |---|---|---|---|---|---|---|
> | 1. Case registration | Preserve scope, exclusions, decisions, phases, and completion gates | User instruction received | Registry, defect-history route, case parent, formal trees, canonical specifications, and management audit agree | COMPLETE | This case and routed management updates | Management audit passed |
> | 2. Verification automation | Add reusable HTTP-contract checks | Phase 1 complete | Positive and negative regression tests pass and protected workflow invokes the production access check | COMPLETE | Release-check function, CLI mode, tests, and workflow integration | Focused tests passed |
> | 3. `.htaccess` implementation | Implement only the adopted access rules in `HP/.htaccess` | Phase 2 complete | Apache-rule validation and all applicable existing tests pass without a new site-state failure | COMPLETE | Minimal `.htaccess` change | Local checks passed |
> | 4. Publication and production verification | Publish and deploy through the protected one-file route | Separate explicit Git and production authorization | Exact one-file deployment succeeds and production HTTP matches every access-contract row while the entry contract remains valid | COMPLETE | GitHub publication, protected deployment, and production evidence | Case completed |
> 
> ## 7. Completion Criteria
> 
> The current user instruction covering Phases 1 through 3 is complete only when:
> 
> - The management audit passes after registration and specification updates.
> - Focused positive and negative access-control tests pass.
> - `HP/.htaccess` contains only the required new access-control behavior.
> - Apache-rule validation and the applicable existing regression tests pass.
> - The full site-state check introduces no finding beyond its separately recorded preexisting baseline.
> 
> The case itself MUST remain active until the separately authorized Phase 4 deploys the isolated `HP/.htaccess` change and production HTTP verifies source HTML `404`, include paths `403`, `source/style.css` `200`, and the unchanged public entry contract.

### 対応

> Registered `CANDY-INTERNAL-PATH-ACCESS-20260817` with scope, exclusions, decisions, phases, and completion gates; routed it through Defect and Response History; synchronized both formal trees and the code, SEO, verification, and deployment contracts; added an independent release-check mode for source HTML `404`, include-path `403`, and `source/style.css` `200`; added positive, negative, `.htaccess` rule/order, and workflow-integration regression assertions; connected the protected `.htaccess` workflow to the production access check; added only three access rules to `HP/.htaccess`. No public PHP, source HTML content, include PHP content, CSS bytes, JavaScript, images, JSON-LD, canonical, robots, sitemap, database, Git state, Commit, Push, deployment, or production state was changed

### 結果

**旧記録の確認結果**

> Management audit passed 69 formal Markdown files, seven technical references, five sidecars, and matching 74-file trees with zero failures; Python syntax, workflow YAML, release-check self-test and integration, FTP self-test and integration, site-state metadata, `candy-site-state audit`, `.htaccess` rule uniqueness/order/path matching, `source/style.css` exclusion, and `git diff --check` passed; the full site-state check introduced no result beyond the same six preexisting member/privacy findings

**旧記録の補足・未確認事項**

> No local `httpd`, `apachectl`, Apache container runtime, test-server deployment, or production deployment was available, so actual Apache execution of the new rules remains unverified; Git publication, protected one-file deployment, production HTTP, access logs, Search Console, and external callers not represented in repository references remain unperformed or unverified

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
