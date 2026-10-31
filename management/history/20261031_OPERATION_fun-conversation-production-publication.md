# 会話が楽しい女の子ブログの本番公開

- Type: OPERATION
- Start Date: 2026-10-31

## 目的

GitHub保存済みの最新ブログ一式を本番へ反映し、必要な公開確認と履歴保存を完了する。

## 対象範囲

`kagoshima-deliveryhealth-blog-fun-conversation` のHTML・公開PHP・dataset・画像1件、共通PHP登録、ブログ一覧、TOP、サイトマップの8ファイルと本案件の履歴。保護対象index.php・.htaccess、DB、無関係な修正は含まない。

## 完了条件

- 確定SHAのpreviewとdeployが成功し、転送後ハッシュ・後処理・公開入口契約を確認する。
- 公開記事、一覧・TOP・サイトマップ、必要な画像・PC/SP表示を確認する。
- 履歴をmainへコミット・Pushし、ライブGitHubとの一致と作業ツリーの状態を確認する。

## 初期情報

- ユーザーは必要なステージ・コミット・Push・デプロイを許可し、5分以内の完了、超過時は現在状態・原因・残作業の報告を指示した。
- 開始時はmainのローカル・GitHubが `9fa2f3c3e8704906fd5442b313f14501fcb0594b` で一致し、作業ツリーはクリーン。
- [GitHub保存案件](20261031_OPERATION_fun-conversation-github-publication.md)の8本番ファイルが未公開。比較元 `87d6c442b416f608e1336a3c5dcca25b39dfbe62` のHP内容は、直近成功デプロイ `4a8ec31a4e873c2ec84e02719d48a86fe11ff258` と同一。
- [本番公開管理書](../specs/CANDY_PRODUCTION_MIGRATION_MASTER.md)第7.1節に従い、GitHub保存済み未公開範囲の手動preview・deployを使用する。
