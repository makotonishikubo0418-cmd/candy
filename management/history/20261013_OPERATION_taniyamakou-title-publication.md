# 谷山港ページの修正タイトルの本番公開

- Type: OPERATION
- Start Date: 2026-10-13

## 目的

ローカルで修正済みの谷山港ページのタイトルを本番へ反映し、公開応答で修正結果を確認する。

## 対象範囲

`HP/source/kagoshima-deliveryhealth-area-taniyamakou.html` と `HP/sitemap.xml` の2ファイルのステージ、Commit、mainへのPush、通常のGitHub Actionsによる本番公開と検証。原稿・生成処理・検査処理・ホテル掲載順試験・管理資料の変更は今回のGit公開対象に含めない。

## 完了条件

承認対象2ファイルだけをCommit・Pushし、対応するActionsが成功すること。転送内容のSHA-256照合、本番ページのHTTP・title・og:title・SEO検査、サイトマップの対象日付と内容、共通公開入口の検査結果を確認すること。

## 初期情報

[タイトル混入のローカル修正](20261013_PROBLEM_taniyamakou-title-contamination.md)と[ホテル掲載順試験の修正](20261013_PROBLEM_hotel-registration-order-test.md)を完了後、ユーザーが本番公開を指示した。2ファイルのステージ・Commit・mainへのPushを具体的に確認した際の「あっぷしろ」により、その操作の許可を取得した。
