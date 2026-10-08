# トップページのホテル最新15件制御のGitHub・本番公開完了

- History: [20261008_MODIFY_hotel-top-latest-fifteen.md](../20261008_MODIFY_hotel-top-latest-fifteen.md)
- Record Date: 2026-10-08
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- ローカル改修完了後、ユーザーからGitHubへのアップロードと本番公開が明示的に指示された。
- 公開前のローカル `main` とGitHub `main` は `d25414d193050a95e60833aa1d3f1f423d286be0` で一致し、ahead / behind は `0 / 0` だった。
- 公開計画は `HP/source/index.html` 1ファイル、削除0件、名称変更0件だった。

### 対応

- 対象16ファイルをCommit `fcb0dfa9785e4637f1259cad918f31f5e25cff54` として作成し、GitHub `main` へPushした。
- GitHub Actions `CANDY Production Deploy` のRun `37738551603` が、計画、承認値検証、FTP反映、本番入口検証をすべて成功した。
- 正規URL `/` とホテル一覧 `/hotel.php` を直接取得し、トップページとホテル一覧のURL、表示名、順序をローカル正本と比較した。

### 結果

- 本番トップページはHTTP 200で、ホテル情報が最新15件だけになった。
- 本番ホテル一覧は30件を保持し、本番トップ15件は一覧の末尾15件とURL、表示名、順序が完全一致した。
- 本番入口契約は、正規Root、明示的index、HTTP、非www、公開indexability、direct-host noindexの全項目で合格した。
- DB操作は実行していない。PC表示・モバイル表示の画面目視は実行していない。

## 現在

- Remaining Work: None
- Next Action: None
