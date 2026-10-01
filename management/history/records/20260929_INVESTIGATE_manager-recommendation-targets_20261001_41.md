# プロフィール内おすすめ入力欄のデザイン整理

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 41
- Status: Verification Pending

## 記録

### 決定・確認済み事実

- ユーザーはプロフィール内「店長おすすめの女の子」の入力部分を、添付2の既存「プロフィール詳細」「セールスポイント」と同様に整理するよう指示した。対象はおすすめ編集部分の表示であり、保存・DB・公開処理の新規変更は行わない。
- 既存 `site/css/style.css` の textarea border:none に対し、おすすめCSSには枠線の指定がなく、入力箇所が白い余白に埋もれていた。現行のCandy追加項目CSSとプロフィールの読み込み順を確認した。

### 対応・結果

- `cr-content-panel` 専用範囲で文字サイズ、枠線、余白を設定。`01 おすすめ写真` にPC/SP写真とアップロード欄、`02 掲載テキスト` にタイトル・太文字見出し・本文をまとめた。写真登録済みはプレビュー、未登録は「写真未登録」を表示する。
- 各テキスト欄は太字ラベル、1pxの明確な枠、15pxの入力文字、右寄せの既存上限（255／1,000／10,000文字）を表示。更新ボタンを右下に配置。幅600px以下では写真カードを縦に並べる。
- 表示PHP・CSS・個人フォーム読込include・独立ページのCSSキャッシュキーの計4ファイルを変更。個人フォームは引き続き非同期読込で、既存プロフィールの応答をDB待ちに戻さない。画像保持、下書き保存、趣味の案内、CSRF、入力名、文字数制限、更新先は維持。
- 既存defalt.css／style.css／shopmaster3_candy_page_content.cssを読み込むループバック限定ダミー画面で検証。600pxのプロフィール領域でPC/SPが2列、入力欄3つは1px枠・15px文字、既存textareaの枠は0pxのまま、写真読込2件正常を確認。390px幅では写真1列・横はみ出しなし。実DB・本番UI・SSHには接続していない。
- プロフィール構造48、必須項目141、一覧42、読取隔離50、保存模擬40、契約288、JavaScript35、配置/復元192の計836項目通過。対象PHP構文、PowerShell転送/復元2モードの全文構文・バイト往復、固定パッケージの再照合も通過。
- プレビュー画像: Control `codex/project_management/investigation/candy_manager_recommendation/profile_design_preview_20261001.png`。写真はダミー。確認用タブとサーバーは終了し、画面幅は通常に戻す。
- 累積6ファイルパッケージ `admin_profile_design_20261001.json`、release `worktree-20261001-profile-design-v5`、アプリ計48,172 bytesを準備。前回承認済み一覧デザインと8項目判定のservice/JSを同梱し、元版・一覧版・角丸版・8項目版・一覧デザイン版から更新可能。既知でない変更は停止し、今回の前に存在した内容へ復元する。以前の固定パッケージは変更していない。
- パッケージSHA-256: `bce7a7033a9c533b0f8f6271e52ecbbe2a36c4713e092764d150117ad4e87b0e`。実行PHP SHA-256: `c11e900cecb4aa33813983d48892c885e60acbd7ec548dd0b2ddd32033f95ed3`。
- 実行手順は同ディレクトリ `Invoke-AdminProfileDesignDeployment.ps1 -Run`。バックアップ `/firststar/candy_admin_profile_日時_乱数`、事前ハッシュ・構文検査、配置後照合、途中失敗時の自動復元を備える。手動復元は同スクリプトに `-Run -RestoreBackupName candy_admin_profile_日時_乱数` を指定。HP・DB・機能ON/OFF・アクセス設定は変更しない。
- SCREEN_SPECとPROJECT_STATUSを更新。履歴採番前の読み取り確認ではCandy local/GitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementブランチは双方なし。同日最大40から41を採番。Git状態変更・Push・Scope Manifest更新・固定Commit監査は未実施。

## 現在

- Remaining Work: 今回分の本番配置と実行結果・バックアップ先の受領、プロフィールの実画面確認。既存の未検証事項とGit保存/Push保留は継続。
- Next Action: `Invoke-AdminProfileDesignDeployment.ps1 -Run` をユーザーへ案内。成功表示は `CANDY_PROFILE_DESIGN_DEPLOYMENT_OK=6`。配置後にプロフィールを再読み込みして写真・文章欄の表示を確認する。
