# 共通blog.cssへの切替と表示維持確認

- History: [20261014_MODIFY_shared-blog-css.md](../20261014_MODIFY_shared-blog-css.md)
- Record Date: 2026-10-14
- Sequence: 1
- Status: Completed

## 記録

### 決定

ユーザーが指定した4記事だけを共通 `blog.css` に切り替える。本文・画像・リンク先・SEO本文情報は維持し、テンプレートの仮文章とnoindex/nofollowを維持する。旧CSSの削除や他の6記事の変更は行わない。今回の指示はローカル修正であり、Git操作・本番公開は行わない。

### 対応

- `HP/css/blog.css` を作成し、4つの専用CSSにあった340ルールを205ルールに整理した。共通部品は `blog-*`、色・装飾は `blog-theme-pink` / `brown` / `gold` / `deep-pink` で管理する。
- 女の子選び・個室待機・東京土産・編集用テンプレートの4つのHTMLを、同一の `./css/blog.css?v=c47a1983` 参照に修正した。共通CSS2枚の読込順は維持し、本文・画像・リンク・metadata・JSON-LDを変更していない。
- サイトマップは該当する公開済み3記事のlastmodだけを2026-10-14へ同期した。URL数254件は不変。テンプレートのURLは追加していない。
- 管理用の生成文書7ファイルを現在の参照関係へ更新した。PHP、dataset、共通JavaScript、既存CSS4ファイルには今回の追加変更を行っていない。以前からの未コミット変更は保持した。

### 結果

- Chromeの独立したオフライン検証環境で、4記事それぞれを幅1440px / 390pxで変更前後比較した。
- 8条件すべてで、DOM各要素と疑似要素の対象CSSプロパティ、要素の位置・寸法の差分は0件。導入位置のスクリーンショットPNGも変更前後で同一バイトだった。
- 画像読み込み、JavaScript実行、スマートフォンのメニュー開閉を確認。欠落アセット・JavaScriptエラーは0件。
- 実ファイルは検証済み候補と一致し、4記事の共通CSS更新キーがSHA-256先頭8桁と一致。空のCSS宣言なし。本文等の差分がclass属性とCSS参照だけであることを確認。
- 他の6記事は従来の共通CSS2枚のみを参照し、旧専用CSS4ファイルは変更前と同一バイトのまま保持している。
- 台帳の全体チェックは `sitemap_lastmod_or_structure=page=other:kagoshima-deliveryhealth-blog-candy_dominant_girls` で未合格。以前からの単体noindex雛形を通常ページとして判定する既存の分類・未登録問題であり、今回のCSSの問題ではない。ユーザー指定を優先し、雛形の一覧・サイトマップ登録や分類実装の変更は行っていない。
- ステージ・コミット・Push・本番公開は未実施。本番への反映確認も対象外。

## 現在

- Remaining Work: None（今回の共通CSS化・ローカル表示確認の範囲）。台帳の雛形分類問題は既存の別範囲として残る。
- Next Action: None。旧CSS削除、分類変更、GitHub同期、本番更新は具体的な指示に従う。
