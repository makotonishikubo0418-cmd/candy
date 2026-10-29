# 秋冬ブログの本番公開

- Type: OPERATION
- Start Date: 2026-10-29

## 目的

最新ローカル情報を管理書とともにmainへ保存し、本番公開対象を反映する。

## 対象範囲

GitHub保存済みの秋冬ブログのPHP・HTML・dataset・共通PHP登録、記事画像、TOP・ブログ一覧、サイトマップの8ファイルと本案件の履歴。既存の公開ツールを使用し、無関係な修正やスクリプトの追加・改修は行わない。

## 完了条件

- 対象のGitHub保存、本番アップ、必要なHTTP・表示確認、履歴保存を完了する。
- 5分を超える外部待ち・失敗があれば、完了扱いせず現在状態・原因・残作業を報告する。

## 初期情報

- ユーザーはステージ・コミット・Push・デプロイを明示的に許可した。
- 開始時点のmainはローカル・GitHubともに `2ac4cefd5d64535dab1435650a37847262f0e054`、作業ツリーはクリーン。
- 未公開分は `437ee2f60bacda646cf7e3bd95dab61e02d978e2` から上記SHAまで。直近成功デプロイ `dcedda69bada1faae7ee3799cf898bebca234ac6` とbefore SHAのHP差分はない。
- [本番管理書](../specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) 第7.1節に従い、保存済み未公開分を既存の手動preview・deployで反映する。
