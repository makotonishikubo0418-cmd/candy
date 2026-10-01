# おすすめ選択の8項目必須化とアップ準備

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 39
- Status: Verification Pending

## 記録

### 決定

- ユーザーはPC/SP写真・タイトル・太文字見出し・本文・趣味・身長・スリーサイズの全8項目を選択の必須条件とし、不足時の選択拒否と指定ポップアップ、登録項目数0～8を指示。続けて一覧の「趣味を登録してください…」の赤文字を不要と指定し、「修正実行」で実作業を指示した。
- スリーサイズはバスト・カップ・ウエスト・ヒップがそろって1項目。身長と各サイズは正の整数、カップは既存A～J（1～10）。趣味は既存仕様どおり公開済み内容を使用。空欄・空白のみ・NULL・数値0は登録済み扱いにしない。
- 不足通知は選択時に `PC版写真 / SP版写真 / タイトル / 太文字見出し / 本文 / 趣味 / 身長 / スリーサイズ / を登録してから選択してください。` を各行に分けて表示する。一覧の趣味注意文だけを削除し、プロフィール側の既存案内や未選択者の途中保存は変更しない。

### 対応・結果

- 管理側の共通判定、一覧描画、JavaScript、入口キャッシュ番号を修正。保存処理が呼ぶ共通判定でも8項目を再確認し、JavaScriptを迂回した不完全な選択保存も拒否する。
- 登録不足の既存選択は一覧上で未選択にし、「更新」を押した時点で掲載対象から外れると案内する。GETでDBを書き換えず、既存HPを勝手に非表示にしない。選択表示の人物は8、その他は実際の0～8を表示する。
- 身長・バスト・カップ・ウエスト・ヒップは既存の対象限定SELECTに追加。問い合わせ数は一覧5回・個別/選択保存4回・全解除2回を維持。セッション早期解放、公開状態・人物対応、写真実在、同時更新、CSRF、200件ごとの保存、選択順、ゼロ人確認を維持する。
- 必須判定141、一覧38、JavaScript34、読取範囲/隔離50、保存模擬40、既存契約288、プロフィール構造10、配置/復元124の計725項目を通過。対象PHP3ファイルの構文検査、PowerShell転送/復元2モードの完全な送信コード構文・バイト往復、固定パッケージ再照合も通過。DB・本番接続はせず、ダミーのみで検証した。実ブラウザーでの新ポップアップ操作は未確認。
- 旧版、3ファイル一覧版、4ファイル角丸版から適用可能な5ファイルパッケージを追加。配置順はservice、CSS、JS、view、入口。復元は逆順で、追加関数を使う描画コードを先に元へ戻す。既知ハッシュ以外のファイルは上書きせず、非対象8ファイルも照合する。
- 固定成果物はControlの `codex/project_management/investigation/candy_manager_recommendation/` 内の `admin_required_fields_20261001.json`、`build_admin_required_fields.py`、`deploy_admin_required_fields.php`、`Invoke-AdminRequiredFieldsDeployment.ps1`、対応する試験。リリース `worktree-20261001-required8-v3`、対象5ファイル計36,066 bytes、パッケージSHA-256 `62209fc8fc0f7afd0b34af765b4cf34282f13fd02887957a418aa875db7dc83a`。旧パッケージは変更しない。
- バックアップは `/firststar/candy_admin_required_日時_乱数`。変更前PHP構文検査、途中失敗時の自動復元、明示したバックアップからの手動復元に対応。HP・DB・機能設定・アクセス設定を変更しない。
- `SCREEN_SPEC.md`、`BUSINESS_RULE_SPEC.md`、`PROJECT_STATUS.md` に仕様と検証境界を反映。Git保存/Pushは引き続き保留、Scope Manifestと固定Commit監査は未更新・未実施。
- HISTORY第9章の確認: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementは双方NOT_PRESENT。同日最大38を確認し39を採番。

## 現在

- Remaining Work: 新5ファイル版の本番配置、成功結果とバックアップ先の受領、本番一覧の0～8表示・赤文字非表示・不足時ポップアップ・選択拒否・保存の確認。既存の同時更新/実測性能検証とGit保存/Push保留は継続。
- Next Action: `Invoke-AdminRequiredFieldsDeployment.ps1 -Run` をユーザーへ案内する。成功表示は `CANDY_REQUIRED_DEPLOYMENT_OK=5`。不具合時は同じスクリプトに `-Run -RestoreBackupName candy_admin_required_日時_乱数` を指定し、その実行時のバックアップへ戻す。本番配置済みとは扱わない。
