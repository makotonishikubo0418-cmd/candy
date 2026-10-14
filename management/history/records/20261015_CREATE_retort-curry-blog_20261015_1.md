# ローカル作成と公開前検証

- History: [20261015_CREATE_retort-curry-blog.md](../20261015_CREATE_retort-curry-blog.md)
- Record Date: 2026-10-15
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 指定タブの本文、主見出し7件、小見出し8件、原本JPEG3点（各1000×750）を確認した。
- 既存の同名ページ・画像はなく、ブログ一覧とトップページの登録順は作成前に一致していた。
- ローカルリポジトリは `C:/Codex/FSG/Candy` の1件。ローカルブランチはmainのみ。originは `https://github.com/makotonishikubo0418-cmd/candy.git`。mainのahead/behindは0/0。GitHub側には `feature/member-loyalty-mypage` (`933bb909c54f6fdb688dea69a2b9e505606bd0c0`) も存在し、ローカルにはない。managementブランチはNOT_PRESENT。

### 対応

- 公式スタッフブログテンプレートと既存の `blog.css?v=91007576`、deep-pinkテーマを使用した。CSS宣言・テンプレートは変更していない。
- 原稿の文章を保持し、見出しの編集ラベルだけを表示から除外した。個人著者名、FAQ、女の子紹介、顧客コメントは追加していない。質問5件は原稿どおりインタビュー本文として掲載した。
- 画像は原本バイトを保持して、冒頭、玉ねぎの調理、ストック紹介へ配置した。alt、寸法、nolazy、本文画像の説明文を設定した。
- ArticleとBreadcrumbList、7項目の目次、関連記事3件・対応エリア3件、PHP/dataset/base、一覧、トップ、サイトマップを同期した。
- 一覧同期ヘルパーの出力がトップのブログ行をラッパー外へ移動したため、今回の出力だけを修正し、既存ラッパー内に全行を保持した。共通スクリプトは変更していない。

### 結果

- 原稿全段落の存在、HTMLタグ構造、ID重複なし、目次一致、JSON構文、画像・ローカルリンク、一覧順、サイトマップの検証に合格。
- 対象ページのsite-state: structure=COMPLETE / seo=OK / images=OK / list=1 / sitemap=1 / issues=NONE。入力はgate=EXISTING / blocker=NONE。
- 全体checkには既存の `other:kagoshima-deliveryhealth-blog-candy_dominant_girls` サイトマップ分類不整合が残る。今回の対象外であり修正していない。
- ローカルPC/SP表示を確認。SP実測幅390pxで本文の横はみ出し0。スクロール後に画像3点のcurrentSrc・自然寸法1000×750を確認。
- ローカルプレビューでは共通JSの本番直接取得がアクセス拒否文を返すため、共通JSの完全動作は本番検証へ持ち越す。
- 配信スクリプトのself-testとFTP自動化のオフライン統合検証に合格。

## 現在

- Remaining Work: 今回の変更だけをCommit/Pushし、Actions・本番HTTP・画像ハッシュ・PC/SP表示を確認する。
- Next Action: 配信差分確認と本番公開。
