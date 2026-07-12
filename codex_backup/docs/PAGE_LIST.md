# PAGE_LIST

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`

## 前提

| 項目 | 内容 |
|---|---|
| ドメイン | 不明 |
| 公開状態 | 公開前データ。開発中は `noindex` を設定する前提 |
| 想定URL | ドメイン未確認のため `/ファイル名.php` として記載 |
| 公開ファイル | ルート直下 PHP |
| テンプレート | `H:\Data\01_CTI\candy_HP\source\*.html` |
| 共通生成処理 | `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` |
| 置換トークン | `rep000...eot` 形式 |

## CSS / JS 凡例

| 表記 | 実ファイル |
|---|---|
| 共通CSS | `H:\Data\01_CTI\candy_HP\css\default.css` |
| 記事CSS | `H:\Data\01_CTI\candy_HP\source\style.css` |
| 女の子詳細CSS | `H:\Data\01_CTI\candy_HP\css\girls.css`、`H:\Data\01_CTI\candy_HP\css\jquery.fs.boxer.css` |
| 女の子一覧CSS | `H:\Data\01_CTI\candy_HP\css\girls_list.css` |
| 出勤CSS | `H:\Data\01_CTI\candy_HP\css\schedule.css`、`H:\Data\01_CTI\candy_HP\css\colorbox.css` |
| 料金CSS | `H:\Data\01_CTI\candy_HP\css\system.css`、`H:\Data\01_CTI\candy_HP\css\colorbox.css` |
| 動画CSS | `H:\Data\01_CTI\candy_HP\css\movie.css`、`H:\Data\01_CTI\candy_HP\css\colorbox.css` |
| マイページCSS | `H:\Data\01_CTI\candy_HP\css\mypage.css`、`H:\Data\01_CTI\candy_HP\css\colorbox.css` |
| NEWS CSS | `H:\Data\01_CTI\candy_HP\css\news.css` |
| 共通JS | 外部 `https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js`、`H:\Data\01_CTI\candy_HP\js\amadare_webapp2.4.php`、`H:\Data\01_CTI\candy_HP\js\common.js`、Google tag |
| 計測JS | `H:\Data\01_CTI\candy_HP\js\amadareAccess.1.0.js` |
| お気に入りJS | `H:\Data\01_CTI\candy_HP\js\fav.js` 参照あり。ただし実ファイルは未確認 |
| モーダルJS | `H:\Data\01_CTI\candy_HP\js\jquery.colorbox-min.js` |
| トップJS | `H:\Data\01_CTI\candy_HP\js\candyTile.js`、`H:\Data\01_CTI\candy_HP\js\commonLite.js` |

## 主要ページ

| ページ名 | ファイルパス | 想定URL | 役割 | 使用CSS | 使用JS | 主な画像 | 備考 |
|---|---|---|---|---|---|---|---|
| トップ | 公開: `H:\Data\01_CTI\candy_HP\index.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\index.html` | `/index.php` | サイト入口、店舗説明、導線集約 | 共通CSS、記事CSS | 共通JS、トップJS、計測JS | `H:\Data\01_CTI\candy_HP\imgHtml` 配下、女の子・店舗画像 | 公開前のため `robots noindex`、置換トークンあり、`____link____` placeholder あり |
| NEWS | 公開: `H:\Data\01_CTI\candy_HP\news.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\news.html` | `/news.php` | 新着情報 | 共通CSS、NEWS CSS | 共通JS、計測JS | NEWS 画像 | 置換トークンあり |
| 女の子一覧 | 公開: `H:\Data\01_CTI\candy_HP\girls_list.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\girls_list.html` | `/girls_list.php` | 在籍一覧 | 共通CSS、女の子一覧CSS | 共通JS、お気に入りJS、計測JS | 女の子画像 | `robots index`、`fav.js` 参照あり・実ファイルなし |
| 女の子詳細 | 公開: `H:\Data\01_CTI\candy_HP\girls.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\girls.html` | `/girls.php` | 個別プロフィール | 共通CSS、女の子詳細CSS | 共通JS、モーダルJS、お気に入りJS、計測JS、`love2.js` | 女の子画像、プロフィール画像 | URL パラメータ `no` を使う想定 |
| 出勤情報 | 公開: `H:\Data\01_CTI\candy_HP\schedule.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\schedule.html` | `/schedule.php` | 本日・週間出勤 | 共通CSS、出勤CSS | 共通JS、モーダルJS、お気に入りJS、計測JS | 女の子画像 | タブ切替あり |
| 料金・システム | 公開: `H:\Data\01_CTI\candy_HP\system.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\system.html` | `/system.php` | 料金、利用方法、注意事項、決済導線 | 共通CSS、料金CSS | 共通JS、モーダルJS、お気に入りJS、計測JS | クレジットカード・説明画像 | 外部決済 POST フォームあり |
| 動画一覧 | 公開: `H:\Data\01_CTI\candy_HP\movie.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\movie.html` | `/movie.php` | 女の子動画一覧 | 共通CSS、動画CSS | 共通JS、お気に入りJS、計測JS | 動画サムネイル | `matchMedia.matches` の JS ロジック候補あり |
| 動画 iframe | 公開: `H:\Data\01_CTI\candy_HP\movie_iframe.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\movie_iframe.html` | `/movie_iframe.php` | 動画埋め込み | 不明 | Google tag | 不明 | title / description は未確認 |
| マイページ | 公開: `H:\Data\01_CTI\candy_HP\mypage.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\mypage.html` | `/mypage.php` | お気に入り・マイページ | 共通CSS、マイページCSS | 共通JS、モーダルJS、お気に入りJS、計測JS | 女の子画像 | Cookie 連動の可能性 |
| 対応エリア一覧 | 公開: `H:\Data\01_CTI\candy_HP\area.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\area.html` | `/area.php` | エリアページ一覧 | 共通CSS、記事CSS | 共通JS、Google tag | なし | 存在しないエリア PHP へのリンク候補あり |
| ホテル一覧 | 公開: `H:\Data\01_CTI\candy_HP\hotel.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\hotel.html` | `/hotel.php` | ホテル一覧 | 共通CSS、記事CSS | 共通JS、Google tag | ホテル画像 | placeholder `aaaaaaaaaa` と未作成ホテルリンク候補あり |
| ブログ一覧 | 公開: `H:\Data\01_CTI\candy_HP\blog.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\blog.html` | `/blog.php` | ブログ・SEO記事一覧 | 共通CSS、記事CSS | 共通JS、Google tag | ブログ画像 | 外部 SNS / ブログ導線あり |
| contact | 公開: `H:\Data\01_CTI\candy_HP\contact.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\contact.html` | `/contact.php` | 名称上は問い合わせ候補 | 共通CSS、記事CSS | 共通JS、Google tag | サンプル画像 | placeholder `aaaaaaaaaaaaaaa`。問い合わせフォームは未確認 |
| create | 公開: `H:\Data\01_CTI\candy_HP\create.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\create.html` | `/create.php` | 管理・ページ作成系 | 共通CSS、記事CSS | 共通JS、Google tag | サンプル画像 | PHP 本体は認証・ファイル生成処理あり。通常ページ扱い禁止 |

## エリアページ

共通: CSS は共通CSS・記事CSS、JS は共通JS・Google tag、主な画像は `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area` と `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\shop` 配下。

| ページ名 | ファイルパス | 想定URL | 役割 | 使用CSS | 使用JS | 主な画像 | 備考 |
|---|---|---|---|---|---|---|---|
| 鹿児島市荒田 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-arata.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-arata.html` | `/kagoshima-deliveryhealth-area-arata.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市有屋田町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ariyadacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ariyadacho.html` | `/kagoshima-deliveryhealth-area-ariyadacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市祇園之洲町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-gionnosucho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-gionnosucho.html` | `/kagoshima-deliveryhealth-area-gionnosucho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder `aaaaaaaaaaaaaaaaaaaa` |
| 鹿児島市呉服町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-gofukucho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-gofukucho.html` | `/kagoshima-deliveryhealth-area-gofukucho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市五ヶ別府町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-gokabeppucho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-gokabeppucho.html` | `/kagoshima-deliveryhealth-area-gokabeppucho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市花野光ヶ丘 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-hananohikarigaoka.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-hananohikarigaoka.html` | `/kagoshima-deliveryhealth-area-hananohikarigaoka.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市池之上町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ikenouecho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ikenouecho.html` | `/kagoshima-deliveryhealth-area-ikenouecho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市稲荷町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-inaricho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-inaricho.html` | `/kagoshima-deliveryhealth-area-inaricho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市犬迫町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-inusakocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-inusakocho.html` | `/kagoshima-deliveryhealth-area-inusakocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像パス切れ候補 |
| 鹿児島市入佐町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-irisacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-irisacho.html` | `/kagoshima-deliveryhealth-area-irisacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市石谷町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ishidanicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ishidanicho.html` | `/kagoshima-deliveryhealth-area-ishidanicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市伊敷 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ishiki.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ishiki.html` | `/kagoshima-deliveryhealth-area-ishiki.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市伊敷台 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ishikidai.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ishikidai.html` | `/kagoshima-deliveryhealth-area-ishikidai.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市泉町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-izumicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-izumicho.html` | `/kagoshima-deliveryhealth-area-izumicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市加治屋町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kajiyacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kajiyacho.html` | `/kagoshima-deliveryhealth-area-kajiyacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市鴨池 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamoike.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamoike.html` | `/kagoshima-deliveryhealth-area-kamoike.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市鴨池新町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamoikeshinmachi.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kamoikeshinmachi.html` | `/kagoshima-deliveryhealth-area-kamoikeshinmachi.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市春日町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kasugacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kasugacho.html` | `/kagoshima-deliveryhealth-area-kasugacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市花野光ヶ丘 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kenohikarigaoka.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kenohikarigaoka.html` | `/kagoshima-deliveryhealth-area-kenohikarigaoka.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像名の `.1.jpg` / `.2.jpg` 表記がパス切れ候補 |
| 鹿児島市希望ヶ丘町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kibougaokacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kibougaokacho.html` | `/kagoshima-deliveryhealth-area-kibougaokacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市喜入町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiirecho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiirecho.html` | `/kagoshima-deliveryhealth-area-kiirecho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市喜入一倉町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiirehitokuracho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiirehitokuracho.html` | `/kagoshima-deliveryhealth-area-kiirehitokuracho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市喜入生見町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiireikemicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiireikemicho.html` | `/kagoshima-deliveryhealth-area-kiireikemicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市喜入一倉町候補 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiireikkuracho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiireikkuracho.html` | `/kagoshima-deliveryhealth-area-kiireikkuracho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市喜入前之浜町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiiremaenohamacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiiremaenohamacho.html` | `/kagoshima-deliveryhealth-area-kiiremaenohamacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市喜入中名町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiirenakamyocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiirenakamyocho.html` | `/kagoshima-deliveryhealth-area-kiirenakamyocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市喜入瀬々串町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiiresesekushicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kiiresesekushicho.html` | `/kagoshima-deliveryhealth-area-kiiresesekushicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市錦江町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kinkocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kinkocho.html` | `/kagoshima-deliveryhealth-area-kinkocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市錦江台 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kinkodai.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kinkodai.html` | `/kagoshima-deliveryhealth-area-kinkodai.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市金生町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kinseicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kinseicho.html` | `/kagoshima-deliveryhealth-area-kinseicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市高麗町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koraicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koraicho.html` | `/kagoshima-deliveryhealth-area-koraicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市郡元 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-korimoto.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-korimoto.html` | `/kagoshima-deliveryhealth-area-korimoto.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市郡元町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-korimotocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-korimotocho.html` | `/kagoshima-deliveryhealth-area-korimotocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市郡山町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koriyamacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koriyamacho.html` | `/kagoshima-deliveryhealth-area-koriyamacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市郡山岳町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koriyamadakecho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koriyamadakecho.html` | `/kagoshima-deliveryhealth-area-koriyamadakecho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市小松原町候補 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kotsukicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-kotsukicho.html` | `/kagoshima-deliveryhealth-area-kotsukicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市皇徳寺台 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koutokujidai.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koutokujidai.html` | `/kagoshima-deliveryhealth-area-koutokujidai.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市小山田町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koyamadacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koyamadacho.html` | `/kagoshima-deliveryhealth-area-koyamadacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市向陽 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-koyo.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-koyo.html` | `/kagoshima-deliveryhealth-area-koyo.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市小原町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-obaracho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-obaracho.html` | `/kagoshima-deliveryhealth-area-obaracho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市小川町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ogawacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ogawacho.html` | `/kagoshima-deliveryhealth-area-ogawacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市岡之原町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-okanoharacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-okanoharacho.html` | `/kagoshima-deliveryhealth-area-okanoharacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市小野 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-ono.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-ono.html` | `/kagoshima-deliveryhealth-area-ono.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市卸本町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-oroshihommachi.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-oroshihommachi.html` | `/kagoshima-deliveryhealth-area-oroshihommachi.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市坂元町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-sakamotocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-sakamotocho.html` | `/kagoshima-deliveryhealth-area-sakamotocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市坂之上 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-sakanoue.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-sakanoue.html` | `/kagoshima-deliveryhealth-area-sakanoue.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市桜ヶ丘 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-sakuragaoka.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-sakuragaoka.html` | `/kagoshima-deliveryhealth-area-sakuragaoka.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder |
| 鹿児島市三和町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-sanwacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-sanwacho.html` | `/kagoshima-deliveryhealth-area-sanwacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市下荒田 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimoarata.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimoarata.html` | `/kagoshima-deliveryhealth-area-shimoarata.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像パス切れ候補 |
| 鹿児島市下福元町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimofukumotocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimofukumotocho.html` | `/kagoshima-deliveryhealth-area-shimofukumotocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市下伊敷 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimoishiki.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimoishiki.html` | `/kagoshima-deliveryhealth-area-shimoishiki.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像パス切れ候補 |
| 鹿児島市下伊敷町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimoishikicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimoishikicho.html` | `/kagoshima-deliveryhealth-area-shimoishikicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像パス切れ候補 |
| 鹿児島市下田町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimotacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimotacho.html` | `/kagoshima-deliveryhealth-area-shimotacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市下竜尾町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimotatsuocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-shimotatsuocho.html` | `/kagoshima-deliveryhealth-area-shimotatsuocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area 画像 | placeholder、画像パス切れ候補 |
| 鹿児島市上荒田町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-uearatacho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-uearatacho.html` | `/kagoshima-deliveryhealth-area-uearatacho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市上之園町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-uenosonocho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-uenosonocho.html` | `/kagoshima-deliveryhealth-area-uenosonocho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市魚見町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-uomicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-uomicho.html` | `/kagoshima-deliveryhealth-area-uomicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市宇宿 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-usuki.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-usuki.html` | `/kagoshima-deliveryhealth-area-usuki.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |
| 鹿児島市薬師 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-yakushi.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-yakushi.html` | `/kagoshima-deliveryhealth-area-yakushi.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 画像パス切れ候補 |
| 鹿児島市易居町 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-yasuicho.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-area-yasuicho.html` | `/kagoshima-deliveryhealth-area-yasuicho.php` | エリア紹介 | 共通CSS、記事CSS | 共通JS | area/shop 画像 | 確認済み |

## ブログ詳細ページ

共通: CSS は共通CSS・記事CSS、JS は共通JS・Google tag、主な画像は `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\blog` と `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\girl` 配下。

| ページ名 | ファイルパス | 想定URL | 役割 | 使用CSS | 使用JS | 主な画像 | 備考 |
|---|---|---|---|---|---|---|---|
| グラマー美女記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-glamourgirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-glamourgirl.html` | `/kagoshima-deliveryhealth-blog-glamourgirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |
| 小柄な女の子記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-petitegirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-petitegirl.html` | `/kagoshima-deliveryhealth-blog-petitegirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |
| ぽっちゃり美女記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-poccharigirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-poccharigirl.html` | `/kagoshima-deliveryhealth-blog-poccharigirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |
| 素人っぽい女の子記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-shiroutogirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-shiroutogirl.html` | `/kagoshima-deliveryhealth-blog-shiroutogirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |
| スレンダー記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-slendergirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-slendergirl.html` | `/kagoshima-deliveryhealth-blog-slendergirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |
| 長身美女記事 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-blog-tallbeautygirl.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-blog-tallbeautygirl.html` | `/kagoshima-deliveryhealth-blog-tallbeautygirl.php` | SEO 記事 | 共通CSS、記事CSS | 共通JS | blog/girl 画像 | 公開前のため `robots noindex` |

## ホテル詳細ページ

共通: CSS は共通CSS・記事CSS、JS は共通JS・Google tag、主な画像は `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\hotel` 配下。

| ページ名 | ファイルパス | 想定URL | 役割 | 使用CSS | 使用JS | 主な画像 | 備考 |
|---|---|---|---|---|---|---|---|
| グリーンリッチホテル鹿児島天文館 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.html` | `/kagoshima-deliveryhealth-hotel-greenrichkagoshimatenmonkan.php` | ホテル情報 | 共通CSS、記事CSS | 共通JS | hotel 画像 | Google Map 埋め込み/リンク候補 |
| Hotel M | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-hotel-hotelm.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-hotel-hotelm.html` | `/kagoshima-deliveryhealth-hotel-hotelm.php` | ホテル情報 | 共通CSS、記事CSS | 共通JS | hotel 画像 | Google Map 埋め込み/リンク候補 |
| ヴィラコスタ500 | 公開: `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-hotel-villacosta500.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\kagoshima-deliveryhealth-hotel-villacosta500.html` | `/kagoshima-deliveryhealth-hotel-villacosta500.php` | ホテル情報 | 共通CSS、記事CSS | 共通JS | hotel 画像 | Google Map 埋め込み/リンク候補 |

## テンプレート専用と思われる HTML

以下は `source` 配下に存在しますが、同名の公開 PHP は確認できませんでした。削除禁止です。

| テンプレート | ファイルパス | 想定用途 | 備考 |
|---|---|---|---|
| 女の子テンプレート | `H:\Data\01_CTI\candy_HP\source\template_girls.html` | 女の子ページ生成元 | 推測 |
| エリアテンプレート | `H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-area.html` | エリアページ生成元 | placeholder あり |
| ブログテンプレート | `H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-blog.html` | ブログページ生成元 | placeholder あり |
| ホテルテンプレート | `H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-hotel.html` | ホテルページ生成元 | placeholder あり |
| 店舗テンプレート | `H:\Data\01_CTI\candy_HP\source\template_shop.html` | 店舗紹介生成元 | 推測 |

## フォーム・ボタン・リンク

| 種別 | 確認済み内容 |
|---|---|
| 通常問い合わせフォーム | 未確認 |
| クレジット決済フォーム | `H:\Data\01_CTI\candy_HP\source\system.html` に外部 POST フォームあり |
| 電話リンク | `tel:0992266956` を複数テンプレートで確認 |
| 外部求人リンク | `http://new-cast.com/` を確認 |
| 外部写メ日記リンク | `https://www.cityheaven.net/...` を確認 |
| 外部 SNS / ブログリンク | `http://candy6956.blog.fc2.com/` を確認 |
| Google Map | ホテル・エリア系ページでリンク/埋め込みを確認 |

## 未確認

| 項目 | 状態 |
|---|---|
| 各ページの本番 URL | ドメイン不明 |
| 実ブラウザで全ページが表示されるか | 未確認 |
| PHP 実行時に全置換トークンが埋まるか | 未確認 |
| DB 由来画像の実 URL | 不明 |
| `source\template_*.html` の実運用 | 不明 |
