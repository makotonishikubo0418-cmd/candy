# 指示書の優先順位とルートAGENTSの整理 — 2026-07-25の作業記録

- History: [20260725_MODIFY_instruction-hierarchy.md](../20260725_MODIFY_instruction-hierarchy.md)
- Record Date: 2026-09-20
- Sequence: 2
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-25
- 旧Task ID: `TASK-20260725-INSTRUCTION-HIERARCHY-REMEDIATION-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 16行目

**当時の依頼**

> Enforce the instruction hierarchy `AGENTS.md` > routed common management documents > routed category documents across every tracked Markdown file, remove lower-level duplicates and conflicts, and retire stale instruction/state text

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 43行目

- 担当表記: current
- 期間表記: 2026-07-25
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Enforce `AGENTS.md` > routed common management documents > routed category documents across all tracked Markdown; remove duplicate, conflicting, stale, and shadow instructions; use the ignored root `調査結果.md` for the final self-audit and delete it after a clean result; exclude implementation, generated documents, HP, Text data, Commit, Push, Actions, production, and database work

### 対応

> Added the explicit hierarchy and cumulative-route boundary to root `AGENTS.md`; centralized management locations in `codex/README.md`, Git procedure in `DOCUMENT_RULES.md`, deletion/recovery safety in `SAFETY_PROTOCOL.md`, and response structure only in root `AGENTS.md`; reduced common and category documents to responsibility-specific rules; converted obsolete entry points and dated records to non-executable compatibility/history sources; removed stale fixed counts, completed handoff instructions, duplicate Git/authority/report rules, and obsolete current-state sections.

### 結果

**旧記録の確認結果**

> Audited all 51 tracked Markdown files through the temporary ignored `調査結果.md`: one root `AGENTS.md`, zero override files, zero live `HP/AGENTS.md` references, zero UTF-8 errors, broken relative links, table-column errors, duplicate headings, top-level numbering errors, conflict markers, lower response-format overrides, or lower Git-authority redefinitions; generated documents remained unchanged; `git diff --check` and `candy-site-state check` passed. The temporary audit file is removed after the clean audit.

**旧記録の補足・未確認事項**

> Commit, Push, Actions, production, HTTP, browser, and database operations were not performed because they were outside this task.

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
