# 身バレ対策ブログのローカル作成

- History: [`20261105_CREATE_privacy-measures-blog.md`](../20261105_CREATE_privacy-measures-blog.md)
- Record Date: 2026-11-05
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 同一slugのPHP、HTML、dataset、一覧、TOP、サイトマップ登録は作成前になかった。テンプレート作成の dry-run は `DRY_RUN_OK`。
- 共通CSS `blog-theme-smoky-blue` のアクセントは `#547FA1`。指定文面の `#527F9B` へ共通CSSは変更していない。
- 原稿に女の子紹介はない。FAQは3件。著者と日付は未提供のため Article に入れていない。
- 画像2点は 1000×750。`nolazy` 付き。ローカル静的配信のPC幅1614とスマートフォン幅390で `naturalWidth` 1000、`naturalHeight` 750、横スクロールなし。
- 固定CTAは DOM順 `女の子一覧` `./girls_list.php` `blog-cta-link is-primary`、`本日の出勤情報` `./schedule.php` `blog-cta-link is-secondary`。PCは横並び、幅390では同一行に収まり、先頭は女の子一覧。
- TOPブログ欄は白枠内15件、末尾が本記事、一覧ボタンは枠外。PC枠幅1050、幅390でも枠内。
- `candy-blog-template check` は `CHECK_OK`。PHP構文は通過。`preview-sitemap-lastmod` と `sync-sitemap-lastmod` は変更0。`candy-site-state check` は `CHECK=OK`。
- FAQの1件目はクリックで `active` になり、回答が開いた。

### 決定

- テーマは既存の `blog-theme-smoky-blue` を使い、記事別CSSは作らない。
- 導入文は `blog-lead` と scene1 の両方に置き、H2と目次を一致させる。
- 関連記事は共通選択のブログ3件、エリア3件。
- 生成管理書は site-state の再生成結果をこの変更に含める。生成時刻と基準コミットの更新を含む。本件以外の未コミット仕様書・履歴・画像は含めない。

### 対応

- 公開一式、画像、一覧、TOP最新15件、サイトマップ、生成管理書をローカルに作成した。

### 結果

- ローカルの記事、一覧、TOP、機械検証は完了。本番反映は未実施。

## 現在

- Remaining Work: `main` への commit・push と、本番URLのHTTP、本文、画像、一覧、TOP、サイトマップの確認。
- Next Action: 対象ファイルだけを commit して push し、Actions と本番表示を確認する。
