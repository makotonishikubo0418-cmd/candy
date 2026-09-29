# 移設後のスクリプト内部パスの修正 — 2026-07-17の作業記録

- History: [20260717_PROBLEM_scripts-workspace-paths.md](../20260717_PROBLEM_scripts-workspace-paths.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-17
- 旧Task ID: `TASK-20260717-SCRIPTS-LOCAL-PATHS-001`
- 出典: [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 24行目

**当時の依頼**

> Migrate internal paths in `codex/scripts/` to the new local layout and validate without writes

### 対応

> Unified 10 Python files on `REPO_ROOT`, `HP_ROOT`, the `TEXT_*_DIR` constants, `SCRIPTS_DIR`, and `DOCS_DIR` in `candy_page_common.py`. Changed the hotel publish dry-run to avoid acquiring a write lock. Updated two PowerShell files for the bundled Python interpreter, preview mode, and bytecode suppression. Three CMD files already used relative execution and were unchanged. Updated `README.md` and `PROJECT_STATUS.md` to replace the execution STOP notice with verified results.

### 結果

**旧記録の確認結果**

> Verified 10 files with `py_compile`, all module imports, seven shared paths, area and hotel `target-check` and `target-next`, area, hotel, and blog build dry-runs, hotel count JSON, and document generation with `--preview`. Found zero old `HP/codex/`, `HP/Text_*_data`, or fixed `parents[3]` references. Runtime writes to HP, Text, and target generated documents were zero. Deleted one generated pyc and empty directories after approval; a second wrapper run also produced zero pyc files.

**旧記録の補足・未確認事項**

> Production, publish, and actual writes to 13 documents were not performed. The complete public production path remains unverified.

**関連連絡の引き継ぎ** — [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 28行目

旧連絡 `COMM-20260717-015`（日付: 2026-07-17、状態: COMPLETE）。

> STOP while the internal paths in `codex/scripts/` were not migrated

> `TASK-20260717-SCRIPTS-LOCAL-PATHS-001` migrated and dry-run-verified the internal paths. On 2026-07-18, the current scripts were rechecked and contained zero old `HP/codex/`, `HP/Text_*_data`, or fixed `parents[3]` references.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
