# Task Log

- Purpose: Route completed task execution history to time-bounded children without storing specifications or current case state
- Parent / Owner: `codex/README.md`
- Scope: Completed task results and historical approval-basis notes
- Status / Lifecycle: Canonical Index / Historical Evidence
- Source of Truth Responsibility: Sole index and recording rules for completed task execution history
- Related Documents: `CASE_REGISTRY.md`, `TASK_RESERVATIONS.md`, `CODEX_COMMUNICATION.md`, and `task_history/`
- Related Implementation Files: Recorded per task row in the selected child
- Last updated: 2026-08-15

## 1. Recording Rules

- Record results only; do not store specifications, case plans, current project state, reservations, or communications here.
- Separate completed, incomplete, and unverified items.
- Mention Commit, Push, or production only when the operation was actually performed.
- When existing differences are found, record the Task ID, objective, approval basis, and unverified items separately.
- Add a row to the child matching the task date. Create a new time-bounded child only under the capacity and parent-child rules in `DOCUMENT_RULES.md`.
- A registered case links to its parent through `CASE_REGISTRY.md`; a task row records execution results and does not become a duplicate case parent.

## 2. History Index

| Period | Child | Rows | Lifecycle |
|---|---|---:|---|
| 2026-07-01 through 2026-07-20 | [`TASK_LOG_2026_07_01_20.md`](task_history/TASK_LOG_2026_07_01_20.md) | 46 | Historical Evidence |
| 2026-07-21 through 2026-07-31 | [`TASK_LOG_2026_07_21_31.md`](task_history/TASK_LOG_2026_07_21_31.md) | 30 | Historical Evidence |
| 2026-08-01 through 2026-08-31 | [`TASK_LOG_2026_08.md`](task_history/TASK_LOG_2026_08.md) | 16 | Historical Evidence |

## 3. Approval Basis Notes

| Target | Basis | Handling |
|---|---|---|
| 86 relocated items / 76 deletion entries | User instruction: "`\\192.168.1.3\disk1\FSG_SEO\candy\除外リスト` 作成した 実行しろ" | Relocated locally, then included in the canonical-structure synchronization recorded as `TASK-20260717-GITHUB-SYNC-001` and Commit `7d23c91`. |
| Three area input files | Input Text corrections after the user instruction: "間違いは今修正しろ" | Corrected locally. Page generation and production deployment are separate tasks. |
