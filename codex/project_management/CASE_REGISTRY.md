# Candy Case Registry

- Purpose: Provide the central list and lifecycle route for every persistent Candy change or investigation case
- Parent / Owner: `codex/README.md`
- Scope: Case identity, type, lifecycle, parent location, current phase, implementation relationship, and next action
- Status / Lifecycle: Canonical / Active
- Source of Truth Responsibility: Sole canonical owner of the all-case list and case-to-parent mapping
- Related Documents: `DOCUMENT_RULES.md`, `PROJECT_STATUS.md`, `TASK_RESERVATIONS.md`, `TASK_LOG.md`, and `cases/`
- Related Implementation Files: None; implementation paths are recorded per case
- Updated: 2026-08-12

## 1. Registry Rules

- Register a case before persistent case documentation or implementation begins.
- Use one row per case. Do not store detailed analysis, decisions, phase evidence, or task history here.
- `Parent` is the case-specific source. A registry row may be the parent only for an atomic case with no persistent case document.
- Completed and historical cases remain registered and reachable.
- New-case creation and lifecycle rules are defined only in `DOCUMENT_RULES.md` Sections 3.10 through 3.17.

## 2. Cases

| Case ID | Type | Title | Status / Lifecycle | Parent | Related specifications / runbooks | Target implementation files | Phase / current position | Completion state | Next action |
|---|---|---|---|---|---|---|---|---|---|
| CANDY-MGMT-20260812 | Specification Change / Refactoring | Reconstruct the Candy management-document system | Complete / Completed | [Case parent](cases/CANDY_MANAGEMENT_SYSTEM_REBUILD.md) | `DOCUMENT_RULES.md`, `codex/WORK_ROUTING.md`, `codex/README.md` | Management Markdown, site-state renderer, output names, generated outputs, and management audit script named in the parent | Phase 6 of 6 / completion recorded | Full management-population audit PASS | None |
| CANDY-INCIDENT-20260713 | Investigation / Production Change | Preserve the 2026-07-13 incident context and prevention evidence | Complete / Historical Evidence | [Historical parent](../docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md) | Current production rules are linked from the parent | Historical HP and deployment evidence named in the parent | Complete | Historical record retained; not a current implementation route | None |
| CANDY-SEO-AUDIT-20260718 | Investigation | Repository SEO audit dated 2026-07-18 | Complete / Historical Evidence | [Historical parent](CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md) | Current SEO rules are linked from the parent | Audit population for the commit named in the parent | Complete | Audit complete; no implementation was authorized | None |
| CANDY-AREA-CLASS-20260720 | Investigation | Area input classification snapshot dated 2026-07-20 | Complete / Historical Evidence | [Historical parent](../docs/CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md) | Current area specifications and generated state are linked from the parent | Input files represented by the dated snapshot | Complete | Snapshot retained; current state comes from generated outputs | None |
| CANDY-HOTEL-HANDOFF-20260723 | Modification | Completed hotel-image normalization handoff | Complete / Completed | [Completed parent](../data/hotel-image-handoff-20260723/HANDOFF_README.md) | Current hotel-image lifecycle sources are linked from the parent | Evidence files in the handoff directory | Complete | Handoff complete; not an active queue | None |
| CANDY-INSTRUCTION-AUDIT-20260726 | Investigation | One-time instruction audit dated 2026-07-26 | Complete / Historical Evidence | [Historical parent](../指示書監査.md) | Current management sources supersede the audited snapshot | Repository and commit named in the parent | Complete | Audit complete; not a current instruction | None |

## 3. Adoption Boundary

This registry became the canonical case list on 2026-08-12. Earlier task rows that have no persistent case document remain complete task history in `TASK_LOG.md` and its history children; they are not converted into artificial case parents. Earlier persistent evidence documents are registered above so every retained standalone record has a formal owner and route.
