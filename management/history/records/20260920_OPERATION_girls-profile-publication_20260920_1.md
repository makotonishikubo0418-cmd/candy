# GitHub main・本番公開と表示確認完了

- History: [20260920_OPERATION_girls-profile-publication.md](../20260920_OPERATION_girls-profile-publication.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 対応

- GitHub最新mainの一時作業コピーで公開対象2ファイルのみをステージング・コミット・Pushした。元の `C:\Codex\FSG\Candy` のGit履歴、インデックス、別件の作業中ファイルは維持した。
- 公開コミット: [`a206c67fa3faaff31767b94a5c0eb9167eea9637`](https://github.com/makotonishikubo0418-cmd/candy/commit/a206c67fa3faaff31767b94a5c0eb9167eea9637)。親コミット: `bc5a3f9d28f30258966dd6bf75b9f311b61b731a`。
- 公開前にデプロイ関連の構文・自己テスト・統合テスト・女性情報台帳検査がすべて成功した。サイトマップ日付プレビューは140 URLすべて変更不要だった。
- 一時作業コピーで検証用生成資料6件を再生成し、`check --target girls` は `CHECK=OK documents=10` を確認した。既存の生成資料の差分は本番改修に含めず、GitHubへのコミット対象はHPの2ファイルだけに限定した。
- 公開計画: アップロード2件、削除・名前変更0件、70,915 bytes。PLAN_TOKEN: `ff70b242de5d15fe7d9b27b1ebdf1f0cbe940f3ff6a75cd1f31e33c8a9e6731d`。

### 結果

- [GitHub Actions run 35480572813](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/35480572813) は `success`。実デプロイ工程で対象2ファイルそれぞれのSHA-256検証と、一時バックアップの後片付け成功を確認した。
- 配置先は `/public_html/group/candy/css/girls.css` と `/public_html/group/candy/source/girls.html`。CSSのSHA-256は `b8297e7284f848d29e4fd85cf64f1b82b3c8a22881a203d18842cc19f0d034b2`、HTMLは `4fd68798c82ac072c783912f509b96da60d13251ccc05ef29752a2072e0342ce`。
- Actionsの本番入口検証は、トップ200、タイトル・canonical・H1、公開index可、index.php・HTTP・非wwwの転送、直接ホストのnoindexを含めて成功した。
- 本番一覧から実在する [プロフィール no=1486](https://www.55810.com/girls.php?no=1486) を確認した。本番ページとバージョン付きCSSはいずれもHTTP 200。CSS実取得内容のSHA-256はローカルと完全一致し、HTMLの参照は `girls.css?v=b8297e7` だった。
- 本番Chromeで320・375・768・769・1280pxを検証し、SPは縦並び・上下15px・花柄余白20px、PCは横並び・左右15px・既存の花柄余白を確認した。全幅で文字切れ・横はみ出しなし、JavaScript実行エラー0件。375pxと1280pxの画像も目視確認した。
- 元のローカルHEADは `d2e53a6449380fcc8e649b424deeee03bdb761f7` のまま保持した。公開済み2ファイルの作業内容は本番と一致している。管理資料の構成移行と未コミット変更の整理は本公開の対象外である。

## 現在

- Remaining Work: None
- Next Action: None
