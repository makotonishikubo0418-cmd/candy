# 店長おすすめ初期登録の独立事後照合成功

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 14
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーが `022_postcheck.sql` の実行結果を会話へ提示した。トリガー6件の `definition_matches` がすべて1、専用登録の `total_rows=12`、12名の `content_matches` がすべて1であり、保存後の独立した照合が成功した。
- 12名のgirls_id・公開番号・掲載順1〜12は初期移行の対象と一致し、全行の `updated_at=2026-10-01 09:00:52`。写真名、タイトル、見出し、本文、人物対応、選択、順序、登録者を含むSQLの比較条件が全件成立している。
- 設定行は `club_id=2 / schema_version=1 / migration_ready=0 / revision=1`。DBの公開準備フラグは未切替。既存12名の移行データについて、同じ確認SQLを再実行してもらう必要はない。
- アシスタントによるDB操作・本番ファイル変更・Git状態変更は行っていない。実際の非公開・削除時の解除試験や両アプリの結合動作は、このSELECT結果だけでは検証済みとしない。

### 次工程の承諾確認

- [承諾記録11](20260929_INVESTIGATE_manager-recommendation-targets_20261001_11.md)は画像配置・初期登録・自動解除追加までで、両アプリの有効化・公開切替を含まない。この未承諾範囲だけをまとめて確認する。
- 提案対象は、本件の改修ファイルをCANDYのHPとControl管理画面の本番へ配置し、必要な動作確認後に管理画面を有効化して、既存12名を管理画面連動表示へ切り替える工程。DB側の対象は `fsg_db.candy_recommendation_settings` のclub_id=2の `migration_ready` を0から1へ切り替えるためのSQL準備とユーザー実行であり、実行時の状態・検証結果を確認してから案内する。
- DBのSQL実行は引き続きユーザーが担当する。GitのCommit/Push、他案件の変更、実在人物を非公開・削除する試験はこの提案の対象に含めない。配置・検証・復旧の具体的な手順を確定し、残る検証条件を満たすまでは一般公開を切り替えない。
- HISTORY.md第9章に従い記録前のLocal/GitHub比較を実施。Candy mainは双方 `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチは双方NOT_PRESENT。同日最大連番13を確認して14を使用した。

## 現在

- Remaining Work: 実DBの自動解除・失敗時挙動の確認、HP/管理画面の本番配置・結合・有効化、公開切替と公開後検証。Git公開は未実施・未承諾。
- Next Action: 本件のHP/管理画面への配置・検証・有効化・公開切替（DB切替SQLはユーザー実行）の承諾を受け、具体的な配置・検証・復旧手順を確定する。同じ移行登録・事後確認SQLは繰り返さない。
