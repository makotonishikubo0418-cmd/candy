# 全ページ本番SEO検証の実装と初回実測

- History: [`20261011_MODIFY_full-population-live-seo-verification.md`](../20261011_MODIFY_full-population-live-seo-verification.md)
- Record Date: 2026-10-11
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

- ローカルと本番のサイトマップは各252 URLで一致し、公開 `girls_list.php` から150件の異なるプロフィールURLを取得できた。
- サイトマップ252 URLと動的プロフィール150 URLの生成HTMLについて、HTTP、title、description、robots、canonical、H1、OGP、JSON-LD、残存プレースホルダー、重複title/canonicalを全件確認した。
- 分類済み非サイトマップ9経路、公開入口の正規化リダイレクト、直接確認ホスト、source/includeのアクセス制御も同じ実行で確認した。
- 本番 `/customers/index.php` の `301` 応答だけ、仕様上必要な `X-Robots-Tag: noindex, nofollow` が付いていない。ローカルの `HP/.htaccess` と会員bootstrapには保護規則がある。
- 承認済みREAD-ONLYサーバー確認は、このPCに専用鍵 `candy_readonly_rsa` が存在せず実行できなかった。
- GitHub Actionsの保護対象 `.htaccess` 公開履歴では、最新成功実行が2026-08-17のcommit `61567e7` で、必要なrobots規則はその後の2026-08-19のcommit `9f71a70` で追加されていた。以後の保護対象公開実行はないため、公式公開経路では当該規則が本番未反映である。

### 対応

- 既存の `.github/scripts/candy_release_check.py` に `--full-seo` を追加し、別の重複スクリプトは作成しなかった。
- 全件列挙、生成HTMLのSEO検証、動的プロフィール発見、非サイトマップ分類、異常時の問題のみの出力を回帰試験へ追加した。
- 正規仕様、全件検証計画、公開運用仕様を同じコマンドへ同期した。
- 確定した本番問題を `CANDY_FIX_BACKLOG.md` に登録した。

### 結果

- 全件検証機能と回帰試験は実装済み。
- 初回本番全件検証は `/customers/index.php` のrobots応答ヘッダー1件を問題として終了し、未解決状態を成功へ変換していない。

## 現在

- Remaining Work: 現行 `.htaccess` を正式な保護対象公開経路で本番へ反映し、`--full-seo` の問題0件を確認する。
- Next Action: 保護対象 `.htaccess` の本番反映に対する明示指示を受ける。
