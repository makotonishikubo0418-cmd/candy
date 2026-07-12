# CODEX_MANAGEMENT_GUIDE

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`

## 管理方針

| 原則 | 内容 |
|---|---|
| 既存ファイル保護 | 変更前に必ず対象ファイルを読み、影響範囲を確認する |
| 公開前前提 | 公開前データのため、開発中の `noindex` は問題点として扱わない |
| 事実分離 | 確認済み・未確認・推測を混ぜない |
| 生成物配置 | Codex が作る文書・補助ファイルは `H:\Data\01_CTI\candy_HP\codex` 配下 |
| 直接修正禁止 | 影響範囲不明のまま PHP / HTML / CSS / JS を修正しない |
| 秘密値保護 | 認証値・決済 hidden 値・DB 接続情報・ログ本文をチャットや文書へ転記しない |

## 最初に確認するファイル

| 目的 | 確認ファイル |
|---|---|
| 全体把握 | `H:\Data\01_CTI\candy_HP\codex\docs\CODEX_SITE_OVERVIEW.md` |
| 構成確認 | `H:\Data\01_CTI\candy_HP\codex\docs\SITE_STRUCTURE.md` |
| ページ対応確認 | `H:\Data\01_CTI\candy_HP\codex\docs\PAGE_LIST.md` |
| 技術確認 | `H:\Data\01_CTI\candy_HP\codex\docs\TECHNICAL_ANALYSIS.md` |
| リスク確認 | `H:\Data\01_CTI\candy_HP\codex\docs\UNKNOWN_AND_RISK_LIST.md` |
| 生成中心 | `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` |
| 置換処理 | `H:\Data\01_CTI\candy_HP\includefile\class.hpgcoder2.php` |
| トップ | `H:\Data\01_CTI\candy_HP\index.php`、`H:\Data\01_CTI\candy_HP\source\index.html`、`H:\Data\01_CTI\candy_HP\includefile\dataset_index.php` |
| 女の子一覧 | `H:\Data\01_CTI\candy_HP\girls_list.php`、`H:\Data\01_CTI\candy_HP\source\girls_list.html`、`H:\Data\01_CTI\candy_HP\includefile\dataset_girls_list.php` |
| 女の子詳細 | `H:\Data\01_CTI\candy_HP\girls.php`、`H:\Data\01_CTI\candy_HP\source\girls.html`、`H:\Data\01_CTI\candy_HP\includefile\dataset_girls.php` |
| 料金 | `H:\Data\01_CTI\candy_HP\system.php`、`H:\Data\01_CTI\candy_HP\source\system.html`、`H:\Data\01_CTI\candy_HP\includefile\dataset_system.php` |
| 共通デザイン | `H:\Data\01_CTI\candy_HP\css\default.css`、`H:\Data\01_CTI\candy_HP\js\common.js` |

## 修正時の基本手順

1. 変更依頼の対象ページ・対象表示を特定する。
2. `PAGE_LIST.md` で公開 PHP、テンプレート HTML、関連 CSS / JS を確認する。
3. `dataset_base.php` の switch と該当 `dataset_*.php` を確認する。
4. 変更対象が静的文言か、DB 由来か、置換トークン由来かを判定する。
5. 変更対象ファイルをバックアップする。
6. 最小範囲で修正する。
7. PHP 実行結果、PC/SP 表示、リンク、画像、JS console を確認する。
8. 変更内容・未確認事項・残リスクを `H:\Data\01_CTI\candy_HP\codex` 配下に記録する。

## 変更してよいファイル候補

実際の変更はユーザー承認後のみです。

| 対象 | 条件 |
|---|---|
| `H:\Data\01_CTI\candy_HP\source\*.html` | 文言・リンク・構造修正。ただし置換トークンを壊さない |
| `H:\Data\01_CTI\candy_HP\css\*.css` | 表示調整。ただし共通 CSS は影響範囲確認後 |
| `H:\Data\01_CTI\candy_HP\source\style.css` | 記事系ページ調整 |
| `H:\Data\01_CTI\candy_HP\js\*.js` | UI 挙動修正。ただし古いライブラリ更新は別作業 |
| `H:\Data\01_CTI\candy_HP\imgHtml` | 画像追加・差し替え。ただし既存削除は禁止 |
| `H:\Data\01_CTI\candy_HP\codex` | Codex 管理文書・検証メモ |

## 変更注意ファイル

| ファイル | 注意理由 |
|---|---|
| `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` | 全ページ生成に影響 |
| `H:\Data\01_CTI\candy_HP\includefile\class.hpgcoder2.php` | 置換エンジン。全ページ影響 |
| `H:\Data\01_CTI\candy_HP\includefile\funcs.php` | 共通関数。dataset 群に影響 |
| `H:\Data\01_CTI\candy_HP\includefile\dataset_*.php` | DB 出力・置換内容に影響 |
| `H:\Data\01_CTI\candy_HP\css\default.css` | 全体デザインに影響 |
| `H:\Data\01_CTI\candy_HP\js\common.js` | 全体 UI に影響 |
| `H:\Data\01_CTI\candy_HP\.htaccess` | サーバー挙動に影響 |
| `H:\Data\01_CTI\candy_HP\makeSitemap.php` | 外部クロール・XML 生成に影響 |

## 変更禁止候補ファイル

ユーザーの明示承認なしに変更しない対象です。

| 対象 | 理由 |
|---|---|
| `H:\Data\01_CTI\candy_HP\create.php` | 認証・ファイル生成・生成ロジックを含む |
| `H:\Data\01_CTI\candy_HP\log` | ログ保全対象。削除禁止 |
| `H:\Data\01_CTI\candy_HP\.well-known` | 証明書・外部認証用途の可能性 |
| `H:\Data\01_CTI\candy_HP\sitemap.xml` | 本番 SEO に影響。生成元確認後 |
| `H:\Data\01_CTI\candy_HP\movie` | 大容量・公開動画の可能性 |
| 認証値・決済 hidden 値を含む箇所 | 秘密値保護 |

## よく使う修正パターン

| 修正内容 | 確認ファイル | 注意 |
|---|---|---|
| 電話番号変更 | 全 `source\*.html`、`dataset_*.php` 検索 | 表示番号、`tel:`、計測引数を揃える |
| 営業時間変更 | `source\system.html`、共通フッターを含む全 `source\*.html` | 現在不一致あり |
| 料金変更 | `source\system.html`、`dataset_system.php` | クレジット決済導線も確認 |
| ナビ追加 | 全 `source\*.html` のヘッダー・フッター | 複製構造のため漏れやすい |
| エリアページ追加 | `source\template_kagoshima-deliveryhealth-area.html`、公開 PHP、`dataset_base.php`、`dataset_*.php` | `create.php` との関係確認 |
| ホテルページ追加 | `source\template_kagoshima-deliveryhealth-hotel.html`、公開 PHP、画像、Map | placeholder を残さない |
| 画像差し替え | `imgHtml` と該当 HTML/CSS | 実ファイル名と拡張子を確認 |
| JS エラー修正 | `js\common.js`、ページ内 script、参照ファイル存在確認 | PC/SP 両方確認 |
| SEO 修正 | `title`、`description`、`robots`、JSON-LD、`sitemap.xml` | 公開前は `noindex` 前提。公開作業時だけ解除対象を確認 |

## 変更前チェックリスト

| チェック | 状態 |
|---|---|
| 対象ページの公開 PHP を確認した | 未実施なら実施 |
| 対象 `source\*.html` を確認した | 未実施なら実施 |
| 対象 `dataset_*.php` を確認した | DB 由来表示なら必須 |
| 置換トークン `rep...eot` の意味を確認した | 不明なら触らない |
| 共通ヘッダー・フッターの複製範囲を確認した | ナビ・電話・営業時間修正時必須 |
| 画像ファイルの実在を確認した | 画像修正時必須 |
| 外部リンク先の現況を確認した | 外部リンク変更時必須 |
| バックアップを作成した | PHP/CSS/JS/HTML 修正時必須 |
| 秘密値を転記していない | 必須 |

## 修正後チェックリスト

| チェック | 内容 |
|---|---|
| PHP 構文 | 対象 PHP の構文確認 |
| HTML 表示 | PC/SP の表示確認 |
| リンク | 内部リンク、外部リンク、電話リンク |
| 画像 | missing image がないか |
| JS console | エラーがないか |
| フォーム | 決済フォーム等の action / hidden 値を壊していないか |
| SEO | title、description、robots、JSON-LD、sitemap |
| ログ | PHP warning / error が増えていないか |
| 差分確認 | 意図しないファイル変更がないか |

## テスト手順

| 手順 | 内容 | 状態 |
|---|---|---|
| 1 | ローカルまたは検証環境で PHP が実行できるか確認 | 今回未実施 |
| 2 | `index.php` を開きトップ表示を確認 | 今回未実施 |
| 3 | `girls_list.php`、`girls.php?no=...`、`schedule.php` を確認 | 今回未実施 |
| 4 | `system.php` の料金・決済フォームを確認 | 今回未実施 |
| 5 | `area.php`、代表エリアページを確認 | 今回未実施 |
| 6 | `hotel.php`、ホテル詳細ページを確認 | 今回未実施 |
| 7 | PC/SP 幅でヘッダー・メニュー・フッターを確認 | 今回未実施 |
| 8 | ブラウザ console を確認 | 今回未実施 |
| 9 | アクセスログ・PHP エラーログを確認 | 今回未実施 |

## 改修時の注意点

| 注意点 | 内容 |
|---|---|
| DB 依存 | 女の子、出勤、動画、お気に入りは DB / Cookie / dataset 依存の可能性 |
| 置換トークン | `rep...eot` を消すと出力が壊れる可能性 |
| 複製構造 | 共通部品が include ではなく HTML 複製されている箇所が多い |
| placeholder | `aaaaaaaa...` を正規文言と誤認しない |
| noindex | 公開前は標準設定。通常相談では指摘不要。公開作業時のみ解除確認 |
| 外部決済 | hidden 値を変更しない |
| create.php | 管理系。通常ページと同じ扱いで編集しない |

## 不明点リスト

| 不明点 |
|---|
| 本番 URL |
| 本番サーバーの PHP バージョン |
| DB の実体 |
| `create.php` の現在の運用 |
| `fav.js` が欠落している理由 |
| `shopinfo.php` が欠落している理由 |
| 公開時に `noindex` を解除する対象ページ |
| 営業時間の正 |
| placeholder ページを公開対象にするか |

## 今後追加で確認すべきこと

| 優先度 | 確認内容 |
|---|---|
| 高 | 本番 URL と検証環境 |
| 高 | PHP 実行・DB 接続ができる環境 |
| 高 | `fav.js` / `shopinfo.php` の欠落理由 |
| 高 | placeholder 公開ページの扱い |
| 高 | 営業時間・料金・電話番号の現行情報 |
| 中 | 公開時の `noindex` 解除手順 |
| 中 | 外部リンクの HTTPS / 稼働状況 |
| 中 | 画像パス切れ候補の正しいファイル名 |
| 中 | `create.php` のアクセス制限 |
