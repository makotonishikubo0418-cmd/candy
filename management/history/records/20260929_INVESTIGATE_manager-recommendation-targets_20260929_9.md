# 店長おすすめ — phpMyAdmin確認結果と権限確認SQLの補正

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-29
- Sequence: 9
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーが提示したphpMyAdminの実行結果では、MySQLは `5.6.36`、サーバーは `o4042s-134.kagoya.net`。初回の選択DBは `information_schema` だったが、その後の `USE fsg_db` と確認SELECTでは選択DBが `fsg_db`、接続アカウントが `firststar@localhost` と表示された。アシスタントによる直接接続の結果ではない。
- 対象表の検索結果には `fsg_db` の4表がある。`cast_mast`、`girls_data`、`girls_images` はMyISAM、`girls_candy_page_content` はInnoDBで、4表とも照合順序は `utf8_general_ci`。新設予定のおすすめ2表は検索結果に含まれていない。
- 関連トリガーの検索結果は0件。ただしTRIGGER権限が未確認のため、不存在の証明とは扱わない。
- 提示した権限確認SQLの結果は `GLOBAL / *.* / FILE` の1行。これだけでDB単位の権限がないとは判断できない。
- バックアップ取得済みかについては、まだユーザーから回答がない。

### 確認SQLの補正

- 訂正対象は前回チャットで提示した権限確認SELECT。`SCHEMA_PRIVILEGES.TABLE_SCHEMA = 'fsg_db'` の完全一致条件は、DB名がエスケープ付きまたはワイルドカードで登録された権限を取りこぼし得る。権限確認方法として不十分だった。
- MySQL公式5.6資料では、DB単位の権限指定で `%`・`_` を使用でき、文字としての `_` はバックスラッシュでエスケープすると説明されている。根拠: [MySQL 5.6 Reference Manual](https://downloads.mysql.com/docs/refman-5.6-en.pdf)。
- 実際の今回の原因がその表記であるかは未確認。DB名の絞り込みを外し、現在のアカウントに付与されたDB単位権限を以下のSELECTで確認する。認証情報や各DBの業務データは取得しない。

```sql
SELECT TABLE_SCHEMA AS grant_database,
       GROUP_CONCAT(
           PRIVILEGE_TYPE ORDER BY PRIVILEGE_TYPE SEPARATOR ', '
       ) AS granted_privileges
FROM information_schema.SCHEMA_PRIVILEGES
WHERE GRANTEE = CONCAT(
    QUOTE(SUBSTRING_INDEX(CURRENT_USER(), '@', 1)),
    '@',
    QUOTE(SUBSTRING_INDEX(CURRENT_USER(), '@', -1))
)
GROUP BY TABLE_SCHEMA
ORDER BY TABLE_SCHEMA;
```

### 結果

- この対応ではアシスタントはDBへ接続・操作していない。新しいSELECTの実行結果は未受領。権限変更、表作成、トリガー作成、移行、アプリ有効化は行っていない。
- 履歴作成前の読取照合では、ローカルmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致し、managementブランチはNOT_PRESENT、同日最大連番は8だった。Git状態変更なし。

## 現在

- Remaining Work: 補正後の権限確認結果とバックアップ取得状況の回答待ち。記録7・8の結合検証、復旧方法確認、変更SQL確定・ユーザー実行、初期移行、アプリ動作・公開確認は引き続き未了。
- Next Action: 上記SELECT結果とバックアップ取得状況を受け取り、権限・既存トリガーの判断を確定する。安全性確認が未了の作成SQLは実行案内しない。
