# Candy Case History

- Purpose: Provide the single management and record-history entrypoint for the three user-required categories
- Parent / Owner: [`README.md`](../../README.md)
- Scope: Category selection and routing from each recorded case to its canonical individual detail
- Status / Lifecycle: Canonical / Active
- Source of Truth Responsibility: Sole category-history entrypoint; lifecycle and parent mapping remain in `CASE_REGISTRY.md`
- Related Documents: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md), [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), [`CONSULTATION_HISTORY.md`](CONSULTATION_HISTORY.md), [`DEFECT_RESPONSE_HISTORY.md`](DEFECT_RESPONSE_HISTORY.md), and [`CHANGE_HISTORY.md`](CHANGE_HISTORY.md)
- Related Implementation Files: None; individual records identify their own implementation files
- Updated: 2026-08-16

## 1. Required Structure

```text
Management and record history
├─ Consultation History (相談履歴)
│  └─ Individual Consultation Detail (個別相談内容)
├─ Defect and Response History (不具合・対応履歴)
│  └─ Individual Detail (個別詳細)
└─ Modification, Addition, and New-Creation History (改修・追加・新規作成等)
   └─ Individual Detail (個別詳細)
```

## 2. Category Routes

| Required category | Category index | Registered cases | Individual-detail rule |
|---|---|---:|---|
| Consultation History (相談履歴) | [`CONSULTATION_HISTORY.md`](CONSULTATION_HISTORY.md) | 3 | Route to the adopted consultation, investigation, audit, or retained evidence detail |
| Defect and Response History (不具合・対応履歴) | [`DEFECT_RESPONSE_HISTORY.md`](DEFECT_RESPONSE_HISTORY.md) | 8 | Route to the defect case parent, response record, or completed task detail |
| Modification, Addition, and New-Creation History (改修・追加・新規作成等) | [`CHANGE_HISTORY.md`](CHANGE_HISTORY.md) | 12 | Route to the change case parent, completed handoff, or completed task detail |

## 3. Responsibility Boundary

- Every case registered in `CASE_REGISTRY.md` MUST appear in exactly one category index.
- A category index stores only the case ID, subject, and route to the individual detail. It MUST NOT duplicate detailed findings, decisions, current state, specifications, or execution evidence.
- `CASE_REGISTRY.md` remains the sole lifecycle and case-parent map.
- `CANDY_FIX_BACKLOG.md` remains the unresolved owner-decision queue.
- `TASK_LOG.md` and its dated children remain the completed execution history.
- Existing complete detail owners are reused. A new individual detail is created only when no existing document completely owns that case detail.

## 4. Maintenance

When a case is registered, add it to exactly one category index in the same task. When its detail owner changes, update the category route and `CASE_REGISTRY.md` together. Run the management audit to confirm zero missing or duplicate category assignments and zero broken detail links.
