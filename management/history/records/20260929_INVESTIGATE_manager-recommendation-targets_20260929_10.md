# 店長おすすめ — 権限確認・バックアップ取得報告と専用2表の作成SQL提示

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-29
- Sequence: 10
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザー提示の権限一覧でDB単位の権限表記が `fsg\_db` と確認できた。記録9で未確定だった、完全一致条件による取りこぼしの原因を確認した。
- その表記を指定した追加SELECTで、`firststar@localhost` の対象DBに対する `CREATE`、`DROP`、`INSERT`、`SELECT`、`TRIGGER`、`UPDATE` の6権限がすべて表示された。先に提示された関連トリガー0件は、当該権限を持つユーザーの調査結果として扱う。今後の追加・変更がないことまで保証する結果ではない。
- ユーザーは本日バックアップ未取得と回答した後、関連4表の構造・データをSQL形式で保存する案内に対して「バックアップを取得しました」と報告した。取得完了はユーザー申告として記録する。保存ファイル名・容量・時刻は未受領であり、内容、更新停止中の整合性、復元可能性をアシスタントが検証したものではない。
- 2表の定義とHP側・管理側の参照列をローカルソースで照合した。両側の `candy_recommendation_config.php` は現在も `enabled=false`。両側のDB読取・保存は設定行の `schema_version=1` と `migration_ready=1` を要求している。

### 決定と提示範囲

- ユーザー自身がSQLを実行する担当分担を維持する。アシスタントはDBへ接続・変更しない。
- 今回は新設する `fsg_db.candy_recommendation_settings` と `fsg_db.candy_recommendations` の作成、および前者への初期設定1行だけを案内する。既存4表の構造・データを変更せず、外部キー、トリガー、既存12名の移行、HPの切替、管理画面の有効化を含めない。
- 元の `002_create.sql` の表定義のみを使用し、対象DBを各オブジェクト名に明記する。初期INSERTは両表のCREATE成功後とする。元ファイルのトリガー部分を含めた一括実行は案内しない。
- 下記CREATE・INSERTは1文ずつ実行し、各文の正常終了後に次へ進む。エラー時は再実行・DROP・既存データの復元を行わず停止し、結果を確認する。`IF NOT EXISTS` で既存同名表を見過ごさず、同名表があればエラーで止める。
- この独立した準備段階で中断する場合は、成功分の専用表と設定行を保持し、`migration_ready=0` と両アプリ設定falseを維持する。既存4表を変更しないため、この段階の中断に既存表のバックアップ復元は用いない。削除による原状復帰が必要なら、作成結果と利用状況を確認して別途対象を確定する。自動DROPはしない。
- 既存処理に介入するトリガーは、記録7・8の混在engine・並行更新・他店舗影響・結合試験・復旧確認が済むまで本番実行を案内しない。バックアップ取得報告や専用表作成を、これらの完了と読み替えない。

### 提示する作成SQL

```sql
CREATE TABLE `fsg_db`.`candy_recommendation_settings` (
    club_id INT NOT NULL PRIMARY KEY,
    schema_version INT NOT NULL,
    migration_ready TINYINT NOT NULL DEFAULT 0,
    revision BIGINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fsg_db`.`candy_recommendations` (
    girls_id INT NOT NULL PRIMARY KEY,
    club_id INT NOT NULL,
    cast_id INT NOT NULL,
    selected TINYINT NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    pc_image VARCHAR(68) NOT NULL DEFAULT '',
    sp_image VARCHAR(68) NOT NULL DEFAULT '',
    title VARCHAR(255) NOT NULL DEFAULT '',
    heading VARCHAR(1000) NOT NULL DEFAULT '',
    body TEXT NOT NULL,
    updated_by INT NOT NULL,
    updated_at DATETIME NOT NULL,
    KEY public_order (club_id,selected,sort_order,girls_id),
    KEY cast_selection (cast_id,club_id,selected)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fsg_db`.`candy_recommendation_settings`
    (club_id,schema_version,migration_ready,revision)
VALUES (2,1,0,0);
```

### 提示する結果確認SQL

```sql
SELECT TABLE_NAME, ENGINE, TABLE_COLLATION
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'fsg_db'
  AND TABLE_NAME IN (
      'candy_recommendation_settings', 'candy_recommendations'
  )
ORDER BY TABLE_NAME;

SELECT club_id, schema_version, migration_ready, revision
FROM `fsg_db`.`candy_recommendation_settings`;

SELECT COUNT(*) AS recommendation_count
FROM `fsg_db`.`candy_recommendations`;
```

期待値は2表ともInnoDB・utf8mb4_unicode_ci、設定1行が2/1/0/0、おすすめ件数0。期待値はまだ実行結果ではない。

### 結果

- 履歴10だけを新規作成した。既存履歴・アプリ・SQL原本・DB・Git状態は変更していない。作成SQLの本番実行結果は未受領。
- 履歴追加前に、同日最大連番9を確認した。ローカルmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致し、managementブランチはNOT_PRESENTだった。

## 現在

- Remaining Work: ユーザーによる専用2表の作成・初期設定・結果確認。既存人物対応、隔離DBでの解除処理・競合・失敗復旧・他店舗を含む結合検証、バックアップの内容と復元方法の検証、画像処理環境と公開経路、初期12名移行、両側配置・有効化・公開確認、正式監査とGit公開は未了。
- Next Action: ユーザーによる上記SQLの実行結果を受け取り、2表と初期設定を確認する。アプリは有効化しない。
