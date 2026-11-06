# 1ヶ月だけ働きたい女性向けブログの作成と本番公開

- History: [`20261106_CREATE_one-month-blog.md`](../20261106_CREATE_one-month-blog.md)
- Record Date: 2026-11-06
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- ブログカテゴリは求人。固定CTAは「女の子一覧」（`./girls_list.php`）と「求人情報はコチラ」（`./kagoshima-deliveryhealth-blog-privacy-measures.php`）。
- 共通CSSへ `blog-theme-calm-lavender` を追加し、アクセントを `#9275B6` とした。参照中HTMLの `blog.css` バージョンは `0c586236`。記事別CSSは作成していない。
- 原稿に著者、日付、女の子紹介はないため、JSON-LDは Article と BreadcrumbList のみ。
- ローカル確認では、PC幅とスマートフォン幅390で横スクロールはなく、画像2点の自然寸法は各 1000×750、求人CTAは2ボタン、TOPブログ枠は15件で本件が末尾だった。
- 公開コミットは `253e49b431390805fe1eec84e16053e4db19c099`。`e6f66a3..253e49b` を GitHub `main` へ push した。
- 本番デプロイは成功。Actions: https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42179926348
- デプロイ計画は31ファイル、1,262,269バイト。適用ログは31件をSHA-256検証し、obsolete削除は0件。失敗や一時ファイル残存を示すログはない。
- 同じrunの入口検査は `ENTRY_CONTRACT_OK`。公開記事の追跡検査は HTTP 200 で、指定文「1ヶ月だけの短期勤務もOK」を確認した。
- 本番の記事、TOP、ブログ一覧、サイトマップは HTTP 200 で本件URLを含む。画像2点の本番SHA-256はローカルと一致した。
- 本番記事のPC幅では横スクロールがなく、画像の自然寸法は各 1000×750、見出しバーは `rgb(146, 117, 182)`、強調色は `rgb(114, 89, 146)`、CTAは横並びで各 362×60。給与明細画像は「しかも、実際の給与明細も公開しています！」の直後だった。
- 本番記事のスマートフォン幅390では横スクロールがなく、H1は幅内で折り返し、CTAは横並びで各 147×56、POINT枠の右端は350だった。
- 本番TOPはPC幅でブログ枠15件、枠クラスは `lp_0_55_35 w_1050 lm_0_auto bg_f`、先頭は `candy_dominant_girls`、末尾は本件、一覧ボタンは枠の外。スマートフォン幅390でも15件、横スクロールはなく、一覧ボタンは枠の外だった。

### 決定

- 作業ツリーに既にあった本件以外の変更は、公開コミットと履歴コミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像2点、共通CSSを作成または更新した。
- `main` へ push し、本番デプロイの成功と本番URLの表示を確認した。

### 結果

- 公開URL https://www.55810.com/kagoshima-deliveryhealth-blog-one-month.php で記事を公開した。
- 本番デプロイ run 42179926348 は success。

## 現在

- Remaining Work: None
- Next Action: None
