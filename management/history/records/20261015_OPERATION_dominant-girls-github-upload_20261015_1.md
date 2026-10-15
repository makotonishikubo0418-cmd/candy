# GitHub保存対象と実行範囲の確定

- History: [20261015_OPERATION_dominant-girls-github-upload.md](../20261015_OPERATION_dominant-girls-github-upload.md)
- Record Date: 2026-10-15
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- HTML1件、JPEG3件、生成管理書7件の差分を確認した。ステージ済み差分はない。
- 生成管理書の差分は対象記事の情報・画像数・状態指紋の反映である。
- 現行ワークフローはmainのHP変更Pushで本番配信を起動する。
- 既存のサイトマップ分類指摘は未解決である。今回の依頼は保存であり、公開準備や記事内容の検証完了を意味しない。

### 決定

- 対象11ファイルのバイトを変更せずCommit/Pushする。
- コミットメッセージに `[skip ci]` を付け、本番自動反映を起動しない。

## 現在

- Remaining Work: Commit/Push、GitHub mainとのSHA一致・対象バイト保持・作業ツリー・Actions未起動の確認。
- Next Action: 対象11ファイルと履歴をGitHubへ保存する。
