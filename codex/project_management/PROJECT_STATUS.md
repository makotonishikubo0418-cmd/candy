# プロジェクト現在地

- 目的: 全体計画、現在地、問題、次作業を1か所で見る
- 状態: 正本
- 更新日: 2026-07-17

## 1. 現在地

| 種別 | パス | 状態 |
|---|---|---|
| ローカルGit作業場 | `C:\Codex\candy` | GitHubへ同期する唯一の作業用リポジトリルート |
| GitHub同期ハブ | `makotonishikubo0418-cmd/candy` | Codex間でCommitを共有する。Pushは明示指示時だけ行う |
| Codex管理正本 | `C:\Codex\candy\codex` | 管理資料と作業ツールの正本 |
| 管理正本入口 | `C:\Codex\candy\codex\README.md` | 正本 |
| プロジェクト管理 | `C:\Codex\candy\codex\project_management` | ルール、状態、予約、履歴、安全手順 |
| HP制作仕様 | `C:\Codex\candy\codex\docs` | area・hotel・blog等のrunbookと仕様 |
| 作業ツール | `C:\Codex\candy\codex\scripts` | 内部パス移行未完了のため生成・公開コマンドは実行停止を継続 |
| 実サイト配下 | `C:\Codex\candy\HP` | PHP、source、includefile、画像、log、movie |
| 制作入力 | ルート直下の `Text_area_data`、`Text_blog_data`、`Text_hotel_data` | HP外の制作元データ |
| NAS保管 | `\\192.168.1.3\disk1\FSG_SEO\candy` | `Backup/`・受入素材の保管専用。Git操作は禁止 |
| HP作業導線 | `C:\Codex\candy\HP\AGENTS.md` | HP作業用。管理正本ではない |

## 2. Git状態

### 2.1 現行ローカルGit状態

2026-07-17の本Task開始時に `C:\Codex\candy` で確認した状態。

| 項目 | 値 |
|---|---|
| ブランチ | `main` |
| upstream | `origin/main` |
| リモート | `https://github.com/makotonishikubo0418-cmd/candy.git` |
| `core.autocrlf` | `false` |
| HEAD / origin/main | `1f856a7c4f513a2908ca5578eb517e9d872635e7` で一致 |
| 作業ツリー | Task開始時はclean。本Taskの管理文書差分は未Commit |

作業開始時は `git fetch origin` と `git status --short --branch` で最新状態を確認し、behindなら編集前にpullする。NASではGit操作しない。

### 2.2 2026-07-16旧NAS作業場の過去スナップショット

以下は2026-07-16の管理体制修正開始時点で確認した過去状態。

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
| ローカル作業場と管理資料の不一致 | 修正済み・未Commit | 現行導線を `C:\Codex\candy`、GitHub同期、NAS保管専用へ更新 |
| NAS上Git運用 | 解決済み（ユーザー実施） | NASの `.git` は `Backup/` 内へ退避済みとの申告。今後NASでGit操作しない。今回NASは未操作 |
| `codex/scripts/` 内部の旧階層前提 | 未解消・実行停止 | リポジトリルート、HPルート、`Text_*_data` の解決処理を修正し、area・hotel・blog・資料生成をdry-run検証する |
| 旧NAS作業場の大量差分・未追跡 | 解決済み | 現ローカルcloneは本Task開始時clean。旧件数は過去スナップショットとして扱う |
| 今回の管理文書のGitHub共有 | Commit/Push未実施 | 今回は禁止。別途明示指示がある場合だけ対象を固定してCommit/Pushする |
| area・hotel入力の停止対象 | 未解消 | 各入力分類とtarget gateを、スクリプト移行完了後に再実行する |
| `seiryo` / `seiryou` 画像重複 | 保留 | canonical参照を確認してから判断する |

## 5. 次作業候補

1. `codex/scripts/` のルート計算と `Text_*_data` 入力制限を新配置へ修正する
2. area・hotel・blog・管理資料生成を本番操作なしで検証する
3. スクリプト実行停止を解除できるか判定し、管理資料へ結果を反映する
4. 今回の管理文書をCommit/Pushする場合は、別途明示指示を受け、対象を固定して確認する
5. area・hotelの入力停止対象を再分類する

## 6. Commit対象候補

今回のローカル作業場定義変更をGitHubへ永続化する場合は、今回更新した管理文書だけを対象として確認する。

管理資料の中心対象:

- `AGENTS.md`
- `codex/README.md`
- `codex/管理体制_概要説明書.md`
- `codex/project_management/` の変更対象
- `codex/docs/` の参照先変更対象

Commit/Pushは明示指示がある場合だけ実行する。

## 7. 更新ルール

- 新しい実作業の結果は、必要な要約だけここへ反映する。
- 詳細ログは `TASK_LOG.md` へ書く。
- Codex間の依頼や引継ぎは `CODEX_COMMUNICATION.md` へ書く。

## 8. 2026-07-16 管理正本の外側一本化

- ユーザー指示により `\\192.168.1.3\disk1\FSG_SEO\candy` を管理正本にした。
- 詳細管理文書を `HP/管理体制` から外側 `管理体制` へ反映した。
- `HP/管理体制`, `HP/管理体制_概要説明書.md`, `HP/ページ作成用.md` は重複として削除した。
- その後、ユーザー指示により `.git` を外側へ移動し、外側をGitHub作業場にした。
- GitHubに接続している作業場は `\\192.168.1.3\disk1\FSG_SEO\candy`。HP実サイト配下は `\\192.168.1.3\disk1\FSG_SEO\candy\HP`。

## 9. 2026-07-16 HP階層一本化

- ユーザー指示「HPの中は HP\HP の中身だけでいい」により、旧 HP\HP の中身を HP 直下へ移動した。
- .git は \\192.168.1.3\disk1\FSG_SEO\candy 直下へ移動した。
- 旧 HP 直下のGitHub設定、バックアップ、ログ、movie、root用ファイルは外側へ移動または 除外リスト\20260716_hp_flatten_non_site へ退避した。
- HP\HP は削除済み。今後作らない。

## 10. 2026-07-16 Git破損事故後の復旧と安全管理

- GitHub最新反映済みCommitは `45b28c1c6b869cca7e21938b7713b5a709ed4be7`。
- `.git/HEAD`、`.git/config`、`.git/index`、`AGENTS.md`、`README.md`、`HP/AGENTS.md`、`HP/index.php` は復旧確認済み。
- `HP/HP` は存在しないことを確認済み。
- `git fsck --no-dangling`、`git diff-tree --check --no-commit-id -r HEAD` は成功済み。
- 事故原因は、削除対象分類不足、`.git` 除外明示不足、削除前対象リスト未確定、物理削除とGit管理削除の混同。
- 再発防止の正本は `管理体制/SAFETY_PROTOCOL.md`。
- 高リスク操作では、削除可、Git管理から削除、移動済み、Git登録、復旧、保留へ分類してから実行する。
