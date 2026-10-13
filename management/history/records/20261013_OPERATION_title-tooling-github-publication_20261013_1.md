# 残存修正26ファイルのGitHub公開と自動検査の結果

- History: [20261013_OPERATION_title-tooling-github-publication.md](../20261013_OPERATION_title-tooling-github-publication.md)
- Record Date: 2026-10-13
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 公開前のローカルmainとライブGitHub mainは `41878b7e336e3ae7ba345b500a882efa9fa83c75` で一致した。残存変更は18変更・8追加の26ファイルで、依頼対象とその関連資料だった。管理専用ブランチは `NOT_PRESENT`。
- 本番公開に伴い、生成台帳TSVの262行にある検証元情報だけが旧Commitを参照していた。差分列が `verification source` のみであることを確認し、正規生成処理で当該1ファイルを更新した。HP差分は0件。
- 生成管理資料の検査は `CHECK=OK documents=10`。状態指紋は `1262c8e094b804c20d3ad33530a99e1e27f016d3fb8898d4a9979d7e86aa5d66`。SEO表の実データ変更は谷山港のtitleのみで、履歴8件の参照先とサイズも確認した。
- ホテル掲載順を含む28試験の成功結果は既存修正記録を引き継いだ。試験対象コードにその後の変更がないため、本作業では同じ試験を再実行していない。

### 対応

- 対象26ファイルを明示指定してステージし、差分・書式・HP対象0件を確認した。独立した読み取りレビューでも依頼外変更や公開阻害の指摘はなかった。
- Commit `8593242e06cf2f6115f88f5eae9277650512529d`（`Fix title validation and hotel registration order tests`）をmainへPushした。

### 結果

- ライブGitHub mainが上記Commitと一致し、修正26ファイルの公開直後にルートの未コミット・未追跡変更0件を確認した。
- [CANDY Production Deploy #277](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38351478063)は当該Commitで `completed / success`。構文・自己試験・転送統合試験・タイトル内容・SEOメタデータ・公開入口検査の回帰試験を通過した。
- Actionsの実計画は転送0件、削除0件、0バイト。FTPによる本番変更は発生していない。谷山港ページの本番反映結果は先行する公開案件を参照する。
- 原稿、生成・検査処理、ホテル掲載順試験、仕様・生成資料、SEO監査・修正・本番公開履歴までGitHubに保存した。先行記録の「ローカルのみ」はその記録時点の事実として保持した。

## 現在

- Remaining Work: None
- Next Action: None
