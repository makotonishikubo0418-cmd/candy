# TECHNICAL_ANALYSIS

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`

## 使用技術

| 項目 | 確認済み内容 |
|---|---|
| 言語 | PHP、HTML、CSS、JavaScript |
| サイト種別 | PHP テンプレート生成型 |
| DB 利用 | あり。`H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` と dataset 群で確認 |
| WordPress | WordPress 標準ファイルは確認できませんでした |
| Laravel | Laravel 標準構成は確認できませんでした |
| CMS | 一般的 CMS の標準構成は確認できませんでした |
| 文字コード | HTML の `meta charset="UTF-8"`、CSS の `@charset "utf-8"`、PowerShell `-Encoding UTF8` 読み取りで日本語正常表示を確認 |
| Git | `H:\Data\01_CTI\candy_HP\.git` は確認できませんでした |

## ページ生成の仕組み

```text
ルート直下 PHP
  例: H:\Data\01_CTI\candy_HP\index.php
    ↓ include
H:\Data\01_CTI\candy_HP\includefile\dataset_base.php
    ↓ 現在の PHP ファイル名から source HTML を決定
H:\Data\01_CTI\candy_HP\source\index.html
    ↓ switch で dataset を include
H:\Data\01_CTI\candy_HP\includefile\dataset_index.php
    ↓ rep...eot 置換
H:\Data\01_CTI\candy_HP\includefile\class.hpgcoder2.php
    ↓
HTML 出力
```

## `dataset_base.php` の確認済み内容

| 項目 | 内容 | 注意 |
|---|---|---|
| 外部 include | `/home/firststar/public_html/group_test/...` と `/home/firststar/public_html/group/...` を参照 | ローカル単体実行できない可能性 |
| DB 初期化 | `$Database = new Database($DSN)` を確認 | DSN 実体は外部 include 依存 |
| CLUBID | `CLUBID` 定義あり | 店舗 ID として使われる可能性 |
| テンプレート選択 | 実行 PHP 名から `source\*.html` を決定 | 同名 HTML がないとエラー候補 |
| dataset 読み込み | switch で `dataset_*.php` を include | ページ追加時に追記が必要 |
| URL 置換 | `.html` リンクを `.php` に置換する処理あり | リンク修正時は生成後 URL も確認 |
| デバッグログ | `error_log("Source file: ...")` 等を確認 | 本番ログ肥大・情報露出に注意 |
| PC/SP 分岐 | コメントで PC/SP 分離廃止に触れつつ旧分岐あり | 古い分岐の残存候補 |

## PHP / CMS 判定

| 判定対象 | 結果 |
|---|---|
| HTML だけの静的サイトか | 違います。PHP と DB 連携を確認 |
| WordPress か | 確認できませんでした |
| Laravel か | 確認できませんでした |
| その他 CMS か | 不明。独自テンプレート生成型と判断 |
| 管理画面の有無 | `H:\Data\01_CTI\candy_HP\create.php` に認証・ページ生成系処理を確認 |

## JavaScript 機能

| ファイル | 確認済み機能 | 注意 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\js\common.js` | ローディング解除、フェード、ニュース表示、PC ヘッダー固定、hover 処理、出勤タブ切替 | 全体影響大 |
| `H:\Data\01_CTI\candy_HP\js\amadare_webapp2.4.php` | `WAtoggle` 等の UI 補助、Cookie、Ajax 系の共通機能 | PHP 拡張子だが JS として参照 |
| `H:\Data\01_CTI\candy_HP\js\amadareWebApp2.6.js` | 類似ユーティリティ | 現在参照範囲は要確認 |
| `H:\Data\01_CTI\candy_HP\js\amadareAccess.1.0.js` | 外部アクセス計測、Cookie、fetch / beacon 系 | 外部送信先あり |
| `H:\Data\01_CTI\candy_HP\js\candyTile.js` | トップページ用 | 詳細挙動は未整理 |
| `H:\Data\01_CTI\candy_HP\js\jquery.colorbox-min.js` | モーダル・iframe 表示 | 古いライブラリ候補 |
| `H:\Data\01_CTI\candy_HP\js\fav_gen.js` / `fav_ka.js` | お気に入り関連候補 | `fav.js` 参照との関係が不明 |
| `H:\Data\01_CTI\candy_HP\js\diary.js` | 0 バイト | 未使用候補 |

## フォーム処理

| フォーム | ファイル | action | method | 状態 |
|---|---|---|---|---|
| クレジット決済 | `H:\Data\01_CTI\candy_HP\source\system.html` | `https://credit.alij.ne.jp/service/credit/selfplogin.html` | POST | 確認済み。hidden 値は転記禁止 |
| 通常問い合わせ | 不明 | 不明 | 不明 | `contact.php` は存在するが実フォーム未確認 |

## 外部連携

| 連携先 | 確認済み内容 | 注意 |
|---|---|---|
| Google tag | `googletagmanager.com/gtag/js` と測定 ID `G-0VBTBPHDD2` を確認 | Google Analytics 側設定は未確認 |
| Google Tag Manager | `GTM-` 形式のコンテナは未確認 | Google tag とは分けて扱う |
| Google Map | ホテル・エリア系ページでリンク/iframe を確認 | API キー利用の有無は未整理 |
| reCAPTCHA | 参照を確認できませんでした | 未導入または未使用の可能性 |
| jQuery CDN | `https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js` | 古いバージョン |
| Amadare 計測 | `https://amadare.me/acc/9005/js/amadareAccessServer.php` | 外部仕様不明 |
| 外部決済 | `https://credit.alij.ne.jp/service/credit/selfplogin.html` | 契約・動作未確認 |
| 求人 | `http://new-cast.com/` | HTTP URL。現況未確認 |
| 写メ日記 | `https://www.cityheaven.net/...` | 外部サービス |
| FC2 ブログ/SNS | `http://candy6956.blog.fc2.com/` | HTTP URL。現況未確認 |
| グループ系リンク | `http://kd-g.org/` 等 | HTTP URL。現況未確認 |

## CSS 構成

| ファイル | 役割 | 注意 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\css\default.css` | 全体共通、ヘッダー、フッター、レスポンシブ、フォント、共通パーツ | 変更影響最大 |
| `H:\Data\01_CTI\candy_HP\source\style.css` | エリア・ブログ・ホテル・記事系ページ | `source` 配下だが CSS として使用 |
| `H:\Data\01_CTI\candy_HP\css\girls.css` | 女の子詳細 | 画像パス切れ候補あり |
| `H:\Data\01_CTI\candy_HP\css\girls_list.css` | 女の子一覧 | DB 出力要素と連動 |
| `H:\Data\01_CTI\candy_HP\css\schedule.css` | 出勤情報 | タブ・日付表示と連動 |
| `H:\Data\01_CTI\candy_HP\css\system.css` | 料金・システム | 決済導線と同ページ |
| `H:\Data\01_CTI\candy_HP\css\movie.css` | 動画 | 動画 JS と連動 |
| `H:\Data\01_CTI\candy_HP\css\mypage.css` | マイページ | Cookie/お気に入り処理と連動 |
| `H:\Data\01_CTI\candy_HP\css\colorbox.css` | モーダルライブラリ | 古いライブラリ候補 |
| `H:\Data\01_CTI\candy_HP\css\YTPlayer.css` | YouTube Player 系候補 | 参照画像・フォント切れ候補あり |

## 画像構成

| フォルダ | 内容 |
|---|---|
| `H:\Data\01_CTI\candy_HP\imgHtml` | HTML 内の表示画像 |
| `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\girl` | 女の子画像 |
| `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\shop` | 店舗・グループ店舗画像 |
| `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\area` | エリア画像 |
| `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\hotel` | ホテル画像 |
| `H:\Data\01_CTI\candy_HP\imgHtml\new_202601\blog` | ブログ画像 |
| `H:\Data\01_CTI\candy_HP\imgCss` | CSS 背景・UI 画像 |

## レスポンシブ構成

| 項目 | 確認済み内容 |
|---|---|
| ブレークポイント | `common.js` で `max-width:768px` を確認 |
| 表示切替 | `pcOnly` / `spOnly` 系 CSS を確認 |
| SP ナビ | `menuOpenBtn` / `menuCloseBtn` と `WAtoggle` を確認 |
| PC ヘッダー | スクロール時の固定ヘッダー処理を確認 |
| 画像 | `img switch`、ローディング系処理を確認 |

## パス指定

| 種別 | 例 | 状態 |
|---|---|---|
| 相対パス | `./css/default.css`、`./js/common.js`、`./imgHtml/...` | 多数 |
| ルート相対パス | 不明 | 主要テンプレートでは限定的 |
| 絶対サーバーパス | `/home/firststar/public_html/...` | PHP include で確認 |
| 外部 URL | Google、jQuery CDN、Amadare、決済、求人等 | 多数 |
| プレースホルダー | `____link____`、`aaaaaaaa...` | 複数 |

## エラー候補・リンク切れ候補

| 候補 | 確認箇所 | 内容 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\js\fav.js` 不在 | `girls.html`、`girls_list.html`、`schedule.html`、`system.html`、`movie.html`、`mypage.html` | 参照あり・実ファイル未確認 |
| `H:\Data\01_CTI\candy_HP\shopinfo.php` 不在 | `area.html`、`blog.html`、`contact.html`、`create.html`、`hotel.html` | リンクあり・実ファイル未確認 |
| `____link____` | `H:\Data\01_CTI\candy_HP\source\index.html` | placeholder |
| `aaaaaaaa...` | `contact.html`、`create.html`、複数エリア、テンプレート | placeholder |
| 未作成ホテルリンク | `H:\Data\01_CTI\candy_HP\source\hotel.html` | `kagoshima-deliveryhealth-hotel-aaaaaaaaaa.php` 候補 |
| 存在しないエリアリンク | `H:\Data\01_CTI\candy_HP\source\area.html` | 多数の未作成 area PHP 候補 |
| 画像パス切れ候補 | 複数エリアページ、`css\girls.css`、`css\YTPlayer.css` | ファイル名揺れ・フォルダ不足 |
| `matchMedia.matches` | `H:\Data\01_CTI\candy_HP\source\movie.html` | JavaScript ロジック不備候補 |
| テンプレートなし PHP | `main.php`、`page.php`、`test.php`、一部 area PHP | `source\*.html` 不在 |

## セキュリティ上の注意

| 対象 | 注意 |
|---|---|
| `H:\Data\01_CTI\candy_HP\create.php` | 認証値・ファイル生成・`dataset_base.php` 追記処理を含む。公開範囲とアクセス制御確認必須 |
| `H:\Data\01_CTI\candy_HP\source\system.html` | 外部決済 POST 用 hidden 値あり。値をチャット・文書へ転記しない |
| `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` | DB 接続・外部 include・ログ出力あり。編集前バックアップ必須 |
| `H:\Data\01_CTI\candy_HP\.htaccess` | `Access-Control-Allow-Origin: "*"` が設定済み | 必要性確認が必要 |
| `H:\Data\01_CTI\candy_HP\log` | 個人情報・システム情報を含む可能性 | 内容転記禁止、削除禁止 |
| 外部 HTTP リンク | 平文 HTTP の外部リンクあり | HTTPS 化は外部側確認後 |

## バックアップが必要な箇所

| 対象 | 理由 |
|---|---|
| `H:\Data\01_CTI\candy_HP\includefile` | 生成・DB・全ページ出力に関わる |
| `H:\Data\01_CTI\candy_HP\source` | 全ページテンプレート |
| `H:\Data\01_CTI\candy_HP\css` | 表示全体に影響 |
| `H:\Data\01_CTI\candy_HP\js` | UI・計測・お気に入りに影響 |
| `H:\Data\01_CTI\candy_HP\imgHtml` | 表示画像 |
| `H:\Data\01_CTI\candy_HP\.htaccess` | サーバー挙動 |
| `H:\Data\01_CTI\candy_HP\create.php` | 管理系・生成系 |

## 未確認

| 項目 | 状態 |
|---|---|
| PHP 実行結果 | 未確認 |
| DB 接続可否 | 未確認 |
| 本番サーバー設定 | 不明 |
| JavaScript の実ブラウザエラー | 未確認 |
| 外部 API / 決済の実通信 | 未確認 |
| Google Analytics 画面側設定 | 不明 |
| sitemap.xml が本番と一致するか | 不明 |

