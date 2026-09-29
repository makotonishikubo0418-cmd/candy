# 公開除外条件の修正と動画4件のGit管理復元 — 2026-08-19の作業記録

- History: [20260819_MODIFY_deploy-exclusions-movies.md](../20260819_MODIFY_deploy-exclusions-movies.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-19
- 旧Task ID: `TASK-20260819-DEPLOY-EXCLUSION-MOVIE-RECOVERY-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 20行目

**当時の依頼**

> Prevent local member-development files from entering production deployment and make all four required public movies recoverable through Git

### 対応

> Added `HP/sql/**` and `HP/.gitignore` to both the normal Push trigger exclusions and FTP deployment-plan exclusions; added focused exclusion assertions; removed only the `movie/` ignore rule from `HP/.gitignore`; explicitly staged `HP/movie/candyStyle.mp4`, `HP/movie/howToMyPage.mp4`, `HP/movie/howToMyPage.ogv`, and `HP/movie/howToMyPage.webm` as Git additions; recorded the permanent deployment and recovery rule and registered atomic case `CANDY-DEPLOY-EXCLUSION-MOVIE-RECOVERY-20260819`. No Commit, Push, production deployment, server mutation, database operation, or unrelated cleanup was performed

### 結果

**旧記録の確認結果**

> `candyStyle.mp4` is referenced by `HP/source/index.html`; the three `howToMyPage` formats are referenced by `HP/source/mypage.html`; all four indexed files match the inspected local paths and total 49,586,005 bytes; deployment self-test and integration pass; Python and workflow YAML syntax pass; sitemap synchronization changed zero of 140 URLs; full site-state check passes with fingerprint `sha256:1ca12944ff3ddb8e0eb0252c66eaf985f6591acf0842c3a6b92727ab568df745`; the deterministic second write changed zero files

**旧記録の補足・未確認事項**

> At original task completion, Commit, Push, GitHub storage, Actions, and production upload were unverified. The complete scope was subsequently pushed in Commit `66f199bd8f00c916c6d693fea00d1fa94557c7d3`, which is contained in live GitHub `main`. Production upload, current production hash equality, browser playback, access logs, and external inbound references remain `UNVERIFIED`

**旧案件台帳との照合** — [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) 28行目

旧状態表記: `GitHub Published / Active`。

旧台帳の次対応:

> Verify the resulting Actions state, production movie hashes, and browser playback. Production deployment, current production hash equality, playback, access logs, and external inbound references remain `UNVERIFIED`

**移行時の状態判定**: GitHub反映まで記録済み。本番動画のハッシュ一致・再生、実運用での除外動作は未確認。 旧表記を根拠なく全工程完了と扱わない。

## 現在

- Remaining Work: GitHub反映まで記録済み。本番動画のハッシュ一致・再生、実運用での除外動作は未確認。
- Next Action: 不足している根拠・確認結果を照合し、必要な調査または確認結果をこの案件の新しい進捗記録に追記する。
