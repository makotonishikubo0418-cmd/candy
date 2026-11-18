# 髪色自由バイトブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-18

## 目的

指定原稿の髪色・ネイル・ピアス自由に関する求人ブログを、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-haircolor-free.php`
- slug: `haircolor-free`
- タイトル: 黒染めしなくていいんです♡ 鹿児島で髪色自由のバイト！ネイル・ピアスもOK？
- ブログカテゴリ: 求人
- ポイント色: 共通テーマ `blog-theme-dusty-rose-pink`。アクセント `#C76A92`
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-haircolor-free_1.jpg` と `_2.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、共通 `blog.css` のテーマ追加と参照バージョン
- 実施範囲に明示された git add・commit・push と本番公開
- 指定範囲外の既存記事本文、記事別CSS、DB操作、女の子紹介ブロックは対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明を欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供。FAQはない。
- 女の子紹介ブロックの掲載指定はない。
- ブログカテゴリは「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。
- 指定画像は作成前からローカルに存在していた。
