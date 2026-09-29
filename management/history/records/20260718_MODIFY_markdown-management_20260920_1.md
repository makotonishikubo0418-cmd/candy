# HP内Markdownの管理対象・責任の整理 — 2026-07-18の作業記録

- History: [20260718_MODIFY_markdown-management.md](../20260718_MODIFY_markdown-management.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

この記録は旧資料に記載された事実・判断を移したもの。今回、当時のサイト・本番・DB・GitHubの状態を再検証したものではない。旧管理規則やパスは当時の経緯として扱う。

- 旧作業日: 2026-07-18
- 旧Task ID: `CANDY-HP-MD-MANAGEMENT-20260718`
- 出典: [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 23行目

**当時の依頼**

> Separate stable and generated HP management Markdown, isolate legacy documents, and implement a management update gate

**予約情報の補助根拠** — [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) 97行目

- 担当表記: current
- 期間表記: 2026-07-18
- 旧予約状態: COMPLETE（予約の終了を示し、本番確認済みとは限らない）

当時の予約範囲:

> Management entry point, stable and generated documents, management and publish scripts, and four specified legacy document groups

### 対応

> Made `CANDY_HP_STRUCTURE_MAP.md`, `CANDY_CODE_FILE_STRUCTURE.md`, and the new `CANDY_SEO_SPEC.md` stable canonical documents. Implemented `audit`, `preview`, `write`, and `check` in `candy-site-state` and generated four documents. Integrated the pre-stage `write` and `check` gate into area, hotel, and blog publish flows and runbooks. Relocated 20 files in the four specified legacy groups to NAS Backup and deleted their local originals.

### 結果

**旧記録の確認結果**

> Audit passed, the second `write` produced zero differences, and `check` passed. Generated records for 113 public PHP files, 270 Text files, 253 unique candidates, and 1,054 assets. Compared area `arata`, hotel `villacosta500`, blog `poccharigirl`, and top `index` with actual files. All three publish self-tests passed. Confirmed matching SHA-256 values for all 20 NAS payload files, a UTF-8-without-BOM `LEGACY_INDEX`, and zero remaining local files in the four legacy groups.

**旧記録の補足・未確認事項**

> Existing structure, SEO, and asset issues were not corrected. Production HTTP, database, browser, Commit, Push, and Actions were not performed.

**移行時の完了判定**: 上記の当時の結果と案件の完了条件を照合し、記録された範囲は完了として引き継ぐ。旧記録で対象外・未確認とされた事項は、その記載を保持する。

## 現在

- Remaining Work: None
- Next Action: None
