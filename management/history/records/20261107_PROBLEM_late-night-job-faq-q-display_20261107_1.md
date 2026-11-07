# 深夜バイトブログFAQのQ重複修正と本番公開

- History: [`20261107_PROBLEM_late-night-job-faq-q-display.md`](../20261107_PROBLEM_late-night-job-faq-q-display.md)
- Record Date: 2026-11-07
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- FAQ見出し3件とFAQPageの質問名から文字の「Q. 」を外した。青いQは `.faq-question::before` のまま。
- 変更ファイルは `HP/source/kagoshima-deliveryhealth-blog-late-night-job.html` のみ。

### 決定

- 身バレ対策ブログの同種修正と同じ扱いとし、本文の「Q. 」だけを削除する。

### 対応

- ローカルHTMLを修正した。GitHub反映と本番公開を実施する。

## 現在

- Remaining Work: commit・push と本番確認
- Next Action: 本件ファイルだけを commit・push し、本番HTMLを確認する
