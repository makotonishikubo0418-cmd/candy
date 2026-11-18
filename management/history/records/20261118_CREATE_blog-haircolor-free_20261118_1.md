# 髪色自由バイトブログのローカル作成と表示確認

- History: [`20261118_CREATE_blog-haircolor-free.md`](../20261118_CREATE_blog-haircolor-free.md)
- Record Date: 2026-11-18
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像2点は作成前から存在し、寸法はいずれも 1000×750 だった。
- ブログカテゴリはユーザー指定の「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存テーマに `#C76A92` はなく、共通CSSへ `blog-theme-dusty-rose-pink` を追加し、参照中HTMLの `blog.css` バージョンを `1b856eb5` に揃えた。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQはないため FAQPage は出していない。女の子紹介ブロックはないため ItemList は出していない。
- `candy-site-state check --target haircolor-free` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。関連記事は決定論的候補6件。TOP最新15件の末尾が本件。
- ローカル表示は `http://127.0.0.1:18888` 経由で確認した。記事は PC幅1280とスマートフォン幅390で横スクロールなし。画像2点の自然寸法はいずれも0より大きい。アクセントは `#C76A92`。CTAは2ボタンで求人構成を確認した。
- TOPブログ枠は15件、末尾が本件。スマートフォン幅390で横スクロールなし。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像2点、共通CSSと参照バージョン、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認。
- Next Action: 本件ファイルだけを commit・push し、本番公開を確認する
