# コース時間ブログのローカル一式作成と検証

- History: [20261030_CREATE_course-time-blog-template.md](../20261030_CREATE_course-time-blog-template.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業開始時のローカルとライブGitHub mainは `1b52e2555680a933d5232d8ac8652478b266f615` で一致。未追跡の対象名画像3枚が存在し、追跡済みファイルには変更がなかった。画像は変更していない。

### 対応

- 正式テンプレートから公開入口PHP、source HTML、記事用datasetを作成し、共通PHP登録、ブログ一覧、TOP、サイトマップを更新した。
- 指定タイトルをtitle・H1・OGP・パンくず・Article・リンク表示名に反映。JSON-LDはArticleとBreadcrumbList。本文は準備中表示と `CANDY_BLOG_BODY_PENDING` を保持し、descriptionはタイトルの暫定値、OGPは既存の共通画像とした。
- TOPは一覧末尾15件に同期し、生成時に移動した閉じタグを元の位置に戻した。HTML解析で15リンクが既存ブログ枠内にあること、ブログ領域外が変更前と一致することを確認した。TOPから外れたpetitegirlは一覧に保持。生成スクリプトは変更していない。
- 生成管理情報7ファイルを更新した。MarkdownのUpdatedとコード参照台帳のサイズ特例を保持した正式出力で、全10文書の生成内容を検証した。

### 結果

- dry-runは `DRY_RUN_OK`、作成は `SCAFFOLD_OK BODY=PENDING`、作成後は `CHECK_OK BODY=PENDING`。PHP構文、HTMLメタ情報、JSON-LD、共通登録、一覧・TOP・サイトマップ整合を検証した。
- サイトマップは262件でlastmod差分0件。対象記事・TOP・一覧の日付は2026-10-30。
- 生成管理情報は `CHECK=OK documents=10`。対象はstructure=COMPLETE、seo=OK、list=1、sitemap=1、issues=NONE。記事画像は未組込みのためimages=UNVERIFIEDであり、画像統合済みとは扱わない。
- ローカルブラウザーのPC幅1366px・スマートフォン幅390pxでひな形を確認。H1は1件、横方向のはみ出しなし、JavaScriptコンソールエラーなし。ローカル表示確認はsource HTMLと既存資産によるもので、本番PHP実行確認ではない。
- 本文差し替え先は `HP/source/kagoshima-deliveryhealth-blog-course-time.html` の `CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END` の間。本文完成時は準備中表示とPENDINGマーカーを除去し、description・記事画像・JSON-LDを実際の内容に合わせる。
- Git状態変更、DB操作、本番公開は実施していない。ローカル確認サーバーを停止し、ブラウザーviewport指定を解除した。

## 現在

- Remaining Work: None（依頼されたローカルテンプレート準備の範囲）
- Next Action: None（本文作成・公開は本案件の対象外）
