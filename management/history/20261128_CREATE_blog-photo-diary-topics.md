# 写メ日記ネタ切れ求人ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-28

## 目的

指定原稿の「写メ日記のネタ切れ、みんなどうしてる？」求人ブログを、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-photo-diary-topics.php`
- slug: `photo-diary-topics`
- タイトル: 写メ日記のネタ切れ、みんなどうしてる？
- ブログカテゴリ: 求人
- ポイント色: `blog-theme-soft-dusty-pink`（基準 `#D48FA6`）
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-photo-diary-topics_1.jpg`〜`_4.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、`blog.css` へのテーマ追加と参照 `v` 更新、生成管理書
- 実施範囲に明示された git add・commit・push と本番公開
- 指定範囲外の既存記事本文、記事別CSS、DB操作、本件外の未追跡画像は対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明、指定リンクを欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供のため JSON-LD に追加しない。FAQと女の子紹介はない。
- ブログカテゴリは「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。
- 本文内リンクは関連記事「アイディアは突然に。💡」（`./kagoshima-deliveryhealth-blog-sudden-ideas.php`）1件。
- 指定画像4点は作成前からローカルに未追跡で存在していた。
- 既存 `blog-theme-dusty-pink` は `#C58E9B` のため、指定 `#D48FA6` 用に `blog-theme-soft-dusty-pink` を新規追加する。
