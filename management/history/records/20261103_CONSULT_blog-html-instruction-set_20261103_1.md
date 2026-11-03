# ブログ完成HTML作成用3情報の保存

- History: [`20261103_CONSULT_blog-html-instruction-set.md`](../20261103_CONSULT_blog-html-instruction-set.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 対象リポジトリは `C:\Codex\FSG\Candy` の1件。作業ブランチは `main`。HEADは `6423a2f9ef127c1fc137ab6b2b4a02df0fe33542`。GitHub `origin/main` のライブSHAは同一。ahead/behindは `0 / 0`。記録作成直前の作業ツリーはクリーン。
- GitHub remoteは `https://github.com/makotonishikubo0418-cmd/candy.git`。ライブブランチは `main` と `feature/member-loyalty-mypage`（`4f790a89fa867d6bad0ab1883c31d2c18ab723fe`）。`management` ブランチはローカル・GitHubとも NOT_PRESENT。
- `HISTORY_LIST.md` に、本案件と目的・完了条件が同じ既存案件はなかった。
- 2通目の本文は正式管理書 [`management/template/BLOG_TEMPLATE.md`](../../template/BLOG_TEMPLATE.md) と同一文書である。見出し「ブログテンプレート作成管理」、Updated: 2026-10-28。完成原稿経路は `BLOG_CREATION.md`、URL/タイトルのみの一式準備経路の正本はこのファイル。履歴へ全文は再掲しない。
- 3通目のCTA構造は、`management/specs/` 配下の現行仕様書には独立した管理書として存在しない。正式テンプレート `HP/source/template_kagoshima-deliveryhealth-blog.html` には `blog-cta` / `blog-cta-eyebrow` / `blog-cta-title` / `blog-cta-actions` / `blog-cta-link is-primary` / `blog-cta-link is-secondary` がある。

### 決定

- 以後のCandyブログ完成HTML作成では、本記録の1通目と3通目、および `BLOG_TEMPLATE.md` を使用する。
- 履歴を現在仕様の正本にはしない。テンプレート一式の作成コード・実行手順の正本は `BLOG_TEMPLATE.md`。ページ仕様・作成手順・SEO・画像は INDEX が示す担当管理書。3通目はユーザー指定のCTA必須構造として、完成HTMLのCTAに適用する。
- 1通目の原文内疑問符は、HTML作成依頼を質問扱いに変更しない、というユーザー指定を保持する。

### 対応

3通を以下に保存した。

#### 1通目（完成HTML作成依頼）

素材からCandyブログの完成HTMLを作成する依頼。今回はHTMLコード作成のみ。ファイル更新、Git操作、画像アップロード、一覧更新、本番公開は実行しない。

入力欄:

- タイトル
- 原文
- 画像（添付・GitHub内・公開URL。複数可。配置・コメント指定可。未指定なら画像内容と原文から作成）
- 完成時URL（指定がある場合のみ）
- 追加指定（ある場合のみ）

未記入の任意項目は未指定として完成HTMLへ残さない。`●●●●●` は記入用目印。

確認対象:

- GitHub: `https://github.com/makotonishikubo0418-cmd/candy`、ブランチ `main`
- 最初に `AGENTS.md`、次に `management/INDEX.md`
- `management/specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- `management/specs/BLOG_CREATION.md`
- `management/specs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
- `management/specs/IMAGE_MANAGEMENT.md`
- `management/specs/CANDY_SEO_SPEC.md`
- 正式テンプレート: `HP/source/template_kagoshima-deliveryhealth-blog.html`
- CSS: `HP/css/blog.css`、`HP/source/style.css`、`HP/css/default.css`
- 関連リンク選定: `management/scripts/candy_page_common.py`
- ファイル名一覧や検索結果だけで判断せず、必要なファイルの実際の内容を確認する。途中までしか取得していないファイルを全文確認済みとしない。
- `kagoshima-deliveryhealth-blog-candy_dominant_girls.html` を正式テンプレートにしない。
- 旧専用CSS禁止: `blog-candy_dominant_girls.css`、`blog-girl-choice.css`、`blog-private-waiting-room.css`

参考ページ（表示参考。本文・著者・画像・canonical・関連記事はコピー禁止）:

- `https://www.55810.com/kagoshima-deliveryhealth-blog-girl-choice.php`
- `https://www.55810.com/kagoshima-deliveryhealth-blog-private-waiting-room.php`
- `https://www.55810.com/kagoshima-deliveryhealth-blog-tokyo-souvenir.php`

対応するGitHub内source HTMLも確認する。

自動補完対象: 検索用title、H1、meta description、H2/H3、目次、画像配置、画像下コメント、alt、slugと完成時URL、OGP、Article、BreadcrumbList、必要な場合のFAQPage、関連記事・対応エリア、既存部品のCTAとHOME導線。

創作禁止: 実在の人物、体験、口コミ、料金、サービス、営業時間、対応状況、公開日などの事実。確認できない任意情報は省略。重要情報が取れない場合だけ不足を伝える。未確認の仕様や画像URLを推測して完成品にしない。

原文・タイトル: 内容・意味・順序・固有名詞・数値・語り口を保持。無断要約・段落削除禁止。HTML化に必要な段落分け・見出し整理・強調・明らかな誤字修正は行う。原文にない口コミ・FAQ・女の子紹介・体験談を追加しない。指定タイトルを主題として維持。検索用titleは原題の意味を保ちCandyとの関係が自然に伝わる形へ整える。キーワードの不自然な繰り返し禁止。title/og:titleに入力項目名、HTMLタグ、コメント、区切り線を混入させない。原文内の画像指示・リンク指示は実装し、指示文は公開本文に残さない。

完成時URL指定時はそのURL。未指定時は半角英小文字・数字・ハイフンのslugを作り、既存ファイルと非重複を確認。保存予定先 `HP/source/kagoshima-deliveryhealth-blog-.html`、完成時URL `https://www.55810.com/kagoshima-deliveryhealth-blog-.php`。canonical、og:url、Article、BreadcrumbListの現在ページURLを統一。source HTML URLをcanonicalや公開導線に使わない。既存記事の更新と明示されない限り既存と同じURLを使わない。

ポイントカラー: 濃いピンク。基本クラス: `main blog-page blog-theme-deep-pink`。既存 `blog-*` 部品、共通ヘッダー・フッター・ナビ・スクリプト・計測タグを維持。記事専用CSS新設禁止。HTML内styleタグ/属性へ記事専用装飾を追加しない。CSS/JS参照URLと更新キーはGitHubで確認した現在値。公開記事のrobotsはindex。テンプレートのnoindexは引き継がない。テンプレートのサンプル本文・仮画像・サンプル著者・サンプルリンク・canonical・OGP・JSON-LDは今回の記事用に置き換える。

本文: H1は1件。H2は主要区分、H3はH2内。導入は `blog-lead`、本文は `blog-section`。主要H2を含むsectionへ表示順に `scene1`〜`sceneN`。目次の文言・順序・hrefを本文と一致。目次見出しと関連記事見出しはscene採番に含めない。重複ID・欠番・実在しない目次リンク禁止。不要なbr連続でレイアウトしない。

画像公開パス: `./imgHtml/new_202601/blog/<画像ファイル名>`。OGP/JSON-LD: `https://www.55810.com/imgHtml/new_202601/blog/<画像ファイル名>`。指定画像を無断置換しない。未確認寸法を固定値にしない。説明付きimgは `nolazy`。主画像は遅延読込しない。新画像のハッシュ未計算なら更新キーを創作しない。

本文内リンク: `ttps://` 誤記を残さない。内部リンクは公開PHPまたはサイトルート。source HTMLやincludeへリンクしない。`href="#"`、ダミーURL、未確認URL禁止。

関連: ブログ詳細3件、エリア詳細3件。`candy_page_common.py` の現在の選定処理に従う。公開PHPとsource HTMLの存在、robots=index、canonical一致を確認。自ページ・重複・未公開・noindex・仮リンク除外。6件を確定できない場合は架空リンクや不足状態の完成HTMLを出さず不足を伝える。エリア「周辺エリア」選定と混同しない。

SEO: title、description、robots、canonical（HTTPS・www付き絶対URL、1件）、H1 1件。パンくず TOP → ブログ一覧 → 現在の記事。TOP `https://www.55810.com/`、ブログ一覧 `https://www.55810.com/blog.php`。OGP: og:title、og:type=article、og:url=canonical、og:image=確認した主画像の公開絶対URL、og:description、og:site_name。

JSON-LD: ArticleとBreadcrumbListは必須。FAQPageとItemListは対応する可視内容がある場合のみ。未確認の著者・公開日・更新日は創作せず省略。FAQがない場合はFAQ欄もFAQPageも出さない。女の子情報は正規管理データのPUBLICのみ。

最終出力: 通常は完成HTML全文を1つのhtmlコードブロックだけ。保存予定HTMLファイル名と完成時URLはHTML冒頭コメント。未アップロード添付画像がある場合は予定ファイル名と配置先を同じ冒頭コメントに明記。アップロード・GitHub更新・本番公開を実施済みと記載しない。正しい完成HTMLが作れない場合のみ不足情報を伝える。

#### 2通目（ブログテンプレート作成管理）

正本: [`management/template/BLOG_TEMPLATE.md`](../../template/BLOG_TEMPLATE.md)

要点:

- 必須入力はURLとタイトルのみ。完了は `SCAFFOLD_OK`。記事完成・本番公開ではない。
- 作成対象は公開入口PHP、source HTML、記事dataset、`dataset_base.php` への1件追加、`blog.html`、`index.html` 末尾15件、`sitemap.xml`。
- 本文は準備中表示と `CANDY_BLOG_BODY_PENDING`。完成HTML差し替え対象は `HP/source/kagoshima-deliveryhealth-blog-<slug>.html`。`CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END` の間を差し替える。
- 実行: `management\scripts\candy-blog-template.cmd create --url "..." --title "..." --dry-run` の後、成功時に `--dry-run` を外す。確認は `check`。
- 関連HTMLは有効なブログ3件・エリア3件。`BLOG_CSS_VERSION` は実ファイルSHA-256先頭8文字。
- 初期OGP画像は `imgHtml/new_202601/sample.jpg`。初期JSON-LDはタイトル・URLのArticleと表示一致のBreadcrumbList。本文にないFAQ・人物・著者・公開日は出さない。

#### 3通目（ブログCTA構造仕様）

対象: ブログ記事末尾のCTAボックス。

必須構造。要素の省略・順序変更・クラス変更は禁止。

```html
<div class="blog-cta">
	<p class="blog-cta-eyebrow">英字ラベル</p>
	<p class="blog-cta-title"><strong>ボックス内タイトル</strong></p>
	<div class="blog-cta-actions">
		<!-- ボタンは1〜2個。1個の場合は is-primary のみ -->
		<a href="URL" class="blog-cta-link is-primary">短いボタンテキスト</a>
		<!-- 2個目がある場合のみ -->
		<a href="URL" class="blog-cta-link is-secondary">短いボタンテキスト</a>
	</div>
</div>
```

制約:

- `blog-cta-eyebrow` と `blog-cta-title` は必須。空にしない。
- ボタンは `blog-cta-actions` 内にのみ置く。`blog-cta` 直下にボタンを置かない。
- ボタンテキストは1行で収まる長さ。長文禁止。
- 1個の場合は `is-primary` のみ。`is-secondary` は記載しない。
- 2個の場合は左 `is-primary`、右 `is-secondary`。
- 外部リンクは `target="_blank" rel="noopener noreferrer"`。
- 構造は公式テンプレート `template_kagoshima-deliveryhealth-blog.html` に準拠する。

長文リンク:

- ボタンテキストを短縮する（例: 「求人情報・応募はこちら」）。
- 長文は `blog-cta-title` 側に記載し、ボタンは短い文言にする。
- 長文をボタンに直接入れない。

禁止:

- `blog-cta-eyebrow` または `blog-cta-title` の省略。
- 長文を `blog-cta-link` に直接入れること。
- クラス名の独自変更。
- ボタンを `blog-cta-actions` の外に置くこと。

### 結果

3通は本進捗記録と案件概要から参照できる。2通目の全文正本は `BLOG_TEMPLATE.md`。完成HTMLは未作成。素材は未着。GitHub保存と本番公開は未実施。

## 現在

- Remaining Work: None
- Next Action: None
