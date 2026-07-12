# UNKNOWN_AND_RISK_LIST

作成日: 2026-06-05  
対象: `H:\Data\01_CTI\candy_HP`

## 重要リスク一覧

| 優先度 | 種別 | 対象 | 内容 | 対応方針 |
|---|---|---|---|---|
| 高 | セキュリティ | `H:\Data\01_CTI\candy_HP\create.php` | 認証値、ファイル作成、`dataset_base.php` 追記処理を確認 | 公開範囲・アクセス制限確認。値は転記禁止 |
| 高 | セキュリティ | `H:\Data\01_CTI\candy_HP\source\system.html` | 外部決済 POST と hidden 値を確認 | 値を変更・転記しない |
| 高 | 表示不具合 | `H:\Data\01_CTI\candy_HP\js\fav.js` | 複数ページで参照あり、実ファイル未確認 | 欠落理由確認 |
| 高 | リンク切れ | `H:\Data\01_CTI\candy_HP\shopinfo.php` | 複数ページでリンクあり、実ファイル未確認 | 必要ページか確認 |
| 高 | placeholder | `contact.html`、`create.html`、複数エリア、`hotel.html` | `aaaaaaaa...` 残存 | 公開方針確認後に修正 |
| 高 | 情報不一致 | 営業時間 | `system.html` と共通フッターが不一致 | 現行営業時間確認 |
| 高 | テンプレート欠落 | 一部ルート PHP | 同名 `source\*.html` 不在 | アクセス時の挙動確認 |
| 中 | 公開作業 | 多数ページ | 公開前保護として `robots noindex` 設定あり | 公開時のみ解除対象を確認 |
| 中 | 外部リンク | HTTP URL | 求人、FC2、グループリンク等 | HTTPS 対応確認 |
| 中 | 画像切れ | 複数エリア・CSS | 画像パス切れ候補 | 実ファイル名確認 |
| 中 | 古いライブラリ | jQuery 1.11.2、Colorbox | セキュリティ・互換性リスク | 更新影響調査 |
| 中 | サーバー設定 | `.htaccess` | CORS `*` | 必要性確認 |

## 未確認事項

| 項目 | 状態 |
|---|---|
| 本番 URL | 不明 |
| 本番サーバーの PHP バージョン | 不明 |
| 本番 DB の実体 | 不明 |
| DB テーブル構成 | 不明 |
| DB 接続可否 | 未確認 |
| 実ブラウザ表示 | 未確認 |
| PHP 実行結果 | 未確認 |
| 外部決済の契約・稼働状況 | 不明 |
| Google Analytics の管理画面設定 | 不明 |
| `amadare.me` 側の仕様 | 不明 |
| reCAPTCHA の導入有無 | 参照は確認できず。未導入かは不明 |
| `.well-known` の用途 | 不明 |
| `log` の保存ポリシー | 不明 |
| `create.php` の運用者・使用手順 | 不明 |
| `source\template_*.html` の正式運用 | 不明 |

## 推測事項

| 推測 | 根拠 | 確認方法 |
|---|---|---|
| 独自 CMS 的な生成方式 | `create.php`、`dataset_base.php`、`source\template_*.html` がある | `create.php` の運用手順確認 |
| エリアページは SEO 目的で量産 | 60 件の同構造ページがある | SEO 方針確認 |
| placeholder ページは未完成ページ | `aaaaaaaa...` が複数テンプレート・ページに残る | 公開方針確認 |
| `fav_gen.js` / `fav_ka.js` が `fav.js` の代替候補 | ファイル名が近い | JS の呼び出し関係確認 |
| `makeSitemap.php` が `sitemap.xml` 生成元 | クロールして XML 出力するコードがある | 生成履歴・運用手順確認 |

## 壊れやすい箇所

| 対象 | 壊れやすい理由 |
|---|---|
| `H:\Data\01_CTI\candy_HP\includefile\dataset_base.php` | 全ページ生成の中心 |
| `H:\Data\01_CTI\candy_HP\includefile\class.hpgcoder2.php` | 置換エンジン |
| `H:\Data\01_CTI\candy_HP\includefile\dataset_*.php` | DB 由来表示に直結 |
| `H:\Data\01_CTI\candy_HP\source\*.html` の `rep...eot` | 削除すると出力欠落の可能性 |
| 共通ヘッダー・フッター | 多数ファイルへ複製され、修正漏れが起きやすい |
| `H:\Data\01_CTI\candy_HP\css\default.css` | 全体表示に影響 |
| `H:\Data\01_CTI\candy_HP\js\common.js` | 全体 UI に影響 |
| 外部決済フォーム | hidden 値・action を壊すと決済不可 |
| `create.php` | 管理系・生成系のため誤修正リスクが高い |

## セキュリティ上の注意

| 対象 | 注意 |
|---|---|
| 認証値 | `create.php` 内に確認。値は文書化・チャット転記禁止 |
| 決済 hidden 値 | `system.html` 内に確認。値は文書化・チャット転記禁止 |
| DB 接続情報 | 外部 include 経由の可能性。転記禁止 |
| ログ | 個人情報・アクセス情報・エラー情報を含む可能性 |
| CORS | `.htaccess` に `Access-Control-Allow-Origin: "*"` |
| 外部 HTTP リンク | 改ざん・混在コンテンツ・信頼性リスク |
| 古い jQuery | 既知脆弱性の可能性。更新は互換性検証後 |

## 古い情報の可能性

| 対象 | 理由 |
|---|---|
| コピーライト `2025` | 現在日 2026-06-05 と差分 |
| `dateModified` | ファイル更新日とズレの可能性 |
| jQuery 1.11.2 | 古い |
| Colorbox 系 | 古いライブラリ候補 |
| PC/SP 分離廃止コメント | 旧構成の残存 |
| HTTP 外部リンク | 古い URL の可能性 |
| `robots noindex` | 公開前保護として意図的に設定。公開時に解除漏れがあると検索登録されない |

## 追加確認が必要なこと

| 優先度 | 確認内容 | 理由 |
|---|---|---|
| 高 | 本番 URL | 想定 URL と表示確認に必須 |
| 高 | 検証環境 | PHP/DB 実行テストに必須 |
| 高 | 現行営業時間・料金・電話番号 | サイト掲載内容の正確性 |
| 高 | `fav.js` の正しい配置/代替 | 機能不具合候補 |
| 高 | `shopinfo.php` の必要性 | リンク切れ候補 |
| 高 | placeholder ページの公開方針 | 公開品質に直結 |
| 高 | `create.php` のアクセス制限 | セキュリティ |
| 中 | 公開時の `noindex` 解除対象 | 公開作業 |
| 中 | 画像パス切れ候補の正しいファイル名 | 表示品質 |
| 中 | 外部リンクの稼働確認 | ユーザー導線 |
| 中 | `sitemap.xml` 生成・更新手順 | SEO/運用 |

## Codex 単独では判断しない方がよいこと

| 判断対象 | 理由 |
|---|---|
| placeholder ページを削除するか | SEO・公開方針・ページ作成途中の可能性 |
| `robots noindex` を公開時にどこまで外すか | 本番公開範囲に関わる |
| 料金・営業時間を変更するか | 現行営業情報の確認が必要 |
| 外部決済フォームを修正するか | 契約・決済事故リスク |
| `create.php` を無効化するか | 管理運用に関わる |
| `log` を削除するか | 監査・障害調査に必要な可能性 |
| `.well-known` を削除するか | 証明書・外部認証に関わる可能性 |
| 古いライブラリを更新するか | 既存 UI 互換性確認が必要 |
| DB 接続処理を変更するか | 本番全体停止リスク |

## 確認済みだが未修正の事項

| 事項 | 状態 |
|---|---|
| 既存コードは未変更 | 確認済み |
| ファイル削除なし | 確認済み |
| ファイル移動なし | 確認済み |
| ファイルリネームなし | 確認済み |
| Codex 成果物は `H:\Data\01_CTI\candy_HP\codex\docs` に作成 | 確認済み |
