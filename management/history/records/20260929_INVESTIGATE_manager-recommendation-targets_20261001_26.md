# 管理側修正6ファイルのCommitとOFF限定転送手順の準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 26
- Status: Waiting for Response

## 記録

### 決定

- ユーザーは「ではアップするので指示しろ」に続き、Controlの修正版6ファイルに限ったgit add・git commitを許可した。GitHub送信・DB操作・機能の再有効化は許可対象に含めない。
- 今回は管理側OFFのまま6ファイルを配置する準備。実DB速度・保存・同時更新等の未検証事項を解消したとは扱わず、有効化やHP切替は行わない。

### 対応・結果

- Control mainに `e2b493bcf9380a2a73c0701c0db05eb15c1d79f6` を作成。親は `126a99b52291c73f380c800651f337dd5614d552`。対象は `site/candy_recommendation_profile.inc.php`、`site/candy_recommendation_save.php`、`site/candy_recommendation_service.php`、`site/candy_recommendation_view.php`、`site/candy_recommendations.php`、`site/js/candy_recommendation.js` の6ファイルのみ。GitHubへのPushは未実施。既存upstream参照との比較はahead 4 / behind 0で、未追跡の調査資料・既存の別案件差分は含めていない。
- ローカル再検証: 契約287、保存模擬40、読取分離49、実コントローラー境界10ケース31、フォーム構造10、UI模擬17、計434項目と5 PHPの構文検査を通過。実DB・本番動作検証ではない。
- `C:\Codex\FSG\control\codex\project_management\investigation\candy_manager_recommendation` に修正専用の `build_admin_repair.py`、`admin_repair_20261001.json`、`deploy_admin_repair.php`、`Invoke-AdminRepairDeployment.ps1`、`test_admin_repair.php`、`Test-AdminRepairDeployment.ps1` を作成した。これらの補助資料は今回の6ファイルCommitには含めていない。既存の旧転送・有効化パッケージは変更していない。
- 新パッケージはCommitから取得した6ファイル37,689 bytes。JSON全体53,164 bytes、SHA256 `98fb417b9216f6978e3e612c7ee4708fc1fcc66b057f746514ea2c72eeba5bc4`。作業ツリーの将来の変更を転送しない。PowerShellがパッケージ・転送処理・共通ファイル操作部品のSHA256を固定照合する。
- 転送先は `/firststar/public_html/group/control`。OFF設定を含む既存7ファイルを読取照合し、6対象の現行値が既知の旧版または同一修正版であることを確認する。不一致、ON化、パス/所有者異常時は停止。設定・既存フック7ファイル・HP・DBを書き換えない。
- 転送時は `/firststar/candy_admin_repair_<UTC日時>_<識別子>` に6ファイルの変更前原本と復元情報を保存し、同一領域に全修正版を準備する。本番PHPの構文検査に合格してから個別に原子的置換し、全6ファイルをSHA256照合する。共通の転送ロックで旧有効化コマンド等との同時実行を防ぎ、途中障害時は復元を試み、失敗時には復元要確認を明示する。後から変更された別内容を復元で上書きしない。
- 専用転送試験72項目をローカル一時領域で通過。配置、バックアップ原本、OFF維持、再実行、復元、未知の旧版拒否、欠損・範囲外・破損拒否、構文検査失敗時の配置前停止、途中障害の自動復元、後続編集保持を確認した。一時試験領域のみ削除済み。
- PowerShell 5.1で接続なし検証を実施。組立済み転送PHPを一時ファイルとして全文構文検査し、実際のBase64復号結果のSHA256一致も確認。Windows PHP 8.3の標準入力直接lintでは長い入力の解析エラーが発生したため、全文を保存したファイルのlintと復号照合を証拠とした。サーバーのPHP 7.2による事前lintはユーザー実行時に確認する。
- ユーザーに渡す実行対象は `Invoke-AdminRepairDeployment.ps1 -Run`。成功目印は `CANDY_REPAIR_DEPLOYMENT_OK=6` と `CANDY_REPAIR_ADMIN_DISABLED=1; HP_NOT_CHANGED=1; DB_NOT_ACCESSED=1`。エラー時は再実行せず出力・バックアップ場所を確認する。手動復元は同スクリプトの `-Run -RestoreBackupName <今回出力されたバックアップの末尾名>` を使用し、必要時に対象を確認して案内する。
- この作業では本番へ接続・配置していない。HISTORY第9章照合: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大25を確認して26を採番。

## 現在

- Remaining Work: ユーザーによる修正版6ファイルのOFF限定配置と結果確認。PHP 7.2・MySQL 5.6相当での実DB速度・保存・同時更新等の検証、承認後の再有効化と本番画面確認。HP切替は未実施。
- Next Action: ユーザーに新しいPowerShellコマンドを提示し、実行結果を受け取る。OFFのまま既存管理画面が従来速度で開くことを確認する。今回の配置だけで障害修正の本番完了とは報告しない。
