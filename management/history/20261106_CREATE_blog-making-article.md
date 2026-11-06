# ブログ制作の裏側ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-06

## 目的

指定原稿のブログ制作の裏側記事を、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-making.php`
- slug: `making`
- タイトル: キャンディのブログ、実はこんなふうに作っています！📝
- ブログカテゴリ: 一般
- ポイント色: 共通テーマ `blog-theme-warm-terracotta`。アクセント `#B67A5E`
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-making_1.jpg`、`_2.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、共通 `blog.css` のテーマ追加と参照バージョン
- 実施範囲に明示された git add・commit・push と本番公開
- 女の子紹介ブロックは対象外
- 指定範囲外の既存記事本文、記事別CSS、DB操作は対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明を欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供。
- ブログカテゴリは当初の指示になく、確認後に「一般」と指定された。
- 画像2点は `HP/imgHtml/new_202601/blog` に未追跡ファイルとして存在し、更新日時は 2026-11-04、寸法は各 1000×750 の JPEG。
- 作業開始時の `main` は GitHub `main` と同一SHA `ac81b3a26d95b1933d514d4cd81ef13079ec99cb`。作業ツリーには本件以外の未コミット変更があった。
