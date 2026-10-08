# 本文画像2点の表示不具合を修正

- History: [`20261008_CREATE_first-deliveryhealth-girl-choice-blog.md`](../20261008_CREATE_first-deliveryhealth-girl-choice-blog.md)
- Record Date: 2026-10-08
- Sequence: 4
- Status: In Progress

## 記録

### 確認済み事実

- 本番ページの本文画像2点で、実DOMの `src` が画像パスから `alt` の文章へ書き換えられ、画像が表示されない状態だった。
- 既存の画像遅延読み込み処理は、`nolazy` クラスがない画像について `alt` を画像パスとして扱い、表示時に `src` へ代入する仕様である。
- 対象画像には通常の説明的な `alt` を設定していたが、既存処理から除外する `nolazy` クラスが不足していた。
- 先頭画像は `nolazy` クラスが設定されていたため正常に表示されていた。

### 対応

- 本文画像2点へ `nolazy` クラスを追加し、説明的な `alt`、画像パス、寸法、ネイティブ遅延読み込み属性は維持した。

## 現在

- Remaining Work: ローカル検証、GitHubへのPush、GitHub Actions本番公開、本番DOMと実画像表示の確認。
- Next Action: 対象2ファイルだけを検証して本番公開する。
