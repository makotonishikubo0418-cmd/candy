# 店長おすすめ — 両側のローカル実装と非DB検証

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-29
- Sequence: 7
- Status: Verification Pending

## 記録

### 確認済み事実

- ユーザーの「それなら進行しろよ」に基づき、先に指示されたHP側・管理画面側の実装を進めた。
- 許可済みの `Invoke-LiveDbRead.ps1 -Create` により `girls_data`、`cast_mast`、`girls_images`、`girls_candy_page_content` を確認した。前3表はMyISAM/utf8、最後はInnoDB/utf8。公開番号に一意制約はない。DB書込みはしていない。
- 個人情報を含む表の全列取得は避けたため、人物ID・公開番号・状態の本番全件対応は未確認。専用読取Launcherには列指定・WHEREがない。

### 対応

- Controlに、写真・名前・チェック・登録状況一覧、上下更新、ドラッグ順、独立した内容フォーム、専用POST保存、CSRF・対象・必須項目・版番号検証を追加した。
- 趣味未登録は一覧・個別フォーム・保存結果で登録を通知する。下書きの趣味を公開せず、HPの趣味行そのものは消さない。趣味だけの不足を新しい保存禁止条件にはしていない。
- Candyトップに専用読取・描画部品、JSON-LDの枠、既存変換後のトップ限定挿入を追加した。0名は見出し・バナーを含め非表示。既存の女の子一覧・出勤リンクを維持する。
- 非公開・削除・再登録後の自動復帰を防ぐ専用2表・6トリガーのSQL案を作成した。既存MyISAM更新へ関わるため、実DBでの他店舗・競合・失敗時検証前には適用しない。
- 両側の機能設定はfalse。既存の固定12名表示を維持し、本番データ・本番ファイルを変更していない。
- 詳細な対象・処理・証跡・未確認項目は [Control案件別実装記録](../../../../control/codex/project_management/investigation/candy_manager_recommendation/IMPLEMENTATION.md) と [現在状態](../../../../control/docs/PROJECT_STATUS.md) に記録した。

### 結果

- PHP構文、JavaScript構文、287項目の純粋検証、21項目の保存模擬検証が成功。実DBを使わない結果であり、MySQL上の正しさ・本番無影響の証明ではない。
- computer-useスキルのブラウザー優先手順で、127.0.0.1上のダミー画面を確認。ドラッグ後の下更新は `[3,2,1]`、上更新は `[1,2]` の順序を送信した。390px幅で横にはみ出さない。本番操作なし。
- 固定12カード・既存24画像のローカル抽出検証が成功。DBへの初期移行は未実施。
- Candy生成文書6ファイルを更新。sitemapは144件中変更0件。トップの構造・SEO・参照画像チェックは正常。
- 記録前にGitHubのheadを読み取り、mainはローカルHEADと同じ `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチなし、既存同日連番は6までと確認した。Gitの状態変更・commit・pushは行っていない。

## 現在

- Remaining Work: 承認された列限定経路での本番人物対応・トリガー確認、隔離DBでの作成・保存・競合・解除・復旧・他店舗検証、実画像保存、初期移行、正式監査、Git公開、本番配置・有効化・実HTTP確認。
- Next Action: 隔離DBの接続先を確定し、その環境での専用2表・6トリガー作成と試験データ更新について具体的許可を得て結合試験へ進む。本番のDDL・データ更新・配置は別途の具体的許可前に行わない。
