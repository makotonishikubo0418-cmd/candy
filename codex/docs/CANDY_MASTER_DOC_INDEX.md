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
| area画像の新規制作 | `CANDY_AREA_IMAGE_CREATION_SPEC.md`、`CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| 通常hotel制作・公開 | `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` |
| hotel入力分類・作成順 | `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` |
| hotel画像の新規制作 | `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` |
| 通常blog制作・公開 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| area未知例外 | `CANDY_AREA_PAGE_GENERATION_SPEC.md`、必要時 `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| blog未知例外 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_BLOG_PAGE_GENERATION_SPEC.md` |
| hotel未知例外 | `CANDY_PAGE_GENERATION_GOVERNANCE.md`、`CANDY_HOTEL_PAGE_GENERATION_SPEC.md`、必要時 `CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md` |
| area／hotel／blog以外のページ調査・修正 | `CANDY_OTHER_PAGES_MANAGEMENT.md`、`CANDY_OPERATION_BASICS.md` |
| 既存ページ修正 | `CANDY_OPERATION_BASICS.md`、対象カテゴリspec |
| 構造調査 | `CANDY_HP_STRUCTURE_MAP.md`、`CANDY_CODE_FILE_STRUCTURE.md` |
| 全件・リンク・画像検証 | `CANDY_VERIFICATION_PLAN.md` |
| 本番・Actions・FTP基盤 | `CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| 未対応課題 | `CANDY_FIX_BACKLOG.md` |
| 事故経緯 | `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` |

一覧・inventory資料は調査対象の特定にだけ使い、現在値は実ファイルから取得する。

### 2.1 area・hotelの役割とページ内構成をすぐ出す

「areaページの役割・構成」「hotelページ版」「構成をツリーで出す」と依頼された場合は、次の即時参照節を使う。

| 対象 | 役割・ページ内構成の正本 | 使い方 |
|---|---|---|
| area詳細 | `CANDY_AREA_PAGE_GENERATION_SPEC.md` の「1.1 役割とページ内構成（即時参照）」 | 5sceneの標準表示順、店舗・ホテル・スポットの反復項目、関連記事8件、構造化データを確認する |
| hotel詳細 | `CANDY_HOTEL_PAGE_GENERATION_SPEC.md` の「1.1 役割とページ内構成（即時参照）」 | 可変scene、必須・任意セクション、店舗・FAQ・料金・スポットの反復項目、構造化データを確認する |

- 構成回答は各節のツリーを土台にし、単純な見出し一覧だけへ省略しない。
- 件数は固定表示例から推測せず、対象txtの完成ブロック数へ合わせる。固定数は関連記事予約ダミー8件だけとする。
- 実際の作成・修正では、即時参照節だけで判断せず、同じspecの絶対ルールと該当runbookも適用する。
- `CANDY_PAGE_SPEC_INDEX.md` と `CANDY_PAGE_CATEGORY_STRUCTURE.md` は過去時点の全件スナップショットであり、現在のarea・hotel構成回答の正本にしない。

## 3. 通常作業から除外

- `C:\Codex\candy\codex\ページ作成用.md`: 更新停止した旧制作記録
- `codex/00_CANDY_SEO_START_HERE.md`
- `codex/area/`
- `codex/reform_20260529/`
- NAS `\\192.168.1.3\disk1\FSG_SEO\candy\Backup/`（保管専用）
- `.git-backups/`

これらは現行仕様の根拠にせず、通常検索・通読・更新の対象にしない。

## 4. 資料更新

- 同じルールを複数資料へ書かない。
- 新規資料を作る前に既存正本へ統合できるか確認する。
- 通常ページごとの結果を新しい `.md` や履歴欄へ追記しない。
- 公開証拠はGitHub Commit、Actions、本番HTTPを正本とする。
- 仕様変更だけ該当正本を更新し、ページ変更と同じCommitへ含める。
- 旧資料・バックアップは書き換えない。
