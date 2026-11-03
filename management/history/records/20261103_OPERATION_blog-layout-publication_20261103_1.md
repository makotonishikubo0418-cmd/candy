# ブログ表示修正の本番公開結果

- History: [20261103_OPERATION_blog-layout-publication.md](../20261103_OPERATION_blog-layout-publication.md)
- Record Date: 2026-11-03
- Sequence: 1
- Status: Completed

## 記録

### 対応と結果

- 開始時のローカル差分25ファイルを内容変更せず、`6cd6dfee7e7ae6eebe0632a2899e84c4f379ceef` としてmainへCommit・Pushした。ステージ差分検査は成功。ライブGitHubのmainが同SHAになったことを確認した。
- 本番比較元は `12d91e5e17258dcde97acb34ee7817968af585a8`。対象は共通blog.cssと参照HTML14ファイル、計15ファイル・437861バイト。削除・名前変更なし。index.php・.htaccessは対象外。
- 自動起動の反映が遅れたため、手動preview [41582358730](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41582358730)を実行して成功。その後に自動実行を確認し、待機中の手動deploy [41582436430](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41582436430)を転送開始前にキャンセルした。起動遅延の原因は未確認。
- 自動deploy [41582377769](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41582377769)はsuccess。事前検査、15ファイルの転送後SHA-256照合、バックアップ後処理、公開入口契約が成功。実転送ログは `SUCCESS: deployed and SHA256-verified 15 file(s); deleted 0 obsolete file(s)`。
- 本番blog.cssの通常URLと `?v=478083ec` の両方がHTTP 200、SHA-256 `478083ec3c2090cb871780ab8b1f06ad92ad9b600931e5d54a0b30c6289cb184` でコミット内容と一致。公開13記事のHTTP 200と新CSS参照を確認した。テンプレートは公開PHP確認の対象外。
- 会話ブログの公開main HTMLがローカルと一致し、重複していた導入文は1回のみ。PC/SPの全体画像を目視確認し、見出し・目次・本文・ボルドーの2ボタンCTAを確認した。
- ブラウザー実測幅1422pxと434pxで横はみ出しなし。H1は34px/26px。目次5項目と本文H2が一致し、画像の自然寸法は1000×750。SPのnowrapは全要素1行で、CTAは女の子一覧・料金への既存リンクを保持していた。
- 今回は既存ローカル内容の保存・公開であり、追加の本文修正や全サイト監査は行っていない。

## 現在

- Remaining Work: None（対象25ファイルのGitHub保存と本番公開・表示確認）
- Next Action: 本記録を含む履歴のみをGitHubへ保存し、最終SHA一致と作業ツリーのクリーンを確認して報告する。履歴保存自体の追加履歴は作成しない。
