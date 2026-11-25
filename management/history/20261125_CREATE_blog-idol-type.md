# 王道アイドル系女子ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-25

## 目的

指定原稿の「まるでアイドル！？ キャンディの王道アイドル系女子4人をご紹介♡」一般ブログを、正式なブログ仕様に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-idol-type.php`
- slug: `idol-type`
- タイトル: まるでアイドル！？ キャンディの王道アイドル系女子4人をご紹介♡
- ブログカテゴリ: 一般
- ポイント色: `blog-theme-light-rose-pink`（基準 `#D16B9D`）
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-idol-type_1.jpg`〜`_5.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、`blog.css` テーマ追加と参照 `v` 更新、生成管理書
- 実施範囲に明示された git add・commit・push と本番公開
- 指定範囲外の既存記事本文、記事別CSS、DB操作、本件外の未追跡画像は対象外

## 完了条件

- 原稿の見出し、本文、画像、画像説明、指定リンクを欠落させず、固定CTA・JSON-LD・一覧・TOP・サイトマップと一致している
- PCとスマートフォンで記事とTOPブログ欄の表示、画像、余白、改行、CTA、横スクロール、リンクを確認している
- `main` へ push し、本番URLで対象ページの公開を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 原稿はユーザー指定の原文。著者と日付は未提供のため JSON-LD に追加しない。FAQはない。
- 女の子4人（ナナセ・ミカ・イト・レイナ）の紹介とプロフィールリンクは原稿・指示どおり。`CANDY_GIRL_INFORMATION.json` の店長おすすめブロックは使わない。
- ブログカテゴリは「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。
- 指定画像5点は作成前からローカルに未追跡で存在していた。
- 既存テーマに `#D16B9D` は無かったため、`blog.css` へ `blog-theme-light-rose-pink` を追加する。
