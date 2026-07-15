# AGENTS.md

## 1. 適用

- `makotonishikubo0418-cmd/candy` 全体の最上位ルール。基準branchは `main`。
- ユーザーの最新の明示指示を最優先する。本ファイルおよび他のプロジェクト文書と矛盾する場合は、ユーザーの最新指示に従う。
- HP作業は `HP/AGENTS.md` も適用する。矛盾時は停止する。
- パス、件数、Git状態は毎回実物で確認し、過去資料の値を流用しない。

## 2. 開始

1. このファイルを読む。
2. HP作業は `HP/AGENTS.md` を読む。
3. 通常area制作は `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` だけを追加で読む。
4. 通常hotel制作は `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` だけを追加で読む。
5. それ以外は `CANDY_MASTER_DOC_INDEX.md` から必要な正本だけを読む。
6. 対象実物と次を確認する。

```powershell
git remote -v
git branch --show-current
git status --short --branch
```

実行前に短く示す。

```text
AGENTS.md check:
- 適用ルール・参照資料
- 作業種別
- 今回やること
- 今回やらないこと
```

## 3. 絶対ルール

- 未実行、未確認、途中を完了と報告しない。
- ローカル変更、Commit、Push、Actions、本番、HTTP、ブラウザを別状態で報告する。
- 指示範囲を拡張しない。無関係な変更を混ぜない。
- dirtyだけで停止せず、対象との重複を確認する。安全に保持できなければ停止する。
- 推測値、秘密値、ログ本文、個人情報を資料・差分・報告へ転記しない。
- `git reset --hard`、`git clean`、force push、無断merge/rebaseを禁止する。
- 固定の作業ファイル数上限を設けない。

明示指示なしに行わない。

- Commit、Push、競合解消
- 本番・DB・noindex/index変更
- ファイルの削除、移動、リネーム
- GitHub Actions手動実行

「全て」「100%」は、母集団、件数、除外、成功、失敗、未確認を集計する。未確認があれば100%と報告しない。

## 4. 「アップしろ」

「アップしろ」は次を一括実行する明示指示である。

1. 今回の対象だけを検証する。通常制作で記録用 `.md` を増やさない。
2. 対象だけをstageし、1回だけCommitする。
3. remoteを再確認し、安全なら `main` へ1回だけPushする。
4. ActionsをAPIで追跡する。
5. 本番HTTPを確認する。
6. 本番URL、Commit URL、Actions URLを報告する。

公開後の記録専用Commit・Pushは禁止する。仕様変更時だけ該当正本を同じCommitへ含める。

通常の1ページ制作開始または「アップしろ」から本番URL報告まで5分以内を目標とする。競合、STOP、外部障害、Actions失敗は停止位置を即時報告する。

## 5. Git

- 新しい作業は、ローカル `HEAD` と `origin/main` の同期状態を確定してから開始する。
- `git fetch origin` 後、`git rev-list --left-right --count HEAD...origin/main` で先行側を確認する。
- GitHubだけが先行している場合、remote変更とローカル変更の重複が0件なら `git pull --ff-only origin main` を行い、更新されたAGENTSを再読する。
- ローカルだけが先行している場合、明示されたアップ指示があれば対象限定でPushし、なければ未同期を報告して新しい作業を開始しない。
- 双方先行、fast-forward不可、競合、対象変更との重複時は停止する。
- 終了前に次を実行する。

```powershell
git status --short
git diff --stat
git diff --check
```

- `git add -A` を使わず対象だけをstageする。
- Push前にremote更新とPush対象を再確認する。
- deploy対象を含む `main` Pushは本番Actionsを起動し得る。

## 6. STOP

- 適用ルール、対象、branch、完了条件を特定できない
- 既存変更を失う、競合する
- 指示外の削除、移動、DB、本番操作が必要
- 必須情報、画像、slug、公開条件を確定できない
- 秘密値・個人情報の転記が必要
- 本番 `index.php` の転送維持条件を確認できない

## 7. 報告

```text
結論:
確認済み:
変更ファイル:
確認用URL:
未確認・未実施:
次に必要な操作:
```

Commit、Push、Actions、本番、ブラウザを実施した場合だけ、対応する確認用URLと証拠を記載する。
