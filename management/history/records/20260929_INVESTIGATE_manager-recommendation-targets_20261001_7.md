# 店長おすすめ診断の一時アクセス許可・復元処理追加

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 7
- Status: Waiting for Response

## 記録

### 承諾と実施範囲

- 前記録の「本番 `control/site/.htaccess` に診断PHP 1件だけの内部アクセス一時許可を追加し、確認後に復元する」提案に対し、ユーザーから「実行して早く終わらせろ」と承諾を受けた。
- [Invoke-EnvironmentCheck.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-EnvironmentCheck.ps1) と [Test-EnvironmentCheck.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Test-EnvironmentCheck.ps1) のみ修正した。診断PHPテンプレートは変更していない。HP・管理画面本体・DB・Git状態は変更対象外。

### 実装

- Runner version 3に更新。サーバーの `site/.htaccess` が存在する場合は、リンクも含め、何も作成する前に `EXISTING_ACCESS_FILE_PRESERVED` で停止する。既存設定の自動上書き・追記は採用しない。存在しない場合だけ一時ファイルを作り、終了時は削除して元の不存在状態へ戻す。
- 一時設定は `<Files "candy_recommendation_environment_check.php">` 内に `Order Deny,Allow`、`Deny from all`、`Allow from 127.0.0.1` だけを指定。他の画面のアクセス制限を変更するディレクティブは追加しない。診断PHP側の一時トークン・ループバック限定・有効期限も維持する。
- 本番 `/firststar/public_html/group/control/site/.candy_recommendation_environment_check.access.tmp` に完成した設定を排他作成・ハッシュ検証し、PHP `link()` で `.htaccess` を一括設置する。これは部分書込み状態の `.htaccess` をApacheに読ませないための一時作業ファイルで、認証情報を含まない。作業ファイルが既存の場合も停止する。
- `link()` は既存先を上書きしないため、事前確認後に別プロセスが `.htaccess` を作成した場合も保全して停止する。上書きrenameへの代替処理はない。PHP関数の使用可否も設置前に確認する。
- 終了処理ではアクセス設定、作業ファイル、診断PHPを個別に確認・削除する。親パス、ファイル種別、デバイス、inode、内容ハッシュが一致した自分の作成物だけを削除する。一つの削除が失敗しても他の後処理を試み、不確かな対象は残して明示する。
- 成功判定にはHTTP 200、正しい診断JSON、SSH終了コード0に加え、`CANDY_DIAGNOSTIC_ACCESS_RESTORED`、`CANDY_DIAGNOSTIC_ACCESS_STAGE_REMOVED`、`CANDY_DIAGNOSTIC_REMOVED` を必須とした。復元・削除失敗や確認不足は成功扱いせず再実行禁止を表示する。

### 検証結果と未確認事項

- Windows PowerShell 5.1 / ローカルPHP 8.3.32で `RESULT=PASS TESTS=89 LOCAL_ONLY MOCK_HTTP NO_SSH_CONNECTION`。生成PHPの構文検証、実NTFSファイルのハードリンク生成・削除、前回の認証/HTTP/異常応答テストを含む。
- HTTP 403/503・通信失敗後の全一時ファイル削除、既存設定の完全保全、設置時の競合、作業ファイル部分書込み、設定の変更/置換/削除失敗、ディレクトリ変更、復元証拠欠落時の成功拒否を検証した。テスト用一時フォルダーは限定パス確認後に削除済み。
- 模擬HTTPで一時設定の全バイトを照合したが、ローカルApache実動作試験は行っていない。本番Apacheによる適用、PHP `link()` の実動作、Web診断JSON取得、実際の復元は未確認。
- パスワード認証が必要で、本人の入力操作を代行できない。既知のREAD-ONLY専用鍵もローカルに存在しない。したがって本ターンは本番 `-Run` を実行していない。ユーザーのPowerShellで修正版を実行し、本人がパスワードを入力する手順を案内する。
- 強制終了やSSH切断時の自動復元は保証できない。実行中のウィンドウを閉じない。復元未確認の場合は再実行せず出力を確認し、残置物だけを特定して回収する。診断PHPの有効期限をファイル削除の代わりに扱わない。
- 実行用PS1 SHA-256: `7799c3a2109dfef908e83e1595364fb60540d2792f09a26ea7bef052076bd29d`。
- テスト用PS1 SHA-256: `c4e2b9fec9177e3c1f83b82f7eba704912fea869f83719a02d09b0a4cea4f5ea`。
- 診断PHP SHA-256（変更なし）: `ce21904875c5dfc9e671f1ba4a822aeb60d3fd4efe0ca6b66024ae1a06b8df59`。
- 記録前照合でCandyのmainはローカル/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチは双方NOT_PRESENT。同日最大連番6を確認し連番7を使用した。

## 現在

- Remaining Work: 第3段階の本番Web環境確認と一時設定の復元確認。本番実行結果、Web側の画像処理機能・アップロード上限・保存先権限はまだ未確認。既存12名の移行・公開切替も未完了。
- Next Action: ユーザーがローカルPowerShellで修正版 `Invoke-EnvironmentCheck.ps1 -Run` を実行し、本人がSSHパスワードを入力する。出力の診断JSONと3つの後処理完了マーカーを確認する。既存設定の検出・HTTP失敗・復元未確認の場合はその出力に基づき対応し、同じ処理を闇雲に再実行しない。
