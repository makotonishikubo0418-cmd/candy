# CANDY AREA 105 PAGE QUEUE

更新日: 2026-07-13
用途: 未作成areaページの制作順・状態・引継ぎ管理

## 1. 集計基準

`Text_area_data`のBackupを除くcanonical 167件を、実ファイルと公開用画像で照合しました。

| 区分 | 件数 |
|---|---:|
| source HTMLが存在するため今回の新規制作対象外 | 57 |
| source HTMLなし・情報ファイルあり・正式slug画像2枚あり | 105 |
| └ 3ファイルすべて未作成の通常新規候補 | 96 |
| └ 公開PHP・dataset PHPあり、source HTMLだけ欠落する既存不整合 | 9 |
| source HTMLなし・情報ファイルあり・正式slug画像不足 | 5 |
| 合計 | 167 |

105件は入力内容の完全性まで保証した完成データではありません。通常新規候補96件は制作前確認へ進める `READY_CANDIDATE` です。既存不整合9件は通常新規へ混ぜず、既存修復として影響範囲を確認し、ユーザー承認後に処理します。各バッチでtxt全文を確認し、不足があれば `BLOCKED` に変更します。

## 2. 運用ルール

- 初回は2件、確認後は5件ずつ処理する
- 上から順番を基本とする。順番変更は記録する
- `LOCAL_COMPLETE`は制作runbookの全完了条件を満たした場合だけ使用する
- 状態変更時は担当、日付、Commit hashまたは停止理由を記録する
- 複数Codexで同時に別バッチを処理しない

状態欄: `READY_CANDIDATE / IN_PROGRESS / LOCAL_COMPLETE / COMMITTED / PUSHED / PUBLISHED / BLOCKED`

## 3. 制作候補105件

| No. | 地域名 | slug | 状態 | 記録 |
|---:|---|---|---|---|
| 1 | 花尾町 | `hanaomachi` | PUBLISHED | Codex / 2026-07-14 / Commit `44df27b` / Actions `29289499915` / 本番HTTP・ブラウザ確認済み |
| 2 | 皆与志町 | `minayoshicho` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / 本番HTTP確認済み |
| 3 | 吉野 | `yoshino` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / 本番HTTP確認済み |
| 4 | 吉野町 | `yoshinocho` | PUBLISHED | Codex / 2026-07-14 / Commit `98b009d` / Actions `29295020132` / 本番HTTP確認済み |
| 5 | 宮之浦町 | `miyanouracho` | BLOCKED_SLUG_CONFLICT | area一覧は `miyanouramachi`、Text canonicalは `miyanouracho`。自動置換せず判断待ち |
| 6 | 玉里団地 | `tamazatodanchi` | READY_CANDIDATE | |
| 7 | 玉里町 | `tamazatocho` | READY_CANDIDATE | |
| 8 | 原良 | `harara` | READY_CANDIDATE | |
| 9 | 光山 | `hikariyama` | READY_CANDIDATE | |
| 10 | 広木 | `hiroki` | READY_CANDIDATE | |
| 11 | 山下町 | `yamashitacho` | READY_CANDIDATE | |
| 12 | 山田町 | `yamadacho` | READY_CANDIDATE | |
| 13 | 山之口町 | `yamanokuchicho` | READY_CANDIDATE | |
| 14 | 四元町 | `yotsumotocho` | READY_CANDIDATE | |
| 15 | 紫原 | `murasakibaru` | READY_CANDIDATE | |
| 16 | 慈眼寺町 | `jigenjicho` | READY_CANDIDATE | |
| 17 | 自由ヶ丘 | `jiyugaoka` | READY_CANDIDATE | |
| 18 | 七ツ島 | `nanatsujima` | READY_CANDIDATE | |
| 19 | 若葉町 | `wakabacho` | READY_CANDIDATE | |
| 20 | 住吉町 | `sumiyoshicho` | READY_CANDIDATE | |
| 21 | 春山町 | `haruyamacho` | READY_CANDIDATE | |
| 22 | 小松原 | `komatsubara` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 23 | 松原町 | `matsubaracho` | READY_CANDIDATE | |
| 24 | 照国町 | `terukunicho` | READY_CANDIDATE | |
| 25 | 上谷口町 | `kamitaniguchicho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 26 | 上福元町 | `kamifukumotocho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 27 | 上本町 | `kamihonmachi` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 28 | 上竜尾町 | `kamitatsuocho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 29 | 城山 | `shiroyama` | READY_CANDIDATE | |
| 30 | 城山町 | `shiroyamacho` | READY_CANDIDATE | |
| 31 | 城西 | `josei` | READY_CANDIDATE | |
| 32 | 常盤 | `tokiwa` | READY_CANDIDATE | |
| 33 | 新栄町 | `shineicho` | READY_CANDIDATE | |
| 34 | 新照院町 | `shinshoincho` | READY_CANDIDATE | |
| 35 | 新町 | `shimmachi` | READY_CANDIDATE | |
| 36 | 真砂町 | `masagocho` | READY_CANDIDATE | |
| 37 | 真砂本町 | `masagohonmachi` | READY_CANDIDATE | |
| 38 | 星ヶ峯 | `hoshigamine` | READY_CANDIDATE | |
| 39 | 清水町 | `shimizucho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 40 | 清和 | `seiwa` | READY_CANDIDATE | |
| 41 | 西伊敷 | `nishiishiki` | READY_CANDIDATE | |
| 42 | 西佐多町 | `nishisatacho` | READY_CANDIDATE | |
| 43 | 西坂元町 | `nishisakamotocho` | READY_CANDIDATE | |
| 44 | 西紫原町 | `nishimurasakibarucho` | READY_CANDIDATE | |
| 45 | 西千石町 | `nishisengokucho` | READY_CANDIDATE | |
| 46 | 西谷山 | `nishitaniyama` | READY_CANDIDATE | |
| 47 | 西田 | `nishida` | READY_CANDIDATE | |
| 48 | 西別府町 | `nishibeppucho` | READY_CANDIDATE | |
| 49 | 西俣町 | `nishimatacho` | READY_CANDIDATE | |
| 50 | 千日町 | `sennichicho` | READY_CANDIDATE | |
| 51 | 川上町 | `kawakamicho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 52 | 川田町 | `kawadacho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 53 | 船津町 | `funatsucho` | READY_CANDIDATE | |
| 54 | 草牟田 | `soumuta` | READY_CANDIDATE | |
| 55 | 草牟田町 | `soumutacho` | READY_CANDIDATE | |
| 56 | 大黒町 | `daikokucho` | READY_CANDIDATE | |
| 57 | 大明丘 | `daimyogaoka` | READY_CANDIDATE | |
| 58 | 鷹師 | `takashi` | READY_CANDIDATE | |
| 59 | 谷山港 | `taniyamakou` | READY_CANDIDATE | |
| 60 | 谷山中央 | `taniyamachuuou` | READY_CANDIDATE | |
| 61 | 中央港新町 | `chuokoshinmachi` | READY_CANDIDATE | |
| 62 | 中央町 | `chuocho` | READY_CANDIDATE | |
| 63 | 中山 | `chuzan` | READY_CANDIDATE | |
| 64 | 中山町 | `chuzancho` | READY_CANDIDATE | |
| 65 | 中町 | `nakamachi` | READY_CANDIDATE | |
| 66 | 長田町 | `nagatacho` | READY_CANDIDATE | |
| 67 | 直木町 | `naokicho` | READY_CANDIDATE | |
| 68 | 田上 | `tagami` | READY_CANDIDATE | |
| 69 | 田上台 | `tagamidai` | READY_CANDIDATE | |
| 70 | 田上町 | `tagamicho` | READY_CANDIDATE | |
| 71 | 唐湊 | `toso` | READY_CANDIDATE | |
| 72 | 東開町 | `tokaicho` | READY_CANDIDATE | |
| 73 | 東郡元町 | `higashikoorimotocho` | READY_CANDIDATE | |
| 74 | 東佐多町 | `higashisatacho` | READY_CANDIDATE | |
| 75 | 東坂元 | `higashisakamoto` | READY_CANDIDATE | |
| 76 | 東千石町 | `higashisengokucho` | READY_CANDIDATE | |
| 77 | 東谷山 | `higashitaniyama` | READY_CANDIDATE | |
| 78 | 東俣町 | `higashimatacho` | READY_CANDIDATE | |
| 79 | 南栄 | `nanei` | READY_CANDIDATE | |
| 80 | 南郡元町 | `minamikorimotocho` | READY_CANDIDATE | |
| 81 | 南新町 | `minamishinmachi` | READY_CANDIDATE | |
| 82 | 南林寺町 | `nanrinjicho` | READY_CANDIDATE | |
| 83 | 日之出町 | `hinodecho` | READY_CANDIDATE | |
| 84 | 樋之口町 | `tenokuchicho` | READY_CANDIDATE | |
| 85 | 浜町 | `hamamachi` | READY_CANDIDATE | |
| 86 | 武 | `take` | READY_CANDIDATE | |
| 87 | 武岡 | `takeoka` | READY_CANDIDATE | |
| 88 | 福山町 | `fukuyamacho` | READY_CANDIDATE | |
| 89 | 平川町 | `hirakawacho` | BLOCKED_EXISTING_PARTIAL | 公開PHP・datasetあり、source HTMLなし |
| 90 | 平田町 | `hiratacho` | READY_CANDIDATE | |
| 91 | 平之町 | `hiranocho` | READY_CANDIDATE | |
| 92 | 堀江町 | `horiecho` | READY_CANDIDATE | |
| 93 | 本港新町 | `honkoshinmachi` | READY_CANDIDATE | |
| 94 | 本城町 | `honjocho` | READY_CANDIDATE | |
| 95 | 本名町 | `honmyocho` | READY_CANDIDATE | |
| 96 | 牟礼岡 | `muregaoka` | READY_CANDIDATE | |
| 97 | 名山町 | `meizancho` | READY_CANDIDATE | |
| 98 | 明和 | `meiwa` | READY_CANDIDATE | |
| 99 | 柳町 | `yanagimachi` | READY_CANDIDATE | |
| 100 | 油須木町 | `yusukicho` | READY_CANDIDATE | |
| 101 | 与次郎 | `yojiro` | READY_CANDIDATE | |
| 102 | 緑ヶ丘町 | `midorigaokacho` | READY_CANDIDATE | |
| 103 | 冷水町 | `hiyamizucho` | READY_CANDIDATE | |
| 104 | 和田 | `wada` | READY_CANDIDATE | |
| 105 | 皷川町 | `tsuzugawacho` | READY_CANDIDATE | |

## 4. 画像不足で停止中5件

次は情報ファイルがありますが、canonicalと完全一致する正式画像2枚がありません。似たslug画像を自動流用しません。

| 地域名 | canonical slug | 状態 | 理由 |
|---|---|---|---|
| 城南町 | `jonancho` | BLOCKED | 正式slug画像なし |
| 新屋敷町 | `shinayashikicho` | BLOCKED | 正式slug画像なし |
| 西陵 | `seiryo` | BLOCKED | 正式slug画像なし |
| 大竜町 | `dairyuucho` | BLOCKED | 正式slug画像なし |
| 天保山町 | `tenpozancho` | BLOCKED | 正式slug画像なし |

## 5. 既存不整合で停止中9件

次は公開PHPとページ別dataset PHPが既に存在し、source HTMLだけがありません。通常新規生成ではなく、既存不整合の修復として扱います。

```text
kamihonmachi, kamifukumotocho, kamitatsuocho,
kamitaniguchicho, komatsubara, kawakamicho,
kawadacho, hirakawacho, shimizucho
```

既存PHP・datasetを上書きせず、内容、dataset_base登録、area一覧、sitemap、旧slugを個別確認してから、ユーザー承認を得ます。

## 6. バッチ履歴

| Batch | 対象No./slug | 担当 | 状態 | 日付 | Commit | 未確認・停止理由 |
|---|---|---|---|---|---|---|
<!-- CANDY_AREA_BATCH_HISTORY_START -->
| TEST-01 | 1 / `hanaomachi` | Codex | PUBLISHED | 2026-07-14 | `44df27b` | Actions `29289499915`、本番HTTP 200・ブラウザ確認済み |
| TEST-02 | 2 / `minayoshicho` | Codex | PUBLISHED | 2026-07-14 | `f1ba7fd` | Actions `29294348852`、本番HTTP 200・title・canonical・h1・店舗・画像・一覧・sitemap確認済み |
| TEST-03 | 3 / `yoshino` | Codex | PUBLISHED | 2026-07-14 | `f1ba7fd` | Actions `29294348852`、本番HTTP 200・title・canonical・h1・店舗・画像・一覧・sitemap確認済み |
| TEST-04 | 4 / `yoshinocho` | Codex | PUBLISHED | 2026-07-14 | `98b009d` | Actions `29295020132`、本番HTTP 200・title・canonical・h1・店舗・画像・一覧・sitemap確認済み |
<!-- CANDY_AREA_BATCH_HISTORY_END -->
