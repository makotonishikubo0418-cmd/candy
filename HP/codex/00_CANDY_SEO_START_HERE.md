# CANDY SEO Codex 管理入口

作成日: 2026-06-09
対象プロジェクト: CANDY SEO
HP本体: `H:\Data\01_CTI\candy_HP`
Codex管理用データ: `H:\Data\01_CTI\candy_HP\codex`

## 結論

このプロジェクトでCodexが作成・更新してよい管理資料は、原則として `H:\Data\01_CTI\candy_HP\codex` 配下に置く。

HP本体の PHP / HTML / CSS / JavaScript / 画像 / `includefile` / `source` / `sitemap.xml` / `.htaccess` は、ユーザーから明確な実行指示が出るまで修正禁止。

## 現在の作業状態

| 項目 | 状態 |
|---|---|
| HP本体修正 | 未実施・禁止 |
| Codex管理資料作成 | 実施可 |
| FSG改修案の最新候補 | `H:\Data\01_CTI\FSG企画 - 改修案_20260529.md` |
| CANDY既存サイト資料 | `H:\Data\01_CTI\candy_HP\codex\docs` |
| CANDYエリアページ資料 | `H:\Data\01_CTI\candy_HP\codex\area` |
| FSG改修準備資料 | `H:\Data\01_CTI\candy_HP\codex\reform_20260529` |

## 最初に読む順番

1. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\00_START_HERE.md`
2. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\01_FSG_SCOPE_SUMMARY.md`
3. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\02_SITE_CURRENT_MAP.md`
4. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\03_REFORM_TO_SITE_IMPACT_MAP.md`
5. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\04_PRE_EDIT_BACKLOG.md`
6. `H:\Data\01_CTI\candy_HP\codex\reform_20260529\05_OPEN_ITEMS_AND_DECISIONS.md`

## 既存資料

| 資料 | 用途 |
|---|---|
| `H:\Data\01_CTI\candy_HP\codex\docs\CODEX_SITE_OVERVIEW.md` | CANDYサイト全体概要 |
| `H:\Data\01_CTI\candy_HP\codex\docs\SITE_STRUCTURE.md` | サイト構造 |
| `H:\Data\01_CTI\candy_HP\codex\docs\PAGE_LIST.md` | ページ一覧 |
| `H:\Data\01_CTI\candy_HP\codex\docs\TECHNICAL_ANALYSIS.md` | 技術構成 |
| `H:\Data\01_CTI\candy_HP\codex\docs\UNKNOWN_AND_RISK_LIST.md` | 未確認事項・リスク |
| `H:\Data\01_CTI\candy_HP\codex\area\AREA_PAGE_CREATION_WORKFLOW.md` | エリアページ作成手順 |

## 作業禁止線

ユーザーの明確な実行指示があるまで、以下は修正しない。

| 禁止対象 | 理由 |
|---|---|
| `H:\Data\01_CTI\candy_HP\*.php` | 公開ルーティング・生成入口に影響 |
| `H:\Data\01_CTI\candy_HP\source\*.html` | 公開ページの表示・SEOに影響 |
| `H:\Data\01_CTI\candy_HP\includefile\*.php` | DB表示・テンプレート変換・全ページ生成に影響 |
| `H:\Data\01_CTI\candy_HP\css\*.css` | 全体デザインに影響 |
| `H:\Data\01_CTI\candy_HP\js\*.js` | UI・計測・動作に影響 |
| `H:\Data\01_CTI\candy_HP\imgHtml` / `imgCss` | 表示画像に影響 |
| `H:\Data\01_CTI\candy_HP\sitemap.xml` | 公開SEOに影響 |
| `H:\Data\01_CTI\candy_HP\.htaccess` | サーバー挙動に影響 |

## 今回の整理方針

FSG改修案はCTI全体・各種マイページ・AI受付・共通データ整理を含む上位仕様であり、CANDY SEOサイトの直接改修仕様ではない。

したがって、現時点では次の形で整理する。

1. FSG改修案のうちCANDYサイトに関係する範囲を分離する。
2. 現在のCANDYサイト構成と影響ファイルを対応付ける。
3. 実装前に確認すべき未決事項を明確化する。
4. HP本体を触る前に必要な調査バックログを作る。

