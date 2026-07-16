# candy Git管理入口

このREADMEは、candyリポジトリのGit管理される管理入口です。

- 共有フォルダ: `\\192.168.1.3\disk1\FSG_SEO\candy`
- Git作業場: `\\192.168.1.3\disk1\FSG_SEO\candy\HP`
- 実サイト配下: `HP/`

## 最初に読むもの

1. `AGENTS.md`
2. この `README.md`
3. 下記のうち今回必要な正本だけ

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

## 作業ルート

| 作業 | 読む導線 |
|---|---|
| HPページ制作 | `AGENTS.md` -> 該当runbook |
| HP管理文書更新 | `AGENTS.md` -> `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| 管理体制変更 | `AGENTS.md` -> `README.md` -> `管理体制_概要説明書.md` -> `管理体制/DOCUMENT_RULES.md` |
| 複数Codexの調整 | `AGENTS.md` -> `README.md` -> `管理体制/TASK_RESERVATIONS.md` -> `管理体制/CODEX_COMMUNICATION.md` |
| 全体状況確認 | `AGENTS.md` -> `README.md` -> `管理体制/PROJECT_STATUS.md` |

## 外側ファイルの扱い

`\\192.168.1.3\disk1\FSG_SEO\candy` 直下の `README.md` と `管理体制/*` は、共有フォルダ直下からこのGit管理入口へ来るための誘導だけです。

管理の正本はこのGit作業場内に置きます。GitHubへ残す必要がある判断、履歴、現在地は外側ではなくこのREADME配下の正本へ記録します。

## 運用方針

- 親 `AGENTS.md` へ詳細手順を増やさない。
- このREADMEへ正本一覧を集約する。
- 仕様、現在状態、連絡、履歴を混ぜない。
- 必要な文書だけを読む。
- 未確認を完了扱いしない。