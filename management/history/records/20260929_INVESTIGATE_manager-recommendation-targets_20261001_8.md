# 店長おすすめ本番Web環境確認成功と一時設定の復元確認

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 8
- Status: Investigation Pending

## 記録

### 実行結果

- ユーザーから修正版Runner version 3の本番実行結果を受領した。診断日時は `2026-09-30T23:28:08+00:00`、日本時間 `2026-10-01 08:28:08 +09:00`。
- `CANDY_SSH_EXIT_CODE=0`、`CANDY_DIAGNOSTIC_HTTP_STATUS=200`、正しい診断JSONを確認した。前回のHTTP 403は今回の承諾済み一時設定によって解消し、Web側の診断を実行できた。
- `CANDY_DIAGNOSTIC_ACCESS_RESTORED`、`CANDY_DIAGNOSTIC_ACCESS_STAGE_REMOVED`、`CANDY_DIAGNOSTIC_REMOVED` の全3件を確認した。今回作成した `.htaccess`、設定作業ファイル、診断PHPが削除され、元のアクセス設定へ戻ったことを確認できた。
- 冒頭にMIB関連警告は残っているが、本実行は上記の正常終了と復元確認まで完了している。警告そのものを解消したとは扱わず、本件外の設定変更も行わない。

### 確認済みWeb環境

- PHP `7.2.12`、SAPI `apache2handler`、読込ini `/usr/local/php7.2/etc/php.ini`、実効UID `67286`。従前のSSH側CGI/FastCGIとは別の実行環境として確認した。
- Web実行パスは `/home/firststar/public_html/group/control/site`。設定ファイルを実行せずに解析した `APP_HOME` は `/home/firststar/public_html/group/`、`UP_DIR` はその下の `upfiles/`。
- `/home/firststar/public_html/group/control` と `/home/firststar/public_html/group/upfiles/2` は存在し、シンボリックリンクではなく、mode `0755`、読取可、Web実行ユーザーの書込権限判定はtrue。
- `/home/firststar/public_html/group/upfiles/2/manager_recommendation` は未作成。ここに対するfalseは未作成パスの判定であり、存在する親フォルダーの書込権限不足とは扱わない。
- Web側では `/firststar/public_html/group/` から始まる3つの対象パスは存在しない。先行するSSH出力での `/firststar/...` と今回Web出力での `/home/firststar/...` を区別し、アプリ側のAPP_HOMEをSSH表記へ変更しない。
- GD読込、JPEG/PNGサポート、`getimagesize`、`imagecreatefromjpeg`、`imagecreatefrompng`、`imagesavealpha`、`imagejpeg`、`imagepng`、`imagedestroy`、`is_uploaded_file`、`fopen`、`random_bytes` はすべてtrue。
- mysqli拡張は読込済み。ただしDBには接続していない。
- `file_uploads=1`、`upload_max_filesize=2000M`、`post_max_size=2000M`、`max_file_uploads=20`、`memory_limit=256M`、`upload_tmp_dir` と `open_basedir` は空文字。Web側の設定上限はアプリ側の写真1件5MiBを下回らない。実際のアップロード成功を確認した結果ではない。

### 確認の限界と現在位置

- `image_write_test_performed=false`、`application_save_test_performed=false`、`db_connection_performed=false`。アプリを起動しない環境診断の成功であり、写真保存・DB保存・一般公開画像URLとの対応を検証したものではない。
- [工程計画](20260929_INVESTIGATE_manager-recommendation-targets_20260930_3.md)の第3段階のうち、PHP/GD等のWeb実行環境、対象パスの存在と権限の読取確認を完了とする。第3段階全体、既存12名の移行、機能の公開は未完了。
- 今回のアシスタント操作は提供結果の読取・照合と本記録の追加のみ。追加の本番操作・アプリ修正・DB操作・Git状態変更は行っていない。
- 記録前照合でCandyのmainはローカル/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチは双方NOT_PRESENT。同日最大連番7を確認し連番8を使用した。

## 現在

- Remaining Work: 本番HPと移行候補の一致、専用画像24点の配置名と公開URL対応、根拠のあるupdated_by、初期登録SQL・確認SQL・復旧手順、非公開/削除解除処理の審査・検証方法。その後、承諾済み範囲を確定して画像・初期データ登録、HP/管理画面連携確認、表示切替、公開後確認を行う。
- Next Action: 成功済みの環境確認を繰り返さず、既存12名の移行準備へ戻る。ローカル候補と現行本番HP・既存公開画像の読取比較を進め、画像配置・SQL実行・検証・復旧の具体的手順を準備する。DB実行は引き続きユーザーが担当する。本結果の受領を追加の本番ファイル配置・DB登録・公開切替の許可と読み替えない。
