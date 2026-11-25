# CANDY Blog Creation Runbook

- Updated: 2026-11-05
- Applies to: 2件目以降の通常ブログ作成、一覧登録、トップページ連携、検証
- Status: Supporting operational document
- Canonical page specification: `CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- Common image specification: `IMAGE_MANAGEMENT.md`

## 1. 目的と責任範囲

この管理書は、スタッフが用意した原稿からブログを繰り返し作成するときの実行順序と完了確認を管理する。ブログ本文の構造、可変ブロック、JSON-LD、生成ファイルの正規仕様は `CANDY_BLOG_PAGE_GENERATION_SPEC.md` が管理し、この管理書では重複定義しない。

対象は、入力確認、デザイン、SEO、画像、ブログ一覧、トップページの最新15件、サイトマップ、ローカル検証である。Git、GitHub Actions、本番公開、DB操作の権限は付与しない。

URLとタイトルだけで「テンプレを作成して」と依頼され、ユーザーが後からHTMLを作成する場合は、[BLOG_TEMPLATE.md](../template/BLOG_TEMPLATE.md) の経路を使用する。原稿不足として通常生成へ進めず、同書のコードからPHP・HTML・一覧・TOP・サイトマップの一式を準備する。本文完成後は本書の該当する完了確認を適用する。

## 2. 作業開始前の確定事項

1. `AGENTS.md` と `management/INDEX.md` から、現在有効なブログ、画像、SEO、Git、本番公開の管理書を確認する。
2. 原本、公開タイトル、検索意図、canonical URL、slug、使用画像、掲載順と、指示書の「ブログカテゴリ」を確定する。カテゴリの有効値・未指定時の扱いは[正規仕様第2.4節](CANDY_BLOG_PAGE_GENERATION_SPEC.md#24-fixed-article-cta)に従う。
3. ユーザー指定の原稿と[正規仕様第2章](CANDY_BLOG_PAGE_GENERATION_SPEC.md#2-mandatory-rules)を確認し、記事ごとに変える本文・画像と、保持する共通部品を分ける。正式テンプレートと自動生成処理の適用限界は同章に従う。既存ページは外観の参考とし、現行仕様と異なる構成・リンク・文言を引き継がない。
4. 既存の同一slug、canonical、一覧リンク、サイトマップ登録、画像名を確認する。衝突または不明点があれば作成前に停止する。
5. 目的、対象ファイル、完了条件を固定し、無関係な修正を同じ変更に含めない。

### 2.1 作成指示書の記事指定欄

作成指示書には次の欄を含める。ブログカテゴリは選択肢を併記したまま渡さず、どちらか1つを記入する。実施範囲・原文・画像・リンクの指定欄は従来どおり併用する。

```text
公開URL：●●●●●
記事タイトル：●●●●●
ポイント色：●●●●●
ブログカテゴリ：一般 または 求人（どちらか1つを記入）
```

## 3. 標準作成単位

通常の新規ブログでは、正規仕様に従い次を同じ変更単位で扱う。

- `HP/kagoshima-deliveryhealth-blog-<slug>.php`
- `HP/source/kagoshima-deliveryhealth-blog-<slug>.html`
- `HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php`
- `HP/includefile/dataset_base.php`
- `HP/source/blog.html`
- `HP/source/index.html`
- `HP/sitemap.xml`
- [既存サイト運用第6章](CANDY_OPERATION_BASICS.md#6-after-a-change)で更新する生成管理書の差分。
- 必要な画像、およびブログ独自の装飾が必要な場合の `HP/css/blog.css` 更新。

CSSの配置・禁止事項・HTMLとの対応は、[正規仕様の第2.2節](CANDY_BLOG_PAGE_GENERATION_SPEC.md#22-blog-css-ownership)に従う。

## 4. 作成順序

1. 原本の見出し、本文、FAQ、画像、導線を抽出し、情報を欠落させずにページ構成へ割り当てる。
2. 検索意図に合う `title`、meta description、canonical、OGP、H1を作成する。
3. H2の表示順にIDを採番し、目次の文言、順序、リンク先を一致させる。
4. 原稿に存在するFAQ・女の子紹介と必要なJSON-LDを[正規仕様第7章](CANDY_BLOG_PAGE_GENERATION_SPEC.md#7-json-ld)に従って対応させる。記事末尾には[同第2.4節の固定CTA](CANDY_BLOG_PAGE_GENERATION_SPEC.md#24-fixed-article-cta)と関連記事を配置する。指示されたブログカテゴリに対応する右ボタンの表示名・リンク先を選び、CTAの誘導文もその行き先に合わせる。
5. `IMAGE_MANAGEMENT.md` に従って画像を配置し、寸法、`src`、`alt`、`nolazy`、表示結果を確認する。
6. 公開PHP、source HTML、dataset PHP、`dataset_base.php` を同期する。
7. ブログ一覧へ新規記事を正規登録順の末尾に1件追加する。
8. トップページのブログ欄を第7章の最新15件へ同期する。
9. サイトマップへの記事登録と必要な内部リンクを同期する。HP側の変更が揃った後、[既存サイト運用第6章](CANDY_OPERATION_BASICS.md#6-after-a-change)の手順で、今回変更した既存ページを含むサイトマップ更新日と生成管理書を同期し、整合検証を完了する。
10. 第8章の検証を完了する。

## 5. デザイン品質

- 原本の情報量を保ちながら、見出し階層、本文幅、行間、余白、画像、注釈、CTAのリズムをページ全体で統一する。
- 強調ボックスは、装飾だけを目立たせず、要点本文も通常本文以上の可読性を持たせる。
- 枠内の見出しと本文には上下左右の余白を確保し、上枠、下枠、隣接要素へ密着させない。
- 画像説明は `figure` と `figcaption` を基本とし、画像との関係が明確な短い文章、十分な余白、本文より控えめだが読める文字サイズにする。
- CTAの誘導文は正規仕様の固定リンク先と意味を一致させる。PCとスマートフォンで、指定順序、幅、間隔、文字の折返し、押しやすさを確認する。
- 「洗練された」状態はソースだけで判断しない。実表示で文字サイズ、余白、揃い、改行、視線の流れを確認する。

## 6. SEO要件

- 主検索意図を1つ定め、タイトル、H1、導入文、主要H2、まとめに自然に反映する。キーワードの不自然な反復は禁止する。
- `title`、meta description、canonical、OGP、BreadcrumbList、FAQPage、記事情報を相互に一致させる。
- H1は1件とし、H2/H3の階層を飛ばさない。目次は実在するH2だけを同じ順序で掲載する。
- FAQPageは表示中の質問と回答だけを、同じ文意、順序、件数で登録する。
- 関連記事は現在ページ、重複、未公開URL、`href="#"` を除外する。
- 画像の代替テキストは画像内容と本文上の役割を説明し、SEO語句の詰め込みに使用しない。

## 7. ブログ一覧とトップページの最新15件

登録順・選出件数・枠内配置・境界・失敗時の復元・回帰検証の正本は、[共通生成規則第10.1節](CANDY_PAGE_GENERATION_GOVERNANCE.md#101-integration-into-public-routes)。本書ではその規則を再定義しない。

承認された公開順で `HP/source/blog.html` の末尾へ新規記事を登録し、`candy_blog_page.py` が利用する共通同期処理で `HP/source/index.html` へ反映する。一覧の先頭行だけ既存の先頭用余白を使い、後続行は区切り線付きの既存行スタイルを保持する。生成前・生成予定HTML・書込後の検証を行い、既存不整合は新規記事作成に混ぜて修復しない。

## 8. 完了確認

- [ ] 原本の必要内容、slug、canonical、画像と、ユーザー指定のブログカテゴリが確定している。
- [ ] PHP、source HTML、dataset、`dataset_base.php` が一致している。
- [ ] title、description、canonical、OGP、H1、目次、見出し、FAQ、JSON-LDが一致している。
- [ ] 固定CTAが、指定カテゴリに対応する正規仕様第2.4節の構成に一致する。カテゴリ・個数・表示名・リンク先・クラス・DOM順・誘導文とPC/SPの表示順を[検証計画第3.9節](CANDY_VERIFICATION_PLAN.md#39-fixed-page-components)に従って確認・記録した。
- [ ] 画像ファイルが存在し、`src` と `alt` が正しく、実DOMの `currentSrc` が画像URLで、`naturalWidth` と `naturalHeight` が0より大きい。
- [ ] PCとスマートフォンで、文字、余白、画像説明、CTA、横スクロールを確認した。
- [ ] ブログ一覧は全公開記事を正規登録順で1件ずつ保持している。
- [ ] トップページはブログ一覧の末尾15件以下と、URL、表示名、順序が一致している。
- [ ] TOPの構造は第7章の参照先に適合し、同期再実行で変化しない。記事単体とは別に、TOPブログ欄のPC・スマートフォン実表示で枠内配置、余白、折返し、一覧ボタン、隣接セクションを確認し、環境・画面幅・証跡を記録した。
- [ ] サイトマップへの記事登録と必要な内部リンクが同期している。
- [ ] 第4章手順9が完了し、今回変更したTOP・ブログ一覧・既存記事のサイトマップ更新日に不一致がなく、ページ台帳・SEO管理データに対象記事が反映され、生成管理書の整合検証が合格している。
- [ ] PHP構文、HTML構造、重複ID、リンク、画像、生成処理の再実行結果を確認した。
- [ ] 正規仕様第2.2節のCSS配置・禁止事項・参照先・更新キーを、記事HTMLと正式テンプレートの両方で確認した。

本章に未確認・不合格がある場合は、作成完了とせず、ステージング・Commit・Push・本番公開へ進まない。本番公開が指示された場合だけ、該当するGitおよび本番公開管理書に従い、Commit、Push、Actions、production HTTP/DOM、画像表示まで確認する。ローカル作成だけの場合、本番状態を完了として報告しない。

機械検証、生成管理書の整合、記事単体の表示確認を、TOP表示確認の代わりにしない。未確認項目は履歴の残作業として保持する。
