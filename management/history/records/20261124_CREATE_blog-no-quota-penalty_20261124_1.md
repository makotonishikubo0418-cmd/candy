# ノルマ・罰金なし求人ブログのローカル作成と検証完了

- History: [`20261124_CREATE_blog-no-quota-penalty.md`](../20261124_CREATE_blog-no-quota-penalty.md)
- Record Date: 2026-11-24
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ求人・ポイント色指定・原稿・画像3点・指定リンクを確認した
- 同一slugの既存記事はなかった
- 共通CSSへ `blog-theme-sage-green`（`--blog-accent: #65877A`）を新規追加し、参照HTMLの `blog.css?v=` を SHA-256先頭8桁 `16b66ce0` に更新した
- `candy-site-state` の sync / write / check が合格した（CHECK=OK）
- ローカルHTTPプレビュー（8767）で、`/source/` 直開きによる相対パスずれを避けるため一時 `<base href>` 付きプレビューを用いて PC（1440px）とSP（390px）を確認した
- 記事: 画像3点の naturalWidth/Height=1000x750、横スクロールなし、アクセント `#65877A`、求人CTA（女の子一覧 / 求人情報はコチラ）、関連リンク6件、FAQ3件を確認した
- TOPブログ欄: 枠内15件・末尾が本件・「ブログ一覧はコチラ」→`./blog.php`、PC/SPとも横スクロールなしを確認した
- 一時プレビューファイルは検証後に削除した

### 対応

- 正式テンプレート構造で記事HTML・公開PHP・dataset・dataset_base・blog一覧・TOP最新15件・sitemapを同一変更単位で整備した
- 固定CTAはカテゴリ求人（女の子一覧 / 求人情報はコチラ）とした
- Article + BreadcrumbList + FAQPage を配置した。女の子ItemListは未使用（店長おすすめブロックなし）
- 記事別CSSは作成していない

### 結果

- ローカル作成と検証は完了した
- GitHub反映と本番公開は未実施

## 現在

- Remaining Work: 本件対象ファイルの git add / commit / push と本番公開確認
- Next Action: 許可された範囲で GitHub 反映し、本番URLを確認する
