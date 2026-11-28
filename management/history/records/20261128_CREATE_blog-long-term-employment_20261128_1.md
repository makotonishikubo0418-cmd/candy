# 進捗記録

- History: [`20261128_CREATE_blog-long-term-employment.md`](../20261128_CREATE_blog-long-term-employment.md)
- Record Date: 2026-11-28
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ求人・ポイント色 #C49A7C・原稿・画像4点・内部リンク1件を確認した
- slug `long-term-employment` の既存衝突はなし。指定画像4点はいずれも 1000×750
- 正式テンプレートに沿い記事HTML・公開PHP・dataset・`dataset_base.php`・一覧・TOP・sitemap を作成した
- `blog.css` へ `blog-theme-warm-camel`（#C49A7C）を追加し、参照中HTMLの `blog.css` バージョンを `1ed0593f` に揃えた。記事別CSSは作成していない
- 求人CTA（女の子一覧 / 求人情報はコチラ）を配置し、FAQPage・author・日付は原稿にないため未追加とした
- `candy-site-state.cmd check --target long-term-employment` は `structure=COMPLETE`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`、`issues=NONE`
- ローカルHTTP（ルート相対パス配信、127.0.0.1:55811）で記事PC（1280）・SP（390）とTOPブログ欄を実表示確認し、画像 naturalWidth/Height=1000x750、アクセント #C49A7C、横スクロールなし、求人CTA・TOP末尾リンク（15件、最終が本件）を確認した

### 未確認事項

- GitHub 反映後の本番HTTP/DOMは未確認

## 現在

- Remaining Work: GitHub 反映（add/commit/push）と本番公開確認
- Next Action: 対象ファイルのみを `main` へ commit・push し、本番URLを確認する
