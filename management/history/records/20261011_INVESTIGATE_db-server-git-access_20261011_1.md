# サーバー・Gitの参照確認とDB接続確認の許可待ち

- History: [20261011_INVESTIGATE_db-server-git-access.md](../20261011_INVESTIGATE_db-server-git-access.md)
- Record Date: 2026-10-11
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

- 対象配下のGitリポジトリは `C:\Codex\FSG\Candy` の1件。調査開始時のブランチは `main`、upstreamは `origin/main`、未コミット変更なし。
- ローカルブランチは `main` のみ。HEADは `2c3e4a3307a822f5c48783f2e52ee0da5843e7a2`。
- originのfetch/push URLは `https://github.com/makotonishikubo0418-cmd/candy.git`。`git ls-remote --heads origin` は成功した。
- GitHubの `main` は `714e2c8bbb8cec1ec70dbaadfb5adbfce16df16b`、`feature/member-loyalty-mypage` は `933bb909c54f6fdb688dea69a2b9e505606bd0c0`。mainのSHAはローカルと不一致。featureブランチはローカルに存在しない。
- GitHub mainのコミットオブジェクトはローカルに存在せず、`git cat-file -e` は `fatal: Not a valid object name 714e2c8bbb8cec1ec70dbaadfb5adbfce16df16b^{commit}` を返した。ahead/behind件数および進行方向はUNVERIFIED。
- `management` ブランチはローカル・GitHubともNOT_PRESENT。確認対象は既存のmainとし、ブランチを変更していない。
- 専用known_hostsのRSA fingerprintはSERVER_MANAGEMENT.mdの管理値と一致した。
- Windows PowerShellの `-File` 実行は実行ポリシーで停止した。ポリシーを変更せず、管理書で許可されたSSH経路からサーバー専用鍵・厳格なホスト鍵照合を使用して固定Action `SelfTest` を実行した。
- サーバー応答は `CANDY_SERVER_READONLY_OK`、`o4042s-134.kagoya.net`、`uid=67286(firststar) gid=101(kirusr)`、`ROOT_EXISTS=YES`。終了コード0。
- DB用Launcher、OpenSSH実行ファイル、DB専用秘密鍵、専用known_hostsの存在を確認した。秘密鍵内容は取得していない。
- GitHub mainのHISTORY_LIST.mdを参照し、同じ目的の既存案件が登録されていないことを確認した。

### 決定

- DBの実接続確認は、AGENTS.md第4章に従って `Invoke-LiveDbRead.ps1 -SelfTest` の具体的な実行許可を確認する。このActionは内部でselftestとstatusを実行し、DB名と接続ユーザーを照合する。
- DB接続確認の許可と、鍵・設定の存在確認までとする選択肢をユーザーへ提示した。

### 結果

サーバーのREAD-ONLY接続およびGitのローカル・GitHub参照は実証済み。DB実接続は未実行・未確認。業務処理の正常動作、本番DBの内容、Gitの同期・更新は今回実証していない。

## 現在

- Remaining Work: DBのREAD-ONLY接続確認の実行許可、または鍵・設定確認までとする回答。
- Next Action: 許可された場合は既存LauncherのSelfTestを実行し、結果を追記する。確認範囲を限定する回答の場合は、その決定を記録する。
