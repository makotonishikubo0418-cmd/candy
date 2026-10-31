# 短時間バイト記事の報酬表の公開完了

- History: [20261031_OPERATION_short-time-job-reward-table-publication.md](../20261031_OPERATION_short-time-job-reward-table-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 対応と結果

- ローカルとライブGitHubのmainが `35d7331b7e72bd9a9a4784670688dc1e119beb64` で一致することを確認し、報酬表のHTML・関連生成管理情報・履歴11ファイルをコミット `4a8ec31a4e873c2ec84e02719d48a86fe11ff258` としてmainへPushした。
- 最終差分は表用クラス・セル文字指定・見出し属性・金額3件の太字に限定。元の表へ逆変換すると変更前の記事全文とバイト単位で一致。対象PHPの構文検査も成功した。既存の未追跡画像は追加・転送していない。
- [Actions 41187789998](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41187789998)で事前検査・公開が成功。本番転送は `HP/source/kagoshima-deliveryhealth-blog-short-time-job.html` 1ファイル、24,888バイト、削除・名前変更0件。PLAN_TOKENは `8f7906ee6ecff1c7626d0abc5e1a646f6bca05c28ab38903d6b268382a5a9052`。
- 2026-10-31 18:13 JSTに `SUCCESS: deployed and SHA256-verified 1 file(s); deleted 0 obsolete file(s)`、続いて `ENTRY_CONTRACT_OK` を確認した。転送処理の成功は一時バックアップの後処理完了を含む。公開入口・リダイレクト・直接ホストのnoindex検査は同runで成功した。
- [本番記事](https://www.55810.com/kagoshima-deliveryhealth-blog-short-time-job.php)はHTTP 200、X-Robots-Tagなし。取得したheadとmain全体が改行正規化後のローカルHTMLと一致し、本文・金額・画像参照・CTA・リンク・SEO情報・JSON-LDの整合を確認した。
- 本番PC・スマートフォンの表全体を目視確認。実測viewport幅1422px・434pxで全セル16px、金額3件はfont-weight 700、淡いピンクの見出し背景、セル余白18px 24px・14px 12px。ページ・セル内とも横はみ出しなし。
- FAQの既存SEO検査警告は対象外として維持した。公開結果の履歴は後続の管理ファイルのみの `[skip ci]` コミットで保存し、本番コードを追加変更しない。

## 現在

- Remaining Work: None
- Next Action: None
