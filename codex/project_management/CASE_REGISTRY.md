# Candy Case Registry

- Purpose: Provide the central list and lifecycle route for every persistent Candy change or investigation case
- Parent / Owner: `codex/README.md`
- Scope: Case identity, type, lifecycle, parent location, current phase, implementation relationship, and next action
- Status / Lifecycle: Canonical / Active
- Source of Truth Responsibility: Sole canonical owner of the all-case list and case-to-parent mapping
- Related Documents: `DOCUMENT_RULES.md`, `PROJECT_STATUS.md`, `TASK_RESERVATIONS.md`, `TASK_LOG.md`, and `cases/`
- Related Implementation Files: None; implementation paths are recorded per case
- Updated: 2026-08-14

## 1. Registry Rules

- Register a case before persistent case documentation or implementation begins.
- Use one row per case. Do not store detailed analysis, decisions, phase evidence, or task history here.
- `Parent` is the case-specific source. A registry row may be the parent only for an atomic case with no persistent case document.
- Completed and historical cases remain registered and reachable.
- New-case creation and lifecycle rules are defined only in `DOCUMENT_RULES.md` Sections 3.10 through 3.17.

## 2. Cases

| Case ID | Type | Title | Status / Lifecycle | Parent | Related specifications / runbooks | Target implementation files | Phase / current position | Completion state | Next action |
|---|---|---|---|---|---|---|---|---|---|
| CANDY-MGMT-REPAIR-20260814 | Specification Change / Maintenance | Repair and organize the lower management-document system | Complete / Completed | [Case parent](cases/CANDY_MANAGEMENT_SYSTEM_REPAIR.md) | `DOCUMENT_RULES.md`, `codex/WORK_ROUTING.md`, `codex/README.md` | Lower management documents named in the parent, seven source-attached technical Markdown files under `HP/docs/`, and `codex/scripts/audit_candy_management_docs.py` | Complete | Normal preexisting headers were restored; the migration CSV was classified; the seven member technical files were moved outside the formal management population without physical relocation; and the audit contract was aligned to the formal and non-management populations | None |
| CANDY-TEST-PATH-20260813 | Defect Fix | Correct the group-test template paths in `dataset_base.php` | Complete / Completed | This registry row (atomic case) | `../docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, `../docs/CANDY_OPERATION_BASICS.md` | `HP/includefile/dataset_base.php` | Complete | Commit `ee983ecefc158f45a3eedfb0dfa3157a754b39b0` is present and its static diff is verified; group-test runtime behavior remains unverified | Reverify only when group-test runtime work is requested |
| CANDY-MGMT-20260812 | Specification Change / Refactoring | Reconstruct the Candy management-document system | Complete / Completed | [Case parent](cases/CANDY_MANAGEMENT_SYSTEM_REBUILD.md) | `DOCUMENT_RULES.md`, `codex/WORK_ROUTING.md`, `codex/README.md` | Management Markdown, site-state renderer, output names, generated outputs, and management audit script named in the parent | Phase 6 of 6 / completion recorded | The then-defined formal-population audit passed; the repository-wide population correction is recorded by `CANDY-MGMT-REPAIR-20260814` | None |
| CANDY-INCIDENT-20260713 | Investigation / Production Change | Preserve the 2026-07-13 incident context and prevention evidence | Complete / Historical Evidence | [Historical parent](../docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md) | Current production rules are linked from the parent | Historical HP and deployment evidence named in the parent | Complete | Historical record retained; not a current implementation route | None |
| CANDY-SEO-AUDIT-20260718 | Investigation | Repository SEO audit dated 2026-07-18 | Complete / Historical Evidence | [Historical parent](CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md) | Current SEO rules are linked from the parent | Audit population for the commit named in the parent | Complete | Audit complete; no implementation was authorized | None |
| CANDY-AREA-CLASS-20260720 | Investigation | Area input classification snapshot dated 2026-07-20 | Complete / Historical Evidence | [Historical parent](../docs/CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md) | Current area specifications and generated state are linked from the parent | Input files represented by the dated snapshot | Complete | Snapshot retained; current state comes from generated outputs | None |
| CANDY-HOTEL-HANDOFF-20260723 | Modification | Completed hotel-image normalization handoff | Complete / Completed | [Completed parent](../data/hotel-image-handoff-20260723/HANDOFF_README.md) | Current hotel-image lifecycle sources are linked from the parent | Evidence files in the handoff directory | Complete | Handoff complete; not an active queue | None |
| CANDY-INSTRUCTION-AUDIT-20260726 | Investigation | One-time instruction audit dated 2026-07-26 | Complete / Historical Evidence | [Historical parent](../指示書監査.md) | Current management sources supersede the audited snapshot | Repository and commit named in the parent | Complete | Audit complete; not a current instruction | None |

## 3. Adoption Boundary

This registry became the canonical case list on 2026-08-12. Earlier task rows that have no persistent case document remain complete task history in `TASK_LOG.md` and its history children; they are not converted into artificial case parents. Earlier persistent evidence documents are registered above so every retained standalone record has a formal owner and route.

The seven member technical references under `HP/docs/` existed before the registry adoption. `CANDY-MGMT-REPAIR-20260814` classifies them outside the formal management population as non-management source-attached technical references: `CANDY_OTHER_PAGES_MANAGEMENT.md` is the canonical management owner, `MEMBER_ARCHITECTURE.md` is the technical index, and the six Phase files are its technical children. They are not case records or live-environment evidence and were not moved, renamed, or duplicated.
