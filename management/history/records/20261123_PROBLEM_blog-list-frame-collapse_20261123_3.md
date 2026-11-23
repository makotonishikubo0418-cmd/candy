# ブログ一覧枠修正のGitHub反映と本番公開確認

- History: [`20261123_PROBLEM_blog-list-frame-collapse.md`](../20261123_PROBLEM_blog-list-frame-collapse.md)
- Record Date: 2026-11-23
- Sequence: 3
- Status: Completed

## 記録

### 確認済み事実

- コミット `c261fa7bd8125cabd189854fd5b3cab3cd0df79a` を `origin/main` へ Push し、ローカル HEAD とライブ remote SHA が一致した。
- 反映対象は `HP/source/blog.html` と本件履歴のみ。他の未コミット変更はステージしていない。
- GitHub Actions `CANDY Production Deploy` run `45117169829` は success で完了した。
- 公開入口 `https://www.55810.com/blog.php` は HTTP 200。HTML上で `w_1050` 閉じタグが最終記事直後・お問い合わせボタン直前にあり、移籍記事直後の早期閉じは解消済み。
- `blog.php` は `source/blog.html` を読み込むため、公開PHP本体の変更は不要。本番HTMLで修正構造を確認した。
- 本番表示計測:
  - PC（1280px）: 枠幅1050px。記事54件すべて枠内。移籍記事と11月21日追加4記事の行幅は同値（940px）。ボタンは枠外。
  - SP（390px）: 枠幅376px。同4記事は枠内・同幅。横スクロールなし。ボタンは枠外。
- entry-contract 確認は `ENTRY_CONTRACT_OK`。

### 結果

- ローカル修正、GitHub反映、本番公開、本番PC/SP表示確認まで完了した。

## 現在

- Remaining Work: None
- Next Action: None
