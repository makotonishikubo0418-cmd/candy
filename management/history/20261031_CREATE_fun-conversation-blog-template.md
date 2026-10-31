# 会話が楽しい女の子ブログのローカルテンプレート作成

- Type: CREATE
- Start Date: 2026-10-31

## 目的

指定URL・タイトルから、ユーザーが後で記事本文HTMLを差し替えるブログのローカル一式を準備する。

## 対象範囲

`kagoshima-deliveryhealth-blog-fun-conversation` のHTML、公開入口PHP、記事用dataset、共通PHP登録、ブログ一覧、TOP、JSON-LD、サイトマップ、生成管理情報と履歴。本文執筆、画像変更、Git状態変更、DB操作、本番公開は含まない。

## 完了条件

- 指定URL・タイトルを反映したHTML・PHP・登録一式を作成する。
- Article・BreadcrumbList、ブログ一覧、TOP最新15件、サイトマップを整合させる。
- ローカル検証、生成管理情報の更新・検証、履歴記録を完了する。
- 本文差し替え先と本文未作成・未公開の状態を引き継ぐ。

## 初期情報

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-fun-conversation.php`
- タイトル: `喋って、笑って「もう時間！？」😆 鹿児島デリヘルキャンディの会話が楽しい女の子3人`
- ユーザーが本文HTMLを後から作成するため、その他の準備をローカルで一式実施する依頼。疑問符を含まない別メッセージで実行指示を受けた。
- 実行方法は [BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md) に従う。
