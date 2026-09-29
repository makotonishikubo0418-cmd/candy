# FSG・Candy作業振り分け資料の配置整理 — 2026-08-05の作業記録

- History: [20260804_MODIFY_work-routing-location.md](../20260804_MODIFY_work-routing-location.md)
- Record Date: 2026-09-20
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-05
- 旧Task ID: `TASK-20260805-CANDY-LOCAL-ROUTING-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 51行目

**当時の依頼**

> Move the tracked Candy router from repository-root `WORK_ROUTING.md` to `codex/WORK_ROUTING.md`; make repository-root `AGENTS.md` the Git-visible Candy authority; update only the directly affected authority, routing, location, safety, and active route references; preserve historical records; exclude every path outside `C:\Codex\FSG\Candy`, implementation, HP, generated documents, Commit, Push, Actions, production, and database work

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 39行目

- 担当表記: current
- 期間表記: 2026-08-05
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Move the tracked Candy router from repository-root `WORK_ROUTING.md` to `codex/WORK_ROUTING.md`; make repository-root `AGENTS.md` the Git-visible Candy authority; update only the directly affected authority, routing, location, safety, and active route references; preserve historical records; exclude every path outside `C:\Codex\FSG\Candy`, implementation, HP, generated documents, Commit, Push, Actions, production, and database work

### 対応

> Historical result migrated from the former completed-reservation record: Moved the sole Candy router to `codex/WORK_ROUTING.md`; changed `AGENTS.md` Section 2 from FSG-wide routing to Candy-local routing; made `AGENTS.md` Git-visible and removed the root-router ignore exception; updated the Git rule, management architecture, location rules, safety rules, indexes, and every active Section 5.2 routing reference while preserving historical records. Verified one Candy `AGENTS.md`, one Candy router, 38 of 38 routed Markdown targets present, zero old active Section 5.2 references, zero Markdown-table errors, `CHECK=OK documents=4`, zero staged paths, and `git diff --check`. Commit, Push, Actions, production, HP, and database work were not performed.

### 結果

**旧記録の確認結果**

> The former reservation ledger recorded status `COMPLETE`; no additional substantive verification was performed during this migration.

**旧記録の補足・未確認事項**

> Any detail not explicit in the preserved historical result remains UNVERIFIED.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
