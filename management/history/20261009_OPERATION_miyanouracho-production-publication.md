# 宮之浦町エリアページのGitHub本番公開

- Type: OPERATION
- Start Date: 2026-10-09

## 目的

ローカル作成済みの宮之浦町エリアページをGitHubの `main` へ登録し、GitHub Actionsによる本番公開と公開後検証を完了する。

## 対象範囲

宮之浦町エリアページのGitコミット、GitHub `main` へのPush、通常の本番デプロイ、Actions結果、本番ページ・画像・一覧・トップページ・サイトマップ・共通入口の確認。

## 完了条件

対象コミットとGitHub `main` のSHAが一致し、本番デプロイActionsが成功し、対象ページと関連する本番URLの検査に合格すること。

## 初期情報

宮之浦町は正式slug `miyanouracho` でローカル作成と公開前検査が完了し、キューは `LOCAL_COMPLETE` だった。
