# CANDY 修正バックログ

CANDY_CODE_FILE_STRUCTURE.md と CANDY_PAGE_SPEC_INDEX.md の要確認差分を1件1行で管理します。
## 共通集計

> 重要: 以下は 2026-07-10 の生成スナップショットであり、現在値ではありません。現在の件数・構成・状態は実ファイルから再集計してください。

集計時点: 2026-07-10 04:52

| 項目 | 件数 |
|---|---:|
| 全フォルダ | 37 |
| 全ファイル | 1683 |
| コード/設定ファイル | 328 |
| 非コードファイル | 1355 |
| codex配下ファイル | 34 |
| codex/docs配下ファイル | 13 |
| 管理MD(CANDY_*.md) | 13 |
| ルート直下PHP | 98 |
| source HTML | 89 |
| includefile PHP | 101 |
| dataset_*.php | 99 |
| Text_*_data配下ファイル | 175 |
| log配下ファイル | 74 |

注記: `全フォルダ` は `[root]` を除く実フォルダ数。フォルダ台帳は管理行として `[root]` を追加するため、台帳行数は `全フォルダ + 1`。

## 統合方針

重複統合なし。問題種別が違うものは同じファイルでも別行で残す。現在の行数は 95 行。

## バックログ

| No | 対象ファイル | 問題 | 推定原因 | 処理案 | 状態 |
|---:|---|---|---|---|---|
| 1 | kagoshima-deliveryhealth-area-hirakawacho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 2 | kagoshima-deliveryhealth-area-kamifukumotocho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 3 | kagoshima-deliveryhealth-area-kamihonmachi.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 4 | kagoshima-deliveryhealth-area-kamitaniguchicho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 5 | kagoshima-deliveryhealth-area-kamitatsuocho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 6 | kagoshima-deliveryhealth-area-kawadacho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 7 | kagoshima-deliveryhealth-area-kawakamicho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 8 | kagoshima-deliveryhealth-area-kiirenakamyoch.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 9 | kagoshima-deliveryhealth-area-komatsubara.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 10 | kagoshima-deliveryhealth-area-shimizucho.php | source HTMLなし | source未作成または削除残り | 直す | 未着手 |
| 11 | main.php | source HTMLなし | 通常ページではない入口 | 要オーナー判断 | 未着手 |
| 12 | makeSitemap.php | source HTMLなし | 通常ページではない入口 | 要オーナー判断 | 未着手 |
| 13 | page.php | source HTMLなし | 通常ページではない入口 | 要オーナー判断 | 未着手 |
| 14 | test.php | source HTMLなし | 通常ページではない入口 | 要オーナー判断 | 未着手 |
| 15 | source/girls.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 16 | source/girls_list.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 17 | source/index.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 18 | source/kagoshima-deliveryhealth-area-ariyadacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 19 | source/kagoshima-deliveryhealth-area-hananohikarigaoka.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 20 | source/kagoshima-deliveryhealth-area-ikenouecho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 21 | source/kagoshima-deliveryhealth-area-inaricho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 22 | source/kagoshima-deliveryhealth-area-inusakocho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 23 | source/kagoshima-deliveryhealth-area-irisacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 24 | source/kagoshima-deliveryhealth-area-ishidanicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 25 | source/kagoshima-deliveryhealth-area-ishiki.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 26 | source/kagoshima-deliveryhealth-area-ishikidai.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 27 | source/kagoshima-deliveryhealth-area-izumicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 28 | source/kagoshima-deliveryhealth-area-kajiyacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 29 | source/kagoshima-deliveryhealth-area-kamoike.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 30 | source/kagoshima-deliveryhealth-area-kamoikeshinmachi.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 31 | source/kagoshima-deliveryhealth-area-kenohikarigaoka.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 32 | source/kagoshima-deliveryhealth-area-kiirehitokuracho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 33 | source/kagoshima-deliveryhealth-area-kiireikemicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 34 | source/kagoshima-deliveryhealth-area-kiireikkuracho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 35 | source/kagoshima-deliveryhealth-area-kiiremaenohamacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 36 | source/kagoshima-deliveryhealth-area-kiirenakamyocho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 37 | source/kagoshima-deliveryhealth-area-kiiresesekushicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 38 | source/kagoshima-deliveryhealth-area-obaracho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 39 | source/kagoshima-deliveryhealth-area-ogawacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 40 | source/kagoshima-deliveryhealth-area-okanoharacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 41 | source/kagoshima-deliveryhealth-area-ono.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 42 | source/kagoshima-deliveryhealth-area-oroshihommachi.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 43 | source/kagoshima-deliveryhealth-area-uearatacho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 44 | source/kagoshima-deliveryhealth-area-uenosonocho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 45 | source/kagoshima-deliveryhealth-area-uomicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 46 | source/kagoshima-deliveryhealth-area-usuki.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 47 | source/kagoshima-deliveryhealth-area-yakushi.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 48 | source/kagoshima-deliveryhealth-area-yasuicho.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 49 | source/kagoshima-deliveryhealth-blog-glamourgirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 50 | source/kagoshima-deliveryhealth-blog-petitegirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 51 | source/kagoshima-deliveryhealth-blog-poccharigirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 52 | source/kagoshima-deliveryhealth-blog-shiroutogirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 53 | source/kagoshima-deliveryhealth-blog-slendergirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 54 | source/kagoshima-deliveryhealth-blog-tallbeautygirl.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 55 | source/kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 56 | source/kagoshima-deliveryhealth-hotel-hotelm.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 57 | source/movie.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 58 | source/mypage.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 59 | source/system.html | dataset_base switch未登録 | dataset_base登録漏れまたは動的読込対象外 | 要オーナー判断 | 未着手 |
| 60 | source/template_girls.html | dataset_base switch未登録 | テンプレ用途のため未登録の可能性 | 放置可 | 未着手 |
| 61 | source/template_kagoshima-deliveryhealth-area.html | dataset_base switch未登録 | テンプレ用途のため未登録の可能性 | 放置可 | 未着手 |
| 62 | source/template_kagoshima-deliveryhealth-blog.html | dataset_base switch未登録 | テンプレ用途のため未登録の可能性 | 放置可 | 未着手 |
| 63 | source/template_kagoshima-deliveryhealth-hotel.html | dataset_base switch未登録 | テンプレ用途のため未登録の可能性 | 放置可 | 未着手 |
| 64 | source/template_shop.html | dataset_base switch未登録 | テンプレ用途のため未登録の可能性 | 放置可 | 未着手 |
| 65 | main.php | 対応dataset未確認 | 通常ページではない入口またはdataset未作成 | 要オーナー判断 | 未着手 |
| 66 | makeSitemap.php | 対応dataset未確認 | 通常ページではない入口またはdataset未作成 | 要オーナー判断 | 未着手 |
| 67 | contact.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 68 | create.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 69 | kagoshima-deliveryhealth-area-gionnosucho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 70 | kagoshima-deliveryhealth-area-gofukucho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 71 | kagoshima-deliveryhealth-area-gokabeppucho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 72 | kagoshima-deliveryhealth-area-hananohikarigaoka.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 73 | kagoshima-deliveryhealth-area-kasugacho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 74 | kagoshima-deliveryhealth-area-kibougaokacho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 75 | kagoshima-deliveryhealth-area-kiirecho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 76 | kagoshima-deliveryhealth-area-kiireikkuracho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 77 | kagoshima-deliveryhealth-area-kiirenakamyocho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 78 | kagoshima-deliveryhealth-area-kinkodai.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 79 | kagoshima-deliveryhealth-area-kinseicho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 80 | kagoshima-deliveryhealth-area-koraicho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 81 | kagoshima-deliveryhealth-area-korimoto.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 82 | kagoshima-deliveryhealth-area-korimotocho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 83 | kagoshima-deliveryhealth-area-koriyamacho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 84 | kagoshima-deliveryhealth-area-koriyamadakecho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 85 | kagoshima-deliveryhealth-area-kotsukicho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 86 | kagoshima-deliveryhealth-area-koutokujidai.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 87 | kagoshima-deliveryhealth-area-koyamadacho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 88 | kagoshima-deliveryhealth-area-koyo.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 89 | kagoshima-deliveryhealth-area-oroshihommachi.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 90 | kagoshima-deliveryhealth-area-sakamotocho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 91 | kagoshima-deliveryhealth-area-sakanoue.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 92 | kagoshima-deliveryhealth-area-sakuragaoka.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 93 | kagoshima-deliveryhealth-area-sanwacho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 94 | kagoshima-deliveryhealth-area-shimofukumotocho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
| 95 | kagoshima-deliveryhealth-area-shimotatsuocho.php | title/H1 placeholder残り | テンプレ未展開ページ | 直す | 未着手 |
