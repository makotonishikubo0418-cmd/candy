# 管理画面13ファイルの本番配置成功

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 17
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーが `Invoke-AdminDeployment.ps1 -Run` の実行結果を提示した。`CANDY_SSH_EXIT_CODE=0`、`created=10 / updated=3 / reused=0 / verified=13`、`CANDY_ADMIN_DEPLOYMENT_OK=13`。前記録16の固定パッケージに含まれる管理画面13ファイルの本番配置と配置後SHA256照合が成功した。
- 本番バックアップは `/firststar/candy_control_backup_20261001_001535_f23a45c3175cf333`。スクリプトの成功結果により、旧3ファイルの内容・属性と復旧マニフェストの保存を確認した。バックアップは保持する。復元の本番実行は行っていない。
- `CANDY_ADMIN_DISABLED=1; HP_NOT_CHANGED=1; DB_NOT_ACCESSED=1` と `ADMIN_TRANSFER_CONFIRMED; MENU_NOT_ENABLED; HP_NOT_SWITCHED` を確認した。管理画面の新機能はOFFのままで、新メニューは未表示。今回の配置でHPファイルやDBは変更していない。
- 冒頭のMIB警告は残るが、配置・照合成功のマーカーと正常終了を確認した。今回の転送を再実行したり、MIB設定を変更したりする必要はない。
- 確認はユーザー提示の実行結果に基づく。本ターンでアシスタントは本番接続・DB操作・Git状態変更・アプリコード変更を行っていない。実ブラウザーの画面表示、保存、自動解除動作の本番確認をこの転送結果で代用しない。

### 次工程の承諾対象

- 次は管理画面側だけの利用開始準備と有効化であり、HPの公開切替とは分ける。今回までの承諾はOFF状態での配置のため、この有効化範囲をまとめて承諾確認する。
- 対象はControlの `site/candy_recommendation_config.php` の `enabled=false` からtrueへの変更、その1ファイルのstage・ローカルCommit（Pushなし）・バックアップ付き本番反映、および `fsg_db.candy_recommendation_settings` のclub_id=2の `migration_ready=0` から1への切替SQL。DBのSQL実行は引き続きユーザーが担当する。
- 承諾後、HP側が切り替わらない条件と依存・検証・復旧手順を確認し、管理画面のメニューと登録済み12名の表示を確認する。実在人物の非公開・削除試験、掲載内容の勝手な変更、HP側の配置・有効化、GitHubへのPushは含めない。残る実DB動作の未確認事項を消したり、安全性を保証したりしない。
- HISTORY.md第9章の確認ではCandy Local/GitHub mainがともに `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementは双方NOT_PRESENT。同日最大連番16を確認して17を使用した。

## 現在

- Remaining Work: 管理画面の有効化・表示/保存/解除の実動作確認。HP側の本番反映・公開切替・公開後確認。Control Commitは未Push。
- Next Action: 管理画面設定1ファイルの変更・stage/ローカルCommit・本番反映と、対象DB設定行の切替（SQLはユーザー実行）の承諾を受ける。HPは切り替えず、成功済みの13ファイル転送を繰り返さない。
