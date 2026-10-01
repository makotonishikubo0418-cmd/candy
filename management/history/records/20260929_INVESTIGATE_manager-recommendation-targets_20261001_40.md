# おすすめ一覧のボタン・上部案内・人数表示のデザイン修正

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 40
- Status: Verification Pending

## 記録

### 決定

- ユーザーは添付1のカード内ボタン、添付2の画面上部、添付3の説明文に埋もれた選択人数について、見た目と分かりやすさの修正を指示した。
- 対象は一覧画面の表示。8項目必須・不足時ポップアップ・登録数・公開状態の除外・表示順・更新先・上下の保存ボタンは維持する。前回5ファイル版の実行出力は未受領。添付には登録項目数8が見えるが、全ファイルの配置成功とは扱わない。

### 対応・結果

- カード操作を高さ36px・角丸のボタンへ変更。「前へ」「後へ」は白系、「更新」は濃いピンク・白文字で区別し、キーボードフォーカス表示を追加した。
- 上部をパンくず、CANDY店舗ラベル、見出し、2段階の説明カードに整理。選択人数は独立したピンクの帯に置き、数字を32pxの太字で表示。未保存の説明は数字と分離した。
- CSSは一覧専用クラスを対象とし、既存プロフィールの見た目に適用しない。表示PHP・CSS・JS・入口の4ファイルを修正。保存/DB処理は変更していない。JS先行配置中は旧HTMLの人数表示にも対応する。
- 隔離ローカル画面（架空14名、表示対象13名）で1280px・390px幅を確認。横はみ出しなし、カードボタン高36px、角丸7px、登録済み者のチェックで人数2→3、前へ操作で順序変更を確認した。ダミー画面の全体画像をControlの `codex/project_management/investigation/candy_manager_recommendation/list_design_preview_20261001.png` に保存。画面サイズは通常へ戻し、確認用タブ・サーバーは終了した。本番UI/DBは操作していない。
- 必須判定141、一覧42、JavaScript35、プロフィール構造10、配置/復元153の計381項目が通過。対象PHP構文、アップ/復元2モードのPowerShell送信コード全体の構文・バイト往復、固定パッケージ照合も通過。
- 新規パッケージは `admin_list_design_20261001.json`、リリース `worktree-20261001-list-design-v4`、5ファイル計42,362 bytes。パッケージSHA-256 `8aace5ba2209287cf541e837409f6f93bb049709ab76f3d7d582995631d1fc47`。前回の8項目判定serviceを同梱し、元版・一覧版・角丸版・8項目版からの配置と、その直前版への復元を検証した。以前の固定パッケージは変更しない。
- 実行スクリプトは同ディレクトリの `Invoke-AdminListDesignDeployment.ps1`。公開領域外の `/firststar/candy_admin_design_日時_乱数` へバックアップし、既知ハッシュ照合・構文検査・配置後照合・途中失敗時の自動復元・逆順の手動復元を維持する。HP・DB・機能ON/OFF・アクセス設定は変更しない。
- `SCREEN_SPEC.md` と `PROJECT_STATUS.md` に反映。Git保存/Push、固定Commit監査、Scope Manifest更新は未実施。HISTORY第9章の確認ではCandy local/GitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大39から40を採番。

## 現在

- Remaining Work: 新デザイン版の本番配置、実行結果とバックアップ先の受領、本番画面確認。既存の残検証・Git保存/Push保留は継続。
- Next Action: `Invoke-AdminListDesignDeployment.ps1 -Run` を修正説明と同時に案内。成功表示は `CANDY_DESIGN_DEPLOYMENT_OK=5`。不具合時は同じスクリプトに `-Run -RestoreBackupName candy_admin_design_日時_乱数` を指定して直前のファイルへ戻す。
