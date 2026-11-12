# 写メ日記ブログ画像コメントの修正と公開

- Type: MODIFY
- Start Date: 2026-11-12

## 目的

公開済み写メ日記記事の画像下コメント3件を指定文言へ修正し、表示確認のうえ GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-photo-diary.php`
- 対象ファイル: `HP/source/kagoshima-deliveryhealth-blog-photo-diary.html` の `figcaption` 3件のみ
- 実施範囲に明示された git add・commit・push と本番公開
- 画像ファイル、配置、本文、見出し、リンク、CSS、記事別CSS作成は対象外

## 完了条件

- 指定3件の画像コメントが修正後文言と一致している
- PCとスマートフォンでコメント表示を確認している
- `main` へ push し、本番URLで修正文言を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 修正対象は `_2`（ソファでスマホ）、`_3`（ハートスタンプ）、`_4`（撮影ブース）の画像下コメント。
- PHOTO NOTE ラベルやコメント枠デザイン、画像パス・ロゴは変更しない。
