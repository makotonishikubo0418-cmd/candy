# SITE_STRUCTURE

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`  
注意: `H:\Data\01_CTI\candy_HP\codex` は Codex 成果物置き場として扱い、サイト解析の既存ファイル数からは除外しています。

## 確認済みファイル数

| 項目 | 件数 |
|---|---:|
| 解析対象ファイル総数 | 1,314 |
| ルート直下 PHP | 97 |
| `source\*.html` | 88 |
| `includefile\dataset_*.php` | 98 |

## 拡張子別件数

| 拡張子 | 件数 | 主な用途 |
|---|---:|---|
| `.jpg` | 594 | 女の子画像、店舗画像、エリア・ホテル画像 |
| `.php` | 200 | 公開ページラッパー、データセット、共通処理、管理系 |
| `.png` | 135 | UI 画像、アイコン、背景 |
| `.html` | 88 | `source` 配下のテンプレート |
| `.log` | 75 | ログ |
| `.txt` | 74 | テキストデータ |
| 拡張子なし | 35 | `.well-known` 等 |
| `.js` | 16 | 共通 JS、計測 JS、動画・お気に入り関連 |
| `.css` | 15 | 共通 CSS、ページ別 CSS、ライブラリ CSS |
| `.mp4` | 11 | 動画 |
| `.gif` | 10 | UI・ローディング系 |
| `.webp` | 3 | 画像 |
| `.svg` | 2 | アイコン等 |
| `.xml` | 1 | `sitemap.xml` |

## ディレクトリツリー

```text
H:\Data\01_CTI\candy_HP
├─ .htaccess
├─ index.php
├─ area.php
├─ blog.php
├─ contact.php
├─ create.php
├─ girls.php
├─ girls_list.php
├─ hotel.php
├─ makeSitemap.php
├─ movie.php
├─ movie_iframe.php
├─ mypage.php
├─ news.php
├─ schedule.php
├─ sitemap.xml
├─ system.php
├─ kagoshima-deliveryhealth-area-*.php
├─ kagoshima-deliveryhealth-blog-*.php
├─ kagoshima-deliveryhealth-hotel-*.php
├─ .vscode
├─ .well-known
│  ├─ .htaccess
│  └─ acme-challenge
├─ codex
│  └─ docs
├─ css
│  ├─ default.css
│  ├─ source/style.css ではなく H:\Data\01_CTI\candy_HP\source\style.css を参照するページあり
│  ├─ girls.css
│  ├─ girls_list.css
│  ├─ schedule.css
│  ├─ system.css
│  ├─ movie.css
│  ├─ mypage.css
│  ├─ news.css
│  └─ colorbox.css
├─ font
├─ imgCss
├─ imgHtml
│  ├─ new_202601
│  │  ├─ area
│  │  ├─ blog
│  │  ├─ girl
│  │  ├─ hotel
│  │  └─ shop
│  └─ ...
├─ includefile
│  ├─ dataset_base.php
│  ├─ class.hpgcoder2.php
│  ├─ funcs.php
│  ├─ dataset_index.php
│  ├─ dataset_girls.php
│  ├─ dataset_girls_list.php
│  ├─ dataset_schedule.php
│  ├─ dataset_system.php
│  └─ dataset_*.php
├─ js
│  ├─ common.js
│  ├─ amadare_webapp2.4.php
│  ├─ amadareWebApp2.6.js
│  ├─ amadareAccess.1.0.js
│  ├─ candyTile.js
│  ├─ jquery.colorbox-min.js
│  ├─ fav_gen.js
│  ├─ fav_ka.js
│  └─ diary.js
├─ log
├─ movie
├─ source
│  ├─ index.html
│  ├─ girls.html
│  ├─ girls_list.html
│  ├─ schedule.html
│  ├─ system.html
│  ├─ movie.html
│  ├─ mypage.html
│  ├─ news.html
│  ├─ area.html
│  ├─ blog.html
│  ├─ hotel.html
│  ├─ contact.html
│  ├─ create.html
│  ├─ style.css
│  ├─ kagoshima-deliveryhealth-area-*.html
│  ├─ kagoshima-deliveryhealth-blog-*.html
│  ├─ kagoshima-deliveryhealth-hotel-*.html
│  └─ template_*.html
├─ Text_area_data
├─ Text_blog_data
└─ Text_hotel_data
```

## 主要フォルダの役割

| フォルダ | 役割 | 注意点 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\source` | 公開ページの HTML テンプレート置き場 | 直接公開されるファイルではなく、PHP 生成の元データ |
| `H:\Data\01_CTI\candy_HP\includefile` | ページ生成、DB 接続、置換処理、ページ別データ処理 | 変更影響が大きい。先にバックアップ必須 |
| `H:\Data\01_CTI\candy_HP\css` | 共通 CSS とページ別 CSS | `default.css` はほぼ全体に影響 |
| `H:\Data\01_CTI\candy_HP\source\style.css` | エリア・ブログ・ホテル・記事系ページの CSS | `source` 配下だが CSS として参照される |
| `H:\Data\01_CTI\candy_HP\js` | 共通 JS、計測、モーダル、動画、お気に入り関連 | `fav.js` は参照あり・実ファイルなし |
| `H:\Data\01_CTI\candy_HP\imgHtml` | HTML 内で使用する画像 | 画像パス切れ候補あり |
| `H:\Data\01_CTI\candy_HP\imgCss` | CSS 用画像 | スプライト・背景系 |
| `H:\Data\01_CTI\candy_HP\font` | Web フォント | `default.css` から参照 |
| `H:\Data\01_CTI\candy_HP\movie` | 動画ファイル | 容量・再生形式の確認が必要 |
| `H:\Data\01_CTI\candy_HP\log` | ログ | 内容に個人情報・システム情報が含まれる可能性があるため転記禁止 |
| `H:\Data\01_CTI\candy_HP\.well-known` | 証明書・外部サービス確認用の可能性 | 用途不明のため削除禁止 |
| `H:\Data\01_CTI\candy_HP\codex` | Codex 作成物置き場 | 今回の成果物のみ配置 |

## 主要ファイルの役割

| ファイル | 役割 | 確認済み内容 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\.htaccess` | Apache 設定 | DirectoryIndex、CORS ヘッダー、RewriteEngine を確認。HTTPS / www / index 正規化はコメントアウト |
| `H:\Data\01_CTI\candy_HP\index.php` | トップページ公開 PHP | `dataset_base.php` を include |
| `H:\Data\01_CTI\candy_HP\girls_list.php` | 女の子一覧公開 PHP | `dataset_base.php` を include |
| `H:\Data\01_CTI\candy_HP\girls.php` | 女の子詳細公開 PHP | `dataset_base.php` を include |
| `H:\Data\01_CTI\candy_HP\system.php` | 料金・システム公開 PHP | `dataset_base.php` を include |
| `H:\Data\01_CTI\candy_HP\create.php` | ページ作成・管理系 PHP | 認証値、ファイル生成、`dataset_base.php` 追記処理を確認。値は転記しない |
| `H:\Data\01_CTI\candy_HP\makeSitemap.php` | サイトマップ生成 | URL をクロールし XML を出力。SSL 検証を無効化した読み込み処理あり |
| `H:\Data\01_CTI\candy_HP\sitemap.xml` | 既存サイトマップ | 2026-04-04 更新ファイルを確認 |
| `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` | 全体生成の中心 | テンプレート選択、dataset include、置換、DB 接続、出力を担当 |
| `H:\Data\01_CTI\candy_HP\includefile\class.hpgcoder2.php` | 置換エンジン | `rep...eot` 形式のテンプレート置換処理 |
| `H:\Data\01_CTI\candy_HP\includefile\funcs.php` | 共通関数 | dataset 側から利用される共通処理 |
| `H:\Data\01_CTI\candy_HP\css\default.css` | 全体 CSS | ヘッダー、フッター、レスポンシブ、フォント、共通部品 |
| `H:\Data\01_CTI\candy_HP\js\common.js` | 全体 JS | ローディング、ヘッダー固定、タブ切替、フェード等 |
| `H:\Data\01_CTI\candy_HP\js\amadareAccess.1.0.js` | 外部アクセス計測 | `https://amadare.me/...` への送信処理を確認 |

## PHP / HTML の対応

| 種別 | 確認済み |
|---|---|
| 公開 PHP | ルート直下に配置 |
| テンプレート HTML | `H:\Data\01_CTI\candy_HP\source\*.html` |
| データ処理 | `H:\Data\01_CTI\candy_HP\includefile\dataset_*.php` |
| 対応例 | `index.php` → `source\index.html` → `includefile\dataset_index.php` |
| 置換方式 | `source\*.html` 内の `rep000...eot` を PHP 側で置換 |

## ルート PHP はあるが source HTML が見つからない候補

以下はルート直下 PHP を確認しましたが、同名の `H:\Data\01_CTI\candy_HP\source\*.html` を確認できませんでした。`dataset_base.php` 経由でアクセスした場合、テンプレートなしエラーになる可能性があります。

| 公開 PHP | 対応 source HTML |
|---|---|
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-hirakawacho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamifukumotocho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamihonmachi.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamitaniguchicho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kamitatsuocho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kawadacho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kawakamicho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-kiirenakamyoch.php` | 不明。ファイル名末尾の `o` 欠落候補 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-komatsubara.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\kagoshima-deliveryhealth-area-shimizucho.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\main.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\page.php` | 不明 |
| `H:\Data\01_CTI\candy_HP\test.php` | 不明 |

## 不要・未使用と思われるファイルの候補

削除判断は禁止です。以下は「候補」であり、未使用確定ではありません。

| 候補 | 理由 | 状態 |
|---|---|---|
| `H:\Data\01_CTI\candy_HP\source\template_*.html` | 直接公開 PHP がない | 推測。ページ生成用テンプレートの可能性あり |
| `H:\Data\01_CTI\candy_HP\js\diary.js` | 0 バイト | 推測。外部写メ日記連携の名残の可能性あり |
| `H:\Data\01_CTI\candy_HP\css\YTPlayer.css` | 参照箇所が限定的で古い YouTube Player 系 | 推測。実使用は未確認 |
| `H:\Data\01_CTI\candy_HP\js\youtube_video.js` | YouTube/動画系の古い処理候補 | 推測。実使用は未確認 |
| `H:\Data\01_CTI\candy_HP\log\*.log` | 運用ログの蓄積 | 不要ではなく保全対象。削除禁止 |

## 未確認

| 項目 | 状態 |
|---|---|
| ファイルごとの最終利用日時 | 不明 |
| 本番サーバーでの公開対象除外設定 | 不明 |
| `.well-known` 内ファイルの用途 | 不明 |
| `log` の保存ポリシー | 不明 |
| `source\template_*.html` の実運用有無 | 不明 |

