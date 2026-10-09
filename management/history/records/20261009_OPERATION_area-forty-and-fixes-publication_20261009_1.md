# エリア40ページと監査修正10ページの公開開始

- History: [20261009_OPERATION_area-forty-and-fixes-publication.md](../20261009_OPERATION_area-forty-and-fixes-publication.md)
- Record Date: 2026-10-09
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 対象40ページのページ固有3ファイルは120件すべて存在し、真砂本町の新規公開用画像2枚も存在する。
- GitHub Actionsの通常公開は1回125操作、50MiBまでで、保護対象 `HP/index.php` と `HP/.htaccess` は通常公開から除外される。
- 公開自動化のself-test、画像置換関連テスト、生成状態メタデータテスト、女性情報検査、FTP統合テスト、release-checkテストはすべて合格した。

### 決定

- 第1バッチは40ページのページ固有120ファイルと真砂本町画像2枚だけを公開する。
- 第2バッチは共有登録、一覧、トップページ、サイトマップ、既存6ページの修正、Text・管理資料・履歴を公開する。
- 削除される旧真砂本町Textと新しい正式Textは同一案件の分類移動として第2バッチに含める。HPの削除は含めない。

## 現在

- Remaining Work: 2バッチのコミット・Push・Actions、本番ページと修正内容の検証、完了記録
- Next Action: 第1バッチ122件のステージ範囲を固定し、コミット前検査を行う。
