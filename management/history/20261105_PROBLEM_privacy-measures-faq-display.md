# 身バレ対策ブログFAQのQ重複と上線

- Type: PROBLEM
- Start Date: 2026-11-05

## 目的

公開済みの身バレ対策ブログで、FAQのQが二重に見え、最初の質問の上の線が二重になっている表示を直し、本番まで反映する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-privacy-measures.php`
- 対象HTML: `HP/source/kagoshima-deliveryhealth-blog-privacy-measures.html`
- 共通 `HP/css/blog.css` のうち、この記事のFAQ見出しに限る上線の削除と、変更に伴う参照HTMLのキャッシュキー
- 生成管理書の再生成
- 実施範囲に明示された git add・commit・push と本番公開
- 質問文、回答文、他記事の本文、記事別CSS、DBは対象外

## 完了条件

- 表示されるQは青い1つだけで、3件の質問文とFAQPageの質問名から文字の「Q. 」が外れている
- FAQ見出しの直下にあった二重の上線が1本になっている
- PCとスマートフォンで対象FAQの表示を確認している
- `main` へ push し、本番URLで同じ表示を確認している
- 対象外の未コミット変更をこの変更に含めていない

## 初期情報

- 青字のQは `blog.css` の `.faq-question::before`。質問の見出し本文も「Q. 」で始まっていた。
- FAQ見出しの下線と `.blog-faq` の上線が、間隔を空けて2本見えていた。
- 対象ページは本番公開済み。作業ツリーには本件以外の未コミット変更がある。
