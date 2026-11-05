# 指名替えブログCTAの2ボタン修正とローカル確認

- History: [`20261105_MODIFY_shimei-change-cta-buttons.md`](../20261105_MODIFY_shimei-change-cta-buttons.md)
- Record Date: 2026-11-05
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- 既存の girl-choice と autumn-winter-cuddle は、末尾CTAに `is-primary`「女の子一覧」（`./girls_list.php`）と `is-secondary`「本日の出勤情報」（`./schedule.php`）を並べている。
- 共通CSSの `.blog-cta-actions` は2列グリッドで、deep-pink 系の副ボタン色は定義済み。記事別CSSは不要。
- 対象記事は主ボタン「女の子一覧を見る」の1個だけだった。
- サイトマップの lastmod プレビューは `changed=0`。対象HTMLの更新日は既存の `2026-11-05` のままなので、サイトマップは変更しない。

### 対応

- 対象記事の末尾CTAだけを、左「女の子一覧」、右「本日の出勤情報」へ変更した。CTA見出しと本文のプロフィールリンクは維持した。

### 結果

- ローカルPC幅1598では2ボタンが同じ高さ60px・幅362pxで横並び、横スクロールなし。
- ローカル幅390では同じ高さ56px・幅147pxで横並び、ボタン内の文字あふれとページの横スクロールはなし。

## 現在

- Remaining Work: 対象ファイルの git add・commit・push、本番表示確認、完了記録
- Next Action: 許可済み範囲でコミット・Pushし、本番URLの2ボタンを確認する
