# 作成経路・管理コード・検証の整備

- History: [20261028_CREATE_blog-template-environment.md](../20261028_CREATE_blog-template-environment.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: Verification Pending

## 記録

### 決定

- 管理書内のHTML・PHPコードを作成ツールが直接読み込むURL・タイトル指定経路を追加する。
- 本文準備と公開を区別し、初期JSON-LDはArticleとBreadcrumbList。FAQ・人物情報は本文に実在するときだけ追加する。
- 既存の同名ファイルと登録は上書きせず、一覧・TOP・サイトマップの不整合は新規作成前に停止する。

### 対応

- BLOG_TEMPLATE.md、作成コマンド、統合検証と回帰テストを追加し、INDEX・ブログ仕様・作成手順・共通生成規則・仕様索引を更新した。

### 確認済み事実

- 一時フォルダで8件の回帰テストが成功。実PHP CLIで公開PHP・dataset・共通PHPの構文を確認した。
- 作業開始時のmainはローカル・GitHubとも5c607c84c45c08eb7cedf7a711fe3b263a2a33be。管理専用ブランチはNOT_PRESENT。GitHubのfeature/member-loyalty-mypageは933bb909c54f6fdb688dea69a2b9e505606bd0c0、同名ローカルブランチなし。
- 既存未コミット変更を保持し、公開ページの生成・既存ページ修正・Git変更・DB操作・本番反映は行っていない。
- 既存の一覧・TOP・サイトマップ不整合はブログ名認識修正の履歴にも記録されており、本件の環境整備で自動修復しない。

## 現在

- Remaining Work: 管理書参照、実環境の停止条件、変更差分の最終確認。
- Next Action: 最終検証後に完了記録を追加する。
