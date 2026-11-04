# ナイトワーク働き方ブログのローカル作成と検証

- History: [`20261104_CREATE_nightwork-style-blog-article.md`](../20261104_CREATE_nightwork-style-blog-article.md)
- Record Date: 2026-11-04
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- slug `nightwork-style` の公開PHP・source HTML・dataset を新規作成し、`dataset_base.php`・`blog.html`・`index.html`（最新15件）・`sitemap.xml` を同期した。
- `HP/css/blog.css` に `blog-theme-terracotta`（基準 `#be6d55`）を追加し、参照ハッシュを `4785552c` へ更新した。記事別CSSは作成していない。
- 画像 `_1`・`_2` はいずれも 1000x750。FAQ 4件と FAQPage を一致。著者は未提供のため Article JSON-LD に含めない。
- 構造検証エラー0件。TOPブログ欄は15件、末尾が本記事。関連記事は決定論選定のブログ3・エリア3。
- ローカルHTTPで記事をPC（横スクロールなし、画像 natural 1000x750、accent `#be6d55`、CTA表示）とスマートフォン幅390（横スクロールなし）で確認。TOPはソース上の15件登録を確認。index.html のローカル相対CSS解決差による見た目崩れは本番パス前提のため、本確認ではレジストリ一致を根拠とした。

### 対応

- 正式スタッフブログテンプレート構造で完成HTMLを作成。
- 指定リンク（個室待機）と画像キャプションを反映。
- 共通CSSへテラコッタテーマを追加。

### 結果

ローカル作成と主要検証は完了。GitHub反映と本番公開は未実施。

## 現在

- Remaining Work: git add・commit・push、本番公開確認、履歴完了記録
- Next Action: 許可済み範囲でコミット・Pushし、Actionsと本番URLを確認する
