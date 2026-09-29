# 女性プロフィールのボタン・余白改修のGitHub本番公開

- Type: OPERATION
- Start Date: 2026-09-20

## 目的

承認済みの女性プロフィールのボタン配置・SP花柄余白の改修をGitHub mainと本番サイトへ反映する。

## 対象範囲

`HP/css/girls.css` と `HP/source/girls.html` の2ファイル、既存のGitHub Actionsによる通常デプロイ、本番表示確認。

## 完了条件

- 今回の改修2ファイルだけをGitHub mainへ反映する。
- 本番Actionsの成功と対象ファイルのハッシュ一致を確認する。
- 本番プロフィールのPC・SP・境界幅で、ボタンの配置と15pxの間隔、SPの花柄余白20pxを確認する。
- 元の作業フォルダにある別件の変更を保護する。

## 初期情報

[ローカル改修案件](20260920_MODIFY_girls-profile-layout.md)の完了後、ユーザーから「Github本番にアップしてください」と明示指示を受けた。GitHub mainは `bc5a3f9d28f30258966dd6bf75b9f311b61b731a`。元のローカルHEADは `d2e53a6449380fcc8e649b424deeee03bdb761f7` で、HP同期に伴う差分と別件の管理資料整理が未コミットで残っていた。
