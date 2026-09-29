# 管理体系の再構築 — 2026-08-12の作業記録

- History: [20260812_MODIFY_management-system-rebuild.md](../20260812_MODIFY_management-system-rebuild.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-12
- 旧Task ID: `TASK-20260812-CANDY-MANAGEMENT-SYSTEM-REBUILD-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 46行目

**当時の依頼**

> Reconstruct the Candy management-document system so every persistent document has a formal owner, route, lifecycle, case relationship, implementation relationship, and capacity-safe structure

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 35行目

- 担当表記: current
- 期間表記: 2026-08-12
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Rebuild the Candy management-document system by adding central case tracking and case-parent lifecycle rules; align the existing management Markdown tree, ownership, links, lifecycle, capacity, task history, generated documents, and their exact generators/tests; exclude HP runtime files, database, Control, deployment, and production changes

### 決定

**詳細資料に記録された判断・根拠** — [CANDY_MANAGEMENT_SYSTEM_REBUILD.md](../履歴/CANDY_MANAGEMENT_SYSTEM_REBUILD.md) 15–49行目

詳細資料の後日追記を含む。個々の記載日は本文に明示された範囲で扱い、すべてを旧作業当日の内容とは断定しない。

> ## 1. Verified Starting State
> 
> - The core population contained 53 management Markdown files: 40 in the formal `WORK_ROUTING.md` tree and 13 outside it.
> - One additional completed handoff Markdown existed under `codex/data/`, making the repository management-document population 54 when that evidence file is included.
> - The 13 outside the core tree consisted of 10 deprecated compatibility entries, two dated evidence documents, and one one-time audit.
> - `TASK_LOG.md` and three generated Markdown files exceeded 70,000 bytes.
> - No central case registry, generic case-parent location, universal lifecycle, or formal parent-child metadata rule existed.
> - The audit population used by this case did not include the seven tracked member implementation references under `HP/docs/`. They were identified and formally adopted by `CANDY-MGMT-REPAIR-20260814`; therefore the counts below describe the formal population defined on 2026-08-12, not every repository Markdown file that existed then.
> 
> ## 2. Decisions
> 
> 1. `DOCUMENT_RULES.md` is the sole source for management-document creation and lifecycle rules.
> 2. `CASE_REGISTRY.md` is the sole all-case list.
> 3. Atomic cases use a registry row as parent; only multi-phase or persistently detailed cases receive a parent under `cases/`.
> 4. Existing standalone historical evidence serves as its own case parent after registry adoption.
> 5. Existing canonical specifications and runbooks remain permanent sources; this case links to them and does not duplicate their rules.
> 6. Oversized history is split by time period; oversized generated tables are split by data responsibility into Markdown summaries, Markdown children when narrative ownership is needed, and deterministic TSV sidecars for row data.
> 7. No HP, database, Control, deployment, production, or Git-publication change belongs to this case.
> 
> ## 3. Phase Plan
> 
> | Phase | Purpose | Start condition | Completion condition | Status | Deliverables | Transition condition |
> |---|---|---|---|---|---|---|
> | 1 | Inventory and classify actual documents, routes, links, generators, and capacity | User instruction received and governance reviewed | Full population and exceptions identified | Complete | Verified inventory and classification | Architecture can be decided without assumptions |
> | 2 | Define central cases, case parents, lifecycle, responsibilities, and capacity model | Phase 1 complete | One non-duplicating model approved by the instruction | Complete | `DOCUMENT_RULES.md` model and this case parent | Core documents can be updated consistently |
> | 3 | Implement the formal management hierarchy and metadata | Phase 2 complete | Core route, registry, case parent, existing document ownership, and history structure are written | Complete | Core and existing management-document changes | Generated-output restructuring can use final ownership rules |
> | 4 | Restructure generated outputs and generators | Phase 3 complete | Every generated Markdown is at most 70,000 bytes and deterministic | Complete | Renderer split, Markdown summaries, child output, TSV sidecars | Full audit can inspect stable outputs |
> | 5 | Run full-population audit and correct finding-driven defects | Phase 4 complete | Count, tree, metadata, links, ownership, lifecycle, cases, implementation relations, capacity, and determinism pass | Complete | Management audit script and PASS evidence | Completion record can be finalized |
> | 6 | Finalize lifecycle, reservation, and task history | Phase 5 complete | Registry, case parent, reservation, and task history all show the verified result | Complete | Completed records and final report | Case is complete |
> 
> ## 4. Existing-Document Classification
> 
> - Ten compatibility entries remain at their existing paths with lifecycle `Deprecated Compatibility`; they are excluded from current work routing.
> - The July 13 incident, July 18 SEO audit, July 20 area classification, July 23 hotel-image handoff, and July 26 instruction audit are retained as registered historical or completed parents.
> - Active specifications, runbooks, state, queues, rules, and indexes remain active under their current canonical responsibility.

### 対応

> Made `DOCUMENT_RULES.md` the sole document-creation and lifecycle rule source; added `CASE_REGISTRY.md` and one non-atomic case parent; added mandatory routes; classified and connected the 13 documents outside the former core tree plus the completed data handoff; added direct-open identity to the full population; split 84 existing task rows into three time-responsibility children without loss; split oversized generated state into Markdown parents, one cohesive Markdown child, and deterministic category/detail TSV sidecars; separated rendering from collection; added the complete management audit

### 結果

**旧記録の確認結果**

> Management audit passed 60 Markdown files, five sidecars, and 65 tree files with zero missing identity, broken links, invalid tables, duplicate sources of truth, tree mismatches, parent-child failures, unknown case relationships, or Markdown over 70,000 bytes; preexisting task-row population matched before and after with SHA-256 `AC0C3DB0F73AE5EDB4362C168EEB05B107EAF983173CFFF5555FE6FF643F7277` before and after; second generated-state write changed zero of ten outputs; tracked HP diff count was zero

**旧記録の補足・未確認事項**

> At original task completion, Commit and Push were unperformed, and deployment, production, database, Control, and public-site changes were outside scope. The reconstruction was subsequently pushed in Commit `746406348848a4cacb41db343936563932dc3d70`, which is contained in live GitHub `main`; the excluded operational states remain `UNVERIFIED`

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 48行目

旧状態表記: `Complete / GitHub Published`。

旧台帳の次対応:

> None

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
