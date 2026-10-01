# プロフィール左メニューのおすすめリンク欠落を確認

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 42
- Status: Investigating

## 記録

### 確認済み事実

- ユーザーはCANDYプロフィールの左メニューから「店長おすすめ」のリンクが消えると画像を提示し、確認を指示した。画像のパンくずはCANDYで、店舗マスタメニューの先頭は新着情報管理になっている。
- ローカル `control/site/shopmaster3.php` はclub_id=2で `shopmaster3_candy.html` を選択する。Candy用テンプレートの左メニュー（347～359行）は独立したHTMLであり、共通 `parts/side_menu.php` のメニューではない。
- 一覧用 `site/shopmaster2.html` の197行には `candy_recommendation_menu.inc.php` の読込がある一方、プロフィール用 `site/shopmaster3_candy.html` の同メニューにはない。おすすめ編集フォームの読込だけは存在する。リンクがない状態は店舗選択の操作ではなく、プロフィール用メニューへの追加漏れで説明できる。
- 本番に接続してHTML/サーバーファイルを取得してはいない。画像とローカル実装の照合結果であり、全本番ファイルの一致を確認したものではない。

### 対応・結果

- 確認依頼のためアプリケーション修正・アップ・DB操作・Git状態変更は行っていない。修正対象はプロフィール用テンプレートの店舗マスタメニュー。既存のCANDY/権限/有効設定を確認する共通メニュー部品を、一覧と同様に読み込む必要がある。
- 履歴採番前の読取比較ではCandy local/GitHub mainが `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致し、managementブランチは双方なし。同日最大41から42を採番。

## 現在

- Remaining Work: プロフィール用左メニューの追加漏れの修正・検証・アップ用差分準備・本番確認。前回のプロフィールデザイン版の実行出力は未受領。
- Next Action: 原因と修正箇所をユーザーへ報告する。実装修正は別途の修正指示に従う。
