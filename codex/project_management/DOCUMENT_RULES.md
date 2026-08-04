# Document Separation and Update Rules

- Purpose: Separate document responsibilities in the Markdown management system
- Status: canonical document
- Updated: 2026-07-25
- Canonical scope: Management-document naming, language, responsibility, structure, update rules, and document-change Git audit
- Update trigger: A management document, route, responsibility, naming rule, or generated-document contract changes

## 1. Principles

- One subject MUST have one canonical document.
- Do not duplicate the same explanation across documents.
- Store detailed procedures in the canonical document for the task type.
- Do not mix reports or history into specifications.
- Canonical management information belongs under `codex/`, except for the common rule files under `docs/rules/` that root `AGENTS.md` explicitly requires; project-management documents belong under `codex/project_management/`.
- Do not convert unverified information into confirmed information.
- Keep stable specifications, current state, generated facts, and task history separate.

## 2. Location-Rule Boundary

Canonical management, common-rule, project, HP, input, accepted-asset, and NAS
locations are defined only in `codex/README.md`. This document applies naming,
structure, update, and document-change Git rules to those locations without
copying or redefining them.

## 3. Markdown Naming and Language Standard

### 3.1 Folder Names

- Active management folder names MUST use English ASCII `lowercase_snake_case`.
- Preserve an existing compliant folder name. Do not rename it only to use a different English synonym.
- Preserve the current authoritative separation between `codex/project_management/`, `codex/docs/`, `codex/docs/generated/`, and `codex/scripts/`.
- Do not create generic top-level duplicates such as `rules/`, `specs/`, `runbooks/`, `state/`, `records/`, or `decisions/`. The `docs/rules/` directory explicitly required by root `AGENTS.md` is the only current exception.

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
- Generated current-state documents MUST include a deterministic state fingerprint derived from their actual generator inputs. Normal drift checks MUST compare the fingerprint and generated content, not generated time or Git Commit metadata.
- Generated time, Git Commit SHA, and per-row verification time/SHA are provenance metadata. Refresh or compare them only through an explicit strict-metadata operation; they MUST NOT make the normal content check fail by themselves.
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

## 4. Prohibited Document Updates

- Do not append unstructured content to the end of a document.
- Do not place unverified information in a confirmed specification.
- Do not treat an old report as current state.
- Do not add a new Markdown document for a subject that already has a canonical document.
- Do not store substantive management history in root `AGENTS.md`.
- Do not edit a generated document manually. Use actual files as the source and update it with `candy-site-state write`.
- Do not store page counts, file counts, Git state, HTTP state, Actions state, or other volatile values in a stable specification.

## 5. Information-State Labels

When a document contains uncertain information, label it with one of:

- `CANONICAL`
- `USER_REPORTED`
- `IMPLEMENTATION_VERIFIED`
- `UNVERIFIED`
- `AWAITING_APPROVAL`

## 6. Validation After a Document Change

At minimum, verify:

- No duplicate canonical source was introduced.
- README location and responsibility entries are intact.
- Specifications and history remain separate.
- Unverified work was not reported as complete.
- Canonical management documents remain under local `codex/` or the explicitly authorized `docs/rules/` common-rule directory and were not duplicated at the repository root, under HP, or on the NAS.
- `candy-site-state check` succeeds and generated documents agree with actual files.

## 7. Document-Change Git Rules

- Common repository discovery, branch selection, live GitHub verification,
  reporting, mismatch handling, and management-branch publication belong only
  in `docs/rules/GIT_RULES.md`.
- Run Git operations only in the local working repository `C:\Codex\Candy`; never on the NAS.
- Freeze the target-file list and exclude out-of-scope changes, deletions, and untracked files from Stage and Commit.
- `git add .` and `git add -A` are prohibited. Specify every staged file.
- Before Commit, verify that only target files are staged, `git diff --cached --check` succeeds, and the commit content matches the authorized scope.
- Push only in a task where the user explicitly authorized Push, upload, or Commit and Push.

## 8. Git Commit and Push Audit

`git diff --check` alone is insufficient for a Git Commit or Push audit. Verify:

| Item | Required verification |
|---|---|
| Fixed scope | Stage, Commit, and Push contain only authorized targets |
| Work location | Git operations ran only in `C:\Codex\Candy`, never on the NAS |
| Task-start Git verification | Completed and reported exactly once under `docs/rules/GIT_RULES.md`; no additional preflight command or synchronization action was added |
| Divergence handling | No editing or Git-state change proceeded contrary to the reporting and STOP requirements in `docs/rules/GIT_RULES.md` |
| Pre-Commit check | `git diff --cached --check` succeeded |
| Markdown tables | Header and row column counts match |
| Placement | Task history, communication, and current state are in the correct sections |
| Status | Status values agree with document placement |
| Authority | Commit and Push authorization does not conflict with a higher-level AGENTS rule |
| GitHub verification | Only the live verification method authorized by `docs/rules/GIT_RULES.md` was used |

## 9. Responsibility Boundaries

- Highest authority and user-response structure are defined only in root `AGENTS.md`; common Git rules belong only in `docs/rules/GIT_RULES.md`, and required-document routing belongs only in `docs/rules/WORK_ROUTING.md`.
- Canonical management locations and management-document responsibilities are
  defined only in `codex/README.md`; category documents may define only their
  own target-specific implementation and asset paths.
- Area production rules belong only in the routed area specification or runbook.
- Hotel production rules belong only in the routed hotel specification or runbook.
- Production deployment rules belong only in `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` and the exact workflow or script.
- Deletion, movement, bulk cleanup, and Git recovery rules belong only in `SAFETY_PROTOCOL.md`.
- This document MUST NOT copy rules from those sources. When a document change affects another responsibility, update that responsibility's canonical source in the same authorized task instead of appending an override here.
