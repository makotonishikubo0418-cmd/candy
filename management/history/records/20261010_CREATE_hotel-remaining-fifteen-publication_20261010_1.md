# 残りホテル15ページの作成と本番公開完了

- History: [20261010_CREATE_hotel-remaining-fifteen-publication.md](../20261010_CREATE_hotel-remaining-fifteen-publication.md)
- Record Date: 2026-10-10
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 対象は、ビジネスホテル鴨池プラザ、ホテル鹿児島ヒルズ、マリンパレスかごしま、リッチモンドホテル鹿児島天文館、リッチモンドホテル鹿児島金生町、レム鹿児島、変なホテルプレミア鹿児島 天文館、天然温泉 霧桜の湯 ドーミーイン鹿児島、天然温泉 ホテル自治会館（市町村自治会館）、東横INN鹿児島中央駅東口、東横INN鹿児島中央駅西口、東横INN鹿児島天文館1、東横INN鹿児島天文館2、鹿児島アイネ、鹿児島サンロイヤルホテルだった。
- 各対象は入力検査、ページ生成、専用ページ検査、PHP構文検査、生成状態検査、デプロイ計画検査に合格した。
- 公開前に画像30枚の本番バイトとローカルバイトが一致することを確認した。
- 各ページのActionsは成功し、本番ページはHTTP 200だった。
- 最終一括検査で、全15ページの対象名、canonical、JSON-LD、画像2枚、ホテル一覧登録、トップページ登録、サイトマップ登録を確認した。画像30枚は本番とローカルのバイトが一致した。
- 本番サイトマップの `loc` は251件で、対象15ページをすべて含んでいた。
- 入力監査結果は全72件のうち、作成済みまたは登録あり71件、管理用txt 1件、作成可能0件だった。

### 対応

- 各ホテルを独立したページCommit、公開計画検査、Push、Actions、本番検証の順で公開した。
- 既知の自動公開ツールの生成TSVステージ判定不整合を避けるため、各回について変更対象15件の固定照合、未ステージ変更0件、GitHub `main` の期待SHA一致を確認し、同じ独立トランザクションを手動実行した。

### 結果

- ビジネスホテル鴨池プラザ: Commit `8b936c1968270239bd794dc1f801486729e4e8ee`、Actions `38037424878`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelkamoikeplaza.php`
- ホテル鹿児島ヒルズ: Commit `c4e93e05a1f058eb9e0a69d89ba8feb7d4914ce5`、Actions `38037563860`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hotelkagoshimahills.php`
- マリンパレスかごしま: Commit `d3535d2febf9358eccfeff786eb651d7371b9f86`、Actions `38037693470`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-marinepalacekagoshima.php`
- リッチモンドホテル鹿児島天文館: Commit `6574c1a343964434556f307d5f96924be415cb28`、Actions `38037832781`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-richmondhotelkagoshimatenmonkan.php`
- リッチモンドホテル鹿児島金生町: Commit `770f54db26f80452e626004f9f7da5db14982077`、Actions `38037959492`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-richmondhotelkagoshimakinseicho.php`
- レム鹿児島: Commit `617867712033cc6db3bacc84a1beafc9c3442bd7`、Actions `38038095130`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-remmkagoshima.php`
- 変なホテルプレミア鹿児島 天文館: Commit `247537d66291c479cee52748079f842958dc31fe`、Actions `38038225665`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-hennnahotelpremierkagoshimatenmonkan.php`
- 天然温泉 霧桜の湯 ドーミーイン鹿児島: Commit `06754d2fbc33a2be967a55957c2516960f0bd626`、Actions `38038371305`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-dormyinnkagoshima.php`
- 天然温泉 ホテル自治会館（市町村自治会館）: Commit `91cb7c95a5f4770f30ad5b56e4e15e01398714ee`、Actions `38038504212`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-naturalhotspringhoteljichikaikan.php`
- 東横INN鹿児島中央駅東口: Commit `abfb599d951072015231b1d90e40fa7704c074b0`、Actions `38038649605`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-toyokoinnkagoshimachuoekihigashiguchi.php`
- 東横INN鹿児島中央駅西口: Commit `ad1c435595b11f3ba440cada363b6e397a1b92ef`、Actions `38038784657`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-toyokoinnkagoshimachuostationnishi.php`
- 東横INN鹿児島天文館1: Commit `a5f6f1c054da8ab493ec2f9f7a04394365327d4c`、Actions `38038913619`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-toyokoinnkagoshimatemmonkanno1.php`
- 東横INN鹿児島天文館2: Commit `e55a72cfb81d5db073c25137a6ba58449160fdca`、Actions `38039043390`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-toyokoinnkagoshimatemmonkanno2.php`
- 鹿児島アイネ: Commit `60df73d1009f14058afeffd012328308febee3f2`、Actions `38039182974`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-kagoshimaaine.php`
- 鹿児島サンロイヤルホテル: Commit `7a666db05562d001bb8ab223f834b0d8eb7a9bf4`、Actions `38039323580`、`https://www.55810.com/kagoshima-deliveryhealth-hotel-kagoshimasunroyalhotel.php`
- 画像ライフサイクルの確認済み到達点は `DEPLOYED_ASSET`。PC・モバイル画面確認は未実行。

## 現在

- Remaining Work: None
- Next Action: None
