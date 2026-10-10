# 次のホテル10ページの作成と本番公開完了

- History: [20261010_CREATE_hotel-next-ten-page-publication.md](../20261010_CREATE_hotel-next-ten-page-publication.md)
- Record Date: 2026-10-10
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 自動選定順は、ホテルニューニシノ、ホテルマイステイズ鹿児島天文館、ホテルマイステイズ鹿児島天文館2番館、ホテルユニオン、ホテルリブマックスBUDGET鹿児島、ホテルリブマックスBUDGET鹿児島天文館、ホテル・レクストン鹿児島、ホテル・レクストン鹿児島アネックス、ホテル吹上荘、ホテル法華クラブ鹿児島だった。
- 各対象はページ生成、専用ページ検査、PHP構文検査、生成状態検査、デプロイ計画検査に合格した。
- 各ページのActionsは成功し、本番ページはHTTP 200だった。
- 本番で全10ページの対象名とJSON-LD、画像2枚、ホテル一覧登録、トップページ登録、サイトマップ登録を確認した。全30 URLのHTTP応答は200だった。
- 本番サイトマップの `loc` は236件で、対象10ページをすべて含んでいた。

### 対応

- 各ホテルを独立したページCommit、Push、Actions、本番検証の順で公開した。
- 公開ツールが内容不変の生成TSV2件をステージ必須と判定して最初の対象で停止したため、前回と同じくステージ対象15件の固定照合、未ステージ変更0件、専用検査合格、GitHub `main` の期待SHA一致を対象ごとに確認してから、独立トランザクションを手動で継続した。
- ホテル吹上荘は、元Textの `subtitle_` と「ローソン 照国神社前店」が1行に連結されていたため生成前検証で停止した。内容は変更せず、ラベルと値を2行に分離する書式修正だけを行い、再検査合格後に公開した。

### 結果

- ホテルニューニシノ: Commit `9640f57dddec0f32aaf81ecc78f4ba1a46253ddc`、Actions `38034981768`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelnewnishino.php`
- ホテルマイステイズ鹿児島天文館: Commit `0d961ac3ed8c3d1b95251589a5fec62197feb723`、Actions `38035154512`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelmystayskagoshimatenmonkan.php`
- ホテルマイステイズ鹿児島天文館2番館: Commit `e8332df6f514db8013c8403d01f4f5eb2b0ce9d0`、Actions `38035285627`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelmystayskagoshimatenmonkanannex.php`
- ホテルユニオン: Commit `f3584a7383e1f64feda14fc3135d6fb467b89b39`、Actions `38035412410`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelunion.php`
- ホテルリブマックスBUDGET鹿児島: Commit `493270c87b7719101f47243a067106158b0b40ee`、Actions `38035557256`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotellivemaxbudgetkagoshima.php`
- ホテルリブマックスBUDGET鹿児島天文館: Commit `92ce0df9937f76c7dc3aa983b1a627b4ab9a474c`、Actions `38035697139`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotellivemaxbudgetkagoshimatenmonkan.php`
- ホテル・レクストン鹿児島: Commit `0feb45b3435aa1b114cfbcdd0e23339d0c8b8055`、Actions `38035842903`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotellextonkagoshima.php`
- ホテル・レクストン鹿児島アネックス: Commit `0b97034fd18cef71e2e87314b499ed85223061b2`、Actions `38035980877`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotellextonkagoshimaannex.php`
- ホテル吹上荘: Commit `d89f9a90426f3552b8e15622e870b79d7eac0a63`、Actions `38036253090`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelfukiageso.php`
- ホテル法華クラブ鹿児島: Commit `33ff19a174c9ac7383bc5496e9e602948b911e04`、Actions `38036417518`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelhokkeclubkagoshima.php`
- 画像ライフサイクルの確認済み到達点は `DEPLOYED_ASSET`。PC・モバイル画面確認は未実行。

## 現在

- Remaining Work: None
- Next Action: None
