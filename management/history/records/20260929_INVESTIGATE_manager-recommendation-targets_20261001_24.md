# 既存画面を待たせるおすすめ処理を分離し、全経路のローカル修正を実施

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 24
- Status: Verification Pending

## 記録

### 指示・範囲

- ユーザーから「全体を把握の上最適に改善、修正しろ」と明示指示を受領し、おすすめの一覧・個人編集・保存・写真・並べ替え・自動解除・HP表示の関連経路を対象に、ローカル修正と非DB試験を実施した。
- 本番停止を解除する指示ではない。本番転送・有効化、Git状態変更、実DBへの接続・SQL実行は行っていない。管理/HPのローカル設定もOFFを保持。追加DDLなし。

### 確認済み事実・設計上の問題

- 旧個人フォームは既存プロフィールPHPの表示途中で、全CANDY人物・全おすすめ内容・各人物の相関画像検索を同期実行していた。同じ全件取得が個人内容保存と選択保存にも流用されていた。新規処理の接続・応答待ちの上限もなかった。
- 新規一覧/保存のPHPは、セッションを保持したままDB・画像処理を行っていた。既存プロフィール本体もsession_startから表示終了まで明示解放しない構造だった。個人フォームに全件処理を入れた設計と、本番相当の性能確認を欠いた有効化判断が不十分だった。
- 本番のSQL別実行時間・検索計画・セッション待ち・DBロック待ちの内訳は未計測。機能OFFで復旧した事実と、上記の実装欠陥を、特定SQLが何秒を占めたという測定結果に置き換えない。

### 修正内容

| 経路 | ローカル修正 |
|---|---|
| 既存プロフィールへの組込み | include内のDB接続・検索を撤去。従来指定位置へ読込欄を置き、おすすめフォームだけを別リクエストで取得。失敗時は別画面リンクを保持し、旧プロフィール/動画処理を実行し続ける。 |
| 個人フォーム・内容保存 | girls_idを明示した本人だけの取得。girls_imagesへアクセスしない。GETのcastも検証。 |
| 掲載対象保存 | 送信された掲載対象IDだけを検証。0名では設定の版確認以外の旧表読取を行わない。 |
| 一覧 | 写真を人物ごとの相関検索から、club=2・type=2・status=1の最新IDを一括集計する検索へ変更。人物/キャスト対応は維持。カード順を組む処理の繰返し全配列検索も撤去。 |
| ログインセッション | 新規GET/POSTは認証・必要値確保後、DB/画像処理前に解放。POST結果の通知のみ短く再開し、別タブのログアウト後にログイン情報を復元しない。DB読取失敗時は送信内容の通知を保持。 |
| 並べ替え保存 | 設定行ロック→専用表更新→版更新→COMMITの順を保持。1人ずつの更新を200件単位のバインド付きUPDATEへ変更。人数上限は追加せず、件数不一致/途中失敗では全体ROLLBACKを要求。旧MyISAM表をロック後に読まない。 |
| 待機時間 | 新規専用接続の接続待ち3秒・応答待ち5秒、管理側専用接続のInnoDBロック待ち3秒。設定不可なら新機能だけを失敗扱い。ブラウザーの追加フォーム取得は15秒で打切り、自動再試行しない。 |
| HP表示 | 共通の既存DB接続を変更せず、おすすめ読取だけを独立した待機上限付き接続へ分離。掲載順・公開可否・必須5項目・HTML/JSON-LD同一配列・0名時全非表示は保持。 |

- Control変更: `site/candy_recommendation_service.php`、`site/candy_recommendation_profile.inc.php`、`site/candy_recommendations.php`、`site/candy_recommendation_save.php`、`site/candy_recommendation_view.php`、`site/js/candy_recommendation.js`。
- Candy変更: `HP/includefile/candy_recommendation.php`、`HP/includefile/dataset_index.php`。既存の未Commit差分は保持した。
- 既存プロフィール/通常順/動画の保存PHP、他店舗、Work API、CTI、解除トリガーの定義、既存画像は変更していない。非公開/削除後の再選択必須、趣味不足の登録通知（趣味だけを理由とする保存禁止は追加しない）は保持。
- [PHP公式の接続オプション](https://www.php.net/manual/en/mysqli.options.php)と[セッション解放](https://www.php.net/manual/en/function.session-write-close.php)を確認。応答タイムアウトはサーバー上のSQL強制停止を保証するものではなく、全リクエストの総時間保証でもない。ネイティブドライバーでの確認は残る。

### 検証結果

- PHP 8.3.32で対象PHP7ファイルの構文検査、JavaScript構文検査、差分空白検査を通過。PHP 7.2上での実行試験ではない。
- Control `codex/project_management/investigation/candy_manager_recommendation/` の非DB試験: `test_contract.php` 287項目、`test_save_mock.php` 40項目、`test_read_scope.php` 49項目、`test_endpoint_probe.php` 10ケース31項目、`test_ui.js` 17項目。合計424項目を通過。
- 1,000名の模擬一覧でも取得命令は5回、個人/選択保存は画像検索なし。401名の並べ替えは3バッチで順番を維持し、2バッチ目失敗時のROLLBACK要求を確認。これは命令数・制御の試験であり、MySQLの検索時間/原子性の実証ではない。
- `test_release_sql.py` 14テストを通過。非公開→再公開、削除/再登録、他店舗除外等の既存トリガー条件モデルと保存SQLの契約を確認。実トリガーは起動していない。
- computer-useスキルに従う127.0.0.1のダミー画面では、フォーム正常取得、HTTP503、17秒遅延に対するタイムアウト、遅延中の既存入力・ボタン操作、チェック/前後移動/ドラッグを確認。下の更新から `[3,2,1]` の送信を隔離受信先で確認。DB保存なし。
- 全員解除のブラウザー確認ダイアログがユーザー画面に出てしまった。ユーザーから指摘を受けて説明し、ユーザーが閉じた。以後は画面を出さないJS模擬試験へ切替え、確認取消/承認/空配列送信を検証した。ブラウザーでの0名送信完了とは記録しない。テストサーバーは停止済み。
- 画面証跡: Control `codex/project_management/investigation/candy_manager_recommendation/repair_timeout_preview_20261001.png`。本番画面ではない。
- HISTORY.md第9章の照合: Candy mainのLocal/GitHubは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致。managementは双方NOT_PRESENT。同日最大23を確認し24を採番。Controlの既存未Push3Commit・他の作業差分は変更していない。

## 現在

- Remaining Work: 本番の索引/行数規模/検索計画の読取確認、PHP 7.2とMySQL 5.6相当の実DBによる速度・混在engine同時更新・画像保存/エラーの試験。修正版の固定差分確認と必要な監査・Git承認・転送承認・限定有効化・本番回帰。HP公開切替も未実施。
- Next Action: 本番OFFを保持。必要なDB操作の対象/範囲を明示して個別許可を得る。旧13ファイル転送/有効化パッケージは旧版のままであり、修正版の配布に再使用しない。非DB試験成功だけで本番を再開しない。
