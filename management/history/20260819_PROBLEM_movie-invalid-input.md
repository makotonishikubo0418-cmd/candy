# 動画iframeの不正入力応答の修正

- Type: PROBLEM
- Start Date: 2026-08-19

## 目的

動画iframeの不正入力応答の修正を行う。

## 対象範囲

movie_iframeのmid・midgと再生可能性判定。

## 完了条件

再生可能な入力以外を404・noindexにし、本番の入力分岐を確認する。

## 初期情報

旧資料での最初の関連作業: `TASK-20260819-MOVIE-IFRAME-INVALID-INPUT-001`（2026-08-19）。

当時の依頼:

> Keep `movie_iframe.php` noindex and render only a correctly selected playable movie, returning 404 otherwise

出典: [TASK_LOG_2026_08.md](履歴/TASK_LOG_2026_08.md) 22行目。

Start Dateは同じ目的について旧資料に記録された最初の日付を引き継いでいる。これより前の登録の有無は不明。

旧案件ID: `CANDY-MOVIE-IFRAME-INVALID-INPUT-20260819`。
