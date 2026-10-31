# 短時間バイト記事の報酬表の本番公開

- Type: OPERATION
- Start Date: 2026-10-31

## 目的

ローカル確認済みの基本報酬表をGitHub mainへ保存し、本番へ反映する。

## 対象範囲

[報酬表のローカル修正](20261031_MODIFY_short-time-job-reward-table.md)と関連生成管理情報・履歴。本番転送は `HP/source/kagoshima-deliveryhealth-blog-short-time-job.html` の1ファイル。本文・金額・画像・CTA・リンク先は維持し、FAQの既存SEO検査警告は対象外とする。

## 完了条件

mainへのPush、Actions成功、本番HTTP・記事内容の一致、PC・スマートフォンの報酬表表示を確認し、公開結果の履歴保存と報告を完了する。

## 初期情報

ユーザーはローカル修正の完了を確認したうえで、mainへのPushと本番公開を明示指示した。変更は表用クラス・セルの16px指定・見出し属性・金額3件の太字。表外のHTMLは変更前とバイト単位で一致する。
