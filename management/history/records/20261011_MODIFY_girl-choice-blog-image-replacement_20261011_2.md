# 女の子選びブログ画像2点の本番公開完了

- History: [`20261011_MODIFY_girl-choice-blog-image-replacement.md`](../20261011_MODIFY_girl-choice-blog-image-replacement.md)
- Record Date: 2026-10-11
- Sequence: 2
- Status: Completed

## 記録

### 対応

- 対象差分をCommit `c6cb6d223ecf764ddd067c6eb35233637ed9a753` として `main` へPushした。
- デプロイdry-runで画像2点、本文HTML、サイトマップの4ファイルだけが公開対象であり、削除操作0件、除外ファイル0件であることを確認した。
- GitHub Actions `CANDY Production Deploy` Run `38101513694` で4ファイルを本番へ公開した。

### 確認済み結果

- GitHub Actionsは成功し、4ファイルすべてが本番サーバー上のSHA-256照合に合格した。
- 本番2枚目はHTTP 200、1000×750px、77423 bytes、SHA-256 `ba3cbfa6d35b56dd0804792a30cda14671a086908fa427dd2cd2aa7766ada949` だった。
- 本番3枚目はHTTP 200、1000×750px、245663 bytes、SHA-256 `d29ba82a4d3665df598af5e4ecfc0143e9220dd1d56b835367b017ef9a42a6ed` だった。
- 本番ページはHTTP 200で、2枚目を `?v=ba3cbfa6`、3枚目を `?v=d29ba82a` で参照していた。
- 実DOMでは両画像とも読み込み完了、自然寸法1000×750px、表示比率4:3だった。PC表示は836×627px、スマートフォン表示は521×391pxで、スマートフォン表示に横方向のはみ出しはなかった。
- 旧画像の別名ファイルは作成していない。同名公開パスは新画像バイトへ上書きされ、旧SHA-256は正規公開パスに残っていない。復元経路はGit履歴だけである。
- 本番エントリー契約は `ENTRY_CONTRACT_OK` で、対象ページの表示内容と公開状態に問題はなかった。

## 現在

- Remaining Work: なし。
- Next Action: なし。
