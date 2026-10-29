# CANDY WORK STYLEの2ボタンCTAを公開

- History: [20261029_OPERATION_short-time-job-html-publication.md](../20261029_OPERATION_short-time-job-html-publication.md)
- Record Date: 2026-10-29
- Sequence: 3
- Status: Completed

## 記録

### 結果

- ユーザーの明示許可に基づき、公開コミット `dcedda69bada1faae7ee3799cf898bebca234ac6` をmainへPushした。
- 待機設備ブログを参考にしたCANDY WORK STYLEの2ボタンCTAと、指定文「1日3時間からの短時間勤務もOK！気になることはお気軽に。」を公開した。記事の他部分・画像・SEO・CSSは変更していない。
- [Actions 40802094842](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40802094842) 成功。deployジョブ40秒、HTML1件を転送・SHA-256照合、削除0件。事前検査と共通入口検証は同runで成功した。
- [本番記事](https://www.55810.com/kagoshima-deliveryhealth-blog-short-time-job.php) はHTTP 200。24,414バイトが公開コミットと完全一致。SHA-256: `4eaf5cb350b2e425301b27608e2fea7c3cf8f75a01f06fcbddfd0487bd1c753b`。
- 本番PC画面で文章・2ボタンを確認し、両リンクのhref・CSSクラス・target・relが指定どおりであることを確認。PC幅1600・SP幅434のDOM実測で横はみ出しなし。SPの見出しと2ボタンは画面内に収まり、ボタン同士の重なりなし。SPの画像による表示確認は直前のローカル構造検証時の結果を利用し、今回の本番ではDOM寸法を確認した。
- ローカルHTML構造検証、CTA外のバイト不変確認、未変更の画像・公開導線・FAQ処理の検証結果を再利用。一時画面サイズ指定は解除した。

## 現在

- Remaining Work: None
- Next Action: None
