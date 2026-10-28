# WSL PHPの導入とWindows管理コマンドからの利用確認

- History: [20261028_OPERATION_local-php-runtime.md](../20261028_OPERATION_local-php-runtime.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- PHP公式Windows版8.5.12-1 NTS x64を取得し、SHA-256 `2a36a2edcb7a5b9252f638fd5e4cf84d7f6e066092654220dc0b63e5cf4e9723` が公式値と一致した。配置先は `%LOCALAPPDATA%\Programs\PHP`。
- 同版の起動はWindows Code Integrityがphp8.dllの署名要件を満たさないとして拒否した。終了コードは `0xC0E90002`。保護の無効化や除外登録は行っていない。このWindows版は利用するPATHに登録していない。
- 既存のWSLディストリビューション `Sugar-PHP-Verify-20261022` はUbuntu 24.04で、PHP未導入だった。

### 対応

- 同WSLにUbuntu公式パッケージの `php-cli` と検証用の `python3-pil` を導入した。PHPは8.3.6、パッケージ版は `8.3.6-0ubuntu0.24.04.13`。
- `%LOCALAPPDATA%\Programs\CandyPHP\php.cmd` と `php-wsl.sh` を作成し、WindowsからWSLの `/usr/bin/php` を呼び出すようにした。Windows絶対パス引数はwslpathで変換し、PHPの終了コードを呼び出し側へ返す。
- ユーザーPATHに同ランチャーのディレクトリを追加した。既存アプリが古いPATHを継承していても管理コマンドが利用できるよう、`candy-python.cmd` に既存PHPが見つからない場合のランチャー検出を追加した。
- Windows側の既存PythonとPHPランチャーで回帰テスト全体を再実行した。

### 結果

- `candy-python.cmd site-state doctor` はPHPとshort_open_tagの構文検査を検出し、`RESULT=READY_LOCAL`。
- `test_candy_tooling_recovery.py` は30件すべて成功。以前停止していたホテル・エリア生成ケースも成功した。
- ユーザーPATHへの登録と、Smart App ControlがOnのままであることを確認した。
- これはローカルPHP CLI環境と回帰テストの結果であり、本番PHP 7.2.12との完全互換性を証明するものではない。
- 履歴作成前のlive GitHub mainは `5c607c84c45c08eb7cedf7a711fe3b263a2a33be`、managementブランチはNOT_PRESENT。同名案件はなく連番1で作成した。Gitの状態変更操作と本番反映は行っていない。

## 現在

- Remaining Work: None
- Next Action: None
