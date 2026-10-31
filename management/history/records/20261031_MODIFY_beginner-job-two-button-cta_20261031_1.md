# 2ボタンCTAのローカル修正と検証完了

- History: [20261031_MODIFY_beginner-job-two-button-cta.md](../20261031_MODIFY_beginner-job-two-button-cta.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 結果

- 「バニラの求人を見る」: https://kyusyu-okinawa.qzin.jp/candy98/?v=official
- 「ガールズヘブンの求人を見る」: https://www.girlsheaven-job.net/kagoshima/ma-200/sa-352/newcandy/
- 両URLは既存TOPの正式なCandy求人導線から取得し、HTTP 200とキャンディ求人ページのtitleを確認した。URLの推測・新規組み立てはしていない。
- course-time本番HTMLの既存CTA構造を確認し、同じprimary・secondary構成を採用した。カード・NEXT STEP・誘導文・配色は保持し、1ボタン用の9行の上書きCSSだけを削除した。独自の新規デザインは追加していない。
- PC viewport1230×810では2列、ボタン幅362.2px、間隔14px、文字16px、最低高さ60px。スマートフォン390×844では参考記事と同じ2列、幅139.8px、間隔10px、文字13px、最低高さ56px。カードの角丸・余白も参考記事と一致し、PC／スマートフォンで目視・計測を完了。ページ・ボタンの横はみ出しなし。
- #547FA1のテーマを維持。本文・画像・報酬表・関連記事は、CTA変更とCSSキーを逆変換した全文比較で開始時コミットと一致した。他12参照HTMLはCSSキー以外に変更なし。
- CSS仕様第2.2節に従い全13参照HTMLの更新キーをe7caf179に統一。サイトマップ更新日プレビューは変更0件。生成管理情報6ファイルを更新し10文書CHECK=OK、対象記事structure=COMPLETE・seo=OK・images=OK・issues=NONEを確認。正規の更新日・サイズ特例も保持した。
- git diff --check成功。ステージ・コミット・Push・本番公開・DB操作は実施していない。

## 現在

- Remaining Work: None（依頼されたローカル修正の範囲）
- Next Action: None。Push・公開は別途指示がある場合のみ実施する。
