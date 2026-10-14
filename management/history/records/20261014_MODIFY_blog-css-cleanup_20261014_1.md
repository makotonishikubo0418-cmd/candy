# 旧ブログ専用CSS削除と禁止ルール反映

- History: [20261014_MODIFY_blog-css-cleanup.md](../20261014_MODIFY_blog-css-cleanup.md)
- Record Date: 2026-10-14
- Sequence: 1
- Status: Completed

## 記録

### 対応

- 事前に削除対象の未コミット変更なし・実装参照0件を確認し、ユーザー承認済みの次の4ファイルを削除した。Git履歴に元ファイルが存在する。
  - `HP/css/blog-candy_dominant_girls.css`
  - `HP/css/blog-girl-choice.css`
  - `HP/css/blog-private-waiting-room.css`
  - `HP/css/blog-tokyo-souvenir.css`
- `CANDY_BLOG_PAGE_GENERATION_SPEC.md` 第2.2節をCSS配置・再作成禁止・共通部品再利用・参照確認の正本とした。`BLOG_CREATION.md` の専用CSS追加を許容する記述を除き、同節への参照と完了確認を追加した。
- blog.cssの説明コメントを正式テンプレートと全参照HTMLに対応させ、スタイル定義は維持した。記事4件と正式テンプレートのCSS更新キーをSHA-256先頭8桁 `d8facf18` へ統一した。HTML本文や別作業の変更は保持した。
- 生成台帳を更新した。管理書の責任・配置変更はないためINDEXの変更は不要。

### 結果

- ブログ関連HTML12件のCSS参照先は存在し、共通のsource/style.css・css/default.cssを利用する。blog.cssを利用する5件の更新キーを統一した。残る従来記事・一覧には不要な装飾を追加していない。
- 旧CSS4件は削除済み、実装参照0件。CSSのスタイル定義は変更していないため、今回のPC/SP画面再検証は行っていない。
- サイトマップ更新日は254件すべて変更不要。
- 全体checkには既存の `sitemap_lastmod_or_structure=page=other:kagoshima-deliveryhealth-blog-candy_dominant_girls` が残る。別作業の本文変更を含む現在の状態を台帳へ反映し、この既存問題をCSS整理の結果と区別する。
- Git操作・DB操作・本番公開は実施していない。本番の旧CSS削除・反映状態は未確認。

## 現在

- Remaining Work: None（承認されたローカルCSS整理と管理書反映）。既存サイトマップ判定問題と本番反映は対象外。
- Next Action: None。
