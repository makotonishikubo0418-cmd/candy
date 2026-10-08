# 本文画像2点の表示不具合修正を本番確認

- History: [`20261008_CREATE_first-deliveryhealth-girl-choice-blog.md`](../20261008_CREATE_first-deliveryhealth-girl-choice-blog.md)
- Record Date: 2026-10-08
- Sequence: 5
- Status: Completed

## 記録

### 訂正

- 訂正対象ファイル: `20261008_CREATE_first-deliveryhealth-girl-choice-blog_20261008_3.md`
- 誤っていた情報: PC・スマートフォンの本番表示確認を完了としていたが、本文画像2点の実表示確認が不足しており、実際には既存の遅延読み込み処理によって表示されていなかった。
- 正しい情報: 先頭画像は正常だったが、本文画像2点は実DOMの `src` が説明用 `alt` の文章へ書き換えられていた。修正後にPC・スマートフォンの双方で本文画像の実表示を確認した。
- 訂正の根拠: 修正前の本番DOMでは本文画像2点の `currentSrc` が空で `naturalWidth` が0だった。修正後の本番DOMでは画像パスが維持され、両画像とも `naturalWidth` 1000、`naturalHeight` 750、読み込み完了を確認した。

### 結果

- 本文画像2点へ `nolazy` クラスを追加した修正をコミット `17fda4852c6142bf26a980e19c4c46bfdfed7227` として `main` へPushした。
- GitHub Actions `CANDY Production Deploy` の実行 `37750336033` は成功した。
- 本番URLはHTTP 200を返し、本番HTMLの対象画像3点すべてで正しい画像パス、説明用 `alt`、`nolazy` クラスが維持されている。
- PC・スマートフォンの本番表示で本文画像2点を目視し、いずれも読み込み完了、元画像寸法1000×750を確認した。
- 本番確認時のブラウザエラーおよび警告は0件だった。

## 現在

- Remaining Work: None
- Next Action: None
