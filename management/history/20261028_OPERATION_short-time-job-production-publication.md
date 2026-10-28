# 短時間バイトブログの本番公開

- Type: OPERATION
- Start Date: 2026-10-28

## 目的

GitHubへ保存済みの短時間バイトブログを本番へ公開し、記事・画像・公開導線の反映を確認する。

## 対象範囲

`kagoshima-deliveryhealth-blog-short-time-job` の公開PHP・source HTML・dataset、共通dataset、TOP、ブログ一覧、サイトマップ、画像2枚の計9ファイルと公開履歴。既存管理書不備やFAQ検証ツールの修正、DB操作は含めない。

## 完了条件

- 対象9ファイルを正式なGitHub Actions経路で反映する。
- 本番記事・画像・TOP・一覧・サイトマップと、共通入口契約、PC/SP表示、FAQ開閉を検証する。
- 実行結果を履歴へ保存する。

## 初期情報

ユーザーは本番未公開との回答を受けて本番公開を指示した。GitHub保存済みコミットは `2f052ff6da3695ab1cdfe4af7879311934c61eb4`。公開前は記事と画像がHTTP 404で、TOP・一覧・サイトマップにも対象記事がなかった。
