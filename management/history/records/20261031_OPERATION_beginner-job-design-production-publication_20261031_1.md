# デザイン修正の本番反映と検証完了

- History: [20261031_OPERATION_beginner-job-design-production-publication.md](../20261031_OPERATION_beginner-job-design-production-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 結果

- 公開対象SHA: be5f3aa92e4a233b5626da281718134426a49557。比較元: 1ac9ac66c03ede430da2a4afc45dc14ea71a4220。
- [preview 41176347094](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41176347094) と [deploy 41176384763](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41176384763) が成功。
- 共通blog.css、全13参照HTML、sitemap.xmlの15ファイル・466,403バイトを転送。削除・名前変更なし。index.php・.htaccess・画像・DBは変更していない。
- PLAN_TOKEN: 97844f40f6fabe288b2f619785f5adeab88e1919c6976a5c767af2c8230717ec。事前検査・PHP lint工程・件数／SHA／トークン検証が成功し、実転送のVERIFIED 15/15とSUCCESS: deployed and SHA256-verified 15 file(s); deleted 0 obsolete file(s)を確認。既存処理の成功条件によりcleanup完了も確認した。
- 本番入口検査は当該deployのENTRY_CONTRACT_OKを使用。root 200、canonical、公開indexability、index.php・HTTP・non-wwwリダイレクト、直接ホストnoindexが合格。重複実行はしていない。
- 本番記事・CSS正規URL・CSS更新キー付きURL・サイトマップはHTTP 200。記事mainとJSON-LDは公開対象と完全一致。CSS両URLのSHA-256は667802dbe8905fc573a6e3473f852ead47501285a6e4a5a511f8d5e65d38aa16でローカルと一致。サイトマップに記事URLが存在する。
- 本番ブラウザーでPC・スマートフォンの描画を確認。PC実測viewport 1518×1000、スマートフォン434×938。表2つは16px、幅はPC836px・スマートフォン約380.9pxで横はみ出しなし。配色変数は#547FA1、CTAは青系グラデーション。
- NEXT STEPと「求人情報を見る」を確認。リンク先はhttps://kyusyu-okinawa.qzin.jp/candy98/?v=officialのまま。CTA文字・ボタンは枠内に収まる。見出しに不要な「導入」はない。
- 画像3枚は両表示幅で読込済み、自然サイズ1000×750。全ページ描画で画像・報酬表・CTAを確認した。通常の部分スクリーンショットが欠ける検証ブラウザー側の取得問題により確認が延びたが、幅設定を再取得し、全ページ画像とDOM計測で確認を完了した。
- 既に確認済みのローカル画面検証・全件SEO検査・生成台帳再生成・追加修正は行わず、本番反映に必要な確認だけを実施した。

## 現在

- Remaining Work: None
- Next Action: None
