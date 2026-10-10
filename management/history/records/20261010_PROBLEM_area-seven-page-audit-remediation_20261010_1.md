# エリア7ページの監査指摘修正と本番公開 進捗1

- History: [`20261010_PROBLEM_area-seven-page-audit-remediation.md`](../20261010_PROBLEM_area-seven-page-audit-remediation.md)
- Record Date: 2026-10-10
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 荒田、花野光ヶ丘、喜入一倉町は、元TextとソースHTMLの店舗順・移動時間・交通費が一致していなかった。
- 池之上町は元Textの`image`と`img_1`が存在しない非連番画像名を参照していた。
- 入佐町、泉町を含む6件の元Textでは、移動時間の範囲表記がページと一致していなかった。
- 錦江町は店舗一覧の終了`</ul>`が欠けていた。
- 旧形式Textを現行生成器でページ全体へ強制適用するとホテル・スポット区切りを誤解釈するため、全体再生成結果は採用しなかった。

### 対応

- 6件の元Textにある画像名または移動時間表記を修正した。
- 荒田、花野光ヶ丘、喜入一倉町は、店舗一覧と対応するItemList JSON-LDを元Textへ一致させ、花野光ヶ丘と喜入一倉町はtitleと`og:title`も一致させた。
- 錦江町へ欠落していた`</ul>`を追加した。
- サイトマップ`lastmod`と生成管理資料を同期した。

### 結果

- 対象6件の専用チェックとPHP構文検査はすべて合格した。
- 全164エリアページ監査は、修正対象となる`CORE_ISSUE`と`CURRENT_CONTRACT_MISMATCH`が0件になった。141件は`PASS`、元Text未特定または旧形式不備の23件は`INPUT_REVIEW`として分離されている。
- 関連リンク検査は164ソース、703リンクで合格した。
- `candy-site-state check`は10文書で合格した。

## 現在

- Remaining Work: GitHubへのPush、Actions完了確認、対象7ページの本番確認。
- Next Action: 変更対象を限定ステージングし、デプロイ計画を検証して`main`へPushする。
