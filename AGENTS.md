# AGENTS.md

## 0. このファイルの位置づけ

このファイルは、`candy` リポジトリ全体で共通使用する正本ルールです。

- GitHub リポジトリ: `makotonishikubo0418-cmd/candy`
- 基準ブランチ: `main`
- 現在確認済みのローカル作業ルート: `H:\Data\01_FSG\candy`
- 主対象: `H:\Data\01_FSG\candy\HP`
- 対象サイト: 鹿児島キャンディ公式ホームページ

複数の PC で作業するときは、このファイル自体も通常の管理対象ファイルとして GitHub で同期してください。

サブフォルダに別の `AGENTS.md` がある場合は、その配下に限定した追加ルールとして確認してください。この正本と内容が矛盾する場合は、Codex が優先順位を決めず、作業を停止してユーザーへ報告してください。

過去資料や別環境のパスは、現在の正本情報として扱ってはいけません。現在の事実は、実ファイル、現在の Git 状態、現行管理資料で再確認してください。

---

## 1. 最重要原則

Codex は、調査、作成、修正、削除、整理、検証、Git 操作、完了報告の前に、必ずこのファイルを確認してください。

禁止事項:

- 確認せずに理解済み、確認済み、完了済みと報告する
- 未確認情報を確認済みの事実として扱う
- 実行していない確認を実行済みと報告する
- ユーザーが指定した範囲を勝手に拡張する
- ついで修正、ついで整理、ついで削除、ついでリファクタを行う
- 既存ファイルを承認なしに削除、移動、リネームする
- 秘密値、個人情報、ログ本文、DB 接続値、決済情報を転記する
- コンフリクトを内容確認なしに自動解消する
- 未取得のリモート変更を無視して上書きする
- ユーザー確認なしに Commit、Push、本番反映、DB 変更を行う

報告では、必要に応じて以下を分けてください。

- 確認済み
- 未確認
- 推測
- ユーザー判断が必要な点
- 実施内容
- 変更範囲
- 次にやること

---

## 2. 作業開始前の必須確認

作業前に、最低限次の証拠を短く示してください。

```text
AGENTS.md check:
- 適用する見出し名またはルール
- 今回の作業種別
- 今回やること
- 今回やらないこと
```

「AGENTS.md を読みました」だけでは作業開始の証拠になりません。

作業種別は、必要に応じて次から選んでください。

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

---

## 3. 複数 PC の Git 運用

### 3.1 作業開始前

各 PC は、作業開始前に必ず現在のリポジトリ、ブランチ、作業ツリーを確認してください。

確認例:

```powershell
git remote -v
git branch --show-current
git status --short --branch
```

作業ツリーが clean で、対象ブランチが `main`、リモートが指定された GitHub リポジトリであることを確認してから、次を行います。

```powershell
git fetch origin
git pull --ff-only origin main
```

ルール:

- 作業ツリーに未保存変更がある場合は、勝手に Pull しない
- `main` 以外にいる場合は、勝手に切り替えず対象ブランチを報告する
- `origin/main` に未取得変更がある場合は、変更内容を取得してから作業する
- fast-forward できない場合は、merge や rebase を勝手に行わず停止して報告する
- 他 PC の変更を消す可能性がある操作を行わない
- `git reset --hard`、force push、履歴改変をユーザー承認なしに行わない

GitHub Desktop を使う場合も、作業開始前に `Fetch origin`、続いて必要な `Pull origin` を実行し、未取得変更がないことを確認してください。

### 3.2 作業中

- 変更前に `git status --short --branch` を確認する
- 変更対象を明示し、指示されたファイルだけを変更する
- 1 回の指示で変更するファイル数に固定上限は設けない。この企画では、同一目的・同一変更内容であれば 100 ファイル以上を対象とする場合がある
- 多数のファイルを変更する場合も、変更目的、対象範囲、除外対象を事前に明示し、ユーザーが指示または承認した範囲だけを変更する
- 他 PC の変更やユーザーの既存変更を上書きしない
- コンフリクトが発生した場合は、勝手に解消せず、対象ファイルと競合内容を報告する

### 3.3 作業終了後

変更後は、最低限次を確認してください。

```powershell
git status --short
git diff --stat
git diff --check
```

その後の運用:

1. 変更ファイルと差分要約をユーザーへ報告する
2. ユーザーが内容を確認する
3. 明示指示がある場合だけ Commit する
4. Push 前に再度 Fetch し、リモート更新の有無を確認する
5. 明示指示がある場合だけ `origin/main` へ Push する
6. 別 PC は作業開始前に Fetch と Pull を行い、変更を取得する

`AGENTS.md` を変更した場合も、同じ手順で Commit と Push を行ってください。

---

## 4. 現在確認済みのリポジトリ構成

2026-07-12 に `H:\Data\01_FSG\candy` の実フォルダと Git 状態から確認した構成です。

```text
candy
├─ AGENTS.md                  リポジトリ全体の正本ルール
├─ HP                         ホームページ本体と現行 Codex 管理資料
├─ codex_backup               過去資料のバックアップ。現行仕様の正本ではない
├─ .well-known                用途・公開影響の確認なしに変更しない
├─ .htaccess                  公開挙動へ影響するため要承認
└─ .git                       Git 管理情報
```

`HP` 直下で確認済みの主な構成:

```text
HP
├─ AGENTS.md
├─ codex
│  └─ docs
├─ includefile
├─ source
├─ css
├─ js
├─ imgHtml
├─ imgCss
├─ movie
├─ Text_area_data
├─ Text_blog_data
├─ Text_hotel_data
├─ log
├─ .well-known
├─ .htaccess
├─ sitemap.xml
├─ makeSitemap.php
└─ ルート直下 PHP
```

実ファイルから確認した件数:

| 対象 | 件数 |
|---|---:|
| `HP` 直下 PHP | 98 |
| `HP/source` 直下 HTML | 89 |
| `HP/includefile` 直下 PHP | 101 |
| `HP/includefile/dataset_*.php` | 99 |
| `HP/css` 直下ファイル | 14 |
| `HP/js` 直下ファイル | 18 |

件数は将来変わる可能性があります。最新件数が必要な場合は、実ファイルを再集計してください。

---

## 5. 現行資料の読み順

HP の調査・修正では、次の順を基本にしてください。

1. `AGENTS.md`
2. `HP/AGENTS.md`
3. `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md`
4. `HP/codex/docs/CANDY_OPERATION_BASICS.md`
5. `HP/codex/docs/CANDY_HP_STRUCTURE_MAP.md`
6. `HP/codex/docs/CANDY_FOLDER_ROLE_MAP.md`
7. `HP/codex/docs/CANDY_FULL_FILE_CODE_INVENTORY.md`
8. `HP/codex/docs/CANDY_CODE_FILE_STRUCTURE.md`
9. `HP/codex/docs/CANDY_NON_CODE_ASSET_INVENTORY.md`
10. 作業対象に対応するページ別・カテゴリ別資料

`codex_backup` は過去資料です。現在の仕様、パス、ファイル件数、作業フェーズを、バックアップ資料だけから断定してはいけません。

---

## 6. サイト生成構造

確認済みの基本構造:

```text
HP 直下の公開入口 PHP
  ↓
HP/includefile/dataset_base.php
  ↓
HP/source/同名.html
  ↓
HP/includefile/dataset_*.php
  ↓
HP/includefile/class.hpgcoder2.php
  ↓
HTML 出力
```

ページによって対応関係や処理が異なるため、1 ファイルだけを見て仕様を断定してはいけません。

### 6.1 通常の新規ページ生成における絶対ルール

この項目は、`area`、`blog`、`hotel` の通常運用として新しい公開ページを生成する場合に必ず適用します。機能追加、不具合修正、構造変更、リファクタなどの開発改修には適用せず、該当する調査・修正ルールに従ってください。

Codex は、通常の新規ページを単独の HTML ファイルだけで作成してはいけません。必ず次の対応関係と手順を守ってください。

| 種別 | 元データ | HTML テンプレート |
|---|---|---|
| area | `HP/Text_area_data` | `HP/source/template_kagoshima-deliveryhealth-area.html` |
| blog | `HP/Text_blog_data` | `HP/source/template_kagoshima-deliveryhealth-blog.html` |
| hotel | `HP/Text_hotel_data` | `HP/source/template_kagoshima-deliveryhealth-hotel.html` |

通常の新規ページ生成では、次を1セットとして扱います。

1. 対象カテゴリの元データと `template_*.html` を対応させる
2. `HP/source/<ページ名>.html` を生成する
3. `HP/<ページ名>.php` を生成する
4. `HP/includefile/dataset_<ページ名>.php` を生成する
5. `HP/includefile/dataset_base.php` に、HTML 名と dataset PHP の対応を登録する
6. `dataset_base.php` の HTML リンクから PHP リンクへの変換対象へ登録する
7. 既存の同カテゴリ完成ページと比較し、ファイル名、slug、内部リンク、画像参照、置換箇所を確認する
8. placeholder、未置換文字列、旧ページ名、誤ったカテゴリ名が残っていないことを確認する
9. PHP 構文、差分、対象ファイル一式を確認してから完了報告する

禁止事項:

- `source/<ページ名>.html` だけを作って完了とする
- 公開入口 PHP または dataset PHP を欠いた状態で完了とする
- `dataset_base.php` への登録を省略する
- area 用元データを blog 用テンプレートへ入れるなど、異なるカテゴリを組み合わせる
- `create.php` を通常の Codex ページ生成手段として使用する
- 既存ファイルを確認なく上書きする

`create.php` は既存の Web ページ生成機能として残しますが、今後の通常運用では原則使用しません。Codex が上記の一式を管理します。`dataset_base.php` は影響範囲が広いため、通常の新規ページ生成でも、生成対象と登録内容を事前に明示し、ユーザーの実行指示または承認後に最小差分で変更してください。

カテゴリ別の詳細な基本仕様、例外処理、現状不整合、検証手順は、対象に応じて次を必ず確認してください。

- 共通: `HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md`
- area: `HP/codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md`
- area画像: `HP/codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
- areaスタッフ制作手順: `HP/codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`
- area未作成105件の進捗台帳: `HP/codex/docs/CANDY_AREA_105_PAGE_QUEUE.md`
- blog: `HP/codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md`
- hotel: `HP/codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md`

調査時は、必要に応じて以下を分けて確認してください。

- 公開入口 PHP
- `source/*.html`
- `includefile/dataset_*.php`
- `includefile/dataset_base.php`
- `includefile/class.hpgcoder2.php`
- CSS、JavaScript、画像、動画、Text データ
- DB 由来、静的 HTML 由来、置換トークン由来の別
- 外部連携の有無

---

## 7. 重要ファイルと変更ルール

### 7.1 ユーザー承認なしに変更禁止

- `HP/create.php`
- `HP/log` 配下
- `HP/.well-known` 配下
- ルートの `.well-known` 配下
- `HP/movie` 配下の削除・置換
- 認証値を含む箇所
- DB 接続情報を含む箇所
- 決済 hidden 値を含む箇所
- 外部決済フォーム
- 本番設定、サーバー設定
- noindex 設定
- ログ本文

### 7.2 変更前に影響範囲確認と承認が必要

- `HP/includefile/dataset_base.php`
- `HP/includefile/class.hpgcoder2.php`
- `HP/includefile/funcs.php`
- `HP/includefile/dataset_*.php`
- `HP/source/system.html`
- `HP/css/default.css`
- `HP/js/common.js`
- `HP/.htaccess`
- ルートの `.htaccess`
- `HP/makeSitemap.php`
- `HP/sitemap.xml`

### 7.3 通常の変更でも必要な確認

- `HP/source/*.html`: 置換トークン、関連 dataset、表示範囲を確認する
- `HP/css/*.css`: 共通利用と PC/SP への影響を確認する
- `HP/js/*.js`: 参照ページ、既存ライブラリ、console 影響を確認する
- `HP/imgHtml`、`HP/imgCss`: 参照元と実ファイル名を確認する
- `HP/codex`: 現行事実と未確認事項を分け、秘密情報を記載しない

---

## 8. 秘密値・個人情報・ログの扱い

次の値そのものを、チャット、Markdown、報告書、差分説明、Commit メッセージへ転記してはいけません。

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

秘密情報に近い内容を確認した場合は、値を書かずに所在と対応要否だけを報告してください。

```text
確認済み:
- 対象ファイル内に秘密値に近い情報を確認しました。
- 値そのものは転記していません。
- 変更、削除、共有にはユーザー確認が必要です。
```

---

## 9. STOP 条件

次の場合は、作業を停止してユーザーへ報告してください。

- この `AGENTS.md` を読めない
- 適用ルールを確認できない
- ユーザー指示と AGENTS.md が矛盾する
- 作業対象、変更範囲、対象ブランチが不明
- 作業ツリーが clean ではない状態で、新しい変更を始める必要がある
- ローカルとリモートの差異を確認できない
- Pull が fast-forward で完了しない
- コンフリクトが発生した
- 調査のみの依頼で変更が必要になった
- 指示外ファイルの変更、削除、移動、リネームが必要になった
- DB 変更、本番反映、サーバー操作、ログ削除が必要になった
- 秘密値、個人情報、ログ本文、決済情報を転記しそうになった

矛盾する指示の優先順位を Codex 単独で決めてはいけません。

---

## 10. 修正前後の手順

修正前に、必要に応じて以下を明示してください。

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

ユーザー承認後、最小範囲だけ変更してください。

変更後は、実行した確認だけを報告してください。次は別の確認として扱います。

- 構文確認
- 静的確認
- コマンド成功
- PHP 実行確認
- DB 接続確認
- ブラウザ表示確認
- PC 表示確認
- SP 表示確認
- JavaScript console 確認
- 画像表示確認
- リンク確認
- 外部サービス稼働確認

実ブラウザで確認していない場合は、表示確認済みと書いてはいけません。DB を確認していない場合は、DB 確認済みと書いてはいけません。

---

## 11. 現在の未確認事項

次は、現時点で確認済みとして扱ってはいけません。

- 本番 URL と公開方式
- 本番サーバーの PHP バージョンと Web サーバー種別
- 本番 DB の実体、テーブル構成、接続可否
- PHP 実行結果
- 実ブラウザでの全ページ表示
- スマホ実機表示
- 外部決済の契約・稼働状況
- 外部サービスの管理画面設定
- `.well-known` の用途
- `log` の保存ポリシー
- noindex の運用方針
- placeholder ページの公開方針
- 営業時間、料金、電話番号、届出番号の現行正誤

未確認事項を確認済みへ変更する場合は、確認方法と確認結果を記録してください。

---

## 12. 報告ルール

結論を最初に書き、判断に必要な内容だけを報告してください。

基本形:

```text
結論:
AGENTS.md check:
確認済み:
未確認:
実施内容:
変更ファイル:
変更していないファイル:
リスク:
次にやること:
```

不要な見出しは省略できます。

禁止表現:

- たぶん直りました
- おそらく問題ありません
- 一通り確認しました
- 多分これで大丈夫です
- 必要に応じて修正しました
- 影響は少ないと思います
- だいたい理解しました

使用する表現:

- 確認済み
- 未確認
- 推測
- 変更なし
- 変更あり
- 実行できません
- 対象外です
- ユーザー確認が必要です
- Push は未実施です
- 本番 URL は未確認です
- PHP 実行結果は未確認です
- DB 接続は未確認です
- ブラウザ表示は未確認です
