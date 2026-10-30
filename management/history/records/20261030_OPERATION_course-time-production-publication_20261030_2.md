# コース時間記事の本番公開・反映確認完了

- History: [20261030_OPERATION_course-time-production-publication.md](../20261030_OPERATION_course-time-production-publication.md)
- Record Date: 2026-10-30
- Sequence: 2
- Status: Completed

## 記録

### 結果

- タイトル修正と生成管理情報をコミット `6d6742febd8929b55c2e81ec9f01ceda672dda13` でGitHub mainへ保存した。生成管理情報は全10文書でCHECK=OK、対象記事はstructure=COMPLETE、seo=OK、images=OK、issues=NONE。
- 比較元 `1b52e2555680a933d5232d8ac8652478b266f615`、修正コミットを対象とする手動preview [41022210673](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41022210673) が成功。対象10ファイル、604,994 bytes、PLAN_TOKEN `48d273642aeec86b8721c26184ffc7f3fd3368a8d8cad3b5e285469ff1ab4511` を確認した。
- 同じ値のdeploy [41022284398](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41022284398) はsuccess。PHP構文・デプロイ処理検査を通過し、全10ファイルの最終SHA-256検証、バックアップ後処理、削除0件、`ENTRY_CONTRACT_OK` を確認した。保護対象index.php・.htaccessは変更していない。
- 公開URL `https://www.55810.com/kagoshima-deliveryhealth-blog-course-time.php` はHTTP 200。title・og:title・H1が完全一致し、公開main要素とJSON-LDが修正コミットのHTMLと一致した。H1・本文は修正前から変更していない。
- TOP・ブログ一覧・サイトマップはHTTP 200で対象URLを各1件保持。公開画像3枚はHTTP 200で、取得バイトのSHA-256がコミットと一致した。
- 本番PC・スマートフォン（計測CSS viewport幅1518px・434px）で全体表示、画像・画像説明、本文、CTAを確認。画像3枚はいずれも正しいcurrentSrc、自然寸法1000×750、読込完了。横方向のはみ出しなし。FAQはPCで1問目、スマートフォンで2問目の展開を確認し、JavaScriptコンソールエラーはなかった。
- 本番確認後、ブラウザーviewport指定を解除した。この完了記録のみを `[skip ci]` コミットで保存し、同じ公開を再実行しない。

## 現在

- Remaining Work: None
- Next Action: None
