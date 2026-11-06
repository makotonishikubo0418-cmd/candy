# ギャップ萌えブログ見出し接頭辞のローカル修正

- History: [`20261106_MODIFY_gap-moe-heading.md`](../20261106_MODIFY_gap-moe-heading.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 「導入｜」は `HP/source/kagoshima-deliveryhealth-blog-gap-moe.html` の目次リンクと scene1 の H2 に各1か所だけ存在した。
- 差分は、その2か所から「導入｜」を削除した2行だけである。H2要素、`blog-nowrap`、目次の `href="#scene1"` は維持した。
- ローカル `http://127.0.0.1:8899/kagoshima-deliveryhealth-blog-gap-moe.php` は HTTP 200。修正後の文言は2件、旧接頭辞は0件。
- 別の headless Chrome で幅1280と390を計測した。見出しは H2、文字色は `rgb(53, 21, 35)`、見出し内の横あふれは0、ページの横スクロールは0。目次の文言とリンク先は見出しと一致した。

### 決定

- 目次は実在するH2と同じ文言にするため、同じ接頭辞削除を目次表示へも適用する。リンク先は変えない。
- 共通CSS、他の本文、画像、リンク、生成管理書は変更しない。

### 対応

- source HTML の対象2か所だけを修正した。

### 結果

- ローカル確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番確認
- Next Action: 本件ファイルだけを commit・push し、本番URLの見出しを確認する
