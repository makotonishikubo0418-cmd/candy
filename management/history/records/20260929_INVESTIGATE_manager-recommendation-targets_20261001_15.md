# 管理画面先行アップロードの指示とCommit承諾待ち

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-10-01
- Sequence: 15
- Status: Waiting for Response

## 記録

### 指示と対象範囲

- ユーザーはHPより先に管理画面をアップロードできるか確認した。アシスタントだけではSSHパスワード入力を行えず、転送コマンドの実行はユーザーが担当する旨を回答後、「OK 指示しろ」を受領した。管理画面側に限定した転送手順を準備する指示として扱う。
- 今回はHPの変更・公開切替、DB切替、Git状態変更を承諾されたものとして扱わない。まず無効設定のまま管理画面用ファイルを配置する段階とし、有効化や実DB動作確認を配置成功と混同しない。

### 確認済み事実と停止理由

- Controlの対象は既存3ファイル `parts/side_menu.php`、`site/shopmaster2.html`、`site/shopmaster3_candy.html` と、新規10ファイル `site/candy_recommendation_bootstrap.php`、`site/candy_recommendation_config.php`、`site/candy_recommendation_menu.inc.php`、`site/candy_recommendation_profile.inc.php`、`site/candy_recommendation_save.php`、`site/candy_recommendation_service.php`、`site/candy_recommendation_view.php`、`site/candy_recommendations.php`、`site/css/candy_recommendation.css`、`site/js/candy_recommendation.js`。既存3ファイルは未コミット変更、新規10ファイルは未追跡である。
- ローカル `site/candy_recommendation_config.php` は `enabled=false` のまま。アプリコード・機能設定は本ターンで変更していない。他の未コミット管理書等は対象に含めない。
- Controlの `docs/rules/TEST_RELEASE_RULES.md` 第5節は、本番配置の条件として「Target commit matches the deployment artifact」を要求する。対象13ファイルがCommitに含まれておらず、この条件を満たしていない。AGENTS.mdによりGit状態変更には具体的な許可が必要で、今回のアップロード指示からCommit権限を推定しない。
- 必要な追加承諾はControl管理画面の対象13ファイルだけのstageとローカルCommit。Push・ブランチ変更・HP変更・DB操作は含めない。承諾後、対象差分・依存関係・試験・バックアップと復旧を確認した転送手順を用意する。本番転送コマンドはまだ発行していない。
- 本番接続、転送、DB操作、Git状態変更は行っていない。履歴記録前のCandy mainはLocal/GitHubとも `11fdcd1d3f6e53e6800457d06ec7f61d547058d1`、managementブランチは双方NOT_PRESENT、同日最大連番14を確認して15を使用した。

## 現在

- Remaining Work: 管理画面対象13ファイルのCommit承諾、配置物の固定・検証・バックアップ/復旧付き転送手順、ユーザー実行による管理側配置と結果確認。有効化・実DB動作確認・HP公開切替は別途未了。
- Next Action: Controlの対象13ファイルだけをstageしローカルCommitする承諾を受ける。承諾前にGit変更や本番配置を実行しない。
