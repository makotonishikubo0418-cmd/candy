# 指名替えブログのGitHub反映と本番確認完了

- History: [`20261105_CREATE_shimei-change-blog-article.md`](../20261105_CREATE_shimei-change-blog-article.md)
- Record Date: 2026-11-05
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- 記録作成前のローカル `main` と GitHub `main` は `d93ecb01d9d9cd3b8e987c7c5ea130835cbeb84b` で一致した。同一案件・同一日の進捗記録の最大連番は 1 で、本記録が 2 になる。
- 公開コミットは `d93ecb01d9d9cd3b8e987c7c5ea130835cbeb84b`。`origin/main` へ Push 済み。
- [Actions 41989573101](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41989573101) は成功。deploy job `127118307301` は約3分24秒で完了し、preview job は push のため skipped。
- 本番転送は28件、削除0件。ログは `SUCCESS: deployed and SHA256-verified 28 file(s); deleted 0 obsolete file(s)` と `ENTRY_CONTRACT_OK`。転送前SHAは `936b8a59a5d02e04fa2ed5774e4492ac8d206c10`、転送後SHAは公開コミットと一致。
- 本番 `https://www.55810.com/kagoshima-deliveryhealth-blog-shimei-change.php` は HTTP 200。本文「もちろんOKです」を確認した。
- 本番記事のPC幅1598では横スクロールなし。画像3点はいずれも表示元が指定JPEGで natural 1000x750。`--blog-accent` は `#B65B86`。CSSは `blog.css?v=f3d19e6f`。
- 本番記事のスマートフォン幅390では横スクロールなし。
- 本番TOPは15件、末尾が本記事。PCでは枠幅1050px・左274px・横スクロールなし。スマートフォン幅390では枠幅390px・横スクロールなし。
- 本番TOPには既存の年齢確認が表示される。確認後のDOMに本記事リンクと「ブログ一覧はコチラ」がある。
- 検索エンジンへのインデックス状態は未確認。

### 結果

GitHub反映と本番公開、本番の記事・TOP表示確認が完了した。無関係な未コミット変更はステージしていない。

## 現在

- Remaining Work: None
- Next Action: None
