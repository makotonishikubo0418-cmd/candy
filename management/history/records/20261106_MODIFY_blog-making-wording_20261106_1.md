# ブログ制作記事の2か所修正と本番確認

- History: [`20261106_MODIFY_blog-making-wording.md`](../20261106_MODIFY_blog-making-wording.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 変更前の差分は `HP/source/kagoshima-deliveryhealth-blog-making.html` の2行だけ。オフィスの文を「キャンディ」へ変更し、対象文の `blog-callout` を `<p><b>…</b></p>` へ変更した。
- ローカル `http://127.0.0.1:8766/kagoshima-deliveryhealth-blog-making.php` と本番 `https://www.55810.com/kagoshima-deliveryhealth-blog-making.php` の両方で、対象文は背景・枠線・POINTラベルがなく、文字サイズ16px、行間は本文と同じ、`font-weight` は700、文字色は本文色。前後のPOINTボックスは残った。POINTボックスは5件。
- オフィスの文は通常の本文と同じ太さ。HTML内の `Candy` は2件で、「Candyのブログ制作の裏側」とフッター。`Candyのオフィス` は0件。
- PC幅1280とスマートフォン幅390で横スクロールは0。画像2点の自然寸法は各1000×750。スマートフォンのCTAは横並びで、高さ56px、幅147px、上端は同じ。
- サイトマップ `lastmod` のプレビューは changed=0。生成管理書の `check --target` は対象ページ `structure=COMPLETE`、`issues=NONE`。文書差分は、生成管理書の検証元が `ac81b3a` のままである既存差分と、未コミットの別案件が混ざる再生成を避けるため、今回のコミットへ含めなかった。
- 修正コミットは `b833bd5caa69ec6108847f9eb2f6ab037ad39efc`。本番デプロイ run 42147106670 は success。本番URLは HTTP 200。

### 決定

- 対象文は `<strong>` にしない。共通CSSが `.blog-section strong` の色をアクセント色へ変えるため、太さだけを持つ `<b>` を使う。
- 共通CSS、他記事、生成管理書の全件再生成は今回の修正に含めない。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- source HTMLの指定2か所を修正し、ローカルのPC・スマートフォン表示を確認した。
- `main` へ push し、本番デプロイ後に同じ2か所とPC・スマートフォン表示を確認した。

### 結果

- 本番URL https://www.55810.com/kagoshima-deliveryhealth-blog-making.php に指定2か所が反映された。
- 本番デプロイ run 42147106670 は success。https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42147106670

## 現在

- Remaining Work: None
- Next Action: None
