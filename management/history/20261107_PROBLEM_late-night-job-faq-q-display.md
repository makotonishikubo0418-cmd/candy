# 深夜バイトブログFAQのQ重複

- Type: PROBLEM
- Start Date: 2026-11-07

## 目的

公開済みの深夜バイトブログで、FAQのQが二重に見える表示を直し、本番まで反映する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-late-night-job.php`
- 対象HTML: `HP/source/kagoshima-deliveryhealth-blog-late-night-job.html`
- 実施範囲に明示された git add・commit・push と本番公開
- 質問文の「Q. 」除去とFAQPage質問名の同期のみ。CSS、他記事、DBは対象外

## 完了条件

- 表示されるQは青い1つだけで、3件の質問文とFAQPageの質問名から文字の「Q. 」が外れている
- `main` へ push し、本番URLで同じ表示を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 青字のQは `blog.css` の `.faq-question::before`。質問の見出し本文も「Q. 」で始まっていた。
- 対象ページは本番公開済み。作業ツリーには本件以外の未コミット変更がある。
