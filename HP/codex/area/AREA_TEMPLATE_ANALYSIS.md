# AREA_TEMPLATE_ANALYSIS

作成日: 2026-06-05
対象テンプレート: H:\Data\01_CTI\candy_HP\source\template_kagoshima-deliveryhealth-area.html

## 結論

このテンプレートは対応エリア詳細ページの生成元です。area名、slug、画像、Map、ホテル/スポット情報、JSON-LD内のURL・名称・position・店舗情報を置換する前提で、placeholderが多数残っています。

## テンプレート基本情報

| 項目 | 内容 |
| --- | --- |
| robots | noindex |
| description | 鹿児島「aaaaaaaaaaaaaaaaaaaa」で呼べる人気デリヘル店舗情報！交通費・ホテル一覧も掲載中！ |
| keywords | なし |
| title | 鹿児島市aaaaaaaaaaaaaaaaaaaaで呼べるデリヘル｜対応店舗・ホテル情報 |
| canonical | https://www.55810.com/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa.php |
| OGP | og:title=鹿児島市aaaaaaaaaaaaaaaaaaaaで呼べるデリヘル｜対応店舗・ホテル情報 / og:url=https://www.55810.com/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa.php / og:image=https://www.55810.com/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_1.jpg |
| JSON-LD | 2 ブロック。BreadcrumbList / ItemList 系を確認。placeholderにより構文不正候補あり |
| h1 | 鹿児島市aaaaaaaaaaaaaaaaaaaaで呼べるデリヘル |
| CSS | ./source/style.css / ./css/default.css |
| JS | https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js / ./js/amadare_webapp2.4.php / ./js/common.js / https://www.googletagmanager.com/gtag/js?id=G-0VBTBPHDD2 |

## ページ全体構造

1. head: robots、description、title、CSS、canonical、OGP、Google tag、JSON-LD。
2. header: 電話リンク、ロゴ、PC/SPナビ。
3. breadcrumb: TOP、対応エリア一覧、当該エリアページ名。
4. main visual: エリア画像1、h1、subtitle、description、店舗一覧導線。
5. 店舗紹介ブロック: CANDY、REBORN、人妻エステ、黒薔薇の紹介枠。
6. エリア紹介ブロック: エリア画像2、エリア説明、基本情報、Google Map iframe。
7. ホテル紹介ブロック: 近辺ホテル/宿泊施設のFAQ型カード。
8. 地図・待ち合わせブロック: 周辺スポットのFAQ型カード。
9. 関連リンクブロック: リンクタイトル placeholder。
10. footer: ナビ、電話、営業時間、コピーライト、共通JS。

## 置換が必要な箇所

- title、meta description、canonical、OGP title/url/image/description。
- JSON-LDのposition、name、item、url、telephone、description。
- breadcrumbのエリア名。
- h1、subtitle、description、各h2見出し。
- area画像1/2のファイル名とalt。
- Google Map iframeのtitle/src。
- 基本情報表の所在地等。
- ホテル/スポット名、住所、TEL、詳細リンク。
- 関連リンクのhrefとリンクテキスト。

## 共通で維持する箇所

- H:\Data\01_CTI\candy_HP\source\style.css と H:\Data\01_CTI\candy_HP\css\default.css の参照。
- header/footer の共通ナビ構造。
- 電話リンクと計測呼び出し。
- 店舗紹介ブロックの共通カード構造。
- Google tag、共通JS、レスポンシブ用class。

## 削除してはいけない箇所

- head内SEO/OGP/JSON-LD。
- breadcrumb構造。
- h1と主要h2構造。
- 店舗紹介ブロック。
- Map iframe構造。
- footerナビ、電話導線、共通JS。

## SEO上重要な箇所

- title / meta description / canonical / robots / og:title / og:url / og:image / h1。
- source HTML内では robots noindex が設定されています。公開時に解除するかはユーザー判断が必要です。
- meta keywords は確認できませんでした。

## JSON-LD上重要な箇所

- BreadcrumbList と ItemList 系のJSON-LDがあります。
- テンプレートは placeholder の position が未引用で、置換前のままではJSONとして不正候補です。
- エリア名、URL、店舗名、電話、説明をページごとに正しく反映する必要があります。

## placeholder一覧

| placeholder | 件数 | 出現箇所 | 例 |
| --- | --- | --- | --- |
| aaaaaaaaaaaaaaaaaaaa | 81 | 8, 9, 12, 14, 16, 17, 18, 41, 42, 43, 47, 48, 49, 53, 54, 55, 64, 70, 73, 74, 75, 76, 81, 84 他57件 | 不明 |

## 画像参照

| 参照元 | 画像パス | 種別 | 実ファイル | ローカル候補 |
| --- | --- | --- | --- | --- |
| img src | ./imgHtml/new_202601/area/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_1.jpg | area画像 | なし | H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area\kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_1.jpg |
| img src | ./imgHtml/new_202601/area/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_2.jpg | area画像 | なし | H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area\kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_2.jpg |
| og:image | https://www.55810.com/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_1.jpg | area画像 | なし | H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area\kagoshima-deliveryhealth-area-aaaaaaaaaaaaaaaaaaaa_1.jpg |

## 不明点

- source\template_kagoshima-deliveryhealth-area.html が create.php から現在どの手順で使用されているかは未確認です。
- placeholderの正式な置換値一覧は未確認です。
- PHP実行後の最終HTMLは未確認です。
