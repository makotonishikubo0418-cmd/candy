# 店長おすすめ環境確認のHTTP 403原因確定

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 6
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーが修正版 `Invoke-EnvironmentCheck.ps1 -Run` を本番へ実行した。最初の認証拒否後にSSH接続が成立し、runner version 2の `preflight`、`create`、`http` まで進んだ。
- `CANDY_DIAGNOSTIC_HTTP_STATUS=403`、`HTTP_RESPONSE_REJECTED`、SSH終了コード33で停止した。`cleanup` と `CANDY_DIAGNOSTIC_REMOVED` が返り、当該一時診断ファイルの削除は確認できた。Web診断JSONは得られていない。
- ユーザー提供のKAGOYAエラーログに、表示時刻 `2026-10-01 08:13:35.328807`、モジュール `access_compat:error`、接続元 `127.0.0.1`、`AH01797: client denied by server configuration`、対象 `/home/firststar/public_html/group/control/site/candy_recommendation_environment_check.php` の一致する記録がある。
- 今回の403はApacheのホスト/IPアクセス制御による拒否と判明した。SSH認証エラーや診断PHPの認証判定ではない。前段で出力されたMIB警告とは区別する。診断PHPのガードは不適合時404を返す実装である。
- ローカル [control/.htaccess](../../../../control/.htaccess) には `order deny,allow` と `deny from all` がある。全3,606行からIPv4許可3,570件を抽出して照合し、127.0.0.1に一致するIPv4許可は0件。その他の許可はGoogleのホスト名3件である。ただし、本番のどの設定ファイル・行が拒否を決定したかは未確認で、ローカルと本番の設定一致も確認していない。
- ローカル `control/site/.htaccess` は存在しない。本番同パスの有無・内容は未確認。
- [実行用スクリプト](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-EnvironmentCheck.ps1) の現在のSHA-256は `08286bd151c89d0fe095212312a95c4ffb97783300c24bc70dac871540e46662` で前記録と一致した。

### 判断と対応案

- 既存のアクセス制限を踏まえずループバックHTTPが通る前提にした確認方法に不備があった。同じスクリプトをそのまま再実行しない。
- 対応案は、本番 `/firststar/public_html/group/control/site/.htaccess` を事前確認・保全し、診断ファイル `candy_recommendation_environment_check.php` 1件だけに127.0.0.1からの一時許可を設定し、診断後に元の状態へ復元する方式。既存の一般アクセス制限、他の画面、DB、画像、本体機能を変更対象にしない。
- これは従来承諾された「一時診断PHP 1件の設置・実行・削除」から、本番アクセス設定の操作へ範囲が増える。具体的な操作・復元を含む追加承諾前には実装・設置・設定変更を実行しない。
- Apache公式 [mod_access_compat](https://httpd.apache.org/docs/2.4/mod/mod_access_compat.html) のFiles単位の制御、Order/Allow/Deny、設定継承の仕様を確認した。採用する場合は、本番の既存設定と継承、診断対象限定、外部拒否維持、構文検証、復元・途中停止時の回収を検証してから行う。未確認のまま設定断片を貼り付けさせない。
- このターンでは提供ログとローカル資料の読取り、履歴追加のみ。スクリプト・本番・アクセス設定・DB・Git状態は変更していない。
- 記録前にCandyのmainがローカル/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチが双方NOT_PRESENT、同日最大連番5であることを確認した。

## 現在

- Remaining Work: 第3段階の本番Web実行環境確認がアクセス制御で停止中。Web側PHPの設定・画像処理機能・保存先の確認、既存12名の移行、公開切替は未完了。403解消を機能完成や本番反映完了と扱わない。
- Next Action: 診断ファイル1件に限った内部アクセスの一時許可と復元についてユーザーの承諾を待つ。承諾後に本番設定の必要範囲を確認し、安全な設定・復元方法を確定する。承諾前にアクセス制限を変更・迂回しない。
