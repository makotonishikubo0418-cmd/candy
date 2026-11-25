# 進捗記録

- History: [`20261125_CREATE_blog-established-vs-new-shop.md`](../20261125_CREATE_blog-established-vs-new-shop.md)
- Record Date: 2026-11-25
- Sequence: 3
- Status: In Progress

## 記録

### 確認済み事実

- ユーザーが TOP 画像と文中画像のファイル名を修正したと指示した
- 作業ツリー上、`established-vs-new-shop_1.jpg` と `_3.jpg` のバイナリが入れ替わっており、`_2.jpg` と記事HTML・参照パスは未変更である

### 決定

- HTML・画像コメント・参照先・その他設定は変更せず、修正済み画像ファイルのみ本番へ反映する

### 対応

- 対象画像のみをステージし、本番公開用に commit / push する

## 現在

- Remaining Work: commit / push、本番デプロイ確認、TOP画像と文中画像の表示確認
- Next Action: GitHub反映と本番表示確認
