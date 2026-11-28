# ローカル作成と表示検証

- History: [`20261128_CREATE_blog-hotel-comparison_1.md`](../20261128_CREATE_blog-hotel-comparison_1.md)
- Record Date: 2026-11-28
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- slug `hotel-comparison_1` の既存同名公開ファイルはなかった。
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧末尾登録、TOP最新15件、サイトマップ、生成管理書を同期した。
- `blog-theme-smoky-mauve`（`#8B718C`）を `blog.css` に追加し、参照 `v=889a13a0` を更新した。
- `candy-site-state.cmd check --target hotel-comparison_1` は `CHECK=OK`、対象ページは `structure=COMPLETE seo=OK images=OK list=1 sitemap=1`。
- ローカル HTTP で PC（約1280px）とスマートフォン（390px）の記事・TOPブログ欄を確認した。画像3点は `naturalWidth/Height` が 1000x750、横スクロールなし、一般CTA（女の子一覧 / 本日の出勤情報）、`./hotel.php` リンク、アクセント色 `#8B718C` を確認した。

### 決定

- 画像ファイル名は指定どおり `...hotel-comparison_1.jpg`〜`_3.jpg` とする（slug 末尾 `_1` を stem として `_1_1.jpg` 形式にはしない）。

### 対応

- 正式テンプレート構造で記事HTMLを作成し、関連記事は決定論的選定結果を配置した。
- FAQPage / 著者 / 日付は原稿にないため JSON-LD に含めない。

### 結果

- ローカル作成・検証は完了。GitHub反映と本番公開は未実施。

## 現在

- Remaining Work: 許可済みの git add・commit・push と本番公開確認
- Next Action: 対象ファイルのみを commit / push し、本番URLを確認する
