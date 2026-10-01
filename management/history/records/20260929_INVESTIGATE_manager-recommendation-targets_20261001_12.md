# 店長おすすめ写真24枚の本番配置成功とDB実行SQLの準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 12
- Status: Waiting for Response

## 記録

### 写真の配置・公開確認成功

- ユーザーが `Invoke-ImagePlacement.ps1 -Run` の実行結果を提示した。`CANDY_SSH_EXIT_CODE=0`、`created=24 / reused=0 / verified=24 / bytes=351016`、`CANDY_IMAGE_PLACEMENT_OK` を確認した。
- `CANDY_PUBLIC_IMAGES_VERIFIED=1/24` から `24/24`、`CANDY_IMAGE_DEPLOYMENT_OK=24` を確認した。承諾済み24枚の新規配置と、新しい公開URLからのHTTP200・サイズ・SHA256照合が完了した。SSH保存先・Web側保存先・設定上の画像URLの対応も、この24点について確認できた。
- `DB_NOT_ACCESSED=1; FEATURE_FLAGS_NOT_CHANGED=1`。DB登録とHP/管理画面の切替は行っていない。冒頭のMIB警告は残るが、今回の配置・公開照合を妨げていない。MIB設定の変更や写真転送の再実行は不要。

### DB実行用SQLの作成

- [承諾記録11](20260929_INVESTIGATE_manager-recommendation-targets_20261001_11.md)の範囲を継続し、[build_release_sql.py](../../../../control/codex/project_management/investigation/candy_manager_recommendation/build_release_sql.py)と[release_sql](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/release_sql/)を追加した。アシスタントはDBに接続・実行していない。
- 実行ファイルは [020_021_install_and_import.sql](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/release_sql/020_021_install_and_import.sql)。新規自動解除トリガー6件の追加と既存12名の初期登録を同じSQL欄から実行できるよう連結した。元となる `020_install_triggers.sql` と `021_initial_import.sql` は処理別の正本として保持するが、連結版を実行した後に重ねて実行しない。
- 既存の専用2表は作り直さない。`girls_data` / `cast_mast` の列や既存プロフィールデータを書き換えるSQLは含まない。既存トリガーの置換・DROP、画像変更、機能フラグ変更、migration_ready=1も含まない。
- 初期登録者は確認済み管理者ID1を使用する。12名のID・公開番号・cast対応・状態・趣味・公開番号一意性を実行時に再判定し、対象データは配置済み画像名と9月30日の固定12名の内容を引き継ぐ。文字列をUTF-8のhexリテラルとして出力し、SQLとして解釈される文字列連結をしない。

### 自動解除処理と初期登録の条件

- トリガーのINSERTはAFTERへ変更し、自動採番後のIDを使用する。UPDATEとDELETEはBEFORE。OLD/NEW両方のID、CANDYへ入る/出る所属変更、人物対応・公開番号・公開状態変更、削除、ID再利用、人物側のCANDY関連行が欠けた状態でも専用登録が残る場合を扱う。他店だけの変更でCANDYとの参照関係がない場合は専用設定・登録を更新しない。
- トリガーは専用設定行のrevision更新→専用おすすめ行の選択解除の順。設定行が存在しない/想定schemaでない場合はSIGNALで異常を通知する。選択を1へ戻す処理はない。解除はCANDYの対応行だけで、画像・本文は削除しない。
- 初期登録は旧MyISAM表の読取→専用設定行のロックの順。読取前revisionとロック後revisionが違う、人物条件が12件でない、既存登録が0件でない、管理者が無効、自動解除6件の対象・タイミング・空白正規化後の本文SHA256が一致しない等の場合はINSERT対象を0件にする。
- INSERT後に12行すべてのID・選択・順序・写真名・文字列・登録者・登録時刻を照合し、総件数12と設定revision更新1件も確認する。全条件成立時だけCOMMITし、不成立時はROLLBACKする。MySQL 5.6のPREPARE対応に合わせ、分岐は `COMMIT` / `DO 0` とし、その後に通常のROLLBACKを置く。COMMIT成功後のROLLBACKは既に確定した登録を取り消さない。参考: [MySQL 5.6公式マニュアルのPrepared statements対応文](https://downloads.mysql.com/docs/refman-5.6-en.pdf)。
- 正常な初期登録の結果は `CANDY_INITIAL_IMPORT_COMMITTED`、inserted_rows12、verified_rows12、matching_triggers6、migration_ready0。登録時刻とexpected_import_revisionを復旧判断用に保存する。不成立は `CANDY_INITIAL_IMPORT_NOT_APPLIED`。エラーや結果不明時は再実行せず、部分適用状態を確認する。
- DDLとデータ登録は同じトランザクションではない。初期登録がROLLBACKされても追加済みトリガーは残る。この点をSQLコメントとユーザーへの手順で明示する。

### 復旧・試験・確認の限界

- [019_prechange_state.json](../../../../control/codex/project_management/investigation/candy_manager_recommendation/migration_20261001/release_sql/019_prechange_state.json)に、ユーザー提示の設定行・おすすめ全体0件・対象2表の既存トリガー0件を保全した。全DBダンプではない。別途ユーザーが取得済みと報告したバックアップの正確な保存場所・復元試験は未確認で、復旧可能性を保証する根拠にはしない。ケース証跡は自動削除せず保持する。
- `022_postcheck.sql` は読取専用の独立した事後照合。`023_rollback_import.REVIEW.sql` は登録時刻・revision・承認値の初期値をNULL/0としており、そのままでは削除しない。復旧時に12件全体が初期値から未変更で、設定も未変更・ready0と一致した場合だけ対象12件を取り消せる。写真とトリガーは残す。
- `024_rollback_triggers.REVIEW.sql` は例外時の手動復旧用で通常実行しない。初期登録0件・ready0・両アプリOFF、今回追加した名前と本文の一致を確認した後だけ使用する。新トリガーが既存更新を阻害した場合の撤去用であり、通常のコード切戻しで解除保護を無条件に消す手順ではない。旧プロフィール表全体を古いダンプから戻すことはしない。
- [test_release_sql.py](../../../../control/codex/project_management/investigation/candy_manager_recommendation/test_release_sql.py)14件成功。SQL条件を読むオフラインモデルで、非公開→再公開、削除/再登録、自動採番後のID、所属の双方向変更とNULL、OLD/NEW両ID、人物/公開番号変更、通常の名前編集、無関係な他店、孤立した専用登録の解除、書込範囲・ロック順、初期登録のガード、UTF-8文字列復元、定義ハッシュ、生成一致、復旧の初期拒否を確認した。
- これらはMySQLでの実行試験ではない。実DBのDDL受理・ロック待ち・実トリガー実行・エラー時復旧・アプリ結合は未確認。MyISAMの元更新はROLLBACKできず、InnoDB側の解除と常に一体で戻せるものではない。通常経路外の明示トランザクションのROLLBACKや障害時まで「絶対に再掲載されない」「他機能への影響なし」と保証しない。これらは公開前の実DB/運用確認事項として残す。実在人物を試験目的で非公開・削除する操作は行っていない。
- 本ターンは新しい準備スクリプト・試験・SQL・本記録だけを作成し、旧DRAFT・元の候補JSON・アプリコード・機能フラグは変更していない。記録前のCandy mainはLocal/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementは双方NOT_PRESENT、同日最大連番11を確認し12を使用。Git状態変更なし。

## 現在

- Remaining Work: ユーザーによる承諾済みDB処理の実行結果受領・照合。実DB解除/ロック/失敗時の確認、HP/管理画面の配置と結合、別承諾の公開切替・公開後検証。
- Next Action: 取得済みバックアップを保持した状態で、phpMyAdminのfsg_dbに020_021_install_and_import.sql全体を一度だけ実行し、結果を提示してもらう。エラーや不成立時は再実行せず結果を調査する。旧002_create.sqlや011_seed.DRAFT.sqlは実行しない。
