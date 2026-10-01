# HPおすすめ画像の非表示原因と1ファイル修正

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 33
- Status: Waiting for Response

## 記録

### 確認済み事実・原因

- ユーザーがHPアップ完了を報告後、おすすめ画像が非表示になると報告した。アップの成功出力・バックアップ名は未受領だが、公開HPのHTMLから新しい動的カードが稼働していることを確認した。
- 公開HTMLのおすすめ画像には正しい画像URLがあるが `class="nolazy"` がなかった。既存の固定おすすめ画像と表示されるバナーには同クラスがある。
- 公開JS `js/amadare_webapp2.4.php` をトップのReferer付きで取得し、`WAimgLoadAdd` が `nolazy` 以外の画像のaltをtitleへコピーし、`__WAimgLoad` がtitleをsrcへ設定することを確認。新しい画像の説明文altを画像URLとして読み込んでしまう実装漏れだった。画像自体はリンゴPC/SPともHTTP200、image/jpeg、JPEG先頭バイトを確認した。
- ローカルの実レンダラー出力と既存JS `mdrwbpp2.4.js` を組み合わせた試験で、修正前のURL破壊を再現し、修正後はPC/SP幅でsrc・altが保持されることを確認。ブラウザー実機での修正後本番表示はまだ未確認。

### 修正・配置準備

- HPの変更は `includefile/candy_recommendation.php` のimgへ `class="nolazy"` を付ける1行だけ。既存の共通JSは変更しない。修正ファイルSHA256: `a2aca8cca498155692359bace6c93a7f14a4ae73dfe70cedb48ed95fcae1d90d`。
- `Invoke-HpDeployment.ps1 -Run -ImageRepair` を追加。対象は本番 `/firststar/public_html/group/candy/includefile/candy_recommendation.php` のみ。既存5ファイルの既知ハッシュを読取照合し、Web外へ旧ファイルをバックアップ、サーバーPHP構文検査後に原子的に差し替える。現在のON/OFF設定・DB・管理プログラムは変更しない。
- 修正パッケージSHA256: `d4a1e76601fd85ec2a006e7da03d8dc446d5cd6671935b104191acba41687dfe`。転送処理PHPのSHA256: `a2a9c19fda77aba3a309b87f69f71e3307dd9e2029804e3b778b5b00f44e92eb`。
- 失敗時は既知の旧画像レンダラーへ自動復元。未知の後続編集は上書きしない。緊急OFF `-Run -Disable` は継続可能。初回5ファイル版と同一の復元パッケージを再構成して照合し、元のバックアップからの復元も修正後レンダラーを許容する。初回アップ `-Run` 単独は旧不具合版再配置防止のため停止する。
- Git保存・Pushは進捗32のユーザー指示どおり後回し。DB操作は行わない。

### 検証結果・限界

- ローカルPHP8.3で配置・復旧122項目が通過。1ファイル変更、再実行、構文失敗時の無変更、失敗注入時の復元、OFF維持、未知変更の保護、修正後の緊急OFF・初回バックアップ復元を含む。
- PowerShell5.1で4モードの転送PHP構文・バイト復号一致が通過。元の復旧パッケージSHA256 `50e9c659f83865d0c84a27282e9690d1b7f4054ea71ff4a9f6836a74c152cdba` は不変。
- 画像ローダー試験110項目、機能契約288項目、読取分離49項目が通過。契約試験の初回はローカル管理設定OFFに対して誤ってON期待オプションを指定して失敗した。設定は変更せず、実際のOFF設定を確認して正しい引数で再実行し通過した。
- 修正版の本番アップはまだ実行していない。本番PHP7.2の構文検査はユーザー実行時に行う。ローカル試験は本番表示・速度の証明ではない。
- HISTORY第9章: Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大32から33を採番。

## 現在

- Remaining Work: 修正専用コマンドの実行結果・バックアップ名受領、公開HTMLとPC/SPの画像表示確認。既存の実DB同時更新・性能検証、後回しのGit保存・Pushは未完了。
- Next Action: ユーザーが `Invoke-HpDeployment.ps1 -Run -ImageRepair` を実行。SSH終了値0と `CANDY_HP_IMAGE_REPAIR_OK=1` を確認後、公開HPを再読込して画像復旧を確認する。
