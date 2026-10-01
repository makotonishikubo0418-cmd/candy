# おすすめ一覧の非表示除外・カード操作・登録項目数の変更

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 35
- Status: Verification Pending

## 記録

### 決定

- ユーザーが、非表示設定の女の子を一覧に出さないこと、カード下部を「前へ｜後へ｜更新」にして「更新」から本人のプロフィール更新ページへ移動すること、旧「プロフィール・掲載内容」を不要とすることを指示した。
- 登録状態は旧「登録状況: 専用5項目登録済み」から「登録項目数 : N」へ変更する。Nは専用PC写真・SP写真・タイトル・見出し・本文の登録済み数（0～5）。趣味の未登録通知は別に維持する。

### 対応

- Controlの `site/candy_recommendation_view.php`、`site/css/candy_recommendation.css`、`site/candy_recommendations.php` の3ファイルをローカル修正。
- 一覧描画時に、人物が存在しない、girls_data.statusが1でない、cast_mastが存在しない、cast_mast.statusが1でないカードを除外。選択済み・保存失敗時の再表示でも同じ条件を適用する。共通DB読取条件、プロフィール編集可否、保存時検証、セッション解放・非同期読込等の既存の速度対策は変更しない。
- カードの3操作を一列に配置し、「更新」は既存の本人プロフィール編集URLへリンクする。上下の一覧保存ボタンとドラッグ・前後移動は維持。CSSのキャッシュ識別子を更新。
- 非DB描画テスト `test_list_view.php` を追加。現在仕様はControlの `docs/SCREEN_SPEC.md`、配置状態は `docs/PROJECT_STATUS.md` に反映。

### 結果

- PHP構文検査2ファイル成功。新規描画テスト26項目、既存契約テスト288項目、UIテスト17項目が成功。差分検査で空白エラーなし。
- 本番の認証・DBを読み込まない既存の隔離fixtureをローカルで表示。14名中、非表示1名を除く13カードのみ表示され、登録項目数0・5、全カードの「前へ／後へ／更新」が一列であふれず表示されること、本人編集リンクのclub/cast/girl/gnoを確認した。確認用サーバー・タブは終了した。
- 本番アップ、DB操作、Git保存・Pushは実施していない。今回の3ファイル差分は旧管理修正用のハッシュ固定手順とは一致しないため、旧6ファイル修正コマンドをそのまま再案内しない。
- HISTORY第9章: Candy Local/GitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致、managementは双方NOT_PRESENT。同日最大34から35を採番。Git状態変更なし。

## 現在

- Remaining Work: 今回の一覧変更3ファイルの本番配置と配置後の画面確認。以前からの実DB同時更新・実測性能等の残検証、後回し指示のGit保存・Pushは保持する。
- Next Action: 今回分の本番アップ指示があれば、3ファイル限定のバックアップ・照合・復旧付き手順を準備する。現時点ではローカル修正済み・本番未反映として報告する。
