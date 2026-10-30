# CTA本番公開と反映確認完了

- History: [20261030_MODIFY_double-work-cta.md](../20261030_MODIFY_double-work-cta.md)
- Record Date: 2026-10-30
- Sequence: 2
- Status: Completed

## 記録

- 修正コミット `e4b5338ad9310138f62f50724bf68d47c72c631f` をGitHub mainへPushした。
- 自動デプロイ [41012623740](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41012623740) はsuccess。公開対象は記事source HTMLの1ファイル、削除0件。最終SHA-256一致とバックアップ後処理を含む `SUCCESS: deployed and SHA256-verified 1 file(s); deleted 0 obsolete file(s)`、共通入口確認 `ENTRY_CONTRACT_OK` を確認した。
- 公開URL `https://www.55810.com/kagoshima-deliveryhealth-blog-double-work.php` はHTTP 200。公開main要素とJSON-LDが修正コミットと完全一致し、CTA1件・H1が1件であることを確認した。
- CTAの指定文章とボタン2件の表示・hrefを本番DOMで確認した。求人情報・待機設備記事のリンク先は両方HTTP 200。
- 本番PC・スマートフォンのCTAを全体スクリーンショットで確認した。計測されたCSS viewport幅は1518px・434px。CTAの重複・横方向のはみ出しなし。表示取得で通常のviewportスクリーンショットが空白になるため、全体撮影に切り替えて確認した。ローカルでの参考記事とのPC・390px比較結果は前記録のとおり。
- 変更前とのバイト比較により既存本文・画像・装飾・SEO設定を保持。公開済み他記事、共通CSS、PHP処理は変更していない。ローカル確認サーバーを停止し、ブラウザーのviewport指定を解除した。
- この完了記録のみを `[skip ci]` コミットで保存する。履歴保存自体の追加履歴は作成しない。

## 現在

- Remaining Work: None
- Next Action: None
