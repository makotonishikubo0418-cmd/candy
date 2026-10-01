# Git保存を後回しにする明示指示とHPアップ手順の準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 32
- Status: Waiting for Response

## 記録

### 決定

- 「Gitへの保存だけを後回しにして、HPのアップは今進める」という確認に、ユーザーが「そう」と回答した。進捗31のコミット許可待ちは解除する。ユーザー指示を優先し、今回は未コミットの対象5ファイルをSHA256で固定して、既存のSSH方式で配置する。コミット・Push・ブランチ操作は行わない。
- 管理画面の一覧表示・保存確認完了は進捗31のユーザー報告を引き継ぐ。HPの配置先は `/firststar/public_html/group/candy`。管理プログラム・DB・保護対象の `index.php` と `.htaccess` は変更対象外。

### 対応

- Controlの既存案件調査フォルダーに `Invoke-HpDeployment.ps1`、`deploy_hp_recommendation.php`、`test_hp_deployment.php`、`Test-HpDeployment.ps1` を追加した。既存の固定済み転送ヘルパーは変更せず再利用する。
- 対象5ファイル: `includefile/candy_recommendation_config.php`、`includefile/candy_recommendation.php`、`includefile/dataset_base.php`、`source/index.html`、`includefile/dataset_index.php`。
- 公開する各ファイルのハッシュと既存3ファイルの基準ハッシュを固定。未知の既存変更、リンク、別所有者、並行更新は拒否する。管理側ON設定のハッシュも配置前に読み取り照合する。
- Web外の `/firststar/candy_hp_<UTC日時>_<乱数>` に原本を保存し、サーバーPHPで5回の構文検査（設定OFF/ONを含む）を通過してから配置する。設定OFFで5ファイルを配置・照合し、最後に設定だけONへ原子的に切り替える。保護対象2ファイルの不変性も確認する。
- 配置途中の失敗は原本から復元する。後続編集を検出した場合は上書きせず要復旧と出力する。新規ヘルパーは削除せずOFFで保持する。明示指定バックアップからの復元と、設定だけをOFFにする緊急操作を用意した。
- アップ用パッケージSHA256は `50e9c659f83865d0c84a27282e9690d1b7f4054ea71ff4a9f6836a74c152cdba`。PowerShellのSHA256は `f822d7d06d54cdd069acf639e25e64b8fe62eaab374d099742018e32dbc90637`。PHP転送処理は `10ec53f3d705d3d1514799d7147f0acdd6b0b517e168076742d698ebfc610b10`。
- ローカルHP設定ファイルはOFFのまま保存している。転送パッケージ内で唯一の `enabled` をONへ変換して最後に配置する。後日のGit保存・公開では、本番ONとの違いを忘れず扱う。現在のローカルOFFを無確認でPushして本番へ戻さない。

### 確認済み結果と未確認事項

- ローカルPHP8.3の転送・復元試験98項目が通過。配置、再実行、緊急OFF、再ON、原本復元、6箇所の失敗注入、構文失敗時の無変更、未知変更・並行変更・後続編集の保護、対象外パス・内容改変の拒否を確認。
- PowerShell5.1で実行し、アップ・OFF・復元の3モードについて、組立後PHP全体の構文検査と転送データの復号一致が通過。テスト用一時領域は検証した対象パスだけ削除済み。
- 既存の機能契約287項目と読取範囲49項目も再実行して通過。これらは本番DB・本番HTTP・本番性能の証明ではない。
- AIは本番に接続していない。SSHパスワード入力はユーザー操作。実行コマンドを案内する段階であり、配置成功・実ページ反映は未確認。
- HISTORY第9章照合: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementは双方NOT_PRESENT。同日最大31から32を採番。

## 現在

- Remaining Work: ユーザーによるHPアップ実行と出力受領、公開HPの表示・PC/SP画像・掲載順・リンク・応答の確認、共通公開入口確認。Git保存・Pushはユーザー指示で後回し。既存の実DB同時更新等の未検証事項は保持する。
- Next Action: `Invoke-HpDeployment.ps1 -Run` の実行結果を受領する。成功条件はSSH終了値0と `CANDY_HP_DEPLOYMENT_OK=5`、今回のバックアップ先。成功出力だけでは公開画面確認まで完了扱いにしない。
