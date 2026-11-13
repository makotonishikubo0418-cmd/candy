# ローカルHP退避完了と取得接続の用途確認

- History: [20261113_OPERATION_server-authoritative-local-sync.md](../20261113_OPERATION_server-authoritative-local-sync.md)
- Record Date: 2026-11-13
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

- ローカルHPを `C:\Users\teamp\.codex\artifacts\candy-server-to-local-20261113\local-before\HP` へコピーした。
- 退避元と退避先の1,757ファイルのSHA-256、および25ディレクトリの相対パスの一致を検証した。検証一覧は退避先親ディレクトリの `verification.json` に保存した。
- ローカルHPにはシンボリックリンクや禁止されている `HP/HP` は存在しない。
- 現在確認できているCandy専用READ-ONLY接続は全階層の取得に対応していない。
- 前の調査で使った `cti-deploy` はCTI用途の接続として登録されている。Candy用途の許可確認を省いた判断は、比較案件の訂正記録に残した。

### 決定

- サーバーからローカルHPへの同期自体は、ユーザーの指示で許可されている。重ねて同期作業全体の許可は求めない。
- 別用途の接続を無確認で流用しない。既存 `cti-deploy` 接続をCandyフォルダの読取り・ダウンロードだけに利用してよいか、またはCandy用のFTP/SFTP接続を別途用意するかを一度に確認した。
- 取得先と退避先をローカルHPから分離し、取得内容の検証を終えてからHPへ反映する。

### 結果

ローカルHPの復元可能な退避を完了した。本番からの再取得とローカルHPへの反映はまだ行っていない。本番ファイル、DB、Git参照・インデックスには変更を加えていない。

## 現在

- Remaining Work: Candy全階層取得に使う接続の用途確認、現在の本番取得、ローカル反映、最終一致検証。
- Next Action: 既存 `cti-deploy` のCandy読取り・DL用途への許可、またはCandy用FTP/SFTP接続の指定を待つ。
