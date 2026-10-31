# 最新ローカル状態とGitHub保存対象の確定

- History: [20261031_OPERATION_fun-conversation-github-publication.md](../20261031_OPERATION_fun-conversation-github-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- mainのHEADとライブGitHub mainは `87d6c442b416f608e1336a3c5dcca25b39dfbe62` で一致。開始時のルート全体は変更12件・未追跡6件で、ステージ済み変更はない。
- source HTMLは前回のテンプレートから本文入りへ更新され、準備中マーカーはない。本文・OGP・Article.imageが参照する `kagoshima-deliveryhealth-blog-fun-conversation_1.jpg` はローカルに存在する。
- 現在HTMLのSHA-256は `6a02b75ddaebade228c892656ee587cee4aa3a1c88a05b4230ab8c9208979646`、画像は `39887dd1ec8cb09b442f381f9fe01ef074941bceffaa1ad4579251edeaa9e66e`。前回のテンプレート検証結果は現在HTMLへ適用しない。
- 生成管理情報は前回のテンプレート作成時点のもの。今回のGitHub保存では再生成しない。
- 本番デプロイworkflowはmainへのHP変更Pushを契機とする。他のworkflowは手動起動。

### 決定

- 確認した18ファイルを現状のまま保存し、本案件の履歴を加える。
- `[skip ci]` 付きでコミット・Pushする。本番公開・DB操作は行わず、記事の公開適否や表示検証済みとは扱わない。

## 現在

- Remaining Work: ステージ・コミット・Push、ライブGitHubとの一致確認、結果記録。
- Next Action: 対象パスを明示してステージし、保存内容を検証してGitHubへ送る。
