# おすすめ一覧3ファイルのアップ・復元コマンド準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 36
- Status: Verification Pending

## 記録

### 決定

- ユーザーから「アップするコマンドを出せ」と指示を受領。進捗35で修正した管理一覧3ファイルのみを、ユーザーがPowerShellで実行する手順として準備する。HP、DB、管理機能ON/OFF設定、アクセス設定は変更しない。
- Git保存・Pushは後回しの指示を継続。未Commitの差分は作業ツリーリリース `worktree-20261001-list-v1` と明記し、Commit済みと偽らず、実データ・パッケージ・実行コードのSHA-256を固定する。

### 対応

- Controlの同案件調査フォルダーに `build_admin_list.py`、`admin_list_20261001.json`、`deploy_admin_list.php`、`Invoke-AdminListDeployment.ps1` とPHP/PowerShellテストを追加。以前の管理・HPアップスクリプトは変更していない。
- 転送対象はCSS、一覧描画、一覧入口の3ファイル（合計12,148 bytes）のみ。既存の配置済み固定パッケージ・速度修正版・ON設定パッケージを基準に、差し替え対象3ファイルと非変更対象10ファイルを照合する。差異があればアプリ変更前に停止する。
- サーバー名・実行アカウント・既知SSHホスト鍵を検証し、SSH標準入力から実行する。公開ディレクトリに作業用PHPを設置しない。PHPアプリを実行せず、DB接続なし。
- 変更前3ファイルを `/firststar/candy_admin_list_日時_乱数` にバックアップし、サーバーPHPで構文検査後に同一ファイルシステム内で置換する。途中例外時は自動復元を試み、復元不能は明示する。後日の別変更がある場合は上書きしない。
- 手動復元は同じPSスクリプトに `-Run -RestoreBackupName` と今回出力されたバックアップのディレクトリ名を指定する。復元対象も同じ3ファイルに限定する。

### 結果

- 固定パッケージSHA-256: `3535a1ea1bca47501950e5fc148bf7b3ca6baf51435f39f066051521ead5b3c7`。
- ローカル一時ディレクトリで配置、再実行、変更拒否、バックアップ、手動復元、途中失敗からの自動復元、後続編集保護の65項目が成功。
- 実際のWindows PowerShellで、アップ・復元両モードのSSH送信文字列全体のPHP構文検査とバイト往復検査が成功。いずれも `-Run` なしで本番には接続していない。
- 元の3ファイル差分は維持。今回はアプリの追加修正・DB操作・Git状態変更なし。作業用一時テストディレクトリのみ検証後に削除。
- HISTORY第9章: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大35から36を採番。

## 現在

- Remaining Work: ユーザーによるアップ実行結果（`CANDY_LIST_DEPLOYMENT_OK=3`、バックアップ先）の受領と、本番一覧で3変更の確認。従来の残検証・Git保存/Push保留は保持。
- Next Action: `Invoke-AdminListDeployment.ps1 -Run` をユーザーに案内し、実行結果を確認する。未実行段階を本番反映済みとは扱わない。
