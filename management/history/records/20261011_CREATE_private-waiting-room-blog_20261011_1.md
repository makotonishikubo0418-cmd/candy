# Candy個室待機スペース紹介ブログの作成と公開準備

- History: [20261011_CREATE_private-waiting-room-blog.md](../20261011_CREATE_private-waiting-room-blog.md)
- Record Date: 2026-10-11
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- Googleドキュメント原本の対象タブには、導入、9つの主題、画像3点がある。
- 原本画像3点はすべてJPEG・1000×750ピクセルで、メイン画像、個室が並ぶ待機エリア、個室内部の順に対応している。
- 原本にはFAQ、店長おすすめの女の子、お客様の声がなく、現行の共通ブログ生成器をそのまま使うと原本にない内容の補完が必要になる。
- 作業前の生成管理書検証は CHECK=OK だった。

### 決定

- 公開URLは https://www.55810.com/kagoshima-deliveryhealth-blog-private-waiting-room.php とする。
- 原本の見出し順と語り口を維持し、ArticleとBreadcrumbListだけを構造化データへ登録する。
- 画像は内容・寸法を変更せず、説明的なaltと nolazy を設定する。
- 共通生成器は変更せず、この記事だけを現行完成ページ仕様に合わせて構成する。

### 対応

- 公開PHP、本文HTML、専用データセット、共通データセット登録、専用CSS、作成元Text、画像3点を作成した。
- ブログ一覧とトップページへ新規記事を追加し、サイトマップの新規URLと更新対象のlastmodを2026-10-11へ更新した。
- 生成管理書を書き出し、CHECK=OK とSEO問題0件を確認した。
- PHP構文、H1数、目次とsceneの9件一致、画像3点の nolazy、関連記事リンク先の存在を確認した。
- PC 1440pxとスマートフォン向け500px幅で、主要な階層、画像、余白、折り返しを確認した。

## 現在

- Remaining Work: 対象差分の限定ステージング、コミット、Push、GitHub Actions、本番HTTP・DOM・PC/SP表示確認。
- Next Action: 対象ファイルだけをステージし、公開前差分を検証する。
