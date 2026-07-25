# candy Markdown Management System Overview

- Purpose: Summarize the management system requested by the user and its design intent
- Document responsibility: Overview of the management system goals and design principles
- Detailed canonical sources: The management documents listed in `codex/README.md`
- Status: canonical document for the candy management-system overview
- Canonical location: `C:\Codex\Candy\codex`
- Updated: 2026-07-25

## 1. Intended Outcome

Establish the management system before page production so candy page production, generation tools, GitHub synchronization, asset management, and multi-Codex work do not become disorganized.

The intended state is:

- Preserve every required check while removing only unnecessary reading, investigation, and reporting.
- Handle simple work concisely and high-impact work carefully.
- Prevent multiple Codex tasks from changing the same file at the same time.
- Make it clear who did what and how far the work progressed.
- Preserve visibility into the overall plan, current state, problems, open issues, and next work.
- Do not add similar code or override layers with each change.
- Keep specifications, current state, and historical reports separate.
- Separate stable specifications from current state that can be regenerated from actual files.
- Maintain necessary communication and never report unverified work as complete.
- Preserve a structure that remains maintainable when expanded across categories.

Here, "shortest route" does not mean rushed or incomplete work. It means completing every required step and omitting only unnecessary steps.

## 2. Core Architecture

Do not place every detailed procedure in `AGENTS.md`. Keep it focused on common
rules and mandatory task-to-document routing, then read only the documents
named by the applicable route.

```text
AGENTS.md
  ↓
management documents named by AGENTS.md
(`codex/README.md` only when the route names it)
  ↓
target code, specification, asset, or environment
```

This route avoids broad document reading for simple work while preserving every required rule.

## 3. Separation of Markdown Responsibilities

Maintain separate canonical documents for common rules, the overall plan, specifications, code structure, inter-Codex communication, and individual task history.

Maintain required-document routing only in `AGENTS.md` Section 2. Maintain
canonical management locations and operational route summaries in
`codex/README.md`. Do not create another competing index in this overview.

## 4. Work Sequence

1. Review the common rules in `AGENTS.md`.
2. Use `AGENTS.md` Section 2 to select the required documents, reading
   `codex/README.md` only when the applicable route names it.
3. Before modification or coordination, check active reservations, related
   in-progress tasks, assigned handoffs, and existing changes to target files.
4. When work overlaps or depends on another task, determine target separation, ordering, or handoff.
5. Reserve the task and target files.
6. Confirm the existing canonical source, dependencies, and prohibited-change scope.
7. Make a small, consistent change to the existing canonical source.
8. Validate the diff, syntax, quality gates, and required rendered output.
9. For HP changes, regenerate the generated documents and verify agreement with actual files.
10. Report the actual diff, verified items, unverified items, remaining work, and handoff target.
11. Release the reservation and proceed to Commit, Push, or deploy only when separately authorized.

## 5. Problems Prevented by This System

| Problem to prevent | Management control |
|---|---|
| A long AGENTS document slows simple work | Keep `AGENTS.md` focused on routing |
| Multiple Codex tasks change the same location | Prevent concurrent edits with task IDs and file reservations |
| The overall current state becomes unclear | Centralize current state, issues, and next work in `PROJECT_STATUS.md` |
| Reports become scattered and cannot be handed off | Separate responsibilities between `CODEX_COMMUNICATION.md` and `TASK_LOG.md` |
| Repeated changes degrade the code | Enforce one responsibility per canonical source, integration into existing routes, and no appended override blocks |
| Specifications differ between documents | Assign one canonical document to each subject and prohibit duplicate specifications |
| Unverified work is reported as complete | Distinguish canonical, user-reported, implementation-verified, and unverified information |
| Excessive communication interrupts work | Limit updates to required start, material change or blocker, and completion reports |

## 6. Target State

The purpose of this management system is not to increase the document count.

It exists so the required person or Codex task can reach the required canonical source by the shortest route, avoid damaging other work, and preserve the reason for each decision and change.

Maintain these four qualities:

1. Efficient and direct
2. High-quality and safe
3. Resistant to code degradation during changes
4. Clear communication and responsibility boundaries across multiple Codex tasks

## 7. Maintenance Rule

This document is the overview of the candy management system.

Use `AGENTS.md` and the canonical management documents named by its applicable
route for actual work decisions. Use `codex/README.md` when that route names it.
When a detailed operating rule changes, update its canonical document; do not
change only this overview.
