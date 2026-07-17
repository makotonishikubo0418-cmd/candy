# 削除・移動・一括操作安全プロトコル

- 目的: 削除、移動、一括整理、Git修復で作業場を壊さないための実行ルール
- 状態: 正本
- 更新日: 2026-07-17

## 1. 適用範囲

この文書は次の作業に必ず適用する。

- ファイル削除
- Git管理からの削除
- ファイル移動、rename、フォルダ整理
- `Get-ChildItem -Recurse` などの再帰処理
- `git add`、`git rm`、Commit、Push
- `.git` 破損、Git作業場復旧
- 生成物、ログ、キャッシュ、未追跡ファイルの一括整理

## 2. 絶対禁止

次は禁止する。

- 対象リスト未確定の削除、移動、Stage、Commit、Push
- 「ゴミ」「未整理」など曖昧な呼び名のまま実行すること
- `git add .`、`git add -A`、`git clean`、`git reset --hard`
- `.git` 配下を削除候補に含めること
- `Remove-Item` を再帰検索結果へ直接つなぐこと
- root配下かどうかだけで安全判定すること
- 大量出力を出したまま判断を進めること
- ユーザーの質問に答えず、勝手に実行へ進むこと

## 3. 常時保護対象

次は、明示された対象でない限り、削除、移動、Stage対象にしてはいけない。

| 対象 | 理由 |
|---|---|
| `.git/` | Git管理本体 |
| `.git-backups/` | 復旧用退避先 |
| `AGENTS.md` | 共通ルール入口 |
| `codex/README.md` | 管理入口 |
| `codex/project_management/` | プロジェクト管理正本 |
| `codex/docs/` | HP制作仕様の正本 |
| `codex/scripts/` | 生成・検証・公開ツール |
| `HP/AGENTS.md` | HP作業導線 |
| `HP/index.php` | 実サイト入口 |
| `Text_area_data/`、`Text_blog_data/`、`Text_hotel_data/` | ページ制作入力 |
| `Backup/` | 旧データと退避済み確認先 |
| `HP/HP/` | 作ってはいけない重複階層。存在したら停止して報告 |

## 4. 実行前分類

削除、移動、一括整理の前に、必ず対象を次へ分類する。

| 分類 | 意味 | 実行条件 |
|---|---|---|
| 削除可 | 実行時ログ、キャッシュ、明確な不要物 | 対象一覧と件数を提示し、承認後に実行 |
| Git管理から削除 | ファイル実体は不要で、Git履歴から次Commitで外す | `git add -u -- <明示対象>` だけ使う |
| 移動済み | 元位置の削除と新位置の追加が1対1で照合済み | 照合結果が不足0、重複0の時だけStage |
| Git登録 | 管理表、分類結果、必要画像、制作入力など | 用途と保存先を確認してからStage |
| 復旧 | 誤削除、破損、必要ファイル欠落 | GitHub最新Commitまたは退避先から戻す |
| 保留 | 必要性や正本が不明 | 実行せずユーザーへ確認 |

## 5. 実行前チェック

実行前に最低限これを確認する。

1. 作業場が `\\192.168.1.3\disk1\FSG_SEO\candy` であること。
2. `AGENTS.md`、`codex/README.md`、必要な管理文書を読んだ証拠を出すこと。
3. `codex/project_management/TASK_RESERVATIONS.md` で対象を予約すること。
4. Git状態を1回だけ確認すること。
5. 対象リストを固定すること。
6. 対象リストに保護対象が混ざっていないこと。
7. 削除、移動、Commit、Push、本番操作はユーザー明示指示があること。

## 6. Git操作ルール

Git操作は次を守る。

- Stageは対象パスを明示する。
- `git add -u -- <対象>` と `git add -- <対象>` を使い分ける。
- Stage後に `git diff --cached --name-status` で対象外がないことを確認する。
- Commit前に `git diff --cached --check` を必ず通す。
- Commit前に `git status --porcelain=v1 -uall` の残り差分を確認する。
- Push前に `.git/HEAD`、`.git/config`、`.git/index`、`AGENTS.md`、`codex/README.md`、`HP/AGENTS.md`、`HP/index.php` の存在を確認する。
- Push後に `git ls-remote origin refs/heads/main` でGitHub側のCommitを確認する。

## 7. Git破損時の停止条件

次のどれかが発生したら、作業を止める。

- `.git/HEAD` がない
- `.git/config` がない
- `.git/index` がない
- `git status` が `not a git repository` になる
- `HEAD` が不明
- 意図せず `master` になった
- `AGENTS.md`、`codex/README.md`、`HP/AGENTS.md`、`HP/index.php` のどれかが消えた

停止後は、被害範囲、GitHub最新Commit、退避先の有無、復旧案を報告し、明示承認なしに復旧操作をしない。

## 8. Git復旧手順

Git復旧は明示承認後だけ実行する。

1. 壊れた `.git` を `.git-backups/broken_git_日時` へ退避する。
2. `git -c safe.directory='*'` をすべてのGitコマンドに付ける。
3. GitHubの最新Commitを確認する。
4. 作業ツリー本体を壊さない方法でGit管理だけ復旧する。
5. 誤削除された追跡ファイルだけを戻す。
6. `git fsck --no-dangling` を実行する。
7. `git status` が読めることを確認する。
8. 復旧結果を報告する。

## 9. 報告ルール

ユーザーへは、専門用語だけで終わらせない。

- `D` は「GitHubにはあるが今の作業場では消えた扱い」
- `??` は「GitHub未登録」
- `M` は「変更済み」
- `Stage` は「次のCommitに入れる準備」
- `Commit` は「ローカルGitへ保存」
- `Push` は「GitHubへ反映」

問題報告では必ず、何が、何件、どこにあり、次に何をするかを書く。

## 10. 今日の事故からの固定ルール

- 「ゴミは消せ」と言われても、先に分類する。
- `.git` は削除候補検索から必ず除外する。
- 削除対象を画面に大量出力しない。件数と代表例、必要なら保存リストで出す。
- `root配下だから安全` という判断は禁止する。
- 物理削除、Git管理から削除、Stage、Commit、Pushを混同しない。
- 分からない時は、実行せず質問に答える。