# 店長おすすめ Web環境確認スクリプトのPHP方式への修正とローカル検証

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 5
- Status: Waiting for Response

## 記録

### 確認済み事実と指示

- ユーザーの本番SSH出力で `base64_decode=1`、`hash_file=1`、`curl_init=1`、`disabled_functions=` を確認した。PHPの代替機能の存在確認であり、Web側の動作確認ではない。
- ユーザーから「確認用スクリプトを修正して進めて」と明示的な指示を受けた。対象は確認用スクリプトとそのテストで、HP・管理画面本体・DBの変更は含まない。

### 修正内容

- controlの既存調査フォルダー内 [Invoke-EnvironmentCheck.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-EnvironmentCheck.ps1) と [Test-EnvironmentCheck.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Test-EnvironmentCheck.ps1) を修正した。
- サーバー側の `base64`、`sha256sum`、`curl`、シェル補助コマンドへの依存を廃し、SSH標準入力から `/usr/bin/php -q` でPHP処理を実行する方式に変更した。サーバーに保存するのは、従前と同じ承諾済みの `control/site/candy_recommendation_environment_check.php` 1件のみ。PHP実行用の別ファイルは設置しない。
- 事前確認・作成・HTTP・削除の段階を表示し、SSH終了コードを常に判定時に提示する。未作成、作成後の削除済み、削除未確認を分けた。SSHの標準エラーはパスワード入力表示のためコンソールに残す。
- PHPの関数・定数、実行アカウント、ホスト、実体パス、既存ファイル、転送内容のハッシュを検証してから排他的に作成する。削除時は親ディレクトリ、通常ファイル種別、作成時のデバイス・inode、内容ハッシュを照合し、不一致・部分書込み・削除失敗は保存して停止する。既存ファイルは上書き・削除しない。
- HTTPは127.0.0.1だけに接続し、Hostを既存の対象サイトへ固定する。プロキシ・リダイレクトを無効化、接続10秒・全体25秒、応答64KiB上限とした。HTTP失敗ページやPHPソースが返った場合は本文を出力せず、正常な診断JSONだけを返す。
- Web診断テンプレートは変更していない。外部アクセス拒否、毎回の一時認証、10分失効、設定ファイルを実行せず読取る方式、画像書込み・DB接続を行わない範囲は維持した。

### 検証結果と限界

- Windows PowerShell 5.1で `Test-EnvironmentCheck.ps1` を実行し、最終結果は `RESULT=PASS TESTS=62 LOCAL_ONLY MOCK_HTTP NO_SSH_CONNECTION`。両PowerShellファイルの構文エラー0件、生成PHPと変更していない診断テンプレートのローカルPHP構文検証も成功した。実行用スクリプトの `-Run` なし起動が接続しないことも確認した。
- 既存ファイル保護、未検出機能・異なるアカウント/ホスト/パス・ハッシュ不一致による作成前停止、HTTP失敗・異常JSON・巨大応答時の削除、ファイル改変・inode/リンク置換の模擬条件・部分書込み・削除失敗時の保存、SSH失敗時の終了コード表示、誤った成功判定の拒否を検証した。
- ローカルWindows PHPでは、長い標準入力を直接PHPコードとして解析すると途中で切れる挙動を観測した。一方、同じ入力を `stream_get_contents(STDIN)` で全量読み取れることは確認できた。このため、テスト用ハーネスで全量のハッシュ一致を検証した後、隔離フォルダー・模擬通信でPHP処理を実行している。短いCGI標準入力とBOM/改行処理も確認した。これは本番Linux/PHP 7.2での長い入力・実SSH・実HTTPの検証を代替しない。
- 本ターンは実行用スクリプトの本番 `-Run` を行っていない。実際のパスワード入力表示、Apacheによる診断実行、本番での一時ファイル作成・削除、Web設定取得は未検証。SSH切断やプロセス強制終了による残置は自動削除を保証せず、削除未確認なら再実行せず確認する。
- テスト用一時ディレクトリは、絶対パスと固有名を検証してテスト後に削除した。HP・管理画面本体・本番DB・画像・公開設定は未変更。Gitの状態変更・Commit・Pushも行っていない。

### 成果物識別

- 実行用PS1 SHA-256: `08286bd151c89d0fe095212312a95c4ffb97783300c24bc70dac871540e46662`。
- テスト用PS1 SHA-256: `d93d6a20a8bf18531b9379c182f7ecbb102cb1e575a164ed524eac14883d02d7`。
- Web診断テンプレート SHA-256（変更なし）: `ce21904875c5dfc9e671f1ba4a822aeb60d3fd4efe0ca6b66024ae1a06b8df59`。
- 記録前照合でCandyのmainはローカル/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`。managementブランチは双方NOT_PRESENT。同日最大連番4を確認して連番5を使用した。

## 現在

- Remaining Work: 第3段階の本番Web実行環境確認は未完了。修正版の本番実行結果と一時ファイル削除の確認を待つ。画像・DB初期移行・公開切替は未実施のまま。
- Next Action: ユーザーがSSH画面で `exit` し、ローカルのPowerShellに戻って修正版 `Invoke-EnvironmentCheck.ps1 -Run` を実行し、パスワードを本人が入力する。表示結果を受領して、環境情報と `CANDY_DIAGNOSTIC_REMOVED` を確認する。エラーなら再実行せず提示結果から次の対応を決める。
