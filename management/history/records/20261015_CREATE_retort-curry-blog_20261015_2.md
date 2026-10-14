# 本番公開と表示検証完了

- History: [20261015_CREATE_retort-curry-blog.md](../20261015_CREATE_retort-curry-blog.md)
- Record Date: 2026-10-15
- Sequence: 2
- Status: Completed

## 記録

### 対応

- 25ファイルを `cd035ba344de23a562299bbb2d81bf4b40945b2e` にCommitし、origin/mainへPushした。live GitHub mainのSHA一致を確認。
- Actions [38650886947](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38650886947)、deploy job `116149091174` が成功した。
- Actions内のPHP構文検証、配信自動化試験、実配信計画・承認値検証を通過。配信は画像3点、公開PHP、dataset PHP、dataset_base.php、source HTML、blog.html、index.html、sitemap.xmlの10件。削除0件、転送805,067バイト。
- 実配信PLAN_TOKEN: `c689b559d595b99de847e26d9f0ab623a68c022adf4bcbc1edc0636c96ea99a2`。ローカルCRLFを含む事前計画とはバイトが異なり、ActionsはGitの確定LFバイトから同一run内で計画・照合・配信した。
- 10/10ファイルのFTP再取得SHA-256照合と一時バックアップ削除処理が成功した。保護対象のHP/index.php・HP/.htaccessは配信対象外。DB操作なし。

### 結果

- 公開URL: https://www.55810.com/kagoshima-deliveryhealth-blog-retort-curry.php
- 本文HTTP 200、robots=index、canonical一致、Article/BreadcrumbList正常。
- トップ・ブログ一覧・サイトマップはHTTP 200で新記事の登録を確認。関連記事3件・対応エリア3件も全件HTTP 200。
- 画像3点はHTTP 200 / image/jpegで原本・ローカル公開画像・本番画像のSHA-256が一致した。

| 画像末尾 | SHA-256 |
|---|---|
| `_1.jpg` | `d8f36dafe142b39737f9e9711637fa7eefece04209e65a17d23fa520d382be81` |
| `_2.jpg` | `96d8d8c2e7df913ff53c1a7b245d9e0e327cb29ef152fe211e26f49709d06656` |
| `_3.jpg` | `6508fa2fee3f6ac40e7a196e5fbb21e7c4fd5ee3de0fbb1b6e763102a760bd45` |

- 本番PC幅1440px・SP幅390pxで本文・画像・説明文・関連記事を確認。本文の横はみ出し0。スクロール後の画像3点はcurrentSrcが正式画像URL、complete=true、自然寸法1000×750。公開ページに由来するコンソールエラーなし。
- ローカルプレビューで記録した共通JSのアクセス拒否は本番では再現しなかった。
- 共通入口検査（root 200、canonical、H1、公開index可、index.php・HTTP・non-wwwのリダイレクト、直接ホストnoindex）が成功した。
- 対象外のcandy_dominant_girls HTMLと未追跡画像3点はバイトを保持して未公開のままとした。生成管理書は公開コミットの内容と現在のローカル内容を分けて保存し、ローカル側を再生成後、10出力すべてpreview差分0を確認。
- 既存のcandy_dominant_girlsサイトマップ分類不整合は継続して対象外。本案件の対象ページに未解決の検証項目はない。

## 現在

- Remaining Work: None
- Next Action: None
