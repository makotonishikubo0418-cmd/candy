# 本番転送成功と公開表示不備の確認

- History: [20261029_OPERATION_autumn-winter-cuddle-production-publication.md](../20261029_OPERATION_autumn-winter-cuddle-production-publication.md)
- Record Date: 2026-10-29
- Sequence: 1
- Status: Modification Pending

## 記録

### 対応

- 公開SHA: `2ac4cefd5d64535dab1435650a37847262f0e054`。before SHA: `437ee2f60bacda646cf7e3bd95dab61e02d978e2`。
- [preview run 40825160526](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40825160526) は成功。8アップロード、453071バイト、削除0。PLAN_TOKEN: `bb42c4c09353b8e5c958ff932c2fe82c9f1098d60a3e893616c178105da41d78`。
- 同じSHA・件数・トークンで [deploy run 40825230241](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40825230241) を起動し、successを確認した。
- 対象は記事HTML・公開PHP・dataset・dataset_base.php・記事JPEG・TOP・ブログ一覧・サイトマップ。保護対象のindex.phpと.htaccessは変更していない。

### 確認済み結果

- Actionsの事前テスト、対象PHP構文、承認照合、8/8ファイルの転送後SHA256照合が成功。正常終了により同処理内のバックアップ清掃も完了。
- 同runの本番入口確認が成功。root 200、canonical、H1、indexability、index.php・HTTP・非wwwの転送、直接ホストのnoindexを確認。
- 記事・TOP・ブログ一覧・サイトマップ・記事画像を並行HTTP取得。すべて200。本文・Article/BreadcrumbListのJSON構文・対象URLのリンクとサイトマップ掲載を確認。公開JPEGはローカルとSHA256一致。
- ブラウザのPC・スマートフォン表示でH1、4本文節、1000×750の記事画像の読み込みを確認。

### 表示検証の不合格事項

- 最新ローカルHTMLの1行目に「確認済み：指定の…」という編集説明、3行目にMarkdownの開始囲み記号、末尾に終了囲み記号が含まれている。公開DOM・PC表示にも混入していた。
- スマートフォン表示でも横スクロールを確認した。横スクロールの原因は未特定であり、説明文混入が唯一の原因とは断定していない。
- 最新ローカルの内容をそのまま公開した結果であり、記事内容やスクリプトを追加修正していない。転送成功を表示品質の合格として扱わない。

## 現在

- Remaining Work: HTMLに混入した編集説明とMarkdown囲み記号の除去、スマートフォンの横スクロール原因の確認・必要な修正、再公開と表示確認。
- Next Action: 表示不備をユーザーへ報告し、修正対象を確定したうえで対応する。本記録をmainへ保存して今回の公開処理を終了する。
