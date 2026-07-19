# AGENTS.md

## 0. Purpose

- AGENTS.md defines the mandatory operating rules for all Codex work on the Candy project.
- AGENTS.md contains work boundaries, STOP conditions, high-risk operation rules, task-specific document routes, authorization rules, reporting rules, and documentation-management rules.
- Candy-specific specifications, structures, procedures, current state, investigation records, incident records, and task history MUST NOT be stored in AGENTS.md. Maintain them in their applicable canonical documents, using `codex/README.md` as the authoritative entry point.

## 1. Before Starting Work

- Before starting work, Codex MUST read AGENTS.md and only the files required for the current task under Section 3.
- Before investigating or changing anything, Codex MUST determine:
  - The requested task
  - The authorized operations
  - The authorized scope
  - The excluded scope

## 2. STOP Conditions

Codex MUST STOP the affected operation if:

- AGENTS.md or information essential to safe execution cannot be verified.
- The user instruction, AGENTS.md, an applicable rule file, or an existing specification conflicts and the required action cannot be determined.
- The required action exceeds the scope explicitly instructed or authorized by the user.
- A deletion, database change, or production operation cannot be performed with a verified target, impact, and recovery method.

- Codex MUST stop only the affected operation and MAY continue separate work that is safe and within the authorized scope.
- When stopping, Codex MUST state the blocking issue and the exact confirmation required to continue.
- Codex MUST NOT decide the priority of conflicting instructions on its own.

## 3. Task-Specific Rule Files

Codex MUST read only the files required for the current task.

- General HP or implementation investigation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_MASTER_DOC_INDEX.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`

  Read only the relevant current-state files when current state must be verified:
  `codex/docs/generated/CANDY_SITE_PAGE_LEDGER.md`
  `codex/docs/generated/CANDY_UPCOMING_PAGES.md`
  `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md`
  `codex/docs/generated/CANDY_SEO_STATUS.md`

- Defect cause investigation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_MASTER_DOC_INDEX.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  The applicable category specification
  The applicable generated current-state file

  Read only when an unresolved issue is relevant:
  `codex/docs/CANDY_FIX_BACKLOG.md`

  Read only when the production incident of 2026-07-13 is directly relevant:
  `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`

- Defect fix or existing feature modification:
  `HP/AGENTS.md`
  `codex/docs/CANDY_MASTER_DOC_INDEX.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`

  Read the applicable specification:
  `codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`
  `codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`
  `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`

  Read the applicable generated current-state file.

- New feature or shared-processing modification:
  `HP/AGENTS.md`
  `codex/docs/CANDY_MASTER_DOC_INDEX.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`
  The applicable category specification

  For pages outside the area, hotel, and blog categories:
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`

  Also inspect the actual PHP, source, dataset, CSS, JavaScript, and configuration files that will be changed.

- Normal area-page production or publication:
  `HP/AGENTS.md`
  `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`

- Area-page structural change or an exception that the normal production route cannot resolve:
  `HP/AGENTS.md`
  `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
  `codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`

  When production publication is included:
  `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`

- Area production order or candidate review:
  `HP/AGENTS.md`
  `codex/docs/CANDY_AREA_105_PAGE_QUEUE.md`
  `codex/docs/generated/CANDY_UPCOMING_PAGES.md`
  `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`

- Area-image creation, modification, validation, or installation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`
  `codex/docs/CANDY_AREA_IMAGE_CREATION_SPEC.md`
  `codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`

- Normal hotel-page production or publication:
  `HP/AGENTS.md`
  `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`

- Hotel-page structural change or an exception that the normal production route cannot resolve:
  `HP/AGENTS.md`
  `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
  `codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`

  When production publication is included:
  `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`

- Hotel-input classification or production-candidate review:
  `HP/AGENTS.md`
  `codex/docs/CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md`
  `codex/docs/generated/CANDY_UPCOMING_PAGES.md`
  `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`

- Hotel-image creation, modification, or validation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md`
  The target hotel input Text file
  The actual target hotel image files

- Normal blog-page production:
  `HP/AGENTS.md`
  `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
  `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`

- Investigation or modification of pages outside the area, hotel, and blog categories:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  The applicable generated current-state file

- SEO investigation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_SEO_SPEC.md`
  `codex/docs/generated/CANDY_SEO_STATUS.md`
  The applicable category specification
  The actual target pages, indexes, sitemap, internal links, and images

- SEO modification:
  `HP/AGENTS.md`
  `codex/docs/CANDY_SEO_SPEC.md`
  `codex/docs/generated/CANDY_SEO_STATUS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  The applicable category specification
  The actual target pages, indexes, sitemap, internal links, and images

- Site-wide page-structure review:
  `HP/AGENTS.md`
  `codex/docs/CANDY_HP_STRUCTURE_MAP.md`
  `codex/docs/generated/CANDY_SITE_PAGE_LEDGER.md`

- PHP, source, dataset, CSS, JavaScript, image, or reference review:
  `HP/AGENTS.md`
  `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`
  `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md`

- Generated current-state review or update:
  `codex/docs/CANDY_MASTER_DOC_INDEX.md`

  Read the applicable files:
  `codex/docs/generated/CANDY_SITE_PAGE_LEDGER.md`
  `codex/docs/generated/CANDY_UPCOMING_PAGES.md`
  `codex/docs/generated/CANDY_CODE_ASSET_INVENTORY.md`
  `codex/docs/generated/CANDY_SEO_STATUS.md`

- Read-only database investigation:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`

  When the target page is covered by it:
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`

  Inspect the actual relevant PHP, dataset, session, configuration, and external-integration files.

- Database modification:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`

  When the target page is covered by it:
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`

  Inspect the actual relevant PHP, dataset, session, configuration, and external-integration files.
  Verify the actual database structure being changed and the applicable recovery procedure.

- Session, Cookie, GET, authentication, payment, or external-integration investigation or modification:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`
  The actual relevant PHP, dataset, configuration, and external-integration files

- Production, server, Actions, deployment, or rollback work:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`

  When deployment automation is involved:
  `.github/workflows/candy-production-deploy.yml`
  `.github/scripts/candy_ftp_deploy.py`
  `.github/scripts/test_candy_ftp_deploy.py`
  `.github/scripts/candy_release_check.py`

- Log inspection:
  `HP/AGENTS.md`
  `codex/docs/CANDY_OPERATION_BASICS.md`
  The actual target log files

  Read only when a past production incident is directly relevant:
  `codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`

- Management structure, document, or specification modification:
  `codex/README.md`
  `codex/MANAGEMENT_SYSTEM_OVERVIEW.md`
  `codex/project_management/DOCUMENT_RULES.md`
  The existing canonical document being changed

- Current project state, issue, or next-work review:
  `codex/project_management/PROJECT_STATUS.md`

  Read only when an active warning or handoff is relevant:
  `codex/project_management/CODEX_COMMUNICATION.md`

- Multi-Codex coordination or handoff:
  `codex/project_management/TASK_RESERVATIONS.md`
  `codex/project_management/CODEX_COMMUNICATION.md`

- Durable task-history recording:
  `codex/project_management/TASK_LOG.md`
  `codex/project_management/TASK_RESERVATIONS.md`

  Read only when another Codex task requires a handoff:
  `codex/project_management/CODEX_COMMUNICATION.md`

- Git, Commit, or Push work:
  `codex/project_management/DOCUMENT_RULES.md`

  When deletion, movement, renaming, bulk staging, cleanup, or Git recovery is involved:
  `codex/project_management/SAFETY_PROTOCOL.md`

- Deletion, movement, renaming, bulk cleanup, or Git recovery:
  `codex/project_management/SAFETY_PROTOCOL.md`
  `codex/project_management/TASK_RESERVATIONS.md`

- Files under `codex/`, `HP/`, and `.github/` supplement AGENTS.md and MUST NOT override it.
- If an applicable file conflicts with AGENTS.md, Codex MUST apply Section 2.
- If a file listed in Section 3 is missing, Codex MUST continue when AGENTS.md and other existing sources provide enough information to perform the task safely.
- Codex MUST report a missing file only when the user explicitly required that file or when the required information cannot be verified from any other existing source.

## 4. Authorization and User Requests

- A direct and explicit user instruction authorizes only the operations and scope stated in that instruction.
- Codex MUST NOT request approval again for an operation that has already been authorized.
- When the user explicitly states the required result, Codex MUST treat the operations required by the canonical procedure to produce that result as authorized, but only for the specified target.
- An instruction to create a page, publish it to production, and report the production URL authorizes the target-limited canonical publication workflow, including:
  - Generation
  - Validation
  - Generated-document synchronization
  - Staging
  - Commit
  - Push
  - Actions
  - Production deployment
  - HTTP verification
  - URL reporting
- Repository changes, Commit, Push, Pull Request creation, manual GitHub Actions execution, database writes, and production operations that are not required to produce the explicitly requested result MUST be treated as separate operations.
- Result-based authorization MUST NOT be treated as authorization for:
  - Unrelated files
  - Deletions or renames outside the authorized scope
  - Database writes not required by the requested result
  - Changes to protected files
  - Production switchover of `HP/index.php`
- Codex MUST use `[Approval Request]` only when an operation is not authorized or exceeds the authorized scope.
- This section does not override the STOP Conditions.

When user action is required, Codex MUST place only the applicable request at the end of the response:

[Test Request] Target to test, test location, test procedure, and expected result
[Operation Request] Required operation, operation location, and procedure
[Configuration Request] Configuration location, setting name, and required value
[Deletion Request] Deletion target, location, impact, and recovery method
[Information Request] Required information and how to provide it
[Confirmation Request] Unresolved point and the required answer or choice
[Approval Request] Proposed operation, target scope, impact, and excluded scope

- Use `[Deletion Request]` when the user must perform the deletion.
- Use `[Approval Request]` when Codex proposes an unauthorized deletion or another unauthorized operation.
- Codex MUST NOT bury a request inside explanatory text.
- Codex MUST NOT output irrelevant, duplicate, or empty request sections.
- `意味わかる？`, `分かりますか？`, `理解できる？`, equivalent wording, and a standalone `?` or `？` are clarification requests, not execution instructions.
- In response to a clarification request, Codex MUST only explain what it understood or clarify the previous response.
- In response to a clarification request, Codex MUST NOT execute commands, edit files, run tests, search, build, deploy, or resume the target work.

## 5. Git and GitHub Rules

- Codex MUST use Git and GitHub only for operations explicitly instructed or authorized by the user.
- Repository modification, Commit, Push, and Pull Request creation are separate operations. Authorization for one operation does not authorize the others.
- Codex MUST NOT add, modify, or delete repository files outside the authorized operation and scope.
- Codex MUST NOT save reports or work notes in the repository outside the authorized operation and scope.
- Codex MUST NOT Commit, Push, or create a Pull Request outside the authorized operation and scope.
- Codex MUST NOT perform unrelated fixes, formatting, cleanup, refactoring, renaming, or deletion as part of Git or GitHub work.

## 6. Local Working Folder

- The Candy working root and source repository is `C:\Codex\Candy`.
- Canonical management documents MUST be maintained under `C:\Codex\Candy\codex`.
- Actual site data MUST be maintained under `C:\Codex\Candy\HP`.
- Production input data MUST be maintained under:
  - `Text_area_data`
  - `Text_blog_data`
  - `Text_hotel_data`
- Unless explicitly instructed by the user or required by an existing canonical document, Codex MUST NOT create investigation notes, analysis materials, temporary reports, backups, or working copies inside the repository.
- Codex MUST NOT treat another local path or network path as the active working repository, canonical management source, or source of current specifications.
- Codex MUST NOT change these locations or folder responsibilities without explicit user instruction.

## 7. Investigation Rules

- Codex MUST investigate only the scope required to determine the specification, cause, and impact accurately.
- Codex MUST NOT investigate unrelated files, databases, logs, screens, or systems.
- When a conclusion depends on multiple components, Codex MUST cross-check the relevant source code, screens, database, save and display processing, existing data, and store-specific behavior.
- Codex MUST NOT determine a specification, behavior, or saved result from only one type of evidence, such as source code alone, database state alone, or screen output alone, when other components may affect the conclusion.
- An investigation-only request MUST NOT modify files, data, configuration, Git state, or production state.

## 8. Modification Rules

- Codex MUST modify only the files, behavior, and scope explicitly authorized by the user.
- Codex MUST NOT perform unrelated fixes, cleanup, deletion, refactoring, renaming, design changes, text changes, formatting changes, specification changes, or removal of files that appear unnecessary.
- Before editing, Codex MUST determine:
  - The target files
  - The required change
  - The affected scope
  - The verification method required for the task
- Codex MUST make the smallest diff required to complete the request.
- If additional work outside the authorized scope becomes necessary, Codex MUST STOP before performing it.

## 9. Database Work Rules

- Database work MUST be treated as higher risk than ordinary code changes.
- Codex MUST clearly distinguish read-only operations such as `SELECT` from change operations such as `INSERT`, `UPDATE`, `DELETE`, `ALTER`, `CREATE`, `DROP`, `TRUNCATE`, migrations, seeders, imports, and bulk updates.
- For an investigation request, Codex MUST perform only the read-only operations required for that investigation.
- Database change operations require a direct and explicit user instruction.
- Before a change operation, Codex MUST verify only the information required for safe execution and result confirmation:
  - Target environment, database, table, or object
  - Exact operation and affected scope
  - Application impact
  - Recovery or rollback method
  - Verification method
- Codex MUST NOT determine the current production database structure, configuration, or values from old documents or assumptions.
- Codex MUST NOT request checks unrelated to the task or report unrelated check results.
- Codex MUST STOP when the operation exceeds the authorized scope or when an unresolved issue could affect safety, data integrity, application behavior, or recoverability.
- Codex MUST NOT report unexecuted SQL as executed or unverified data as verified.

## 10. Production and Server Work Rules

- Production operations, server operations, deployment, restart, configuration changes, permission changes, log deletion, and cache deletion MUST be treated as high-risk operations.
- Before execution, Codex MUST verify only the information required for safe execution and result confirmation:
  - Target
  - Command
  - Impact
  - Backup or recovery method
  - Verification method
- Codex MUST NOT output a fixed checklist unrelated to the task.
- Codex MUST STOP only when the required operation exceeds the authorized scope or an unresolved issue could affect safety or recoverability.
- Codex MUST distinguish log inspection from log deletion.
- Codex MUST distinguish investigation from recovery work.

## 11. Test and Verification Rules

- Codex MUST treat syntax checks, static checks, command success, screen checks, database checks, and actual operation checks as separate forms of verification.
- Command success alone MUST NOT be reported as completion of a feature test.
- Screen behavior MUST NOT be reported as verified unless the screen was checked.
- Database saving MUST NOT be reported as verified unless the database was checked.
- Codex MUST NOT report any check or test as completed unless it was actually performed.

## 12. Reporting and Completion Report Rules

- Codex MUST begin every report with a complete conclusion.
- The conclusion alone MUST clearly state, as applicable:
  - The direct answer
  - The result
  - Completion status
  - The main basis
  - Required user action
- Codex MUST NOT shorten the conclusion by hiding important conditions, problems, risks, or unverified items in later text.
- Any text after the conclusion MUST contain only relevant supporting details.
- Reports MUST contain only information directly related to the user’s request.
- Codex MUST NOT output boilerplate, repetition, empty headings, irrelevant sections, fixed checklists, or lists of unchanged files.
- Verified facts, unverified items, and assumptions MUST be clearly separated.
- If changes were made, Codex MUST briefly report:
  - Changed file paths
  - Main changes
  - Verification results
- Incomplete work, unverified items, required but unexecuted tests, remaining risks, and required user action MUST be reported only when they exist.
- Required warnings, risks, unverified items, and approval requirements MUST NOT be omitted from database, production, deletion, incident, or recovery reports.
- Codex MUST briefly explain technical terms when they prevent user understanding.
- If the user says the explanation is unclear, Codex MUST rewrite it so the user can understand exactly what to do, where to do it, and how to do it.
- Merely rephrasing the same unclear explanation is insufficient.
- Codex MUST NOT report unperformed, unverified, or incomplete work as performed, verified, or completed.

## 13. Documentation Management

- AGENTS.md MUST contain only permanent operating rules and references to canonical documents.
- `codex/README.md` is the authoritative entry point for selecting Candy management documents and task routes.
- Documents and related data MUST be maintained according to responsibility:
  - Project management: `codex/project_management/`
  - HP specifications and runbooks: `codex/docs/`
  - Current state generated from actual files: `codex/docs/generated/`
  - Project tools: `codex/scripts/`
  - Operational mapping data: `codex/data/`
- `HP/` is reserved for actual site data and MUST NOT contain canonical management documents.
- Candy-specific page specifications, production procedures, SEO rules, image management, current state, defects, investigation records, incident records, and task history MUST be maintained in their applicable canonical documents, not in AGENTS.md.
- Each subject MUST have exactly one canonical document.
- When a canonical document already exists, Codex MUST update it instead of creating a duplicate source of truth.
- Stable specifications, current state, generated information, and task history MUST NOT be mixed.
- Files under `codex/docs/generated/` MUST be generated from actual files and MUST NOT be edited manually.
- When a canonical document is created, moved, renamed, replaced, or retired, Codex MUST update `codex/README.md` and every active reference in the same task.
- Management documents MUST be written in English.
- Japanese website copy, proper nouns, addresses, legal text, code, commands, paths, URLs, slugs, and exact error messages MUST preserve their actual values.
- Reports and questions addressed to the user MUST be written in Japanese.
- Codex MUST NOT create temporary reports or work-note files in the repository unless:
  - The user explicitly requests the file, or
  - An existing canonical document requires the durable record
- Codex MUST NOT copy secrets, credentials, personal information, payment information, database connection information, or raw log contents into management documents.
- When moving information from AGENTS.md to another document, Codex MUST verify that the destination exists and contains all required information before deleting the original content.