# 店長おすすめ — DB操作担当とSQL提示方式の決定

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-29
- Sequence: 8
- Status: Verification Pending

## 記録

### 決定

- ユーザーから「DB改修は俺がする SQLコマンドで」と指示された。DB操作はユーザーが担当し、アシスタントはSQL提示・実行結果の確認を担当する。この指示をアシスタントへのDB変更許可とは扱わない。
- 添付はphpMyAdminのサーバー画面。MySQL 5.6.36、PHP 7.2.12の表示があるが、CandyアプリのPHP環境まで同一とは断定しない。左欄の `fsg` という表示だけでは実DB名やDBグループを確定できない。管理資料上の対象は `fsg_db`。
- 初めにサーバー・DB名、関連表、新設予定表、既存トリガーを確認するSELECTのみ提示する。個人情報・認証情報の取得、CREATE/ALTER/DROP/データ更新は含めない。
- 変更用SQLの適用前に、既存処理との競合、バックアップ・復旧、未了の結合検証を確認する。ユーザー実行に変わったことを、安全性確認済みの根拠にはしない。

### 提示する確認SQL

```sql
SELECT VERSION() AS mysql_version,
       @@hostname AS server_host,
       DATABASE() AS selected_database;

SELECT TABLE_SCHEMA, TABLE_NAME, ENGINE, TABLE_COLLATION
FROM information_schema.TABLES
WHERE TABLE_SCHEMA IN ('fsg_db', 'fsg')
  AND TABLE_NAME IN (
    'girls_data', 'cast_mast', 'girls_images',
    'girls_candy_page_content',
    'candy_recommendation_settings', 'candy_recommendations'
  )
ORDER BY TABLE_SCHEMA, TABLE_NAME;

SELECT TRIGGER_SCHEMA, TRIGGER_NAME, EVENT_OBJECT_TABLE,
       ACTION_TIMING, EVENT_MANIPULATION
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA IN ('fsg_db', 'fsg')
  AND (EVENT_OBJECT_TABLE IN ('girls_data', 'cast_mast')
       OR LEFT(TRIGGER_NAME, 10) = 'candy_rec_')
ORDER BY TRIGGER_SCHEMA, EVENT_OBJECT_TABLE, TRIGGER_NAME;
```

### 結果

- この時点でアシスタントはDBへ接続・操作していない。ユーザーによるSQL実行結果も未受領。
- 前記録のコード・無効設定・未実施項目を維持した。履歴のみ追記し、既存記録は変更していない。
- 記録前の読取確認では、ローカルmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementブランチなし、同日最大連番7。Git状態変更なし。

## 現在

- Remaining Work: ユーザー実行の確認SQL結果の確認、対象・既存トリガー・権限・バックアップ／復旧・結合検証の確認、変更SQLの確定とユーザーによる適用、初期移行、アプリ動作・公開確認。先の未実施項目は完了していない。
- Next Action: phpMyAdminで実行した3つの確認SELECTの結果を受け取り、変更先と既存処理の競合有無を確認する。結果0件は権限による非表示の可能性もあるため、トリガー不存在の証明とは即断しない。
