# きれい系とかわいい系ブログの作成と本番公開

- History: [`20261106_CREATE_beautiful-cute-blog.md`](../20261106_CREATE_beautiful-cute-blog.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業ディレクトリは `C:\Codex\FSG\Candy`。指定画像5点は `HP/imgHtml/new_202601/blog` に存在し、Git未追跡、各 1000×750 の JPEG だった。
- ブログカテゴリはユーザー指定の「一般」。CTAは「女の子一覧」（`./girls_list.php`）と「本日の出勤情報」（`./schedule.php`）の2件。
- 紹介したプロフィール番号は、アル 1234、キヌア 772、アン 1248、ナツメグ 69。キヌアの台帳画像状態は `LOCAL_ONLY` のため、台帳画像は公開せず、原稿指定のブログ画像とプロフィールURLだけを使用した。
- 共通CSSへ `blog-theme-dusty-lavender` を追加し、アクセントを `#8976AF` とした。参照中HTMLの `blog.css` バージョンは `24f0c50d` に揃えた。記事別CSSは作成していない。
- ローカルプレビューは `http://127.0.0.1:8878/kagoshima-deliveryhealth-blog-beautiful-cute.php` と `http://127.0.0.1:8878/index.php`。PC幅とスマートフォン幅390で、画像の自然寸法は各 1000×750、横スクロールは0、CTAは2ボタン、TOPブログ枠は15件で本件が末尾、一覧ボタンは枠の外だった。
- 公開コミットは `e408a8589edcd0df3002db145f503e935690d192`。GitHub `main` へ push 済み。
- 本番デプロイは成功。Actions: https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42151390140
- 本番記事は HTTP 200。PC幅1614とスマートフォン幅390で、画像5点の `currentSrc` は指定JPEG、自然寸法は各 1000×750、アクセントは `#8976AF`、横スクロールは0、CTAは指定2件。本番TOPは15件、本件が末尾、一覧ボタンは枠の外、横スクロールは0だった。

### 決定

- 著者と日付は原稿にないため、JSON-LDへ追加しない。FAQはないため FAQPage は出力しない。
- 作業ツリーに既にあった本件以外の変更は、公開コミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像5点、共通CSSを作成または更新した。
- `main` へ push し、本番デプロイの成功と本番URLの表示を確認した。

### 結果

- 公開URL https://www.55810.com/kagoshima-deliveryhealth-blog-beautiful-cute.php で記事を公開した。
- 本番デプロイ run 42151390140 は success。

## 現在

- Remaining Work: None
- Next Action: None
