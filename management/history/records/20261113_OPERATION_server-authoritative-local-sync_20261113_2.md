# CTI用接続のCandy用途への利用拒否

- History: [20261113_OPERATION_server-authoritative-local-sync.md](../20261113_OPERATION_server-authoritative-local-sync.md)
- Record Date: 2026-11-13
- Sequence: 2
- Status: Waiting for Response

## 記録

### 決定

- 既存 `cti-deploy` 接続をCandyの読み取り・ダウンロードにも使用してよいかという確認に対し、ユーザーは「NG」と回答した。
- 当該接続をCandy用途に使用しない。別用途の鍵への差替えや接続制限の回避も行わない。
- Candy専用の全階層取得経路が確認できるまで、本番からの再取得とローカルHPへの反映は実行できない。

### 結果

検証済みのローカルHP退避は保持した。ローカルHPの上書き、本番変更、Git操作、DB操作は行っていない。

## 現在

- Remaining Work: Candy用の正規な全階層取得経路の確保、現在の本番取得、ローカル反映、最終一致検証。
- Next Action: Candy用FTP/SFTP等の利用可能な接続情報または接続準備を待つ。`cti-deploy` の利用許可は再度求めない。
