# Candy Girls Invalid-Number Response Behavior

- Purpose: Correct the separately discovered problem in which a missing or invalid girls number could render an unrelated woman's profile
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Missing, empty, malformed, and active-woman-unresolved `no` values supplied to `girls.php`
- Status / Lifecycle: Local implementation complete / Active
- Source of Truth Responsibility: Individual detail for case `CANDY-GIRLS-INVALID-NO-20260816`
- Related Documents: [`DEFECT_RESPONSE_HISTORY.md`](../records/DEFECT_RESPONSE_HISTORY.md), [`CANDY_FIX_BACKLOG.md`](../../docs/CANDY_FIX_BACKLOG.md), [`CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md`](CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md), [`CANDY_OTHER_PAGES_MANAGEMENT.md`](../../docs/CANDY_OTHER_PAGES_MANAGEMENT.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: [`HP/girls.php`](../../../HP/girls.php), [`HP/includefile/dataset_girls.php`](../../../HP/includefile/dataset_girls.php), and [`test_candy_girls_invalid_no_behavior.py`](../../scripts/test_candy_girls_invalid_no_behavior.py)
- Case ID: `CANDY-GIRLS-INVALID-NO-20260816`
- Updated: 2026-08-19

## 1. Recorded Problem

A nonexistent woman number can return HTTP 200 and render another active woman's profile. The user classified this as a separately discovered system URL-behavior problem and instructed that it be recorded independently from the approved girls-profile SEO remediation.

## 2. Implementation-Verified Cause

`HP/includefile/dataset_girls.php` resolves the requested `no` through the active-woman lookup. When it does not resolve, the current code selects the first active woman and replaces `no` with that woman's stored number.

## 3. Production-Verified Example

On 2026-08-16, the constructed test request `https://www.55810.com/girls.php?no=999999999` produced:

- HTTP status: `200`
- robots: `index`
- rendered woman: `ユリ`
- canonical: `https://www.55810.com/girls.php?no=1479`

The test URL was created for the investigation. It was not discovered from a current website internal link or `sitemap.xml` entry.

## 4. Classification Boundary

- Primary classification: System URL behavior
- Relationship to `CANDY-GIRLS-SEO-20260815`: Separate discovered problem; excluded from that remediation
- Current direct SEO impact: `UNVERIFIED`
- Current external inbound link, bookmark, crawl, or index state: `UNVERIFIED`
- Priority or severity: Not decided by this record

## 5. Adopted Response Contract

On 2026-08-19, the user approved and instructed implementation of this exact behavior:

- `girls.php` with missing or empty `no` returns `301` to the same-host `girls_list.php` route.
- A non-scalar `no` returns HTTP `404` before the common dataset is loaded.
- A scalar `no` that does not resolve to an active woman returns HTTP `404` from `dataset_girls.php` and renders the existing noindex 404 body.
- A resolved active woman keeps the existing indexable profile response and woman-specific canonical URL.
- Do not apply `noindex` to `girls.php` as a whole.

## 6. Local Implementation and Verification

`HP/girls.php` now handles missing, empty, and malformed input before loading the common dataset. `HP/includefile/dataset_girls.php` no longer selects the first active woman when the requested number does not resolve.

Local verification completed:

- `girls.php` without `no`: HTTP `301`, `Location: girls_list.php`
- non-scalar `no`: HTTP `404`, existing 404 body contains `noindex,follow`
- PHP 8.3 lint: both changed PHP files pass
- Existing girls-profile SEO regression: `PASS`, 53 assertions
- Invalid-number focused regression: `PASS`, 5 assertions

## 7. Remaining Work

Commit, Push, GitHub publication, deployment, production HTTP verification, production database-backed valid/invalid-number comparison, access-log review, external inbound-link state, and Search Console remain unperformed or unverified. They require their separately authorized routes.
