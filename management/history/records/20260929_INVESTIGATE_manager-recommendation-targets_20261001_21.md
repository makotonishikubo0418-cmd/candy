# おすすめ機能だけを一時停止する緊急対応の承諾・準備完了

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 21
- Status: Waiting for Response

## 記録

### 決定・実施済み

- 記録20の承諾確認に対するユーザーの「早くしろ本番環境だぞ！」を、提示した管理画面設定1ファイルの一時OFF化・ローカルCommit・本番反映（Pushなし）への実行指示として受領した。DB・12件の登録内容・画像・HPは変更しない。
- Control `site/candy_recommendation_config.php` を有効化前のOFF版と同一内容に戻し、1ファイルだけstage・Commitした。Commit=`126a99b52291c73f380c800651f337dd5614d552`。固定OFFパッケージの元Commit `a379401c3b8dfb2dd597ed8f12ddd49af77bd06e` と当該ファイルの差分が0であることを確認した。Pushせず、対象外の差分は保持した。
- 既存 `Invoke-AdminActivation.ps1` に `-Disable` を追加した。補助PHPは設定1ファイルだけを対象として、ON/OFF既知SHA256と所有者・パスを確認し、ONならWeb外の `candy_admin_stop_日時_乱数` に変更前の設定と属性を退避して、検証済みOFF版を原子的に配置する。既にOFFなら再変更しない。未知の変更・同時変更は上書きせず停止する。HP照合や13ファイル再転送、DB接続、プロセス終了はしない。
- 設定PHP構文、基本処理287 assertions、有効化/停止/復元の57 assertions、PowerShell非接続の `-Disable` 検証が成功した。停止対象以外の12管理ファイルとHPを変更しないこと、既存ON版の退避、停止の再実行、未知変更の拒否をローカルfixtureで確認した。
- 本番SSHはユーザーのパスワード入力が必要なため、作業途中の連絡でただちに実行コマンド `Invoke-AdminActivation.ps1 -Run -Disable` を提示した。成功マーカーは `CANDY_ACTIVATION_DISABLED_OK=1`。本番停止はまだ実行結果未受領であり、復旧済みとは扱わない。
- HISTORY.md第9章の照合はCandy Local/GitHub mainともに `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、management双方NOT_PRESENT。同日最大連番20を確認して21を採番した。

### 未確認

- 原因は引き続き追加された全員分取得処理が候補であり、本番SQLの待機・実行時間は未測定。OFF化後の既存女の子編集画面の速度比較が必要。
- OFF化は新たな追加処理の実行を止めるもので、すでに実行中のリクエストやSQLを強制終了するものではない。DBのmigration_ready=1、revision=2という直近の確認状態は変更しない。

## 現在

- Remaining Work: ユーザーによる停止コマンドの本番実行、停止成功・既存編集画面の応答回復確認、原因確定と必要修正。HP公開切替は未実施のまま。Controlの3Commitは未Push。
- Next Action: `-Run -Disable` の結果を受領し、既存の女の子編集画面が通常の時間で開くか確認する。停止完了や復旧を先取りして報告しない。
