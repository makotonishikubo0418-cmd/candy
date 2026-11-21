# 美尻女子ブログの作業開始とローカル作成着手

- History: [`20261121_CREATE_blog-bijiri-girls.md`](../20261121_CREATE_blog-bijiri-girls.md)
- Record Date: 2026-11-21
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 公開URL・タイトル・カテゴリ一般・ポイント色 #C85A7C・画像5点・指定リンクがユーザー指示で確定している。
- slug `bijiri-girls` の公開PHP / source HTML / dataset は作業開始時点で存在しなかった。画像5点はローカルに存在し、各 1000×750 だった。
- 既存 `blog-theme-rose-pink` は基準色 #B65B86 のため、指定色 #C85A7C 用の新規テーマが必要である。
- ブランチは `main`、ローカル HEAD は `origin/main` と一致していた。本件以外の未コミット差分が存在する。

### 決定

- 正式テンプレート型のスタッフ記事として作成し、legacy `candy_blog_page.py` の本文生成経路は使わない。
- 女の子紹介は原稿の H3 本文紹介とし、店長おすすめ正式ブロックおよび ItemList は出力しない。
- FAQ は原稿にないため FAQPage を出力しない。著者・日付は未提供のため Article JSON-LD に含めない。
- ポイント色は共通 `blog.css` に `blog-theme-bright-rose-pink`（#C85A7C）を追加する。記事別CSSは作成しない。
- Git 操作・本番公開はユーザーが明示した add / commit / push / 本番公開のみを行う。本件以外の差分はステージしない。

### 対応

- 履歴案件を登録し、ローカル一式の作成を開始する。

### 結果

- 案件登録済み。ローカル作成は進行中。

## 現在

- Remaining Work: 記事一式作成、検証、git add/commit/push、本番公開確認、完了進捗記録
- Next Action: HTML / PHP / dataset / 一覧 / TOP / sitemap / CSS を作成し、検証する
