# 本番公開と反映確認の完了

- History: [20261028_OPERATION_short-time-job-production-publication.md](../20261028_OPERATION_short-time-job-production-publication.md)
- Record Date: 2026-10-28
- Sequence: 1
- Status: Completed

## 記録

### 対応

- 既存本番反映済み `9fdb63b176cc5f4b668f17362fc90e30da69fb76` と保存済み `2f052ff6da3695ab1cdfe4af7879311934c61eb4` の差分を正式workflowのnormalモードで反映した。
- [preview run 40655439305](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40655439305) が成功。9ファイル、627,586バイト、削除0件、PLAN_TOKEN `fe75ec64b57f25cb60ffc0580e36e3272df809db8fc5bb0bcfe37a1fd32fdead` を確定した。
- [deploy run 40655631776](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40655631776) が成功。事前テスト、PHP構文、計画照合を通過し、9ファイルの転送・SHA-256照合完了、削除0件を確認した。

### 結果

- 本番記事はHTTP 200。応答HTMLは対象コミットのsource HTMLとバイト単位で一致した。
- 画像2枚はHTTP 200、各1000×750。両方とも対象コミットの画像とバイト単位で一致した。
- 本番TOP・ブログ一覧・サイトマップに対象記事が各1件存在。サイトマップ全体も対象コミットとバイト単位で一致した。
- Actionsの共通入口検証で、root 200、title・canonical・H1、public indexable、index.php・HTTP・non-wwwの転送、direct host noindexが成功した。
- 本番ブラウザはPC幅1600、SP幅434（実測CSSピクセル）で確認。横方向のはみ出しなし。スクロール後に画像2枚のcurrentSrc、complete、自然寸法1000×750を確認。FAQの開閉は成功し、本番URLに対するJavaScriptエラーは検出されなかった。
- 記事公開前のローカル表示もPC/SPで確認。一時プレビューサーバーは終了した。
- 事前のFAQ本文・JSON-LDは5件とも質問・回答・順序が一致しており、管理用検証ツールの見出し認識漏れによる誤検知は記事修正の対象としなかった。生成管理書の既存サイズ超過・Updated欠落も別案件として未修正。

## 現在

- Remaining Work: None
- Next Action: None
