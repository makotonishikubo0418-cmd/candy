# 文書分割・更新ルール

- 目的: Markdown管理体制で文書の責任を分ける
- 状態: 正本
- 更新日: 2026-07-16

## 1. 原則

- 1つの内容に正本は1つだけ。
- 同じ説明を複数文書へ重複して持たない。
- 入口は `AGENTS.md` と `README.md` に限定する。
- 詳細手順は作業別の正本へ置く。
- 報告や履歴は仕様書に混ぜない。
- 管理情報の正本は共有フォルダ直下へ置く。HP側に同じ管理文書を作らない。

## 2. 管理正本の場所

管理文書の正本は `\\192.168.1.3\disk1\FSG_SEO\candy` 直下に置く。

内側 `\\192.168.1.3\disk1\FSG_SEO\candy\HP` の `AGENTS.md` と `README.md` はHP作業導線だけであり、管理正本ではない。`HP\管理体制` と `HP\管理体制_概要説明書.md` は作らない。

## 3. 文書の役割

| 種別 | 役割 |
|---|---|
| `AGENTS.md` | 共通ルールと導線 |
| `README.md` | 正本一覧と読む順番 |
| `管理体制_概要説明書.md` | 管理体制の目的と設計思想 |
| 仕様書 | 確定仕様 |
| `PROJECT_STATUS.md` | 計画、問題、残件、次作業 |
| `CODEX_COMMUNICATION.md` | 引継ぎ、依頼、注意 |
| `TASK_LOG.md` | 実施結果、確認済み、未確認 |
| `TASK_RESERVATIONS.md` | 同時編集防止 |
| `CODE_STRUCTURE.md` | フォルダと作業対象の構成 |
| SAFETY_PROTOCOL.md | 削除、移動、一括操作、Git復旧の安全手順 |

## 4. 更新禁止

- 末尾へ無秩序に追記しない。
- 未確認情報を確定仕様へ入れない。
- 古い報告を現在状態として扱わない。
- 既存正本がある内容の新規MDを増やさない。
- 外側入口に実質的な管理履歴を持たせない。

## 5. 状態ラベル

文書内で不確定情報を扱う場合は、次のどれかを明記する。

- 確定
- ユーザー申告
- 実装確認済み
- 未確認
- 保留

## 6. 変更時の確認

文書を更新したら、最低限次を確認する。

- 正本の重複が増えていない
- READMEの導線が壊れていない
- 仕様と履歴が混ざっていない
- 未確認を完了扱いしていない
- 管理文書の正本が共有フォルダ直下にあり、HP側に重複していない

## 7. Git Commit・Push高速実行ルール

- 開始時に今回の対象ファイルを固定する。対象外の変更、削除、未追跡は確認、Stage、Commitへ含めない。
- `AGENTS.md`、必要な`README.md`、Branch、Remote、対象差分は開始時に1回だけ確認する。
- NAS/UNC上ではGit確認を細切れ、並列実行しない。1本のPowerShellにまとめる。
- `multi_tool_use.parallel`でGit確認を複数投入しない。
- 対象確定後は、全体`status`、全体`diff`、全体`diff --stat`を実行しない。
- `git add .`、`git add -A`は禁止。対象ファイルを明示してStageする。
- Commit前確認は、Stage済みが対象ファイルだけ、`git diff --cached --check`成功、Commit内容が指示範囲と一致、の3点だけを1回確認する。
- Commit成功後は、競合、Push先不明、非Fast-forwardがない限り、そのままPushする。
- この連続Pushは、ユーザーがPush、アップ、またはCommit/Pushを明示承認したTaskに限る。

### 7.1 one-shot Git preflight command

対象ファイルを修正し、対象リストを固定した後、次のPowerShellを1回だけ実行する。`$targets`には、今回の対象ファイルだけをGit作業場ルートからの相対パスで入れる。

```powershell
$managementRoot = '\\192.168.1.3\disk1\FSG_SEO\candy'
$repo = '\\192.168.1.3\disk1\FSG_SEO\candy'
$targets = @(
  'REPLACE_WITH_TARGET_FILE'
)

Test-Path -LiteralPath (Join-Path $managementRoot 'AGENTS.md')
Test-Path -LiteralPath (Join-Path $managementRoot 'README.md')
Test-Path -LiteralPath (Join-Path $managementRoot '管理体制\DOCUMENT_RULES.md')
Test-Path -LiteralPath (Join-Path $repo 'AGENTS.md')
Test-Path -LiteralPath (Join-Path $repo 'README.md')
Test-Path -LiteralPath (Join-Path $repo 'HP\AGENTS.md')
Test-Path -LiteralPath (Join-Path $repo 'README.md')

git -c safe.directory='*' -C $repo remote -v
git -c safe.directory='*' -C $repo branch --show-current
git -c safe.directory='*' -C $repo status --short -- $targets
git -c safe.directory='*' -C $repo diff --stat -- $targets
git -c safe.directory='*' -C $repo diff --check -- $targets
```

## 8. Git Commit・Push監査項目

Git Commit・Push作業後の監査では、`git diff --check`だけで合格扱いしない。次を確認する。

| 項目 | 確認内容 |
|---|---|
| 対象固定 | Stage、Commit、Push対象が指示された対象だけである |
| NAS/UNC対応 | Git確認が1本のPowerShellに統合されている |
| 並列Git確認 | `multi_tool_use.parallel`でGit確認を複数投入していない |
| Commit前確認 | `git diff --cached --check`が成功している |
| Markdown表 | ヘッダーと各行の列数が一致している |
| 配置 | Task履歴、連絡帳、現在地が正しい節に入っている |
| 状態 | 「有効」「完了」など状態と配置が一致している |
| 権限 | 上位AGENTSのCommit/Push許可条件と矛盾していない |
| GitHub確認 | 開始時に決めた方法だけで確認している |

## 9. Area制作対象管理

- `間違い無し`分類は新規制作可能を意味しない。
- publish前にcanonical slugから公開PHP、source HTML、dataset PHP、dataset_base、area一覧、sitemapの既存有無を確認する。
- 同一地域名で別slugのarea一覧リンクがある候補は、新規制作対象にしない。
- area一覧の対象slugリンク1件は必要条件として扱う。同一地域名で別slugがある場合だけ除外する。
- `NEW_PAGE_TARGET_OK` が出た対象だけ制作へ進める。

## 10. Hotel制作対象管理

- hotel制作は `candy-hotel.cmd target-next` または `target-check` で `NEW_HOTEL_TARGET_OK` が出た1件だけ進める。
- 画像なし、入力不備、作成済み、入力未追跡、未登録店舗は制作前に除外する。
- hotel制作は `candy-hotel.cmd publish-next` を標準入口にする。
- 停止時は `COUNTS_JSON` だけでなく `BLOCKER_COUNTS_JSON` を確認し、画像なしと入力未追跡を分けて扱う。
- `HP/source/hotel.html`、`dataset_base.php`、`sitemap.xml`、既存3ファイルのどれかに対象slugがある場合は、新規制作として進めない。

## 11. ユーザー向け説明

- まず結論を言う。
- 専門用語を使う場合は、ユーザーに必要な意味を短く説明する。
- 「不足があります」だけで止めず、何が、何件、どのファイルで足りないかを言う。
- 要約しすぎて、原因、対象、次の作業が分からなくなる説明は禁止。
- 最終報告には必ず `要約:` を入れる。

## 12. 共有フォルダ正本ルール

- 管理正本は `\\192.168.1.3\disk1\FSG_SEO\candy` 直下に置く。
- `HP/管理体制/` と `HP/管理体制_概要説明書.md` は作らない。
- `HP/AGENTS.md` はHP作業導線にする。`HP/README.md` は作らない。
- 同じ意味の管理MDを外側と内側へ二重管理しない。
- 外側ルート `\\192.168.1.3\disk1\FSG_SEO\candy` がGitHub作業場。`.git` はここに置く。

## 13. HP階層ルール

- HP/HP/ は作らない。
- HP/ 直下は実サイト配下の中身だけにする。
- GitHub作業場は共有フォルダ直下 \\192.168.1.3\disk1\FSG_SEO\candy。
- HP作業の対象パスはGitHub作業場から見て HP/... で固定する。

## 14. 削除・移動・一括操作安全ルール

- 削除、移動、一括整理、Git復旧は `SAFETY_PROTOCOL.md` を正本とする。
- 「ゴミ」「未整理」などの曖昧な表現だけで実行しない。削除可、Git管理から削除、移動済み、Git登録、復旧、保留に分ける。
- `.git/`、`AGENTS.md`、`README.md`、`HP/AGENTS.md`、`HP/index.php`、`管理体制/` は保護対象として扱う。
- 物理削除、Git管理から削除、Stage、Commit、Pushを別操作として報告する。
- Git破損を検知したら復旧操作へ進まず、被害範囲と復旧案を報告して明示承認を待つ。