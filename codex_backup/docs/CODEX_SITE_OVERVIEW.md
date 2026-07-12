# CODEX_SITE_OVERVIEW

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`  
成果物配置: `H:\Data\01_CTI\candy_HP\codex\docs`  
作業範囲: 解析・把握・ドキュメント作成のみ。既存 PHP / HTML / CSS / JavaScript / 画像 / ログは未変更。

## 結論

`H:\Data\01_CTI\candy_HP` は、鹿児島のデリヘル「キャンディ」公式サイト用の PHP テンプレート生成型サイトです。トップページや下層ページはルート直下の PHP ラッパーから `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` を経由し、`H:\Data\01_CTI\candy_HP\source\*.html` のテンプレートと `H:\Data\01_CTI\candy_HP\includefile\dataset_*.php` のデータ処理を組み合わせて出力されます。

## 確認済み概要

| 項目 | 内容 |
|---|---|
| サイト名 | 鹿児島 デリヘル キャンディ |
| トップページ | 公開側: `H:\Data\01_CTI\candy_HP\index.php` / テンプレート: `H:\Data\01_CTI\candy_HP\source\index.html` |
| サイトの目的 | 店舗案内、女の子一覧、出勤情報、料金案内、動画、対応エリア、ホテル情報、電話問い合わせへの誘導 |
| 公開状態 | 公開前データ。開発中は `noindex` を設定する前提 |
| サイトの種類 | PHP テンプレート生成型サイト |
| WordPress / Laravel | WordPress や Laravel の標準構成は確認できませんでした |
| 主な技術 | PHP、HTML テンプレート、CSS、JavaScript、jQuery 1.11.2、Google tag、外部アクセス計測 JavaScript |
| DB 連携 | `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` と `H:\Data\01_CTI\candy_HP\includefile\dataset_*.php` で DB 利用を確認 |
| ページ生成方式 | ルート直下 PHP が `dataset_base.php` を読み込み、対応する `source\*.html` を変換して出力 |
| 主要画像 | `H:\Data\01_CTI\candy_HP\imgHtml`、`H:\Data\01_CTI\candy_HP\imgCss` |
| フォント | `H:\Data\01_CTI\candy_HP\font` |
| 既存ログ | `H:\Data\01_CTI\candy_HP\log` |
| 解析対象から除外 | Codex 作成物置き場である `H:\Data\01_CTI\candy_HP\codex` |

## 主要ページ

| ページ | 公開ファイル | テンプレート | 役割 |
|---|---|---|---|
| トップ | `H:\Data\01_CTI\candy_HP\index.php` | `H:\Data\01_CTI\candy_HP\source\index.html` | サイト入口、店舗説明、女の子・出勤・料金・FAQ 等への導線 |
| NEWS | `H:\Data\01_CTI\candy_HP\news.php` | `H:\Data\01_CTI\candy_HP\source\news.html` | 新着情報 |
| 女の子一覧 | `H:\Data\01_CTI\candy_HP\girls_list.php` | `H:\Data\01_CTI\candy_HP\source\girls_list.html` | 在籍一覧、検索・絞り込み表示 |
| 女の子詳細 | `H:\Data\01_CTI\candy_HP\girls.php` | `H:\Data\01_CTI\candy_HP\source\girls.html` | 個別プロフィール |
| 出勤情報 | `H:\Data\01_CTI\candy_HP\schedule.php` | `H:\Data\01_CTI\candy_HP\source\schedule.html` | 本日・週間スケジュール |
| 料金・システム | `H:\Data\01_CTI\candy_HP\system.php` | `H:\Data\01_CTI\candy_HP\source\system.html` | 料金、利用方法、注意事項、クレジット決済導線 |
| 動画 | `H:\Data\01_CTI\candy_HP\movie.php` | `H:\Data\01_CTI\candy_HP\source\movie.html` | 女の子動画一覧 |
| マイページ | `H:\Data\01_CTI\candy_HP\mypage.php` | `H:\Data\01_CTI\candy_HP\source\mypage.html` | お気に入り等 |
| 対応エリア | `H:\Data\01_CTI\candy_HP\area.php` | `H:\Data\01_CTI\candy_HP\source\area.html` | 対応エリア一覧 |
| ホテル一覧 | `H:\Data\01_CTI\candy_HP\hotel.php` | `H:\Data\01_CTI\candy_HP\source\hotel.html` | 派遣可能ホテル情報 |
| ブログ一覧 | `H:\Data\01_CTI\candy_HP\blog.php` | `H:\Data\01_CTI\candy_HP\source\blog.html` | SEO 記事一覧 |

## 主要機能

| 機能 | 確認済み内容 | 管理上の注意 |
|---|---|---|
| テンプレート置換 | `rep000...eot` 形式の置換トークンを `HpgCoder` が処理 | HTML だけ直しても DB 由来表示は変わらない場合があります |
| 女の子一覧・詳細 | `dataset_girls_list.php`、`dataset_girls.php` 系で DB 情報を使用 | DB・画像・テンプレートの対応確認が必要 |
| 出勤情報 | `dataset_schedule.php` で出勤表示を生成 | 表示不具合時は DB の出勤データも確認対象 |
| 動画 | `movie.php`、`movie_iframe.php`、動画関連 JS / `movie` ディレクトリ | 大容量ファイル・古い JS の可能性があります |
| お気に入り | `candyfav` Cookie らしき処理と `fav.js` 参照を確認 | `H:\Data\01_CTI\candy_HP\js\fav.js` が存在しないため要確認 |
| 電話導線 | `tel:0992266956` とクリック計測 `amadareAcRec` を確認 | 電話番号変更時は複数テンプレート確認が必要 |
| 外部計測 | Google tag、`amadareAccess.1.0.js` を確認 | 外部送信先の現在運用状況は未確認 |
| クレジット決済 | `system.html` から外部決済 URL へ POST | hidden 値を含むため、値はドキュメントへ転記しません |
| サイトマップ生成 | `H:\Data\01_CTI\candy_HP\makeSitemap.php` と `H:\Data\01_CTI\candy_HP\sitemap.xml` を確認 | 生成スクリプトは HTTP 経由でクロールします |

## 全体の管理方針

| 方針 | 内容 |
|---|---|
| 初回対応 | 既存ファイルは変更せず、`H:\Data\01_CTI\candy_HP\codex\docs` のみ更新 |
| 変更前確認 | 公開 PHP、`source\*.html`、`includefile\dataset_*.php`、CSS、JS、画像の関係を先に確認 |
| 事実と推測 | 確認済み・未確認・推測を必ず分離 |
| 機密扱い | `create.php` の認証値、クレジット決済 hidden 値、ログ内容、DB 接続情報は本文へ転記しない |
| 影響範囲 | 共通ヘッダー・フッターは多数ページに複製されているため一括置換前に全対象を列挙 |
| noindex | 公開前は標準設定として扱い、通常の相談では問題点として扱わない。公開作業時のみ解除要否を確認 |
| テスト | PHP 実行、DB 接続、外部通信、実サーバー URL は今回未検証。改修前に別途確認が必要 |

## Codex が最初に読むべき内容

1. `H:\Data\01_CTI\candy_HP\codex\docs\CODEX_SITE_OVERVIEW.md`
2. `H:\Data\01_CTI\candy_HP\codex\docs\SITE_STRUCTURE.md`
3. `H:\Data\01_CTI\candy_HP\codex\docs\PAGE_LIST.md`
4. `H:\Data\01_CTI\candy_HP\codex\docs\TECHNICAL_ANALYSIS.md`
5. `H:\Data\01_CTI\candy_HP\codex\docs\UNKNOWN_AND_RISK_LIST.md`

## 未確認

| 項目 | 状態 |
|---|---|
| 本番 URL | 不明 |
| 本番サーバーの PHP バージョン | 不明 |
| DB の実体・テーブル構成 | 不明 |
| 本番 DB への接続可否 | 不明 |
| 外部決済契約の現況 | 不明 |
| Google tag の管理者・プロパティ設定 | 不明 |
| `amadare.me` 側の計測仕様 | 不明 |
| 実ブラウザでの表示確認 | 未確認 |

## 推測

| 推測 | 根拠 | 扱い |
|---|---|---|
| 本番サーバーでは `dataset_base.php` が正常に外部 include と DB 接続を行う | `dataset_base.php` 内に絶対パス include と DB 初期化がある | 推測。ローカルでは未実行 |
| `source\template_*.html` は `create.php` によるページ生成元 | `create.php` とテンプレート名の対応がある | 推測。実操作は未確認 |
| `log` は運用中のデバッグ・生成ログ | `.log` が 75 件存在 | 推測。ログ本文の精査は未実施 |
