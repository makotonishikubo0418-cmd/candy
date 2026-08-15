# Candy Girls Invalid-Number Response Behavior

- Purpose: Preserve the separately discovered problem in which a nonexistent girls number returns HTTP 200 and renders another woman's profile
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Invalid `no` values supplied to `girls.php?no={id}`; this record does not define or implement a remedy
- Status / Lifecycle: AWAITING_APPROVAL / Active
- Source of Truth Responsibility: Individual detail for case `CANDY-GIRLS-INVALID-NO-20260816`
- Related Documents: [`DEFECT_RESPONSE_HISTORY.md`](../records/DEFECT_RESPONSE_HISTORY.md), [`CANDY_FIX_BACKLOG.md`](../../docs/CANDY_FIX_BACKLOG.md), [`CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md`](CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md), [`CANDY_OTHER_PAGES_MANAGEMENT.md`](../../docs/CANDY_OTHER_PAGES_MANAGEMENT.md), and [`TASK_LOG_2026_08.md`](../task_history/TASK_LOG_2026_08.md)
- Related Implementation Files: [`HP/includefile/dataset_girls.php`](../../../HP/includefile/dataset_girls.php)
- Case ID: `CANDY-GIRLS-INVALID-NO-20260816`
- Updated: 2026-08-16

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

## 5. Exclusions and Next Action

This record does not adopt a 404, redirect, noindex, fallback, or other response design. It authorizes no implementation, database operation, deployment, production mutation, Commit, or Push.

Preserve the recorded behavior until the user separately instructs investigation, prioritization, or modification of this case.
