# 内部ディレクトリへのHTTPアクセス制御 — 2026-08-17の作業記録

- History: [20260817_PROBLEM_internal-path-access.md](../20260817_PROBLEM_internal-path-access.md)
- Record Date: 2026-09-20
- Sequence: 2
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-17
- 旧Task ID: `TASK-20260817-INTERNAL-PATH-ACCESS-DEPLOY-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 33行目

**当時の依頼**

> Publish the management and verification changes separately from `HP/.htaccess`, then preview and deploy exactly one protected `.htaccess` operation

### 対応

> Updated the active case for the separately authorized publication and production phase; explicitly staged and committed 15 management, verification, and workflow paths as `dccf63b60418fd649ae0f6d68dc950c8711b44ad`; pushed and verified GitHub `main`; staged and committed only `HP/.htaccess` as `61567e7a599c993748b9e87ecfd747e15634ff48`; pushed and verified GitHub `main`; ran protected preview `31986249415`; reused its exact parent SHA, target SHA, operation count, plan token, and confirmation in protected deploy `31986330202`; recorded completion in the case, registry, defect route, and task history. No branch change, database operation, unrelated production file, Search Console action, or access-log investigation was performed

### 結果

**旧記録の確認結果**

> Before publication, management audit, access-contract regression, deployment self-test and integration, and site-state audit passed. Both published Commit SHAs matched local, `git ls-remote`, and GitHub API results at their verification points. The `.htaccess` Commit changed exactly one path. Preview reported one upload, zero deletions, 2,418 bytes, and plan token `b9cba0513b955f09722a49a05f273edcdf954f097b76e41fdcd1cca21be96191` without FTP. Deploy logged `VERIFIED 1/1` and SHA-256-verified one file with zero deletions. Workflow and independent local checks passed the unchanged entry contract and all seven internal-path results: source directory/HTML/template `404`, source CSS `200`, and include directory/top-level/nested files `403`

**旧記録の補足・未確認事項**

> Access logs and Search Console were excluded. No other item required for the case remains unverified

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
