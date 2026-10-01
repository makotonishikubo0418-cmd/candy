# 店長おすすめ 設定表の構造確認と内容表の全文待ち

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-30
- Sequence: 4
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーから `fsg_db.candy_recommendation_settings` のSHOW CREATE TABLE全文を受領した。以下の定義は、提示済みの設計と一致する。アシスタントによるDB接続・実行結果ではない。
  - `club_id`: int(11)、NOT NULL、主キー。
  - `schema_version`: int(11)、NOT NULL。
  - `migration_ready`: tinyint(4)、NOT NULL、DEFAULT 0。
  - `revision`: bigint(20) unsigned、NOT NULL、DEFAULT 0。
  - ENGINE=InnoDB、DEFAULT CHARSET=utf8mb4、COLLATE=utf8mb4_unicode_ci。
- この結果は構造の確認であり、設定行の現在値や登録件数を再取得したものではない。
- `candy_recommendations` は以前の省略表示だけで、全列・主キー・索引の定義をまだ受領していない。既に確認した表の存在・engine等と、未確認の列・索引を区別する。

### 対応と結果

- [前記録の第3段階](20260929_INVESTIGATE_manager-recommendation-targets_20260930_3.md)のうち、設定表の構造確認を完了とした。もう一方の内容表の全文だけを依頼する。
- 省略への対処として別SELECTへの切替を検討した後、より新しい全文結果を受領したため、その切替は不要と判断した。追加の確認SQLは提示・実行しない。設定表の取得を再依頼しない。
- 本ターンは本記録1件のみ作成。アプリ・SQL原本・既存履歴・DB・本番ファイルを変更せず、Git状態変更も行っていない。
- 記録前のCandy照合では、ローカルmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementブランチは双方NOT_PRESENT。同日最大連番3を確認して連番4を使用した。

## 現在

- Remaining Work: `candy_recommendations` の列・主キー・索引の照合。以降は前記録3の第3段階の残る移行準備・解除処理審査・検証方式・復旧手順、第4段階の登録と連携確認、第5段階の表示切替・公開後確認を継続する。初期登録・公開切替は未実施。
- Next Action: 既に実行された `SHOW CREATE TABLE fsg_db.candy_recommendations` の全文結果を受領する。新しいSQLの実行は求めず、今回の設定表と同じ方法で全文を提示してもらう。
