# 出稼ぎ初出勤5ステップブログのローカル作成と表示確認

- History: [`20261109_CREATE_blog-dekasegi-steps.md`](../20261109_CREATE_blog-dekasegi-steps.md)
- Record Date: 2026-11-09
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像2点は各 1000×750 の JPEG だった。
- ブログカテゴリはユーザー指定の「求人」。右CTAは「求人情報はコチラ」、リンク先は `./kagoshima-deliveryhealth-blog-privacy-measures.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- テーマは既存の `blog-theme-smoky-blue` を使用。指定文面の `#4F7598` と共通CSSアクセント `#547FA1` は別値で、共通CSSは変更していない。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQは5件で FAQPage と一致。女の子紹介 ItemList はない。
- FAQ見出しは生成管理書のFAQ検出条件に合わせ、原文の「気になる疑問」を「よくある疑問」とした。質問本文と回答の意味は原文どおり。
- 本文の寮記事・1ヶ月記事リンクから `class="fade"` は外し、関連記事数検証と衝突しないようにした。関連記事は決定的選定どおりブログ3・エリア3。
- ローカル表示は HP ルート直下の一時プレビュー経由で確認した。
- 記事は PC幅とスマートフォン幅390で横スクロール0。画像2点の `currentSrc` は各JPEGで、自然寸法は 1000×750。CTAは2ボタンで、左が女の子一覧、右が求人情報はコチラ。
- TOPブログ枠は15件、末尾が本件、一覧ボタンは枠の外。枠内の横スクロールは0。PC枠幅1050、スマートフォン枠幅390。

### 決定

- 記事別CSSは作らず、既存の `blog-theme-smoky-blue` を使う。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認
- Next Action: 本件ファイルだけを commit・push し、本番URLを確認する
