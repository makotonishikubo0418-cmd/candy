# 修正版配置後の既存画面速度をユーザー確認

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 28
- Status: Waiting for Response

## 記録

### 確認済み事実

- 修正版6ファイルの本番配置後、既存管理画面の「女の子一覧→任意の女の子」の表示確認を依頼し、ユーザーから「以前の速度で表示される」と回答を受領した。
- これはおすすめ機能OFFでの既存画面の体感速度確認であり、機能ON時の読込・保存速度の確認ではない。実測秒数は取得していない。

### 決定・次の確認範囲

- 機能OFFを維持し、本番 `fsg_db` のおすすめ関連6表について、構造・索引・件数・固定したおすすめ用検索の実行計画と読込時間の読取確認を次の対象とする。対象は `girls_data`、`cast_mast`、`girls_images`、`girls_candy_page_content`、`candy_recommendations`、`candy_recommendation_settings`。人物1名・選択対象・一覧の読取経路に限定し、個人情報の内容ではなく件数・計画・時間を報告する。
- この読取確認には具体的なDB操作許可が必要なため、ユーザーへ確認する。データ更新・DDL・再有効化はこの確認に含めず、今回はDBへ接続しない。実行手段は既存の制限付きREAD-ONLY経路と適用ルールを確認し、対応しない計測方法を黙って迂回しない。
- 保存・同時更新等の実DB検証は別途残っており、読取確認だけで運用開始可能とは扱わない。
- HISTORY第9章照合: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementは双方NOT_PRESENT。同日最大27を確認して28を採番。今回Git状態変更・本番接続・DB操作は行っていない。

## 現在

- Remaining Work: おすすめ用の実DB読取・速度確認と、PHP 7.2/MySQL 5.6相当での保存・同時更新等の検証。承認後の再有効化・本番画面確認。HP切替は未実施。
- Next Action: 上記本番6表の読取確認についてユーザーの具体的な許可を受ける。既存画面の速度確認は完了済みとして扱い、同じ確認を繰り返し依頼しない。
