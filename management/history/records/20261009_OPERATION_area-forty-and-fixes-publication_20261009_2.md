# エリア40ページと監査修正10ページの本番公開完了

- History: [20261009_OPERATION_area-forty-and-fixes-publication.md](../20261009_OPERATION_area-forty-and-fixes-publication.md)
- Record Date: 2026-10-09
- Sequence: 2
- Status: Completed

## 記録

### 対応

- 第1バッチとして、40ページのページ固有120ファイルと真砂本町画像2枚をコミット `a65571c7825c7fe561e019c3a1af14b183b61b43` でGitHub `main` へPushした。
- 第2バッチとして、共有登録、一覧、トップページ、サイトマップ、既存6ページの監査修正、元Text、管理資料、履歴をコミット `6395aeea29a65bed6b9a6d9fd5655f4bf6810164` でGitHub `main` へPushした。
- 第1バッチは122アップロード・削除0件、第2バッチは10アップロード・削除0件で、いずれも公開上限内であることを事前確認した。
- GitHub Actionsは第1バッチ [run 37888868598](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/37888868598)、第2バッチ [run 37889628271](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/37889628271) が成功した。

### 結果

- 新規公開40ページはHTTP 200、title・canonical・H1のローカル照合が40件すべて合格した。
- 40ページの画像2枚ずつ計80枚は、本番取得データとローカル画像のSHA-256照合が80件すべて一致した。
- エリア一覧、トップページ、サイトマップはHTTP 200で、40ページのURL登録が各40件すべて確認できた。
- 花尾町、皆与志町、田上、田上町、浜町、原良、鷹師、住吉町、大黒町、堀江町は、本番HTTP 200と修正内容を10件すべて確認した。旧交通費表記、重複タイトル、住所href、旧404 URLは対象箇所に残っていない。
- 共通入口契約は `ENTRY_CONTRACT_OK` となり、ルート、canonical、H1、indexability、各HTTP/HTTPSリダイレクト、直接ホストのnoindexを確認した。
- 目視によるPC・スマートフォン表示確認は実施していない。

## 現在

- Remaining Work: None
- Next Action: None
