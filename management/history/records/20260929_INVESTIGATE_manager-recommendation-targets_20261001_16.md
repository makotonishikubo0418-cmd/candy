# 管理画面13ファイルの承諾済みCommitと転送手順準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 16
- Status: Waiting for Response

## 記録

### 承諾とCommit

- [前記録15](20260929_INVESTIGATE_manager-recommendation-targets_20261001_15.md)の対象13ファイルのstage・ローカルCommitについて、ユーザーから「はい承諾」を受領した。同じ許可を再確認せず実行した。対象は前記録の13ファイルだけで、Push、ブランチ変更、HP変更、DB操作を含めない。
- Controlの既存ステージが空であること、対象の差分、実行時ログが追跡対象でないことを確認し、明示した13パスだけをstageした。ステージとレビュー済みファイルの一致、差分検査を確認後、mainにCommit `a379401c3b8dfb2dd597ed8f12ddd49af77bd06e`（`Add disabled CANDY manager recommendation administration`）を作成した。親Commitは `4d2f74444ab2ecf06fea2712fe57751072dfe92b`。
- 13ファイル・468行追加・1行削除。3つの既存ファイルへの部品include、10個の専用ファイルが対象。`enabled=false`を維持した。既存の管理書変更と調査資料・ログはステージせず保持した。Pushしておらず、既存origin/mainに対してahead1。今回のGitHub公開は未実施である。

### 転送物と手順

- [build_admin_package.py](../../../../control/codex/project_management/investigation/candy_manager_recommendation/build_admin_package.py)で上記固定CommitのGit blobから13ファイル・150,755 bytesの [admin_upload_20261001.json](../../../../control/codex/project_management/investigation/candy_manager_recommendation/admin_upload_20261001.json) を生成した。パッケージSHA256は `c9c68e6cd2f01335633e47b82c2f2d26fa32e2ad8c77ff0acfd4f476d1061ad3`。生成元Commit・親・対象一覧・データ・SHA256の再現一致を検証した。作業ツリー全体や未コミットの別ファイルを転送しない。
- [Invoke-AdminDeployment.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-AdminDeployment.ps1) と [deploy_admin_files.php](../../../../control/codex/project_management/investigation/candy_manager_recommendation/deploy_admin_files.php) を準備した。ユーザーPCから既存SSHパスワード認証でfirststar@firststar.kir.jpへ接続し、既知RSA指紋・hostname・アカウントを確認する。パスワードを保存しない。PHP処理を標準入力で渡し、実行用PHPをWeb領域へ置かない。
- 配置先はSSH経路の `/firststar/public_html/group/control`。全13ファイルのパッケージ照合と全配置先確認を先に行う。既存3ファイルが親Commitの内容（CRLF/LFのみ正規化）と異なる、既存の専用ファイルが転送版と異なる、リンクや不正な保存先がある場合は対象ファイルを書き換えず停止する。
- 上書き前に、公開領域外の `/firststar/candy_control_backup_日時_乱数/`（0700）へ原本をバイト単位でバックアップし、復旧マニフェストを保存する。既存ファイルの所有者・グループ・権限を保持できることを確認し、専用10ファイルを先に、既存画面の3ファイルを最後に配置する。配置は同一ファイルシステム上のrenameを使い、最後に全13件のSHA256を照合する。アクセス設定・HP・DB・写真は変更しない。
- 通常の処理例外では、元からあったファイルを照合付きで戻す。後から変更された内容は上書きしない。新規の無効状態の専用ファイルとバックアップは保持する。強制終了・通信断などで結果不明の場合は自動復旧を保証せず、再実行せず出力を確認する。
- 必要時の復旧入口は同じPowerShellの `-Run -RestoreBackupName <出力されたバックアップ名>`。通常転送では指定しない。専用パッケージ・バックアップ・全対象を先に照合し、元のファイルだけを復旧、新規の無効ファイルは保持する。バックアップや旧ファイルを自動削除しない。

### 検証と未確認事項

- PHP構文検査11ファイル、JavaScript構文検査、既存287項目の純粋検証、21項目の保存模擬検証が成功した。これはローカルPHP 8.3の結果で、実DB操作はない。
- [test_admin_deployment.php](../../../../control/codex/project_management/investigation/candy_manager_recommendation/test_admin_deployment.php) の51項目成功。13件の転送・原本保全・CRLF原本復元・再実行時の同一判定・異なる本番版の拒否・破損パッケージ・対象外パス・有効化拒否・親フォルダー不足・途中例外の自動復旧・後からの編集の保護・新規無効ファイル保持を確認した。無効時のメニュー/プロフィール部品が何も表示せず、DBサービスもロードしないことを確認した。作成したテスト専用一時ディレクトリだけを終了時に削除した。
- Windows PowerShell 5.1で `-Run` なしの検証が成功（13件・150,755 bytes・接続なし）。固定Git内容との再現照合と、コミット後の対象13ファイルに差分がないことも確認した。転送スクリプト・検証・生成物はローカル調査資料であり、今回の13ファイルCommitには含めていない。
- 本番転送はまだ実行していない。本番旧3ファイルとの一致、実所有者・グループ保持、バックアップ/置換の実結果、Web側の動作は未確認。今回成功時に確認できるのは無効状態でのファイル配置であり、新メニューはまだ表示されない。利用開始・DB準備フラグ変更・実DB結合・HP公開切替は別工程として残る。
- 記録前のCandy Local/GitHub mainはともに `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`。managementは双方NOT_PRESENT、同日最大連番15を確認し16を使用した。

## 現在

- Remaining Work: ユーザーによる管理画面13ファイルの転送と実行結果照合。その後の管理画面有効化・実DB/画面動作確認、HP切替。Control Commitは未Push。
- Next Action: ユーザーのPowerShellでInvoke-AdminDeployment.ps1に-Runを指定して1回実行し、出力を提示してもらう。成功期待値はCANDY_SSH_EXIT_CODE=0、CANDY_ADMIN_DEPLOYMENT_OK=13、CANDY_ADMIN_DISABLED=1。エラー時は再実行せず出力とバックアップパスを保持する。
