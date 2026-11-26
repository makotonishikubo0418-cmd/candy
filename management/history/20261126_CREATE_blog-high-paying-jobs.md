# 稼げる仕事求人ブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-26

## 目的

鹿児島で稼げる仕事を探している女性向けの求人カテゴリブログ記事を、指定原稿・画像・管理書に従って作成し、GitHub反映と本番公開まで完了する。

## 対象範囲

- 公開URL: https://www.55810.com/kagoshima-deliveryhealth-blog-high-paying-jobs.php
- 記事タイトル: 鹿児島で稼げる仕事がしたい！お金も時間も諦めたくない欲張りな女性へ♡
- ブログカテゴリ: 求人
- ポイント色: ラズベリーピンク（#C05C78）／共通テーマ `blog-theme-raspberry-pink`
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

- ユーザー指示に原文、画像4点（TOP・LINE登録段落直下・プライベート優先段落直下・欲張り上等段落直下）、関連記事リンク2件、カテゴリ求人、ポイント色 #C05C78 が含まれる
- 女の子紹介ブロック・FAQ・著者・日付の指定はない
- 既存の同一slugファイルはなく、画像4点は `HP/imgHtml/new_202601/blog/` に配置済み
- 既存テーマ `blog-theme-deep-dusty-rose` は #B05C78、`blog-theme-bright-rose-pink` は #C85A7C のため、指定HEX #C05C78 用に別テーマを追加する方針
