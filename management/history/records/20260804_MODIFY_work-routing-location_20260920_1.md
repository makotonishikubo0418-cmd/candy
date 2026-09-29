# FSG・Candy作業振り分け資料の配置整理 — 2026-08-04の作業記録

- History: [20260804_MODIFY_work-routing-location.md](../20260804_MODIFY_work-routing-location.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-04
- 旧Task ID: `TASK-20260804-FSG-CANDY-WORK-ROUTING-RELOCATION-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 54行目

**当時の依頼**

> Relocate `docs/rules/WORK_ROUTING.md` to repository-root `WORK_ROUTING.md`; update active routing, location, ignore, and document-responsibility references; after relocation validation, update `C:\Codex\FSG\AGENTS.md` for parent-to-child routing and remove the verified identical child `AGENTS.md`; exclude implementation, HP, generated documents, Commit, Push, Actions, production, and database work

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 42行目

- 担当表記: current
- 期間表記: 2026-08-04
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Relocate `docs/rules/WORK_ROUTING.md` to repository-root `WORK_ROUTING.md`; update active routing, location, ignore, and document-responsibility references; after relocation validation, update `C:\Codex\FSG\AGENTS.md` for parent-to-child routing and remove the verified identical child `AGENTS.md`; exclude implementation, HP, generated documents, Commit, Push, Actions, production, and database work

### 対応

> Historical result migrated from the former completed-reservation record: Relocated the Candy router with its 46-line routing section unchanged; updated all active location and responsibility references; made the new router Git-visible; updated only Section 2 of the parent AGENTS; removed the byte-identical child AGENTS; verified one AGENTS, one Candy router, 39 of 39 routed Markdown references present, `CHECK=OK documents=4`, and `git diff --check`. Commit and Push were not performed.

### 結果

**旧記録の確認結果**

> The former reservation ledger recorded status `COMPLETE`; no additional substantive verification was performed during this migration.

**旧記録の補足・未確認事項**

> Any detail not explicit in the preserved historical result remains UNVERIFIED.

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
