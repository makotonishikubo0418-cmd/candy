# Document Separation and Update Rules

- Purpose: Separate document responsibilities in the Markdown management system
- Status: canonical document
- Updated: 2026-08-14
- Canonical scope: Sole source for management-document creation, placement, naming, composition, capacity, splitting, merging, parent-child relationships, links, lifecycle, and document-change validation
- Update trigger: A management document, route, responsibility, naming rule, or generated-document contract changes

## 1. Principles

- One subject MUST have one canonical document.
- Do not duplicate the same explanation across documents.
- Store detailed procedures in the canonical document for the task type.
- Do not mix reports or history into specifications.
- Canonical management information belongs under `codex/`, except for repository-root `AGENTS.md` and `docs/rules/GIT_RULES.md` that the Candy authority and routing documents explicitly select; project-management documents belong under `codex/project_management/`.
- Do not convert unverified information into confirmed information.
- Keep stable specifications, current state, generated facts, and task history separate.
- Route durable history through `records/CASE_HISTORY.md` to exactly one of the three category indexes, then to one canonical individual detail. Category indexes are navigation metadata, not duplicate detail owners.
- Every persistent management document MUST belong to the formal structure shown in both `codex/README.md` and `codex/WORK_ROUTING.md`.
- A Markdown file created for management analysis, planning, phases, implementation, verification, or completion MUST belong to a registered case before it is treated as a persistent management document. Existing source-attached technical references classified under Section 3.19 remain outside the formal management structure.

## 2. Location-Rule Boundary

Canonical management, common-rule, project, HP, input, accepted-asset, and NAS
locations are defined only in `codex/README.md`. This document applies naming,
structure, update, and document-change Git rules to those locations without
copying or redefining them.

Existing source-attached technical Markdown MAY remain beside its implementation only under Section 3.19. It is non-management reference material linked from a canonical owner under `codex/`, is not a second management authority, and MUST NOT be used as proof of live database, server, authentication, DNS, TLS, deployment, or production state.

## 3. Markdown Naming and Language Standard

### 3.1 Folder Names

- Active management folder names MUST use English ASCII `lowercase_snake_case`.
- Preserve an existing compliant folder name. Do not rename it only to use a different English synonym.
- Preserve the current authoritative separation between `codex/project_management/`, `codex/docs/`, `codex/docs/generated/`, and `codex/scripts/`.
- Do not create generic top-level duplicates such as `rules/`, `specs/`, `runbooks/`, `state/`, `records/`, or `decisions/`. The `docs/rules/` directory explicitly required by `codex/WORK_ROUTING.md` is the only current exception.

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
- `records/CASE_HISTORY.md` is the single history entrypoint. `CONSULTATION_HISTORY.md`, `DEFECT_RESPONSE_HISTORY.md`, and `CHANGE_HISTORY.md` MUST classify cases and link to individual details without copying their findings, decisions, state, or execution evidence.
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

### 3.9 Bounded Retrieval for Large Management and Generated Documents

- Before retrieving management or generated-document content, determine its byte size and line count and identify the exact section, category, target, or fields required by the routed task.
- When the possible output size is unknown or may exceed the tool limit, first obtain bounded metadata such as match count, section boundaries, and maximum matching-line length. The first content-retrieval command MUST then be limited to a precomputed output bound.
- Prefer a canonical target-specific command over direct reading of its generated output. Do not print a complete generated document when a script can select or validate the required target directly.
- For a required contiguous section, retrieve non-overlapping contiguous ranges with explicit first and last line numbers. For a table query, return only the required columns and include the total match count so completeness can be verified without printing large free-text fields.
- In PowerShell, collect `foreach` or conditional statement output in an intermediate variable before piping it to formatting or filtering commands. Do not place a pipeline directly after a statement block when that form has not already been validated.
- A command with a syntax error, omitted content, or truncated output supplies no evidence. Its single retry MUST use a smaller, pre-bounded method that removes the observed failure cause; repeating or broadening the failed extraction is prohibited.
- Validate a bounded retrieval by checking its expected count and explicit boundaries. If complete retrieval still fails after the permitted retry, apply the higher-authority `AGENTS.md` information-retrieval STOP rule.

### 3.10 Central Case Registration

`CASE_REGISTRY.md` is the canonical list of all change and investigation cases. Register a case before creating a persistent case document or changing implementation. The registry owns only the case ID, type, title, parent location, lifecycle, current phase, implementation relationship, and next action.

`records/CASE_HISTORY.md` is the category-history entrypoint, not a second case registry. After registration, classify every case exactly once in `CONSULTATION_HISTORY.md`, `DEFECT_RESPONSE_HISTORY.md`, or `CHANGE_HISTORY.md` and link that row to the canonical individual detail. Existing case parents, task records, and retained historical evidence remain detail owners when complete.

Cases include defect investigation, bug fixes, modifications, new features, new pages, new systems, refactoring, specification changes, migrations, production changes, audits, and other work that must remain traceable after the task ends.

- A small atomic case MAY use its registry row as the case parent. Record the completed result in the applicable task-history file.
- A multi-phase case, a case with multiple persistent child documents, or a case whose analysis and decisions must remain available MUST have one parent document under `codex/project_management/cases/`.
- Do not create a separate parent document merely because a case has a phase number.
- Historical evidence that already identifies one completed event MAY serve as its own case parent after it is registered; do not create an empty wrapper.
- The central registry MUST link to the parent. A separate parent MUST link back to the registry.

### 3.11 Case Parent and Child Responsibilities

A case parent is the one entry point for case-specific facts. It owns the objective, scope and exclusions, analysis, verified and unverified facts, decisions, phase plan, current phase, related canonical specifications, implementation files, child documents, verification, completion result, and remaining work.

A child document is permitted only when it owns a distinct responsibility or is required by the capacity rule. It MUST link to its case parent, and the parent MUST link to it. A child MUST NOT restate a permanent specification or runbook. Permanent behavior remains in the applicable canonical specification; the case parent records only the case-specific decision and links to that source.

### 3.12 Required Direct-Open Identification

A newly created persistent management Markdown file, a newly adopted orphan or ambiguous management document, and a new case parent or child MUST allow a reader who opens it directly to determine:

- Purpose
- Parent / Owner
- Scope
- Status / Lifecycle
- Source of Truth Responsibility
- Related Documents
- Related Implementation Files, or `None` when the document is management-only

These facts MAY be stated in existing introductory prose or a concise metadata block. A normal preexisting management document that was already reachable from the formal route and whose role and canonical responsibility are clear from its existing title, introduction, parent index, or routing entry MUST NOT receive a uniform metadata block or a lifecycle line merely for standardization. Do not paste empty headings or a meaningless uniform template. Generated Markdown MUST emit equivalent facts from its generator when its generator contract requires them.

### 3.13 Lifecycle

Use one of these lifecycle values:

| Lifecycle | Meaning |
|---|---|
| `Active` | Current rule, specification, state, queue, registry, or active case |
| `Completed` | Completed case or handoff retained in the formal hierarchy |
| `Archived` | Inactive material retained for reference and excluded from current work routes |
| `Deprecated Compatibility` | Compatibility entry retained only for old references; never a current instruction |
| `Historical Evidence` | Dated evidence, audit, snapshot, incident record, or completed task history; never a current specification |

Do not delete a completed case merely because it is complete. Separate active and completed populations when their combined size impairs use, while preserving the parent and registry route.

Do not retrofit a lifecycle line into a normal preexisting canonical specification, runbook, rule, queue, or ledger solely to make its header match other documents. Use lifecycle labels for new case records, generated or split children, and retained historical, compatibility, completed, or newly adopted material when the classification is necessary.

### 3.14 Phase Management

When phases are required, the case parent MUST record each phase's purpose, order, start condition, completion condition, status, deliverables, and transition condition. The parent is the phase-wide entry point. Create a phase child only when the phase owns an independently useful responsibility or cannot fit within the capacity rule.

### 3.15 Persistent-Document Creation Decision

Before creating a Markdown file, perform this decision in order:

1. Identify the registered case and applicable canonical responsibility.
2. Select exactly one history category and identify the applicable category index under `records/`.
3. Confirm that no existing canonical document owns the required individual detail.
4. Prefer adding the case-specific information to the existing case parent or existing complete detail owner.
5. Create a child only for a distinct responsibility or capacity split.
6. Select the location and filename from Sections 3.1 and 3.2.
7. Add or update the category-index row so it links to the individual detail without duplicating it.
8. For a new child or newly adopted orphan, add the necessary parent-child links, direct-open identification, lifecycle, and implementation relationships; do not retrofit a uniform block into an already clear normal document.
9. Update the actual trees in `codex/README.md` and `codex/WORK_ROUTING.md` in the same task.
10. Run the validation in Section 6.

### 3.16 Capacity and Responsibility-Based Splitting

Capacity applies to every formal management Markdown file, including generated Markdown:

| Size | Rule |
|---|---|
| `0-60,000 bytes` | Normal target range |
| `60,001-70,000 bytes` | Allowed only when one cohesive responsibility would be damaged by splitting |
| `over 70,000 bytes` | Prohibited; split before completion |

- Never remove required information merely to meet the size limit.
- Split by responsibility, data class, time period, or lifecycle, not by arbitrary `PART1` / `PART2` numbering.
- A split parent MUST remain the entry point and describe every child. Every child MUST return to the parent.
- Large tabular generated detail MAY be stored in deterministic TSV or CSV sidecars when a generated Markdown parent explains scope, ownership, and retrieval.
- A generator that approaches the limit MUST be divided by implementation responsibility rather than compressed into unreadable code.

### 3.17 Temporary Material and Existing-Document Adoption

At task completion, every temporary analysis or audit file MUST be either incorporated into an existing canonical document, registered as a case parent or child, classified as `Historical Evidence` or `Archived`, or removed under the applicable safety rule. An unexplained standalone Markdown file is prohibited.

Existing documents adopted into this structure MUST be classified by actual responsibility. Deprecated compatibility files may remain at their old paths, but the formal tree and their own metadata MUST explain why they exist, their parent, that they are excluded from current work, and their lifecycle.

### 3.18 Daily Information Classification

Classify new information by responsibility before deciding whether to write a persistent document:

| Information | Required owner and handling |
|---|---|
| Transient question, consultation, or explanation | Keep it in the conversation. Do not create a persistent file merely because a conversation occurred |
| Adopted decision, consultation outcome, investigation, audit, or unresolved consultation requirement that must persist | Register or identify the case in `CASE_REGISTRY.md`; add it to `records/CONSULTATION_HISTORY.md`; route to the existing or required individual detail; update a stable specification only when permanent behavior changed |
| Defect and response history | Add the registered case to `records/DEFECT_RESPONSE_HISTORY.md` and route it to one individual detail |
| Unresolved defect requiring an owner decision | `CANDY_FIX_BACKLOG.md`, with category routing in `DEFECT_RESPONSE_HISTORY.md` and case detail in the registered parent when required |
| Current cross-case blocker, priority, or next action | `PROJECT_STATUS.md` |
| Modification, addition, new creation, refactoring, or migration | Register or identify the case in `CASE_REGISTRY.md`; add it to `records/CHANGE_HISTORY.md`; route to the existing or required individual detail; permanent behavior remains in the applicable specification or runbook |
| Completed execution result | The time-bounded child selected by `TASK_LOG.md` |
| Inter-task request, caution, handoff, or response | `CODEX_COMMUNICATION.md` |
| System and operational information | Use the field-specific owner in `codex/README.md` and `codex/WORK_ROUTING.md`; verify volatile external state from the actual configuration, provider, server, or service and label it `UNVERIFIED` until checked |

Do not create one file per chat, consultation, date, defect, or phase when an existing canonical document, registry row, case parent, backlog, status document, or task-history child can hold the individual detail without mixing responsibilities. The exact routed indexes `CONSULTATION_HISTORY.md`, `DEFECT_RESPONSE_HISTORY.md`, and `CHANGE_HISTORY.md` are permitted only under `records/CASE_HISTORY.md`; do not create parallel category indexes, `BUG_HISTORY.md`, or `SYSTEM_INFORMATION.md`.

### 3.19 Existing Source-Attached Technical Markdown

An existing technical Markdown file outside `codex/` MAY remain beside its implementation as non-management reference material only when all of the following are true:

- Moving or duplicating it is not required to satisfy the user's objective.
- A canonical management owner under `codex/` is identified and links to one technical index rather than duplicating the technical content.
- The technical index links to its technical children, and each child links back to that technical index.
- Each file states that it is a source-attached technical reference outside the formal management-document tree.
- Each file distinguishes intended or static implementation behavior from verified live database, server, authentication, DNS, TLS, deployment, and production state.
- The repository-wide audit classifies it separately from the formal management population, validates its required hierarchy and warning boundary, and reports any other Markdown outside the formal tree as unclassified.

Such a file is not a management document, case record, canonical specification, or proof of live state. Actual implementation is the source for code behavior; current external state requires live evidence. Preserve useful technical content and warning boundaries without adding management Parent, Lifecycle, Source-of-Truth, case, or formal-tree metadata.

Do not create a new source-attached technical Markdown file outside `codex/` without specific approval and a verified technical reason that the canonical structure cannot satisfy.

## 4. Prohibited Document Updates

- Do not append unstructured content to the end of a document.
- Do not place unverified information in a confirmed specification.
- Do not treat an old report as current state.
- Do not add a new Markdown document for a subject that already has a canonical document.
- Do not store substantive management history in root `AGENTS.md`.
- Do not edit a generated document manually. Use actual files as the source and update it with `candy-site-state write`.
- Do not store page counts, file counts, Git state, HTTP state, Actions state, or other volatile values in a stable specification.
- Do not create a persistent Markdown file outside the formal tree or without a case or canonical owner.
- Do not create duplicate case parents, disconnected phase files, arbitrary size chunks, or a second work-routing index.
- Do not create a persistent detail file solely to mirror a conversation, consultation, defect label, change type, or operational category when an existing complete detail owner exists. The routed category indexes under `records/CASE_HISTORY.md` are navigation documents and are not detail duplicates.

## 5. Information-State Labels

When a document contains uncertain information, label it with one of:

- `CANONICAL`
- `USER_REPORTED`
- `IMPLEMENTATION_VERIFIED`
- `UNVERIFIED`
- `AWAITING_APPROVAL`

A structural or descriptive classification such as `SPECIAL`, duplicate hash,
local-only, historical, or compatibility MUST NOT be treated as a problem by
its label alone. Report it as a current problem only when its canonical owner
or generated current-state source also classifies it as `ISSUE`, `CONFLICT`,
`BLOCKED`, `UNREVIEWED`, an actionable candidate, or an unresolved backlog
item. An explicitly intentional or required classification with no issue MUST
remain outside problem lists.

## 6. Validation After a Document Change

At minimum, verify:

- No duplicate canonical source was introduced.
- README location and responsibility entries are intact.
- Specifications and history remain separate.
- Unverified work was not reported as complete.
- Large management or generated documents have a bounded target-specific retrieval route, and integrated runbooks do not require full-document output for an internal automated step.
- Canonical management documents remain under local `codex/`, repository-root `AGENTS.md`, or the explicitly authorized `docs/rules/GIT_RULES.md` location and were not duplicated elsewhere at the repository root, under HP, or on the NAS.
- When a management-document change affects HP or generated current state, the applicable target or full `candy-site-state check` MUST succeed and generated documents MUST agree with actual files. For a management-only change, run the full check as a drift observation when required, but do not expand scope to repair preexisting unrelated HP findings; record the exact findings separately and do not report them as fixed.
- Enumerate repository Markdown and distinguish formal management documents and formally retained implementation references from generated output, historical or compatibility material, source-attached technical documentation, and other non-management Markdown. Do not promote a file into the management hierarchy merely because its extension is `.md`.
- Every formal management document is reachable from the formal entry point and has a determinable purpose and canonical responsibility through its existing content, parent index, or routing entry. Apply the direct-open identification block only where Section 3.12 requires it.
- Parent-child links are bidirectional; registered cases resolve to their parent; case children resolve back to the parent and registry.
- Every `CASE_REGISTRY.md` case appears in exactly one of the three category indexes, no unregistered case appears there, and every category row resolves to an individual detail.
- No broken relative Markdown links, duplicate canonical responsibility, orphan management document, or formal management Markdown file over 70,000 bytes remains.
- The formal management-document count, capacity bands, required classifications, formal trees, and actual formal-file population agree; separately identified non-management Markdown is not forced into the formal tree.
- Generated Markdown and deterministic sidecars pass a second no-change generation.

## 7. Document-Change Git Rules

- Common repository discovery, branch selection, live GitHub verification,
  reporting, mismatch handling, and management-branch publication belong only
  in `docs/rules/GIT_RULES.md`.
- Run Git operations only in the local working repository `C:\Codex\FSG\Candy`; never on the NAS.
- Freeze the target-file list and exclude out-of-scope changes, deletions, and untracked files from Stage and Commit.
- `git add .` and `git add -A` are prohibited. Specify every staged file.
- Before Commit, verify that only target files are staged, `git diff --cached --check` succeeds, and the commit content matches the authorized scope.
- Push only in a task where the user explicitly authorized Push, upload, or Commit and Push.

## 8. Git Commit and Push Audit

`git diff --check` alone is insufficient for a Git Commit or Push audit. Verify:

| Item | Required verification |
|---|---|
| Fixed scope | Stage, Commit, and Push contain only authorized targets |
| Work location | Git operations ran only in `C:\Codex\FSG\Candy`, never on the NAS |
| Daily initial Git verification | Completed and reported at the timing defined by root `AGENTS.md` and `docs/rules/GIT_RULES.md`; no duplicate preflight or synchronization action was added |
| Divergence handling | No editing or Git-state change proceeded contrary to the reporting and STOP requirements in `docs/rules/GIT_RULES.md` |
| Pre-Commit check | `git diff --cached --check` succeeded |
| Markdown tables | Header and row column counts match |
| Placement | Task history, communication, and current state are in the correct sections |
| Status | Status values agree with document placement |
| Authority | Commit and Push authorization does not conflict with a higher-level AGENTS rule |
| GitHub verification | Only the live verification method authorized by `docs/rules/GIT_RULES.md` was used |

## 9. Responsibility Boundaries

- Highest authority and user-response structure are defined only in `C:\Codex\FSG\Candy\AGENTS.md`; Candy Git rules belong only in `docs/rules/GIT_RULES.md`, and Candy required-document routing belongs only in `codex/WORK_ROUTING.md`.
- Canonical management locations and management-document responsibilities are
  defined only in `codex/README.md`; category documents may define only their
  own target-specific implementation and asset paths.
- Area production rules belong only in the routed area specification or runbook.
- Hotel production rules belong only in the routed hotel specification or runbook.
- Production deployment rules belong only in `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` and the exact workflow or script.
- Deletion, movement, bulk cleanup, and Git recovery rules belong only in `SAFETY_PROTOCOL.md`.
- This document MUST NOT copy rules from those sources. When a document change affects another responsibility, update that responsibility's canonical source in the same authorized task instead of appending an override here.

## 10. Project-Management Responsibility Matrix

| Information | Sole canonical owner |
|---|---|
| Overall project current state, cross-case blockers, priorities, and next work | `PROJECT_STATUS.md` |
| All case IDs, case types, lifecycle, parent locations, current phases, and next actions | `CASE_REGISTRY.md` |
| Three-category management and record-history entrypoint | `records/CASE_HISTORY.md` |
| Consultation History (相談履歴) category routing | `records/CONSULTATION_HISTORY.md` |
| Defect and Response History (不具合・対応履歴) category routing | `records/DEFECT_RESPONSE_HISTORY.md` |
| Modification, Addition, and New-Creation History (改修・追加・新規作成等) category routing | `records/CHANGE_HISTORY.md` |
| Detailed facts, decisions, phases, files, verification, and completion for one non-atomic case | Its case parent under `cases/` or its registered historical parent |
| Concurrent task ownership and file reservation | `TASK_RESERVATIONS.md` |
| Inter-task request, caution, handoff, and response | `CODEX_COMMUNICATION.md` |
| Completed task execution results and verified or unverified evidence | `TASK_LOG.md` and its history children |
| Persistent consultation outcomes and adopted case-specific decisions | The individual detail selected through `records/CONSULTATION_HISTORY.md`; `CASE_REGISTRY.md` retains lifecycle and parent mapping |
| Unresolved defects requiring owner decisions | `codex/docs/CANDY_FIX_BACKLOG.md`; category routing remains in `records/DEFECT_RESPONSE_HISTORY.md` |
| Repository, GitHub, database, server, authentication, domain, DNS, TLS, placement, and other system or operational information | The field-specific owner in `codex/README.md` and `codex/WORK_ROUTING.md`, plus verified live evidence for volatile external state |

Do not permanently maintain the same information in two owners. A document may link to another owner's record and summarize only what is necessary for navigation.
