# 更新作業の開始手順と入口条件の整備 — 2026-08-06の作業記録

- History: [20260806_MODIFY_renewal-entry-contract.md](../20260806_MODIFY_renewal-entry-contract.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-08-06
- 旧Task ID: `TASK-20260806-RENEWAL-ENTRY-CONTRACT-001`
- 出典: [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 50行目

**当時の依頼**

> Align the current post-renewal production entry contract across canonical management, page-publication verification, and protected deployment workflows without performing Git publication or production mutation

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 38行目

- 担当表記: current
- 期間表記: 2026-08-06
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Five canonical management documents, three page publishers, shared release checker, two deployment workflows, `HP/.htaccess`, one new release-check regression test, four generated current-state documents, `TASK_LOG.md`, and this reservation

### 対応

> Replaced the obsolete CityHeaven and final-switchover contract in five canonical management documents, three page publishers, the production workflow, and the `.htaccess` comment; centralized root, index, canonical host/scheme, public-indexability, and direct-host checks in `candy_release_check.py`; connected the common check to area, hotel, blog, normal deployment, and protected `.htaccess` deployment; added one regression test; regenerated all four current-state documents through the canonical generator; replaced the unresolved final-switchover backlog row with the remaining Git-publication gate

### 結果

**旧記録の確認結果**

> Python syntax passed for five files; the new entry-contract tests rejected external root/index redirects and missing robots controls; area, hotel, blog, category-hotel, and FTP integration tests passed; both workflow files parsed as YAML; live entry verification returned `ENTRY_CONTRACT_OK` for all ten checks; sitemap preview/sync reported 128 URLs and zero changes; the second generated-document write changed zero files; global, top, and `nishisakamotocho` state checks passed with fingerprint `sha256:411d16f27abe85787a8137102a15bf448f54bf2ca88da4c1e81f76f7fa936a92`; obsolete active-contract matches were zero

**旧記録の補足・未確認事項**

> Commit, Push, a live Actions run using the corrected workflow, resumption of the preserved `nishisakamotocho` publication state, the remaining three area pages, and browser rendering were not performed

## 現在

- Remaining Work: 同じ案件の後続作業・判断がある。後続の進捗記録で到達点を管理する。
- Next Action: 同じ案件の次の進捗記録に続く。
