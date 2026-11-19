# 当日予約ブログのローカル作成と表示確認

- History: [`20261119_CREATE_blog-same-day-reservation.md`](../20261119_CREATE_blog-same-day-reservation.md)
- Record Date: 2026-11-19
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 同一slugの公開PHP、source HTML、dataset、一覧、サイトマップは作成前に存在しなかった。指定画像2点は作成前から存在し、寸法はいずれも 1000×750 だった。
- ブログカテゴリはユーザー指定の「一般」。右CTAは「本日の出勤情報」、リンク先は `./schedule.php`。左は「女の子一覧」、リンク先は `./girls_list.php`。
- ポイント色は既存テーマ `blog-theme-deep-teal`（アクセント `#417F80`）を使用した。指示の `#287C80` と共通CSSのアクセントは別値。記事別CSSは作成していない。
- 著者と日付は原稿にないため JSON-LD へ追加していない。FAQは5件のため FAQPage を同期した。女の子紹介ブロックはないため ItemList は出していない。
- 関連記事は決定論的候補6件。TOP最新15件の末尾が本件。`candy-site-state check --target same-day-reservation` は `CHECK=OK`、`seo=OK`、`images=OK`、`list=1`、`sitemap=1`。
- ローカル表示は `http://127.0.0.1:18888` 経由で確認した。記事画像2点の `currentSrc` は画像URLで、自然寸法はいずれも 1000×750。PC幅1280・スマートフォン幅390で横スクロールなし。アクセントは `#417F80`。CTAは一般構成を確認した。指定の女の子一覧・出勤情報・女の子の選び方リンクも確認した。
- TOPブログ枠は15件、末尾が本件。PC幅・スマートフォン幅390で横スクロールなし。

### 決定

- 既存のディープティールテーマを使い、記事別CSSは作らない。
- 作業ツリーに既にあった本件以外の変更は、今回のコミットへ含めない。

### 対応

- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、生成管理書、画像2点、履歴を作成または更新した。
- PC・スマートフォンで記事とTOPブログ欄の実表示を確認した。

### 結果

- ローカル作成と表示確認は完了した。GitHub反映と本番公開は未了。

## 現在

- Remaining Work: `main` への commit・push と本番公開確認。
- Next Action: 本件ファイルだけを commit・push し、本番公開を確認する
