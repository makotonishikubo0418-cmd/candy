# 写メ日記ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-12

## 目的

指定原稿の写メ日記記事を、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-photo-diary.php`
- slug: `photo-diary`
- タイトル: 写メ日記は強制？書かなくても稼げる？鹿児島デリヘルキャンディが解説！
- ブログカテゴリ: 求人
- ポイント色: 共通テーマ `blog-theme-dusty-coral-pink`。アクセント `#C77985`
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-photo-diary_1.jpg` から `_4.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、共通 `blog.css` のテーマ追加と参照バージョン
- 実施範囲に明示された git add・commit・push と本番公開
- 指定範囲外の既存記事本文、記事別CSS、DB操作は対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明、指定リンクを欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供。FAQと店長おすすめ女の子紹介はない。
- ブログカテゴリは「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。
- 本文内リンク「関連記事：鹿児島キャンディの身バレ対策をご紹介！」は `kagoshima-deliveryhealth-blog-privacy-measures.php`。
- 画像4点は各 1000×750 の JPEG。
