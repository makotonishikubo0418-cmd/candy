# Cursor版実行基準と寮ブログ依頼内容の固定

- History: [`20261103_CREATE_dormitory-blog-cursor-full-workflow.md`](../20261103_CREATE_dormitory-blog-cursor-full-workflow.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

#### Git・作業ツリー（本記録作成時）

- リポジトリは `C:\Codex\FSG\Candy` の1件。ブランチ `main`。HEAD `6423a2f9ef127c1fc137ab6b2b4a02df0fe33542`。ライブ `origin/main` 同一。ahead/behind `0 / 0`。
- 未コミット: `management/HISTORY_LIST.md` 変更、旧3情報の履歴2ファイル未追跡、寮画像4ファイル未追跡。
- 寮ページのPHP・source HTML・datasetは未作成。画像のみ存在:
  - `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_1.jpg`
  - `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_2.jpg`
  - `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_3.jpg`
  - `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_4.jpg`
- スモーキーブルーは既存CSS `blog-theme-smoky-blue`（`HP/css/blog.css`）で利用可能。

#### ユーザー意図（文脈から確定した内容。言い換えによる変更なし）

1. 旧記録 [`20261103_CONSULT_blog-html-instruction-set`](../20261103_CONSULT_blog-html-instruction-set.md) は、ブラウザ版Grokへ投げていた完成HTML作成用情報の保存であり、Cursor版の実行正としては古い。
2. Cursor版への依頼では、完成HTML作成に加え、ファイル作成、index等の関連ページ対応、その他必要な対応、GitHub・本番環境アップまでを行う。
3. いま提示した2件（実際の寮記事依頼、テンプレート引き継ぎ）と、それ以前に提示した技術情報、当チャットの文脈を踏まえ、実行指示が出たときに間違いない作業ができるように履歴で固定する。
4. 管理書の修正は権限外。履歴記録で実行可能にする。

#### 経路の使い分け（提示資料の関係）

| 経路 | 正となる参照 | 完了の意味 |
|---|---|---|
| Cursor版・完成記事の全工程（今回の寮依頼） | 本案件の進捗記録 + INDEX経由の既存管理書 | HTML完成、ファイル、関連対応、許可後のGitHub・本番 |
| URL・タイトルのみのテンプレ準備 | [`BLOG_TEMPLATE.md`](../../template/BLOG_TEMPLATE.md) と提示された引き継ぎ書 | ローカル一式・検証・記録まで。本文未作成。Git・本番は含めない |
| ブラウザ版HTMLのみ出力 | 旧案件 `_1` | Cursor版では採用しない |

衝突の扱い（ユーザー明示を優先）:

- 寮記事依頼文の「全文コードブロックで出力」「ファイル更新等はしない」はブラウザ版テンプレ由来である。
- Cursor版メタ指示により、実行時はコード出力だけで終わらせず、ローカルファイル化と関連対応、さらに明示許可後のGitHub・本番まで行う。
- 引き継ぎ書の「本文作成・Git・本番は含めない」は、テンプレ準備だけの依頼に適用する。今回提示済みの寮依頼は完成記事の全工程対象である。

#### 実行時に必ず使う既存管理書（修正しない。読む）

入口: `AGENTS.md` → `management/INDEX.md`

- テンプレ一式: [`management/template/BLOG_TEMPLATE.md`](../../template/BLOG_TEMPLATE.md)
- ブログ仕様: `management/specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- 作成手順: `management/specs/BLOG_CREATION.md`
- 共通生成: `management/specs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
- 画像: `management/specs/IMAGE_MANAGEMENT.md`
- SEO: `management/specs/CANDY_SEO_SPEC.md`
- 既存運用・生成管理書: INDEX第5章・生成docs、`CANDY_OPERATION_BASICS.md`
- Git: `management/GIT_MANAGEMENT.md`
- 本番: `management/specs/CANDY_PRODUCTION_MIGRATION_MASTER.md`、`SERVER_MANAGEMENT.md`
- 履歴: `management/HISTORY.md`
- 関連リンク選定: `management/scripts/candy_page_common.py`
- 正式HTML部品参考: `HP/source/template_kagoshima-deliveryhealth-blog.html`
- CSS: `HP/css/blog.css`、`HP/source/style.css`、`HP/css/default.css`
- 旧専用CSS禁止: `blog-candy_dominant_girls.css`、`blog-girl-choice.css`、`blog-private-waiting-room.css`
- `candy_dominant_girls` HTMLを正式テンプレートにしない

旧3情報からCursorでも維持する技術制約:

- 原文の意味・順序・固有名詞・数値・語り口を保持。無断要約・段落削除禁止。
- 事実の創作禁止（人物・体験・口コミ・料金・サービス・営業時間・対応状況・公開日など、原文にない断定）。
- 原文内の画像指示・仕切り線・設置コメントは実装後に本文へ残さない。
- CTAは次の必須構造。省略・順序変更・クラス変更禁止。長文をボタンに入れない。

```html
<div class="blog-cta">
	<p class="blog-cta-eyebrow">英字ラベル</p>
	<p class="blog-cta-title"><strong>ボックス内タイトル</strong></p>
	<div class="blog-cta-actions">
		<a href="URL" class="blog-cta-link is-primary">短いボタンテキスト</a>
		<!-- 2個目がある場合のみ is-secondary -->
	</div>
</div>
```

- 関連はブログ詳細3件＋エリア詳細3件。`candy_page_common.py` の選定に従う。未確定なら架空リンクで完成扱いにしない。
- 公開robotsは `index`。canonical等は完成時URLに統一。

#### 寮ブログ依頼（実行対象の入力）

- 完成時URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-dormitory.php`
- タイトル / H1: 鹿児島で寮付き求人を探している女性へ｜キャンディの出稼ぎ寮をご紹介
- ポイント色: スモーキーブルー → `main blog-page blog-theme-smoky-blue`
- robots: index
- 出力の実質: 完成HTMLを正式ファイルへ反映し、関連対応まで行う（Cursor版）
- 参考公開URL:
  - `https://www.55810.com/kagoshima-deliveryhealth-blog-girl-choice.php`
  - `https://www.55810.com/kagoshima-deliveryhealth-blog-private-waiting-room.php`
  - `https://www.55810.com/kagoshima-deliveryhealth-blog-tokyo-souvenir.php`
- GitHub: `https://github.com/makotonishikubo0418-cmd/candy`（既存cloneを使用。無断で別clone運用を増やさない）

画像位置（指定どおり。設置後、指示コメントと `-` 仕切りは削除）:

1. 見出し「キャンディの出稼ぎ寮、実際のお部屋を公開！🏠」直下  
   `./imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_2.jpg`  
   コメント: 出稼ぎ中の『帰る場所』になるお部屋。お仕事が終わったら、ここでひと息☺️

2. 見出し「冷蔵庫に電子レンジ、洗濯機も！寮の設備をご紹介🧺」直下  
   `./imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_3.jpg`  
   コメント: 冷蔵庫に電子レンジ、洗濯機も！出稼ぎ中の生活にうれしい設備がそろっています🧺

3. 「お風呂やトイレも見てみよう🛁」章内、「ということで、お風呂とトイレもお見せしちゃいます🔍」の直後  
   `./imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-dormitory_4.jpg`  
   コメント: 毎日使う場所だからこそ気になる水回り。お風呂とトイレもチェックしてみてください🛁

- `_1.jpg` はローカルに存在するが、依頼文の配置指定には含まれない。実行時に画像内容と仕様を確認し、主画像／OGPへの採用可否を決定する。未確認のまま用途を断定しない。

原文構成（保持対象。実行時は原文全文を使用）:

- 導入｜鹿児島で働きたい！でも住むところは？🤔
- H2｜キャンディの出稼ぎ寮、実際のお部屋を公開！🏠
- H2｜冷蔵庫に電子レンジ、洗濯機も！寮の設備をご紹介🧺
- H2｜お風呂やトイレも見てみよう🛁
- H2｜キャンディの寮費は1日2,000円！💰
- H2｜寮の近くには何がある？🚶‍♀️
- H2｜出稼ぎ寮を利用したいときは？🏠
- H2｜鹿児島で寮付き求人を探している女性へ🌷

SEO調整対象: meta description、robots、canonical、h1、h2/h3、パンくず、内部リンク・関連記事、OGP、Article、BreadcrumbList、FAQPage（本文にFAQがある場合のみ）。

#### テンプレート引き継ぎ（テンプレのみ依頼時）

1. `AGENTS.md` → `INDEX.md` → `BLOG_TEMPLATE.md`
2. 指定URL・タイトルからHTML、PHP、登録、JSON-LD、TOP・一覧、サイトマップを作成・検証。本文は未作成のまま。
3. `BLOG_TEMPLATE.md` の参照先に従い、ブログ仕様・共通生成・SEO・変更後検証を確認。
4. `management/specs/generated/` のページ台帳・制作候補・コード／資産台帳・SEO状態を更新・検証。
5. `HISTORY.md` / `HISTORY_LIST.md` で既存案件確認。概要は `management/history/`、進捗は `management/history/records/`。
6. 再開時は最新進捗の `Status`・`Remaining Work`・`Next Action` を確認。
7. 完了範囲はローカル準備・検証・記録まで。本文作成・Git・本番は含めない。

### 決定

#### 実行指示が出たときの作業順序（寮ブログ全工程）

1. `AGENTS.md` と `INDEX.md` から必要管理書を特定し、必要範囲を確認する。本記録を実行入力の正とする。
2. `GIT_MANAGEMENT.md` に従い、必要な読取確認を行う。Git状態変更は個別の明示許可があるまで行わない。
3. 同一URL衝突を確認する。未作成なら、既存ツール／仕様に従い公開入口・source HTML・dataset・共通登録・一覧・TOP・サイトマップを準備する。テンプレ段階を挟む場合も、最終的に本文完成状態へ進める。
4. 指定原文・画像・スモーキーブルー・CTA仕様・SEO・関連3+3で完成HTMLを作成し、`HP/source/kagoshima-deliveryhealth-blog-dormitory.html` に反映する。`CANDY_BLOG_BODY_PENDING` を残さない。
5. 画像寸法・alt・nolazy・主画像／OGP整合を、確認した実ファイルに基づき設定する。未確認寸法を創作しない。
6. 生成管理書の更新・検証、履歴への進捗追記を行う。
7. GitHub保存・本番公開は、ユーザーが対象を明示して許可した範囲だけ実施し、許可前にpushやデプロイしない。
8. 管理書（specs/template/INDEX等）は修正しない。不足は進捗記録に残し、管理書変更が必要ならユーザーへ確認する。

#### 実行時にやってはいけないこと

- 旧ブラウザ版の「HTMLだけチャット出力して終了」を完了とすること
- 管理書の無断修正
- 許可のないGit状態変更・DB操作・本番変更
- 原文にない事実の創作、未確定の関連リンクの仮置き完成扱い
- 濃いピンクテーマの誤用（本記事はスモーキーブルー）
- 画像指示文や仕切り線の本文残留

### 対応

- 旧案件へ `_2` を追加し、Cursor用途の旧扱いと新案件への参照切替を記録した。
- 本案件概要と本進捗を作成し、`HISTORY_LIST.md` に登録する。

### 結果

実行基準と寮ブログ入力は本記録に固定した。記事ファイル作成、関連対応、GitHub、本番は未実施。ユーザーの実行指示待ち。

## 現在

- Remaining Work: 実行指示後の寮ブログ全工程（完成HTML、ローカルファイル、関連ページ、生成管理書、履歴、許可後のGitHub・本番）。
- Next Action: ユーザーの実行指示を待つ。指示後は本記録の決定した作業順序に従う。
