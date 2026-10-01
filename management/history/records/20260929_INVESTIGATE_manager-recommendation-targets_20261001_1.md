# 店長おすすめ SSH確認結果の受領とWeb環境診断の実行準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 1
- Status: Waiting for Response

## 記録

### ユーザー提示結果で確認できたこと

- ユーザーがWindows PowerShellのSSHからパスワード認証で接続した。提示ログのホスト鍵指紋は管理書と一致し、`whoami=firststar`、`hostname=o4042s-134.kagoya.net`。PuTTYは不要だった。これはユーザーの実行結果であり、アシスタントによるSSH接続成功ではない。
- SSH上では `/home/firststar/public_html/group/control` および同表記の `upfiles/2` は存在しない。`/firststar/public_html/group/control` と `/firststar/public_html/group/upfiles/2` は存在し、いずれも755、所有者・グループはfirststar:kirusr。後者の `manager_recommendation` は未作成。Web側から見えるパスやPHPの書込可否までは確定しない。
- `/usr/bin/php` はPHP 7.2.12、CGI/FastCGI、読み込むphp.iniなし。GDは有効、JPEG/PNG対応、mysqli有効。file_uploads=On、upload_max_filesize=2M、post_max_size=8M、max_file_uploads=20、memory_limit=128M。これはコマンド実行環境の値であり、管理画面Web実行環境の値ではない。

### 今回の承諾と実行範囲

- ユーザーの「上記確認してください 承諾します」により、管理画面と同じWebディレクトリにおける画像保存先の参照・書込権限、画像処理機能、容量制限を確認する。直前に提示した `/firststar/public_html/group/control/site/candy_recommendation_environment_check.php` 1件の一時設置・実行・削除を対象とする。
- DB操作、既存ファイル変更、画像や画像フォルダの作成・書込テスト、権限変更、HP切替、Git状態変更は含まない。サーバーのPHP設定も変更しない。

### ローカルで準備した実行資材

- 既存Control案件フォルダに [診断PHPテンプレート](../../../../control/codex/project_management/investigation/candy_manager_recommendation/candy_recommendation_environment_check.php)、[PowerShell実行用](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-EnvironmentCheck.ps1)、[ローカル検証用](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Test-EnvironmentCheck.ps1)を追加した。アプリ公開ディレクトリには追加していない。
- 診断PHPはDB・セッション・既存アプリ処理を読み込まず、必要なパス定義を実行せずに解析し、承諾済み2パス表記のcontrol・upfiles/2・専用画像フォルダの計6件だけを確認する。書込可否は権限照会であり、実保存の成功証明ではない。画像関数・拡張の有無とPHP設定を返し、実際の写真デコード・保存・DB連携・HP表示の試験はしない。
- 接続元は実際のREMOTE_ADDRがloopbackの場合に限定し、実行ごとの乱数認証と設置後10分の期限を併用する。転送ヘッダーでの送信元偽装を認証に使わない。認証値をURL・出力・ログへ記録せず、curlには標準入力で渡す。HTTPはサーバー内部127.0.0.1へ固定し、外部プロキシ・リダイレクトを使用しない。
- 実行用はSSHホスト鍵の指紋照合と厳密検証を行い、接続後にアカウント・ホスト・設置先実体を確認する。既存ファイルまたはシンボリックリンクがあれば変更せず停止する。設置は新規作成限定、転送後にSHA-256照合・本番PHP構文確認を行う。
- 正常時およびHTTP失敗時はEXIT処理で作成したファイルだけを削除し、不在確認後に `CANDY_DIAGNOSTIC_REMOVED` を出す。内容が途中で変更された場合は削除せず停止して報告する。強制終了・接続断等を含め、本番での削除完了は結果の受領前に確定しない。
- テンプレートSHA-256: `ce21904875c5dfc9e671f1ba4a822aeb60d3fd4efe0ca6b66024ae1a06b8df59`。実行用SHA-256: `e71bfd894cdc47b2c62a1daa086fee167875f607dc99e8545020b482e0d9756e`。検証用SHA-256: `60a8515b1215de0ae31868b9e3f867d74f174907c02048d4bd5293862aec8538`。実行時は認証ハッシュを差し込んだ送信内容を別途照合する。認証値そのものは保存しない。

### 検証と現在の実行状態

- ローカルPHP 8.3.32の構文確認PASS。Windows PowerShell 5.1で19件のローカル検証PASS。PHPへの模擬リクエストによる外部接続元・無認証・誤認証・POST・期限切れの拒否、設定ファイルを実行しないこと、許可外パス拒否、およびシェル側の既存ファイル保護、正常・HTTP失敗時の削除、途中変更時の保護を確認した。PowerShellから送信されるBOM/CRLFを取り除く処理も検証した。
- これらはローカルのPHP模擬入力とHTTP代替関数を使った検証であり、本番Web応答・SSHパスワード入力・Apache設定を検証したものではない。テスト専用一時ディレクトリは範囲を確認して削除した。既存の利用者データは削除していない。
- アシスタントは本番接続・一時ファイル設置・HTTP診断・本番ファイル削除をまだ実行していない。既存のユーザーSSHセッションをツールから操作できず、接続用パスワードも扱わないため、ユーザーによるPowerShell実行と結果提供が必要。
- 本日の初回Git確認: 対象GitリポジトリはCandyとControlの2件、双方ローカルブランチはmainのみで未コミット変更あり。Candy mainはローカル/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、Control mainは双方 `4d2f74444ab2ecf06fea2712fe57751072dfe92b`、ahead/behindはいずれも0/0。originはそれぞれ `https://github.com/makotonishikubo0418-cmd/candy.git` と `https://github.com/makotonishikubo0418-cmd/fsg_control.git`。CandyのGitHub側だけにfeature/member-loyalty-mypage `933bb909c54f6fdb688dea69a2b9e505606bd0c0` が存在する。現在のmainのまま既存変更を保持し、新規3資材と本記録だけを追加した。
- 記録直前のCandy読取照合でもmainは同じSHAで一致し、managementブランチは双方NOT_PRESENT。当日の同案件記録は未作成だったため連番1を使用した。

## 現在

- Remaining Work: 第3段階のWeb実行環境確認は未完了。ユーザーの実行結果からWeb側の保存先・画像機能・容量制限と一時ファイル削除を確認する。実写真のアップロード・既存12名の移行・DB解除処理の検証・公開切替等は引き続き未実施で、今回の承諾には含めない。
- Next Action: ユーザーが現在のSSH画面をexitで抜け、Windows PowerShellから実行用スクリプトに `-Run` を付けて起動し、サーバーパスワードを入力する。認証値を含めず表示結果を提供してもらう。`CANDY_DIAGNOSTIC_REMOVED` が出ない場合は再実行せず、残置・途中失敗の状態を先に確認する。同一範囲の許可は再要求しない。
