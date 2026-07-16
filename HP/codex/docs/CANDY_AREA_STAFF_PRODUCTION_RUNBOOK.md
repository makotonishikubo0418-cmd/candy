# CANDY AREA 最短制作手順

更新日: 2026-07-14

## 1. 標準実行

「次の1ページ作成→アップ→本番URL報告」はこれだけを実行する。

```powershell
HP\codex\scripts\candy-area.cmd publish-next
```

対象指定:

```powershell
HP\codex\scripts\candy-area.cmd publish --input "HP/Text_area_data/対象.txt"
```

本番操作なし:

```powershell
HP\codex\scripts\candy-area.cmd build --input "HP/Text_area_data/対象.txt"
HP\codex\scripts\candy-area.cmd check --input "HP/Text_area_data/対象.txt"
```

必要画像が存在しない場合は、上記コマンドの前に `CANDY_AREA_IMAGE_CREATION_SPEC.md` を読む。画像元の保存・加工・商用公開条件と帰属表示を確認できる場合だけ画像を制作する。確認できない場合はSTOPとし、ページ生成・公開へ進まない。

入力全体の例外調査時だけ実行する。

```powershell
HP\codex\scripts\candy-area.cmd audit-inputs
HP\codex\scripts\candy-area.cmd audit-inputs --render
```

正常系で事前の `build`、`check`、全資料再読、途中質問を追加しない。


## 1.5 作成順番と新規制作対象ゲート

作成対象は手で選ばない。次の順番で、専用ゲートが `NEW_PAGE_TARGET_OK=<slug>` を出した1件だけを対象にする。

1. `HP/Text_area_data` 直下にあるtxtをファイル名昇順で確認する。
2. 最新の `HP/Text_area_data/分類_*/01_間違い無し` をファイル名昇順で確認する。
3. 既存公開PHP、source HTML、dataset PHP、`dataset_base.php`、sitemapに既存ファイルまたは既存登録があるslugは自動で除外する。
4. area一覧に対象slugのリンクが1件あることを必須にする。area一覧に同一地域名で別slugのリンクがある場合は `同一地域・別slug` として自動除外する。
5. 画像2枚がないslugは自動で除外する。
6. 最初に通過した1件だけを制作する。

`01_間違い無し` はtxt内容の分類であり、新規ページとして制作可能という意味ではない。

```powershell
HP\codex\scripts\candy-area.cmd target-next
```

分類フォルダ内の候補を実際に制作する場合は、1件だけ通常位置へ戻す。

```powershell
HP\codex\scripts\candy-area.cmd target-next --restore
```

指定対象を確認する場合:

```powershell
HP\codex\scripts\candy-area.cmd target-check --input "HP/Text_area_data/対象.txt"
```

`NEW_PAGE_TARGET_OK=<slug>` が出ない対象で `publish` してはいけない。既存ファイル、既存登録、同一地域・別slug、旧slug、類似slugが出たら、制作へ進まず候補選定からやり直す。
## 2. 一括処理
`publish-next` は次を連続実行する。

1. キュー先頭の `READY_CANDIDATE` を選ぶ。
2. Text、slug、画像、既存ファイル、共有登録、Git/remoteを検査する。
3. テンプレートからページ一式を生成する。
4. 静的検査とstage許可表を確認する。
5. 対象だけを1回Commit・1回Pushする。
6. Actionsと本番HTTPを確認する。
7. 本番URL、Commit URL、Actions URLを出力する。

公開後の記録用 `.md` 更新、資料専用Commit、2回目Pushは行わない。公開状態はGitHub、Actions、本番HTTPが正本である。

## 3. 生成ルール

- 入力: `HP/Text_area_data/対象.txt`
- HTML: `HP/source/template_kagoshima-deliveryhealth-area.html`
- 店舗: `HP/source/template_shop.html`
- Textの店舗、移動時間、交通費を最優先する。
- 店舗未指定時は、既存ページで使用頻度の低い組合せを使う。
- 値未指定時は、地図座標と近隣完成ページの同店舗設定を使う。
- 店舗、記事、ホテル、スポット、電話番号は入力件数へ合わせる。
- 元データにない値、画像、URLを推測しない。
- 関連記事は、実リンク設定までテンプレートの予約ダミー8件を残す。
- 既知例外は専用ツールへ追加し、ページごとの即席処理を作らない。

作成・更新対象:

```text
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php
HP/includefile/dataset_base.php
HP/source/area.html
HP/sitemap.xml
HP/codex/docs/CANDY_AREA_105_PAGE_QUEUE.md の対象1行
```

`\\192.168.1.3\disk1\FSG_SEO\candy\ページ作成用.md` は更新しない。

## 4. 検証

専用ツールが次を検査する。成功時に手作業で重ねない。

- 必須入力、canonical、slug、画像2枚
- 3ファイルと共有登録
- 店舗順、移動時間、交通費
- scene、ID、FAQ、JSON-LD
- 関連記事予約ダミー8件または実リンク
- area一覧、sitemap、内部リンク
- PHP lint、JSON、画像、差分
- stage対象、削除・rename・許可外変更
- remote、Push、Actions、本番ページ・画像・一覧・sitemap・転送

ローカルPHP CLIがない場合は `PHP_LINT=UNAVAILABLE` とする。本番公開はActionsのFTP前lint成功を必須とする。

## 5. キュー

- キューは制作順と重複防止にだけ使う。
- 1 slug 1行とし、別のバッチ履歴を作らない。
- `publish-next` は `READY_CANDIDATE` だけを選ぶ。
- build後は対象行を `LOCAL_COMPLETE` または `IN_PROGRESS` にする。
- 公開結果をキューへ後追い記録しない。
- 実際の公開状態はCommit、Actions、本番HTTPで確認する。

## 6. STOP

- main以外、remote不一致、fast-forward不可、競合
- 対象または共有ファイルの既存変更を保持できない
- 入力不足、画像不足、slug不一致、同名ファイル競合
- 旧slug・類似slug・誤記の自動置換が必要
- 未知店舗、共有登録重複、area一覧・sitemap不整合
- PHP、JSON、stage許可表、Actions、本番HTTP失敗
- 削除、rename、DB、秘密値、`index.php`公開切替が必要

STOP時は停止位置、完了済み状態、未実施状態、再実行コマンドを出力する。別slugへ自動置換しない。

## 7. 報告

正常時は次だけを報告する。

```text
結論: 本番反映済み
作成ページ:
本番URL:
Commit URL:
Actions URL:
未確認:
```

ブラウザ表示を実施していない場合は未確認と書く。通常1ページは指示から本番URL報告まで5分以内を目標とする。
