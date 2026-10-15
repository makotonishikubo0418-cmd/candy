# ローカル作成と公開前検証

- History: [20261015_CREATE_maimai-profile-blog.md](../20261015_CREATE_maimai-profile-blog.md)
- Record Date: 2026-10-15
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 指定タブの原稿125段落（H1編集ラベルを含む）、本文10節、小見出し5件、画像3点を取得した。同名ページ・画像・登録との衝突はなかった。
- 作成前のブログ一覧とトップ掲載は各11件で、URL・表示名・順序・終端CTAが整合していた。
- 原本画像はPNG 1448×1086が1点、JPEG 1000×750が2点。公開用と保存用のバイトおよびSHA-256が原本と一致する。

### 対応

- 正式スタッフブログテンプレートを直接適用し、原稿の文章と掲載順を保持した。H1/H2/導入の編集ラベルだけを表示から除外した。
- 著者は原稿どおり「まいまい」。ArticleとBreadcrumbListを掲載し、原稿にないFAQ・女の子紹介・顧客コメントは追加していない。
- `blog.css` に紫variantを追加。既存テーマの宣言・寸法は維持し、参照HTML8件を `v=37ef71f0` へ同期した。
- 画像は冒頭、執筆の話の後、日常の話の前に原稿と同じ順で配置。alt、自然寸法、nolazy、本文画像の説明文を設定した。
- 本文内で紹介する既存記事3件、正規helperが選定した関連記事3件・対応エリア3件へリンクした。
- PHP/dataset/base登録、ブログ一覧、トップ、サイトマップを同期した。トップの掲載行は正規helperの最新15件計算結果を使用し、既存ラッパー内に保持した。
- 原本Text、画像、生成管理書を保存・更新した。

### 結果

- 原稿の全表示対象段落、本文10節と目次、H1一件、小見出し5件、JSON-LD、重複IDなし、画像・公開導線の検査に合格。
- PHP3ファイルの構文、配信スクリプトとrelease checkerのself-testに合格。
- PC 1440px／SP 390pxのブラウザ確認で、画像3点のcurrentSrc・自然寸法・縦横比が正常。横はみ出し、ページJavaScript例外、同一originのHTTPエラーは0件。
- 紫の表示、本文・タイトル・ボタンの可読性を画面で確認した。既存CSSの宣言は新variantと説明コメント以外に変更がない。
- 対象site-stateはstructure=COMPLETE、seo=OK、images=OK、list=1、sitemap=1、issues=NONE。ブログ一覧・トップは各12件で同順。
- サイトマップlastmodは257URL中変更0・不整合0。全体checkには既存の `other:kagoshima-deliveryhealth-blog-candy_dominant_girls` のサイトマップ分類不整合が残る。新記事の不整合ではなく、今回の対象外のため変更していない。

## 現在

- Remaining Work: 公開差分の検証、Commit/Push、Actionsと本番配信・PC/SP表示の確認。
- Next Action: 対象ファイルを限定して公開する。
