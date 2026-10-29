# 新規ブログのローカル一式作成と既存サイトマップ日付の確認待ち

- History: [20261029_CREATE_autumn-winter-cuddle-blog-template.md](../20261029_CREATE_autumn-winter-cuddle-blog-template.md)
- Record Date: 2026-10-29
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

- 作業前のローカルとGitHubのmainは `437ee2f60bacda646cf7e3bd95dab61e02d978e2` で一致。未追跡の既存記事画像1件を保持した。
- 指定ブログのdry-runと作成が成功し、`SCAFFOLD_OK BODY=PENDING`、作成後の専用検証は `CHECK_OK BODY=PENDING`。対象PHP3件の構文、HTML、Article・BreadcrumbList、共通登録、ブログ一覧・TOP、サイトマップの登録整合を確認した。
- サイトマップ260件の更新日検証で、既存の `kagoshima-deliveryhealth-blog-short-time-job.php` のみ `2026-10-28` とHTMLのGit更新日 `2026-10-29` が不一致。全体checkは `sitemap_lastmod_or_structure=page=blog:short-time-job` により終了コード1。

### 対応

- 公開入口PHP、source HTML、dataset PHPを新規作成し、dataset_base.php、ブログ一覧、TOP、サイトマップを更新した。
- 生成処理がTOPのブログ枠の閉じタグをリンク群の前へ移したため、今回の生成結果を修正した。HTML解析で全15リンクが元の枠内にあること、新規リンク1行を除くTOPの内容が作業前と同一であることを確認した。共通生成スクリプトは変更していない。
- 生成管理情報7ファイルを更新した。生成Markdown5件は既存のUpdatedとコード参照台帳のサイズ特例説明が再生成で失われるため、Updatedを2026-10-29として既存説明を保持した。生成データ本文が補完前後で同一であることを確認した。既知の生成ツール制約は [既存記録](20261028_MODIFY_generated-document-metadata_20261028_1.md) に従って引き継いだ。
- 既存記事のサイトマップ日付1か所の修正許可をユーザーに求めた。回答前のため変更していない。

### 結果

- 新規記事の一式はローカルに存在し、本文は準備中。差し替え対象は `HP/source/kagoshima-deliveryhealth-blog-autumn-winter-cuddle.html` の `CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END` の間。
- 初期descriptionは指定タイトル、OGP画像は管理書所定の共通画像。本文・著者・公開日・FAQ・人物情報は追加していない。
- Git状態変更、DB操作、本番公開は未実施。PC/SPの画面描画と本番動作は未確認。
- 全体完了とは判定していない。生成管理書の既存メタ情報を保持すると通常checkの生成文書比較とは差が生じるため、本文データの一致と正式メタ情報の検証を分けて扱う。

## 現在

- Remaining Work: 既存短時間バイト記事のサイトマップ日付1か所の修正可否への回答、および回答後の必要な更新・検証。
- Next Action: 許可された場合は当該lastmodだけを2026-10-29へ同期し、生成管理情報の更新と検証を行う。既存メタ情報は維持する。
