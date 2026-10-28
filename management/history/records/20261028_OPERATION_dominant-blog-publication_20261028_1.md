# GitHub保存と3ファイルの本番反映成功

- History: [20261028_OPERATION_dominant-blog-publication.md](../20261028_OPERATION_dominant-blog-publication.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: Completed

## 記録

### 対応

- 対象22ファイルを `9fdb63b176cc5f4b668f17362fc90e30da69fb76` にCommitし、GitHub mainへPushした。live mainのSHA一致を確認した。
- 本番対象は `HP/source/index.html`、`HP/source/blog.html`、`HP/sitemap.xml` の3件、削除0件。保護対象index.phpと.htaccessは変更していない。
- デプロイスクリプトself-test、FTP統合テスト、releaseチェックの回帰テスト、管理台帳10文書のcheckに成功した。

### 結果

- [Actions run 40637022865](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40637022865) はsuccess。ログで本番3ファイルのSHA-256照合成功、バックアップ清掃完了後のSUCCESS、共通入口契約の成功を確認した。
- ActionsのPLAN_TOKENは `aa7a36ee227bfca3de2d085bd3aafbae090c063e072d8fe22c8e6bb93a536d01`。改行形式が異なるWindowsプレビューのtokenとは分け、Actions内で生成・承認・照合された値を使用した。
- 配備されたGitスナップショットのSHA-256は以下のとおり。
  - index.html: `de7b8e38b05fb957cbb711433f4e321b78fcefbbe0daafd322528ff5375f4578`
  - blog.html: `3ade825d89737cd53ee2034ec4c1741523281e4f20542e01bf33f9d1b61a1a26`
  - sitemap.xml: `99e4054e429559b292f84266de8140fa4f9c65ec96c428174cde640d1181e377`
- 本番トップ・一覧・記事・サイトマップはHTTP 200。トップと一覧の13件が順序・表記とも一致し、追加記事が末尾であることを検証した。ブラウザーでも追加記事のリンク描画を確認した。
- 本番トップのJSON-LDは解析成功、WebPageのdateModifiedは2026-10-28。サイトマップは258件、トップ・一覧のlastmodは2026-10-28、追加記事は2026-10-15。本番XMLのバイト列は配備Commitと一致した。
- 作業中に現れた別作業の `management/scripts/candy_tool.py`、`candy-blog-template.cmd`、`candy_blog_template.py`、`management/template/BLOG_TEMPLATE.md` はアップロード対象から除外して保持した。リポジトリ全体の未コミット変更なしとは判定していない。

## 現在

- Remaining Work: None
- Next Action: None
