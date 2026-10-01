# 管理画面だけの有効化承諾・設定Commit・転送とDB切替手順の準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 18
- Status: Waiting for Response

## 記録

### 決定

- ユーザーの「はい」を、記録17の具体的な承諾対象（管理画面設定1ファイルの変更・stage・ローカルCommit・バックアップ付き本番反映、CANDY設定行のmigration_ready切替SQL）への承諾として受領した。Push、HP反映、実在人物の非公開・削除試験、掲載内容の任意変更は含まない。SQL実行はユーザーが担当する。
- 管理画面とHPの有効化は分離する。HPの既存処理3ファイルと任意の設定ファイルを転送時に読み取り照合し、旧HTML表示が維持される条件を満たす場合だけ管理設定を変更する。不明な変更があれば書き換え前に停止する。

### 対応・確認済み事実

- Control `site/candy_recommendation_config.php` のenabledをtrueに変更し、コメントを管理画面単独の有効化に合わせた。明示された1ファイルだけをstage・Commitした。Commitは `9578675e7325e2f1ecdf16418f16e879c2a86967`、親は `a379401c3b8dfb2dd597ed8f12ddd49af77bd06e`。Push、branch変更、HP変更、DB操作は行っていない。対象外の既存差分は保持した。
- 以下の補助ファイルをControlの `codex/project_management/investigation/candy_manager_recommendation/` に準備した。補助ファイルと履歴はCommitに含めていない。
  - `Invoke-AdminActivation.ps1`：ユーザーがSSHパスワードを入力して実行する管理設定1ファイル転送。旧13ファイル転送を繰り返さない。`-Run`なしは接続しない。
  - `activate_admin.php`：SSH標準入力で実行し、公開ディレクトリへスクリプトを置かない。既存13管理ファイルとHPガードのSHA256を確認、Web外の `candy_admin_enable_日時_乱数` に旧設定・属性・マニフェストを保存し、同一ファイルシステムのrenameで設定のみ更新する。更新後の確認失敗は元のOFF設定への復元を試み、後発変更があれば上書きせず報告する。DBへ接続しない。
  - `admin_activation_20261001.json` / `build_admin_activation.py`：上記Commitの固定転送データ。JSON SHA256=`d33326fa5d8d9f5500ca9eb81bb1d2f298bf13a14811322101e4ada2cfebc925`。旧13ファイル固定パッケージは変更していない。
  - `migration_20261001/release_sql/040_enable_admin.sql` / `build_admin_activation_sql.py`：ユーザー実行用。人物対応・公開状態・趣味、6トリガー定義、初期12件の本文/写真等を照合し、CANDY設定行だけmigration_ready=1、revision=revision+1にする。MyISAMの読み取りはsettingsのFOR UPDATEより前に行い、revisionの変化は拒否する。不一致時は更新を確定しない。再インポート、テーブル/トリガー作成、プロフィール更新はない。
- ローカル検証：設定と補助PHPの構文確認成功、基本処理287 assertions、保存mock21 assertions、旧OFF配置51 assertions、有効化/復元38 assertions成功。固定旧パッケージ・新パッケージ・SQL生成結果の再照合、SQL更新対象とロック順の静的検査、Windows PowerShell 5.1の非接続実行成功。テストは本番接続・DB接続なしで実施した。テスト用一時ディレクトリのみ、生成場所・名前を検証して削除した。
- テストの管理設定期待値を承諾済みのON段階として明示し、HP設定OFFは引き続き必須とした。旧配置テストは作業ツリーではなく、旧固定パッケージから作成したOFFの一時fixtureを検証するよう調整した。
- HISTORY.md第9章の照合：Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementは双方NOT_PRESENT。同日最大連番17の後に18を使用した。

### 未確認・実行順

- 本番の設定転送、DB切替、画面表示は本ターンでは未実行。現時点で本番管理画面が有効になったとは扱わない。
- ユーザーが `Invoke-AdminActivation.ps1 -Run` を実行する。`CANDY_SSH_EXIT_CODE=0` と `CANDY_ACTIVATION_OK=1` が出た場合だけ、phpMyAdminのfsg_dbで `040_enable_admin.sql` 全文を1回のSQL実行として送信する。転送・照合が失敗したらSQLを実行せず結果を提示する。
- 設定転送後でもDBがmigration_ready=0の間は新メニューから正常利用できない。SQL結果の `CANDY_ADMIN_DB_READY` とmigration_ready=1を確認した後、管理画面 `http://firststar.kir.jp/group/control/site/candy_recommendations.php?club=2` を開き、登録済み12名の表示を確認する。
- 復旧時は出力されたバックアップ名を `Invoke-AdminActivation.ps1 -Run -RestoreBackupName <name>` に指定し、管理設定1ファイルだけOFFに戻せる。これは準備・ローカル試験済みであり、本番復元は未実行。既存13ファイル用の復元スクリプトを有効化済み設定に対して直接実行しない。
- 実DB保存、実トリガー動作、同時更新/障害時挙動は未検証。ローカルmockやファイル転送検査を代用しない。HP公開切替の判断は別工程に残す。

## 現在

- Remaining Work: 管理設定1ファイルの本番転送、ユーザーによるDB設定行の切替、管理画面の表示/保存/解除の実動作確認。HP側の本番反映・公開切替・公開後確認。Controlのローカル2Commitは未Push。
- Next Action: ユーザーが新しい有効化コマンドを実行し、成功時のみ準備済みSQLを実行する。両方の結果を受領して、管理画面の表示確認へ進む。
