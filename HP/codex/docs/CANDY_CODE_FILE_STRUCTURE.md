# CANDY HP コード構成管理

PHP、HTML、CSS、JS、htaccess、JSON、XMLの構成を管理します。
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

## Phase B 判定

| 項目 | 件数 |
|---|---:|
| コード/設定ファイル総数 | 328 |
| HP直下PHP | 98 |
| source直下HTML | 89 |
| includefile PHP | 101 |
| dataset_*.php | 99 |
| dataset_base switch登録 | 72 |
| HpgCoder rep switch数 | 562 |
| HpgCoder func数 | 563 |

## 生成構造

| 段階 | 役割 | 管理対象 |
|---:|---|---|
| 1 | HP直下PHPが入口になる | `*.php` |
| 2 | 共通処理を読む | `includefile/dataset_base.php` |
| 3 | PHP名からsource HTMLを見る | `source/同名.html` |
| 4 | dataset_baseのswitchでページ別datasetを読む | `includefile/dataset_*.php` |
| 5 | class.hpgcoder2.phpが置換トークンを処理する | `HpgCoder` |
| 6 | CSS/JS/画像/動画等を参照して公開ページを構成する | `css`, `js`, `imgHtml`, `imgCss`, `movie` |

## 要確認差分

| 観点 | 件数 | 対象 |
|---|---:|---|
| source HTMLなしの直下PHP | 14 | kagoshima-deliveryhealth-area-hirakawacho.php, kagoshima-deliveryhealth-area-kamifukumotocho.php, kagoshima-deliveryhealth-area-kamihonmachi.php, kagoshima-deliveryhealth-area-kamitaniguchicho.php, kagoshima-deliveryhealth-area-kamitatsuocho.php, kagoshima-deliveryhealth-area-kawadacho.php, kagoshima-deliveryhealth-area-kawakamicho.php, kagoshima-deliveryhealth-area-kiirenakamyoch.php, kagoshima-deliveryhealth-area-komatsubara.php, kagoshima-deliveryhealth-area-shimizucho.php, main.php, makeSitemap.php, page.php, test.php |
| 直下PHPなしのsource HTML | 5 | source/template_girls.html, source/template_kagoshima-deliveryhealth-area.html, source/template_kagoshima-deliveryhealth-blog.html, source/template_kagoshima-deliveryhealth-hotel.html, source/template_shop.html |
| dataset_base未読込の直下PHP | 1 | makeSitemap.php |
| 対応dataset未確認の直下PHP | 2 | main.php, makeSitemap.php |
| switch未登録のsource HTML | 50 | source/girls.html, source/girls_list.html, source/index.html, source/kagoshima-deliveryhealth-area-ariyadacho.html, source/kagoshima-deliveryhealth-area-hananohikarigaoka.html, source/kagoshima-deliveryhealth-area-ikenouecho.html, source/kagoshima-deliveryhealth-area-inaricho.html, source/kagoshima-deliveryhealth-area-inusakocho.html, source/kagoshima-deliveryhealth-area-irisacho.html, source/kagoshima-deliveryhealth-area-ishidanicho.html, source/kagoshima-deliveryhealth-area-ishiki.html, source/kagoshima-deliveryhealth-area-ishikidai.html, source/kagoshima-deliveryhealth-area-izumicho.html, source/kagoshima-deliveryhealth-area-kajiyacho.html, source/kagoshima-deliveryhealth-area-kamoike.html, source/kagoshima-deliveryhealth-area-kamoikeshinmachi.html, source/kagoshima-deliveryhealth-area-kenohikarigaoka.html, source/kagoshima-deliveryhealth-area-kiirehitokuracho.html, source/kagoshima-deliveryhealth-area-kiireikemicho.html, source/kagoshima-deliveryhealth-area-kiireikkuracho.html, source/kagoshima-deliveryhealth-area-kiiremaenohamacho.html, source/kagoshima-deliveryhealth-area-kiirenakamyocho.html, source/kagoshima-deliveryhealth-area-kiiresesekushicho.html, source/kagoshima-deliveryhealth-area-obaracho.html, source/kagoshima-deliveryhealth-area-ogawacho.html, source/kagoshima-deliveryhealth-area-okanoharacho.html, source/kagoshima-deliveryhealth-area-ono.html, source/kagoshima-deliveryhealth-area-oroshihommachi.html, source/kagoshima-deliveryhealth-area-uearatacho.html, source/kagoshima-deliveryhealth-area-uenosonocho.html, source/kagoshima-deliveryhealth-area-uomicho.html, source/kagoshima-deliveryhealth-area-usuki.html, source/kagoshima-deliveryhealth-area-yakushi.html, source/kagoshima-deliveryhealth-area-yasuicho.html, source/kagoshima-deliveryhealth-blog-glamourgirl.html, source/kagoshima-deliveryhealth-blog-petitegirl.html, source/kagoshima-deliveryhealth-blog-poccharigirl.html, source/kagoshima-deliveryhealth-blog-shiroutogirl.html, source/kagoshima-deliveryhealth-blog-slendergirl.html, source/kagoshima-deliveryhealth-blog-tallbeautygirl.html, source/kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.html, source/kagoshima-deliveryhealth-hotel-hotelm.html, source/movie.html, source/mypage.html, source/system.html, source/template_girls.html, source/template_kagoshima-deliveryhealth-area.html, source/template_kagoshima-deliveryhealth-blog.html, source/template_kagoshima-deliveryhealth-hotel.html, source/template_shop.html |

## HP直下PHPとHTML/dataset対応

| No | PHP | 役割 | dataset_base | source HTML | HTML有無 | switch dataset | dataset有無 |
|---:|---|---|---|---|---|---|---|
| 1 | area.php | 対応エリア一覧ページ | yes | source/area.html | yes | dataset_area.php | yes |
| 2 | blog.php | ブログ一覧ページ | yes | source/blog.html | yes | dataset_blog.php | yes |
| 3 | contact.php | 問い合わせ/連絡導線候補 | yes | source/contact.html | yes | dataset_contact.php | yes |
| 4 | create.php | 認証付きページ作成機能。公開ページの通常改修対象ではなく、値の転記は禁止。 | yes | source/create.html | yes | dataset_create.php | yes |
| 5 | girls.php | 女の子詳細プロフィールページ | yes | source/girls.html | yes | - | yes |
| 6 | girls_list.php | 女の子一覧ページ | yes | source/girls_list.html | yes | - | yes |
| 7 | hotel.php | ホテル一覧ページ | yes | source/hotel.html | yes | dataset_hotel.php | yes |
| 8 | index.php | トップページ / サイト入口 | yes | source/index.html | yes | - | yes |
| 9 | kagoshima-deliveryhealth-area-arata.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-arata.html | yes | dataset_kagoshima-deliveryhealth-area-arata.php | yes |
| 10 | kagoshima-deliveryhealth-area-ariyadacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ariyadacho.html | yes | - | yes |
| 11 | kagoshima-deliveryhealth-area-gionnosucho.php | 対応エリア詳細ページ（slug: gionnosucho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-gionnosucho.html | yes | dataset_kagoshima-deliveryhealth-area-gionnosucho.php | yes |
| 12 | kagoshima-deliveryhealth-area-gofukucho.php | 対応エリア詳細ページ（slug: gofukucho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-gofukucho.html | yes | dataset_kagoshima-deliveryhealth-area-gofukucho.php | yes |
| 13 | kagoshima-deliveryhealth-area-gokabeppucho.php | 対応エリア詳細ページ（slug: gokabeppucho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-gokabeppucho.html | yes | dataset_kagoshima-deliveryhealth-area-gokabeppucho.php | yes |
| 14 | kagoshima-deliveryhealth-area-hananohikarigaoka.php | 対応エリア詳細ページ（slug: hananohikarigaoka / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-hananohikarigaoka.html | yes | - | yes |
| 15 | kagoshima-deliveryhealth-area-hirakawacho.php | 対応エリア詳細ページ（slug: hirakawacho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-hirakawacho.php | yes |
| 16 | kagoshima-deliveryhealth-area-ikenouecho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ikenouecho.html | yes | - | yes |
| 17 | kagoshima-deliveryhealth-area-inaricho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-inaricho.html | yes | - | yes |
| 18 | kagoshima-deliveryhealth-area-inusakocho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-inusakocho.html | yes | - | yes |
| 19 | kagoshima-deliveryhealth-area-irisacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-irisacho.html | yes | - | yes |
| 20 | kagoshima-deliveryhealth-area-ishidanicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ishidanicho.html | yes | - | yes |
| 21 | kagoshima-deliveryhealth-area-ishiki.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ishiki.html | yes | - | yes |
| 22 | kagoshima-deliveryhealth-area-ishikidai.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ishikidai.html | yes | - | yes |
| 23 | kagoshima-deliveryhealth-area-izumicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-izumicho.html | yes | - | yes |
| 24 | kagoshima-deliveryhealth-area-kajiyacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kajiyacho.html | yes | - | yes |
| 25 | kagoshima-deliveryhealth-area-kamifukumotocho.php | 対応エリア詳細ページ（slug: kamifukumotocho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kamifukumotocho.php | yes |
| 26 | kagoshima-deliveryhealth-area-kamihonmachi.php | 対応エリア詳細ページ（slug: kamihonmachi / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kamihonmachi.php | yes |
| 27 | kagoshima-deliveryhealth-area-kamitaniguchicho.php | 対応エリア詳細ページ（slug: kamitaniguchicho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kamitaniguchicho.php | yes |
| 28 | kagoshima-deliveryhealth-area-kamitatsuocho.php | 対応エリア詳細ページ（slug: kamitatsuocho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kamitatsuocho.php | yes |
| 29 | kagoshima-deliveryhealth-area-kamoike.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kamoike.html | yes | - | yes |
| 30 | kagoshima-deliveryhealth-area-kamoikeshinmachi.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kamoikeshinmachi.html | yes | - | yes |
| 31 | kagoshima-deliveryhealth-area-kasugacho.php | 対応エリア詳細ページ（slug: kasugacho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kasugacho.html | yes | dataset_kagoshima-deliveryhealth-area-kasugacho.php | yes |
| 32 | kagoshima-deliveryhealth-area-kawadacho.php | 対応エリア詳細ページ（slug: kawadacho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kawadacho.php | yes |
| 33 | kagoshima-deliveryhealth-area-kawakamicho.php | 対応エリア詳細ページ（slug: kawakamicho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-kawakamicho.php | yes |
| 34 | kagoshima-deliveryhealth-area-kenohikarigaoka.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kenohikarigaoka.html | yes | - | yes |
| 35 | kagoshima-deliveryhealth-area-kibougaokacho.php | 対応エリア詳細ページ（slug: kibougaokacho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kibougaokacho.html | yes | dataset_kagoshima-deliveryhealth-area-kibougaokacho.php | yes |
| 36 | kagoshima-deliveryhealth-area-kiirecho.php | 対応エリア詳細ページ（slug: kiirecho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kiirecho.html | yes | dataset_kagoshima-deliveryhealth-area-kiirecho.php | yes |
| 37 | kagoshima-deliveryhealth-area-kiirehitokuracho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kiirehitokuracho.html | yes | - | yes |
| 38 | kagoshima-deliveryhealth-area-kiireikemicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kiireikemicho.html | yes | - | yes |
| 39 | kagoshima-deliveryhealth-area-kiireikkuracho.php | 対応エリア詳細ページ（slug: kiireikkuracho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kiireikkuracho.html | yes | - | yes |
| 40 | kagoshima-deliveryhealth-area-kiiremaenohamacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kiiremaenohamacho.html | yes | - | yes |
| 41 | kagoshima-deliveryhealth-area-kiirenakamyoch.php | 対応エリア詳細ページ（slug: kiirenakamyoch / 表示名未確定） | yes | - | no | - | yes |
| 42 | kagoshima-deliveryhealth-area-kiirenakamyocho.php | 対応エリア詳細ページ（slug: kiirenakamyocho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kiirenakamyocho.html | yes | - | yes |
| 43 | kagoshima-deliveryhealth-area-kiiresesekushicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kiiresesekushicho.html | yes | - | yes |
| 44 | kagoshima-deliveryhealth-area-kinkocho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-kinkocho.html | yes | dataset_kagoshima-deliveryhealth-area-kinkocho.php | yes |
| 45 | kagoshima-deliveryhealth-area-kinkodai.php | 対応エリア詳細ページ（slug: kinkodai / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kinkodai.html | yes | dataset_kagoshima-deliveryhealth-area-kinkodai.php | yes |
| 46 | kagoshima-deliveryhealth-area-kinseicho.php | 対応エリア詳細ページ（slug: kinseicho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kinseicho.html | yes | dataset_kagoshima-deliveryhealth-area-kinseicho.php | yes |
| 47 | kagoshima-deliveryhealth-area-komatsubara.php | 対応エリア詳細ページ（slug: komatsubara / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-komatsubara.php | yes |
| 48 | kagoshima-deliveryhealth-area-koraicho.php | 対応エリア詳細ページ（slug: koraicho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koraicho.html | yes | dataset_kagoshima-deliveryhealth-area-koraicho.php | yes |
| 49 | kagoshima-deliveryhealth-area-korimoto.php | 対応エリア詳細ページ（slug: korimoto / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-korimoto.html | yes | dataset_kagoshima-deliveryhealth-area-korimoto.php | yes |
| 50 | kagoshima-deliveryhealth-area-korimotocho.php | 対応エリア詳細ページ（slug: korimotocho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-korimotocho.html | yes | dataset_kagoshima-deliveryhealth-area-korimotocho.php | yes |
| 51 | kagoshima-deliveryhealth-area-koriyamacho.php | 対応エリア詳細ページ（slug: koriyamacho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koriyamacho.html | yes | dataset_kagoshima-deliveryhealth-area-koriyamacho.php | yes |
| 52 | kagoshima-deliveryhealth-area-koriyamadakecho.php | 対応エリア詳細ページ（slug: koriyamadakecho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koriyamadakecho.html | yes | dataset_kagoshima-deliveryhealth-area-koriyamadakecho.php | yes |
| 53 | kagoshima-deliveryhealth-area-kotsukicho.php | 対応エリア詳細ページ（slug: kotsukicho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-kotsukicho.html | yes | dataset_kagoshima-deliveryhealth-area-kotsukicho.php | yes |
| 54 | kagoshima-deliveryhealth-area-koutokujidai.php | 対応エリア詳細ページ（slug: koutokujidai / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koutokujidai.html | yes | dataset_kagoshima-deliveryhealth-area-koutokujidai.php | yes |
| 55 | kagoshima-deliveryhealth-area-koyamadacho.php | 対応エリア詳細ページ（slug: koyamadacho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koyamadacho.html | yes | dataset_kagoshima-deliveryhealth-area-koyamadacho.php | yes |
| 56 | kagoshima-deliveryhealth-area-koyo.php | 対応エリア詳細ページ（slug: koyo / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-koyo.html | yes | dataset_kagoshima-deliveryhealth-area-koyo.php | yes |
| 57 | kagoshima-deliveryhealth-area-nagayoshi.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-nagayoshi.html | yes | dataset_kagoshima-deliveryhealth-area-nagayoshi.php | yes |
| 58 | kagoshima-deliveryhealth-area-obaracho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-obaracho.html | yes | - | yes |
| 59 | kagoshima-deliveryhealth-area-ogawacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ogawacho.html | yes | - | yes |
| 60 | kagoshima-deliveryhealth-area-okanoharacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-okanoharacho.html | yes | - | yes |
| 61 | kagoshima-deliveryhealth-area-ono.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-ono.html | yes | - | yes |
| 62 | kagoshima-deliveryhealth-area-oroshihommachi.php | 対応エリア詳細ページ（slug: oroshihommachi / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-oroshihommachi.html | yes | - | yes |
| 63 | kagoshima-deliveryhealth-area-sakamotocho.php | 対応エリア詳細ページ（slug: sakamotocho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-sakamotocho.html | yes | dataset_kagoshima-deliveryhealth-area-sakamotocho.php | yes |
| 64 | kagoshima-deliveryhealth-area-sakanoue.php | 対応エリア詳細ページ（slug: sakanoue / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-sakanoue.html | yes | dataset_kagoshima-deliveryhealth-area-sakanoue.php | yes |
| 65 | kagoshima-deliveryhealth-area-sakuragaoka.php | 対応エリア詳細ページ（slug: sakuragaoka / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-sakuragaoka.html | yes | dataset_kagoshima-deliveryhealth-area-sakuragaoka.php | yes |
| 66 | kagoshima-deliveryhealth-area-sanwacho.php | 対応エリア詳細ページ（slug: sanwacho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-sanwacho.html | yes | dataset_kagoshima-deliveryhealth-area-sanwacho.php | yes |
| 67 | kagoshima-deliveryhealth-area-shimizucho.php | 対応エリア詳細ページ（slug: shimizucho / 表示名未確定） | yes | - | no | dataset_kagoshima-deliveryhealth-area-shimizucho.php | yes |
| 68 | kagoshima-deliveryhealth-area-shimoarata.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-shimoarata.html | yes | dataset_kagoshima-deliveryhealth-area-shimoarata.php | yes |
| 69 | kagoshima-deliveryhealth-area-shimofukumotocho.php | 対応エリア詳細ページ（slug: shimofukumotocho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-shimofukumotocho.html | yes | dataset_kagoshima-deliveryhealth-area-shimofukumotocho.php | yes |
| 70 | kagoshima-deliveryhealth-area-shimoishiki.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-shimoishiki.html | yes | dataset_kagoshima-deliveryhealth-area-shimoishiki.php | yes |
| 71 | kagoshima-deliveryhealth-area-shimoishikicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-shimoishikicho.html | yes | dataset_kagoshima-deliveryhealth-area-shimoishikicho.php | yes |
| 72 | kagoshima-deliveryhealth-area-shimotacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-shimotacho.html | yes | dataset_kagoshima-deliveryhealth-area-shimotacho.php | yes |
| 73 | kagoshima-deliveryhealth-area-shimotatsuocho.php | 対応エリア詳細ページ（slug: shimotatsuocho / 表示名未確定） | yes | source/kagoshima-deliveryhealth-area-shimotatsuocho.html | yes | dataset_kagoshima-deliveryhealth-area-shimotatsuocho.php | yes |
| 74 | kagoshima-deliveryhealth-area-uearatacho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-uearatacho.html | yes | - | yes |
| 75 | kagoshima-deliveryhealth-area-uenosonocho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-uenosonocho.html | yes | - | yes |
| 76 | kagoshima-deliveryhealth-area-uomicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-uomicho.html | yes | - | yes |
| 77 | kagoshima-deliveryhealth-area-usuki.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-usuki.html | yes | - | yes |
| 78 | kagoshima-deliveryhealth-area-yakushi.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-yakushi.html | yes | - | yes |
| 79 | kagoshima-deliveryhealth-area-yasuicho.php | 対応エリア詳細ページ | yes | source/kagoshima-deliveryhealth-area-yasuicho.html | yes | - | yes |
| 80 | kagoshima-deliveryhealth-blog-glamourgirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-glamourgirl.html | yes | - | yes |
| 81 | kagoshima-deliveryhealth-blog-petitegirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-petitegirl.html | yes | - | yes |
| 82 | kagoshima-deliveryhealth-blog-poccharigirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-poccharigirl.html | yes | - | yes |
| 83 | kagoshima-deliveryhealth-blog-shiroutogirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-shiroutogirl.html | yes | - | yes |
| 84 | kagoshima-deliveryhealth-blog-slendergirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-slendergirl.html | yes | - | yes |
| 85 | kagoshima-deliveryhealth-blog-tallbeautygirl.php | SEOブログ詳細ページ | yes | source/kagoshima-deliveryhealth-blog-tallbeautygirl.html | yes | - | yes |
| 86 | kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.php | ホテル詳細ページ | yes | source/kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.html | yes | - | yes |
| 87 | kagoshima-deliveryhealth-hotel-hotelm.php | ホテル詳細ページ | yes | source/kagoshima-deliveryhealth-hotel-hotelm.html | yes | - | yes |
| 88 | kagoshima-deliveryhealth-hotel-villacosta500.php | ホテル詳細ページ | yes | source/kagoshima-deliveryhealth-hotel-villacosta500.html | yes | dataset_kagoshima-deliveryhealth-hotel-villacosta500.php | yes |
| 89 | main.php | dataset_base.phpを読む薄い入口候補。表示内容は共通処理側に依存する。 | yes | - | no | - | no |
| 90 | makeSitemap.php | 現在ホストを起点にURLを収集し、XMLサイトマップを出力する処理。 | no | - | no | - | no |
| 91 | movie.php | 鹿児島 デリヘル キャンディ \| 女の子動画一覧 | yes | source/movie.html | yes | - | yes |
| 92 | movie_iframe.php | コード上不明 / 要オーナー確認 | yes | source/movie_iframe.html | yes | dataset_movie_iframe.php | yes |
| 93 | mypage.php | 鹿児島 デリヘル キャンディ │ マイページ | yes | source/mypage.html | yes | - | yes |
| 94 | news.php | NEWS/お知らせページ | yes | source/news.html | yes | dataset_news.php | yes |
| 95 | page.php | dataset_base.phpを読む薄い入口候補。表示内容は共通処理側に依存する。 | yes | - | no | dataset_page.php | yes |
| 96 | schedule.php | 出勤スケジュールページ | yes | source/schedule.html | yes | dataset_schedule.php | yes |
| 97 | system.php | 鹿児島 デリヘル キャンディ │ システム・料金案内 | yes | source/system.html | yes | - | yes |
| 98 | test.php | dataset_base.phpを読む薄い入口候補。表示内容は共通処理側に依存する。 | yes | - | no | dataset_test.php | yes |

## コードファイル一覧

| No | パス | 拡張子 | 役割 |
|---:|---|---|---|
| 1 | .htaccess | .htaccess | 公開ルート設定 |
| 2 | .vscode/settings.json | .json | 非コード資産 |
| 3 | .well-known/.htaccess | .htaccess | 公開ルート設定 |
| 4 | area.php | .php | 公開入口PHP |
| 5 | blog.php | .php | 公開入口PHP |
| 6 | codex/area/backups/dataset_base.before-nagayoshi-20260605.php | .php | Codex管理資料/作業資料 |
| 7 | codex/area/backups/kagoshima-deliveryhealth-area-shimotacho.before-complete-20260605.html | .html | Codex管理資料/作業資料 |
| 8 | contact.php | .php | 公開入口PHP |
| 9 | create.php | .php | 管理/生成系PHP（認証値は転記禁止） |
| 10 | css/colorbox.css | .css | 公開CSS |
| 11 | css/default.css | .css | 公開CSS |
| 12 | css/girls.css | .css | 公開CSS |
| 13 | css/girls_list.css | .css | 公開CSS |
| 14 | css/job.css | .css | 公開CSS |
| 15 | css/jquery.fs.boxer.css | .css | 公開CSS |
| 16 | css/movie.css | .css | 公開CSS |
| 17 | css/mypage.css | .css | 公開CSS |
| 18 | css/new_main.css | .css | 公開CSS |
| 19 | css/new_page.css | .css | 公開CSS |
| 20 | css/news.css | .css | 公開CSS |
| 21 | css/schedule.css | .css | 公開CSS |
| 22 | css/system.css | .css | 公開CSS |
| 23 | css/YTPlayer.css | .css | 公開CSS |
| 24 | girls.php | .php | 公開入口PHP |
| 25 | girls_list.php | .php | 公開入口PHP |
| 26 | hotel.php | .php | 公開入口PHP |
| 27 | imgCss/pc/cssSprite.php | .php | 画像資産 |
| 28 | imgCss/s/cssSprite.php | .php | 画像資産 |
| 29 | includefile/class.hpgcoder2.php | .php | 置換エンジン |
| 30 | includefile/dataset_area.php | .php | ページ別データセット |
| 31 | includefile/dataset_base.php | .php | 共通データセット/生成制御 |
| 32 | includefile/dataset_base_def.php | .php | ページ別データセット |
| 33 | includefile/dataset_blog.php | .php | ページ別データセット |
| 34 | includefile/dataset_contact.php | .php | ページ別データセット |
| 35 | includefile/dataset_create.php | .php | ページ別データセット |
| 36 | includefile/dataset_default.php | .php | ページ別データセット |
| 37 | includefile/dataset_girls.php | .php | ページ別データセット |
| 38 | includefile/dataset_girls_list.php | .php | ページ別データセット |
| 39 | includefile/dataset_hotel.php | .php | ページ別データセット |
| 40 | includefile/dataset_index.php | .php | ページ別データセット |
| 41 | includefile/dataset_kagoshima-deliveryhealth-area-arata.php | .php | ページ別データセット |
| 42 | includefile/dataset_kagoshima-deliveryhealth-area-ariyadacho.php | .php | ページ別データセット |
| 43 | includefile/dataset_kagoshima-deliveryhealth-area-gionnosucho.php | .php | ページ別データセット |
| 44 | includefile/dataset_kagoshima-deliveryhealth-area-gofukucho.php | .php | ページ別データセット |
| 45 | includefile/dataset_kagoshima-deliveryhealth-area-gokabeppucho.php | .php | ページ別データセット |
| 46 | includefile/dataset_kagoshima-deliveryhealth-area-hananohikarigaoka.php | .php | ページ別データセット |
| 47 | includefile/dataset_kagoshima-deliveryhealth-area-hirakawacho.php | .php | ページ別データセット |
| 48 | includefile/dataset_kagoshima-deliveryhealth-area-ikenouecho.php | .php | ページ別データセット |
| 49 | includefile/dataset_kagoshima-deliveryhealth-area-inaricho.php | .php | ページ別データセット |
| 50 | includefile/dataset_kagoshima-deliveryhealth-area-inusakocho.php | .php | ページ別データセット |
| 51 | includefile/dataset_kagoshima-deliveryhealth-area-irisacho.php | .php | ページ別データセット |
| 52 | includefile/dataset_kagoshima-deliveryhealth-area-ishidanicho.php | .php | ページ別データセット |
| 53 | includefile/dataset_kagoshima-deliveryhealth-area-ishiki.php | .php | ページ別データセット |
| 54 | includefile/dataset_kagoshima-deliveryhealth-area-ishikidai.php | .php | ページ別データセット |
| 55 | includefile/dataset_kagoshima-deliveryhealth-area-izumicho.php | .php | ページ別データセット |
| 56 | includefile/dataset_kagoshima-deliveryhealth-area-kajiyacho.php | .php | ページ別データセット |
| 57 | includefile/dataset_kagoshima-deliveryhealth-area-kamifukumotocho.php | .php | ページ別データセット |
| 58 | includefile/dataset_kagoshima-deliveryhealth-area-kamihonmachi.php | .php | ページ別データセット |
| 59 | includefile/dataset_kagoshima-deliveryhealth-area-kamitaniguchicho.php | .php | ページ別データセット |
| 60 | includefile/dataset_kagoshima-deliveryhealth-area-kamitatsuocho.php | .php | ページ別データセット |
| 61 | includefile/dataset_kagoshima-deliveryhealth-area-kamoike.php | .php | ページ別データセット |
| 62 | includefile/dataset_kagoshima-deliveryhealth-area-kamoikeshinmachi.php | .php | ページ別データセット |
| 63 | includefile/dataset_kagoshima-deliveryhealth-area-kasugacho.php | .php | ページ別データセット |
| 64 | includefile/dataset_kagoshima-deliveryhealth-area-kawadacho.php | .php | ページ別データセット |
| 65 | includefile/dataset_kagoshima-deliveryhealth-area-kawakamicho.php | .php | ページ別データセット |
| 66 | includefile/dataset_kagoshima-deliveryhealth-area-kenohikarigaoka.php | .php | ページ別データセット |
| 67 | includefile/dataset_kagoshima-deliveryhealth-area-kibougaokacho.php | .php | ページ別データセット |
| 68 | includefile/dataset_kagoshima-deliveryhealth-area-kiirecho.php | .php | ページ別データセット |
| 69 | includefile/dataset_kagoshima-deliveryhealth-area-kiirehitokuracho.php | .php | ページ別データセット |
| 70 | includefile/dataset_kagoshima-deliveryhealth-area-kiireikemicho.php | .php | ページ別データセット |
| 71 | includefile/dataset_kagoshima-deliveryhealth-area-kiireikkuracho.php | .php | ページ別データセット |
| 72 | includefile/dataset_kagoshima-deliveryhealth-area-kiiremaenohamacho.php | .php | ページ別データセット |
| 73 | includefile/dataset_kagoshima-deliveryhealth-area-kiirenakamyoch.php | .php | ページ別データセット |
| 74 | includefile/dataset_kagoshima-deliveryhealth-area-kiirenakamyocho.php | .php | ページ別データセット |
| 75 | includefile/dataset_kagoshima-deliveryhealth-area-kiiresesekushicho.php | .php | ページ別データセット |
| 76 | includefile/dataset_kagoshima-deliveryhealth-area-kinkocho.php | .php | ページ別データセット |
| 77 | includefile/dataset_kagoshima-deliveryhealth-area-kinkodai.php | .php | ページ別データセット |
| 78 | includefile/dataset_kagoshima-deliveryhealth-area-kinseicho.php | .php | ページ別データセット |
| 79 | includefile/dataset_kagoshima-deliveryhealth-area-komatsubara.php | .php | ページ別データセット |
| 80 | includefile/dataset_kagoshima-deliveryhealth-area-koraicho.php | .php | ページ別データセット |
| 81 | includefile/dataset_kagoshima-deliveryhealth-area-korimoto.php | .php | ページ別データセット |
| 82 | includefile/dataset_kagoshima-deliveryhealth-area-korimotocho.php | .php | ページ別データセット |
| 83 | includefile/dataset_kagoshima-deliveryhealth-area-koriyamacho.php | .php | ページ別データセット |
| 84 | includefile/dataset_kagoshima-deliveryhealth-area-koriyamadakecho.php | .php | ページ別データセット |
| 85 | includefile/dataset_kagoshima-deliveryhealth-area-kotsukicho.php | .php | ページ別データセット |
| 86 | includefile/dataset_kagoshima-deliveryhealth-area-koutokujidai.php | .php | ページ別データセット |
| 87 | includefile/dataset_kagoshima-deliveryhealth-area-koyamadacho.php | .php | ページ別データセット |
| 88 | includefile/dataset_kagoshima-deliveryhealth-area-koyo.php | .php | ページ別データセット |
| 89 | includefile/dataset_kagoshima-deliveryhealth-area-nagayoshi.php | .php | ページ別データセット |
| 90 | includefile/dataset_kagoshima-deliveryhealth-area-obaracho.php | .php | ページ別データセット |
| 91 | includefile/dataset_kagoshima-deliveryhealth-area-ogawacho.php | .php | ページ別データセット |
| 92 | includefile/dataset_kagoshima-deliveryhealth-area-okanoharacho.php | .php | ページ別データセット |
| 93 | includefile/dataset_kagoshima-deliveryhealth-area-ono.php | .php | ページ別データセット |
| 94 | includefile/dataset_kagoshima-deliveryhealth-area-oroshihommachi.php | .php | ページ別データセット |
| 95 | includefile/dataset_kagoshima-deliveryhealth-area-sakamotocho.php | .php | ページ別データセット |
| 96 | includefile/dataset_kagoshima-deliveryhealth-area-sakanoue.php | .php | ページ別データセット |
| 97 | includefile/dataset_kagoshima-deliveryhealth-area-sakuragaoka.php | .php | ページ別データセット |
| 98 | includefile/dataset_kagoshima-deliveryhealth-area-sanwacho.php | .php | ページ別データセット |
| 99 | includefile/dataset_kagoshima-deliveryhealth-area-shimizucho.php | .php | ページ別データセット |
| 100 | includefile/dataset_kagoshima-deliveryhealth-area-shimoarata.php | .php | ページ別データセット |
| 101 | includefile/dataset_kagoshima-deliveryhealth-area-shimofukumotocho.php | .php | ページ別データセット |
| 102 | includefile/dataset_kagoshima-deliveryhealth-area-shimoishiki.php | .php | ページ別データセット |
| 103 | includefile/dataset_kagoshima-deliveryhealth-area-shimoishikicho.php | .php | ページ別データセット |
| 104 | includefile/dataset_kagoshima-deliveryhealth-area-shimotacho.php | .php | ページ別データセット |
| 105 | includefile/dataset_kagoshima-deliveryhealth-area-shimotatsuocho.php | .php | ページ別データセット |
| 106 | includefile/dataset_kagoshima-deliveryhealth-area-uearatacho.php | .php | ページ別データセット |
| 107 | includefile/dataset_kagoshima-deliveryhealth-area-uenosonocho.php | .php | ページ別データセット |
| 108 | includefile/dataset_kagoshima-deliveryhealth-area-uomicho.php | .php | ページ別データセット |
| 109 | includefile/dataset_kagoshima-deliveryhealth-area-usuki.php | .php | ページ別データセット |
| 110 | includefile/dataset_kagoshima-deliveryhealth-area-yakushi.php | .php | ページ別データセット |
| 111 | includefile/dataset_kagoshima-deliveryhealth-area-yasuicho.php | .php | ページ別データセット |
| 112 | includefile/dataset_kagoshima-deliveryhealth-blog-glamourgirl.php | .php | ページ別データセット |
| 113 | includefile/dataset_kagoshima-deliveryhealth-blog-petitegirl.php | .php | ページ別データセット |
| 114 | includefile/dataset_kagoshima-deliveryhealth-blog-poccharigirl.php | .php | ページ別データセット |
| 115 | includefile/dataset_kagoshima-deliveryhealth-blog-shiroutogirl.php | .php | ページ別データセット |
| 116 | includefile/dataset_kagoshima-deliveryhealth-blog-slendergirl.php | .php | ページ別データセット |
| 117 | includefile/dataset_kagoshima-deliveryhealth-blog-tallbeautygirl.php | .php | ページ別データセット |
| 118 | includefile/dataset_kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.php | .php | ページ別データセット |
| 119 | includefile/dataset_kagoshima-deliveryhealth-hotel-hotelm.php | .php | ページ別データセット |
| 120 | includefile/dataset_kagoshima-deliveryhealth-hotel-villacosta500.php | .php | ページ別データセット |
| 121 | includefile/dataset_movie.php | .php | ページ別データセット |
| 122 | includefile/dataset_movie_iframe.php | .php | ページ別データセット |
| 123 | includefile/dataset_mypage.php | .php | ページ別データセット |
| 124 | includefile/dataset_news.php | .php | ページ別データセット |
| 125 | includefile/dataset_page.php | .php | ページ別データセット |
| 126 | includefile/dataset_schedule.php | .php | ページ別データセット |
| 127 | includefile/dataset_system.php | .php | ページ別データセット |
| 128 | includefile/dataset_test.php | .php | ページ別データセット |
| 129 | includefile/funcs.php | .php | 共通関数/設定補助 |
| 130 | index.php | .php | 公開入口PHP |
| 131 | js/amadare_webapp2.4.php | .php | 公開JavaScript |
| 132 | js/amadareAccess.1.0.js | .js | 公開JavaScript |
| 133 | js/amadareWebApp2.6.js | .js | 公開JavaScript |
| 134 | js/api.js | .js | 公開JavaScript |
| 135 | js/candyKissDijest.js | .js | 公開JavaScript |
| 136 | js/candyTile.js | .js | 公開JavaScript |
| 137 | js/common.js | .js | 公開JavaScript |
| 138 | js/commonLite.js | .js | 公開JavaScript |
| 139 | js/diary.js | .js | 公開JavaScript |
| 140 | js/fav_gen.js | .js | 公開JavaScript |
| 141 | js/fav_ka.js | .js | 公開JavaScript |
| 142 | js/jquery.colorbox-min.js | .js | 公開JavaScript |
| 143 | js/jquery.fs.boxer.min.js | .js | 公開JavaScript |
| 144 | js/love2.js | .js | 公開JavaScript |
| 145 | js/mdrwbpp2.4.js | .js | 公開JavaScript |
| 146 | js/movieSum.js | .js | 公開JavaScript |
| 147 | js/youtube_video.js | .js | 公開JavaScript |
| 148 | kagoshima-deliveryhealth-area-arata.php | .php | 公開入口PHP |
| 149 | kagoshima-deliveryhealth-area-ariyadacho.php | .php | 公開入口PHP |
| 150 | kagoshima-deliveryhealth-area-gionnosucho.php | .php | 公開入口PHP |
| 151 | kagoshima-deliveryhealth-area-gofukucho.php | .php | 公開入口PHP |
| 152 | kagoshima-deliveryhealth-area-gokabeppucho.php | .php | 公開入口PHP |
| 153 | kagoshima-deliveryhealth-area-hananohikarigaoka.php | .php | 公開入口PHP |
| 154 | kagoshima-deliveryhealth-area-hirakawacho.php | .php | 公開入口PHP |
| 155 | kagoshima-deliveryhealth-area-ikenouecho.php | .php | 公開入口PHP |
| 156 | kagoshima-deliveryhealth-area-inaricho.php | .php | 公開入口PHP |
| 157 | kagoshima-deliveryhealth-area-inusakocho.php | .php | 公開入口PHP |
| 158 | kagoshima-deliveryhealth-area-irisacho.php | .php | 公開入口PHP |
| 159 | kagoshima-deliveryhealth-area-ishidanicho.php | .php | 公開入口PHP |
| 160 | kagoshima-deliveryhealth-area-ishiki.php | .php | 公開入口PHP |
| 161 | kagoshima-deliveryhealth-area-ishikidai.php | .php | 公開入口PHP |
| 162 | kagoshima-deliveryhealth-area-izumicho.php | .php | 公開入口PHP |
| 163 | kagoshima-deliveryhealth-area-kajiyacho.php | .php | 公開入口PHP |
| 164 | kagoshima-deliveryhealth-area-kamifukumotocho.php | .php | 公開入口PHP |
| 165 | kagoshima-deliveryhealth-area-kamihonmachi.php | .php | 公開入口PHP |
| 166 | kagoshima-deliveryhealth-area-kamitaniguchicho.php | .php | 公開入口PHP |
| 167 | kagoshima-deliveryhealth-area-kamitatsuocho.php | .php | 公開入口PHP |
| 168 | kagoshima-deliveryhealth-area-kamoike.php | .php | 公開入口PHP |
| 169 | kagoshima-deliveryhealth-area-kamoikeshinmachi.php | .php | 公開入口PHP |
| 170 | kagoshima-deliveryhealth-area-kasugacho.php | .php | 公開入口PHP |
| 171 | kagoshima-deliveryhealth-area-kawadacho.php | .php | 公開入口PHP |
| 172 | kagoshima-deliveryhealth-area-kawakamicho.php | .php | 公開入口PHP |
| 173 | kagoshima-deliveryhealth-area-kenohikarigaoka.php | .php | 公開入口PHP |
| 174 | kagoshima-deliveryhealth-area-kibougaokacho.php | .php | 公開入口PHP |
| 175 | kagoshima-deliveryhealth-area-kiirecho.php | .php | 公開入口PHP |
| 176 | kagoshima-deliveryhealth-area-kiirehitokuracho.php | .php | 公開入口PHP |
| 177 | kagoshima-deliveryhealth-area-kiireikemicho.php | .php | 公開入口PHP |
| 178 | kagoshima-deliveryhealth-area-kiireikkuracho.php | .php | 公開入口PHP |
| 179 | kagoshima-deliveryhealth-area-kiiremaenohamacho.php | .php | 公開入口PHP |
| 180 | kagoshima-deliveryhealth-area-kiirenakamyoch.php | .php | 公開入口PHP |
| 181 | kagoshima-deliveryhealth-area-kiirenakamyocho.php | .php | 公開入口PHP |
| 182 | kagoshima-deliveryhealth-area-kiiresesekushicho.php | .php | 公開入口PHP |
| 183 | kagoshima-deliveryhealth-area-kinkocho.php | .php | 公開入口PHP |
| 184 | kagoshima-deliveryhealth-area-kinkodai.php | .php | 公開入口PHP |
| 185 | kagoshima-deliveryhealth-area-kinseicho.php | .php | 公開入口PHP |
| 186 | kagoshima-deliveryhealth-area-komatsubara.php | .php | 公開入口PHP |
| 187 | kagoshima-deliveryhealth-area-koraicho.php | .php | 公開入口PHP |
| 188 | kagoshima-deliveryhealth-area-korimoto.php | .php | 公開入口PHP |
| 189 | kagoshima-deliveryhealth-area-korimotocho.php | .php | 公開入口PHP |
| 190 | kagoshima-deliveryhealth-area-koriyamacho.php | .php | 公開入口PHP |
| 191 | kagoshima-deliveryhealth-area-koriyamadakecho.php | .php | 公開入口PHP |
| 192 | kagoshima-deliveryhealth-area-kotsukicho.php | .php | 公開入口PHP |
| 193 | kagoshima-deliveryhealth-area-koutokujidai.php | .php | 公開入口PHP |
| 194 | kagoshima-deliveryhealth-area-koyamadacho.php | .php | 公開入口PHP |
| 195 | kagoshima-deliveryhealth-area-koyo.php | .php | 公開入口PHP |
| 196 | kagoshima-deliveryhealth-area-nagayoshi.php | .php | 公開入口PHP |
| 197 | kagoshima-deliveryhealth-area-obaracho.php | .php | 公開入口PHP |
| 198 | kagoshima-deliveryhealth-area-ogawacho.php | .php | 公開入口PHP |
| 199 | kagoshima-deliveryhealth-area-okanoharacho.php | .php | 公開入口PHP |
| 200 | kagoshima-deliveryhealth-area-ono.php | .php | 公開入口PHP |
| 201 | kagoshima-deliveryhealth-area-oroshihommachi.php | .php | 公開入口PHP |
| 202 | kagoshima-deliveryhealth-area-sakamotocho.php | .php | 公開入口PHP |
| 203 | kagoshima-deliveryhealth-area-sakanoue.php | .php | 公開入口PHP |
| 204 | kagoshima-deliveryhealth-area-sakuragaoka.php | .php | 公開入口PHP |
| 205 | kagoshima-deliveryhealth-area-sanwacho.php | .php | 公開入口PHP |
| 206 | kagoshima-deliveryhealth-area-shimizucho.php | .php | 公開入口PHP |
| 207 | kagoshima-deliveryhealth-area-shimoarata.php | .php | 公開入口PHP |
| 208 | kagoshima-deliveryhealth-area-shimofukumotocho.php | .php | 公開入口PHP |
| 209 | kagoshima-deliveryhealth-area-shimoishiki.php | .php | 公開入口PHP |
| 210 | kagoshima-deliveryhealth-area-shimoishikicho.php | .php | 公開入口PHP |
| 211 | kagoshima-deliveryhealth-area-shimotacho.php | .php | 公開入口PHP |
| 212 | kagoshima-deliveryhealth-area-shimotatsuocho.php | .php | 公開入口PHP |
| 213 | kagoshima-deliveryhealth-area-uearatacho.php | .php | 公開入口PHP |
| 214 | kagoshima-deliveryhealth-area-uenosonocho.php | .php | 公開入口PHP |
| 215 | kagoshima-deliveryhealth-area-uomicho.php | .php | 公開入口PHP |
| 216 | kagoshima-deliveryhealth-area-usuki.php | .php | 公開入口PHP |
| 217 | kagoshima-deliveryhealth-area-yakushi.php | .php | 公開入口PHP |
| 218 | kagoshima-deliveryhealth-area-yasuicho.php | .php | 公開入口PHP |
| 219 | kagoshima-deliveryhealth-blog-glamourgirl.php | .php | 公開入口PHP |
| 220 | kagoshima-deliveryhealth-blog-petitegirl.php | .php | 公開入口PHP |
| 221 | kagoshima-deliveryhealth-blog-poccharigirl.php | .php | 公開入口PHP |
| 222 | kagoshima-deliveryhealth-blog-shiroutogirl.php | .php | 公開入口PHP |
| 223 | kagoshima-deliveryhealth-blog-slendergirl.php | .php | 公開入口PHP |
| 224 | kagoshima-deliveryhealth-blog-tallbeautygirl.php | .php | 公開入口PHP |
| 225 | kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.php | .php | 公開入口PHP |
| 226 | kagoshima-deliveryhealth-hotel-hotelm.php | .php | 公開入口PHP |
| 227 | kagoshima-deliveryhealth-hotel-villacosta500.php | .php | 公開入口PHP |
| 228 | main.php | .php | 公開入口PHP |
| 229 | makeSitemap.php | .php | サイトマップ生成PHP |
| 230 | movie.php | .php | 公開入口PHP |
| 231 | movie_iframe.php | .php | 公開入口PHP |
| 232 | mypage.php | .php | 公開入口PHP |
| 233 | news.php | .php | 公開入口PHP |
| 234 | page.php | .php | 公開入口PHP |
| 235 | schedule.php | .php | 公開入口PHP |
| 236 | sitemap.xml | .xml | 非コード資産 |
| 237 | source/area.html | .html | source HTMLテンプレート |
| 238 | source/blog.html | .html | source HTMLテンプレート |
| 239 | source/contact.html | .html | source HTMLテンプレート |
| 240 | source/create.html | .html | source HTMLテンプレート |
| 241 | source/girls.html | .html | source HTMLテンプレート |
| 242 | source/girls_list.html | .html | source HTMLテンプレート |
| 243 | source/hotel.html | .html | source HTMLテンプレート |
| 244 | source/index.html | .html | source HTMLテンプレート |
| 245 | source/kagoshima-deliveryhealth-area-arata.html | .html | source HTMLテンプレート |
| 246 | source/kagoshima-deliveryhealth-area-ariyadacho.html | .html | source HTMLテンプレート |
| 247 | source/kagoshima-deliveryhealth-area-gionnosucho.html | .html | source HTMLテンプレート |
| 248 | source/kagoshima-deliveryhealth-area-gofukucho.html | .html | source HTMLテンプレート |
| 249 | source/kagoshima-deliveryhealth-area-gokabeppucho.html | .html | source HTMLテンプレート |
| 250 | source/kagoshima-deliveryhealth-area-hananohikarigaoka.html | .html | source HTMLテンプレート |
| 251 | source/kagoshima-deliveryhealth-area-ikenouecho.html | .html | source HTMLテンプレート |
| 252 | source/kagoshima-deliveryhealth-area-inaricho.html | .html | source HTMLテンプレート |
| 253 | source/kagoshima-deliveryhealth-area-inusakocho.html | .html | source HTMLテンプレート |
| 254 | source/kagoshima-deliveryhealth-area-irisacho.html | .html | source HTMLテンプレート |
| 255 | source/kagoshima-deliveryhealth-area-ishidanicho.html | .html | source HTMLテンプレート |
| 256 | source/kagoshima-deliveryhealth-area-ishiki.html | .html | source HTMLテンプレート |
| 257 | source/kagoshima-deliveryhealth-area-ishikidai.html | .html | source HTMLテンプレート |
| 258 | source/kagoshima-deliveryhealth-area-izumicho.html | .html | source HTMLテンプレート |
| 259 | source/kagoshima-deliveryhealth-area-kajiyacho.html | .html | source HTMLテンプレート |
| 260 | source/kagoshima-deliveryhealth-area-kamoike.html | .html | source HTMLテンプレート |
| 261 | source/kagoshima-deliveryhealth-area-kamoikeshinmachi.html | .html | source HTMLテンプレート |
| 262 | source/kagoshima-deliveryhealth-area-kasugacho.html | .html | source HTMLテンプレート |
| 263 | source/kagoshima-deliveryhealth-area-kenohikarigaoka.html | .html | source HTMLテンプレート |
| 264 | source/kagoshima-deliveryhealth-area-kibougaokacho.html | .html | source HTMLテンプレート |
| 265 | source/kagoshima-deliveryhealth-area-kiirecho.html | .html | source HTMLテンプレート |
| 266 | source/kagoshima-deliveryhealth-area-kiirehitokuracho.html | .html | source HTMLテンプレート |
| 267 | source/kagoshima-deliveryhealth-area-kiireikemicho.html | .html | source HTMLテンプレート |
| 268 | source/kagoshima-deliveryhealth-area-kiireikkuracho.html | .html | source HTMLテンプレート |
| 269 | source/kagoshima-deliveryhealth-area-kiiremaenohamacho.html | .html | source HTMLテンプレート |
| 270 | source/kagoshima-deliveryhealth-area-kiirenakamyocho.html | .html | source HTMLテンプレート |
| 271 | source/kagoshima-deliveryhealth-area-kiiresesekushicho.html | .html | source HTMLテンプレート |
| 272 | source/kagoshima-deliveryhealth-area-kinkocho.html | .html | source HTMLテンプレート |
| 273 | source/kagoshima-deliveryhealth-area-kinkodai.html | .html | source HTMLテンプレート |
| 274 | source/kagoshima-deliveryhealth-area-kinseicho.html | .html | source HTMLテンプレート |
| 275 | source/kagoshima-deliveryhealth-area-koraicho.html | .html | source HTMLテンプレート |
| 276 | source/kagoshima-deliveryhealth-area-korimoto.html | .html | source HTMLテンプレート |
| 277 | source/kagoshima-deliveryhealth-area-korimotocho.html | .html | source HTMLテンプレート |
| 278 | source/kagoshima-deliveryhealth-area-koriyamacho.html | .html | source HTMLテンプレート |
| 279 | source/kagoshima-deliveryhealth-area-koriyamadakecho.html | .html | source HTMLテンプレート |
| 280 | source/kagoshima-deliveryhealth-area-kotsukicho.html | .html | source HTMLテンプレート |
| 281 | source/kagoshima-deliveryhealth-area-koutokujidai.html | .html | source HTMLテンプレート |
| 282 | source/kagoshima-deliveryhealth-area-koyamadacho.html | .html | source HTMLテンプレート |
| 283 | source/kagoshima-deliveryhealth-area-koyo.html | .html | source HTMLテンプレート |
| 284 | source/kagoshima-deliveryhealth-area-nagayoshi.html | .html | source HTMLテンプレート |
| 285 | source/kagoshima-deliveryhealth-area-obaracho.html | .html | source HTMLテンプレート |
| 286 | source/kagoshima-deliveryhealth-area-ogawacho.html | .html | source HTMLテンプレート |
| 287 | source/kagoshima-deliveryhealth-area-okanoharacho.html | .html | source HTMLテンプレート |
| 288 | source/kagoshima-deliveryhealth-area-ono.html | .html | source HTMLテンプレート |
| 289 | source/kagoshima-deliveryhealth-area-oroshihommachi.html | .html | source HTMLテンプレート |
| 290 | source/kagoshima-deliveryhealth-area-sakamotocho.html | .html | source HTMLテンプレート |
| 291 | source/kagoshima-deliveryhealth-area-sakanoue.html | .html | source HTMLテンプレート |
| 292 | source/kagoshima-deliveryhealth-area-sakuragaoka.html | .html | source HTMLテンプレート |
| 293 | source/kagoshima-deliveryhealth-area-sanwacho.html | .html | source HTMLテンプレート |
| 294 | source/kagoshima-deliveryhealth-area-shimoarata.html | .html | source HTMLテンプレート |
| 295 | source/kagoshima-deliveryhealth-area-shimofukumotocho.html | .html | source HTMLテンプレート |
| 296 | source/kagoshima-deliveryhealth-area-shimoishiki.html | .html | source HTMLテンプレート |
| 297 | source/kagoshima-deliveryhealth-area-shimoishikicho.html | .html | source HTMLテンプレート |
| 298 | source/kagoshima-deliveryhealth-area-shimotacho.html | .html | source HTMLテンプレート |
| 299 | source/kagoshima-deliveryhealth-area-shimotatsuocho.html | .html | source HTMLテンプレート |
| 300 | source/kagoshima-deliveryhealth-area-uearatacho.html | .html | source HTMLテンプレート |
| 301 | source/kagoshima-deliveryhealth-area-uenosonocho.html | .html | source HTMLテンプレート |
| 302 | source/kagoshima-deliveryhealth-area-uomicho.html | .html | source HTMLテンプレート |
| 303 | source/kagoshima-deliveryhealth-area-usuki.html | .html | source HTMLテンプレート |
| 304 | source/kagoshima-deliveryhealth-area-yakushi.html | .html | source HTMLテンプレート |
| 305 | source/kagoshima-deliveryhealth-area-yasuicho.html | .html | source HTMLテンプレート |
| 306 | source/kagoshima-deliveryhealth-blog-glamourgirl.html | .html | source HTMLテンプレート |
| 307 | source/kagoshima-deliveryhealth-blog-petitegirl.html | .html | source HTMLテンプレート |
| 308 | source/kagoshima-deliveryhealth-blog-poccharigirl.html | .html | source HTMLテンプレート |
| 309 | source/kagoshima-deliveryhealth-blog-shiroutogirl.html | .html | source HTMLテンプレート |
| 310 | source/kagoshima-deliveryhealth-blog-slendergirl.html | .html | source HTMLテンプレート |
| 311 | source/kagoshima-deliveryhealth-blog-tallbeautygirl.html | .html | source HTMLテンプレート |
| 312 | source/kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.html | .html | source HTMLテンプレート |
| 313 | source/kagoshima-deliveryhealth-hotel-hotelm.html | .html | source HTMLテンプレート |
| 314 | source/kagoshima-deliveryhealth-hotel-villacosta500.html | .html | source HTMLテンプレート |
| 315 | source/movie.html | .html | source HTMLテンプレート |
| 316 | source/movie_iframe.html | .html | source HTMLテンプレート |
| 317 | source/mypage.html | .html | source HTMLテンプレート |
| 318 | source/news.html | .html | source HTMLテンプレート |
| 319 | source/schedule.html | .html | source HTMLテンプレート |
| 320 | source/style.css | .css | 非コード資産 |
| 321 | source/system.html | .html | source HTMLテンプレート |
| 322 | source/template_girls.html | .html | source HTMLテンプレート |
| 323 | source/template_kagoshima-deliveryhealth-area.html | .html | source HTMLテンプレート |
| 324 | source/template_kagoshima-deliveryhealth-blog.html | .html | source HTMLテンプレート |
| 325 | source/template_kagoshima-deliveryhealth-hotel.html | .html | source HTMLテンプレート |
| 326 | source/template_shop.html | .html | source HTMLテンプレート |
| 327 | system.php | .php | 公開入口PHP |
| 328 | test.php | .php | 公開入口PHP |
