# 秋冬のくっつきたくなる気持ちブログのローカルテンプレート作成

- Type: CREATE
- Start Date: 2026-10-29

## 目的

指定URL・タイトルから、ユーザーが後で記事本文HTMLを差し替えるブログのローカル一式を準備する。

## 対象範囲

対象は `kagoshima-deliveryhealth-blog-autumn-winter-cuddle` のHTML、公開入口PHP、記事用dataset、共通PHP登録、ブログ一覧、TOP、JSON-LD、サイトマップ、生成管理情報と履歴。記事本文の執筆、Git状態変更、DB操作、本番公開は含まない。

## 完了条件

- 指定URL・タイトルを反映したHTML・PHP・登録一式を作成する。
- Article・BreadcrumbList、ブログ一覧、TOP最新15件、サイトマップを整合させる。
- ローカル検証、生成管理情報の更新・検証、履歴記録を完了する。
- 本文差し替え先と本文未作成・未公開の状態を引き継ぐ。

## 初期情報

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-autumn-winter-cuddle.php`
- タイトル: `寒くなると誰かにくっつきたくなるのは私だけ？`
- ユーザーは本文HTMLを後から作成・アップロードし、その他の準備をローカルで一式実施するよう指示した。
- 実行方法は [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md) に従う。
- 同名記事画像 `HP/imgHtml/new_202601/blog/autumn-winter-cuddle.jpg` は作業前から存在する未追跡ファイルであり、保持する。
