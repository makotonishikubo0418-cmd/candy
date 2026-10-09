# エリア9ページの本番公開完了

- History: [20261009_CREATE_area-nine-page-publication.md](../20261009_CREATE_area-nine-page-publication.md)
- Record Date: 2026-10-09
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- ページコミットは `58561918cfac38e729a88629526e06502edb994f`。Push後のGitHub `main` と一致した。
- 本番計画はアップロード49件、削除0件、名前変更0件、3,799,500 bytesだった。保護対象の `HP/index.php` と `HP/.htaccess` は変更していない。
- GitHub Actions run `37874023508` は成功した。
- 9ページすべて、本番HTTP、title、canonical、H1、店舗、画像2枚、エリア一覧、トップページ、サイトマップの検査に合格した。
- 共通入口はroot 200、公開index可能、`index.php`・HTTP・non-wwwの正規URL転送、直接ホストのnoindexを確認した。
- 本番画像18枚は、対応するローカル公開用画像とSHA-256が一致した。

### 対応

- 9ページをGitHub `main` へPushし、通常のGitHub Actionsで本番へ公開した。
- Actions成功後に9ページと共通入口を本番URLで再検証した。

### 結果

- 小松原、上谷口町、上福元町、上本町、上竜尾町、清水町、川上町、川田町、平川町の本番公開が完了した。
- PC・スマートフォンの実ブラウザによる目視確認は未実施。それ以外の対象検査に未完了はない。

## 現在

- Remaining Work: None
- Next Action: None
