# 癒やし系女子ブログのローカル作成と表示確認

- History: [`20261112_CREATE_blog-healing-girls.md`](../20261112_CREATE_blog-healing-girls.md)
- Record Date: 2026-11-12
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像の実ファイル4点は `_1` 1000×750、`_2`/`_3` 1280×960、`_4` 853×640 の JPEG だった。
- 指示書のTOP画像パスは二重プレフィックスだったが、実ファイル名は `kagoshima-deliveryhealth-blog-healing-girls_1.jpg` だったため、実ファイルを参照した。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#D58D9F` はなく、共通CSSへ `blog-theme-dusty-rose` を追加し、参照中HTMLの `blog.css` バージョンを `7f35a839` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。女の子紹介は原稿の本文ブロックであり、店長おすすめ ItemList は出していない。
- `candy-site-state check --target kagoshima-deliveryhealth-blog-healing-girls` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。PHP構文検査は公開PHP、dataset、`dataset_base.php` が通過した。
- ローカル表示は `http://127.0.0.1:8765/_preview/` 経由で確認した。
- 記事は PC幅1280とスマートフォン幅390で横スクロール0。画像4点の `currentSrc` は各JPEGで、自然寸法は上記どおり。アクセントは `#D58D9F`。CTAは2ボタンで、左が女の子一覧、右が本日の出勤情報。
- TOPブログ枠は15件、末尾が本件、先頭は nightwork-style、一覧ボタンは枠の外。枠内の横スクロールは0。PC枠幅1050、スマートフォン枠幅390。

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
