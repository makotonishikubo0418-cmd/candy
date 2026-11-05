# 身バレ対策ブログFAQ表示のローカル修正

- History: [`20261105_PROBLEM_privacy-measures-faq-display.md`](../20261105_PROBLEM_privacy-measures-faq-display.md)
- Record Date: 2026-11-05
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 青字のQは `.faq-question::before` の `content: 'Q'`、色は `rgb(84, 127, 161)`。見出し本文の「Q. 」と重なって2つ見えていた。
- 見出し `h2` の下線と `.blog-faq` の上線が、最初の質問の上に2本出ていた。
- ローカル静的配信のPC幅1614で、3件の見出し本文から「Q. 」は消え、`::before` は `Q` の1つ。見出しの `border-bottom` は `0px`。横スクロールなし。1件目はクリックで回答が開いた。
- 幅390でもQは1つ、見出し下線はなく、横スクロールなし。
- `preview-sitemap-lastmod` は変更0。無関係な未追跡画像を除いた状態の `candy-site-state check` は `CHECK=OK`、fingerprint `sha256:a96b551672dc8a0dd55747e6cca38e289950004adc39242296a5f291b3f68414`。

### 決定

- 文字の「Q. 」は見出し3件とFAQPageの質問名から外し、青いQを残す。
- 二重線の上側であるFAQ見出しの下線だけを、この記事の `blog-section is-faq` に限定して消す。他記事のFAQ見出しは変えない。
- `blog.css` の参照キーはSHA-256先頭8桁 `184fd934` にそろえる。本文を変えるのは対象記事だけ。
- 生成管理書は、無関係な未追跡画像を含めない再生成結果をこの変更に含める。

### 対応

- 対象HTML、共通CSS、参照HTML19件のキャッシュキー、生成管理書6件を更新した。

### 結果

- ローカルのFAQ表示と機械検証は完了。本番反映は未実施。

## 現在

- Remaining Work: 対象ファイルだけの commit・push と、本番URLでのFAQ表示確認。
- Next Action: 対象ファイルだけを commit して push し、Actions と本番のFAQを確認する。
