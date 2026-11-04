# nightwork-style監査指摘の修正・GitHub反映・本番確認完了

- History: [20261104_CREATE_nightwork-style-blog-article.md](../20261104_CREATE_nightwork-style-blog-article.md)
- Record Date: 2026-11-04
- Sequence: 4
- Status: Completed

## 記録

### 決定

- ユーザーは管理書・検査プログラム・今回の未達箇所の修正と、GitHub反映・本番公開までを明示的に指示した。
- 前記録のサイトマップ17件、生成管理書の更新漏れ、FAQ誤判定を対象とした。原文照合・追加表示確認はユーザー確認済みとして再実施しない。

### 対応

- `BLOG_CREATION.md` の作成単位・作成順序・完了チェックを、`CANDY_OPERATION_BASICS.md` 第6章のサイトマップ更新日同期・生成管理書更新・整合検証へ接続した。未確認・不合格があればステージング・公開へ進まない条件を明記した。
- 正式ブログ仕様の完了条件と `INDEX.md` の参照関係を同期した。変更した管理書のUpdatedは2026-11-04。
- `candy_site_state.py` は「よくある質問」「よくあるご質問」「よくある…疑問」「FAQ」を含むH2の範囲で、HTML要素の正確な `faq-item` クラスを数えるよう修正した。FAQ以外に同じクラスを使用する箇所、コメント、script、style、template、類似クラス名を除外する。
- 回帰テストを拡張し、見出しの表現差、入れ子の装飾、属性の空白、非FAQ区間、実記事を検証した。検査の都合で記事本文・見出しを変更していない。
- サイトマップ17件のlastmodを2026-11-03から2026-11-04へ同期した。267URLの登録内容は維持した。
- 生成管理書7ファイルを再生成し、nightwork-styleをページ台帳・SEO管理データへ追加した。

### 結果

- `SITE_STATE_METADATA_TESTS: passed`。旧検査とのsource HTML比較で件数が変わったのは、同じ誤判定原因を持つnightwork-style（0→4件）とshort-time-job（0→5件）のみ。両記事ともFAQPageの件数と一致した。
- 更新後に生成管理書10ファイルのディスク内容を検証し `CHECK=OK`。対象記事は `structure=COMPLETE seo=OK images=OK list=1 sitemap=1 issues=NONE`。生成データ上のSEO判定は275件すべてOK。これは静的検査結果であり検索エンジンの評価を示さない。
- 変更管理書の参照先・Updated・サイズ、ステージ済み差分を確認し、`git diff --cached --check` 合格。
- 修正コミット: `19dd15c49f8212729b379e16c598c17d76c304b6`。mainへPush済み。
- [Actions 41808648571](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41808648571) は成功。deploy job `126501938128` の回帰テスト・公開前検証・本番転送・公開入口検証が合格。
- 本番転送は `HP/sitemap.xml` の1件、削除0件。ログで `SUCCESS: deployed and SHA256-verified 1 file(s); deleted 0 obsolete file(s)` と `ENTRY_CONTRACT_OK` を確認した。
- 本番 `https://www.55810.com/sitemap.xml` はHTTP 200、修正コミットのファイルとバイト単位で一致。SHA-256は `4905cbc62fa2ed05c1643ac25ae226d96cf93b9b5fce34916e1f0b164b2d63f4`。17件の日付が2026-11-04になっていることを確認した。
- 対象記事の公開PHPはHTTP 200、FAQ本文4件・JSON-LD4件で一致。今回、記事HTML・CSS・画像は変更していない。
- 既存の別作業分は保持した。`BLOG_CREATION.md` は今回の修正差分だけをステージし、従来のTOP表示検証に関する未コミット差分を取り込んでいない。

## 現在

- Remaining Work: None
- Next Action: None
