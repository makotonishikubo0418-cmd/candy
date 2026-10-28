# GitHub保存対象と実行方針の確定

- History: [20261028_OPERATION_short-time-job-github-publication.md](../20261028_OPERATION_short-time-job-github-publication.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 保存先は `https://github.com/makotonishikubo0418-cmd/candy.git` のmain。作業開始時の変更は追跡済み12ファイルと未追跡7ファイルで、ステージ済み差分はない。
- 対象HTMLには本文と記事画像2枚への参照があり、準備中マーカーはない。専用検証は `CHECK_OK BODY=EDITED_NOT_REVIEWED`。本文の意味・PC/SP実画面・公開適否は未審査。
- サイトマップ更新日の検証は259 URLで変更0。`git diff --check` は成功。
- HP変更をmainへ通常pushすると本番Actionsが起動する構成。今回の対象はGitHub保存のため、コミットメッセージに `[skip ci]` を使用する。

### 決定

- 最新の本文HTML・画像2枚をそのまま保存し、生成管理情報を実ファイルに同期して含める。
- 本番公開や管理書不備の修正は実施せず、未確認・未解決事項を維持する。

## 現在

- Remaining Work: 生成管理情報の同期検証、ステージ・コミット・push、live SHA・作業ツリー・Actionsの確認、結果記録。
- Next Action: 検証後に対象ファイルをステージする。
