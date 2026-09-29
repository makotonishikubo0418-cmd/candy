# HP以外の追加変更をGitHubへ反映

- History: [20260929_OPERATION_non-hp-local-authoritative-sync.md](../20260929_OPERATION_non-hp-local-authoritative-sync.md)
- Record Date: 2026-09-29
- Sequence: 3
- Status: In Progress

## 記録

### 確認済み事実

- ユーザーから、GitHubへHP以外をアップする明示指示を受けた。対象は現在のローカル変更のうち `HP/` 以外であり、HP、DB、本番デプロイは対象外。
- 対象リポジトリは `C:\Codex\FSG\Candy` の1件。現在ブランチは `main`、ローカルHEADとGitHub `main` はともに `b1c8b3fc1190c7119aaa55fd84367aee3bd52c92` で、先行・遅延は0/0。
- ローカルブランチは `main` のみ。GitHubには `main` と対象外の `feature/member-loyalty-mypage` が存在する。ブランチの作成・切替・Pull・Merge・Rebaseは行わない。
- 未コミット変更はHP以外に存在し、`HP/` 配下の差分と既存ステージは0件。

### 対応

- `git diff --check` はエラーなし。改行変換予定の警告はあるが、空白エラーは検出されていない。
- `management/scripts/test_candy_tooling_recovery.py` は22件すべて合格。
- `management/scripts/candy-site-state.cmd check` は `CHECK=OK documents=10`。内容指紋は `208d394fa77d24f6315afdabacb78cd87ffe4611e79dcaedb9f33837b6f40ad9`。

## 現在

- Remaining Work: HP以外の明示パスをステージし、差分とHP除外を再確認してCommit・Push・GitHub SHA照合を完了する。
- Next Action: `git add -- <明示パス>` で対象を限定し、ステージ内容を検証する。
