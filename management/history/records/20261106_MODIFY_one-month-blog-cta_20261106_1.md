# 1ヶ月ブログCTAの修正と本番反映

- History: [`20261106_MODIFY_one-month-blog-cta.md`](../20261106_MODIFY_one-month-blog-cta.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 既存ブログ `beginner-job`、`dormitory`、`nightwork-style` のCTAは同一で、左が「バニラの求人を見る」（`https://kyusyu-okinawa.qzin.jp/candy98/?v=official`）、右が「ガールズヘブンの求人を見る」（`https://www.girlsheaven-job.net/kagoshima/ma-200/sa-352/newcandy/`）。どちらも新しいタブで開き、`noopener noreferrer` を付ける。
- 修正コミットは `356f9738f579f5d3f6707805e46bb1f150d9c666`。記事HTMLの差分はCTAの誘導文と2ボタンのみ。
- サイトマップの `lastmod` は changed=0。生成状態は fingerprint `sha256:76845f2cbe6987a8d1015417c2b98c49ad0cb492fb279230fe9f3064cf2da59a` で check OK。台帳TSVの差分は検証元コミット列のみ。
- ローカルと本番のPC幅では横スクロールがなく、NEXT STEP、紫の角丸CTA、横並びボタン各 362×60 を確認した。
- 幅390でも横スクロールはなく、ボタンは横並び各 147×56、文字はボタン幅内、CTA右端は372だった。
- 本番デプロイは success。Actions: https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42182058658
- 適用ログは対象HTML 1件をSHA-256検証し、obsolete削除は0件。入口検査は `ENTRY_CONTRACT_OK`。公開URLは HTTP 200 で新しいCTA文言を含む。旧誘導文は本番表示にない。

### 決定

- 作業ツリーに既にあった本件以外の変更は、修正コミットと履歴コミットへ含めない。

### 対応

- 記事末尾CTAだけを変更し、`main` へ push して本番反映を確認した。

### 結果

- https://www.55810.com/kagoshima-deliveryhealth-blog-one-month.php のCTAを指定内容へ更新した。
- 本番デプロイ run 42182058658 は success。

## 現在

- Remaining Work: None
- Next Action: None
