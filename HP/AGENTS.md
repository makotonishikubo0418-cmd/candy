# AGENTS.md

## 0. このファイルの目的

このファイルは、Codex が candy プロジェクトの `HP` 配下を扱うときの補足ルールです。

リポジトリ全体の正本は、リポジトリ直下の `AGENTS.md` です。作業前に正本を先に読み、このファイルは `HP` 配下に限定した追加ルールとして適用してください。両者が矛盾する場合は、Codex が優先順位を決めず、作業を停止してユーザーへ報告してください。

対象は Sugar ではありません。  
対象は `candy` / `CANDY SEO` / `鹿児島キャンディ公式ホームページ` です。

このファイルは詳細仕様書ではありません。  
Codex が勝手に判断、修正、削除、整理、断定をしないための管理ルールです。

Codex は、調査、整理、管理 `.md` 作成、修正、検証、報告の前に必ずこのファイルを確認してください。

---

## 1. 最重要前提

ユーザーは、この HP の構成や目的を理解しています。

このプロジェクトで必要なのは、ユーザーへの基礎説明ではありません。  
必要なのは、Codex が candy の実フォルダ、実ファイル、既存資料を読み、構成と仕様を正しく理解できる状態にすることです。

Codex は以下を禁止します。

- 確認せずに「理解しました」と言う
- 読んでいない資料を読んだことにする
- 未確認情報を確認済みとして扱う
- Sugar 由来の記述を candy の事実として扱う
- 一般論や抽象論で返す
- ユーザーが理解済みの前提を長く説明する
- 省エネで浅くまとめる
- 文章量で作業した風にする

必要な出力は、確認済み、未確認、判断が必要な点、次の作業、変更範囲です。

---

## 2. プロジェクト定義

確認済みの現在対象は以下です。

| 項目 | 内容 |
|---|---|
| プロジェクト | candy |
| 管理名 | CANDY SEO / candy_HP |
| 対象サイト | 鹿児島キャンディ公式ホームページ |
| 現在確認済みのリポジトリルート | `C:\Users\nishi\Desktop\data\candy` |
| 現在のHP作業ルート | `C:\Users\nishi\Desktop\data\candy\HP` |
| Codex 管理フォルダ | `C:\Users\nishi\Desktop\data\candy\HP\codex` |
| Codex 既存資料 | `C:\Users\nishi\Desktop\data\candy\HP\codex\docs` |
| サイト種別 | PHP テンプレート生成型サイト |
| 主な用途 | 店舗案内、女の子一覧、出勤情報、料金案内、動画、対応エリア、ホテル情報、問い合わせ導線 |

GitHubで同期する正本ルールと現行管理資料では、上記の現在確認済みパスまたはリポジトリ相対パスを使用してください。履歴・バックアップ資料に残る別環境の絶対パスは、現在の作業ルートとして扱わないでください。

---

## 3. 現在確認済みの構成

2026-07-10 時点で、現在の作業ルートから再確認した件数は以下です。

| 分類 | 件数 |
|---|---:|
| 全フォルダ | 37 |
| 全ファイル | 1679 |
| コード/設定ファイル | 328 |
| 非コードファイル | 1351 |
| 管理MD | 28 |
| ルート直下 PHP | 98 |
| source HTML | 89 |
| includefile PHP | 101 |
| includefile\dataset_*.php | 99 |
| css 配下ファイル | 14 |
| js 配下ファイル | 18 |
| imgHtml + imgCss 配下ファイル | 974 |
| Text_*_data 配下ファイル | 175 |
| log 配下ファイル | 74 |
| codex 配下ファイル | 30 |
| codex\docs 配下ファイル | 11 |

主要フォルダと詳細台帳は `codex\docs\CANDY_MASTER_DOC_INDEX.md` から辿ってください。

---

## 4. 基本構造の理解

この HP は、PHP と HTML が混在して見える構成です。  
基本的には、以下の関係で追ってください。

```text
ルート直下 PHP
  例: index.php
    ↓
includefile\dataset_base.php
    ↓
source\*.html
    ↓
includefile\dataset_*.php
    ↓
includefile\class.hpgcoder2.php
    ↓
HTML 出力
```

Codex は、1 ファイルだけを見て仕様を断定してはいけません。

### 4.1 通常の新規ページ生成における絶対ルール

`area`、`blog`、`hotel` の通常運用として新しい公開ページを生成する場合、Codex は必ずリポジトリ直下 `AGENTS.md` の「6.1 通常の新規ページ生成における絶対ルール」に従ってください。

- `Text_area_data` は `source/template_kagoshima-deliveryhealth-area.html` と組み合わせる
- `Text_blog_data` は `source/template_kagoshima-deliveryhealth-blog.html` と組み合わせる
- `Text_hotel_data` は `source/template_kagoshima-deliveryhealth-hotel.html` と組み合わせる
- 公開入口 PHP、`source` HTML、ページ別 dataset PHP、`dataset_base.php` の登録を必須の1セットとする
- HTML だけを生成して完了としてはいけない
- `create.php` は通常の Codex ページ生成では原則使用しない
- 通常生成前に `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` と対象カテゴリの生成仕様書を必ず確認する
- スタッフ・別Codexがareaページを分割制作するときは、`codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` と `codex/docs/CANDY_AREA_105_PAGE_QUEUE.md` も必ず確認する

この絶対ルールは通常の新規ページ生成に限定します。機能追加、不具合修正、既存機能変更、構造変更、リファクタなどの開発改修は別作業として扱い、このファイルの調査・変更・修正ルールに従ってください。

特に以下を分けて確認してください。

- 公開入口の PHP
- 表示テンプレートの `source\*.html`
- 差し込み処理の `dataset_*.php`
- 共通生成処理の `dataset_base.php`
- 置換処理の `class.hpgcoder2.php`
- CSS / JS / 画像 / Text データ
- DB 由来か、静的 HTML 由来か、置換トークン由来か

---

## 5. 作業開始前の必須確認

Codex は作業前に、このファイルを読んだ証拠を短く示してください。

最低限、以下を出してください。

```text
AGENTS.md check:
- 適用する見出し名またはルール
- 今回の作業種別
- 今回やること
- 今回やらないこと
```

「AGENTS.md を読みました」だけでは不足です。

---

## 6. 作業種別

作業前に、次のどれかに分類してください。

- Investigation only
- Specification organization
- Management document creation
- Existing document modification
- Defect cause investigation
- Defect fix
- Existing feature modification
- New feature implementation
- Database check
- Database change
- Production/server work
- Log check
- Verification
- Report creation
- Git operation

複数に該当する場合は分けて書いてください。

例:

- `Specification organization` + `Management document creation`
- `Investigation only`。ファイル変更なし
- `Existing document modification`。対象は `codex` 配下のみ
- `Defect fix`。修正案提示後、ユーザー承認が必要

---

## 7. 最初に読む資料

作業内容に応じて、以下を優先して確認してください。

| 優先 | 資料 | 用途 |
|---:|---|---|
| 1 | `AGENTS.md` | リポジトリ全体の正本ルール |
| 2 | `HP\AGENTS.md` | HP配下の補足ルール |
| 3 | `HP\codex\docs\CANDY_MASTER_DOC_INDEX.md` | 現行管理資料の入口 |
| 4 | `HP\codex\docs\CANDY_FULL_FILE_CODE_INVENTORY.md` | 全フォルダ/全ファイル台帳 |
| 5 | `HP\codex\docs\CANDY_CODE_FILE_STRUCTURE.md` | PHP/HTML/dataset/CSS/JS/設定構成 |
| 6 | `HP\codex\docs\CANDY_NON_CODE_ASSET_INVENTORY.md` | 非コード資産、ログ、DB、TXT/CSV |
| 7 | `HP\codex\docs\CANDY_FOLDER_ROLE_MAP.md` | 全フォルダ役割 |
| 8 | `HP\codex\docs\CANDY_PAGE_SPEC_INDEX.md` | ページ別役割 |
| 9 | `HP\codex\docs\CANDY_PAGE_CATEGORY_STRUCTURE.md` | カテゴリ別ページ構成 |
| 10 | `HP\codex\docs\CANDY_CODEX_BACKUP_REMARKS.md` | codex_backup備考、旧資料削除後の扱い |
| 11 | `HP\codex\docs\CANDY_EXISTING_DOCS_INVENTORY.md` | 旧資料削除後の棚卸記録 |
| 12 | `HP\codex\docs\CANDY_PHASE_RECHECK.md` | フェーズ別再確認 |

旧資料を参照する場合は、現行資料と実ファイルで再確認してください。旧資料だけで現行仕様と断定してはいけません。

---

## 8. STOP 条件

以下の場合、Codex は作業を止めて報告してください。

- `AGENTS.md` を読めない
- 適用ルールを確認できない
- 指示内容と AGENTS.md が矛盾する
- candy と Sugar など別プロジェクトの前提が混ざっている
- 作業範囲が不明で、修正、削除、DB 変更、本番作業が発生し得る
- 調査のみの依頼なのに修正が必要になった
- ファイル変更が必要だが、ユーザーの明示指示がない
- 削除、移動、リネーム、整理、リファクタが必要
- DB 変更、ログ削除、本番反映、サーバー操作が必要
- 秘密値、DB 接続情報、ログ本文、決済 hidden 値を転記しそうになった

Codex は、矛盾する指示の優先順位を自分で決めてはいけません。

---

## 9. 変更ルール

HP 本体は、ユーザーの明確な実行指示があるまで変更禁止です。

HP 本体に含まれる主な対象:

- ルート直下 `*.php`
- `source\*.html`
- `includefile\*.php`
- `css\*.css`
- `js\*.js`
- `imgHtml`
- `imgCss`
- `movie`
- `.htaccess`
- `.well-known`
- `sitemap.xml`
- `makeSitemap.php`
- `log`

Codex 管理資料は、ユーザーの指示がある場合に限り、`HP\codex` 配下へ作成または更新できます。

ただし、管理資料であっても以下は禁止です。

- 未確認情報を確認済みとして書く
- 秘密値を転記する
- ログ本文を転記する
- DB 接続情報を転記する
- 決済 hidden 値を転記する
- 既存資料を勝手に削除する
- 古い資料を確認なしに正本扱いする

---

## 10. 重要ファイル

以下は特に慎重に扱ってください。

| 対象 | 理由 | 方針 |
|---|---|---|
| `create.php` | 認証、ファイル作成、生成処理に関係する可能性 | 勝手に触らない |
| `includefile\dataset_base.php` | 全ページ生成の中心 | 変更は承認必須 |
| `includefile\class.hpgcoder2.php` | 置換エンジン | 変更は承認必須 |
| `includefile\dataset_*.php` | ページ別データ処理 | 影響範囲確認後のみ |
| `source\*.html` | 表示テンプレート | 置換トークンを壊さない |
| `css\default.css` | 共通 CSS | 影響範囲が広い |
| `js\common.js` | 共通 JS | 影響範囲が広い |
| `source\system.html` | 料金、外部決済導線の可能性 | hidden 値を転記しない |
| `.htaccess` | サーバー挙動 | 勝手に変更しない |
| `sitemap.xml` | SEO 公開情報 | 勝手に更新しない |
| `log` | ログ本文を含む | 調査時も転記禁止 |
| `.well-known` | 用途未確認 | 勝手に削除しない |

---

## 11. 秘密値・個人情報・ログの扱い

以下はチャット、Markdown、報告書、差分説明へ値そのものを転記してはいけません。

- 認証値
- パスワード
- API キー
- DB 接続情報
- DSN
- 決済 hidden 値
- 決済契約情報
- ログ本文
- 個人情報
- アクセスログ内の IP、ユーザー情報、操作履歴
- 外部サービスの管理画面情報

秘密値に近い情報を見つけた場合は、値を書かずに以下の形で報告してください。

```text
確認済み:
- 対象ファイル内に秘密値に近い情報を確認しました。
- 値そのものは転記していません。
- 変更、削除、共有にはユーザー確認が必要です。
```

---

## 12. 管理 `.md` 作成フェーズ

Codex が candy を理解するための管理資料は、以下の順で整備してください。

### Phase 0: AGENTS 正本化

目的:
- Sugar 由来の前提を排除する
- リポジトリ直下を全PC共通の正本ルールにする
- `HP` 配下の固有ルールを補足として分離する
- 今後の調査、整理、修正の判断基準を固定する

対象:
- `AGENTS.md`
- `HP\AGENTS.md`

### Phase 1: 既存資料の棚卸

目的:
- `codex/docs`
- `codex/area`
- `codex/reform_20260529`

を読み、使える資料、古い資料、未確認資料に分ける。

### Phase 2: HP 構成マップ

目的:
- ルート PHP
- `source`
- `includefile`
- CSS
- JS
- 画像
- Text データ
- ログ

の役割と関係を整理する。

作成候補:
- `HP\codex\docs\CANDY_HP_STRUCTURE_MAP.md`

### Phase 3: ページ生成仕様

目的:
- PHP 入口
- `dataset_base.php`
- `source\*.html`
- `dataset_*.php`
- 置換処理

の流れを整理する。

作成候補:
- `HP\codex\docs\CANDY_PAGE_GENERATION_SPEC.md`

### Phase 4: ファイル内仕様の分類

目的:
- 各ファイルを同じ粒度で読まない
- 入口 PHP、テンプレート HTML、dataset、共通 CSS、共通 JS、画像、Text データ、外部連携、フォーム系に分ける

作成候補:
- `HP\codex\docs\CANDY_FILE_SPEC_CLASSIFICATION.md`

### Phase 5: ページ別仕様

目的:
- 主要ページ
- エリアページ
- ブログページ
- ホテルページ
- 動画
- マイページ
- 問い合わせ

を分けて、表示元、関連ファイル、注意点を整理する。

作成候補:
- `HP\codex\docs\CANDY_PAGE_SPEC_INDEX.md`

### Phase 6: 変更禁止・注意ファイル整理

目的:
- 触ってはいけないファイル
- 触る前に承認が必要なファイル
- 秘密値を含む可能性があるファイル
- 影響範囲が広いファイル

を分ける。

作成候補:
- `HP\codex\docs\CANDY_CHANGE_GUARD.md`

### Phase 7: 検証計画

目的:
- PHP 実行
- DB 接続
- PC / SP 表示
- JS console
- 画像切れ
- リンク切れ
- noindex
- placeholder
- 外部 HTTP リンク

の確認計画を作る。

作成候補:
- `HP\codex\docs\CANDY_VERIFICATION_PLAN.md`

### Phase 8: 管理用入口

目的:
- Codex が最初に読む資料
- 各資料の用途
- 更新ルール
- 未確認事項
- 次作業

をまとめる。

作成候補:
- `HP\codex\docs\CANDY_MANAGEMENT_INDEX.md`

---

## 13. 調査ルール

Codex は、1 ファイルだけで仕様や原因を断定してはいけません。

確認結果は、以下に分けてください。

- 確認済み
- 未確認
- 推測
- 判断が必要な点

以下の判断は禁止です。

- PHP だけ見て表示仕様を断定する
- HTML だけ見て DB 由来の表示を断定する
- 管理資料だけ見て現ファイルの状態を断定する
- 既存資料の件数を再確認なしに最新件数として扱う
- `source` だけ見て公開ページの実挙動を断定する
- 実ブラウザ確認なしに表示確認済みと書く
- DB 確認なしに DB 保存や DB 表示を確認済みと書く
- ログ本文を読まずにログ内容を断定する
- ログ本文をチャットへ転記する

---

## 14. 修正ルール

修正が必要な場合、Codex は修正前に必ず以下を出してください。

- 結論
- 修正対象
- 修正理由
- 変更ファイル
- 変更しないファイル
- 影響範囲
- DB 影響
- 秘密値、ログ、決済情報への影響
- バックアップ対象
- 検証方法
- 未確認事項
- ユーザー判断が必要な点

ユーザー承認後に、最小範囲だけ修正してください。

禁止:

- ついで修正
- ついで整理
- ついで削除
- ついでリファクタ
- ファイル名変更
- デザイン変更
- 文言変更
- SEO 設定変更
- noindex 解除
- sitemap 更新
- ログ削除
- `.well-known` 削除
- 決済フォーム変更
- DB 変更
- 本番作業

---

## 15. DB・本番・ログ作業

DB 作業、サーバー作業、本番作業、ログ確認は通常のファイル調査より慎重に扱ってください。

DB 変更、データ更新、データ削除、テーブル変更は、ユーザーの明示承認なしに実行禁止です。

本番反映、サーバー設定変更、再起動、キャッシュ削除、ログ削除、権限変更は、ユーザーの明示承認なしに実行禁止です。

ログ確認とログ削除を混同してはいけません。

ログ本文を報告書やチャットへ転記してはいけません。

---

## 16. テスト・検証ルール

Codex は、実行していない確認を実行済みとして報告してはいけません。

以下は別物として扱ってください。

- 構文確認
- 静的確認
- コマンド成功
- PHP 実行確認
- DB 接続確認
- ブラウザ表示確認
- PC 表示確認
- SP 表示確認
- JS console 確認
- 画像表示確認
- リンク確認
- 外部サービス稼働確認

実ブラウザで見ていない場合は、表示確認済みと書かないでください。

PHP を実行していない場合は、PHP 実行結果確認済みと書かないでください。

DB を確認していない場合は、DB 確認済みと書かないでください。

---

## 17. 報告ルール

結論を最初に書いてください。

報告は短く、判断に必要な内容だけを書いてください。  
固定テンプレートを毎回すべて埋める必要はありません。

基本形:

```text
結論:
AGENTS.md check:
確認済み:
未確認:
実施内容:
変更ファイル:
変更していないファイル:
次にやること:
```

不要な見出しは出さないでください。

ユーザーへの確認が必要な場合は、以下を分けて書いてください。

```text
[Confirmation Request]
- 確認してほしいこと
- 判断点
```

ユーザーに操作やテストを依頼する場合は、以下を分けて書いてください。

```text
[Test Request]
- 操作
- 期待結果
```

---

## 18. 出力禁止表現

以下は禁止です。

- たぶん直りました
- おそらく問題ありません
- 一通り確認しました
- 多分これで大丈夫です
- 必要に応じて修正しました
- 影響は少ないと思います
- だいたい理解しました
- 確認した感じでは
- 見たところ問題なさそうです

使うべき表現:

- 確認済み
- 未確認
- 推測
- 変更なし
- 変更あり
- 実行できません
- 対象外です
- ユーザー確認が必要です
- 本番 URL 未確認です
- PHP 実行結果は未確認です
- DB 接続は未確認です
- ブラウザ表示は未確認です

---

## 19. Codex 作成・更新ファイルの管理

Codex が管理資料を作成または更新する場合、原則として `HP\codex` 配下に置いてください。

HP 本体の PHP、HTML、CSS、JS、画像、動画、ログ、`.htaccess`、`.well-known`、`sitemap.xml` は、管理資料置き場ではありません。

管理資料を作成または更新した場合は、完了報告で以下を明記してください。

- 作成または更新したファイル
- 目的
- 参照した資料
- 確認済み
- 未確認
- 次に読むべき資料

---

## 20. Phase 0 完了条件

Phase 0 は、以下を満たしたら完了です。

- リポジトリ直下の `AGENTS.md` が全PC共通の正本になっている
- `HP\AGENTS.md` がHP配下の補足ルールとして正本を参照している
- `HP\AGENTS.md` から Sugar 由来の前提が除外されている
- 対象が candy であることが明記されている
- 現在の作業ルートが明記されている
- PHP / HTML / dataset が混在する構造を前提にしている
- HP 本体を勝手に変更しないルールが明記されている
- `codex` 配下の管理資料を次フェーズで整理する方針が明記されている
- 確認済み、未確認、推測を分けるルールが明記されている
- 改修は指示された対象ファイルのみ変更する。1回の指示で触るファイル数に固定上限は設けず、同一目的・同一変更内容であれば100ファイル以上を対象とする場合がある。
- 多数のファイルを変更する場合も、変更目的、対象範囲、除外対象を事前に明示し、ユーザーが指示または承認した範囲だけを変更する。
- 変更前に git status、対象ブランチ、リモートを確認し、他PCの変更や無関係なローカル変更を上書きしない。
- 変更後は git diff --stat と変更内容の要約を報告し、未承認の追加変更へ範囲を広げない。
- CANDY_OPERATION_BASICS.md の変更禁止/要承認ファイル一覧を遵守する。
- Gitがあるため、ファイル単位の .before- コピー作成は不要とする。
- 認証値・DB値・ログ本文・hidden値は今後も報告・資料へ転記しない。

---

## 21. HP変更のKAGOYA自動本番反映

- `/public_html/group/candy/`は本番、`/public_html/group_test/candy/`は制作時のテスト版である。Codexは両環境を混同しない
- HP内の`group_test/candy`絶対参照は本番移行の最終切替対象とし、ユーザー承認なしに一括置換しない
- 段階移行・本番反映前に、`codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md`と`codex/docs/CANDY_PRODUCTION_MIGRATION_INVENTORY.csv`を必ず確認する
- 移行マスターのGate 0が完了するまで、自動反映対象ファイルを通常運用としてPushしない。例外は対象と本番影響を限定した明示承認済み検証だけとする
- 再取得した`HP_旧データ`は取得時点の本番スナップショットである。再取得後は旧件数・旧ハッシュ・旧結論を流用せず、必ず再集計する
- 段階移行中は本番の転送用`index.php`を維持し、workflowのpaths除外とデプロイスクリプトの保護対象によって最新`HP/index.php`を二重に除外する
- 最新`HP/index.php`の上書きは、ユーザーが明示承認した最終公開切替でのみ行う。上書きがシティヘブン転送終了と新サイト公開の切替スイッチになる
- 「全て」「100％」「漏れなく」と指示された場合、対象全件を機械列挙・集計し、未確認が1件でもあれば100％確認済みと報告しない
- チャット内の古い貼付ルールより、現在の実`AGENTS.md`と実ファイルを優先する。差異があれば作業前に報告する
- 初回移行の明示承認時は、転送用`index.php`を除くGit管理中の本番用ファイルを一度だけ全件反映する。管理資料、元データ、ログ、`.well-known`は除外し、サーバーファイルは削除しない
- 全件反映後もトップ転送を維持し、他のPHP・CSS・JS・画像は直接URLで確認可能な最新状態にする
- `main`へ自動反映対象の`HP`ファイルをPushすると、GitHub ActionsによりKAGOYA本番サーバーへ追加・更新ファイルが自動反映される
- HP変更をPushする前に、本番反映が発生することを明示し、Commit、Push、本番反映の明示許可を確認する
- Markdown、`codex`、`log`、`Text_area_data`、`Text_blog_data`、`Text_hotel_data`、`.well-known`、`AGENTS.md`は自動反映対象外とする
- 削除・リネームは自動反映せず、workflowを停止して手動承認を求める
- 本番サーバー上のファイルを直接編集しない
- FTP Secretsをコード、ログ、報告へ出さない
- FTPテストworkflowは診断専用で、手動実行だけとする
- KAGOYAのFTPアクセスを「制限」に戻すと、GitHub Actionsから接続できなくなる
