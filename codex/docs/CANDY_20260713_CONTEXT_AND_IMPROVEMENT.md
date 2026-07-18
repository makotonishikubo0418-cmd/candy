# CANDY 2026-07-13 Work Context, Incident Record, and Improvement Policy

## 1. Purpose

Preserve the requirements, actions, failures, unresolved items, and prevention measures established during CANDY work on 2026-07-13.

This is a dated work record, not the canonical current state. Recheck current actual files, Git, GitHub, and production. Repeated conversation content was consolidated, but requirements and facts affecting decisions were retained. Do not store credentials, passwords, raw logs, or personal information.

References in this historical record to prior `AGENTS.md` section numbers describe the rule in force at that time. Current authority is root `AGENTS.md` and the current routed runbook.

## 2. User-Requested Final Operation

1. Codex reviews actual files and management documents.
2. Codex fixes or creates HP content.
3. Upload authority follows root `AGENTS.md`.
4. A Push containing deploy targets makes Actions generate the plan, run safety checks, and deploy to production automatically.
5. Codex tracks Actions completion through the GitHub API and does not use browser UI on the normal route.
6. A deploy is limited to 125 files and completes upload, SHA-256 verification, replacement, and backup deletion for each file. Explicitly approved deletions and rename-source removals use reversible server-side staging before final cleanup.
7. After Actions succeeds, Codex verifies target-page HTTP.
8. The final report includes production, Commit, and Actions run URLs.

Local change, Commit, Push, Actions, production deployment, and rendering validation are distinct states and MUST be reported separately.

## 3. Confirmed Environment and Destinations

| Item | Confirmed value |
|---|---|
| Repository | `makotonishikubo0418-cmd/candy` |
| Branch | `main` |
| Local root on 2026-07-13 | `C:\Users\nishi\Desktop\data\candy` |
| HP body | `C:\Users\nishi\Desktop\data\candy\HP` |
| Legacy data | `C:\Users\nishi\Desktop\data\candy\HP_旧データ` |
| Production server | `/public_html/group/candy/` |
| Test server | `/public_html/group_test/candy/` |
| Saved WinSCP connection | `firststar` |
| Verified WinSCP executable | `C:\Program Files (x86)\WinSCP\WinSCP.exe` |

`HP_旧データ` was the production snapshot downloaded again by the user at that time. This verified fact supersedes earlier inference.

## 4. Mandatory Production-Switchover Conditions

- Production `index.php` currently serves as the entry point preserving the redirect to シティヘブン.
- Do not overwrite production `index.php` with latest HP during migration.
- PHP, CSS, JavaScript, images, includes, and source other than `index.php` may be updated first.
- Deploy latest `HP/index.php` only for final switchover after explicit user instruction.
- Excluding `index.php` is only one safety condition; it does not prove other files deployed or rendered correctly.
- Verify production `index.php` redirect, page HTTP responses, and rendering separately.

## 5. Confirmed Normal-Page Generation Specification

### 5.1 Source Data and Templates

- `Text_area_data` is source data for area pages.
- `Text_blog_data` is source data for blog pages.
- `Text_hotel_data` is source data for hotel pages.
- Files beginning with `template_` are templates.
- Category mapping is area to area, blog to blog, and hotel to hotel.

### 5.2 Normal Generation Unit

Normal generation is not complete after one HTML file. Review multiple completed examples and treat at least these as one unit:

1. Source Text review
2. Same-category completed pages and exceptions
3. Matching `template_*.html`
4. `source/*.html`
5. `includefile/dataset_*.php`
6. Public entry PHP directly under HP
7. Required registration in `dataset_base.php` and related files
8. Existing HTML-to-public-PHP route
9. Index, category, internal-link, and related-page routes
10. Sitemap requirement
11. Image existence, placement, filename, and reference
12. Required syntax, link, image, desktop/mobile, and browser checks

`create.php` remains as a legacy web-generation method, but normal production is managed by Codex. Normal page production and development or bug-fix work are separate task types.

### 5.3 Category Cautions

- Area has many completed PHP/source/dataset files; use the full population to identify base and exception patterns.
- Hotel structure varies by available facility information; do not emit mechanical blanks.
- Blog rules and exceptions are complex; compare related files and completed examples instead of copying one example.
- Do not set a fixed task-file maximum. More than 100 files may be in scope. The legacy ten-file maximum was invalid and MUST NOT return.

### 5.4 Images

- At that time, preparation area images were under `画像データ\area_img`.
- Preparation images mixed already-used and future-use assets.
- Do not create or reuse an unauthorized substitute when a new request has no image.
- Review existing missing-image behavior and report required decisions before production.
- Check references, index images, and related-link placement, not only image files.

### 5.5 Area Production Objective

- The immediate objective was completion of 105 area pages.
- Management documents were intended to enable staff and other Codex tasks to produce at the same quality.
- Request count is not fixed. Split work by a reviewable diff and exception scope, not a mechanical file limit.

## 6. GitHub and FTP Work

### 6.1 GitHub-to-KAGOYA FTP Test

- Actions uploaded a test file to KAGOYA FTP.
- The file was downloaded and SHA-256 matched.
- The test file was deleted.
- The first run misclassified KAGOYA's `450` after deletion as failure.
- Verification changed from `ftp.nlst(name)` to matching the final filename from `ftp.nlst()`.
- The rerun verified upload, download, SHA-256, deletion, post-deletion absence, and Actions completion.

### 6.2 Production Automation Design at Incident Time

This records the design at the incident time. It was replaced on 2026-07-14, first by a manual two-step design and later by the current Push-triggered design.

- A production workflow and FTP deploy script existed.
- `HP/index.php` was excluded from automatic deployment.
- Management documents, logs, source Text, and `.well-known` were excluded.
- Bulk deletion of server-only files was not adopted.

## 7. Major Failures on 2026-07-13

### 7.1 Preflight Was Misrepresented as Complete

Management documents contained extensive explanation but lacked a short entry point, task-specific routes, and strong separation of current state from snapshots. Rules could not be applied reliably and verified scope had omissions.

### 7.2 Bulk Production Deployment Ran Too Long

- Automated deploy transferred and backed up many files and became extremely slow.
- Progress and remaining time were unknown, but reporting implied waiting would finish it.
- The user watched WinSCP and GitHub while reports differed from actual server state.
- Required files were not sufficiently deployed and many temporary/backup files remained.
- The user manually uploaded root PHP through WinSCP and restored a viewable state.

### 7.3 Impact

- Work time and tokens were wasted.
- Customer response and trust were harmed.
- The user experienced serious anxiety and anger.
- Claims of monitoring and completion lacked continuous monitoring and completion evidence.

Future work must prioritize execution design and verified facts that prevent recurrence, not explanations about irreversible cost, time, or trust.

## 8. Production Recovery and Cleanup

- The user uploaded PHP directly under HP through WinSCP and restored page access.
- Many unnecessary temporary and backup files existed on production.
- 321 confirmed unnecessary files were deleted: 319 `.candy-backup-*`, one server `.gitignore`, and one FTP smoke test.
- Production root PHP count after deletion was 100.
- Production `index.php` retained the redirect.
- Do not bulk-delete an uncertain file from its name alone.

## 9. Site-Wide Audit Results

These are 2026-07-13 results, not reusable current state.

### 9.1 Public PHP and Production

- PHP directly under HP: 100
- Production HTTP: 99 pages returned `200`
- `index.php`: intended `301`
- Unexpected PHP HTTP statuses: 0
- Production inventory: 1,428 files and 29 directories

### 9.2 Internal Links and Images

- Rendered internal references: 752
- Missing references after false-positive exclusion: 155
- Area links from `area.php`: 194
- Missing area PHP: 137
- Missing area images: 14
- `shopinfo.php`: missing
- Preparation links on 27 area pages
- Incomplete row in the hotel index
- Anchors: 767; missing anchors: 0

### 9.3 CSS Assets

- Missing assets referenced by active CSS: `img/dummy.gif` and `imgHtml/cdBgGirl.png`.
- Inactive `YTPlayer.css` referenced six missing assets.

### 9.4 External URLs

- External URLs in public code and generated pages: 623
- 4xx results: 26
- Payment-form GET `400` was not classified as broken from GET inspection alone.
- Clear 404 examples included legacy FC2 SNS, legacy diary, kaminokawa, Lawson, and Park Hotel.
- Code, template, metadata, and image references on `www.55810.com` included 20 404s.

### 9.5 URLs in Source Text

- Source Text URLs: 1,229
- `www.55810.com`: 346; 228 OK and 118 404 (102 PHP, 16 images)
- External: 883; 430 OK, 450 restricted, and 3 unreachable
- Most restrictions were `429` from `maps.app.goo.gl` and were not classified as broken.

## 10. Local Snapshot at End of 2026-07-13

Reacquire every value before current work.

- Branch: `main`
- Local HEAD and `origin/main` were confirmed equal at one point.
- Tracked changes: 118; untracked: 348.
- Untracked files were not bulk-deleted, added, or staged.
- Uncommitted changes included public PHP, datasets, and FTP deploy scripts, not only management documents.
- PHP directly under HP: 100.
- Production include references: 97.
- Two PHP files retained test includes: `kagoshima-deliveryhealth-petitegirl.php` and `kagoshima-deliveryhealth-slendergirl.php`.
- `makeSitemap.php` had no dataset include.
- This management redesign did not Commit or Push.

## 11. Why the Management Documents Failed

### 11.1 AGENTS.md Was Too Long

Root was about 540 lines and HP about 680, duplicating principles, Git procedures, counts, and production history. Required prohibitions and routes were buried, and broad reading displaced task-specific selection.

### 11.2 Volatile Counts Were Fixed in AGENTS.md

Old PHP, HTML, dataset, and document counts remained after actual counts changed and were misused as current state.

### 11.3 Multiple Documents Appeared Canonical

Root AGENTS, HP AGENTS, operation basics, generation governance, and production migration duplicated explanations. Updating one left contradictions elsewhere.

### 11.4 Verification States Were Weak

Local changes, GitHub, Actions, production, HTTP, and browser were mixed. Evidence for complete, verified, and monitoring was insufficient.

### 11.5 Exceptions Were Continuously Appended

Each omission added detail to AGENTS and made it harder to read. Exception ownership and update responsibility were undefined.

## 12. Permanent Improvement Policy

### 12.1 Limit AGENTS.md to an Entry Point

Include only mandatory rules, short preflight, task routes, STOP conditions, completion-state definitions, and minimal report shape. Exclude volatile counts, dated findings, long file lists, production history, every page exception, and duplicate Git procedures.

### 12.2 Separate Root and HP Responsibilities

- Root `AGENTS.md`: Repository-wide mandatory rules, Git, production authority, and task routing.
- `HP/AGENTS.md`: HP-specific generation routes, categories, images, production index, risky files, and validation routing.
- Store detail in one canonical document and link to it.

### 12.3 Make "All" and "100%" Verifiable

1. Enumerate the population by command.
2. Record the count.
3. State exclusions.
4. Record each result mechanically.
5. Aggregate success, failure, unverified, and restricted.
6. Never report 100% when one item is unverified.
7. Never report full verification without population and evidence.

### 12.4 Keep Production Work Small and Verifiable

- Prepare preview and target list.
- Verify actual time, authority, and rendering with a small first batch.
- Emit file-level progress.
- Estimate time only from measured speed.
- Complete temporary and backup lifecycle per target.
- Enumerate remaining temporary files after partial failure.
- Do not report monitoring from merely starting a long process; inspect the process, exit code, and current progress.
- On a user stop instruction, verify process termination.

### 12.5 Protect a Dirty Worktree

- Do not STOP solely because dirty or untracked files exist.
- Check overlap with current targets.
- When there is no overlap, change only the target.
- When overlap cannot be understood or preserved, STOP.
- Never run `reset --hard`, `clean`, or force push.
- Limit Stage, Commit, and Push to explicitly authorized files.

## 13. Shortest Next-Task Start

1. Read root `AGENTS.md`.
2. For HP work, read `HP/AGENTS.md`.
3. Use `CANDY_MASTER_DOC_INDEX.md` to select only the task document.
4. Inspect `git status --short --branch` and target actual files.
5. For production, inspect `CANDY_PRODUCTION_MIGRATION_MASTER.md` and actual workflow/script.
6. State included work, excluded work, and completion evidence.
7. Report local, Git, Actions, production, HTTP, and browser separately.

## 14. Prohibited Reporting and Required Replacement

| Prohibited | Required replacement |
|---|---|
| "It is written in the management document" | Show the result of rechecking the target section and actual file |
| "I checked it generally" | Show population, count, success, failure, and unverified |
| "Complete" | Show what completed and what remains by state |
| "Monitoring" | Show running process, latest time, progress, and completion condition |
| "It will finish if we wait" | Without measured speed and remaining count, identify it as an estimate |
| "100%" | Show population and evidence of zero unverified items |

## 15. Update Conditions

- Do not rewrite the historical record itself; corrections retain date and reason.
- Put new current state in its canonical document or a new dated record.
- Do not add credentials, passwords, raw logs, or personal information.
- Do not duplicate one rule across documents; select one canonical source and link to it.

## 16. Chronological Instruction and Decision Ledger for 2026-07-13

This ledger preserves decision-relevant instructions, corrections, and findings in order. Repeated identical messages were consolidated. Strong reprimands are recorded as serious distrust and immediate-correction requirements without omitting requirements or impact.

### 16.1 Git and noindex/index

1. The user instructed verification on GitHub that data changing noindex to index had been committed.
2. Commit, Push, and Pull were instructed.
3. The prior ten-file maximum in AGENTS was an invalid GPT-created rule; more than 100 files may be in scope and the rule was ordered removed.
4. No fixed work-file limit became user policy.

### 16.2 Production and Generation Files

1. The user required understanding that the repository contains template HTML/PHP, future-production Text, and other non-production files.
2. `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` were identified as HTML source data.
3. `template_` files were identified as templates.
4. Category mapping was explicitly area-to-area, blog-to-blog, and hotel-to-hotel.
5. Reports were required to remain short.

### 16.3 Generation-Algorithm Analysis

1. The user instructed investigation of HTML generation and required PHP in this project.
2. The existing `create.php` web route was acknowledged, but future production would normally use Codex management.
3. New-file generation required mandatory rules preserving existing PHP and HTML generation routes.
4. Those rules apply to normal generation; development changes remain separate.
5. Rules were required in a location that would not be overlooked.

### 16.4 Full Area, Blog, and Hotel Analysis

1. Because area had many completed files, all files were to be analyzed for Text application and patterns.
2. Base, exceptions, and other cases were to be separated, verified across all files, and managed in Markdown for future Codex production.
3. Blog and hotel required the same analysis and management.
4. Omission and exception-rule coverage required rechecking.
5. Hotel page content was confirmed to vary with information quantity.
6. Blog rules were confirmed especially complex.
7. Understanding, organization, and management across all three categories required complete rechecking.

### 16.5 Area Images and New Pages

1. Preparation images under `画像データ\area_img` were to be checked.
2. Already-used and future-use images were mixed.
3. Missing-image handling for a new request had to be decided and documented.
4. Inputs with both information/images and inputs with information but no images had to be separated.
5. Page production also included link placement on index, lists, and generated pages.
6. Link routes, not only image rules, required management.
7. The immediate goal was 105 area pages.
8. Markdown completeness had to support staff and other Codex tasks.
9. One area page was produced, committed, and pushed.

### 16.6 Local WinSCP Deploy Proposal

1. A safe PowerShell deploy script using local WinSCP instead of Actions FTP was requested.
2. An initial other-drive path was discarded in favor of the desktop repository.
3. Conditions included saved connection `firststar`, read-only `pwd`/`ls`, preview, upload only with `-Apply`, no deletion, and no secret storage.
4. This proposal must not be confused with later Actions production automation.

### 16.7 GitHub-to-KAGOYA FTP Test

1. Explicit authority covered only `.github/workflows/candy-ftp-test.yml`, Commit, Push, and actual upload/download/hash/delete testing.
2. Untracked files were preserved out of scope.
3. The first test succeeded through delete but Actions failed by misreading KAGOYA `450` during absence validation.
4. The user instructed replacing `ftp.nlst(name)` with full-list filename matching.
5. A second Commit, Push, and Actions check confirmed success.

### 16.8 GitHub main to Production Automation

The final operation presented at that time was Codex changes HP, Commit/Push after explicit approval, Actions starts, and KAGOYA production deploys. One-file testing was requested, with no unauthorized HP changes, no credential display, and no other server-file changes.

### 16.9 Legacy HP Data and Phased Migration

`HP_旧データ` was reacquired from production and treated as a snapshot. Production and test paths were distinguished. The production index redirect would remain until final switchover, and `HP/index.php` automatic deployment had to be prevented.

### 16.10 Early Deployment Excluding index

The user requested the list of accessible PHP names and expected direct access to most pages while `index.php` continued redirecting. All other files, including CSS, were to be brought current and checked.

### 16.11 Long Deploy Failure and Stop

The user repeatedly asked why upload was slow, whether logs were being uploaded, the remaining time, whether the process had stopped, and whether completion would actually be reported. Actual server inspection showed few target files. Customer complaints and serious contract/trust impact were reported. The user required execution to completion, an apology without self-defense, saved records, and evidence instead of token-consuming verbal monitoring. The final stop instruction prohibited continued transfer or monitoring.

### 16.12 Target Confusion and Manual Recovery

The user requested immediate clarification of whether only `index.php` was excluded, why waiting files existed, whether unnecessary files had been excluded, and what "100 files" meant. The target was corrected from one page to all root PHP. The user restored access through WinSCP and asked whether Codex could operate the connected session.

### 16.13 Unnecessary-File Cleanup

After the user manually deleted most temporary files, current upload state and all server unnecessary files were to be checked. The standard was delete unnecessary targets and preserve required targets. Codex verified purpose, references, snapshot, and rollback instead of deleting by name and cleaned 321 confirmed targets.

### 16.14 Full Link Audit

The user ordered a full broken-link audit and repeatedly challenged whether the procedure existed and was correct. "100%" was redefined as full population with zero hidden unverified items. Scope expanded to public PHP, generated references, CSS, images, anchors, external URLs, and source Text. 403/429/timeouts were separated from 404.

### 16.15 Complete Management Redesign

The user required unambiguous reporting, preservation and review of the day's full context, prevention measures, and a high-quality AGENTS design. The root cause included poor AGENTS length and routing. The redesign shortened root/HP AGENTS and created task routers, dated records, operation basics, production migration guidance, and snapshot warnings.

## 17. Manual Two-Step Design on 2026-07-14, Deprecated the Same Day

To prevent the prior long-running deployment and false reporting, a temporary design removed Push-triggered deployment, used manual `workflow_dispatch`, separated preview and deploy, kept preview FTP-free, required matching SHAs/count/`PLAN_TOKEN`/confirmation, limited deployment to 25 files and 50 MiB, prohibited full deploy/deletion/rename/protected targets, completed per-file SHA-256 and backup lifecycle, timed out deploy after ten minutes, and required separate approval for Commit, Push, preview, and deploy. The current implementation supersedes those temporary count, timeout, deletion, and rename restrictions with a 125-file limit, a 30-minute timeout, and rollback-protected deletion for an explicitly approved plan.

It was deprecated because it made upload appear to mean Push only, increased manual Actions/browser work, and obstructed the requested integrated publication. Safety limits, exclusions, and SHA-256 checks remained; only the trigger changed.

## 18. Final Integrated Upload Change on 2026-07-14

### 18.1 Cause

Codex had misread upload as GitHub Push only, created unnecessary waits by separating four actions, and selected manual Actions/browser work while GitHub CLI authentication was expired.

### 18.2 Mandatory Operation

1. Current upload authority follows root `AGENTS.md`.
2. On the normal path, execute to completion without intermediate questions or extra approval.
3. A deploy-target Push to `main` starts Actions automatically.
4. Actions validates SHA, count, `PLAN_TOKEN`, 25-file/50-MiB limits, deletion/rename prohibition, and protected exclusions before FTP.
5. Use the GitHub API, not browser UI, for Actions.
6. Production checks cover HTTP, title, canonical, primary body, and images as required.
7. Final report leads with production URL and also includes Commit and Actions URLs.
8. The operational target was five minutes from instruction to production URL for a next-page request or post-production upload instruction.
9. Deletion, databases, noindex/index, `HP/index.php` switchover, and conflict resolution are excluded from integrated authority.

### 18.3 Immediate Evidence

- 花尾町 used Commit `44df27b` and Actions run `29289499915` to deploy six files with SHA-256 validation.
- `https://www.55810.com/kagoshima-deliveryhealth-area-hanaomachi.php` returned 200 and title, canonical, primary body, two images, and browser rendering were verified.
- Deploy itself took 37 seconds; delay came from choosing manual routes and browser work, not transfer speed.

## 19. Failed One-Area-Page Test and Dedicated Tooling on 2026-07-14

### 19.1 Failure

Codex spent 18 minutes without reaching PHP validation, Commit, Push, or production. It used a long improvised script rather than a dedicated generator and failed four times because of Windows quoting, standard-input encoding, placeholder-count assumptions, and scene5 detection. It re-searched for the known-absent PHP CLI and split static checks into small commands. Management documents existed, but no fast reproducible execution method existed.

### 19.2 Cause

The primary cause was dependence on per-run judgment and improvised code instead of a prepared area generator. Documents alone could not prevent time, quoting, encoding, variable-block, or validation problems. Dedicated tooling was a required preparation for repeated one-page production.

### 19.3 Implemented Correction

- Historical path `HP/codex/scripts/candy_area_page.py`: integrated input parsing, template copying, variable blocks, shop selection, nearby transportation, JSON-LD, three files, shared registrations, production records, and static validation.
- Historical path `HP/codex/scripts/candy-area.cmd`: one-command entry that fixed Python detection on that computer.
- Historical path `HP/codex/scripts/Invoke-CandyAreaPage.ps1`: PowerShell entry; cmd was preferred when execution policy blocked it.
- Source Text shop, travel time, and transportation fees had priority; only unspecified values used existing-page frequency and map-based nearby same-shop data.
- Shop, article, hotel, spot, and telephone-presence counts were variable. Unknown shops, missing required input, slug conflicts, missing images, and duplicate shared registrations STOP instead of inference.

### 19.4 Measured Validation

- All 135 current area Text candidates parsed under one standard: 135 success and 0 failure.
- Actual generation and static validation: 125 success and 10 safe stops; two source placeholders and eight missing or inconsistent images, with no inferred generation.
- Verified current pattern: four shops and one normal article, with three hotels and two or three spots.
- 皆与志町 dry-run/static validation and existing-page-set check each completed in under 0.1 seconds.
- Low-frequency shop selection, hotel/spot count variation, missing telephone, and nearby reference for transportation-fee typo were separately validated.
- PHP CLI was absent on that computer, so PHP syntax remained explicitly unverified.
