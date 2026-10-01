# ユーザー指示による修正版の再有効化コマンド準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 29
- Status: Waiting for Response

## 記録

### 決定

- ユーザーは実DB読取・速度確認の許可依頼に対し、「ONにする／時間がない／コマンド出せ」と管理側の再有効化を明示指示した。AGENTS第1節に基づき、この具体的なユーザー指示を下位管理書の通常の検証順序より優先する。DB読取・速度・保存・同時更新の未検証事項が解消したとは扱わない。
- 許可対象は本番Controlの `site/candy_recommendation_config.php` 1ファイルを、修正版6ファイルが存在することを確認したうえでONにする操作。HP切替・DB変更・追加SQL・Git操作を含めない。AI自身が本番操作するのではなく、ユーザーがSSHパスワードを入力して実行するコマンドを渡す。

### 対応・検証

- 既存 `Invoke-AdminActivation.ps1 -Run` は修正前13ファイルのSHA256を要求し、配置済み修正版では停止するため、そのまま案内しない。旧ファイル・旧安全確認を変更せず、同じ調査フォルダーに `activate_admin_repair.php`、`Invoke-AdminRepairActivation.ps1` と専用PHP/PowerShell試験を追加した。
- 新手順は既存の固定ON/OFF設定とCommit `e2b493bcf9380a2a73c0701c0db05eb15c1d79f6` の6修正を組み合わせ、管理側13ファイルと既存HPの固定表示状態を照合する。未知の差分・修正未配置時は書込前に停止する。設定原本をWeb領域外へ保存し、設定1ファイルのみ原子的にONへ置換して再照合する。
- `-Disable` で設定だけOFFに戻し、修正6ファイルは保持する。共通転送ロック・所有者/パス確認・SHA256固定照合を維持。ON直後の検査失敗時には既存の復元処理でOFFへ戻す。
- ローカル試験は既存57項目、新規20項目が通過。修正版のON/OFF、設定以外の不変性、バックアップ、再実行、旧版や後続編集の拒否、障害注入時のOFF復元を確認した。PowerShell 5.1のON/OFF接続なし確認、転送PHP全体2通りの構文検査、3パッケージとON/OFF指定の復号往復照合も通過。実DBや本番画面の試験ではない。一時試験領域のみ削除済み。
- ONコマンドは `Invoke-AdminRepairActivation.ps1 -Run`、緊急OFFは同コマンドに `-Disable` を追加。成功目印は `CANDY_REACTIVATION_OK=1`、OFF成功は `CANDY_REACTIVATION_DISABLED_OK=1`。旧DB有効化SQLを再実行しない。
- このターンでは本番接続・有効化・DB操作・Git状態変更を行っていない。ユーザーの実行結果はまだ受領していない。
- HISTORY第9章照合: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大28を確認して29を採番。

## 現在

- Remaining Work: ユーザーの再有効化実行結果、おすすめフォーム・一覧・既存画面の実動作確認。実DB速度・保存・同時更新の検証は未完了。HP切替も未実施。
- Next Action: ON/OFFコマンドを提示し、出力を受け取る。遅延等が再発した場合は即OFFに戻す。ユーザーの明示指示があっても、未実施の検証をPASSとして扱わない。
