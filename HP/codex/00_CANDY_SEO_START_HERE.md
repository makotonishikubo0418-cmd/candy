# CANDY SEO Codex 管理入口

> **旧資料:** 現行正本ではありません。通常作業はroot `AGENTS.md` → `HP/AGENTS.md` → `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` の順で開始してください。固定パス・件数・手順は現在値として使用しません。

作成日: 2026-06-09
対象プロジェクト: CANDY SEO
リポジトリ: `H:\Data\01_FSG\candy`
HP本体: `H:\Data\01_FSG\candy\HP`
Codex管理用データ: `H:\Data\01_FSG\candy\HP\codex`

## 結論

リポジトリ全体の正本ルールは `AGENTS.md`、HP固有の補足ルールは `HP\AGENTS.md` とする。

このプロジェクトでCodexが作成・更新してよいHP管理資料は、原則として `HP\codex` 配下に置く。

HP本体の PHP / HTML / CSS / JavaScript / 画像 / `includefile` / `source` / `sitemap.xml` / `.htaccess` は、ユーザーから明確な実行指示が出るまで修正禁止。

## 現在の作業状態

| 項目 | 状態 |
|---|---|
| HP本体修正 | 未実施・禁止 |
| Codex管理資料作成 | 実施可 |
| FSG改修案の参照元 | `H:\Data\01_CTI\FSG企画 - 改修案_20260529.md`（このPCで存在確認済み、GitHub同期対象外） |
| CANDY既存サイト資料 | `HP\codex\docs` |
| CANDYエリアページ資料 | `HP\codex\area` |
| FSG改修準備資料 | `HP\codex\reform_20260529` |

## 最初に読む順番

1. `AGENTS.md`
2. `HP\AGENTS.md`
3. `HP\codex\reform_20260529\00_START_HERE.md`
4. `HP\codex\reform_20260529\01_FSG_SCOPE_SUMMARY.md`
5. `HP\codex\reform_20260529\02_SITE_CURRENT_MAP.md`
6. `HP\codex\reform_20260529\03_REFORM_TO_SITE_IMPACT_MAP.md`
7. `HP\codex\reform_20260529\04_PRE_EDIT_BACKLOG.md`
8. `HP\codex\reform_20260529\05_OPEN_ITEMS_AND_DECISIONS.md`

## 既存資料

| 資料 | 用途 |
|---|---|
| `HP\codex\docs\CANDY_MASTER_DOC_INDEX.md` | 現行管理資料の入口 |
| `HP\codex\docs\CANDY_OPERATION_BASICS.md` | 運用情報、変更禁止・要承認対象 |
| `HP\codex\docs\CANDY_HP_STRUCTURE_MAP.md` | HP全体構成 |
| `HP\codex\docs\CANDY_FIX_BACKLOG.md` | 未確認事項・差分バックログ |
| `HP\codex\area\AREA_PAGE_CREATION_WORKFLOW.md` | エリアページ作成手順 |

## 作業禁止線

ユーザーの明確な実行指示があるまで、以下は修正しない。

| 禁止対象 | 理由 |
|---|---|
| `HP\*.php` | 公開ルーティング・生成入口に影響 |
| `HP\source\*.html` | 公開ページの表示・SEOに影響 |
| `HP\includefile\*.php` | DB表示・テンプレート変換・全ページ生成に影響 |
| `HP\css\*.css` | 全体デザインに影響 |
| `HP\js\*.js` | UI・計測・動作に影響 |
| `HP\imgHtml` / `HP\imgCss` | 表示画像に影響 |
| `HP\sitemap.xml` | 公開SEOに影響 |
| `HP\.htaccess` | サーバー挙動に影響 |

## 今回の整理方針

FSG改修案はCTI全体・各種マイページ・AI受付・共通データ整理を含む上位仕様であり、CANDY SEOサイトの直接改修仕様ではない。

したがって、現時点では次の形で整理する。

1. FSG改修案のうちCANDYサイトに関係する範囲を分離する。
2. 現在のCANDYサイト構成と影響ファイルを対応付ける。
3. 実装前に確認すべき未決事項を明確化する。
4. HP本体を触る前に必要な調査バックログを作る。

