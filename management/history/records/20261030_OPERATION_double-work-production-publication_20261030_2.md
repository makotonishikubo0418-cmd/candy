# 正式テンプレートの再現と本文組み込みの検証

- History: [20261030_OPERATION_double-work-production-publication.md](../20261030_OPERATION_double-work-production-publication.md)
- Record Date: 2026-10-30
- Sequence: 2
- Status: In Progress

## 記録

### 確認済み事実

- ユーザーはGit履歴からの正常テンプレート復元、現在の本文・画像・装飾の保持、必要な修正とmainへのコミット・Push・本番公開を明示許可した。
- 記事単体の正常版は初回作成後に未コミットのまま本文断片へ差し替えられており、記事HTMLのGit履歴は `5208d6d` の本文断片のみ。初回作成時の `604fae587ffc45c0b1da329c6d8a8de87f8079a0` に正式テンプレート `management/template/BLOG_TEMPLATE.md` と生成処理が存在し、現在まで変更されていない。

### 対応・検証

- 上記Git履歴のHTMLコードと当時と同じ生成処理からページ構造を再現し、本文マーカー間へ現在の本文を組み込んだ。元の記事ファイルをGitから直接復元したものではない。
- Markdownコードフェンスと準備中表示を除去。元のタイトル・SEO設定・Article/BreadcrumbList・共通CSS/JS・ヘッダー／フッターを保持し、本文に共通CSS用の7章のsectionと目次を追加した。PHP処理、既存記事、共通CSS、生成・公開スクリプトは変更していない。
- 追加したsectionと目次だけを取り除いた本文が元の本文と完全一致することを検証。本文SHA-256は `ebd4a16b4460d020cc4569f27a571a0b8a94c7d7ee6636c85c087ecc9f7efa15`。文章、画像2枚、figcaption、太字、改行、内部リンクを保持した。
- 専用検証 `CHECK_OK BODY=EDITED_NOT_REVIEWED`、H1は1件、本文画像は重複なし2件、JSON-LDとメタ情報の整合を確認した。PHP構文も合格。
- サイトマップ261件のlastmodは整合し追加変更0件。生成管理情報7ファイルを更新し、正式メタ情報を保持した期待値との全10件比較は `CHECK=OK`。対象は `structure=COMPLETE seo=OK images=OK list=1 sitemap=1 issues=NONE`。
- ローカルのPC・スマートフォン表示で本文、画像説明、横スクロールなしを確認。画像2枚ともcurrentSrcが画像URL、自然寸法1000×750、読込完了、縦横比4:3。確認用サーバーの共通JS取得に必要なReferer条件を合わせた後に再確認した。

### 公開方針

- 最新の本番デプロイ成功はActions `40829949330`、SHA `976ec5b10f1615133b92d49e9f860ef1ff5c0b40`。その後の保存済み未公開分を含め、対象9ファイルを既存の手動preview・deployで反映する。修正コミットは `[skip ci]` で保存し、HTMLだけが先行して自動公開されることを防ぐ。

## 現在

- Remaining Work: コミット・Push、手動preview・deploy、公開HTTP・DOM・画像・入口・リンクの反映確認と完了記録。
- Next Action: 確定SHAと計画件数・PLAN_TOKENを用いて既存公開手順を実行する。
