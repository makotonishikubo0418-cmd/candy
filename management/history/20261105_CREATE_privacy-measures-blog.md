# 身バレ対策ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-05

## 目的

指定原稿の身バレ対策ブログを、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-privacy-measures.php`
- slug: `privacy-measures`
- タイトル: 「働きたいけど身バレが不安…」鹿児島デリヘルキャンディの身バレ対策をご紹介！
- ポイント色: 共通テーマ `blog-theme-smoky-blue`。指定の `#527F9B` と共通CSSのアクセント `#547FA1` は別値
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-privacy-measures_1.jpg`、`_2.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書
- 実施範囲に明示された git add・commit・push と本番公開
- 女の子紹介ブロックは対象外
- 指定範囲外の既存記事修正、記事別CSS、DB操作は対象外

## 完了条件

- 原稿の見出し、本文、FAQ、画像、画像説明を欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供。
- 画像2点は作業開始時にローカル未追跡ファイルとして存在し、寸法は各 1000×750 の JPEG。
- 作業開始時の `main` は GitHub `main` と同一SHA `84c36eb70f4679424005a3bd5f5f0daca9784f3f`。作業ツリーには本件以外の未コミット変更があった。
