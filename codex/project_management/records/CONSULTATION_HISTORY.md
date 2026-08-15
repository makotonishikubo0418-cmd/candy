# Candy Consultation History (相談履歴)

- Purpose: Route persistent consultations, investigations, audits, and adopted consultation outcomes to their individual details
- Parent / Owner: [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Scope: Consultation History (相談履歴) category routing only
- Status / Lifecycle: Canonical Index / Historical Evidence
- Source of Truth Responsibility: Sole consultation-category index; each linked individual detail owns its substantive content
- Related Documents: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md), [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), and [`CASE_HISTORY.md`](CASE_HISTORY.md)
- Related Implementation Files: None; linked individual details identify their own scope
- Updated: 2026-08-16

## 1. Recording Rule

Transient answered questions remain in the conversation. Add a case here only when a consultation outcome, investigation, audit, or unresolved requirement must persist. Do not copy the individual detail into this index.

## 2. Individual Consultation Details (個別相談内容)

| Case ID | Subject | Individual detail owner | Boundary |
|---|---|---|---|
| CANDY-SEO-AUDIT-20260718 | Repository SEO audit dated 2026-07-18 | [`CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md`](../CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md) | Historical investigation; current rules and state remain in their canonical owners |
| CANDY-AREA-CLASS-20260720 | Area input classification snapshot dated 2026-07-20 | [`CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md`](../../docs/CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md) | Historical input classification; not current generated state |
| CANDY-INSTRUCTION-AUDIT-20260726 | One-time instruction audit dated 2026-07-26 | [`指示書監査.md`](../../指示書監査.md) | Historical audit; not a current instruction |
