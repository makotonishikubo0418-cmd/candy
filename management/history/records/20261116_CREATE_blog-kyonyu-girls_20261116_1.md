# 巨乳美女4選ブログのローカル作成と表示確認

- History: [`20261116_CREATE_blog-kyonyu-girls.md`](../20261116_CREATE_blog-kyonyu-girls.md)
- Record Date: 2026-11-16
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像5点は作成前から存在し、寸法は `_1.jpg` が 1000×750、`_2.jpg` から `_5.jpg` が 853×640 だった。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#A72C47` はなく、共通CSSへ `blog-theme-ruby-red` を追加し、参照中HTMLの `blog.css` バージョンを `7aaa5123` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。台帳由来の女の子紹介ブロックはないため ItemList は出していない。
- プロフィールリンクは原稿指定どおり `girls.php?no=1419`、`1243`、`1067`、`1249`。台帳には `1067` リンカのみ存在し `LOCAL_ONLY`。本文画像はブログ専用画像を使用している。
- `candy-site-state check --target kyonyu-girls` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。関連記事は決定論的候補6件。TOP最新15件の末尾が本件。
- ローカル表示は `http://127.0.0.1:18888` 経由で確認した。記事は PC幅とスマートフォン幅390で横スクロールなし。画像5点の自然寸法はいずれも0より大きい。アクセントは `#A72C47`。CTAは2ボタンで一般構成を確認した。
- TOPブログ枠は15件、末尾が本件。スマートフォン幅390で横スクロールなし。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像5点、共通CSSと参照バージョン、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認。
- Next Action: 本件ファイルだけを commit・push し、本番公開を確認する
