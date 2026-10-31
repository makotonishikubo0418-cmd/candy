# ブログ店舗名表記統一のGitHub保存と本番公開

- Type: OPERATION
- Start Date: 2026-10-31

## 目的

ローカルで完了した店舗名表記統一をGitHub mainへ保存し、本番公開と必要な反映確認を完了する。

## 対象範囲

[ローカル修正案件](20261031_MODIFY_blog-store-name-katakana.md)の12記事、ブログ一覧・TOP、現行原稿6件、関連生成管理情報・履歴。本番転送はHTML14ファイルに限定する。既存のFAQ警告は[別件](20261031_PROBLEM_short-time-job-faq-seo-warning.md)で管理し、修正しない。

## 完了条件

指定差分の最終確認、mainへのPush、Actions成功、本番HTTP・対象表示内容・SEO・JSON-LD・PC/SP画像表示の確認、公開履歴保存と報告を完了する。

## 初期情報

ユーザーはローカル修正完了を確認し、mainへのPushと本番公開を明示指示した。表記変更は公開HTML249箇所と現行原稿71箇所。英語デザイン表記、URL、画像パス、リンク先、HTML構造、CSS、プログラム識別子は保持する。
