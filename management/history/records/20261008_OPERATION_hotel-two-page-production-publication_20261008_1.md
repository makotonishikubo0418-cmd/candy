# シェラトン鹿児島・シルクイン鹿児島のGitHub本番公開完了

- History: [20261008_OPERATION_hotel-two-page-production-publication.md](../20261008_OPERATION_hotel-two-page-production-publication.md)
- Record Date: 2026-10-08
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- シェラトン鹿児島の画像コミットは `8e1461f407b2b2bec6475106559538993c7fbf25`、Actionsは `37723437682`。ページコミットは `aaa50dd91075cb430d9b603185ac1a26657bbbbe`、Actionsは `37723626763`。
- シルクイン鹿児島の画像コミットは `351a38a3473fad4d3020329106d25d5e88aea433`、Actionsは `37723766839`。ページコミットは `90adc361430e16adc18a1bf37370c0f02a54f4d9`、Actionsは `37723950536`。
- 各Actionsは成功し、対象画像4枚は本番でHTTP 200、`image/jpeg`、1000x750、ローカル公開用コピーとのSHA-256一致を確認した。

### 対応

- 各ホテルを画像資産単位、ページ単位の順で独立してCommit、Push、Actions、本番検証まで完了した。
- ページ公開ツールが内容不変の生成TSV2件を変更必須と判定して停止したため、予期しないステージ対象がないこと、専用ページ検査、サイト状態検査、`git diff --check` の合格を確認後、対象ごとに手動継続した。
- 本番ページ、title、canonical、H1、JSON-LD、画像2枚、ホテル一覧、トップページ、サイトマップを実URLで検証した。

### 結果

- `https://www.55810.com/kagoshima-deliveryhealth-hotel-sheratonkagoshima.php` はHTTP 200で公開され、対象名・主要構造・画像・一覧・トップ・サイトマップ検査に合格した。
- `https://www.55810.com/kagoshima-deliveryhealth-hotel-silkinnkagoshima.php` はHTTP 200で公開され、対象名・主要構造・画像・一覧・トップ・サイトマップ検査に合格した。
- 画像ライフサイクルの確認済み到達点は `DEPLOYED_ASSET`。PC・モバイル画面確認は未実行のため `PUBLISHED` とは記録しない。

## 現在

- Remaining Work: None
- Next Action: None
