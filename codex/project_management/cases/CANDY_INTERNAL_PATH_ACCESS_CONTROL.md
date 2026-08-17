# Candy Internal-Path HTTP Access Control

- Purpose: Restrict direct HTTP access to generation-source HTML and server-side include files while preserving the current public PHP rendering route
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: HTTP access to `HP/source/`, `HP/source/*.html`, `HP/source/style.css`, and `HP/includefile/**`; local verification automation; the protected `.htaccess` publication route
- Status / Lifecycle: IN_PROGRESS / Active
- Source of Truth Responsibility: Individual detail for case `CANDY-INTERNAL-PATH-ACCESS-20260817`
- Related Documents: [`DEFECT_RESPONSE_HISTORY.md`](../records/DEFECT_RESPONSE_HISTORY.md), [`CANDY_CODE_FILE_STRUCTURE.md`](../../docs/CANDY_CODE_FILE_STRUCTURE.md), [`CANDY_SEO_SPEC.md`](../../docs/CANDY_SEO_SPEC.md), [`CANDY_VERIFICATION_PLAN.md`](../../docs/CANDY_VERIFICATION_PLAN.md), [`CANDY_PRODUCTION_MIGRATION_MASTER.md`](../../docs/CANDY_PRODUCTION_MIGRATION_MASTER.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: [`HP/.htaccess`](../../../HP/.htaccess), [`.github/scripts/candy_release_check.py`](../../../.github/scripts/candy_release_check.py), [`.github/scripts/test_candy_release_check.py`](../../../.github/scripts/test_candy_release_check.py), and [`.github/workflows/candy-htaccess-deploy.yml`](../../../.github/workflows/candy-htaccess-deploy.yml)
- Case ID: `CANDY-INTERNAL-PATH-ACCESS-20260817`
- Updated: 2026-08-17

## 1. Objective

Keep public PHP as the only formal page route while preventing browsers and crawlers from retrieving its raw generation inputs or server-side include implementation directly.

## 2. Implementation-Verified Structure

The current rendering route is:

```text
HP/<page>.php
  -> HP/includefile/dataset_base.php
  -> HP/source/<page>.html
  -> HP/includefile/dataset_<page>.php and common processing
  -> completed HTML response
```

`HP/source/*.html` is generation input, not an independent formal page. `HP/includefile/**` is server-side implementation. `HP/source/style.css` is a public stylesheet referenced by formal pages and is not generation-only HTML.

## 3. Verified Baseline

The 2026-08-17 production HTTP investigation established the following pre-change behavior:

- `/source/`, `/source/mypage.html`, and `/source/template_girls.html` returned HTTP `200`.
- Direct source HTML exposed unreplaced `rep...eot` tokens and source metadata.
- `/source/style.css` returned HTTP `200` and is referenced by public source templates.
- `/includefile/` returned HTTP `403`, but directly requested PHP files including `dataset_base.php`, `dataset_mypage.php`, `class.hpgcoder2.php`, `funcs.php`, and `member/bootstrap.php` returned HTTP `200`.
- The sitemap contains no `/source/` URL, and the repository contains no browser-facing direct reference to `/includefile/`.

External clients not represented by repository references remain `UNVERIFIED` because production access logs are outside the authorized scope.

## 4. Adopted Access Contract

| Request target | Required result | Reason |
|---|---:|---|
| `/source/` | `404` | The generation-source directory is not a public route |
| `/source/*.html` including templates | `404` | Source HTML is not an independent formal page |
| `/source/style.css` | `200` | Formal public pages load this stylesheet |
| `/includefile/` and `/includefile/**` | `403` | Server-side implementation must not be directly retrievable |
| Public PHP, CSS, JavaScript, images, and other existing public assets | Existing contract unchanged | They remain the formal public response and its assets |

The rules MUST run after canonical scheme/host redirects and before explicit `index.php` or `index.html` removal. This order makes `/source/index.html` return `404` instead of redirecting to `/source/`.

## 5. Scope and Exclusions

Included in the current instruction:

- Case registration and permanent access-boundary specifications
- Deterministic tests for source HTML `404`, include paths `403`, and `source/style.css` `200`
- A minimal `HP/.htaccess` rule change
- Local syntax, regression, management, and site-state checks required by the routed documents

Excluded from the completed Phase 1 through 3 instruction:

- Changes to public PHP, source HTML contents, datasets, shared PHP processing, JSON-LD, canonical, robots, sitemap, CSS bytes, JavaScript, images, or database behavior
- Git Stage, Commit, Push, branch changes, GitHub publication, Actions execution, FTP, production deployment, Search Console, and production mutation
- Access-log investigation and compatibility decisions for unknown external callers

The 2026-08-17 Phase 4 instruction separately authorizes Stage, separated Commits, Push to the existing verified branch, the protected `.htaccess` workflow preview, and the one-file production deployment. It does not authorize a branch change, database work, Search Console changes, access-log investigation, or unrelated production changes.

## 6. Phases

| Phase | Purpose | Start condition | Completion condition | Status | Deliverables | Transition condition |
|---|---|---|---|---|---|---|
| 1. Case registration | Preserve scope, exclusions, decisions, phases, and completion gates | User instruction received | Registry, defect-history route, case parent, formal trees, canonical specifications, and management audit agree | COMPLETE | This case and routed management updates | Management audit passed |
| 2. Verification automation | Add reusable HTTP-contract checks | Phase 1 complete | Positive and negative regression tests pass and protected workflow invokes the production access check | COMPLETE | Release-check function, CLI mode, tests, and workflow integration | Focused tests passed |
| 3. `.htaccess` implementation | Implement only the adopted access rules in `HP/.htaccess` | Phase 2 complete | Apache-rule validation and all applicable existing tests pass without a new site-state failure | COMPLETE | Minimal `.htaccess` change | Local checks passed |
| 4. Publication and production verification | Publish and deploy through the protected one-file route | Separate explicit Git and production authorization | Exact one-file deployment succeeds and production HTTP matches every access-contract row while the entry contract remains valid | IN_PROGRESS | GitHub publication, protected deployment, and production evidence | Case may be completed |

## 7. Completion Criteria

The current user instruction covering Phases 1 through 3 is complete only when:

- The management audit passes after registration and specification updates.
- Focused positive and negative access-control tests pass.
- `HP/.htaccess` contains only the required new access-control behavior.
- Apache-rule validation and the applicable existing regression tests pass.
- The full site-state check introduces no finding beyond its separately recorded preexisting baseline.

The case itself MUST remain active until the separately authorized Phase 4 deploys the isolated `HP/.htaccess` change and production HTTP verifies source HTML `404`, include paths `403`, `source/style.css` `200`, and the unchanged public entry contract.

## 8. Local Verification Result

- Management audit: `PASS`; 69 formal Markdown files, seven source-attached technical references, five generated sidecars, and matching 74-file router/README trees; zero catalog, link, table, parent, classification, or capacity failures.
- Access regression: `PASS`; the checker covers source directory, representative source HTML and template, public source CSS, include directory, top-level include file, and nested include file. Negative cases reject exposed source/include files and a missing stylesheet.
- `.htaccess` rule contract: `PASS`; required rule uniqueness, canonical-before-access-before-index order, representative path matching, and `source/style.css` exclusion are deterministic regression assertions.
- Deployment automation: release-check self-test, release integration test, FTP self-test, FTP integration test, workflow YAML parse, Python syntax, and Git diff whitespace checks passed.
- Generated state: metadata test and `candy-site-state audit` passed. The full `candy-site-state check` reports only the same six preexisting other-page findings: `member_login`, `member_logout`, `member_mypage`, `member_password_reset`, `member_register`, and `privacy`.
- Actual Apache execution of the new rules remains `UNVERIFIED` because no local `httpd`, `apachectl`, Apache container runtime, test-server deployment, or production deployment was available in the authorized scope. The protected workflow and production HTTP check are the authoritative runtime validation in Phase 4.

## 9. Remaining Work

Publish the management and verification changes separately from the isolated `HP/.htaccess` Commit, run the protected preview, deploy the approved one-file plan, and verify production HTTP. Access logs and Search Console remain excluded.
