# Candy Management-System Reconstruction

- Case ID: `CANDY-MGMT-20260812`
- Purpose: Reconstruct the Candy management-document system so every persistent document has a formal owner, route, lifecycle, and traceable implementation relationship
- Parent / Owner: [`CASE_REGISTRY.md`](../CASE_REGISTRY.md)
- Scope: Management documents, document history, generated management outputs, their generators, and management validation tooling
- Exclusions: `HP/` runtime behavior, database state or changes, Control implementation, deployment, production, and public website content
- Status / Lifecycle: Complete / Completed
- Source of Truth Responsibility: Case-specific analysis, decisions, phases, changed management files, verification, and completion result for `CANDY-MGMT-20260812`
- Related Documents: [`DOCUMENT_RULES.md`](../DOCUMENT_RULES.md), [`codex/WORK_ROUTING.md`](../../WORK_ROUTING.md), [`codex/README.md`](../../README.md), and [`TASK_LOG.md`](../TASK_LOG.md)
- Related Implementation Files: `codex/scripts/candy_site_state.py`, `codex/scripts/candy_site_state_render.py`, `codex/scripts/candy_page_common.py`, and `codex/scripts/audit_candy_management_docs.py`
- Updated: 2026-08-12
- Corrected: 2026-08-14 to distinguish the then-defined formal population from the repository-wide Markdown population

## 1. Verified Starting State

- The core population contained 53 management Markdown files: 40 in the formal `WORK_ROUTING.md` tree and 13 outside it.
- One additional completed handoff Markdown existed under `codex/data/`, making the repository management-document population 54 when that evidence file is included.
- The 13 outside the core tree consisted of 10 deprecated compatibility entries, two dated evidence documents, and one one-time audit.
- `TASK_LOG.md` and three generated Markdown files exceeded 70,000 bytes.
- No central case registry, generic case-parent location, universal lifecycle, or formal parent-child metadata rule existed.
- The audit population used by this case did not include the seven tracked member implementation references under `HP/docs/`. They were identified and formally adopted by `CANDY-MGMT-REPAIR-20260814`; therefore the counts below describe the formal population defined on 2026-08-12, not every repository Markdown file that existed then.

## 2. Decisions

1. `DOCUMENT_RULES.md` is the sole source for management-document creation and lifecycle rules.
2. `CASE_REGISTRY.md` is the sole all-case list.
3. Atomic cases use a registry row as parent; only multi-phase or persistently detailed cases receive a parent under `cases/`.
4. Existing standalone historical evidence serves as its own case parent after registry adoption.
5. Existing canonical specifications and runbooks remain permanent sources; this case links to them and does not duplicate their rules.
6. Oversized history is split by time period; oversized generated tables are split by data responsibility into Markdown summaries, Markdown children when narrative ownership is needed, and deterministic TSV sidecars for row data.
7. No HP, database, Control, deployment, production, or Git-publication change belongs to this case.

## 3. Phase Plan

| Phase | Purpose | Start condition | Completion condition | Status | Deliverables | Transition condition |
|---|---|---|---|---|---|---|
| 1 | Inventory and classify actual documents, routes, links, generators, and capacity | User instruction received and governance reviewed | Full population and exceptions identified | Complete | Verified inventory and classification | Architecture can be decided without assumptions |
| 2 | Define central cases, case parents, lifecycle, responsibilities, and capacity model | Phase 1 complete | One non-duplicating model approved by the instruction | Complete | `DOCUMENT_RULES.md` model and this case parent | Core documents can be updated consistently |
| 3 | Implement the formal management hierarchy and metadata | Phase 2 complete | Core route, registry, case parent, existing document ownership, and history structure are written | Complete | Core and existing management-document changes | Generated-output restructuring can use final ownership rules |
| 4 | Restructure generated outputs and generators | Phase 3 complete | Every generated Markdown is at most 70,000 bytes and deterministic | Complete | Renderer split, Markdown summaries, child output, TSV sidecars | Full audit can inspect stable outputs |
| 5 | Run full-population audit and correct finding-driven defects | Phase 4 complete | Count, tree, metadata, links, ownership, lifecycle, cases, implementation relations, capacity, and determinism pass | Complete | Management audit script and PASS evidence | Completion record can be finalized |
| 6 | Finalize lifecycle, reservation, and task history | Phase 5 complete | Registry, case parent, reservation, and task history all show the verified result | Complete | Completed records and final report | Case is complete |

## 4. Existing-Document Classification

- Ten compatibility entries remain at their existing paths with lifecycle `Deprecated Compatibility`; they are excluded from current work routing.
- The July 13 incident, July 18 SEO audit, July 20 area classification, July 23 hotel-image handoff, and July 26 instruction audit are retained as registered historical or completed parents.
- Active specifications, runbooks, state, queues, rules, and indexes remain active under their current canonical responsibility.

## 5. Child Documents

No case-specific child document is currently required. `TASK_LOG.md` history children and generated-output children belong to their respective canonical parents, not to this case.

## 6. Verification and Completion

Completed on 2026-08-12 with these verified results for the then-defined formal population:

- Full management audit: `PASS`; 60 Markdown files, five deterministic TSV sidecars, and 65 formal-tree files agreed exactly.
- Direct-open identity, parent, scope, lifecycle, source-of-truth responsibility, related documents, and implementation relationship: missing 0.
- Broken relative links 0; invalid Markdown tables 0; duplicate source-of-truth responsibilities 0; parent-child failures 0; unknown case relationships 0; tree/file mismatches 0; orphan persistent Markdown 0.
- Capacity: 59 Markdown files at or below 60,000 bytes; one cohesive generated code-reference child at 65,940 bytes; zero Markdown files over 70,000 bytes.
- Existing task history: all 84 preexisting rows preserved exactly after the time-responsibility split; sorted-row SHA-256 `AC0C3DB0F73AE5EDB4362C168EEB05B107EAF983173CFFF5555FE6FF643F7277` before and after.
- Generated state: ten-file output set written; second write changed zero files.
- Scope protection: tracked `HP/` diff count was zero. No database, Control, deployment, production, Commit, or Push operation was performed.

On 2026-08-14, case `CANDY-MGMT-REPAIR-20260814` expanded the audit to the repository-wide Markdown population and adopted the seven preexisting `HP/docs/` member references without moving, renaming, or treating their live-environment claims as verified. The original 2026-08-12 counts remain historical evidence and MUST NOT be reported as the current repository-wide count.

The global site-state health check continues to report six preexisting member/privacy sitemap-or-structure findings. They are outside this management-only case and were not changed or reported as fixed.
