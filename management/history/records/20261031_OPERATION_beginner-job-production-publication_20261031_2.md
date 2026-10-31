# 本番公開と反映確認の完了

- History: [20261031_OPERATION_beginner-job-production-publication.md](../20261031_OPERATION_beginner-job-production-publication.md)
- Record Date: 2026-10-31
- Sequence: 2
- Status: Completed

## 記録

### 結果

- 公開コミット: `1ac9ac66c03ede430da2a4afc45dc14ea71a4220`。画像3件の名前変更と管理履歴をmainへコミット・Pushした。画像のバイト変更はない。
- 比較元: `59ed477644458453c7d93d6bcd48e57bebb53926`。GitHub保存済みの未反映ブログ一式を含む10ファイル・621,100バイトを公開し、削除は0件。保護対象index.php・.htaccessは対象外。
- [preview 41175061755](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41175061755) と [deploy 41175097281](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41175097281) は成功。PLAN_TOKENは `fbdc557e8820751321a16f466879c00e5d433e454e300a19cbc3b2d8daeabdba`。
- 対象PHP構文、デプロイツールの事前検査、件数・SHA・トークン照合が成功。実デプロイログの `VERIFIED 10/10` と `SUCCESS: deployed and SHA256-verified 10 file(s); deleted 0 obsolete file(s)` を確認。バックアップcleanup失敗時に成功を返さない既存処理を確認し、当該実行の成功をcleanup完了の証拠として使用した。
- 同じ実行の本番入口検査は `ENTRY_CONTRACT_OK`。root 200、canonical・indexability、index.php・HTTP・non-wwwリダイレクト、直接ホストnoindexが合格。
- [公開記事](https://www.55810.com/kagoshima-deliveryhealth-blog-beginner-job.php)、TOP、ブログ一覧、サイトマップ、画像3件を一括取得し、7URLすべてHTTP 200。記事mainとJSON-LDはローカルの公開対象と完全一致。TOP・一覧・サイトマップに記事URLが存在し、画像3件のHTTP取得バイトのSHA-256がローカルと一致。
- ブラウザーでPC・スマートフォンの初期表示を確認。実測viewport幅1518pxと434pxで横方向のページ超過なし。両方で記事画像3件は読込済み、自然サイズ1000×750。スマートフォンでは目次7リンクの対象ID存在を確認した。全スクロール位置の見た目を確認したという意味ではない。
- 本文・CSS・スクリプトの修正、DB操作、無関係な調査は行っていない。生成管理台帳の再生成は今回の公開作業に追加していない。前回保存時に記録した画像ファイル名の不一致は今回解消された。

## 現在

- Remaining Work: None（依頼されたGitHub保存・本番公開・必要な反映確認の範囲）
- Next Action: None
