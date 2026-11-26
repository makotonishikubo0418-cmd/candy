# ローカル作成と表示検証

- Date: 2026-11-26
- Status: Verification Pending
- Actor: Auto agent
- PC: メインPC

## 確認済み事実

- 記事一式を作成した。公開PHP、source HTML、dataset、`dataset_base.php` 登録、ブログ一覧末尾追加、TOP最新15件同期、sitemap登録、画像4点参照を揃えた。
- 一般カテゴリの固定CTAは「女の子一覧」「本日の出勤情報」で、右リンク先は `schedule.php`。
- FAQと女の子紹介はないため FAQPage / ItemList JSON-LD は置かず、Article と BreadcrumbList のみとした。著者・日付は未提供のため追加していない。
- `blog-theme-dusty-blue-lavender`（基準 `#8C98B5`）を `blog.css` に新規追加し、参照中HTMLの `blog.css?v=` を `4f6c62e4` に揃えた。記事別CSSは作成していない。
- `candy-site-state.cmd check --target beyond-photos` は `structure=COMPLETE`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`、`issues=NONE`。
- ローカル `http://127.0.0.1:8765` で記事とTOPを確認した。画像4点の自然寸法はいずれも 1000×750。アクセントは `#8C98B5`。PC幅・スマートフォン幅で横スクロールなし。CTAは2ボタンで一般構成を確認した。TOP末尾に本件リンクがあることを確認した。

## 未確認事項

- GitHub 反映と本番公開は未実施。

## 残作業

- 許可済みの git add・commit・push
- 本番公開確認

## 次の対応

- 対象パスだけをステージして commit・push し、本番URLを確認する。
