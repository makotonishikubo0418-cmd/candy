# 女性プロフィールのSEO出力改善 — 2026-08-15の作業記録

- History: [20260815_PROBLEM_girls-profile-seo.md](../20260815_PROBLEM_girls-profile-seo.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-15
- 旧Task ID: `TASK-20260815-GIRLS-PROFILE-SEO-PLAN-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 41行目

**当時の依頼**

> Create and preserve the optimal implementation plan, impact analysis, current-defect findings, regression checks, modification boundary, validation, publication, rollback, and completion gates for the approved all-women `girls.php?no={id}` SEO correction

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) 90–105行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 3. Verified Current Implementation and Defect Findings
> 
> | Finding | Verification state | Impact |
> |---|---|---|
> | `HP/girls.php` is a thin route into the common dataset and `source/girls.html` | Verified from source | The correction belongs in the dataset, girls template, and girls-content helper; changing the entry PHP is not expected |
> | Title, description, H1, and OGP are static template forms with replacement tokens | Verified from source | Woman identity is only partly dynamic and the approved text is not produced |
> | ProfilePage remains generic in the baseline plan; the BreadcrumbList final name and URL are now woman-specific in the local worktree | Verified from the post-plan local source | Preserve the completed BreadcrumbList correction while correcting ProfilePage in the remaining implementation |
> | The current canonical token is generated from the resolved woman's stored `no` and excludes added parameters | Verified from source | Preserve this working path and add regression tests; do not rebuild it from raw GET input |
> | The current top-media/OGP token can select video media or a dummy image | Verified from source | Reusing that token for `og:image` or `Person.image` can produce an invalid person-image claim |
> | The content helper defaults missing schedule rows to `お休み` and `次回出勤をご確認ください` | Verified from source | The row data already supports the approved all-women schedule behavior |
> | The renderer hides the whole schedule unless at least one row is a working shift | Verified from source | This is a current conflict with the adopted always-present weekly schedule requirement |
> | A missing or unknown `no` is statically replaced with the first active woman | Verified from source and the constructed production invalid-number test URL | This separately discovered system URL-behavior problem is registered as `CANDY-GIRLS-INVALID-NO-20260816`; do not change it inside this SEO implementation |
> | No committed girls-profile-specific automated test was found | Verified by targeted repository search | Add a focused deterministic regression harness during implementation |
> | Generated static SEO state reports the special girls template as SEO `OK` | Verified from generated state | This is template-token validation, not proof of per-woman runtime values, images, or production behavior |
> | Target state check reports `girls` as `SPECIAL`, SEO `OK`, and images `UNVERIFIED` | Verified locally | Dynamic runtime and image checks remain mandatory |
> | Global state check also reports six member/privacy findings | Verified locally and unrelated | Preserve and report them; do not fix them in this case |

### 決定

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) 17–88行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> The latest adopted decisions override conflicting wording in the earlier supplied instruction:
> 
> - The weekly schedule section exists for every woman. A week with no shifts still renders the section and its existing off/no-schedule messages.
> - `title` and `og:title`: `{名前}のプロフィール・出勤情報｜鹿児島デリヘル キャンディ`
> - H1: `{名前}のプロフィール・出勤情報`. The existing `sr-only` treatment remains; no brand or regional phrase is added to the hidden H1.
> - The description is produced by one deterministic template from content that is linked to the selected woman, published, non-NULL, nonempty after trim, eligible under the frontend visibility predicate, and represented in the actual response HTML.
> - With a real profile image, `og:image` and `Person.image` use that image. Without a real profile image, `og:image` uses the verified CANDY common OGP image and `Person.image` is omitted.
> - Emi's full description is an expected value only when all five optional content groups in that sentence are still public and visible at verification time. The weekly schedule reference is always eligible.
> - Search Console inspection is post-publication verification. If access is unavailable, it is reported as `UNVERIFIED`, not as a completed SEO check.
> - The visible and structured breadcrumb path is `TOP > 女の子一覧 > {名前}のプロフィール・出勤情報`. PC displays it below the main menu; SP displays it immediately above the black footer. The final BreadcrumbList item uses the resolved canonical `girls.php?no={id}` URL.
> 
> ## 2. Fixed Generation Contract
> 
> ### 2.1 Identity and URL
> 
> Use the woman resolved from the authoritative active-woman data, not raw `$_GET` values, for every woman-specific field. The canonical identity must be the same across title, H1, description, OGP, ProfilePage, Person, and the final BreadcrumbList item.
> 
> The canonical and all structured-data profile URLs are:
> 
> `https://www.55810.com/girls.php?no={resolved no}`
> 
> Parameters such as `flw`, `unf`, and `kog` never enter the canonical URL. The existing URL structure and `og:type` remain unchanged.
> 
> The visible breadcrumb and BreadcrumbList use the same three names and hierarchy. The final visible label, final JSON-LD label, and H1 use the same resolved-woman text; the two earlier levels remain `TOP` and `女の子一覧`.
> 
> ### 2.2 Deterministic Description
> 
> The fixed base is:
> 
> `{名前}のプロフィール・週間出勤情報を掲載。`
> 
> Append only the labels for optional groups that satisfy the shared visibility decision, in this order:
> 
> 1. プロフィール動画
> 2. 店長紹介コメント
> 3. 本人Q&A
> 4. 詳細プロフィール
> 5. セールスポイント
> 
> When one or more optional groups qualify, join their labels deterministically and append `も確認できます。`. End every result with `鹿児島デリヘル「キャンディ」公式。`.
> 
> The generator does not write free-form text, infer missing facts, or parse completed HTML during normal request handling. It uses the same normalized visibility facts as the renderer; tests then confirm that each claimed group is present in the actual HTML. Raw manager comments, Q&A answers, and profile values are not inserted into this first implementation of the description because they create unstable length and escaping behavior without being required by the approved expected sentence.
> 
> ### 2.3 Visibility Facts
> 
> Use one normalized decision object or helper result for both rendering and description assembly:
> 
> | Group | Eligibility |
> |---|---|
> | Weekly schedule | Always true after seven schedule rows are built; all-off rows remain visible |
> | Profile movie | At least one usable movie source is actually rendered |
> | Manager comment | The current renderer's published title-or-body predicate is true |
> | Person Q&A | At least one published, nonempty question-and-answer pair is rendered |
> | Detailed profile | At least one of the six published profile fields is nonempty after trim and rendered |
> | Sales point | The current renderer's published title-or-body predicate is true |
> 
> The existing public-content query remains the publication boundary. No second content query is added solely for SEO.
> 
> ### 2.4 Safe Output Contexts
> 
> - HTML text and attribute values use the existing UTF-8 `ENT_QUOTES` HTML escaping path.
> - JSON-LD is built as PHP arrays and serialized with `json_encode`; it is not assembled through unescaped string concatenation and does not reuse HTML-escaped values.
> - JSON serialization must safely handle double quotes, single quotes, ampersands, angle brackets, newlines, and `</script>` without ending the JSON-LD script element.
> - A JSON encoding failure must not emit malformed structured data. The failure is logged and the structured-data block is omitted or fails closed according to the implementation test, while the visible profile page remains functional.
> 
> ### 2.5 Image Decision
> 
> Select a real, visible image belonging to the resolved woman independently from the existing top-media token, because that token can represent a video or a dummy image. Prefer the same first usable profile image displayed by the current gallery rules, preserve its existing public host, and convert it to an absolute HTTPS URL without changing photo or video presentation.
> 
> Reject empty values, video URLs, dummy assets, placeholders, and an image belonging to another woman. If no real profile image is available, omit `Person.image` and use only the verified common OGP fallback for `og:image`.
> 
> `HP/imgHtml/new_202601/sample.jpg` is a local fallback candidate already referenced by multiple current page templates. Its local JPEG is verified at 1000 x 750, 27,497 bytes, SHA-256 `30DAA84777A054BDFFD2C15A502037437FCBED29E9F2B4D620FF8EFE04903367`. It is not designated as the final fallback until its exact HTTPS production URL returns HTTP 200 with `Content-Type: image/*`.

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) 107–143行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 4. Impact Boundary
> 
> ### 4.1 Expected Direct Changes During Implementation
> 
> | File | Planned responsibility |
> |---|---|
> | `HP/source/girls.html` | Replace generic metadata and JSON-LD with context-correct dynamic insertion points; update only the approved H1 text form |
> | `HP/includefile/dataset_girls.php` | Reuse the resolved woman, canonical, loaded content, schedule, movie, and image data to populate one consistent SEO result; do not add duplicate database queries |
> | `HP/includefile/candy_girls_page_content.php` | Centralize visibility facts, deterministic description assembly, safe JSON-LD serialization helpers, and always-visible schedule rendering |
> | `codex/scripts/test_candy_girls_profile_seo.php` | New non-production regression harness for deterministic metadata, visibility, image fallback, and special-character fixtures |
> | `codex/docs/CANDY_SEO_SPEC.md` | Record the permanent girls-profile behavior after the implementation contract is proven |
> | Generated current-state outputs | Regenerate only through the canonical generator after source changes; never hand-edit generated state |
> 
> ### 4.2 Conditional Changes
> 
> - `HP/css/girls_page_content.css`: only if the existing style fails when all seven rows are off. No visual redesign is authorized.
> - `CANDY_OTHER_PAGES_MANAGEMENT.md` or `CANDY_CODE_FILE_STRUCTURE.md`: only if the actual implementation route changes from the currently documented route.
> - Sitemap files: preview only. Current target state has no individual girls-profile sitemap entry, and this case does not add one without a separate verified need and approval.
> 
> ### 4.3 Explicit Exclusions
> 
> Do not change `HP/includefile/dataset_base.php`, `HP/girls.php`, the URL form, image/gallery behavior, video behavior, review links, favorites or my-page behavior, management input forms, database schema or values, unrelated pages, shared SEO behavior, robots policy, or legacy invalid-`no` behavior. The invalid-`no` behavior is tracked separately as `CANDY-GIRLS-INVALID-NO-20260816`. Preserve the later approved visible breadcrumb and its PC/SP placement; do not redesign any other part of the page. Any newly proven issue in those areas is reported with cause and impact before separate authorization.
> 
> ## 5. Regression and Failure Matrix
> 
> | Risk | Prevention | Required proof |
> |---|---|---|
> | Identity from woman A mixes with URL or image from woman B | Build one resolved-woman SEO object and reuse it everywhere | Exact equality assertions across all HTML/OGP/JSON-LD identity fields for three or more women |
> | Raw added parameters alter canonical | Preserve stored `no` canonical construction | Normal and `flw`/`unf`/`kog` URL comparisons |
> | Description claims hidden content | Share normalized visibility facts with renderer | Presence/absence matrix for every optional group plus actual HTML assertions |
> | All-off woman loses the schedule section | Make seven built rows sufficient for schedule visibility | Fixture and rendered DOM showing seven off rows and the approved messages |
> | Video or dummy is emitted as `Person.image` | Separate real-image selection from top media | Real-image, video-first, dummy-only, and no-image fixtures |
> | Special characters break HTML or JSON-LD | Context-specific escaping and `json_encode` | Fixtures containing `"`, `'`, `&`, `<`, `>`, newline, and `</script>`; parse JSON and inspect HTML |
> | Empty dataset causes warnings or a false indexable profile | Guard every resolved-woman access and preserve legacy handling | Invalid, missing, nonnumeric, inactive, and query-failure observations; no new Warning/Notice/Fatal |
> | Existing profile functions regress | Do not alter their routes or presentation | Weekly schedule, movies, comments, Q&A, detail, sales, gallery, review link, favorites/my-page, and PC/SP checks |
> | Static checker passes tokens but runtime output is wrong | Separate source checks from rendered and production checks | Raw HTTP source, rendered DOM, JSON parsing, image HTTP, and production log evidence |
> | A validator shows no rich result and is misreported as failure | Separate schema validity from Google feature eligibility | Schema validation result and Rich Results Test result reported independently |

**詳細資料に記録された判断・根拠** — [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) 145–227行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 6. Optimal Execution Plan
> 
> ### Phase 0 - Authorization and Baseline Freeze
> 
> 1. Receive a separate implementation instruction. Any direct database operation and every Git-state-changing operation requires the specific permission required by project rules.
> 2. Confirm the intended branch, clean/overlapping paths, and live GitHub branch state at the required operation boundary; do not switch branches automatically.
> 3. Preserve baseline raw HTML and rendered evidence for Emi plus two active women selected to cover optional-content and image differences. Prefer one all-off case and one no-image or reduced-content case when such public cases exist.
> 4. Record baseline HTTP status, robots, canonical, metadata, JSON-LD, images, internal links, warnings, and current invalid-`no` behavior. Do not treat a local template check as runtime proof.
> 
> ### Phase 1 - Contract Tests Before Production-Code Edits
> 
> 1. Add fixture coverage for full content, partial content, schedule-only, all-off, real image, video-first media, no real image, missing woman, and special characters.
> 2. Encode exact approved outputs for title, H1, canonical, OGP, ProfilePage, Person, and BreadcrumbList.
> 3. Encode the description order and inclusion/exclusion table, including Emi's conditional expected sentence.
> 4. Make the harness fail against the current generic structured data and hidden all-off schedule behavior.
> 
> ### Phase 2 - Minimal Shared Implementation
> 
> 1. Add pure helper functions for normalization, visibility, deterministic description, image choice, and JSON-LD array construction in `candy_girls_page_content.php`.
> 2. Change schedule visibility so a valid seven-row schedule renders even when every row is off; preserve row wording and CSS.
> 3. In `dataset_girls.php`, build exactly one SEO object after the existing woman, content, schedule, movie, and image data are available. Reuse existing data and queries.
> 4. Replace only the generic metadata, H1 wording, and JSON-LD insertion points in `source/girls.html`.
> 5. Do not touch the common dataset, URL handling, profile layout, image display selection, or unrelated pages.
> 
> ### Phase 3 - Local Static and Deterministic Verification
> 
> 1. Run PHP lint with the production-compatible short-open-tag setting for every changed PHP file.
> 2. Run the focused regression harness and parse every JSON-LD fixture as JSON.
> 3. Confirm HTML escaping and absence of a literal injected `</script>` in JSON-LD output.
> 4. Run the target girls state check, management audit, generated-state preview/write/check as routed, and `git diff --check`.
> 5. Inspect the exact diff and confirm only allowed files changed. Preserve the six unrelated global findings and do not convert them into this case's failures.
> 
> ### Phase 4 - Runtime and Browser Verification Before Publication
> 
> 1. On an environment using representative current data, test Emi and at least two other active women; cover a reduced-content or no-image case and an all-off case when available.
> 2. For each case, compare raw source and rendered DOM for status, robots, title, H1 count/text, description, OGP, canonical, ProfilePage, Person, BreadcrumbList, optional sections, and internal-link reachability.
> 3. Test normal URLs, added-parameter URLs, missing `no`, nonexistent `no`, malformed `no`, and an inactive/retired value if safely observable.
> 4. Exercise gallery/video, review link, favorites/my-page, schedule, and optional content at desktop and mobile widths. Check the browser console and PHP logs for new errors.
> 5. Resolve the formal common OGP fallback and require HTTPS, HTTP 200, and `Content-Type: image/*`; perform the same HTTP checks for each selected real person image.
> 
> ### Phase 5 - Structured-Data and SEO Validation
> 
> 1. Parse every JSON-LD block and assert exact consistency with the HTML and canonical.
> 2. Validate ProfilePage, Person, and BreadcrumbList through Schema Markup Validator or an equivalent schema validator.
> 3. Run Google Rich Results Test when available, but report eligibility separately because ProfilePage is not guaranteed to produce a supported rich result.
> 4. Verify robots remains unchanged, no false content is declared, and no profile image fallback is presented as a Person image.
> 
> ### Phase 6 - Controlled Publication
> 
> 1. Only after explicit Git publication authority, stage an exact allowlist, review the staged diff, Commit, and Push the approved branch.
> 2. Confirm the GitHub SHA and the repository's exact deployment workflow/plan. Require zero unexpected deletions and no protected or unrelated operations.
> 3. Wait for the deployment result and verify the intended production files and public responses; a successful Push or Action alone is not production proof.
> 4. If a regression appears, stop further publication and restore the last known-good implementation through a new reviewed rollback commit and the normal workflow. No database rollback is expected because this case has no database change.
> 
> ### Phase 7 - Post-Publication Completion
> 
> 1. Repeat the full production matrix for Emi and two or more other women, including added parameters, invalid `no`, images, existing functions, PHP/log health, and raw-versus-rendered output.
> 2. Run Search Console URL inspection for the canonical profile URLs when access exists. Otherwise record exactly that it is `UNVERIFIED` due to unavailable access.
> 3. Update canonical specifications and generated state as required, record final changed files and evidence in this parent, close the registry case only when every completion gate is satisfied, and append the completed result to the time-bounded task history.
> 
> ## 7. Stop and Rollback Conditions
> 
> Stop before publication if any woman mismatch, canonical drift, malformed HTML/JSON, dummy/video Person image, false description claim, missing all-off schedule, new PHP Warning/Notice/Fatal, regression in review/favorites/media/content, unexpected generated diff, or out-of-scope file change is found.
> 
> After publication, rollback the exact implementation commit through the normal reviewed workflow if the page becomes unavailable, metadata identity crosses between women, indexable invalid output is newly introduced, JSON-LD breaks page output, or existing profile functions regress. Do not use destructive reset, database mutation, broad file restoration, or unrelated fixes as rollback methods.
> 
> ## 8. Completion Gates and Final Report
> 
> The implementation case is complete only when all of the following are evidenced:
> 
> - Approved title and H1 forms are correct for Emi and two or more other women.
> - Canonical, OGP URL, ProfilePage URL, Person URL, and Breadcrumb final item all use the same resolved woman and exclude added parameters.
> - Description presence rules and actual rendered sections agree.
> - Real-image and no-real-image branches behave exactly as approved.
> - JSON-LD parses safely for normal and special-character fixtures.
> - All-off schedules remain visible.
> - Existing profile functions, PC/SP rendering, PHP health, and internal links have no introduced regression.
> - Production HTTP and image checks pass after deployment.
> - Schema validation and Rich Results Test outcomes are reported separately.
> - Search Console is either verified after publication or explicitly `UNVERIFIED`.
> - Every unresolved legacy issue is reported without being silently changed or marked complete; invalid-`no` behavior is reported through the separate case `CANDY-GIRLS-INVALID-NO-20260816`.
> 
> The final report must list changed files, exact changes, Emi results, at least two other women, canonical parameter results, JSON-LD/schema results, existing-function impact results, production/Actions evidence, Search Console state, and unresolved items.

以下のPhase 0〜7は当時採用された計画と完了条件であり、全工程が実行済みという意味ではない。後続の実装記録と本番未確認事項を分けて読む。

### 対応

> Registered `CANDY-GIRLS-SEO-20260815`; created its case parent; reconciled the supplied instruction with the later adopted weekly-schedule, H1, description, image-fallback, Emi, validation, and Search Console decisions; updated both formal trees and this history index. The plan identifies expected and conditional implementation files, explicit exclusions, one shared visibility/description contract, safe HTML/JSON contexts, a focused regression harness, eight execution phases, stop/rollback conditions, and final evidence requirements. No HP source, PHP, CSS, generated output, database, Git state, deployment, or production state was changed

### 結果

**旧記録の確認結果**

> Live source inspection verified the route `HP/girls.php` to `HP/includefile/dataset_girls.php` and `HP/source/girls.html`, the additional-content helper, generic current ProfilePage/BreadcrumbList values, resolved-woman canonical construction, all-off schedule suppression, video-or-dummy risk in the shared top-media token, and legacy invalid-`no` fallback. The local common-image candidate is a 1000 x 750 JPEG of 27,497 bytes with SHA-256 `30DAA84777A054BDFFD2C15A502037437FCBED29E9F2B4D620FF8EFE04903367`. Management audit passed 69 repository Markdown files classified as 62 formal files and seven technical references plus five sidecars and matching 67-file trees; `candy-site-state audit` returned `AUDIT=OK`; `git diff --check` passed

**旧記録の補足・未確認事項**

> Per-woman runtime/database values, Emi's current six published areas, a qualifying all-off/no-image comparison woman, production HTTP and image responses, invalid-`no` HTTP/robots behavior, browser/PC/SP behavior, PHP logs, Schema Markup Validator, Rich Results Test, Search Console, implementation tests, Git publication, Actions, and production remain unverified and unperformed. The global state check retains six unrelated member/privacy findings. Direct database operations, source implementation, Stage, Commit, Push, branch change, deployment, and production mutation were not performed

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
