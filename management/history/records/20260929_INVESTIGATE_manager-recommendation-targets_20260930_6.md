# 店長おすすめ サーバー読取調査の承諾と接続前提の不足

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-30
- Sequence: 6
- Status: Waiting for Response

## 記録

### 承諾と確認済み事実

- ユーザーの「承諾する」は、[記録5](20260929_INVESTIGATE_manager-recommendation-targets_20260930_5.md)の本番サーバー読取調査に対する承諾として受領した。対象は本件画像保存先の存在・権限・公開URLとの対応、PHPバージョン・GD/mysqli・画像アップロード上限。ファイル作成・アップロード・変更、権限変更、DB操作、Git状態変更、表示切替は含まない。
- 現在の `management/scripts/Invoke-LiveServerRead.ps1` を確認した。同スクリプトは専用秘密鍵とknown_hostsの存在確認、ホスト鍵指紋照合の後、許可ActionだけをSSHで実行する構成。`Php` はCLI環境の確認であり、管理画面を動かすWeb PHP環境の証明にはならない。
- `-Check Php` を実行したが、ローカル前提確認で `Required file not found: C:\Users\nishi\.ssh\candy_readonly_rsa`、終了コード1となった。SSH接続を開始する前に停止しており、本番PHP情報は取得していない。サーバー障害、SSH認証失敗、サーバー側権限不足を確認した結果ではない。
- 専用known_hostsはローカルに存在したが、秘密鍵不足で指紋照合まで進んでいない。DB読取用鍵は存在するが、用途・許可範囲の異なる鍵への差替えやforced commandの迂回は実施していない。秘密鍵本文は取得・出力していない。
- 既存Candy FTPスクリプトが参照する `FTP_SERVER` / `FTP_USERNAME` / `FTP_PASSWORD` は現在の実行プロセスではすべて未設定だった。値は出力せず有無のみ確認した。FTP接続・GitHub Actions実行はしていない。他PC・他アプリに接続手段がないと判断したものではない。

### 結果と次の確認方法

- 第3段階の画像保存先・実行環境確認は未完了。保存先の存在・権限、`/home/firststar` と `/firststar` の対応、画像公開URLとの実体対応、Web PHPのGD/mysqli・アップロード上限はすべて未確認のまま。
- このPCの既存接続経路では必要情報を取得できないため、ユーザーがPuTTYで `firststar.kir.jp` に接続し、同じ承諾済み範囲の読取コマンドを実行して結果を提供する方法を次の手順とする。対象は接続ユーザー・ホスト、両パス表記のgroup実体、control・upfiles/2・manager_recommendationのstat、およびCLI PHPのバージョンと必要設定項目だけ。ファイルの作成・削除や書込テスト、DB操作は含めない。
- PuTTYのCLI結果を受け取っても、Web PHPと画像URLの実体対応の確認まで完了扱いにしない。既存の正規読取経路で確認できない場合、診断ファイル等の設置は別途具体的な変更許可を得る前に実施しない。
- 本ターンの書込みはこのローカル進捗記録のみ。本番ファイル・DB・表示設定・アプリコード・移行候補JSON・Git状態を変更していない。
- 記録前の読取照合でCandyローカルmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementブランチは双方NOT_PRESENT。同日最大連番5を確認して連番6を使用した。

## 現在

- Remaining Work: [計画記録3](20260929_INVESTIGATE_manager-recommendation-targets_20260930_3.md)の第3段階以降。今回の直接の未完了事項は画像保存先・実行環境確認。初期登録SQL、解除処理の審査・検証、画像と既存12名の移行、本番表示切替はいずれも未実施。
- Next Action: ユーザーによるPuTTY読取結果の提供を待つ。受領後、保存先とCLI環境の事実を整理し、残るWeb実行環境・公開URL対応の確認方法を確定する。同じ読取範囲への承諾は再要求しない。DB実行担当は引き続きユーザーであり、現時点では追加SQLを依頼しない。
