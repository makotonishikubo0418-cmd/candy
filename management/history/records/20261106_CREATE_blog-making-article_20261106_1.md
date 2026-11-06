# ブログ制作の裏側ブログの作成と本番公開

- History: [`20261106_CREATE_blog-making-article.md`](../20261106_CREATE_blog-making-article.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業ディレクトリは `C:\Codex\FSG\Candy`。指定画像2点は `HP/imgHtml/new_202601/blog` に存在し、Git未追跡、`.gitignore` 対象外、各 1000×750 の JPEG だった。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- 既存 `blog-theme-terracotta` のアクセントは `#be6d55` で、指定の `#B67A5E` とは別色だったため、共通CSSへ `blog-theme-warm-terracotta` を追加し、参照中HTMLの `blog.css` バージョンを `b3313bc4` に揃えた。
- ローカルプレビューは `http://127.0.0.1:8766/kagoshima-deliveryhealth-blog-making.php` と `http://127.0.0.1:8766/index.php`。PC幅1280とスマートフォン幅390で、画像の自然寸法は各 1000×750、横スクロールは0、CTAは2ボタン、TOPブログ枠は15件で本件が末尾、一覧ボタンは枠の外だった。
- 公開コミットは `50b0cede9d4bad9bb34f742edd62f26c8908328e`。GitHub `main` のSHAと一致した。
- 本番デプロイは成功。Actions: https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42141381542
- 本番 `https://www.55810.com/kagoshima-deliveryhealth-blog-making.php` は HTTP 200。画像2点のSHA-256はローカルと一致。CSSアクセントは `#B67A5E`。PC幅1280とスマートフォン幅390で横スクロールは0、両画像の自然寸法は 1000×750。TOPとブログ一覧に本件リンクがある。

### 決定

- 記事別CSSは作らず、共通 `blog.css` のテーマとして指定色を追加する。
- 著者と日付は原稿にないため、JSON-LDへ追加しない。FAQと女の子紹介はないため、対応するJSON-LDは出力しない。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像2点、共通CSSを作成または更新した。
- `main` へ push し、本番デプロイの成功と本番URLの表示を確認した。

### 結果

- 公開URL https://www.55810.com/kagoshima-deliveryhealth-blog-making.php で記事を公開した。
- 本番デプロイ run 42141381542 は success。

## 現在

- Remaining Work: None
- Next Action: None
