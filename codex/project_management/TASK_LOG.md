# Task履歴

- 目的: 個別Taskの結果、確認済み、未確認を残す
- 状態: 正本
- 更新日: 2026-07-17

## 1. 記録ルール

- 仕様は書かない。結果だけを書く。
- 完了、未完了、未確認を分ける。
- Commit/Push/本番は実施した場合だけ書く。
- 既存差分を見つけた場合は、Task ID、目的、承認根拠、未確認を分けて記録する。

## 2. 履歴

| Task ID | 日時 | 内容 | 変更 | 確認済み | 未確認 |
|---|---|---|---|---|---|
| TASK-20260717-SCRIPTS-LOCAL-PATHS-001 | 2026-07-17 | `codex/scripts/`の内部パスをローカル新配置へ移行し、書き込みなしで検証 | Python 10件を`candy_page_common.py`の`REPO_ROOT`・`HP_ROOT`・各`TEXT_*_DIR`・`SCRIPTS_DIR`・`DOCS_DIR`へ統合。hotel publishのdry-runはロックを書かない経路へ修正。ps1 2件は同梱Python・preview・bytecode抑止へ対応し、cmd 3件は相対実行済みのため変更なし。READMEとPROJECT_STATUSの実行停止記述を検証結果へ合わせて更新 | py_compile 10件、全module import、共通パス7件、area/hotel target-check・target-next、area/hotel/blog build dry-run、hotel件数JSON、資料生成`--preview`を確認。旧`HP/codex/`・`HP/Text_*_data`・固定`parents[3]`は0件。HP・Text・生成対象docsへの実行時書き込み0件。検証時に生成されたpyc 1件と空ディレクトリは承認後に削除し、ラッパー再実行後もpyc 0件を確認 | 本番、publish、資料13件の実書き込みは未実施。実公開経路の完走は未確認 |
| TASK-20260717-LOCAL-WORKSPACE-DOCS-001 | 2026-07-17 | Git作業場のローカルclone移行に伴う管理文書更新とGitHub同期 | 前回更新した管理文書13件と本`TASK_LOG.md`の計14件。Git作業場を`C:\Codex\candy`、GitHubを同期ハブ、NASを保管専用として記録 | `git fetch origin`後のahead/behind 0/0、14ファイルの明示Stage、`git diff --cached --check`、指定メッセージでのCommit、origin/mainへのPush、`git ls-remote`一致、Commit SHA対象のActions RunをGitHub APIで確認 | scripts修正・実行、`HP/`変更、本番操作、手動Actionsは未実施 |
| TASK-20260717-GITHUB-SYNC-001 | 2026-07-17 | GitHub構成を現行NASへ同期 | `.github/`、`.gitignore`、`AGENTS.md`、`HP/`、`codex/`、3つの`Text_*_data/`を正とし、旧ルート構成をGit管理から除外。`Backup/`は除外 | 保護ファイル、対象範囲、未Stage、機密候補、`git diff --cached --check`を確認。Commit `7d23c91` をorigin/mainへPushし、`ls-remote`一致 | 本番操作と手動Actionsは未実施 |
| TASK-20260717-STRUCTURE-DOCS-001 | 2026-07-17 | フォルダ再編後の管理資料更新 | `AGENTS.md`、`HP/AGENTS.md`、`codex/` 配下の管理正本・現行制作資料、計26ファイル | 実パス確認、現行資料の旧参照0件、UTF-8/BOM/見出し/表検査合格、スクリプト未移行STOPを記録 | スクリプト修正・実行、Commit/Push、本番操作は未実施 |
| TASK-20260716-MGMT-001 | 2026-07-16 | 管理体制初期整備 | 外側親AGENTS、README、管理体制MD、HP導線 | 文書作成、導線整理 | Commit/Push未実施。監査でGit管理外問題を検出 |
| TASK-20260716-CLEANUP-001 | 2026-07-16 | 重複・副産物の退避 | `../除外リスト/20260716_clear_duplicate_or_artifact` へ86件退避。Git上では76件が削除表示 | 退避先86件、削除表示76件、主対象がログ/キャッシュであること | Commit/Push未実施。Commit対象に含めるか未確定 |
| TASK-20260716-AREA-TEXT-001 | 2026-07-16 | エリア入力txtの誤記修正 | `HP/Text_area_data/下福元町_テンプレート.txt`, `HP/Text_area_data/下竜尾町.txt`, `HP/Text_area_data/慈眼寺町_テンプレート.txt` | `aaaaaaaaaaaaaaaaaaaa` の地域名・本文補完、下竜尾町画像srcの空白除去、慈眼寺町title/description補正 | Commit/Push未実施。ページ生成・本番反映未実施 |
| TASK-20260716-MGMT-002 | 2026-07-16 | 監査指摘修正 | candy用概要へ修正、Git管理側に管理正本を設置、外側文書を入口へ降格、既存179件をTask履歴へ対応付け | 旧プロジェクト表記の排除、Git管理外問題の是正方針、削除76件の承認根拠記録 | Commit/Push未実施。作業後Git状態は変更4件、削除表示76件、未追跡107件、合計187件 |
| TASK-20260716-AREA-IMAGE-SPEC-001 | 2026-07-16 | エリア画像制作仕様の正本化 | 新規制作仕様、画像管理、area runbook、資料ルーター、Codex連絡を統合 | 1000×750、命名、保存先、組込み、権利・帰属STOP、他カテゴリ展開方針 | 実画像制作、Chrome操作、Commit/Push、本番未実施 |
| TASK-20260716-MGMT-003 | 2026-07-16 | 再監査残件修正 | TASK_LOG表崩れ修正、PROJECT_STATUS現在値更新、管理文書8件のCommit対象候補整理 | 変更7件、削除表示76件、未追跡108件、合計191件を反映 | Commit/Push未実施 |
| TASK-20260716-MGMT-004 | 2026-07-16 | Git Commit/Push高速実行ルール追加 | NAS/UNC上のGit確認統合、並列Git確認禁止、対象固定、Commit前確認、監査項目を管理書へ追加 | 前回の遅延原因をルール化し、監査項目へ反映 | Commit/Push未実施 |
| TASK-20260716-MGMT-005 | 2026-07-16 | エリア対象選定管理の修正 | `間違い無し`分類と新規制作可否を分離し、publish前に既存PHP/source/dataset/共有登録を確認するゲートをrunbookへ追加 | 四元町の既存ページ選定ミスを再発防止ルール化 | Commit/Push未実施 |
| TASK-20260716-MGMT-006 | 2026-07-16 | area一覧slug不一致の管理修正 | `target-next` / `target-check` に同一地域・別slug検査を追加し、runbook・仕様・管理規則・連絡帳へ反映 | 中央港新町でpublish後に出たslug不一致STOPをpublish前に除外する方針へ更新 | Commit/Push未実施 |
| TASK-20260716-MGMT-007 | 2026-07-16 | area一覧リンク扱いの修正 | 対象slugのarea一覧リンク1件は必要条件、同一地域・別slugだけ除外条件としてtarget gateと管理書を修正 | 制作可否管理でarea一覧リンクを作成済み扱いにする誤分類を是正 | Commit/Push未実施 |
| TASK-20260716-MGMT-008 | 2026-07-16 | hotel制作対象管理と既存hotel分解の再整備 | hotel target gate、hotel runbook、hotel spec、hotel入力分類、hotel画像仕様、管理規則を更新。publish-next標準化、BLOCKER_COUNTS_JSON、audit-existingを追加 | 既存3hotelの接続状態、hotel入力74件の分類、target-next停止、publish-next dry-run停止、Markdown表、Python構文、対象diff checkを確認 | Commit/Push、本番、画像制作、既存hotel登録修正は未実施 |
| TASK-20260716-MGMT-009 | 2026-07-16 | 管理正本の外側一本化 | 外側README/AGENTS/管理体制を正本化。HP側の重複管理フォルダ、概要説明書、更新停止ページ作成用、空の外側.gitを削除 | 外側が正、HP側はHP作業導線のみ。HP GitHub作業場は維持 | Commit/Push未実施。外側正本は現時点でGit管理外 |
| TASK-20260716-MGMT-010 | 2026-07-16 | HP階層一本化 | `.git` を外側へ移動し、旧 `HP/HP` の中身を `HP` 直下へ移動。旧HP直下の非サイト物は外側または除外リストへ退避 | `HP/HP` 不在、`HP/index.php` 存在、GitHub remote確認済み | Commit/Push未実施 |
| TASK-20260716-MGMT-011 | 2026-07-16 | Git破損事故後の安全管理再整備 | `SAFETY_PROTOCOL.md` 新設、AGENTS/README/DOCUMENT_RULES/PROJECT_STATUS/CODEX_COMMUNICATION更新 | 削除・移動・一括整理・Git復旧の分類、保護対象、停止条件を正本化 | Commit/Push未実施 |

## 3. 承認根拠メモ

| 対象 | 根拠 | 扱い |
|---|---|---|
| 退避86件 / 削除表示76件 | ユーザー指示「`\\192.168.1.3\disk1\FSG_SEO\candy\除外リスト` 作成した 実行しろ」 | ローカル退避済み。Commit対象化は別途確認 |
| エリア入力3件 | ユーザー指示「間違いは今修正しろ」後の入力txt修正 | ローカル修正済み。ページ生成・本番反映は別Task |
