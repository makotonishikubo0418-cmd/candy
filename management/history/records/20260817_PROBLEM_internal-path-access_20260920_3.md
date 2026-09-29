# 内部ディレクトリへのHTTPアクセス制御 — 2026-08-17の作業記録

- History: [20260817_PROBLEM_internal-path-access.md](../20260817_PROBLEM_internal-path-access.md)
- Record Date: 2026-09-20
- Sequence: 3
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-17
- 旧Task ID: `TASK-20260817-INTERNAL-PATH-PRODUCTION-CONFIRMATION-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 32行目

**当時の依頼**

> Reconfirm the completed production access-control and public-page state, then preserve the final case and task evidence

### 対応

> Re-ran the production entry and internal-path contracts; separately checked representative public PHP generation and the CSS response; recorded the final results in the active case and this task history. The permanent specification documents already contained the adopted contract and were not changed with volatile HTTP observations. No Git-state change, deployment, database operation, Search Console action, or access-log investigation was performed

### 結果

**旧記録の確認結果**

> Entry and canonical-host contract passed. All seven internal-path checks passed: source directory/HTML/template `404`, source CSS `200`, and include directory/top-level/nested files `403`. `mypage.php`, `area.php`, `hotel.php`, and `blog.php` each returned `200`, declared the expected canonical URL, and contained no unresolved `rep[0-9]+eot` token. `/source/style.css` returned `200`, `text/css`, and 11,460 bytes. The post-recording management audit passed

**旧記録の補足・未確認事項**

> Access logs and Search Console remained excluded. The representative public-PHP check does not assert every public PHP URL, and no additional item was required for the completed case

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 40行目

旧状態表記: `Complete / Completed`。

旧台帳の次対応:

> None

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
