# 進捗記録

- History: [`20261125_CREATE_blog-photo-diary-charm.md`](../20261125_CREATE_blog-photo-diary-charm.md)
- Record Date: 2026-11-25
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ一般・ポイント色 #B05C78・原稿・画像3点を確認した
- 同一slugの既存ページはなく、画像3点はローカルに存在した（1000x750）
- 既存 `blog-theme-dusty-rose` は #D58D9F のため、指定HEX用に `blog-theme-deep-dusty-rose`（#B05C78）を `HP/css/blog.css` へ追加し、参照HTMLの `blog.css?v=` を SHA-256先頭8桁 `d1f94079` へ更新した。記事別CSSは作成していない
- 正式テンプレート構造で記事HTMLを作成し、公開PHP・dataset・`dataset_base.php`・blog一覧末尾追加・TOP最新15件同期・sitemap登録を実施した
- 一般CTA（女の子一覧 / 本日の出勤情報）を配置し、FAQPage・author・日付・女の子紹介は原稿にないため未追加とした
- 関連記事は決定的選定で blog 3件・area 3件を配置した

### 対応

- ローカル作成と登録一式の整備
- 生成管理書・サイトマップ同期と検証へ進む

## 現在

- Remaining Work: site-state検証、PC/SP実表示確認、git add/commit/push、本番公開確認
- Next Action: candy-site-state sync/write/check とローカル実表示確認を完了する
