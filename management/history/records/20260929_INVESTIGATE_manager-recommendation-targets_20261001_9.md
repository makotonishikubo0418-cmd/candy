# 店長おすすめ既存12名の本番照合とローカル移行一式の準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 9
- Status: Waiting for Response

## 記録

- 指示は「次は何するの早く終わらせろ」。[前記録](20260929_INVESTIGATE_manager-recommendation-targets_20261001_8.md)で成功した環境診断を繰り返さず、[工程計画](20260929_INVESTIGATE_manager-recommendation-targets_20260930_3.md)第3段階の初期移行準備を進めた。
- Candy AGENTS第4節およびControl AGENTS第3節に従い、本番登録・画像配置・表示切替・Git状態変更は実施していない。DB操作は引き続きユーザーが担当する。

### 確認済み・作成済み

- `https://www.55810.com/` をHTTPで取得し、おすすめ12カードの公開番号、掲載順、PC/SP画像パス、タイトル、見出し、本文が既存ローカル抽出結果および9月30日の候補JSONと一致することを確認した。HP全体の一致ではない。
- 既存公開画像24点をHTTP取得し、それぞれが候補JSONのSHA256・サイズおよびローカル画像のバイト列と一致した。合計351,016 bytes。人物IDは[9月30日記録2](20260929_INVESTIGATE_manager-recommendation-targets_20260930_2.md)に基づき、名前から再推定していない。本日のDB再照合は未実施。
- [準備ツール](../../../../control/codex/project_management/investigation/candy_manager_recommendation/prepare_legacy_migration.py)を追加し、[migration_20261001](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/)に24画像を別名コピーした。各ファイル名はランダム64桁hex＋`.jpg`。再エンコードや元画像の変更はしていない。既存出力フォルダーは上書きしない。
- [manifest.json](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/manifest.json)にHTTP照合日時、取得HTML/対象区間のハッシュ、12名の内容・ID・順序、24画像の元URL・配置名・ハッシュ・サイズを保存した。状態は `PREPARED_NOT_DEPLOYED_SQL_DRAFT`。
- [010_precheck.sql](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/010_precheck.sql)はSELECTのみ。対象サーバー、専用設定・件数、実装が許可する管理者候補のID/表示名/rank、12名の本人対応・状態・公開番号重複・趣味登録状態、関連トリガー一覧をまとめて確認する。ログインID・パスワード・住所・連絡先は取得しない。管理者IDはControlログイン処理が `club_mast.id` を `clubdata.id` に格納するコードに基づく。複数候補を無断で選ばない。
- `011_seed.DRAFT.sql` と `012_postcheck.sql` を作成した。前者は12行を一括INSERTする案で、登録許可フラグ0・登録者NULL・末尾ROLLBACKを初期値とし、本番実行用ではない。既存登録の上書き、DDL、migration_readyの変更、COMMITは含まない。旧MyISAM表の参照を専用設定行のロック前に置く。人物数12、既存登録0、設定schema1/ready0/revision0、管理者1件等を条件とするが、停止した書込処理・解除保護・実DB検証が前提であり、これだけで同時更新安全性を証明しない。
- `013_rollback.DRAFT.sql` は同一接続・COMMIT前のROLLBACKだけを用意した。COMMIT済み登録の自動削除・復元SQLは未準備で、復旧全体の完成とは扱わない。接続断で結果不明の場合は両側OFFを維持し、事後確認SQLで調査する。画像・登録データを自動削除しない。
- [オフライン試験](../../../../control/codex/project_management/investigation/candy_manager_recommendation/test_prepare_legacy_migration.py)9件が成功。抽出互換、マーカー不正、文章・画像差異で出力を作らないこと、24画像のハッシュ、SQL中の文字列60項目のUTF-8復元、初期実行拒否・ROLLBACK、ロック後に旧表を参照しないこと、読取SQLの列制限、SQL再生成一致、既存出力の上書き拒否を検証した。SQLをMySQLで実行した試験ではない。

### 未実施・境界

- 本番DB接続・登録・DDL・トリガー設置・画像配置・新画像URL取得・HP/管理側の配置・有効化は未実施。既存アプリソースと旧候補JSONは変更していない。
- SSH配置先 `/firststar/public_html/group/upfiles/2/manager_recommendation/`、Web保存先 `/home/firststar/public_html/group/upfiles/2/manager_recommendation/`、設定上の画像URL `https://image.can-diary.com/2/manager_recommendation/` のうち、配置後の実ファイルとURLの対応はまだ未確認。
- 非公開・削除解除トリガー案の再審査・実DB確認、初期登録者の確定、変更時点のバックアップ・COMMIT後復旧、具体的な配置・登録・検証・切替の承諾は残る。今回の公開HTML一致はDB掲載状態の保証ではない。
- 記録前照合でCandy mainはLocal/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチは双方NOT_PRESENT。同日最大連番8を確認し9を使用した。Git状態を変更する操作はしていない。

## 現在

- Remaining Work: 010_precheckの結果受領と登録者確定、解除処理の審査・検証方法、変更時点のバックアップ・COMMIT後復旧の確定。その後、具体的な承諾範囲に従い画像配置・初期登録・両側連携確認・表示切替・公開後確認。SQL下書きをそのまま本番実行しない。
- Next Action: ユーザーにphpMyAdminのfsg_dbで010_precheck.sql全体を実行して結果を提示してもらう。これは読取のみ。管理者候補が複数なら実際の登録者を確認する。環境診断、表の再作成、旧候補の再作成には戻らない。
