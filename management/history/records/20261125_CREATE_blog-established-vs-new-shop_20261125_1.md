# 進捗記録

- History: [`20261125_CREATE_blog-established-vs-new-shop.md`](../20261125_CREATE_blog-established-vs-new-shop.md)
- Record Date: 2026-11-25
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ求人・ポイント色 #916247・原稿・画像3点・内部リンク1件を確認した
- `HP/css/blog.css` に `blog-theme-terracotta-brown`（#916247）を追加し、参照HTMLの `blog.css?v=` を SHA-256先頭8桁 `a82db275` へ更新した。記事別CSSは作成していない
- 正式テンプレート構造で記事HTMLを作成し、公開PHP・dataset・`dataset_base.php`・blog一覧末尾追加・TOP最新15件同期・sitemap登録を実施した
- 求人CTA（女の子一覧 / 求人情報はコチラ）を配置し、FAQPage・author・日付は原稿にないため未追加とした
- `candy-site-state.cmd` の sync / write / check が合格した（`TARGET_PAGE=blog:established-vs-new-shop ... issues=NONE`）
- ローカルHTTPで記事PC/SPとTOPブログ欄を実表示確認し、画像 naturalWidth/Height=1000x750、アクセント #916247、横スクロールなし、求人CTA・TOP末尾リンクを確認した

### 結果

- 機械検証とPC/SP実表示は合格。GitHub反映と本番公開は未実施

## 現在

- Remaining Work: git add / commit / push、本番デプロイ完了確認と本番URL表示確認
- Next Action: 許可済みの Git 反映と本番公開確認を実施する
