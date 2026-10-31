# 公開対象の確定

- History: [20261031_OPERATION_beginner-job-production-publication.md](../20261031_OPERATION_beginner-job-production-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 作業前mainはローカル・GitHubともに `7b8a250dc70ee91fe88941e65bc23fba34302456`。ローカル変更は対象画像3件の名前変更だけ。
- 画像名はHTMLの本文・OGP・Article参照と一致する `.jpg` へ変更済み。

### 決定

- 未公開のブログ一式と画像名変更を同時に反映するため、本番管理書第7.1節に従い、比較元 `59ed477644458453c7d93d6bcd48e57bebb53926` から新コミットまでの手動preview・deployを実施する。
- 保存コミットには `[skip ci]` を付け、自動デプロイとの重複を防ぐ。公開対象は7コード・登録ファイルと画像3件。管理書は本番転送対象外。

## 現在

- Remaining Work: GitHub保存、preview、deploy、反映確認、結果記録。
- Next Action: 確定したSHAのpreviewを起動する。
