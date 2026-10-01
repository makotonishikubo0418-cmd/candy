# 店長おすすめ管理対応 — 読取専用接続ファイルの配置確認

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-29
- Sequence: 6
- Status: Investigation Pending

## 記録

### 確認済み事実

- ユーザーの「設置した確認しろ」に基づき、[前記録](20260929_INVESTIGATE_manager-recommendation-targets_20260929_5.md)で不足していた接続ファイルと専用Launcherの接続を確認した。
- `C:\Users\nishi\.ssh\candy_db_readonly_rsa` と `C:\Users\nishi\.ssh\candy_readonly_known_hosts` は、いずれも指定場所に存在する。秘密鍵の内容は表示・記録していない。
- `management/scripts/Invoke-LiveDbRead.ps1 -SelfTest` は終了コード0で、`CANDY_DB_READONLY_LAUNCHER_OK` および `CANDY_DB_READONLY_OK` を返した。
- Launcherによるホスト鍵・接続先Identity確認を通過し、許可対象の `fsg_db` へ読取専用経路で接続できた。返されたDBMSのバージョンはMySQL 5.6.36。

### 結果と確認範囲

- 接続ファイル不足による停止条件は解消した。今回の確認は配置と接続のSelfTest／Statusまでであり、関連テーブル構造やCANDY人物対応は未確認。
- DBの更新・作成・削除、接続設定変更、Git状態変更、本番反映、プログラム改修は行っていない。
- 先に許可されたDB読取の範囲と確定済みの仕様判断は変更しない。

## 現在

- Remaining Work: 許可済みの関連テーブル・人物対応の読取確認、非公開／削除時の選択解除経路の調査、仕様の計画反映、HP側と管理画面側の実装・検証。
- Next Action: 専用READ-ONLY経路で許可済みのDB事前確認から再開する。DB書込み・Git状態変更・本番反映は具体的な別途許可の前に実行しない。
