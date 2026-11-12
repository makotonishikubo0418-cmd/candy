# 写メ日記ブログのローカル作成と表示確認

- History: [`20261112_CREATE_blog-photo-diary.md`](../20261112_CREATE_blog-photo-diary.md)
- Record Date: 2026-11-12
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像4点はいずれも 1000×750 の JPEG だった。
- ブログカテゴリはユーザー指定の「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#C77985` はなく、共通CSSへ `blog-theme-dusty-coral-pink` を追加し、参照中HTMLの `blog.css` バージョンを `56ebb1a2` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。女の子紹介もないため ItemList は出していない。
- `candy-site-state check --target photo-diary` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。共通検証の related / registry エラーは0件だった。
- ローカル表示は `http://127.0.0.1:8765` 経由で source HTML を確認した。パス補正後、記事は PC幅とスマートフォン幅390で横スクロールなし。画像4点の自然寸法はいずれも 1000×750。アクセントは `#C77985`。CTAは2ボタンで求人構成を確認した。
- TOPブログ枠は15件、末尾が本件。PC枠幅1050、スマートフォン幅390で横スクロールなし。

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
