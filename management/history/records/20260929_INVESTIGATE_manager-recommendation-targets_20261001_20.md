# 既存の女の子編集画面での長時間待ち報告と読み取り調査

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 20
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザーは、既存の女の子一覧から女の子をクリックするとタブが読み込み状態のままになり、移動先の表示に非常に長い時間がかかると報告した。管理画面トップへのアクセスも同様に待たされ、その後に開いたとの追加報告があった。「女の子一覧より女の子を押したらそうなる」と再確認された。新しいおすすめ設定一覧の操作と混同しない。
- 添付画面のURLは `site/shopmaster2.php?club=2`。左メニューには「店長おすすめの女の子設定」が見える。設定転送スクリプトの成功出力・バックアップ名は依然未受領。
- ローカルの `site/shopmaster2.html:324` では既存一覧から `shopmaster3.php?cast=…&club=…&girl=…` に移動する。`site/shopmaster3_candy.html:861` に今回追加したプロフィール用のincludeがある。
- `site/candy_recommendation_profile.inc.php:11` は1名の編集でも `cr_snapshot()` を同期実行する。`site/candy_recommendation_service.php:107` 以降の同関数はCANDY全員分を対象とし、人物ごとに `girls_images` の `MAX(i2.id)` を取得する相関サブクエリーを含む。その後に指定人物だけを取り出す。個人編集時にも全員分の取得を行う実装を確認した。
- 既存セッション開始処理は `includefile/setting_session.php:21` の `session_start()`。編集画面の入口には明示的な `session_write_close()` がないことを確認した。ただし本番の実際のセッション保存方式やロック待ちは測定していない。
- 本ターンはローカルソース・管理書の読み取りと履歴追加だけを実施。本番アクセスの再現、DB接続/SQL、設定変更、アプリ修正、Git状態変更は行っていない。連続クリック・再読み込みを避けるよう伝えた。

### 推論・未確認

- 新規の全員分取得処理が遅延し、同じログインの別リクエストも待っている可能性がある。本番の実行中SQL、待機状態、実行計画、各処理の所要時間は未確認であり、原因確定とはしない。
- 復旧優先の切り分け案は管理画面側のおすすめ機能だけを一時OFFにすること。設定 `site/candy_recommendation_config.php` 1ファイルの変更・明示stage・ローカルCommit・バックアップ付き本番反映（Pushなし）の承諾を求める。DB設定・12件の登録内容・画像・HPは変更しない。既存編集画面の追加フォームと新メニューを停止して表示時間を比較する。
- 承諾前に一時OFF化や無断のDB設定巻き戻しは実施しない。疑わしいSQLの繰り返し実行や、実在人物の非公開・削除による試験も行わない。
- HISTORY.md第9章の確認ではCandy Local/GitHub mainがともに `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementは双方NOT_PRESENT。同日最大連番19を確認して20を使用した。

## 現在

- Remaining Work: 遅延の切り分け・復旧・原因確定と必要修正。管理画面の表示/保存/解除の実動作確認。HP側の本番反映・公開切替・公開後確認。未PushのControl Commitの扱い。
- Next Action: 管理設定1ファイルだけを一時OFFにする変更・ローカルCommit・本番反映への承諾を受け、既存の女の子編集画面の応答時間を比較する。DB・画像・HPは保持する。
