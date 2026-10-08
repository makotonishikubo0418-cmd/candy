# ブログ全体デザインの本番公開と表示確認

- History: [`20261008_CREATE_first-deliveryhealth-girl-choice-blog.md`](../20261008_CREATE_first-deliveryhealth-girl-choice-blog.md)
- Record Date: 2026-10-08
- Sequence: 7
- Status: Completed

## 記録

### 実行

- ユーザーの明示許可に基づき、CSS、本文HTML、Sequence 6の履歴記録の3ファイルだけをStageした。
- コミット `beb7a2b883c205d6b9497a93d9d472237816ba9c` を `main` へPushした。
- GitHub Actions `CANDY Production Deploy` の実行 `37753295205` で、`HP/css/blog-girl-choice.css` と `HP/source/kagoshima-deliveryhealth-blog-girl-choice.html` の2件だけを本番へ配信した。
- 本番ファイルの削除は0件で、配信対象2件はサーバー上のSHA-256照合まで完了した。

### 結果

- GitHub Actionsは成功し、本番URLはHTTP 200を返した。
- 本番HTMLはCSS参照クエリ `blog-girl-choice.css?v=2389bba0`、`POINT` 2件、主・副CTAリンクを保持している。
- 本番CSSのSHA-256は `2389bba04d634b1b50c5346f1ff891299924a1ac4561eb3e18409541cf9434d3` で、公開コミットのCSSと一致した。
- 本番の画像3件はすべてHTTP 200で、PC・スマートフォン実ブラウザでは本文画像2件とも読み込み完了、元画像寸法1000×750、説明用 `alt` とキャプションを確認した。
- PC表示では、強調枠本文20px、カード内余白32px 30px 30px、カード直後の間隔36px、CTAボタン高60pxを確認した。
- 390×844px表示では、強調枠本文19px、カード内余白28px 24px 26px、カード直後の間隔30px、キャプション内余白18px 20px、CTAボタン高56pxを確認した。CTAは2列で両ラベルとも1行表示、横方向の表示超過は0だった。
- 本番確認時のブラウザエラーおよび警告は0件だった。
- ブログ一覧とサイトマップへのリンク追加は、当初のユーザー指定どおり実施していない。

## 現在

- Remaining Work: None
- Next Action: None
