# 千日町・船津町エリアページのGitHub本番公開完了

- History: [20260929_OPERATION_area-two-page-production-publication.md](../20260929_OPERATION_area-two-page-production-publication.md)
- Record Date: 2026-09-29
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 公開前提となる周辺エリア定義とローカル生成履歴を `b61831cefa7fce738748a2cd6a85727f38272f23` でGitHubへ反映し、ページ公開開始時にローカルHEADと `origin/main` の一致を確認した。
- 千日町のページコミットは `8bc7c8e7c53bf87aa0f0d9e0e3dc13cfac008082`、Actionsは `36533284638`。船津町のページコミットは `6e9a40591c8b1ce15ba0ea4cbc54941760d854a7`、Actionsは `36533637435`。両Actionsは成功した。
- 各ページコミットは対象エリアの公開用画像2枚、ページ固有3ファイル、共有登録、キュー、生成管理資料を含む。

### 対応

- 千日町と船津町をそれぞれ独立して、生成、専用検査、サイト状態検査、配備計画、Commit、Push、Actions、本番検証まで実行した。
- 公開ツールが内容不変の生成TSV2件を変更必須と判定して停止したため、予期しないステージ対象がないこと、専用ページ検査、サイト状態検査、配備計画を確認後、同じGitHub Actions経路で対象ごとに手動継続した。
- 本番検証器が旧 `/source/` をトップページとして参照して停止したため、正規トップページ `/` を直接確認した。共通公開入口契約、本番ページ、canonical、H1、店舗4件、JSON-LD、画像2枚、エリア一覧、トップページ、サイトマップを実URLで検証した。

### 結果

- `https://www.55810.com/kagoshima-deliveryhealth-area-sennichicho.php` はHTTP 200で公開され、主要構造、ローカルとの画像SHA-256一致、エリア一覧、トップページ、サイトマップの検査に合格した。
- `https://www.55810.com/kagoshima-deliveryhealth-area-funatsucho.php` はHTTP 200で公開され、主要構造、ローカルとの画像SHA-256一致、エリア一覧、トップページ、サイトマップの検査に合格した。
- PC・モバイル画面確認は未実行。

## 現在

- Remaining Work: None
- Next Action: None
