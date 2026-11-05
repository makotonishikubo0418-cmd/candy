# 身バレ対策ブログFAQ修正の本番確認

- History: [`20261105_PROBLEM_privacy-measures-faq-display.md`](../20261105_PROBLEM_privacy-measures-faq-display.md)
- Record Date: 2026-11-05
- Sequence: 2
- Status: Completed

## 記録

### 結果

- `42e6a8a` を `main` へ push した。[自動デプロイ42001024032](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42001024032) は成功。deploy は 2分23秒。
- 本番 `https://www.55810.com/kagoshima-deliveryhealth-blog-privacy-measures.php` は HTTP 200。`blog.css?v=184fd934` を参照し、CSS内にこの記事だけの見出し下線削除がある。
- 見出し本文とFAQPageの質問名に「Q. 」はない。表示Qは `::before` の1つで、色は `rgb(84, 127, 161)`。FAQ見出しの `border-bottom` は `0px`。
- PC幅1614と幅390で、最初の質問の上の線は1本。横スクロールなし。
- 対象外の未コミット変更は含めていない。

## 現在

- Remaining Work: None
- Next Action: None
