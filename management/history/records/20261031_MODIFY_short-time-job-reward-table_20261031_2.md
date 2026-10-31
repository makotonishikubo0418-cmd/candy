# 保存先と本番表示の相違を再確認

- History: [20261031_MODIFY_short-time-job-reward-table.md](../20261031_MODIFY_short-time-job-reward-table.md)
- Record Date: 2026-10-31
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- ユーザーが変更されていないと確認していた画面は、本番の `https://www.55810.com/kagoshima-deliveryhealth-blog-short-time-job.php`。本番HTTP 200のHTMLには装飾クラスのないtableが残っていた。前回のローカル限定指示に従い未公開であるため、ローカル修正版と本番の表示が異なる。
- 修正版は `C:/Codex/FSG/Candy/HP/source/kagoshima-deliveryhealth-blog-short-time-job.html` に保存され、記事HTML本体のGit差分9行を確認した。SHA-256は `3a7ae8effce1dd4b326b2212e50cc1243b28d1f9e3ce9b6f876535cf8448feb7`。公開PHPからdataset_baseを経由し、同名のsource HTMLを読む構成と一致する。
- 正しいHPルート基準のCSS参照でローカル実表示を再検証した。未経験求人記事と同じblog-table-spaciousが適用され、対象記事のピンクのテーマを維持。実測幅1280px・390pxで文字16px、セル余白18px 24px・14px 12px、横はみ出しなし。PC・スマートフォンの表全体を目視確認した。
- 修正済みの表を元へ逆変換すると変更前の全バイト列と一致。記事HTMLの再修正は不要であり、本文・画像・CTA・リンクを追加変更していない。

### 結果

表示差の原因はローカル修正版と未公開の本番ページを比較していたことと確定した。今回もPush・本番公開は実施しない。

## 現在

- Remaining Work: None（保存先・差分・表示の再確認）
- Next Action: None（本番反映は別途明示指示が必要）
