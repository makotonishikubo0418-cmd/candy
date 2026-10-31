# 2ボタンCTAの本番公開完了

- History: [20261031_OPERATION_beginner-job-two-button-cta-publication.md](../20261031_OPERATION_beginner-job-two-button-cta-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 結果

- 23ファイルを明示してステージし、差分とgit diff --cached --checkを確認。31fbc0f2bad41bd9b44206c4d116c7dfd834a923をmainへコミット・Pushし、ライブGitHubとのSHA一致と作業ツリーがクリーンであることを確認した。
- [自動デプロイ41177403800](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41177403800) が成功。比較元5e070b200c53d00d5f5d98c24c819c8e8a1d2a9cから、blog.cssと参照HTML13件の14ファイル・413,112バイトを公開。削除なし。手動preview・deployは重ねて起動していない。
- PLAN_TOKEN: 65fa680fb68bad6bf8e2c3982859944ad04705ccc79580115ee86ab14752d625。実転送のVERIFIED 14/14、SUCCESS: deployed and SHA256-verified 14 file(s); deleted 0 obsolete file(s)を確認。既存処理の成功条件を使用してcleanup完了を確認した。
- 当該runのENTRY_CONTRACT_OKを使用し、root 200、canonical、公開indexability、index.php・HTTP・non-wwwリダイレクト、直接ホストnoindexを確認した。
- 本番記事とCSS正規URL・更新キー付きURLの3件がHTTP 200。記事main・JSON-LDは公開コミットと完全一致。両CSSのSHA-256はe7caf179c4d094bdea77c7c778e744dd46194fbbaeab6530046c825cf82999daでローカルと一致。
- 本番PC実測1367×900、スマートフォン434×938でCTAの2列配置を確認。PC文字16px・間隔14px・最低高さ60px、スマートフォン文字13px・間隔10px・最低高さ56px。ボタン・ページの横はみ出しなし。通常の部分画像取得が白くなる既知の検証ブラウザー挙動は全ページ画像で補い、両画面の描画を確認した。
- NEXT STEP・角丸カード・#547FA1を維持。「バニラの求人を見る」はhttps://kyusyu-okinawa.qzin.jp/candy98/?v=official、「ガールズヘブンの求人を見る」はhttps://www.girlsheaven-job.net/kagoshima/ma-200/sa-352/newcandy/で、本番DOMの文言とリンク先が一致する。
- 確認済み内容の再修正、全件SEO検査、生成台帳再生成、DB操作は実施していない。

## 現在

- Remaining Work: None
- Next Action: None
