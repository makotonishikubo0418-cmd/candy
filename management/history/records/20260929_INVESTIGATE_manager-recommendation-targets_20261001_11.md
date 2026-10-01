# 店長おすすめ本番配置・登録承諾と画像転送手順の準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 11
- Status: Waiting for Response

## 記録

### 承諾範囲

- ユーザーの「承諾」により、[記録10](20260929_INVESTIGATE_manager-recommendation-targets_20261001_10.md)および直前回答で特定した、写真24枚の専用配置・既存12名の初期登録・非公開/削除時の自動解除処理追加が承諾された。同じ対象・範囲・影響について再承諾を求めない。
- 公開表示の切替・機能有効化・migration_ready=1・Git状態変更は今回の承諾に含まれない。DBのSQL実行はユーザー、SQL準備・審査・結果照合はアシスタントという担当も維持する。

### 今回準備した画像配置手順

- [Invoke-ImagePlacement.ps1](../../../../control/codex/project_management/investigation/candy_manager_recommendation/Invoke-ImagePlacement.ps1)と[place_recommendation_images.php](../../../../control/codex/project_management/investigation/candy_manager_recommendation/place_recommendation_images.php)を追加した。既存の環境診断は再実行せず、承諾済み24JPEGの配置専用とする。
- 送信元は `migration_20261001/manifest.json` と同フォルダーの `images/`。24点・合計351,016 bytes・個別SHA256をローカルで確認し、ASCII/base64のPHP入力としてSSHへ送る。サーバー側へPHPファイルや認証情報を設置しない。
- 宛先は固定のSSHホスト `firststar.kir.jp`、ユーザーfirststar、サーバー上の `/firststar/public_html/group/upfiles/2/manager_recommendation/`。既知RSA指紋と実行先hostname/accountを検証する。SSHパスワードは利用者の対話入力であり、保存・取得・ログ出力しない。アシスタント側には対話パスワード入力手段がないため、この実行のみユーザーに依頼する。
- 親フォルダー、専用フォルダー、対象画像の経路・型を検証し、マニフェスト全体と既存対象ファイルの照合が完了してから作成する。新規フォルダー0755、新規写真はumask0022と排他作成を使用。既存画像は同じハッシュなら再使用し、異なる内容・シンボリックリンク・不正名なら停止する。既存ファイルの上書き・既存フォルダーの権限変更はしない。
- 実行中の書込失敗時に削除できるのは、その実行で新規作成し、inode/devと書込済み部分のハッシュが一致する未完成ファイルだけ。先に作成・検証済みの写真や既存データは残す。プロセス強制終了等で未完ファイルが残った場合の自動復旧は保証しない。
- 転送後、ユーザーPCから `https://image.can-diary.com/2/manager_recommendation/` の対象24URLをHTTPS取得し、HTTP200・サイズ・SHA256を照合する。TLS検証を無効にせず、リダイレクトは拒否する。画像URLに失敗した場合は「転送済みだが公開確認未完了」と区別し、再アップロードを指示しない。
- 成功条件はSSH exit0、サーバー上の24点ハッシュ確認、公開URLの24点ハッシュ確認、最後の `CANDY_IMAGE_DEPLOYMENT_OK=24`。DB接続・アプリやアクセス設定の変更・公開切替は行わない。単独の転送成功を機能公開完了と扱わない。

### 検証結果と残るDB審査

- Windows PowerShell 5.1の `-Run` なしでローカル24点検証が成功。接続・転送なし。PowerShell構文エラー0、PHP 8.3構文検査成功。
- [test_image_placement.php](../../../../control/codex/project_management/investigation/candy_manager_recommendation/test_image_placement.php)のローカル37検証成功。24点の新規配置・個別ハッシュ・同一ファイル再使用・異なる既存ファイルの保全・不正名・ハッシュ不正・同名重複・23件・非JPEG・フォルダー位置に既存ファイルがある場合を確認した。生成したテスト用一時フォルダーだけを実行終了時に削除し、元写真・移行パッケージは変更していない。
- 既存の移行準備オフライン試験9件も再実行して成功。実SSH転送・サーバー上の書込・新画像URLの取得は未実施である。
- DB側はまだ実行用SQLに仕上がっていない。旧 `002_create.sql` は既に作成済みの2表も含むため再実行しない。審査ではINSERTのBEFOREトリガーが自動採番後のNEW.idを取得できない点を確認した。INSERT時の解除は採番後の処理とID再利用を扱う必要がある。参考: [MySQL 5.6公式マニュアル](https://downloads.mysql.com/docs/refman-5.6-en.a4.pdf)。混在engineの失敗/ロールバック、ID・所属変更、同時保存、適用前後検証、COMMIT後復旧を含めて仕上げるまでは旧案やDRAFTを実行指示にしない。
- 記録前のCandy mainはLocal/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`。managementブランチは双方NOT_PRESENT、同日最大連番10を確認し連番11を追加。Git状態変更なし。

## 現在

- Remaining Work: ユーザー実行による24画像の配置・公開ハッシュ照合、その結果受領。承諾済みDB自動解除/初期登録の実行用SQL・検証・復旧を完成させ、ユーザーによる実行結果を照合する。その後のHP/管理画面連携と公開切替は別工程。
- Next Action: ユーザーのPowerShellから `Invoke-ImagePlacement.ps1 -Run` を1回実行し、結果を提示してもらう。これは診断の再実行ではなく写真の本番配置である。配置成功後にDB操作へ進み、未確認の画像URLのまま登録や有効化を急がない。同範囲の承諾を再度求めない。
