# CANDY HOTEL 最短制作手順

更新日: 2026-07-16

## 1. 標準実行

「次の1ページ作成→アップ→本番URL報告」はこれだけを実行する。`publish-next` の内部で対象ゲートを通し、`NEW_HOTEL_TARGET_OK` が出た1件だけを公開する。

```powershell
HP\codex\scripts\candy-hotel.cmd publish-next
```

指定対象を確認する場合:

```powershell
HP\codex\scripts\candy-hotel.cmd target-check --input "HP/Text_hotel_data/対象ホテル.txt"
```

入力全体を分類する場合:

```powershell
HP\codex\scripts\candy-hotel.cmd audit-inputs
HP\codex\scripts\candy-hotel.cmd audit-inputs --write-report
HP\codex\scripts\candy-hotel.cmd audit-existing
```

本番操作なし:

```powershell
HP\codex\scripts\candy-hotel.cmd build --input "HP/Text_hotel_data/対象ホテル.txt"
HP\codex\scripts\candy-hotel.cmd check --input "HP/Text_hotel_data/対象ホテル.txt"
```

正常系で事前の `build`、`check`、全資料再読、途中質問を追加しない。


## 1.1 作成依頼時の不足確認

ユーザーが「ホテルページ作る」「ホテル1P作成」「アップまで」と指示したら、ページ生成やpublishの前に不足確認を行う。

```powershell
HP\codex\scripts\candy-hotel.cmd target-next
```

`NEW_HOTEL_TARGET_OK` が出ない場合は、作成へ進まず次を先に報告する。

- 作成可能件数
- 画像なし件数
- 入力不備件数
- 入力未追跡件数
- 既存ページまたは共有登録の有無
- 最初に止まる候補名と不足理由

不足がある状態で、実行後にユーザーへ確認しない。実行前に不足物を報告して停止する。
## 1.5 作成順番と新規制作対象ゲート

作成対象は手で選ばない。次の順番で、専用ゲートが `NEW_HOTEL_TARGET_OK=<slug>` を出した1件だけを対象にする。

1. `HP/Text_hotel_data` 直下にあるtxtをファイル名昇順で確認する。
2. 管理用txt、入力不備、画像なし、作成済み、共有登録あり、未追跡、重複slugを除外する。
3. 既存公開PHP、source HTML、dataset PHP、`dataset_base.php`、hotel一覧、sitemapに既存ファイルまたは既存登録があるslugは除外する。
4. 画像2枚がないslugは除外する。
5. 入力txtがGitのHEADに存在しない場合は除外する。
6. 停止理由は主分類だけでなく BLOCKER_COUNTS_JSON でも確認する。画像なしと未追跡など、複数理由を隠さない。
7. 最初に通過した1件だけを制作する。

`NEW_HOTEL_TARGET_OK=<slug>` が出ない対象で `publish` してはいけない。画像なし、入力不備、既存登録、未追跡、未登録店舗が出たら、制作へ進まず候補整理からやり直す。

## 2. 一括処理

1. 単一実行ロックを取得し、`Text_hotel_data` の必須項目、URL、slug、画像、重複、未完成項目を検査する。
2. 既存3ファイル、共有登録、Git identity、remote、Push dry-runを検査し、依存ファイルのhashを固定する。
3. hotelテンプレートと `template_shop.html` からページ一式を生成し、Text未指定の移動時間・交通費だけを地図座標と最寄り完成areaページから設定する。
4. 入力に存在する全ブロックの順序と件数、関連記事ダミー8件、scene、JSON-LD、画像を検査する。
5. `dataset_base.php`、hotel一覧、sitemapへ対象だけを登録し、出力6ファイルのhashを固定する。
6. stage許可表を確認し、対象だけを1回Commit・1回Pushする。Commit直後停止時は内容一致を確認して既存Commitを採用する。
7. ActionsのFTP前PHP lintとdeployをAPIで追跡する。
8. 本番ページ、h1、JSON-LD、画像、hotel一覧、sitemap、転送をHTTP確認しURLを出力する。

公開後の記録用Commit、2回目Push、入力Textの移動は行わない。

## 3. 入力と生成単位

- 入力: `HP/Text_hotel_data/対象ホテル.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-hotel.html`
- 店舗: `HP/source/template_shop.html`
- 元Textを最優先し、不足値、画像、URL、ホテル情報を推測しない。
- 画像不足時は `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` を確認する。画像元の保存、加工、商用公開条件を確認できなければ制作しない。
- 入力分類は `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` を正本にする。
- 移動時間・交通費がText未指定の場合だけ、ホテル地図の座標と店舗別の最寄り完成areaページを使用する。参照元も依存hashへ含める。
- 通常記事sceneと既知セクションは入力順で保持し、旧optionは独立ブロックとして扱う。
- 店舗、通常記事scene、FAQ、基本情報の任意行、料金行、アクセス、周辺スポットは入力の完成ブロック数へ合わせる。上限件数を設けない。
- 通常記事scene、FAQ、料金、アクセス、周辺スポットが0件なら、質問せずセクション全体を省略する。
- 項目の途中入力はSTOPする。空欄、placeholder、空コンテナを生成しない。
- 固定数は関連記事の予約ダミー8件だけとする。hotelの店舗は1件以上を必要とする。
- 既存hotelの登録漏れや旧型IDを新規制作へ混ぜない。

変更単位:

```text
HP/kagoshima-deliveryhealth-hotel-<slug>.php
HP/source/kagoshima-deliveryhealth-hotel-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-hotel-<slug>.php
HP/includefile/dataset_base.php
HP/source/hotel.html
HP/sitemap.xml
```

## 4. STOP

- main以外、remote不一致、fast-forward不可、競合
- Git identity・Push dry-run失敗、別のhotel公開処理が実行中
- 必須入力不足、placeholder、危険なURL、画像不足、slug不一致、重複、部分入力ブロック、既存ファイル競合
- `target-next` で `NEW_HOTEL_TARGET_OK` が出ない
- 未知店舗、移動時間・交通費が未指定かつホテル座標または近隣完成areaページから参照不能
- dataset_base、hotel一覧、sitemapの対象登録重複、hotel一覧の予約枠不足
- 削除、rename、許可外ファイル、25ファイル超のdeploy
- 依存・出力hash、PHP、JSON、stage許可表、Actions、本番HTTP検証失敗

STOP時は停止工程、完了済み状態、未実施状態、出力された `RECOVERY_COMMAND` を報告する。

## 5. 報告

```text
結論: 本番反映済み
作成ページ:
本番URL:
Commit URL:
Actions URL:
未確認:
```

ブラウザ目視を行っていない場合は未確認と書く。通常1ページは指示から本番URL報告まで5分以内を目標とする。
