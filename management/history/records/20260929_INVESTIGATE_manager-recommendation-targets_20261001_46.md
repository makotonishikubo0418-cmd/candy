# 写真欄へ現在HPと同じ具体的な画像寸法を表示

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 46
- Status: Verification Pending

## 記録

### 決定・確認済み事実

- ユーザーはプロフィールのおすすめ写真欄について、現在HPと同じ画像サイズを具体的に記載し、「各辺○○以下」という案内を使わないよう指示した。対象は案内文であり、登録可否・画像加工・保存処理の変更は含めない。
- 本番 `https://www.55810.com/` のHTTP 200応答からおすすめ欄のpicture/source/imgを照合し、掲載中24画像をHTTP取得して画像ヘッダーの縦横寸法を確認。PC12枚はすべて300×498px、SP12枚はすべて300×300px。画面上の可変表示幅ではなく、実画像ファイルの寸法である。DB接続なし。

### 対応・検証

- `control/site/candy_recommendation_view.php` のPC/SP写真カードに「画像サイズ：横300 × 縦498px」「画像サイズ：横300 × 縦300px」と「（現在のHPと同じ）」を追加。共通案内の各辺6000px/1600万画素表記を外し、JPEG/PNG・1枚5MB以下・既存画像保持の説明は維持。入力から該当サイズ説明へのaria-describedbyを追加。
- PHP構文、プロフィール描画54項目、8項目必須判定141項目、1ファイル配置/復元100項目を通過。PowerShell配置/復元2モードのpayload全体構文・バイト復元を検証済み。隔離ブラウザーで1280px/390px幅を確認し、両サイズ案内が表示され横はみ出しなし。実画像や本番管理画面での入力/保存は行っていない。
- アプリ変更は上記1ファイルの3か所に限定。既存v5パッケージとの比較により、許可した案内文・説明参照以外の差分を生成処理で拒否する。旧v5/v6パッケージは変更しない。画像の厳密寸法検査は追加せず、サーバー側の安全上限も維持。
- 調査フォルダーに `build_admin_image_size.py`、`admin_image_size_20261001.json`、`deploy_admin_image_size.php`、`Invoke-AdminImageSizeDeployment.ps1`、`Test-AdminImageSizeDeployment.ps1`、`test_admin_image_size.php` を追加し、既存 `test_profile_layout.php` に案内文検査を追加した。
- パッケージrelease=`worktree-20261001-image-size-v7`、対象1ファイル8138 bytes、JSON 11886 bytes、SHA256=`88b78f5814a0506030456a31e14a4153df87eaeef29f756d00661f97591f2066`。既存config/service/profile部品の照合、既知旧版LF/CRLF照合、サーバーPHP構文確認、私有領域バックアップ、失敗時自動復元、後続編集を上書きしない手動復元を備える。
- アップコマンドは `powershell -NoProfile -ExecutionPolicy Bypass -File "C:\Codex\FSG\control\codex\project_management\investigation\candy_manager_recommendation\Invoke-AdminImageSizeDeployment.ps1" -Run`。成功マーカーは `CANDY_IMAGE_SIZE_DEPLOYMENT_OK=1`。バックアップは `/firststar/candy_admin_image_size_日時_乱数`、手動復元は同コマンドへ `-RestoreBackupName` と実バックアップ名を追加する。メニュー・HP・DB・設定の変更なし。
- 恒久仕様を `control/docs/SCREEN_SPEC.md`、現在状況を `control/docs/PROJECT_STATUS.md` に反映。Git状態変更・Scope Manifest更新・固定Commit監査・SSH配置は未実施。
- 履歴採番前のCandy読取比較: local HEAD/mainとGitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementは双方なし、同日最大45から46を採番。

## 現在

- Remaining Work: ユーザーによるサイズ案内1ファイルの本番アップと表示確認。その他の案件全体の残検証・Git保存・監査は継続。
- Next Action: 具体的なPC/SP寸法とアップコマンドを同時に案内し、実行結果を受領する。
