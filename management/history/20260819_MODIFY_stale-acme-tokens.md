# 期限切れACME検証ファイル33件の整理

- Type: MODIFY
- Start Date: 2026-08-19

## 目的

期限切れACME検証ファイル33件の整理を行う。

## 対象範囲

ACMEトークン33件、保持する.htaccess、Git・公開除外条件。

## 完了条件

指定トークンの整理と再混入防止を検証し、サーバー上の実在・更新運用の未確認点を解消する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260819-STALE-ACME-TOKEN-CLEANUP-001`（2026-08-19）。

当時の依頼:

> Remove stale one-time ACME challenge files, retain the access configuration, and prevent the same runtime artifacts from re-entering Git or normal deployment

出典: [TASK_LOG_2026_08.md](履歴/TASK_LOG_2026_08.md) 19行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。

旧案件ID: `CANDY-STALE-ACME-TOKEN-CLEANUP-20260819`。
