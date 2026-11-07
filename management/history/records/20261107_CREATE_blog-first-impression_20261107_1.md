# 初対面ブログのローカル作成・検証

- History: [`20261107_CREATE_blog-first-impression.md`](../20261107_CREATE_blog-first-impression.md)
- Record Date: 2026-11-07
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- slug `first-impression` の同名公開PHP・source HTML・dataset は作成前に存在しなかった。
- 画像2点は各 1000×750 JPEG。TOP画が `_1`、Q1末尾直下が `_2`。
- 原稿にFAQブロックと店長おすすめ女の子紹介ブロックはない。匿名の K/R/S ちゃん聞き取りは本文内の通常見出しとして配置した。
- `blog-theme-deep-teal`（`#417F80`）を `HP/css/blog.css` に追加し、参照 `v` を `4ad52240` へ更新した。
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧末尾、TOP最新15件、sitemap、生成管理書を同期した。
- CTAは一般カテゴリどおり「女の子一覧」「本日の出勤情報」。JSON-LDは Article と BreadcrumbList。FAQPage と author/date は未提供のため未出力。
- ローカルHTTPで PC（1280）・SP（390）の記事表示と TOP ブログ欄15件を確認した。画像 naturalWidth/Height は両画像とも 1000×750。記事・TOPとも横スクロールなし。テーマ accent は `#417F80`。

### 対応

- 正式テンプレート構成で記事一式を作成し、ローカル検証まで完了した。

### 結果

- ローカル作成と表示検証は完了。GitHub反映と本番公開は未実施。

## 現在

- Remaining Work: git add・commit・push、本番公開確認
- Next Action: 対象パスのみをステージして commit・push し、本番URLを確認する
