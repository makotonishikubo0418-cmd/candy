# CANDY 管理資料ルーター

## 1. 用途

通常area制作では読まない。未知例外、既存修正、全件検証、本番基盤変更など、標準経路外の作業で必要な正本を選ぶ。

優先順位:

1. root `AGENTS.md`
2. `HP/AGENTS.md`
3. この表が示す正本
4. 対象実ファイルと今回の検証結果

件数、Git状態、URL結果は資料から流用せず再確認する。

## 2. 作業別正本

| 作業 | 読む資料 |
|---|---|
| 通常area制作・公開 | `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| 通常hotel制作・公開 | `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| 通常blog制作・公開 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| area未知例外 | `CANDY_AREA_PAGE_GENERATION_SPEC.md`、必要時 `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| blog未知例外 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| hotel未知例外 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_HOTEL_PAGE_GENERATION_SPEC.md` |
| area／hotel／blog以外のページ調査・修正 | `CANDY_OTHER_PAGES_MANAGEMENT.md`、`CANDY_OPERATION_BASICS.md` |
| 既存ページ修正 | `CANDY_OPERATION_BASICS.md`、対象カテゴリspec |
| 構造調査 | `CANDY_HP_STRUCTURE_MAP.md`、`CANDY_CODE_FILE_STRUCTURE.md` |
| 全件・リンク・画像検証 | `CANDY_VERIFICATION_PLAN.md` |
| 本番・Actions・FTP基盤 | `CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| 未対応課題 | `CANDY_FIX_BACKLOG.md` |
| 事故経緯 | `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` |

一覧・inventory資料は調査対象の特定にだけ使い、現在値は実ファイルから取得する。

## 3. 通常作業から除外

- `ページ作成用.md`: 更新停止した旧制作記録
- `HP/codex/00_CANDY_SEO_START_HERE.md`
- `HP/codex/area/`
- `HP/codex/reform_20260529/`
- `codex_backup/`
- `.git-backups/`

これらは現行仕様の根拠にせず、通常検索・通読・更新の対象にしない。

## 4. 資料更新

- 同じルールを複数資料へ書かない。
- 新規資料を作る前に既存正本へ統合できるか確認する。
- 通常ページごとの結果を新しい `.md` や履歴欄へ追記しない。
- 公開証拠はGitHub Commit、Actions、本番HTTPを正本とする。
- 仕様変更だけ該当正本を更新し、ページ変更と同じCommitへ含める。
- 旧資料・バックアップは書き換えない。
