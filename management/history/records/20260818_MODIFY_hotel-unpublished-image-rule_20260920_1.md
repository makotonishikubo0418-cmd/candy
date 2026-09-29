# 未公開ホテル画像の保持・公開規則の整備 — 2026-08-18の作業記録

- History: [20260818_MODIFY_hotel-unpublished-image-rule.md](../20260818_MODIFY_hotel-unpublished-image-rule.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-18
- 旧Task ID: `TASK-20260818-HOTEL-UNPUBLISHED-PUBLIC-COPY-RULE-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 29行目

**当時の依頼**

> Change the hotel-image lifecycle so accepted source pairs remain stored while public copies exist only for published pages or active authorized page-publication work

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 33行目

- 担当表記: current
- 期間表記: 2026-08-18
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Change only `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` so accepted hotel-image pairs remain stored while unpublished public copies may be removed and later reinstalled for an authorized page publication; update only the required atomic case, change-history, reservation, and August task-history records; exclude every image file, page, script, Git-state change, deployment, production mutation, and database operation

### 対応

> Updated the canonical hotel-image asset-management specification to separate accepted-source retention from public-copy retention; made `ACCEPTED` the valid accepted-source-only state; prohibited stockpiling public copies for future pages; added exact gates for returning an unpublished matching public pair to `ACCEPTED`; required two-file local, GitHub, and production removal as one authorized unit; required later reinstallation from unchanged accepted bytes; registered and classified atomic case `CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-20260818`. No image, page, script, generated output, Git state, deployment, production, or database operation was changed

### 結果

**旧記録の確認結果**

> The routed canonical specification was reviewed in full; the new rule retains accepted files, distinguishes `PUBLISHED` and active-publication requirements, excludes partial, mismatched, and `LEGACY_PUBLIC_ONLY` pairs, requires exact deletion authority, and records public-copy retention state. Management audit, site-state content check, Markdown-table audit, and `git diff --check` pass; the worktree contains only the six authorized specification and management-record files

**旧記録の補足・未確認事項**

> At original task completion, no image deletion, Commit, Push, deployment, production removal, or production URL verification was performed. The rule itself was subsequently pushed in Commit `19e22b4bf1ac4fecb4096e384fa32aab1f9f78dc`, which is contained in live GitHub `main`; the separate removal case owns the later image-deletion and production evidence

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 37行目

旧状態表記: `Complete / GitHub Published`。

旧台帳の次対応:

> None; the separately registered `CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-REMOVAL-20260818` case later completed the verified 48-pair removal and production verification

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
