# Document Separation and Update Rules

- Purpose: Separate document responsibilities in the Markdown management system
- Status: canonical document
- Updated: 2026-07-18
- Canonical scope: Management-document naming, language, responsibility, structure, and update rules
- Update trigger: A management document, route, responsibility, naming rule, or generated-document contract changes

## 1. Principles

- One subject MUST have one canonical document.
- Do not duplicate the same explanation across documents.
- Entry points are limited to root `AGENTS.md` and `codex/README.md`.
- Store detailed procedures in the canonical document for the task type.
- Do not mix reports or history into specifications.
- Canonical management information belongs under `codex/`; project-management documents belong under `codex/project_management/`.
- Do not convert unverified information into confirmed information.
- Keep stable specifications, current state, generated facts, and task history separate.

## 2. Canonical Management Locations

The canonical Codex management source is `C:\Codex\candy\codex`. Use `codex/project_management/` for project-management documents, `codex/docs/` for HP production specifications, and `codex/scripts/` for work tools.

Keep only the common entry point `AGENTS.md` and files required for Git management at the local repository root. Do not duplicate management documents there. `HP/` is exclusively for the actual site tree and MUST NOT contain canonical management documents. Accepted area images belong in the Git-managed local `Text_area_data/画像データ/` directory. The NAS is storage-only for `Backup/`; it MUST NOT contain a canonical management source or be used for Git operations.

## 3. Markdown Naming and Language Standard

### 3.1 Folder Names

- Active management folder names MUST use English ASCII `lowercase_snake_case`.
- Preserve an existing compliant folder name. Do not rename it only to use a different English synonym.
- Preserve the current authoritative separation between `codex/project_management/`, `codex/docs/`, `codex/docs/generated/`, and `codex/scripts/`.
- Do not create generic top-level duplicates such as `rules/`, `specs/`, `runbooks/`, `state/`, `records/`, or `decisions/`.

### 3.2 Markdown Filenames

- Active Markdown filenames MUST use English ASCII `UPPER_SNAKE_CASE.md`.
- `AGENTS.md` and `README.md` are standard-name exceptions and MUST remain unchanged.
- Documents under `codex/docs/` SHOULD use `CANDY_<SUBJECT>_<DOCUMENT_TYPE>.md` when that name materially improves identification.
- Preserve an existing compliant filename. Do not rename it without a material naming improvement.
- Do not create an English copy while leaving the original canonical document active. Rename the canonical document itself.

### 3.3 Document Language

The following management content MUST be English:

- Titles and headings
- Explanatory paragraphs and instructions
- Rules, completion criteria, and STOP conditions
- Table headers and metadata labels
- File-responsibility, architecture, and dependency descriptions
- Runbook procedures and task-record labels
- Generated-document labels, notes, and warnings intended for Codex

The following content MUST preserve its exact real value and MUST NOT be translated or rewritten:

- Public website copy and Japanese page titles or headings used by the website
- Customer-provided source text and user-approved Japanese wording
- Japanese legal or contractual text
- Region, hotel, shop, and person names; addresses; and other proper nouns
- Exact quoted source material or relevant exact error messages
- Code identifiers, commands, paths, URLs, slugs, branch names, commit identifiers, API names, class names, function names, and variable names

Japanese proper nouns and source data MAY remain inside an English document. Reports, questions, STOP reports, and final summaries addressed to the user MUST be Japanese.

### 3.4 English Terminology and Status Values

- Use direct operational English and the normative keywords `MUST`, `MUST NOT`, `SHOULD`, `SHOULD NOT`, `MAY`, and `STOP` consistently.
- Use one canonical term for one concept. In particular, do not use `archived`, `backup`, `relocated`, `deprecated`, `deleted`, and `excluded` interchangeably.
- General project-management status values are `NOT_STARTED`, `IN_PROGRESS`, `BLOCKED`, `AWAITING_APPROVAL`, `COMPLETE`, `ARCHIVED`, `UNVERIFIED`, and `NOT_APPLICABLE`.
- Preserve an existing domain-specific status model when its canonical document defines it.
- Do not translate an executable status value unless every script, parser, test, and reference is updated in the same task.

### 3.5 Document-Type Structures

- Entry and router documents MUST remain short and route to detailed canonical documents.
- Rule documents MUST identify purpose, status, canonical scope, mandatory rules, prohibited operations, STOP conditions, and validation when those sections apply.
- Stable specifications MUST contain non-volatile requirements and MUST NOT store current counts, Git state, HTTP results, or task history.
- Runbooks MUST contain scope, preflight, procedure, validation, STOP conditions, completion criteria, and reporting requirements when applicable. They MUST link to the canonical specification instead of duplicating it.
- Current-state documents MUST separate current state, in-progress work, blockers, unverified scope, next actions, and update rules. Detailed history belongs in `TASK_LOG.md`.
- Task records MUST identify objective, scope, actions performed, verified items, unverified items, changed files, remaining work, and the next action.
- Do not add empty headings solely to make documents visually identical.

### 3.6 Generated Documents

- A generated document MUST identify that manual editing is prohibited, its generator, generation time, branch, commit, population, and unverified scope when those values apply.
- Change generated labels, headings, and fixed wording in the generator, then regenerate the output. Do not edit generated output manually.
- Run the generator twice and verify that the second run creates no difference.
- Generated documents MUST remain current-state outputs and MUST NOT become manually maintained specifications or task histories.

### 3.7 Paths, Links, Commands, and Dates

- Wrap paths in backticks.
- Repository Markdown links MUST be relative. Do not use a machine-specific absolute path as a Markdown link.
- A local absolute path MAY appear as code when the path itself is operationally required.
- Put commands in fenced code blocks with the correct language identifier.
- Use `YYYY-MM-DD` for dates and `YYYY-MM-DD HH:mm JST` when time is required.

### 3.8 Migration and Rename Requirements

- Before a rename, verify that the document is active, owns a unique responsibility, has no target-name collision, and can have every active reference updated safely.
- Record an exact old-to-new mapping with the reason, Git tracking state, reference count, affected scripts, affected generated outputs, and conflict status.
- Use `git mv` for a tracked Markdown file. Handle untracked and ignored files separately.
- If an ignored file appears to be an active canonical document, STOP and report it.
- Update active Markdown links, plain-text path references, scripts, wrappers, configurations, generators, and routing tables in the same task.
- Historical task records MAY preserve an old path only when it is clearly identified as historical.
- Do not merge competing canonical documents automatically. STOP and request resolution.
- Renaming does not authorize document deletion, folder reorganization, specification changes, or status changes without verification.

## 4. Document Responsibilities

| Document type or path | Responsibility |
|---|---|
| `AGENTS.md` | Common rules and routes |
| `codex/README.md` | Canonical document index and required reading order |
| `codex/MANAGEMENT_SYSTEM_OVERVIEW.md` | Management-system purpose and design principles |
| Specifications | Confirmed requirements |
| `PROJECT_STATUS.md` | Plan, current problems, remaining work, and next actions |
| `CODEX_COMMUNICATION.md` | Handoffs, requests, and active warnings |
| `TASK_LOG.md` | Per-task results, verified items, and unverified items |
| `TASK_RESERVATIONS.md` | Concurrent-edit prevention |
| `CODE_STRUCTURE.md` | Folder and work-target structure |
| `SAFETY_PROTOCOL.md` | Safety rules for deletion, movement, bulk operations, and Git recovery |
| Stable specifications under `codex/docs/` | Non-volatile page, code, and SEO specifications. Do not store current counts |
| `codex/docs/generated/` | Current state regenerated from actual files. Manual editing is prohibited |

## 5. Prohibited Document Updates

- Do not append unstructured content to the end of a document.
- Do not place unverified information in a confirmed specification.
- Do not treat an old report as current state.
- Do not add a new Markdown document for a subject that already has a canonical document.
- Do not store substantive management history in root `AGENTS.md`.
- Do not edit a generated document manually. Use actual files as the source and update it with `candy-site-state write`.
- Do not store page counts, file counts, Git state, HTTP state, Actions state, or other volatile values in a stable specification.

## 6. Information-State Labels

When a document contains uncertain information, label it with one of:

- `CANONICAL`
- `USER_REPORTED`
- `IMPLEMENTATION_VERIFIED`
- `UNVERIFIED`
- `AWAITING_APPROVAL`

## 7. Validation After a Document Change

At minimum, verify:

- No duplicate canonical source was introduced.
- README routes are intact.
- Specifications and history remain separate.
- Unverified work was not reported as complete.
- Canonical management documents remain under local `codex/` and were not duplicated at the repository root, under HP, or on the NAS.
- `candy-site-state check` succeeds and generated documents agree with actual files.

## 8. Git Start and Synchronization Rules

- Run Git operations only in the local working repository `C:\Codex\candy`; never on the NAS.
- At the start of work, run `git fetch origin`, followed by `git status --short --branch`.
- Verify that the branch is `main`, the upstream is `origin/main`, and the remote is correct.
- If `main` is behind `origin/main`, pull before editing. When existing changes, conflicts, or divergence prevent a safe pull, STOP instead of pulling automatically.
- Freeze the target-file list and exclude out-of-scope changes, deletions, and untracked files from Stage and Commit.
- `git add .` and `git add -A` are prohibited. Specify every staged file.
- Before Commit, verify that only target files are staged, `git diff --cached --check` succeeds, and the commit content matches the authorized scope.
- Push only in a task where the user explicitly authorized Push, upload, or Commit and Push.

## 9. Git Commit and Push Audit

`git diff --check` alone is insufficient for a Git Commit or Push audit. Verify:

| Item | Required verification |
|---|---|
| Fixed scope | Stage, Commit, and Push contain only authorized targets |
| Work location | Git operations ran only in `C:\Codex\candy`, never on the NAS |
| Start synchronization | `git fetch origin` and `git status --short --branch` confirmed ahead/behind state |
| Behind handling | A behind branch was pulled before editing; conflicts, divergence, or overlapping existing changes caused a STOP |
| Pre-Commit check | `git diff --cached --check` succeeded |
| Markdown tables | Header and row column counts match |
| Placement | Task history, communication, and current state are in the correct sections |
| Status | Status values agree with document placement |
| Authority | Commit and Push authorization does not conflict with a higher-level AGENTS rule |
| GitHub verification | Only the verification method selected at task start was used |

## 10. Area Production-Target Management

- The `間違い無し` classification does not mean a new page may be produced.
- Before publish, use the canonical slug to check for an existing public PHP file, source HTML, dataset PHP file, dataset_base registration, area-index entry, and sitemap entry.
- Do not select a candidate when the area index contains the same region name under a different slug.
- One target-slug link in the area index is a requirement. Exclude only a conflicting different slug for the same region name.
- Proceed with production only for a target that returns `NEW_PAGE_TARGET_OK`.

## 11. Hotel Production-Target Management

- Hotel production may proceed only for one target that returns `NEW_HOTEL_TARGET_OK` from `candy-hotel.cmd target-next` or `target-check`.
- Exclude missing-image, invalid-input, already-built, untracked-input, and unregistered-shop candidates before production.
- Use `candy-hotel.cmd publish-next` as the standard hotel-production entry point.
- On STOP, inspect both `COUNTS_JSON` and `BLOCKER_COUNTS_JSON` and distinguish missing images from untracked input.
- Do not proceed as a new hotel page when the target slug exists in `HP/source/hotel.html`, `dataset_base.php`, `sitemap.xml`, or any of the three page-specific files.

## 12. User-Facing Explanations

- Lead with the conclusion.
- When a technical term is required, explain only the meaning needed by the user.
- Do not report only that something is missing. State what is missing, how many items are affected, and which files contain the shortage.
- Do not shorten the explanation until the cause, target, and next action become unclear.
- Every final report MUST include `要約:`.

### 12.1 Purpose of the Required Summary Field

`要約:` is not a repetition of the conclusion. It MUST allow the user to identify the target, result, problem, remaining work, and next action without rereading the full report.

Include at least:

1. The objective and target scope.
2. What completed and which checks ran.
3. What remains incomplete and whether a blocker exists.
4. The affected files, pages, slugs, and counts.
5. Who must do what next to resume or complete the work.

Even when no problem remains, identify the changed scope and validation result. When multiple problems exist, order them by impact or execution sequence.

### 12.2 Reporting by State

- `COMPLETE`: State the target, result, validation, and whether anything remains.
- Partial completion: State the completed scope, incomplete scope, reason, affected targets, and resume action.
- `BLOCKED`: State the stopping point, reason, missing or incorrect input, affected file/page/slug/count, unblock condition, and rerun action.
- Investigation only: State the investigated scope, verified facts, unverified scope, confirmation that no change was made, and the next decision input.

### 12.3 Prohibited Examples

Do not use a summary that reports only a state:

```text
要約: 完了しました。
要約: 不足があります。
要約: 管理書は未更新です。
```

### 12.4 Examples

Normal completion:

```text
要約: area生成仕様のページ内構成節を更新し、対象1ファイルの差分とUTF-8表示を確認した。ページ本体と生成ツールは変更していない。未実施作業はなく、この仕様を次回のarea制作時から参照できる。
```

STOP:

```text
要約: hotel入力73件を確認したが、画像不足35件、入力不備37件、既存登録1件のため新規作成可能な対象は0件だった。ページ生成は開始しておらず、対象ファイルの変更もない。再開には画像不足または入力不備を解消し、target-nextでNEW_HOTEL_TARGET_OKが出る1件を用意する必要がある。
```

## 13. Canonical Codex Management Rules

- The canonical Codex management source is `C:\Codex\candy\codex`.
- Separate project-management documents under `codex/project_management/`, HP production specifications under `codex/docs/`, and work tools under `codex/scripts/`.
- Keep only the common entry point `AGENTS.md` at the local repository root; do not duplicate management documents there.
- `HP/` is exclusively for the actual site tree and MUST NOT contain management documents or `Text_*_data`.
- Use `HP/AGENTS.md` as the HP work route. Do not create `HP/README.md`.
- Keep the Git working repository and `.git` at `C:\Codex\candy`. Store accepted area images under local `Text_area_data/画像データ/`, use GitHub as the synchronization hub, and use the NAS only for `Backup/`.

## 14. HP Hierarchy Rules

- Do not create `HP/HP/`.
- Keep only actual site-tree contents directly under `HP/`.
- The Git working repository is `C:\Codex\candy`; do not run Git operations on the NAS.
- Address HP work targets as `HP/...` from the local repository root.

## 15. Safety Rules for Deletion, Movement, and Bulk Operations

- `SAFETY_PROTOCOL.md` is the canonical source for deletion, movement, bulk cleanup, and Git recovery.
- Do not execute based only on vague labels such as "junk" or "unorganized." Classify targets as approved for deletion, remove from Git tracking, relocated, register in Git, recovery, or `AWAITING_APPROVAL`.
- Treat `.git/`, `AGENTS.md`, `codex/README.md`, `codex/project_management/`, `HP/AGENTS.md`, and `HP/index.php` as protected targets.
- Report physical deletion, removal from Git tracking, Stage, Commit, and Push as separate operations.
- When Git damage is detected, STOP before recovery, report the affected scope and recovery options, and wait for explicit approval.
