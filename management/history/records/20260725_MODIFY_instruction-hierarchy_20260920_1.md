# 指示書の優先順位とルートAGENTSの整理 — 2026-07-25の作業記録

- History: [20260725_MODIFY_instruction-hierarchy.md](../20260725_MODIFY_instruction-hierarchy.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-25
- 旧Task ID: `TASK-20260725-ROOT-AGENTS-CONSOLIDATION-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 18行目

**当時の依頼**

> Keep only the repository-root agent instruction file, preserve every necessary rule in its existing canonical document, remove the secondary HP router, eliminate its references, and update the root management-document index

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 45行目

- 担当表記: current
- 期間表記: 2026-07-25
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Keep only root `AGENTS.md`; audit and remove the former HP-specific router; preserve necessary rules in their existing canonical documents; remove every live and historical path reference; update root and README routing, affected project-management documents, HP specifications and runbooks, generated state, deployment workflow/scripts/tests, and `candy_site_state.py`; exclude Commit, Push, Actions, production, database, and every path outside `C:\Codex\Candy`

### 対応

> Read both instruction files in full, retained the root file, and removed the one secondary HP router after confirming that its production, image, change-unit, safety, and STOP rules already existed in the category runbooks, common governance, operation basics, and production migration source. Updated root and README routing, affected project-management documents, HP specifications and runbooks, deployment workflow/scripts/tests, and the state generator; regenerated all four current-state documents. No duplicate management document was created.

### 結果

**旧記録の確認結果**

> Confirmed exactly one `AGENTS.md` and zero `AGENTS.override.md` files remain under `C:\Codex\Candy`; found zero deleted-path or override references; deployment self-test, deployment integration test, state-metadata tests, Python syntax checks, generated-document reproducibility, `CHECK=OK documents=4`, and `git diff --check` passed.

**旧記録の補足・未確認事項**

> Commit, Push, Actions, production, database, HTTP, and browser operations were not performed.

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
