# 新人とベテラン比較ブログのGitHub保存と本番公開

- Type: OPERATION
- Start Date: 2026-11-03

## 目的

`newcomer-veteran` ブログのローカル完成一式をGitHubへ保存し、通常Pushの自動デプロイで本番公開まで完了する。

## 対象範囲

記事PHP・HTML・dataset・共通登録・一覧・TOP・サイトマップ・記事画像2点、関連履歴・生成管理情報。無関係な `.github` 改修や他案件履歴は含めない。DB操作は含まない。

## 完了条件

- 対象をmainへCommit・Pushし、ライブGitHub SHAと一致する。
- 自動デプロイ成功と本番URLの公開確認を完了する。
- 結果を履歴に記録する。

## 初期情報

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-newcomer-veteran.php`
- 先行: テンプレート作成と完成HTML作成の両案件がローカル完了済み。
- 実行: [GIT_MANAGEMENT.md](../GIT_MANAGEMENT.md) と [CANDY_PRODUCTION_MIGRATION_MASTER.md](../specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) 第7.1節の通常Push自動デプロイ。
