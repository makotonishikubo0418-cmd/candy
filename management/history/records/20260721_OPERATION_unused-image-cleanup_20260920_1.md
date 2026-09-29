# 未使用画像580件の整理 — 2026-07-21 to 2026-07-22の作業記録

- History: [20260721_OPERATION_unused-image-cleanup.md](../20260721_OPERATION_unused-image-cleanup.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-21 to 2026-07-22
- 旧Task ID: `TASK-20260721-HP-UNUSED-IMAGE-CLEANUP-001`
- 出典: [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 26行目

**当時の依頼**

> Delete only production images proven unused, delete directories emptied by those deletions, preserve future-production inputs, and complete Commit, Push, Actions, and production verification

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 72行目

- 担当表記: current
- 期間表記: 2026-07-21 to 2026-07-22
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Full `HP/` image-use reconciliation; deletion of images proven unused and directories emptied by those deletions; required generated current-state documents; deletion-only and empty-directory production-deployment support; target-limited Commit, Push, Actions, production verification, and task records

### 対応

> Audited 987 image files against local references and a refreshed 401-URL production crawl; fixed the approved deletion set at 580 files and the keep set at 407 files; retained 202 accepted input copies under `Text_area_data` for future page production; added tested deletion-only, transactional rollback, operation-limit, and empty-directory support to the production deployment automation in Commit `706ea48`; deleted and deployed the 580 files through proof Commit `069de33` and batch Commits `27deea8`, `865c0af`, `1554a7e`, `b97dbb4`, and `78b1fa9`; removed `imgHtml/new_202601/adsite`, `imgHtml/pc/pc`, and `imgHtml/pc/s` after they became empty; and regenerated the four current-state documents after every batch.

### 結果

**旧記録の確認結果**

> Confirmed the fixed deletion plan SHA-256, zero current production-reference overlap for all 580 deletion targets, all 407 keep files present locally, all 580 targets absent locally and from Git tracking, and all three emptied directories absent locally and in production. All seven Actions Runs (`29864896267`, `29865162916`, `29865607661`, `29866446421`, `29867012142`, `29867550499`, and `29868086534`) completed successfully with no deployment errors. Final production checks returned HTTP 200 for all 401 audited public URLs and successful responses for all 296 production-referenced assets that exist locally. `candy-site-state audit` and `check` passed, the worktree was clean, and local `main` equaled `origin/main` at `78b1fa9`.

**旧記録の補足・未確認事項**

> Manual browser rendering, Search Console, analytics, Lighthouse, and database behavior were not independently verified. Thirteen pre-existing production references without matching local files remain outside this deletion scope; none was one of the 580 deleted files.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
