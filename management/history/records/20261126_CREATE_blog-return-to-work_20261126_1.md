# 進捗記録

- History: [`20261126_CREATE_blog-return-to-work.md`](../20261126_CREATE_blog-return-to-work.md)
- Record Date: 2026-11-26
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ求人・ポイント色 #A34D5D・原稿・画像4点・指定リンク2件を確認した
- `HP/css/blog.css` に `blog-theme-rose-bordeaux`（#A34D5D）を追加し、参照HTMLの `blog.css?v=` を SHA-256先頭8桁 `3b78b48a` へ更新した。記事別CSSは作成していない
- 正式テンプレート構造で記事HTMLを作成し、公開PHP・dataset・`dataset_base.php`・blog一覧末尾追加・TOP最新15件同期・sitemap登録を実施した
- 求人CTA（女の子一覧 / 求人情報はコチラ）を配置し、FAQPage・author・日付は原稿にないため未追加とした
- AlumPark出典URLは指示文が次リンクのクエリと連結されていたため、`https://alumpark.net/c-article/00004/?utm_source=chatgpt.com` として設置した
- `candy-site-state.cmd check --target return-to-work` が合格した（`structure=COMPLETE` / `seo=OK` / `images=OK` / `list=1` / `sitemap=1` / `issues=NONE`）
- ローカルHTTP（ルート相対パス配信）で記事PC・SP（390）とTOPブログ欄を実表示確認し、画像 naturalWidth/Height=1000x750、アクセント #A34D5D、横スクロールなし、求人CTA・TOP末尾リンクを確認した
- PHP lint は公開PHP・dataset・`dataset_base.php` で合格

### 結果

- 機械検証とPC/SP実表示は合格。GitHub反映と本番公開は未実施

## 現在

- Remaining Work: git add / commit / push、本番デプロイ完了確認と本番URL表示確認
- Next Action: 許可済みの Git 反映と本番公開確認を実施する
