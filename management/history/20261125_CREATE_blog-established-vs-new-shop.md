# 老舗店と新規店比較求人ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-25

## 目的

鹿児島デリヘルで老舗店と新規オープン店の特徴を比較する求人カテゴリのブログ記事を、指定原稿・画像・管理書に従って作成し、GitHub反映と本番公開まで完了する。

## 対象範囲

- 公開URL: https://www.55810.com/kagoshima-deliveryhealth-blog-established-vs-new-shop.php
- 記事タイトル: 鹿児島デリヘルで働くなら老舗店？新規店？それぞれの特徴や魅力を比較！
- ブログカテゴリ: 求人
- ポイント色: テラコッタブラウン（#916247）／共通テーマ `blog-theme-terracotta-brown`
- 記事HTML・公開PHP・dataset・`dataset_base.php`・blog一覧・TOP最新15件・sitemap・必要画像・生成管理書
- 共通CSS `HP/css/blog.css` へのテーマ追加と参照HTMLの `blog.css?v=` 更新
- git add / commit / push と本番公開確認

## 完了条件

- 正式テンプレートとブログ仕様に沿った記事一式がローカルで揃い、検証に合格している
- 指定カテゴリ求人の固定CTA（女の子一覧 / 求人情報はコチラ）が配置されている
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認している
- GitHubへ反映され、本番URLで公開確認が完了している
- 履歴に作成から公開までの結果が記録されている

## 初期情報

- ユーザー指示に原文、画像3点（TOP・新規オープン見出し直下・1999年創業見出し直下）、関連記事リンク1件、カテゴリ求人、ポイント色 #916247 が含まれる
- 女の子紹介ブロック・FAQ・著者・日付の指定はない
- 既存の同一slugファイルはなく、画像3点は `HP/imgHtml/new_202601/blog/` に配置済み
- 既存テーマ `blog-theme-terracotta` は #be6d55、`blog-theme-warm-terracotta` は #B67A5E のため、指定HEX #916247 用に別テーマを追加する方針
