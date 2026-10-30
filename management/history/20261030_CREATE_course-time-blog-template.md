# コース時間ブログのローカルテンプレート作成

- Type: CREATE
- Start Date: 2026-10-30

## 目的

指定URL・タイトルから、ユーザーが後で記事本文HTMLを差し替えるブログのローカル一式を準備する。

## 対象範囲

対象は `kagoshima-deliveryhealth-blog-course-time` のHTML、公開入口PHP、記事用dataset、共通PHP登録、JSON-LD、ブログ一覧、TOP、サイトマップ、生成管理情報と履歴。本文執筆、画像の組込み、Git状態変更、DB操作、本番公開は含まない。

## 完了条件

- 指定URL・タイトルを反映したHTML・PHP・登録一式を作成する。
- Article・BreadcrumbList、ブログ一覧、TOP最新15件、サイトマップを整合させる。
- ローカル検証、生成管理情報の更新・検証、履歴記録を完了する。
- 本文差し替え先と本文未作成・未公開の状態を引き継ぐ。

## 初期情報

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-course-time.php`
- タイトル: `60分じゃ足りない？デリヘルのコース時間、何分を選べば後悔しない？`
- ユーザーは本文HTMLを後から作成・アップロードし、その他の準備をローカルで一式実施するよう指示した。
- 記事名に対応する画像 `_1.jpg`・`_2.jpg`・`_3.jpg` が既にローカルに存在する。これらの既存ファイルは保持する。
- 実行方法は [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md) に従う。
