# 安心サポート求人ブログのローカル作成と表示確認

- History: [`20261120_CREATE_blog-peaceofmindsupport.md`](../20261120_CREATE_blog-peaceofmindsupport.md)
- Record Date: 2026-11-20
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像4点は作成前から存在し、寸法はいずれも 1000×750 だった。
- ブログカテゴリはユーザー指定の「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#ff8c00` はなく、共通CSSへ `blog-theme-dark-orange` を追加し、参照中HTMLの `blog.css` バージョンを `ce1c5605` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQは原稿の3問を表示し、FAQPageも同数・同文意で登録した。女の子紹介ブロックはないため ItemList は出していない。
- FAQ見出しは原稿の「こんなことも相談していいの？」に、FAQ検出と整合する「｜よくある質問」を付与した。
- 本文リンク「デリヘルの体験入店って何をするの？面接からお仕事までの流れをチェック！」は `./kagoshima-deliveryhealth-blog-trial-entry.php`。
- `candy-site-state check --target peaceofmindsupport` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。関連記事は決定論的候補6件。TOP最新15件の末尾が本件。
- ローカル表示は `http://127.0.0.1:8765` 経由で確認した。記事は PC 1280・スマートフォン幅390で横スクロールなし。画像4点の自然寸法はいずれも 1000×750。アクセントは `#ff8c00`。CTAは2ボタンで求人構成を確認した。
- TOPブログ枠は15件、末尾が本件。枠内の横スクロールなし。一覧ボタンは枠外。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像4点、共通CSSと参照バージョン、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認。
- Next Action: 本件ファイルだけを commit・push し、本番公開を確認する
