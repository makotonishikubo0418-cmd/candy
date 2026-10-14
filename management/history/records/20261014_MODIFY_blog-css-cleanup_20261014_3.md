# ブログCSS整理のGitHub・本番公開完了

- History: [20261014_MODIFY_blog-css-cleanup.md](../20261014_MODIFY_blog-css-cleanup.md)
- Record Date: 2026-10-14
- Sequence: 3
- Status: Completed

## 記録

### 対応

- 24ファイルをコミット `00cf507e98a7e095ce8e253b10b81a5d25a47620`（Unify blog templates and remove obsolete article stylesheets）としてmainへPushした。別作業のcandy_dominant_girls本文は除外し、同HTMLはCSS更新キーだけを公開した。
- 公開時のGit改行正規化に合わせ、blog.cssをLFに揃えた。CSS宣言は同一のまま、SHA-256先頭8桁と全5参照の更新キーを `91007576` にした。前記録の `d8facf18` は公開前のローカルCRLF版の値。
- ローカルのGit著者設定がなかったため、直近5コミットで共通していた著者名とGitHub noreplyアドレスを当該commitコマンドに指定した。永続Git設定は変更していない。
- デプロイのself-test、FTP統合、release-check、画像置換関連、台帳metadata検証、女の子情報チェックが通過した。

### 結果

- [Actions 38513155109](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38513155109) はsuccess。アップロード6件・削除4件、アップロード170,877バイト。計画tokenは `49788dc74038d4c21b4ba8fc717f3d842b1c4107e75ee07f0a2b68ff8418992b` で、ローカルpreviewとActionsが一致した。
- 配置した6件はblog.css、記事HTML4件、正式ブログテンプレート。すべてFTPで配置後のSHA-256照合が成功。旧専用CSS4件の退避・削除、および退避ファイル清掃を含む処理が成功した。
- 本番のblog.cssと `blog.css?v=91007576` はともにHTTP 200、SHA-256はコミットと同じ `91007576e83937a238cc8edbcdd5420ad5c7fae6282f021df6fedf13c1b3cd1b`。
- 対象4記事の公開PHPはHTTP 200で、新しいCSS更新キーを参照し旧CSS参照はない。旧専用CSS4件はHTTP 404。正式テンプレートへの直接HTTPアクセスも仕様どおり404。
- workflowの本番入口検査は成功。ルート200、title・canonical・H1・indexability、index.php／HTTP／非wwwのリダイレクト、直接ホストnoindexを確認した。
- 本番4記事をPC幅1440pxとスマートフォン幅390pxで確認した。個室待機記事ではスマートフォン導入枠の右端が408pxとなり、画面幅390pxを超える状態を確認した。今回のCSS宣言変更はないが、変更前の本番実測との比較はなく、原因・発生時期は未確認。表示問題を公開処理の成功と区別し、本件では追加修正していない。他3記事の確認箇所では同じ導入枠のはみ出しは確認されなかった。
- candy_dominant_girlsの本番H1は従来の仮文章であり、別作業の本文が公開されていないことを確認した。ローカル本文は保持し、その作業中状態に合わせて再生成した台帳7件も未コミットで保持する。リポジトリ全体がクリーンな同期状態とは扱わない。
- ローカル台帳checkの既存サイトマップ判定問題は本件で修正していない。保護対象index.php・.htaccess、DBは変更していない。

### 決定

この公開完了記録と正式テンプレート案件の公開記録だけを追加コミットしてPushする。HP変更を含めず、本番再デプロイは行わない。

## 現在

- Remaining Work: None（今回承認されたGitHub・本番公開）。個室待機記事のスマートフォン導入幅、既存サイトマップ判定、別作業の記事本文公開は本件の対象外。
- Next Action: None。
