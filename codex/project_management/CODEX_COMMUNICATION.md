# Codex連絡帳

- 目的: 複数Codex間の引継ぎ、依頼、注意点を残す
- 状態: 正本
- 更新日: 2026-07-17

## 1. 使い方

- 他Codexへ渡す情報だけを書く。
- 仕様や作業履歴をここへ混ぜない。
- 解決した連絡は「完了」へ移す。

## 2. 未完了

| ID | 日時 | 宛先 | 内容 | 対象 | 状態 |
|---|---|---|---|---|---|
| COMM-20260716-001 | 2026-07-16 | 全Codex | 作業前に `codex/project_management/TASK_RESERVATIONS.md` で対象ファイルの予約を確認する | 全体 | 有効 |
| COMM-20260716-003 | 2026-07-16 | 全Codex | 既存の削除表示76件は退避操作の結果。Commit対象に含める前に対象範囲を確認する | Git状態 | 有効 |
| COMM-20260716-004 | 2026-07-16 | 全Codex | area画像不足時は `CANDY_AREA_IMAGE_CREATION_SPEC.md` を確認する。Googleマップ等は保存・加工・商用公開条件と帰属表示を確認できなければ制作・公開を停止する | area画像制作 | 有効 |
| COMM-20260716-005 | 2026-07-16 | 全Codex | NAS/UNC上でCommit・Pushする時は、対象ファイルを固定し、Git確認を1本のPowerShellへまとめる。multi_tool_use.parallelでGit確認を複数投入しない | Git Commit/Push | 有効 |
| COMM-20260716-006 | 2026-07-16 | 全Codex | エリア制作で `01_間違い無し` は新規制作可能を意味しない。publish前にcanonical slugから公開PHP、source HTML、dataset PHP、dataset_base、area一覧、sitemapの既存有無を確認し、NEW_PAGE_TARGET_OKが出た対象だけ進める | Area target selection | 有効 |
| COMM-20260716-007 | 2026-07-16 | 全Codex | エリア制作ゲートは、area一覧に同一地域名で別slugのリンクがある候補を `NEW_PAGE_TARGET_OK` にしてはいけない。`area list same-region slug mismatch` はpublish前に除外する | Area target selection | 有効 |
| COMM-20260716-008 | 2026-07-16 | 全Codex | area一覧の対象slugリンク1件は新規area制作に必要。area一覧に同一地域名で別slugがある場合だけ除外する。area一覧リンクを単純な既存登録扱いで除外しない | Area target selection | 有効 |
| COMM-20260716-009 | 2026-07-16 | 全Codex | hotel制作は `candy-hotel.cmd target-next` または `target-check` で `NEW_HOTEL_TARGET_OK` が出た1件だけ進める。画像なし、入力不備、作成済み、未追跡、未登録店舗は制作前に除外する | Hotel target selection | 有効 |
| COMM-20260716-010 | 2026-07-16 | 全Codex | hotel制作は `publish-next` を標準入口にする。停止時は `COUNTS_JSON` だけでなく `BLOCKER_COUNTS_JSON` を確認し、画像なしと入力未追跡を分けて扱う | Hotel target selection | 有効 |
| COMM-20260716-012 | 2026-07-16 | 全Codex | GitHub作業場は `\\192.168.1.3\disk1\FSG_SEO\candy`。`HP/` は実サイト配下専用。`HP/HP/` と `HP/README.md` は作らない | HP階層 | 有効 |
| COMM-20260716-013 | 2026-07-16 | 全Codex | 削除、移動、一括整理、Git復旧は `codex/project_management/SAFETY_PROTOCOL.md` を読む。対象分類、保護対象除外、対象リスト固定なしに実行しない | 高リスク操作 | 有効 |
| COMM-20260717-014 | 2026-07-17 | 全Codex | 管理入口は `codex/README.md`、プロジェクト管理正本は `codex/project_management/`、HP制作仕様は `codex/docs/`。共有ルートやHP配下へ管理正本を複製しない | 管理文書 | 有効 |
| COMM-20260717-015 | 2026-07-17 | 全Codex | `codex/scripts/` は移動済みだが内部パス移行未完了。area・hotel・blog・資料生成は修正とdry-run検証が終わるまで実行しない | 生成ツール | STOP |

## 3. 完了

| ID | 日時 | 内容 | 結果 |
|---|---|---|---|
| COMM-20260716-002 | 2026-07-16 | 管理文書を共有ルート直下へ置く旧方針 | 2026-07-17のユーザー指示で `codex/` 配下管理へ変更し、COMM-20260717-014へ移行 |
| COMM-20260716-011 | 2026-07-16 | 外側 `candy` を管理正本にする旧方針 | 2026-07-17のユーザー指示で `codex/project_management/` へ変更し、現行導線から除外 |
