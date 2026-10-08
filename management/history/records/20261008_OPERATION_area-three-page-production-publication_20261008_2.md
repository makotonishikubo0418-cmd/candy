# 草牟田・草牟田町・大黒町エリアページのGitHub本番公開完了

- History: [20261008_OPERATION_area-three-page-production-publication.md](../20261008_OPERATION_area-three-page-production-publication.md)
- Record Date: 2026-10-08
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- 3エリア一式の公開コミットは `111d3c06d6bb11515c053558d73273479502f401`、GitHub Actionsは `37719563111` で成功した。
- 配備計画はアップロード19件、削除0件、1,531,251 bytesで、`HP/index.php`、`HP/.htaccess`、DB関連ファイルを含まなかった。
- Actions実ログで、配備対象PHP 7ファイルの構文検査合格、19ファイルのSHA-256検証済み配備、削除0件、共通公開入口検査合格を確認した。

### 対応

- 対象3ページの専用検査、周辺エリアリンク検査、生成管理資料検査、公開ツール自己検査、配備・リリース統合テスト、配備ドライランを実行した。
- 固定した34パスだけをステージして公開コミットを作成し、GitHub `main` へPushした。
- Actions成功後、本番3ページ、画像6枚、エリア一覧、正規トップページ、サイトマップ、共通公開入口を実URLで検証した。

### 結果

- `https://www.55810.com/kagoshima-deliveryhealth-area-soumuta.php` はHTTP 200で公開され、地域名、canonical、H1、店舗4件、JSON-LD、画像2枚のローカルSHA-256一致、エリア一覧、トップページ、サイトマップの検査に合格した。
- `https://www.55810.com/kagoshima-deliveryhealth-area-soumutacho.php` はHTTP 200で公開され、地域名、canonical、H1、店舗4件、JSON-LD、画像2枚のローカルSHA-256一致、エリア一覧、トップページ、サイトマップの検査に合格した。
- `https://www.55810.com/kagoshima-deliveryhealth-area-daikokucho.php` はHTTP 200で公開され、地域名、canonical、H1、店舗4件、JSON-LD、画像2枚のローカルSHA-256一致、エリア一覧、トップページ、サイトマップの検査に合格した。
- 共通公開入口は、ルート200、正規URL、公開indexability、`index.php`・HTTP・non-wwwの正規リダイレクト、直接ホストのnoindex検査に合格した。
- 画像ライフサイクルの確認済み到達点は `DEPLOYED_ASSET`。PC・モバイル画面確認は未実行のため `PUBLISHED` とは記録しない。
- DB操作は実行していない。

## 現在

- Remaining Work: None
- Next Action: None
