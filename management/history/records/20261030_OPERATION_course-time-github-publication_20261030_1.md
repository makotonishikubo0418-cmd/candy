# 最新ローカル状態とGitHub保存対象の確定

- History: [20261030_OPERATION_course-time-github-publication.md](../20261030_OPERATION_course-time-github-publication.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 現在ブランチmainとライブGitHub mainは `1b52e2555680a933d5232d8ac8652478b266f615` で一致。ルート全体の差分は追跡済み変更12件・未追跡8件。
- 最新source HTMLには本文・画像・FAQが追加されている。titleとog:titleの末尾には `｜鹿児島Candy` が付加され、元の指定タイトルによるテンプレートcheckはこの2項目の不一致でSTOPした。H1は元の指定タイトルを保持している。
- 生成管理情報と前回の検証結果はテンプレート準備時点のものであり、現在の本文追加後の検証済み状態を示すものではない。
- mainへのHP変更Pushは自動本番デプロイの起動条件に該当する。

### 決定

- 今回はGitHub保存のみの依頼であるため、最新ローカル20ファイルを変更せず保存し、本案件の履歴を追加する。
- `[skip ci]` を付けてコミットし、本番デプロイは起動しない。本文・SEOの修正や公開可否の判定は本案件では行わない。

## 現在

- Remaining Work: コミット・Push、ライブGitHubとの一致確認、結果記録。
- Next Action: 対象差分を確認してGitHub mainへ保存する。
