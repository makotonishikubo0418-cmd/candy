# CANDY選択時のおすすめメニュー補完と7ファイル配置手順

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 43
- Status: Verification Pending

## 記録

### 決定

- ユーザーは「Candyが選択されている画面には必要 修正してコマンドを出せ」と指示。プロフィールのみでなくCANDY選択状態を持つ共通・独立メニューを対象とする。既存の権限・機能ON条件は維持し、他店舗には表示しない。
- 目的は左メニューのリンク欠落修正。DB、HP、認証設定、保存処理、CSS、過去のデザイン変更を今回の修正対象へ追加しない。

### 確認済み事実・対応

- `control/site/shopmaster3_candy.html`、`control/parts/side_menu.php`、`control/site/castmaster.html`、`control/site/schedule.html`、`control/site/totaldata.html`、`control/site/totaldatac.html`、`control/site/repeatdata.html` の7ファイルに、既存 `candy_recommendation_menu.inc.php` の読込を各1行追加した。既存の `shopmaster2.html` と共通メニューの先行分岐には既に読込があり、重複追加しない。
- Candyプロフィールは独立メニュー、共通メニューは2分岐、人物/出勤/集計画面も独立メニューである。`club/`・`bisiness/` の `SIDE_MENU` 呼出元は37テンプレート。テスト用/他店舗用のプロフィールテンプレートは変更しない。
- 現在ページの店舗IDを優先し、空ならセッション店舗へフォールバックする既存判定を利用。CANDY・rank=3・menu_code=301001・機能ONの場合だけリンクを1件出す。メニュー部品はDB接続もおすすめデータ取得も行わず、今回は部品自体も変更していない。
- 7ファイルの差分はそれぞれ1行追加/0行削除。配置前の旧内容へ読込行だけを足したことをパッケージ生成処理で照合した。

### 検証・配置準備

- `test_menu_coverage.php`: 290項目PASS。8メニューテンプレート、2 control_type、明示CANDY/他店・セッション補完・権限なし・OFF等9条件、リンク文字列/URL/件数、DB非接続、37呼出元を検査。実テンプレートのメニューを隔離実行し、DBや本番bootstrapは読まない。
- `test_admin_menu.php`: 142項目PASS。7ファイルの初回配置・再実行・手動復元、対象/guard改変拒否、構文失敗、7置換境界すべてでの失敗時復元、復元後の完全一致、LF/CRLF旧版照合を検査した。
- 7対象ファイルのPHP構文検査、PowerShell配置/復元の2モードの構文・payload復元検査、`build_admin_menu.py --check` が通過。ローカルPHPは8.3。本番PHP7.2での構文検査は配置時に行う。上記は非DB・ローカル検証であり、本番ログイン後の表示確認ではない。
- 調査フォルダー `control/codex/project_management/investigation/candy_manager_recommendation/` に `build_admin_menu.py`、`admin_menu_20261001.json`、`deploy_admin_menu.php`、`Invoke-AdminMenuDeployment.ps1`、`Test-AdminMenuDeployment.ps1`、上記2試験を作成。
- release=`worktree-20261001-menu-v6`、対象7ファイル計196151 bytes、JSON 264657 bytes。JSON SHA256=`4f9b012fe5a86cca9f770a5282e714c8e7763960d6c244925548bdd7b40463c6`。runner SHA256=`5844944164d53df399df2396421a71749143442efe4ada7bdaf65c4779712df6`。
- 配置コマンド: `powershell -NoProfile -ExecutionPolicy Bypass -File "C:\Codex\FSG\control\codex\project_management\investigation\candy_manager_recommendation\Invoke-AdminMenuDeployment.ps1" -Run`。成功表示は `CANDY_MENU_DEPLOYMENT_OK=7`。SSHパスワードはユーザー入力、保存しない。
- 全対象の既知旧版/新版ハッシュと既存config・menu部品・shopmaster2の3 guardを確認してから書込む。7ファイルともサーバーPHPで構文確認。未知の本番変更は書込前に停止する。基準は固定済み配置パッケージおよびControl Commit `e2b493bcf9380a2a73c0701c0db05eb15c1d79f6` であり、未配置の5独立メニューについて現本番との一致を取得確認したわけではない。
- 配置前バックアップは `/firststar/candy_admin_menu_日時_乱数`。途中失敗は自動復元。手動復元は同じコマンドに `-RestoreBackupName candy_admin_menu_日時_乱数` を追加。バックアップ名は実行結果の実値を使う。復元時は配置後の他変更を上書きしない。
- 今回はメニューのみで旧デザイン改修を累積配置しない。配置後は旧v1～v5パッケージのメニューguardが一致しなくなるため、過去コマンドの再利用はしない。旧パッケージは改変しない。
- 恒久仕様を `control/docs/SCREEN_SPEC.md`、現在状況を `control/docs/PROJECT_STATUS.md` に反映した。Scope Manifest・固定Commit監査は未更新/未実施。
- 履歴採番直前のCandy読取比較: local HEAD/mainとGitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementブランチは双方なし。同日最大42から43を採番。Git状態変更なし。過去記録42の `Investigating` はHISTORYの定義外ラベルだったため、過去ファイルは変えず本記録では定義済み `Verification Pending` を使用する。

## 現在

- Remaining Work: ユーザーによる7メニューファイル配置の実行結果受領と、本番のCANDYプロフィール/共通画面/出勤・集計メニュー表示確認。以前のデザイン版の実行出力未受領、既存の実DB同時更新等・Git保存・監査未完了の境界は継続。今回のSSH接続、本番配置、DB操作は未実施。
- Next Action: メニュー修正専用コマンドをユーザーへ案内し、成功出力とCANDY選択時の左メニュー確認を受領する。照合エラー時はその出力を確認し、未知の本番変更を上書きしない。
