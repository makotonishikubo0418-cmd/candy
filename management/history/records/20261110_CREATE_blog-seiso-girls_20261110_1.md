# 清楚系女子ブログのローカル作成と表示確認

- History: [`20261110_CREATE_blog-seiso-girls.md`](../20261110_CREATE_blog-seiso-girls.md)
- Record Date: 2026-11-10
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像の実ファイル4点は各 1000×750 の JPEG だった。
- 指示書の画像パスは二重プレフィックスだったが、実ファイル名は `kagoshima-deliveryhealth-blog-seiso-girls_N.jpg` だったため、実ファイルを参照した。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#C58E9B` はなく、共通CSSへ `blog-theme-dusty-pink` を追加し、参照中HTMLの `blog.css` バージョンを `cd632bbe` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。女の子紹介は原稿の本文ブロックであり、店長おすすめ ItemList は出していない。
- `candy-site-state check --target kagoshima-deliveryhealth-blog-seiso-girls` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。PHP構文検査は公開PHP、dataset、`dataset_base.php` が通過した。
- ローカル表示は HP ルート直下の一時プレビュー経由で確認した。
- 記事は PC幅1280とスマートフォン幅390で横スクロール0。画像4点の `currentSrc` は各JPEGで、自然寸法は 1000×750。アクセントは `#C58E9B`。CTAは2ボタンで、左が女の子一覧、右が本日の出勤情報。
- TOPブログ枠は15件、末尾が本件、一覧ボタンは枠の外。枠内の横スクロールは0。PC枠幅1050、スマートフォン枠幅390。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像4点、共通CSSと参照バージョン、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認
- Next Action: 本件ファイルだけを commit・push し、本番URLを確認する
