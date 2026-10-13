# 谷山港ページの本番公開結果

- History: [20261013_OPERATION_taniyamakou-title-publication.md](../20261013_OPERATION_taniyamakou-title-publication.md)
- Record Date: 2026-10-13
- Sequence: 1
- Status: Completed

## 記録

### 対応

- 公開前のmainとGitHubのmainは `d7b3ff7c0492529c437ceddc39788bd62171cc60` で一致し、ステージ済み変更はなかった。承認された2ファイルだけを明示指定してステージ・Commit・Pushした。
- 公開Commitは `41878b7e336e3ae7ba345b500a882efa9fa83c75`。Push後のGitHub mainも同じSHAであることを確認した。ブランチ変更・Pull・Merge・Rebase・DB操作は行っていない。
- 事前計画とActionsの実行計画は2アップロード、0削除、81,655バイトで一致した。PLAN_TOKENは `40e0daea1f29e5411b1c411ec65761530303f87a441d07cce06fd17bf14a76ae`。

### 結果

- [CANDY Production Deploy #276](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38332203893)は対象Commitで `completed / success`。構文・自己試験・統合試験・承認計画検査を通過した。
- 実転送ステップのログで `VERIFIED 1/2: HP/sitemap.xml`、`VERIFIED 2/2: HP/source/kagoshima-deliveryhealth-area-taniyamakou.html` と、2ファイルのSHA-256照合成功・削除0件を確認した。試験用ログと実転送ログを区別した。
- 本番ファイルの照合値は、谷山港HTMLが `7b7a902edca45f308cb9d9d60651fbcaaff4156b6326d892d104e82a94e49378`、サイトマップが `c42015c82aa0dda2b5cca327af365733005322121097040f80d4e0269819aef1`。Commit内の内容、事前計画、本番転送時の再取得照合が対応している。
- 転送処理は一時ファイルの正式名への昇格と、全件照合後のバックアップ削除を正常終了した。別経路でのサーバーディレクトリ再一覧は行っていない。
- [本番谷山港ページ](https://www.55810.com/kagoshima-deliveryhealth-area-taniyamakou.php)はHTTP200。titleとog:titleは両方とも `鹿児島市谷山港で呼べるデリヘル｜対応店舗・ホテル情報` に完全一致した。修正済みローカル公開SEO検査を本番HTTP応答に適用し、問題0件を確認した。
- 本番サイトマップはHTTP200、当該URLのlastmodは `2026-10-13`。公開HTTP応答のSHA-256も上記サイトマップの値と一致した。
- Actionsの実公開入口検査は、ルート200、title・canonical・h1・index可能状態、index.phpと各ホスト・HTTP/HTTPSの正規化、直接ホストのnoindexをすべて確認した。
- 今回はtitle・og:titleと対象URLのsitemap更新日のみの変更で、本文・画像・レイアウト変更はない。PC/SPの目視確認は行っていない。
- 原稿・生成処理・検査処理・ホテル掲載順試験・管理資料の変更はローカルに保持した。今回の履歴もローカル記録であり、これらをGitHubへ公開したとは扱わない。作業ツリー全体は未コミット変更が残っている。

## 現在

- Remaining Work: None（承認済み2ファイルの本番公開範囲）。
- Next Action: None
