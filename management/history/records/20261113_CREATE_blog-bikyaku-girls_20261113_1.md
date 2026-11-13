# 美脚女子ブログのローカル作成と表示確認

- History: [`20261113_CREATE_blog-bikyaku-girls.md`](../20261113_CREATE_blog-bikyaku-girls.md)
- Record Date: 2026-11-13
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像5点はいずれも 1000×750 の JPEG だった。配置前のファイル名は `…girls01.jpg`〜`05.jpg` で、指定パスの `_1`〜`_5` にリネームした。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#D41470` はなく、共通CSSへ `blog-theme-vivid-magenta` を追加し、参照中HTMLの `blog.css` バージョンを `ea9ccfda` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。原稿本文の女の子紹介は記事画像と指定プロフィールリンクで構成し、店長おすすめブロック用の ItemList は出していない。
- `candy-site-state check --target bikyaku-girls` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。関連記事検証は OK。TOP最新15件と一覧末尾の一致も確認した。
- ローカル表示は `http://127.0.0.1:8765` 経由で source HTML を確認した。パス補正後、記事は PC幅とスマートフォン幅390で横スクロールなし。画像5点の自然寸法はいずれも 1000×750。アクセントは `#D41470`。CTAは2ボタンで一般構成を確認した。
- TOPブログ枠は15件、末尾が本件。PC枠幅1050、スマートフォン幅390で横スクロールなし。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像5点、共通CSSと参照バージョン、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認。ローカル `main` は live GitHub `main` より 4 commit 遅れているため、push 前に pull が必要。
- Next Action: 本件ファイルだけを commit し、許可を得て pull 後に push・本番確認する
