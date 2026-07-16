# candy 管理入口

このREADMEは、`\\192.168.1.3\disk1\FSG_SEO\candy` の管理入口です。

## 正本の置き場所

| 種類 | 場所 | 扱い |
|---|---|---|
| 管理正本 | `\\192.168.1.3\disk1\FSG_SEO\candy` | AGENTS、README、管理体制、停止済み旧記録を置く正本 |
| GitHub作業場 | `\\192.168.1.3\disk1\FSG_SEO\candy` | GitHub `makotonishikubo0418-cmd/candy` へつながる作業場 |
| 実サイト配下 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP` | PHP、source HTML、dataset、画像、生成ツール |

## 最初に読むもの

1. `AGENTS.md`
2. この `README.md`
3. 下記のうち今回必要な正本だけ
4. HP作業なら `HP/AGENTS.md` と該当runbook

## 正本一覧

| 目的 | 正本 |
|---|---|
| 管理体制の概要 | `管理体制_概要説明書.md` |
| 文書分割・更新ルール | `管理体制/DOCUMENT_RULES.md` |
| 全体計画・現在地・問題 | `管理体制/PROJECT_STATUS.md` |
| Codex間の連絡・引継ぎ | `管理体制/CODEX_COMMUNICATION.md` |
| Taskとファイル予約 | `管理体制/TASK_RESERVATIONS.md` |
| 個別Task履歴 | `管理体制/TASK_LOG.md` |
| コード・フォルダ構成 | `管理体制/CODE_STRUCTURE.md` |
| HP制作・生成仕様 | `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| 旧ページ制作記録 | `ページ作成用.md` |

## 作業ルート

| 作業 | 読む導線 |
|---|---|
| 管理体制変更 | `AGENTS.md` -> `README.md` -> `管理体制_概要説明書.md` -> `管理体制/DOCUMENT_RULES.md` |
| 複数Codexの調整 | `AGENTS.md` -> `README.md` -> `管理体制/TASK_RESERVATIONS.md` -> `管理体制/CODEX_COMMUNICATION.md` |
| 全体状況確認 | `AGENTS.md` -> `README.md` -> `管理体制/PROJECT_STATUS.md` |
| HPページ制作 | `AGENTS.md` -> `README.md` -> `HP/AGENTS.md` -> 該当runbook |
| HP管理資料更新 | `AGENTS.md` -> `README.md` -> `HP/AGENTS.md` -> `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Commit/Push | `AGENTS.md` -> `README.md` -> `管理体制/DOCUMENT_RULES.md` |

## 重複禁止

- `HP/HP/` は作らない。
- `HP/管理体制/` は作らない。
- `HP/管理体制_概要説明書.md` は作らない。
- `HP/README.md` は作らない。HP作業導線は `HP/AGENTS.md` に集約する。
- 管理の判断、履歴、現在地、予約、連絡はこのREADME配下の正本へ書く。
- GitHubへ送る必要がある作業ファイルは、GitHub作業場 `\\192.168.1.3\disk1\FSG_SEO\candy` から対象を固定してCommit/Pushする。
- HPフォルダの中は、実サイト配下 `HP/` の中身だけにする。
