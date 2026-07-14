# CANDY 管理資料・作業ルーター

## 1. このファイルの役割

このファイルは、作業内容から「今回読む正本資料」を選ぶための目次である。全資料を毎回読むための一覧ではない。

開始順:

1. root `AGENTS.md`
2. HP 作業なら `HP/AGENTS.md`
3. このルーター
4. 下表で今回指定された資料
5. 対象の実ファイル、Git、必要なら本番

## 2. 事実の優先順位

現在値は次の順で判断する。

1. 今回取得した実ファイル、Git、GitHub、本番サーバー、HTTP の証拠
2. 現行仕様の正本資料
3. 日付付きの調査記録
4. 過去資料、backup、古い件数

資料内のファイル件数、行数、URL 結果、Git 件数はスナップショットであり、現在値が必要なら再集計する。同じルールを複数資料に複製せず、正本へリンクする。

## 3. 作業別ルーター

| 作業 | 必読 | 必要な場合だけ追加 |
|---|---|---|
| 今日の経緯、事故、再発防止 | `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` | `CANDY_PRODUCTION_MIGRATION_MASTER.md`、`CANDY_VERIFICATION_PLAN.md` |
| 通常の新規ページ生成（area以外） | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、対象カテゴリ spec | `CANDY_PAGE_SPEC_INDEX.md`、構造資料 |
| 通常のarea 1ページ作成→公開→URL報告 | `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` | 未知例外時だけ `CANDY_AREA_PAGE_GENERATION_SPEC.md`、キュー保守時だけ `CANDY_AREA_105_PAGE_QUEUE.md`、画像問題時だけ `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| blog ページ制作 | `CANDY_BLOG_PAGE_GENERATION_SPEC.md` | `CANDY_PAGE_SPEC_INDEX.md`、構造資料 |
| hotel ページ制作 | `CANDY_HOTEL_PAGE_GENERATION_SPEC.md` | `CANDY_PAGE_SPEC_INDEX.md`、構造資料 |
| 画像の選定、使用済み判定 | 対象カテゴリ spec | area は `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| 既存ページの軽微修正 | `CANDY_OPERATION_BASICS.md`、対象カテゴリ spec | `CANDY_FIX_BACKLOG.md` |
| 共通 PHP、dataset、generator 改修 | `CANDY_CODE_FILE_STRUCTURE.md`、`CANDY_HP_STRUCTURE_MAP.md` | `CANDY_FULL_FILE_CODE_INVENTORY.md` |
| フォルダ・資産の役割確認 | `CANDY_FOLDER_ROLE_MAP.md` | `CANDY_NON_CODE_ASSET_INVENTORY.md` |
| 全ファイル、リンク、画像、外部URL検証 | `CANDY_VERIFICATION_PLAN.md` | inventories、対象 spec |
| 本番移行、FTP、Actions | `CANDY_PRODUCTION_MIGRATION_MASTER.md` | workflow、deploy script、`CANDY_VERIFICATION_PLAN.md` |
| 未修正問題の確認 | `CANDY_FIX_BACKLOG.md` | 対象 spec、検証計画 |
| 資料自体の棚卸し | このファイル、`CANDY_EXISTING_DOCS_INVENTORY.md` | `CANDY_PHASE_RECHECK.md` |

## 4. 正本の担当範囲

| 正本 | 担当する内容 | 置かない内容 |
|---|---|---|
| root `AGENTS.md` | 全体の絶対ルール、Git、権限、STOP、報告 | 個別ページ仕様、変動件数 |
| `HP/AGENTS.md` | HP 固有の絶対ルール、生成一式、本番 index、危険ファイル | 詳細なカテゴリ例外、調査履歴 |
| `CANDY_PAGE_GENERATION_GOVERNANCE.md` | 通常ページ生成の共通工程と完了条件 | area/blog/hotel 固有例外 |
| category spec | カテゴリ固有の入力、変換、例外、完成判定 | Git・本番の共通運用 |
| `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` | area の実制作手順、スタッフ引継ぎ | blog/hotel 仕様 |
| `CANDY_VERIFICATION_PLAN.md` | 母集団、全件検証、リンク監査の方法と結果 | ページ制作本文 |
| `CANDY_PRODUCTION_MIGRATION_MASTER.md` | 本番・テスト、index 転送、Actions/FTP、移行状態 | 通常ページの文章規則 |
| `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` | 2026-07-13 の経緯、失敗、影響、改善判断 | 将来の現在値 |

## 5. ページ制作資料

### 共通

- `CANDY_PAGE_GENERATION_GOVERNANCE.md`: 通常ページ生成の全体ルール
- `CANDY_PAGE_SPEC_INDEX.md`: ページ別仕様の入口
- `CANDY_PAGE_CATEGORY_STRUCTURE.md`: ページカテゴリ構成

### area

- `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`: スタッフ・Codex 共通の制作手順
- `CANDY_AREA_PAGE_GENERATION_SPEC.md`: area の生成仕様と例外
- `CANDY_AREA_105_PAGE_QUEUE.md`: 105ページの制作キュー
- `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`: 準備画像、使用済み判定、画像なし時の扱い

### blog

- `CANDY_BLOG_PAGE_GENERATION_SPEC.md`: blog の複雑な生成規則と例外

### hotel

- `CANDY_HOTEL_PAGE_GENERATION_SPEC.md`: 情報量による表示差を含む hotel 生成仕様

## 6. 構造・inventory 資料

- `CANDY_HP_STRUCTURE_MAP.md`: HP 全体と生成経路
- `CANDY_FOLDER_ROLE_MAP.md`: フォルダごとの役割
- `CANDY_CODE_FILE_STRUCTURE.md`: コード構造と依存関係
- `CANDY_FULL_FILE_CODE_INVENTORY.md`: コードファイル inventory
- `CANDY_NON_CODE_ASSET_INVENTORY.md`: 非コード資産 inventory
- `CANDY_EXISTING_DOCS_INVENTORY.md`: 管理資料 inventory
- `CANDY_PRODUCTION_MIGRATION_INVENTORY.csv`: 本番移行 inventory の記録

inventory は作成時点の記録である。削除や本番判断の前に現在の実ファイルと照合する。

## 7. 運用・検証・本番資料

- `CANDY_OPERATION_BASICS.md`: 通常運用の短い手順
- `CANDY_VERIFICATION_PLAN.md`: 全件、内部リンク、画像、外部 URL、本番 HTTP の検証
- `CANDY_FIX_BACKLOG.md`: 確認済み問題と未修正項目
- `CANDY_PRODUCTION_MIGRATION_MASTER.md`: 本番移行の正本
- `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`: 2026-07-13 の全体記録と再発防止

## 8. 補助・過去確認資料

- `CANDY_PHASE_RECHECK.md`: 過去フェーズの再確認記録
- `CANDY_CODEX_BACKUP_REMARKS.md`: backup 資料に関する注意

これらは現在仕様の正本ではない。`codex_backup` も同様に、現在の事実を断定する根拠として単独使用しない。

## 9. Markdown全件分類

2026-07-14に、現在のGitルート配下を再帰列挙した。対象はプロジェクト内の `.md` 全68件、除外はGit管理内部データ `.git/**` だけである。

| 区分 | 件数 | 扱い |
|---|---:|---|
| 最上位・HPルール | 2 | 現行正本 |
| `HP/codex/docs` 現行資料 | 23 | このルーターから必要な資料だけを選ぶ |
| 制作記録 | 1 | 正本を上書きしない運用ログ |
| HP内の旧資料 | 16 | 参照専用、現行判断には使用しない |
| バックアップ | 26 | 保存専用、通常作業のルールにしない |
| 未分類 | 0 | 発生した場合は分類までSTOP |
| 合計 | 68 | 全件分類済み |

### 9.1 最上位・HPルール（2件）

```text
AGENTS.md
HP/AGENTS.md
```

### 9.2 現行資料（23件）

```text
HP/codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md
HP/codex/docs/CANDY_AREA_105_PAGE_QUEUE.md
HP/codex/docs/CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md
HP/codex/docs/CANDY_AREA_PAGE_GENERATION_SPEC.md
HP/codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md
HP/codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md
HP/codex/docs/CANDY_CODE_FILE_STRUCTURE.md
HP/codex/docs/CANDY_CODEX_BACKUP_REMARKS.md
HP/codex/docs/CANDY_EXISTING_DOCS_INVENTORY.md
HP/codex/docs/CANDY_FIX_BACKLOG.md
HP/codex/docs/CANDY_FOLDER_ROLE_MAP.md
HP/codex/docs/CANDY_FULL_FILE_CODE_INVENTORY.md
HP/codex/docs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md
HP/codex/docs/CANDY_HP_STRUCTURE_MAP.md
HP/codex/docs/CANDY_MASTER_DOC_INDEX.md
HP/codex/docs/CANDY_NON_CODE_ASSET_INVENTORY.md
HP/codex/docs/CANDY_OPERATION_BASICS.md
HP/codex/docs/CANDY_PAGE_CATEGORY_STRUCTURE.md
HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md
HP/codex/docs/CANDY_PAGE_SPEC_INDEX.md
HP/codex/docs/CANDY_PHASE_RECHECK.md
HP/codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md
HP/codex/docs/CANDY_VERIFICATION_PLAN.md
```

### 9.3 制作記録（1件）

```text
ページ作成用.md
```

### 9.4 HP内の旧資料（16件）

```text
HP/codex/00_CANDY_SEO_START_HERE.md
HP/codex/area/AREA_IMAGE_CHECK.md
HP/codex/area/AREA_LINK_CHECK.md
HP/codex/area/AREA_NEXT_ACTIONS.md
HP/codex/area/AREA_PAGE_CREATION_WORKFLOW.md
HP/codex/area/AREA_PAGE_MASTER.md
HP/codex/area/AREA_PLACEHOLDER_CHECK.md
HP/codex/area/AREA_SEO_CHECK.md
HP/codex/area/AREA_SERIES_OVERVIEW.md
HP/codex/area/AREA_TEMPLATE_ANALYSIS.md
HP/codex/reform_20260529/00_START_HERE.md
HP/codex/reform_20260529/01_FSG_SCOPE_SUMMARY.md
HP/codex/reform_20260529/02_SITE_CURRENT_MAP.md
HP/codex/reform_20260529/03_REFORM_TO_SITE_IMPACT_MAP.md
HP/codex/reform_20260529/04_PRE_EDIT_BACKLOG.md
HP/codex/reform_20260529/05_OPEN_ITEMS_AND_DECISIONS.md
```

これらは調査履歴として保持する。固定パス、当時の件数、当時の制作手順を現在値として使用しない。

### 9.5 バックアップ（26件）

```text
.git-backups/github-candy-sync/HP/AGENTS.md
codex_backup/00_CANDY_SEO_START_HERE.md
codex_backup/area/AREA_IMAGE_CHECK.md
codex_backup/area/AREA_LINK_CHECK.md
codex_backup/area/AREA_NEXT_ACTIONS.md
codex_backup/area/AREA_PAGE_CREATION_WORKFLOW.md
codex_backup/area/AREA_PAGE_MASTER.md
codex_backup/area/AREA_PLACEHOLDER_CHECK.md
codex_backup/area/AREA_SEO_CHECK.md
codex_backup/area/AREA_SERIES_OVERVIEW.md
codex_backup/area/AREA_TEMPLATE_ANALYSIS.md
codex_backup/docs/AGENTS.md
codex_backup/docs/CODEX_MANAGEMENT_GUIDE.md
codex_backup/docs/CODEX_SITE_OVERVIEW.md
codex_backup/docs/CONTENT_ANALYSIS.md
codex_backup/docs/PAGE_LIST.md
codex_backup/docs/SITE_STRUCTURE.md
codex_backup/docs/TECHNICAL_ANALYSIS.md
codex_backup/docs/UI_DESIGN_ANALYSIS.md
codex_backup/docs/UNKNOWN_AND_RISK_LIST.md
codex_backup/reform_20260529/00_START_HERE.md
codex_backup/reform_20260529/01_FSG_SCOPE_SUMMARY.md
codex_backup/reform_20260529/02_SITE_CURRENT_MAP.md
codex_backup/reform_20260529/03_REFORM_TO_SITE_IMPACT_MAP.md
codex_backup/reform_20260529/04_PRE_EDIT_BACKLOG.md
codex_backup/reform_20260529/05_OPEN_ITEMS_AND_DECISIONS.md
```

バックアップ内容は履歴保存のため書き換えない。バックアップ内の `AGENTS.md` は通常作業に適用しない。

## 10. 資料追加・更新ルール

1. 新資料を作る前に、同じ担当の正本がないか確認する。
2. 既存正本に追記できる場合は新しい重複資料を作らない。
3. 新資料を作ったら、このルーターの作業表と担当範囲へ追加する。
4. 変化する件数を AGENTS.md へ固定しない。
5. 日付付き調査結果には「現在値ではない」ことを明記する。
6. 関連リンクが実在するか検査する。
7. 認証情報、ログ本文、個人情報を資料へ書かない。
8. 古い記述と矛盾する場合は、黙って併存させず正本・日付・優先関係を明記する。
