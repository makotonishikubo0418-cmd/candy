# 進捗記録

- Date: 2026-11-24
- Status: In Progress
- Summary: 口コミ評価ブログのローカル作成・検証まで完了

## 実施内容

- 公開URL・タイトル・カテゴリ一般・ポイント色 #A87545・原稿・画像3点を確認した
- `HP/css/blog.css` に `blog-theme-caramel-brown`（#A87545）を追加し、参照HTMLの `blog.css?v=` を SHA-256先頭8桁 `a68bc347` へ更新した。記事別CSSは作成していない
- 正式テンプレート構造で記事HTMLを作成し、公開PHP・dataset・`dataset_base.php`・blog一覧末尾追加・TOP最新15件同期・sitemap登録を実施した
- 一般CTA（女の子一覧 / 本日の出勤情報）を配置し、FAQPage・author・日付は原稿にないため未追加とした
- 画像パスは実在ファイル `review-rating_1.jpg` / `_2.jpg` / `_3.jpg` を使用した
- `candy-site-state.cmd` の sync / write / check が合格した
- ローカルHTTPで記事PC/SPとTOPブログ欄を実表示確認し、画像 naturalWidth/Height > 0、アクセント #A87545、横スクロールなし、CTA・枠外一覧ボタンを確認した

## 確認済み事実

- 機械検証（robots=index、canonical、scene1–5と目次一致、画像3点、一般CTA、TOP15件、sitemap、PHP構文）は合格
- PC/SP実表示で hero・中間画像2点・CTA・TOP末尾リンクを確認した

## 残作業

- git add / commit / push
- 本番デプロイ完了確認と本番URL表示確認

## 次のアクション

- 許可済みの Git 反映と本番公開確認を実施する
