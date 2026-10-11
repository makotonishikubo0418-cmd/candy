# 女の子選びブログ画像2点のローカル置換完了

- History: [`20261011_MODIFY_girl-choice-blog-image-replacement.md`](../20261011_MODIFY_girl-choice-blog-image-replacement.md)
- Record Date: 2026-10-11
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 原本2枚目は1000×750pxのJPEG、原本3枚目は1448×1086pxのPNGで、どちらも4:3だった。
- 置換前の公開用2枚目のSHA-256は `44cb2cb4b1cce88896d4085f2715cdfa432ad1cd9be029c5b7a920f8e9f31347`、3枚目は `872dd13ba82ab4b300591846d4abd37e479f8cbdb805dc5dfc786944512b65fb` だった。

### 決定

- 原本2枚目は1000×750pxのまま同名置換する。
- 原本3枚目は4:3を維持して1000×750pxへ縮小し、正規公開ファイル名のJPEGへ変換する。
- 旧画像の別名バックアップや重複公開ファイルは作成せず、Git履歴を復元経路とする。

### 対応

- `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-girl-choice_2.jpg` と `_3.jpg` を新画像へ同名置換した。
- 本文HTMLと作成元Textの画像参照を、新SHA-256由来の `?v=ba3cbfa6` と `?v=d29ba82a` へ更新した。
- 対象ページのサイトマップ `lastmod` と生成現況資料6件を正規処理で同期した。

### 結果

- 新2枚目は1000×750pxのJPEG、SHA-256 `ba3cbfa6d35b56dd0804792a30cda14671a086908fa427dd2cd2aa7766ada949` である。
- 新3枚目は1000×750pxのJPEG、SHA-256 `d29ba82a4d3665df598af5e4ecfc0143e9220dd1d56b835367b017ef9a42a6ed` である。
- `HP/` 内の旧画像ハッシュは各0件、新画像ハッシュは正規公開パスに各1件だけだった。
- `candy-site-state check --target kagoshima-deliveryhealth-blog-girl-choice` は `structure=COMPLETE`、`seo=OK`、`images=OK`、`issues=NONE`、`CHECK=OK` だった。
- 対象PHP2件の構文検査、デプロイスクリプト自己テスト、デプロイ統合テストは合格した。

## 現在

- Remaining Work: 対象差分のCommit・Push、GitHub Actions、本番画像SHA-256、HTTP、実DOM、PC・スマートフォン表示の確認。
- Next Action: 明示対象だけをStageして本番公開を実行する。
