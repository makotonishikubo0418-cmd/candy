# 本番公開と実配信の確認

- History: [20261015_CREATE_maimai-profile-blog.md](../20261015_CREATE_maimai-profile-blog.md)
- Record Date: 2026-10-15
- Sequence: 2
- Status: Completed

## 記録

### 対応

- 今回の33ファイルを限定してCommit/Pushした。公開Commitは `b45e4c8f8c0df0e91ae4425333596733d138f009`、比較元は `b12ed8cd5449caa21ded4834130fe3f6c3090bb3`。
- 公開前にPHP構文、staged差分、配信計画、deploy/release self-test、FTP統合テスト、release checkerとsite-state metadataの回帰テストを確認した。
- 公開計画はHP配下18ファイル、削除0件、保護対象変更0件。本番Actionsは18ファイルすべての転送後SHA-256を検証して成功した。
- Windows作業ツリーのCRLFとGitHub ActionsのLFにより、テキストの生バイト数は異なる。公開後CSSの比較基準をGitのCommit内容に合わせ、配信内容の完全一致を確認した。

### 結果

- 公開URL: https://www.55810.com/kagoshima-deliveryhealth-blog-maimai-profile.php
- Actions: https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38667881301
- Actions結果: `success`。実行ログは `SUCCESS: deployed and SHA256-verified 18 file(s); deleted 0 obsolete file(s)`。
- 本番転送量は2,708,368 bytes。公開記事HTTP 200、指定タイトルの表示を確認した。
- 入口検証はroot 200、title/canonical/H1/indexable、index.php・HTTP・non-wwwの正規転送、直接ホストのnoindexに合格した。
- 本番のブログ一覧・トップは各12件で、ローカルのURL・表示名・順序と完全一致。新記事リンクは各1件。サイトマップ257URL中、新記事は1件。
- 記事内の外部ページ導線11URLはすべてHTTP 200。目次10件と本文10節が一致し、原稿の表示対象段落の欠落は0件。
- 画像3点は本番の生バイトが原本と一致。SHA-256は順に `4ee8e04ba730fcef6204ea7b387ef6ba850e93d216bde18f18cf4f979ed15208`、`86b508333012952c3fec74173c2f0fc42115fd8e5bd0354fb2f38cb9fc1cde68`、`b822acaf35cbe3f4ea8ca71c280b7326860751f33725522ea7d7254f36e890fe`。
- 配信CSSはCommit内容と一致し、SHA-256は `46605f8e748c711eaba10e07731b523ed0a25e773d79213cf6ac745308fab573`。HTML参照の更新キーは作業ツリーから計算した `37ef71f0`。
- 本番をEdgeでPC 1440px／SP 390px表示し、紫の配色・導入・ボタンを画面で確認した。本文画像3点の読み込み・自然寸法・縦横比、Article著者まいまい、BreadcrumbList、canonical、robots=indexは正常。横はみ出し、重複ID、JavaScript例外、同一originのHTTPエラーは0件。
- 第1記録の全体site-state検査で報告した既存別ページ `other:kagoshima-deliveryhealth-blog-candy_dominant_girls` の不整合は今回の対象外として残る。新記事の対象検査はstructure=COMPLETE、seo=OK、images=OK、issues=NONE。
- 記録作成前にGitHub mainが公開Commitと一致することを確認した。managementブランチは存在せず、案件の既存記録は連番1のみだった。

## 現在

- Remaining Work: None
- Next Action: None
