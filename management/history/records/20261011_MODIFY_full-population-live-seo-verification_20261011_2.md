# 未使用ページの誤判定訂正と現行ページ検証範囲の確定

- History: [`20261011_MODIFY_full-population-live-seo-verification.md`](../20261011_MODIFY_full-population-live-seo-verification.md)
- Record Date: 2026-10-11
- Sequence: 2
- Status: Completed

## 記録

### 訂正

- 訂正対象ファイル: `20261011_MODIFY_full-population-live-seo-verification_20261011_1.md`
- 誤っていた情報: 未使用の `customers/index.php` を現在の全ページSEO検証に含め、転送応答のrobotsヘッダー不足を本番SEO問題として登録し、`.htaccess` の本番反映を残作業とした。
- 正しい情報: `customers/index.php` は、無効化中の会員機能に属する `INACTIVE_DEVELOPMENT` 経路であり、現在使用中のサイトページではない。現在の全ページSEO検証から除外し、`.htaccess` の本番反映を本案件の残作業にしない。
- 訂正の根拠: `MEMBER_SITE_INTEGRATION_ENABLED` は `false` で、通常の公開導線とサイトマップに当該経路は存在しない。`HP/customers/index.php` 自体もテスト用入口と明記している。ユーザーから、問題は個別SEO設定ではなく管理書が使用状態を管理できていなかったことであるとの訂正を受けた。

### 対応

- `CANDY_OTHER_PAGES_MANAGEMENT.md` のページ管理表へ `Current use` を追加し、使用中ページ、使用中補助経路、未使用の開発経路を区別した。
- `INACTIVE_DEVELOPMENT` は現在のサイトページではなく、ファイル存在、直接URLアクセス、構造完成状態だけでは使用中に変更されない規則を追加した。
- `candy_release_check.py --full-seo` から未使用の会員経路を除外し、使用中のサイトマップページ、公開中プロフィール、使用中補助経路だけを検証するよう訂正した。
- 未使用ページの誤判定から作成した `CUSTOMERS-ROBOTS-HEADER` を `CANDY_FIX_BACKLOG.md` から削除した。
- 回帰試験は、未使用経路を要求せず、未分類の新しい非サイトマップPHPは問題として検出することを確認する内容へ訂正した。

### 結果

- 公開SEO検証は未使用の `customers/` 経路を問題として報告しなくなった。
- `python -B .github/scripts/test_candy_release_check.py`、`python -B .github/scripts/candy_release_check.py --full-seo`、`python -B .github/scripts/test_candy_site_state_metadata.py`、`management\scripts\candy-site-state.cmd check`、`git diff --check` は成功した。
- Git操作、本番反映、DB操作は行っていない。

## 現在

- Remaining Work: None
- Next Action: None
