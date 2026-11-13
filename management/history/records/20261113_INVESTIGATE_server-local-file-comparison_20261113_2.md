# 比較に用いた接続の用途確認不足の訂正

- History: [20261113_INVESTIGATE_server-local-file-comparison.md](../20261113_INVESTIGATE_server-local-file-comparison.md)
- Record Date: 2026-11-13
- Sequence: 2
- Status: Completed

## 記録

### 訂正

- 訂正対象: `20261113_INVESTIGATE_server-local-file-comparison_20261113_1.md` の「同一サーバーの既存通常SSH接続」を使用した対応判断。
- 誤っていた判断: Candy専用READ-ONLY経路の取得範囲が足りないため、別用途の既存SSH鍵を、Candy用途への利用可否を確認せず流用した。
- 確認済みの根拠: ローカルSSH設定の `cti-deploy` はCTIのデプロイ用接続として登録されていた。接続成功や同一サーバーであることだけでは、Candy用途への利用許可の根拠にならない。
- 修正: 当該接続をCandy用途へ再使用せず、Candyの全階層取得に利用できる正式な接続、または当該接続のCandy用途への明示許可を確認する。
- 前記録のファイル一覧・SHA-256・改行差分類は実際の取得結果であり、今回の訂正で数値を変更しない。本番ファイルの変更も行っていない。

### 結果

比較結果と、接続用途の確認に関する判断の誤りを区別して記録した。

## 現在

- Remaining Work: None（完了済みの比較結果に対する訂正記録）
- Next Action: None（後続のダウンロード作業で利用経路を確認する）
