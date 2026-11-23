# ブログ一覧枠の早期閉じタグ調査

- History: [`20261123_PROBLEM_blog-list-frame-collapse.md`](../20261123_PROBLEM_blog-list-frame-collapse.md)
- Record Date: 2026-11-23
- Sequence: 1
- Status: Modification Pending

## 記録

### 確認済み事実

- 対象ソースは `HP/source/blog.html`（公開入口 `blog.php`）。
- 一覧の幅制約親は 131行目の `div.lp_0_55_35.w_1050.lm_30_auto.bg_f`。`style.css` の `.w_1050` はデスクトップで `width: 1050px`。
- 182行目の移籍記事リンクまではその親要素の子である。
- 183行目の `</div>` が上記 `w_1050` 親を閉じている。
- 184–187行目の4記事（`shimei-honshimei` / `shimei-up-tips` / `bijiri-girls` / `30s-40s-job`）は `w_1050` の外、`div.lp_0_7`（130行目）の直下に出ている。
- HTMLパーサによる `div` 追跡ではファイル全体の開閉数は一致（未閉じ・余分な閉じの総数ずれはない）。問題は閉じタグの位置ずれ。
- `HP/source/index.html` の同種枠（1149–1166行付近）では、同じ4記事も含めて枠内に収まっており、今回の崩れ構造は一覧ページ側に限定される。

### 決定

- 本ターンは調査のみ。ソース修正は行わない。

### 結果

- 原因は、幅制約親 `w_1050` の閉じタグが移籍記事直後（183行目）に置かれ、11月21日追加の4記事がその外へ出たこと。

## 現在

- Remaining Work: `HP/source/blog.html` 183行目の `</div>` を、187行目の最終記事リンクの後・188行目お問い合わせボタンの前へ移す修正と、修正後の表示確認
- Next Action: ユーザーから修正実施の明示指示を待つ
