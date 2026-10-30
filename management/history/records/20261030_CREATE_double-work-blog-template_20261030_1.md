# ダブルワークブログのローカル一式作成と検証

- History: [20261030_CREATE_double-work-blog-template.md](../20261030_CREATE_double-work-blog-template.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業開始時のローカルとGitHubのmainは `604fae587ffc45c0b1da329c6d8a8de87f8079a0` で一致し、ルート全体に未コミット変更はなかった。管理専用ブランチはローカル・GitHubともに存在しない。
- 作業前のサイトマップ260件は更新日を含め整合していた。全体checkの既存差分は生成Markdown5件のUpdatedとコード参照台帳のサイズ特例説明のみで、生成データ本文10件は一致した。

### 対応

- 公開入口PHP、source HTML、記事用datasetを作成し、共通PHPのcase・include・HTMLからPHPへの変換、ブログ一覧、TOP、サイトマップを更新した。
- 指定タイトルをtitle・H1・OGP・パンくず・Article・リンク表示名に反映。JSON-LDはArticleとBreadcrumbListとし、本文には準備中表示と `CANDY_BLOG_BODY_PENDING` を保持した。descriptionは指定タイトルを暫定使用し、OGPは既存共通画像とした。
- TOPは一覧の末尾15件へ同期した。生成時に移動した閉じタグを元の位置へ戻し、HTML解析で全15リンクが既存のブログ枠内にあることを確認した。ブログ領域外は作業前と同一。TOPから外れたslendergirlは一覧に保持した。共通生成スクリプトは変更していない。
- 生成管理情報7ファイルを更新した。Markdown5件のUpdatedを2026-10-30とし、コード参照台帳のサイズ特例説明を保持した。既知の生成ツール制約は [既存記録](20261028_MODIFY_generated-document-metadata_20261028_1.md) のとおりで、今回そのツールは改修していない。

### 結果

- dry-runは `DRY_RUN_OK`、作成は `SCAFFOLD_OK BODY=PENDING`、作成後は `CHECK_OK BODY=PENDING`。PHP3件の構文、HTMLメタ情報、JSON-LD、共通登録、一覧・TOP・サイトマップ整合を検証した。
- サイトマップ261件は `PREVIEW_OK`・`SYNC_OK`、追加同期変更0件。対象記事・TOP・一覧のlastmodは2026-10-30。
- メタ情報補完前の全体検証は `CHECK=OK documents=10`、対象は `structure=COMPLETE seo=OK list=1 sitemap=1 issues=NONE`。メタ情報補完後も、その既知の正式メタ情報だけを比較から除外して全10件の生成データ一致を確認し、更新日・サイズ特例も別途検証した。補完済み文書に対する通常CLIの差分判定制約は残るため、無条件の通常CLI合格とは扱わない。
- 本文差し替え先は `HP/source/kagoshima-deliveryhealth-blog-double-work.html` の `CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END` の間。本文完成時は準備中表示とPENDINGマーカーを除去し、本文に合わせてdescription・画像・JSON-LDを更新する。
- 記事本文・記事画像は未作成。PC/SPの画面描画、本番動作は未確認。Git状態変更、DB操作、本番公開は実施していない。

## 現在

- Remaining Work: None（依頼されたローカルテンプレート準備の範囲）
- Next Action: None（本文作成・公開は本案件の対象外）
