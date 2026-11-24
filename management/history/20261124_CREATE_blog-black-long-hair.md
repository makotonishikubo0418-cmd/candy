# 黒髪ロング美女特集ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-24

## 目的

指定原稿の黒髪ロング特集一般ブログを、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-black-long-hair.php`
- slug: `black-long-hair`
- タイトル: やっぱり黒髪ロングが好き♡ 鹿児島デリヘルキャンディの黒髪美女特集！
- ブログカテゴリ: 一般
- ポイント色: 共通テーマ `blog-theme-dark-green`。アクセント `#34574D`
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-black-long-hair_1.jpg` 〜 `_5.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、sitemap、生成管理書、共通 `blog.css` のテーマ追加と参照バージョン
- 実施範囲に明示された git add・commit・push と本番公開
- 指定範囲外の既存記事本文、記事別CSS、DB操作は対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明、指定リンクを欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供。FAQはない。
- 女の子紹介は原稿本文の紹介（ワタ・ナナミ・リア・ユリ）とブログ画像。店長おすすめItemListは未使用。
- ブログカテゴリは「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。
- 指定画像5点は作成前からローカルに存在していた。画像5のパス表記は原稿上 `●●●●●` だったが、同ディレクトリの `_5.jpg` 実ファイルを使用した。
- 途中の CSS バージョン一括置換で `HP/source` HTML が空になる事故があり、`git restore HP/source` で復元したうえで作業を再開した。
