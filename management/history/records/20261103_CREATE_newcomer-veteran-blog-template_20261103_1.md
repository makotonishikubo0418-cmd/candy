# 新人とベテラン比較ブログのローカル一式作成と検証

- History: [20261103_CREATE_newcomer-veteran-blog-template.md](../20261103_CREATE_newcomer-veteran-blog-template.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 対象URLの既存ファイル・登録はなく、dry-runは `DRY_RUN_OK`。作成予定7ファイルを確認した。
- 作成後のテンプレート検証は `SCAFFOLD_OK BODY=PENDING`、`CHECK_OK BODY=PENDING`。
- 再実行は `RESULT=STOP` / `同一URLの既存ファイルがあります。上書きしません` で停止し、上書き防止を確認した。
- サイトマップは266件。`preview-sitemap-lastmod`・`sync-sitemap-lastmod` は変更0件。新記事のlastmodは2026-11-03。
- 生成管理情報は `write` で7ファイル更新、`check` は `CHECK=OK documents=10`。対象は `structure=COMPLETE seo=OK list=1 sitemap=1 images=UNVERIFIED issues=NONE`。
- TOPスタッフブログ枠はHTML解析で15件、末尾が本記事、一覧ボタンあり、直前がお得な割引情報、直後が姉妹店情報。
- ローカル静的プレビュー（`127.0.0.1:8765`、source/index.htmlにbaseを付与した一時プレビュー）でPC幅約1614pxとSP幅390pxを表示確認。白枠内に15件、末尾タイトルの折返し、一覧ボタン、隣接セクションを確認。リンクのはみ出しはなし。一時プレビューHTMLと確認用スクリプトは削除済み。
- 本文・記事画像は未作成。Git状態変更、DB操作、本番公開は未実施。

### 対応

- 公開入口PHP、source HTML、記事用datasetを作成し、共通PHPのcase・include・HTML→PHP変換、ブログ一覧、TOP、サイトマップを更新した。
- 指定タイトルをtitle・H1・OGP・パンくず・Article・リンク表示名へ反映した。JSON-LDはArticleとBreadcrumbList。本文は準備中表示と `CANDY_BLOG_BODY_PENDING`。descriptionはタイトル暫定、OGPはサイト共通画像。
- [CANDY_OPERATION_BASICS.md](../../specs/CANDY_OPERATION_BASICS.md) 第6章に従い、サイトマップ同期・生成管理書のwrite/checkを実施した。

### 結果

- ローカル一式は準備済み。差し替え先は `HP/source/kagoshima-deliveryhealth-blog-newcomer-veteran.html` の `CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END`。
- 本文完成時に準備中表示・PENDINGマーカーを除去し、description・画像・JSON-LDを本文へ合わせる。
- 本番未反映。公開時は対応7ファイルと必要な資産を [本番移行管理](../../specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) に従い反映する。

## 現在

- Remaining Work: None（依頼されたローカルテンプレート準備の範囲）
- Next Action: None（本文作成・公開は本案件の対象外）
