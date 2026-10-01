# 店長おすすめ 専用2表の構造確認完了と移行候補の作成

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-30
- Sequence: 5
- Status: Waiting for Response

## 記録

### 専用2表の構造確認

- ユーザーから本番 `fsg_db.candy_recommendations` のSHOW CREATE TABLE全文を受領した。12列の型・NULL条件・既定値、`girls_id`主キー、`public_order (club_id, selected, sort_order, girls_id)` と `cast_selection (cast_id, club_id, selected)` の索引、InnoDB・utf8mb4_unicode_ciは設計と一致した。
- 文字列列は `pc_image` / `sp_image` がvarchar(68)、`title` がvarchar(255)、`heading` がvarchar(1000)、`body` がtext。いずれもNOT NULLで、前4列の既定値は空文字。`updated_by` はint(11)、`updated_at` はdatetimeでいずれもNOT NULL。人物ID・店舗ID・人物マスターID・順序はint(11)、`selected` はtinyint(4)、順序と選択の既定値は0。
- [前記録4](20260929_INVESTIGATE_manager-recommendation-targets_20260930_4.md)の設定表確認と合わせ、[計画の第3段階](20260929_INVESTIGATE_manager-recommendation-targets_20260930_3.md)の「専用2表の構造確認」は完了した。再作成・ALTERは必要と判断していない。構造確認を実保存・解除処理・表示の正常確認と読み替えない。
- 根拠はユーザー提示のSQL結果であり、本ターンにアシスタントがDBへ接続したものではない。現在の件数・設定値を再取得した結果でもない。

### ローカル移行候補の準備と確認

- [移行候補JSON](../../../../control/codex/project_management/investigation/candy_manager_recommendation/legacy_migration_candidate_20260930.json)を新規作成した。既存の `export_legacy.py` で抽出した12名の公開番号・掲載順・タイトル・見出し・本文・PC/SP画像を、[記録2の人物対応](20260929_INVESTIGATE_manager-recommendation-targets_20260930_2.md)と結び付けた。
- 本ファイルは実行用SQLでも、本番へのインポート用データでもない。`candidate_state=LOCAL_ONLY_NOT_FOR_IMPORT` とし、`updated_by` および各画像の配置先ファイル名は未確定のnull、SQL準備・本番照合・画像配置はfalseとしている。
- ソースHTML・人物対応の根拠ファイル・各画像のSHA-256を保存し、元データの変更を後から検出できる状態にした。掲載名以外の個人情報・認証情報は追加していない。
- 確認結果: 人物12件、PC/SP画像24件、公開番号・人物IDの重複なし、タイトル・見出し・本文の不足なし。テキストの管理画面入力上限と禁止制御文字の確認を通過した。
- 画像はすべてJPEG。寸法は300×498または300×300、合計351,016バイト。24件とも各5MB・各辺6000px・1600万画素の設定上限内。ファイル存在、画像形式・寸法、ハッシュの確認であり、本番の画像処理・アップロード・公開応答の成功証明ではない。
- 保存後にJSONを再読込し、12件と24画像、抽出元との文章・公開番号・掲載順・画像パスの完全一致、すべてのハッシュ一致、重複なし、未実行状態の保持を確認した。画像のコピー・変換・アップロードは行っていない。
- 本番HPの現在のHTMLとの比較は未実施。現在の本番人物状態も再照合していない。候補JSONにこの未確認を明示している。

### 次の確認対象と許可境界

- 次は、画像を置く前の本番保存先・実行環境の読取確認。Controlの [ENVIRONMENT.md 第3節](../../../../control/docs/ENVIRONMENT.md)は将来のSSH操作にも対象・操作・影響を指定した直接許可を要求する。既存のDB読取許可や「本番で進める」指示をサーバー接続全般の許可に拡張しない。
- 今回ユーザーへ確認する範囲は、`firststar.kir.jp` の本件用画像保存先の存在・権限・公開URLとの対応、および本件で使うPHPのバージョン・GD/mysqli・画像アップロード上限の読取調査。ファイル作成・配置・変更・削除、権限変更、DB操作、Git操作、本番切替は含めない。
- ソース上の調査対象は `/home/firststar/public_html/group/control` の本件に必要な設定項目、および `/home/firststar/public_html/group/upfiles/2` とその下の `manager_recommendation`。Candy側管理書のSSH表記 `/firststar/public_html/group/` とソースの `/home/firststar/public_html/group/` の実体対応も未確認として扱う。実体確認なしで片方を正しいと決め付けず、他店舗・他用途を走査しない。
- 公開先設定値は `https://image.can-diary.com/2/manager_recommendation/`。設定値であって、配置済み・公開可能という意味ではない。
- 既存のCandy読取専用WrapperはCandy領域と許可Actionに制限される。Controlや画像領域の調査に流用できると決め付けず、制限を迂回しない。読取の許可後も、利用可能な正規経路の範囲内で確認し、追加設置・設定変更が必要なら実行前に別途扱う。CLI PHPの結果をWeb PHPの結果と同一視しない。

### 実施範囲と保存

- 本ターンの新規ファイルは移行候補JSONと本記録の2件。既存のアプリ・SQL・管理書・履歴・画像を変更せず、DB接続・本番サーバー接続・本番ファイル変更・Git状態変更は行っていない。
- 記録前のCandy照合ではローカルmainとGitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementブランチは双方NOT_PRESENT。同日最大連番4を確認して連番5を使用した。

## 現在

- Remaining Work: 第3段階の本番HPと移行元の比較、画像保存先・実行環境の確認、配置先画像名・更新者ID・初期登録SQL・事前事後確認・復旧手順の確定、解除処理の審査と検証方式の確定。以降の登録・連携確認・表示切替・公開後確認・正式監査・Git公開も未了。
- Next Action: 上記の本番サーバー読取調査についてユーザーの許可を受ける。追加のSQL実行は現時点では依頼しない。許可後、変更を伴わない範囲で保存先と実行環境を確認する。DBの実行担当は引き続きユーザーである。
