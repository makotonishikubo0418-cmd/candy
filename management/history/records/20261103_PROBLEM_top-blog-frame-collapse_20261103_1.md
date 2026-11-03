# TOPブログ枠のローカル修復と再発防止

- History: [`20261103_PROBLEM_top-blog-frame-collapse.md`](../20261103_PROBLEM_top-blog-frame-collapse.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 作業前の `main` はローカル・ライブとも `46f913e695e4ca863f71441bd9b7ed7ff257572e`、ahead/behind `0 / 0`、作業ツリーはクリーンだった。
- 崩れ原因: `index.html` で `bg_f` 枠の `</div>` が BLOG INFO 直後にあり、記事15件が一覧ボタン前の枠外へ出ていた。`synchronize_blog_top_index()` が `insert_before_button()` で記事行だけを差し込み、早期閉じタグを枠内へ戻していなかった。
- 修復後、`blog_registry_alignment_errors` は空。枠内リンク15件、一覧末尾15件とタイトル・URL・順序一致。同期の再実行は差分なし。
- `test_candy_blog_template.py` 全14件 OK。追加テストは壊れた枠の検出・同期停止、枠内維持、冪等、複数追加後の構造維持。
- `candy-site-state write/check --target index` は `WRITE=OK` / `CHECK=OK` / `structure=COMPLETE seo=OK`。
- DOM確認（ローカル静的配信）: 枠内 `a.fade` 15件、一覧ボタンは枠外。静的 `/source/index.html` ではCSSパス都合で白背景の見た目は未再現。本番公開後のPC/SP実表示は未実施。
- GitHub Push・本番公開は未実施。本番はまだ旧構造の可能性が高い。

### 対応

- `HP/source/index.html` の閉じタグを15件目の後・一覧ボタン前へ移動。
- `synchronize_blog_top_index()` を枠内記事領域だけの置換へ変更。親枠と閉じタグを特定し、不正時は停止。ホテル用 `insert_before_button()` は変更していない。
- `blog_registry_alignment_errors()` に枠1個・記事が枠内・ボタンが枠外の構造検査を追加。
- 回帰テスト4件を追加。生成管理書を同期。

### 結果

ローカル修復・再発防止・テスト・生成管理書同期まで完了。本番復旧は未完了。

## 現在

- Remaining Work: 明示許可後のGitHub保存と本番公開、公開後のPC/SP実表示確認（白枠・余白・折返し・ボタン・隣接セクション）。
- Next Action: ユーザーのGitHub／本番公開指示を待つ。
