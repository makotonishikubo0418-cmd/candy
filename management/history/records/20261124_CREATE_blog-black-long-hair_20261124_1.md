# 黒髪ロング美女特集ブログのローカル作成と検証完了

- History: [`20261124_CREATE_blog-black-long-hair.md`](../20261124_CREATE_blog-black-long-hair.md)
- Record Date: 2026-11-24
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ一般・ポイント色 `#34574D`・原稿・画像5点・指定リンクを確認した
- 同一slugの既存記事はなかった
- 既存テーマに `#34574D` はなく、共通CSSへ `blog-theme-dark-green` を追加し、参照中HTMLの `blog.css` バージョンを `0ddde909` に揃えた。記事別CSSは作成していない
- `candy-site-state` の sync / write / check が合格した（TARGET_PAGE seo=OK images=OK list=1 sitemap=1 issues=NONE）
- ローカルHTTPプレビュー（8768）でPC（1440px）とSP（390px）の記事・TOPブログ欄を確認し、画像5点の naturalWidth/Height > 0、横スクロールなし、一般CTA（女の子一覧 / 本日の出勤情報）、関連リンク6件、TOP枠内15件（末尾が本件）を確認した
- 途中で PowerShell の置換失敗により `HP/source` HTML 312件が空になったため、`git restore HP/source` で復元してから作成を再開した

### 対応

- 正式テンプレート構造で記事HTML・公開PHP・dataset・dataset_base・blog一覧・TOP最新15件・sitemapを同一変更単位で整備した
- 固定CTAはカテゴリ一般（女の子一覧 / 本日の出勤情報）とした
- Article + BreadcrumbList のみ配置し、FAQPage・女の子ItemListは未使用（原稿にFAQなし／店長おすすめブロックなし）
- プロフィールリンクは指定どおり `girls.php?no=1189/1193/97/1479`、本文関連記事は清楚系女子へ接続した

### 結果

- ローカル作成と検証は完了した
- GitHub反映と本番公開は未実施

## 現在

- Remaining Work: 本件対象ファイルの git add / commit / push と本番公開確認
- Next Action: 許可された範囲で GitHub 反映し、本番URLを確認する
