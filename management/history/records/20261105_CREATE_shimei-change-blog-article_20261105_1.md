# 指名替えブログのローカル作成と検証

- History: [`20261105_CREATE_shimei-change-blog-article.md`](../20261105_CREATE_shimei-change-blog-article.md)
- Record Date: 2026-11-05
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 作業開始時のローカル `main` と GitHub `main` は `936b8a59a5d02e04fa2ed5774e4492ac8d206c10` で一致した。無関係な未コミット変更が残っていたため、ブランチ全体は未同期。今回の対象ファイルとは重なっていない。
- slug `shimei-change` の公開PHP・source HTML・dataset を新規作成し、`dataset_base.php`・`blog.html`・`index.html`（最新15件）・`sitemap.xml` を同期した。
- `HP/css/blog.css` に `blog-theme-rose-pink`（基準 `#B65B86`）を追加し、参照ハッシュを `f3d19e6f` へ更新した。記事別CSSは作成していない。
- 画像3点はいずれも JPEG 1000x750。著者と公開日は未提供のため Article JSON-LD に含めない。FAQ見出しがないため FAQPage は出力しない。
- 構造検証エラー0件。`candy-site-state check --target shimei-change` は `structure=COMPLETE seo=OK images=OK list=1 sitemap=1 issues=NONE`、文書整合は `CHECK=OK`。
- ローカル `http://127.0.0.1:8765/` で記事をPC幅1598（横スクロールなし、画像 natural 1000x750、accent `#B65B86`、CTA表示）とスマートフォン幅390（横スクロールなし、CTA高さ56px）で確認した。
- TOPブログ欄は15件、末尾が本記事。PCでは枠幅1050px・左274pxで中央、一覧ボタンとの間隔40px、次区画は `shopinfo`。スマートフォン幅390でも枠内15件・横スクロールなし・一覧ボタンと姉妹店区画を確認した。

### 決定

- インタビューのM・A・Nは原稿どおりの呼称のみとし、女の子台帳のプロフィールやItemListには結び付けない。
- レガシー入力形式に合わない原稿テキストを `Text_blog_data` に置くと、未完成候補として生成台帳へ載るため、完成HTMLを正としてテキストファイルは残さない。

### 対応

- 正式スタッフブログテンプレート構造で完成HTMLを作成した。
- 指定の3画像、キャプション、女の子一覧リンクを反映した。
- 共通CSSへローズピンクテーマを追加し、全ブログ参照の `v` を同じハッシュへ更新した。

### 結果

ローカル作成と表示確認は完了。GitHub反映と本番公開は未実施。

## 現在

- Remaining Work: 対象ファイルの git add・commit・push、本番公開確認、完了記録
- Next Action: 許可済み範囲でコミット・Pushし、Actionsと本番URLを確認する
