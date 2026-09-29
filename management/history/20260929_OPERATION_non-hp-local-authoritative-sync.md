# HP以外をローカル正本としてGitHubへ同期

- Type: OPERATION
- Start Date: 2026-09-29

## 目的

ユーザーの「HP以外、ローカルを正として、Github側をローカルの状態にしろ」という指示に従い、HP以外のプロジェクトファイルをローカルの構成・内容に合わせてGitHub mainへ反映する。

## 対象範囲

- `AGENTS.md`、`.gitignore`、`.github/`、`management/`、`Text_area_data/`、`Text_blog_data/`、`Text_girl_data/`、`Text_hotel_data/`。
- ローカルに存在しないGitHub側の旧 `codex/` と `docs/rules/GIT_RULES.md` の削除。
- 必要な同期コミット、Push、照合、履歴保存。
- `.git/` の内部ファイルは公開対象に含めない。HPのファイル内容変更、DB操作、本番デプロイは対象外。

## 完了条件

- GitHub mainのHP以外のファイル構成・Git上の内容が、ローカルと一致する。
- HPのGitツリーが同期前後で変わらない。
- 既存のGitHubコミット履歴を保持し、通常のPushで反映する。
- 反映先コミットと照合結果を記録する。

## 初期情報

- 対象リポジトリ: `C:/Codex/FSG/Candy`、リモート `origin` は `https://github.com/makotonishikubo0418-cmd/candy.git`。
- 同期指示前のローカルmainは `d2e53a6449380fcc8e649b424deeee03bdb761f7`、GitHub mainは `a206c67fa3faaff31767b94a5c0eb9167eea9637`。GitHubが16コミット先行していた。
- 事前のGit内容照合で、HPはローカル・GitHubとも1,016ファイルで、内容・存在範囲が一致していた。
- `management/` はローカルに存在するがGit管理対象外であり、前回の店長おすすめの女の子の相談履歴も含まれる。
