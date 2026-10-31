# CTA誘導文の本番反映と確認完了

- History: [20261031_OPERATION_beginner-job-cta-guidance-publication.md](../20261031_OPERATION_beginner-job-cta-guidance-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 結果

- ユーザー保存HTMLについて、CTA誘導文の文字列だけを元に戻すと開始時HEADと全文一致することを機械比較した。タグ・クラス・CSS参照・NEXT STEP・2ボタンの文言とURL・その他本文は変更なし。HTMLの再編集は行っていない。
- 対象HTMLだけを明示してステージし、差分とgit diff --cached --checkを確認。a4a37893c57a048eebdc4e95ed564ec5d1706386をmainへコミット・Pushし、ライブGitHub SHA一致と作業ツリーのクリーン状態を確認した。
- [自動デプロイ41177838061](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41177838061) が成功。比較元e33d453c204bcc3807406e1cff2d859fa6925084から記事HTML1ファイル・30,038バイトを公開、削除なし。
- PLAN_TOKEN: d1fabc6a39660d17eb186d625dd487a20bc27c7b5042effea21d178adeba10f0。実転送VERIFIED 1/1とSUCCESS: deployed and SHA256-verified 1 file(s); deleted 0 obsolete file(s)を確認。既存処理の成功条件に基づきcleanup完了を確認した。
- 当該runのENTRY_CONTRACT_OKによりroot 200、canonical、公開indexability、index.php・HTTP・non-wwwリダイレクト、直接ホストnoindexを確認した。
- 本番記事はHTTP 200、mainとJSON-LDは公開対象と完全一致。新しい誘導文「お仕事内容や働き方が気になった方は、Candyの求人情報もチェックしてみてください☺️」を確認した。
- 本番PC実測1367×900・スマートフォン434×938で描画とDOMを確認。誘導文はPC2行・スマートフォン3行で枠内に収まり、ページ・誘導文・ボタンの横はみ出しなし。#547FA1・既存カード・2列のボタン配置を保持した。
- 「バニラの求人を見る」のhttps://kyusyu-okinawa.qzin.jp/candy98/?v=officialと、「ガールズヘブンの求人を見る」のhttps://www.girlsheaven-job.net/kagoshima/ma-200/sa-352/newcandy/を本番DOMで確認した。
- CSS・ボタン・リンク先・画像の再修正、生成管理情報の再生成、手動デプロイ、DB操作は行っていない。

## 現在

- Remaining Work: None
- Next Action: None
