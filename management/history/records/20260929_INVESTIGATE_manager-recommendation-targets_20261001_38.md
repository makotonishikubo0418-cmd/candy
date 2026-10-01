# 追加修正を含む4ファイル版アップコマンド準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 38
- Status: Verification Pending

## 記録

### 決定

- ユーザーから「コマンドも同時に出せよ」と指示を受領。修正とアップ準備を分けて案内せず、本案件の追加修正完了時はアップコマンドもまとめて案内する。
- 対象はこれまでの一覧修正と選択状態の角丸表示を含む管理側4ファイルのみ。HP・DB・機能ON/OFF・アクセス設定は変更しない。前回3ファイル版の実行結果は未受領のため、既知の旧版と3ファイル版のどちらからも適用可能にする。

### 対応・結果

- 既存の固定3ファイルパッケージを変更せず、4ファイル版 `admin_list_badges_20261001.json` と `Invoke-AdminListBadgeDeployment.ps1`、専用実行コード・ビルド・試験を追加した。
- リリース識別子 `worktree-20261001-list-badges-v2`、合計16,993 bytes、パッケージSHA-256 `be0e5b522410ae5e34f381369b8496b2a38d909b31ed61c565fdef394e915922`。後回し指示のGit保存は行わず、未Commitと明示した作業ツリー版を固定する。
- アップ順はCSS、JavaScript、一覧描画PHP、入口PHP。キャッシュ識別子を持つ入口を最後に置換する。既知3バージョン（旧版・3ファイル版・今回版）のSHA-256以外は上書きせず、非変更対象9ファイルも照合する。
- サーバー/アカウント/SSHホスト鍵を確認し、変更前4ファイルを公開領域外の `/firststar/candy_admin_badge_日時_乱数` にバックアップ。PHP構文検査、置換後照合、途中失敗時の自動復元、同じコマンドの `-RestoreBackupName` による手動復元を用意。
- ローカル試験86項目で、旧版と3ファイル版からの適用、正確なバックアップ・復元、自動復元、再実行、基準外の変更拒否・後続編集保護を確認した。
- Windows PowerShellでアップ・復元の両モードの送信コード全体のPHP構文検査、バイト往復検査が成功。ビルドの再照合も一致した。作業用一時試験ディレクトリのみ削除し、本番接続・DB操作・Git状態変更は行っていない。
- HISTORY第9章: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大37から38を採番。

## 現在

- Remaining Work: アップ実行結果・バックアップ先の受領、本番一覧の表示確認。以前からの残検証・Git保存/Push保留は保持。
- Next Action: 今回の `Invoke-AdminListBadgeDeployment.ps1 -Run` のみ案内する。成功表示は `CANDY_BADGE_DEPLOYMENT_OK=4`。未実行を本番反映済みとは扱わない。
