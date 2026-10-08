# 未作成ホテル43件の画像86枚の先行配置完了

- History: [20261008_MODIFY_hotel-image-bulk-preinstallation.md](../20261008_MODIFY_hotel-image-bulk-preinstallation.md)
- Record Date: 2026-10-08
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業前監査で、未作成ホテル43件の採用元画像86枚がすべて存在し、公開用画像ディレクトリの対応コピーは0枚だった。
- 86枚はすべてJPEG、1000x750で、同一ホテル内の2枚に同一SHA-256はなかった。
- 対象43件の採用元画像は変更・削除せず保持した。

### 対応

- 43件86枚を採用元から `HP/imgHtml/new_202601/hotel` へ同名でコピーし、全86枚のSHA-256一致を確認した。
- シェラトン鹿児島2枚をコミット `8e1461f407b2b2bec6475106559538993c7fbf25`、シルクイン鹿児島2枚をコミット `351a38a3473fad4d3020329106d25d5e88aea433`、残り41件82枚をコミット `863e4d450b7d7a3205e343c0baa3746012bd8cba` としてGitHubへPushした。
- GitHub Actions `37723437682`、`37723766839`、`37724117875` の成功後、本番画像を検証した。

### 結果

- 全43件86枚はGit管理済みで、本番公開用URLから取得できる。
- 本番86枚はHTTP 200、`image/jpeg`、1000x750、ローカル公開用コピーとのSHA-256一致に合格した。
- ホテル入力監査は、作成可能41件、作成済みまたは登録あり30件、管理用Text 1件となった。
- 次候補2件の `publish-next --count 2 --dry-run` は、画像依存関係を含む事前検査と生成ドライランに合格した。

## 現在

- Remaining Work: None
- Next Action: None
