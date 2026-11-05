# 指名替えブログ末尾CTAの2ボタン化

- Type: MODIFY
- Start Date: 2026-11-05

## 目的

指名替えブログ末尾のCTAを、既存ブログと同じ左「女の子一覧」・右「本日の出勤情報」の2ボタンにし、本番公開する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-shimei-change.php`
- 対象ファイル: `HP/source/kagoshima-deliveryhealth-blog-shimei-change.html` の末尾CTAボタンのみ
- 左ボタン: `./girls_list.php`「女の子一覧」
- 右ボタン: `./schedule.php`「本日の出勤情報」
- 許可された git add・commit・push と本番公開
- 管理書本文、他記事、CTA見出し文、本文リンクは対象外

## 完了条件

- 末尾CTAが既存ブログと同じ2ボタン構成で、PCとスマートフォンで左右に並ぶ
- 対象HTMLだけをGitHubへPushし、本番ページで同じ2ボタンを確認する
- 履歴に経緯と結果を記録する

## 初期情報

- ユーザーは、添付の1ボタンCTAが管理書に従って作られた誤った実装であり、管理書は別途修正中だと指示した
- ボタンの正は他ブログの実装。参照元は girl-choice と autumn-winter-cuddle の `blog-cta-actions`
- 作業開始時の `main` は `3517a229b92f6c7cbc8c83f3cef93ed0fc861fe6`
