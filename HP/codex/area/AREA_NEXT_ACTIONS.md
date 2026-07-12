# AREA_NEXT_ACTIONS

> 過去スナップショット: 2026-06-05時点の確認結果です。件数、noindex、本番404、次作業は現在の状態として再確認が必要です。通常生成の正本は `HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` と `HP/codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md` です。

作成日: 2026-06-05
対象: H:\Data\01_CTI\candy_HP

## 結論

次作業の最優先は、永吉・下田町ページの本番反映確認、source HTMLなしの公開PHP、placeholder残存、画像パス切れ候補、リンク問題候補の公開前判定です。既存サイト修正はユーザー判断後に対象を絞って修正してください。

## 高：公開上すぐ問題になりやすい

- 永吉ページと下田町ページはローカル作成/完成更新済みだが、2026-06-05時点で公開URLと公開画像URLが404です。本番サーバーへの反映・アップロード工程を確認する。
- source HTMLなしの公開PHP 10 件を確認し、公開対象か作成漏れか判断する。対象: hirakawacho, kamifukumotocho, kamihonmachi, kamitaniguchicho, kamitatsuocho, kawadacho, kawakamicho, kiirenakamyoch, komatsubara, shimizucho
- placeholder残存ページ 28 件の公開方針を決め、公開対象は正規文言・Map・JSON-LDへ置換する。
- 画像パス切れ候補ページ 18 件の正しいファイル名・フォルダを確認する。
- placeholder/#/存在しないPHP候補リンク 153 件を公開前に修正候補として整理する。

## 中：SEO・品質上改善した方がよい

- noindex設定 60 件について、公開時に解除するページと残すページを一覧で判断する。
- title / description / h1 のplaceholder重複候補を修正する。
- JSON-LD構文不正候補を、置換後にJSONとして再検証する。
- 外部HTTPリンクとGoogle Map短縮URLの稼働を検証する。

## 低：将来管理しやすくするための整理

- area_name_kana が必要なら、別途読み仮名マスタを作成する。
- 対応エリア一覧 area.html のリンクslugと実ファイルslugの揺れを別表化する。
- ページ追加フロー用チェックリストは AREA_PAGE_CREATION_WORKFLOW.md として作成済み。今後の新規ページ作成は同資料を起点にする。

## ユーザー判断待ち：現行運用確認が必要

- placeholderページを公開対象にするか、下書きとして残すか。
- noindexを公開時にどのページで解除するか。
- source HTMLなしの公開PHPを作成するか、リンクから外すか。
- 画像パス切れ候補の正しい画像名を既存画像から選ぶか、新規画像を用意するか。
- 本番URLを canonical の https://www.55810.com/ として扱ってよいか。
- ローカル作成済みページを本番へ反映する方法。FTP/同期/サーバー側配置のどれで行うかは未確認。

## 確認済み

- 2026-06-05に永吉ページのルートPHP、source HTML、area datasetを新規作成し、dataset_base.phpを更新しました。
- 2026-06-05に下田町ページの既存source HTMLが未完成placeholder状態だったため、Text_area_data\下田町_テンプレート.txtを反映して完成更新しました。公開PHP、area dataset、dataset_base.phpは既存反映済みのため再作成していません。
- 対応エリアシリーズのPHP/HTML対応、placeholder、画像、SEO、リンク、Map、JSON-LDを静的確認しました。
- 永吉ページと下田町ページではローカル画像2点、source HTML内img、JSON-LD、dataset_base.php反映を確認しました。

## 未確認

- PHP実行結果、DB置換後の最終HTML、外部リンク稼働、実ブラウザ表示は未確認です。
- 永吉ページ/永吉画像2点、下田町ページ/下田町画像2点の本番URLは404確認済みです。本番反映は未完了として扱ってください。

## 推測

- placeholder残存ページとsource HTMLなしPHPは、ページ作成途中またはslug揺れによる不整合の可能性があります。
