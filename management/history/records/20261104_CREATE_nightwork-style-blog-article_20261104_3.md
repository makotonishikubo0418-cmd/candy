# ナイトワーク働き方ブログの公開後SEO・適合性監査

- History: [`20261104_CREATE_nightwork-style-blog-article.md`](../20261104_CREATE_nightwork-style-blog-article.md)
- Record Date: 2026-11-04
- Sequence: 3
- Status: Modification Pending

## 記録

### 決定

- ユーザーはCursorでの作成完了後に「実行」と指示し、対象記事のSEOおよび提示済み作成指示・管理書への適合性監査を依頼した。修正・Git操作・再公開は今回の依頼に含まない。
- 原文との一致はユーザーが「それは不要、一致している」と確認したため、独立した原文照合を対象外とした。
- 表示についてユーザーが「表示は確認済みいらん」と指示したため、追加の表示確認を終了した。ユーザー確認と今回の実測を区別する。
- 適用規則はAGENTS第2・3・4節、INDEX第5.1節から到達したブログ仕様、BLOG_CREATION、共通生成規則、画像・SEO仕様、既存サイト運用、検証計画、Git管理、本番移行管理の検証範囲、HISTORY。

### 確認済み事実

監査日時: 2026-11-04 18:17 JST時点。環境: `C:\Codex\FSG\Candy` と `https://www.55810.com/`。

#### 公開・基本SEO・登録

- 公開記事はHTTP 200。title、description、canonical、robots index、H1 1件、OGP必須6項目、JSON-LD構文の公開レスポンス検証は問題0件。公開レスポンスにX-Robots-Tagのnoindexはなく、robots.txtも対象URLのGooglebot取得を許可している。
- ローカルsource HTMLと公開PHPレスポンスのmainおよびJSON-LDが一致した。JSON-LDはArticle、BreadcrumbList、FAQPage。未提供のauthor・datePublishedは追加されていない。
- scene1からscene6の順序と目次6件の名称・リンク先が一致し、DOM上の重複IDは0件。FAQは表示用HTML4件、FAQPage4件で、質問・順序・回答の文意が一致する。絵文字・句読点の表現差はあるが、内容の欠落や異なる回答は認めなかった。
- 本文の画像・リンク設置指示の文言、本文準備中マーカーは残っていない。
- 公開PHP・dataset PHPの構文検査は合格。dataset_baseのcaseとHTMLからPHPへの変換登録は各1件。
- 公開ブログ一覧22件、TOPブログ欄15件。登録URL・名称・順序が一致し、対象記事は末尾。公開TOPの白枠、BLOG INFO、行、枠外の一覧ボタンの構造検査も問題0件。TOP DOMで対象リンクが所定の白枠内にあることを確認した。
- 対象記事はサイトマップに1件登録され、対象記事自身のlastmodは2026-11-04。
- 記事・一覧・TOP・サイトマップ・robots.txt、記事main内の遷移先、直接参照するCSS・JavaScript・画像の計23URLはすべてHTTP 200。関連記事はブログ3件・エリア3件。対象記事と同じtitleまたはcanonicalの別source HTMLは検出されなかった。
- 公開画像2枚はimage/jpeg、1000×750。ブログCSS、共通CSS2件、common.js、画像2枚の公開バイトはローカルとSHA-256一致。
- blog.cssのSHA-256先頭8桁は4785552c。参照HTML17件が同じv値で一致。今回変更された既存記事15件と正式テンプレートはCSS参照キーのみの変更で、本文の変更はない。CSS差分はテラコッタテーマに限定されている。
- Actions [41804359000](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41804359000) は公開コミットe95093a508560d46a3f0115f2ea777b36e73adaeに対してsuccess。ログで26ファイルの配置・最終SHA-256検証、削除0件、ENTRY_CONTRACT_OKを確認した。

#### 今回の表示確認と範囲

- 記事のPC実測viewportは1600×1111、スマートフォン実測viewportは434×938。指定値とブラウザの実測値が異なるため実測を採用。
- 記事は両画面幅で横方向のはみ出しなし。画像2枚のcurrentSrcが正しい画像URL、complete=true、自然寸法1000×750を確認。PCの導入・目次・本文画像・キャプションとスマートフォンの全ページ画像を確認し、文字・画像・CTA・関連記事の配置を確認した。テラコッタ色はcomputed styleで#be6d55。
- FAQはPCで1件、スマートフォンで4件をクリックして回答表示を確認。記事タブのコンソールerror/warnは0件。
- TOPの独立したPC/SP目視確認は今回未完了。年齢確認画面で停止後、ユーザーの表示確認済み・追加不要の指示を受けた。TOPのHTTP・登録・構造・DOM確認済みと混同しない。

#### 不適合1: サイトマップ更新日17件の未同期

- 公開・ローカルのサイトマップで、以下17件のlastmodが2026-11-03のまま。対象sourceの最終Git変更日は公開コミットe95093aによる2026-11-04。
- 対象: TOP、ブログ一覧、およびブログslug `autumn-winter-cuddle`、`beginner-job`、`candy_dominant_girls`、`course-time`、`dormitory`、`double-work`、`fun-conversation`、`girl-choice`、`maimai-profile`、`newcomer-veteran`、`private-waiting-room`、`retort-curry`、`short-time-job`、`tokyo-souvenir`、`waiting-room-facilities`。
- 根拠: CANDY_SEO_SPEC第11章はsource HTMLの最終Git変更日とlastmodの一致を要求する。CSS参照キーだけの変更についても、現行規則に除外はない。
- `candy-site-state.cmd check --target nightwork-style` は上記17件を `CHECK=FAIL sitemap_lastmod_or_structure` と報告した。サイト全体への別件修正を求める結果ではなく、今回の公開コミットが変更したsourceに対応する不一致である。

#### 不適合2: 生成管理書への対象記事未反映

- `management/specs/generated/` のページ台帳・SEO状態等にnightwork-styleの登録が存在しない。ページ台帳とSEO状態の生成基準は2026-11-03、コミット9882add7717a3bd3a0ceed1ed67586ea843a4cd7のまま。
- 根拠: CANDY_OPERATION_BASICS第6章はHP変更後・ステージ前の生成管理書同期と検証を要求する。
- 公開ページの存在・一覧登録と、管理用台帳の更新完了は別の状態。前進捗記録の「完了条件を満たした」はこの未同期を含むため成立していない。

#### 検査側の既存不具合: FAQ0件という誤判定

- target checkは `structure=COMPLETE images=OK list=1 sitemap=1 seo=ISSUE` を返した。
- `candy_site_state.py` の `visible_faq_item_count` は、H2が `FAQ|よくある(?:ご質問|.*疑問)` に一致する場合だけfaq-itemを数える。本記事の「ナイトワークについてよくある質問」を認識せず、本文0件・JSON-LD4件と判定する。
- 実際のDOM・HTMLはFAQ4件、構造化データも4件。ページのFAQ欠落ではなく検査側のFALSE_POSITIVEと判定した。本文の正しい見出しを検査の都合で変更する根拠にはしない。

#### Git状態と既存差分

- リポジトリは1件、rootはC:\Codex\FSG\Candy。ローカルブランチはmainのみ、HEADはbf4ccfec26f40468ed63812e9fce22b5f49216aa。originはhttps://github.com/makotonishikubo0418-cmd/candy.git。
- ライブmainも同SHAでahead/behindは0/0。ライブfeature/member-loyalty-mypageは4f790a89fa867d6bad0ab1883c31d2c18ab723feでローカル同名ブランチなし。管理専用ブランチはNOT_PRESENT。
- 監査開始前の作業フォルダには変更7件・未追跡3件が存在し、リポジトリ全体はクリーンではない。いずれも保持した。
- 変更7件: `.github/scripts/candy_ftp_deploy.py`、`.github/scripts/test_candy_ftp_deploy.py`、`management/specs/BLOG_CREATION.md`、`management/specs/CANDY_PAGE_GENERATION_GOVERNANCE.md`、`management/specs/CANDY_PRODUCTION_MIGRATION_MASTER.md`、`management/specs/CANDY_VERIFICATION_PLAN.md`、`management/template/BLOG_TEMPLATE.md`。
- 未追跡3件: `20261103_CREATE_dormitory-blog-cursor-full-workflow_20261103_6.md`、`20261103_PROBLEM_top-blog-frame-collapse_20261103_3.md`、`20261103_PROBLEM_top-blog-frame-collapse_20261103_4.md`（すべてmanagement/history/records配下）。

### 対応

- 読み取りによる監査と本進捗記録の追加のみ実施。HP、既存管理書、検査プログラム、Git index・commit・remote、本番は変更していない。
- `20261104_CREATE_nightwork-style-blog-article_20261104_2.md` のCompleted判定を、新たな監査証拠に基づき本記録で訂正する。公開・Actions成功の事実は維持し、未達の管理完了条件だけを再開する。

### 結果

記事の基本SEO・公開配線は確認した範囲で正常。管理書への全面適合は未達。サイトマップ17件と生成管理書の同期が必要であり、検査側のFAQ誤判定はページ不具合から分離して扱う。

Googleの実インデックス・検索順位・Search Console・Core Web Vitalsは未確認。全サイトのSEO監査、DB調査、原文の独立照合は対象外。

## 現在

- Remaining Work: サイトマップlastmod17件の同期、生成管理書への対象記事反映と整合検証、FAQ誤判定の検査側対応。GitHub・本番への修正反映は別途指定された操作範囲に従う。
- Next Action: 本監査結果を報告し、修正指示があった範囲について対応する。原文照合・追加表示確認はユーザー指示により残作業へ含めない。
