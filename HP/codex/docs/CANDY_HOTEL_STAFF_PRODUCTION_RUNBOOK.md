# CANDY HOTEL 最短制作手順

更新日: 2026-07-15

## 1. 標準実行

対象ホテル指定から生成、検証、Commit、Push、Actions、本番URL確認まで次の1回で実行する。

```powershell
HP\codex\scripts\candy-hotel.cmd publish --input "HP/Text_hotel_data/対象ホテル.txt"
```

未公開の完全な入力が1件だけある場合:

```powershell
HP\codex\scripts\candy-hotel.cmd publish-next
```

本番操作なし:

```powershell
HP\codex\scripts\candy-hotel.cmd build --input "HP/Text_hotel_data/対象ホテル.txt"
HP\codex\scripts\candy-hotel.cmd check --input "HP/Text_hotel_data/対象ホテル.txt"
```

正常系で事前の `build`、`check`、全資料再読、途中質問を追加しない。

## 2. 一括処理

1. 単一実行ロックを取得し、`Text_hotel_data` の必須項目、URL、slug、画像、重複、未完成項目を検査する。
2. 既存3ファイル、共有登録、Git identity、remote、Push dry-runを検査し、依存ファイルのhashを固定する。
3. hotelテンプレートと `template_shop.html` からページ一式を生成し、Text未指定の移動時間・交通費だけを地図座標と最寄り完成areaページから設定する。
4. 完成した追加scene、関連記事ダミー8件、FAQ、料金、店舗、周辺スポットの件数、scene、JSON-LD、画像を検査する。
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
- 移動時間・交通費がText未指定の場合だけ、ホテル地図の座標と店舗別の最寄り完成areaページを使用する。参照元も依存hashへ含める。
- 完成した追加sceneは入力順で保持し、「関連記事」は予約ダミー8件を保持する。
- FAQ、料金行、店舗、周辺スポットは入力件数へ合わせる。
- FAQが0件ならFAQセクションとFAQPageを省略する。料金が全件空、周辺スポットが0件の場合は省略判断が必要なため停止する。
- 既存hotelの登録漏れや旧型構造を新規制作へ混ぜない。

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
- 入力不足、placeholder、危険なURL、画像不足、slug不一致、重複、未完成scene、既存ファイル競合
- `publish-next` で未公開の完全な入力が複数ある
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
