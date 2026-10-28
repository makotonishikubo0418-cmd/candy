# ブログ名認識の修正と管理台帳の更新

- History: [20261028_PROBLEM_blog-underscore-recognition.md](../20261028_PROBLEM_blog-underscore-recognition.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: Completed

## 記録

### 対応

- ファイル名部分の共通パターンにアンダースコアを含め、ブログ一覧抽出、トップページ行の置換、canonicalの解釈、関連記事リンク抽出、管理台帳のページ分類と原稿URL抽出に適用した。
- 回帰テストを追加し、既存の最新15件テストにもアンダースコアを含む記事を組み込んだ。
- `candy-site-state write` で生成管理書7ファイルを更新した。

### 確認済み事実

- 修正前に回帰テストでcanonical拒否、一覧からの欠落、最新15件の選択誤りを再現した。修正後は対象3テストすべてが成功した。
- 実在するブログPHP13件と、一覧抽出13件と、台帳のブログ詳細13件が一致した。
- 対象記事は `other:kagoshima-deliveryhealth-blog-candy_dominant_girls` から `blog:candy_dominant_girls` に分類され、一覧登録数1件、構造COMPLETE、画像OKを確認した。
- メモリ上のトップページ同期は13件を保持し、繰り返し実行でも同一内容となった。実際のトップページファイルは変更していない。
- 再生成内容と生成管理書10ファイルの内容差分は0件。
- `test_candy_tooling_recovery.py` は30テスト中29件成功。残る既存の生成テスト1件はホテル・エリアの両ケースともPHP実行環境が見つからず `planned PHP lint UNAVAILABLE` で停止した。今回のブログ関連テストに失敗はない。
- `test_candy_site_state_metadata.py` は成功した。

### 結果

- アンダースコアを含むブログの認識漏れ修正と管理台帳更新は完了した。
- サイト全体のcheckには、既存の一覧ページのsitemap更新日差異と、対象記事のsitemap未登録が残る。トップページは既存の12件のままで、一覧との不一致を修正後の処理が検出する。これらは今回の管理ツール修正とは別の公開連携作業である。
- GitのHEADとlive mainは `5c607c84c45c08eb7cedf7a711fe3b263a2a33be`。managementブランチと同名の既存履歴はなく、本件は連番1で作成した。Git状態を変更する操作、公開ファイル編集、DB操作、本番反映は実施していない。

## 現在

- Remaining Work: None（本件の管理ツール修正・台帳更新）
- Next Action: None
