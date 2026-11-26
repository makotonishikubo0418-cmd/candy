# ローカル作成と表示検証

- Date: 2026-11-26
- Status: Verification Pending
- Actor: Auto agent
- PC: メインPC

## 確認済み事実

- 記事一式を作成した。公開PHP、source HTML、dataset、`dataset_base.php` 登録、ブログ一覧末尾追加、TOP最新15件同期、sitemap登録、画像4点参照を揃えた。
- 求人カテゴリの固定CTAは「女の子一覧」「求人情報はコチラ」で、右リンク先は `privacy-measures.php`。
- FAQと女の子紹介はないため FAQPage / ItemList JSON-LD は置かず、Article と BreadcrumbList のみとした。著者・日付は未提供のため追加していない。
- `blog-theme-apricot-orange` のアクセントを `#D99550` に更新し、参照中HTMLの `blog.css?v=` を `38dd7ed1` に揃えた。記事別CSSは作成していない。
- `candy-site-state.cmd check --target local-vs-away` は `structure=COMPLETE`、`seo=OK`、`images=OK`、`issues=NONE`。
- ローカル `http://127.0.0.1:8765` で記事とTOPを確認した。画像4点の自然寸法はいずれも 1000×750。アクセントは `#D99550`。PC幅・スマートフォン幅で横スクロールなし。CTAは2ボタンで求人構成を確認した。TOP末尾に本件リンクがあることを確認した。

## 未確認事項

- GitHub 反映と本番公開は未実施。

## 残作業

- 許可済みの git add・commit・push
- 本番公開確認

## 次の対応

- 対象パスだけをステージして commit・push し、本番URLを確認する。
