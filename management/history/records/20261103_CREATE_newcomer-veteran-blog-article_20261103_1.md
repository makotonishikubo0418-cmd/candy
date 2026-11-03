# 新人とベテラン比較ブログの完成HTML反映と検証

- History: [20261103_CREATE_newcomer-veteran-blog-article.md](../20261103_CREATE_newcomer-veteran-blog-article.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 完成時URLはユーザー訂正により `kagoshima-deliveryhealth-blog-newcomer-veteran.php`。
- 公開入口PHP・dataset・一覧・TOP・サイトマップ登録はテンプレート案件で既存。
- 画像 `_1.jpg` / `_2.jpg` は各1000x750で存在。
- 女の子台帳にイロハ（1361）・ツバサ（1335）は `PUBLIC`。ネオ（1497）・ミヤビ（1494）は台帳未登録。
- 関連記事候補はブログ3・エリア3（shiroutogirl / slendergirl / poccharigirl、koraicho / yoshino / kinseicho）。
- FAQは原稿にない。

### 対応

- source HTMLへ完成本文を反映。テーマは `blog-theme-deep-pink blog-theme-purple`。
- 導入を `blog-lead`、H2をscene1〜5、目次一致。画像2を「あなたはどっち派？🤭」の直後・「新人さんをご紹介🌸」の直前に `figure`/`figcaption` で設置。
- プロフィールリンク4件を参考ページ同仕様の `a.fade` で設置。設置指示コメントと仕切り線は残していない。
- meta description・OGP・Article・BreadcrumbListを本文に合わせて更新。FAQPage・ItemListは未出力。
- CTA（女の子一覧／出勤情報）と関連記事asideを設置。
- `candy-site-state` の write/check を実施。対象は `structure=COMPLETE seo=OK images=OK list=1 sitemap=1 issues=NONE`。

### 結果

- ローカル検証とPC（通常幅）／SP（390px）表示を確認。画像2の自然寸法1000x750、横スクロールなし、画像位置・キャプション・見出し階層を確認。
- Git・DB・本番公開は未実施。公開時は既存PHP等と合わせてHTML差し替えを反映する。

## 現在

- Remaining Work: None（依頼されたローカル完成HTML反映の範囲）
- Next Action: None（本番公開は別指示）
