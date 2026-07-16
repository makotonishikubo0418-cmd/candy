# プロジェクト現在地

- 目的: 全体計画、現在地、問題、次作業を1か所で見る
- 状態: 正本
- 更新日: 2026-07-16

## 1. 現在地

| 種別 | パス | 状態 |
|---|---|---|
| 共有正本フォルダ | `\\192.168.1.3\disk1\FSG_SEO\candy` | ユーザー指定の正フォルダ |
| Git作業場 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP` | GitHub `makotonishikubo0418-cmd/candy` の作業場 |
| 実サイト配下 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP\HP` | PHP/HTML/画像/生成ツール |
| Git管理の管理入口 | `README.md` | 正本 |
| 外側入口 | `../README.md`, `../AGENTS.md` | Git管理正本への誘導のみ |

## 2. Git状態

2026-07-16の管理体制修正開始時点で確認した状態。

| 項目 | 値 |
|---|---:|
| ブランチ | `main` |
| リモート | `https://github.com/makotonishikubo0418-cmd/candy.git` |
| 既存の変更 | 4件 |
| 既存の削除表示 | 76件 |
| 既存の未追跡 | 99件 |
| 既存状態の合計 | 179件 |

2026-07-16再監査残件修正前の確認値は、変更7件、削除表示76件、未追跡108件、合計191件。未追跡108件には、Git管理用Markdown 8件を含む。

## 3. 直近の確認済み事項

- `../除外リスト/20260716_clear_duplicate_or_artifact` に86件を退避済み。
- 退避86件のうち、76件はGit上で削除表示になっている。
- 削除表示76件は主に `log/mainImg_debug_*.log`、`includefile/debug_mypage.log`、`HP/codex/scripts/__pycache__/candy_area_page.cpython-312.pyc`。
- 退避操作のユーザー指示は、2026-07-16の「`\\192.168.1.3\disk1\FSG_SEO\candy\除外リスト` 作成した 実行しろ」。
- エリア入力3件に修正差分がある。
- エリア画像仕様関連の差分が4件増加している。
- 残る完全重複は `seiryo` 画像2件。ただし現行txtが参照しているため保留。

## 4. 主な問題

| 問題 | 状態 | 次の扱い |
|---|---|---|
| 管理文書が外側にありGit管理されていなかった | 修正済み | Git作業場内へ正本を設置し、外側は入口に降格 |
| 既存179件の作業状態がTask履歴と結びついていなかった | 修正済み | `TASK_LOG.md` へ対応付け |
| 管理文書8件のGitHub共有 | Commit未実施 | 明示指示後にCommit/Push対象へ含める |
| 未追跡txtが多数残っている | 未整理 | 作成可否と採用可否で分類 |
| エリア入力に停止対象がある | 未解消 | `HP/codex/docs/CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md` を確認 |
| ホテル入力に停止対象がある | 未解消 | 個別に不足理由を分類 |
| `movie/*` の扱い | 保留 | 必要資産か確認してから判断 |
| `seiryo` / `seiryou` 画像重複 | 保留 | canonical参照を確認してから判断 |

## 5. 次作業候補

1. Commit/Push対象の確認
2. 未追跡txtの採用/保留/除外分類
3. エリア停止対象の修正
4. ホテル停止対象の修正

## 6. Commit対象候補

管理体制をGitHubで永続化する場合、少なくとも次の8件をCommit対象に含める。

- `README.md`
- `管理体制_概要説明書.md`
- `管理体制/CODEX_COMMUNICATION.md`
- `管理体制/CODE_STRUCTURE.md`
- `管理体制/DOCUMENT_RULES.md`
- `管理体制/PROJECT_STATUS.md`
- `管理体制/TASK_LOG.md`
- `管理体制/TASK_RESERVATIONS.md`

Commit/Pushは明示指示がある場合だけ実行する。

## 7. 更新ルール

- 新しい実作業の結果は、必要な要約だけここへ反映する。
- 詳細ログは `TASK_LOG.md` へ書く。
- Codex間の依頼や引継ぎは `CODEX_COMMUNICATION.md` へ書く。