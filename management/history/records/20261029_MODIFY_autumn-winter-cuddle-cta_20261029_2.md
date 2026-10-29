# 秋冬ブログCTAの保存確認と本番公開

- History: [20261029_MODIFY_autumn-winter-cuddle-cta.md](../20261029_MODIFY_autumn-winter-cuddle-cta.md)
- Record Date: 2026-10-29
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実と決定

- ユーザーから、変更一覧に管理履歴3ファイルしか表示されていないため実ファイルの保存を確認し、CTA修正と管理履歴をコミット・Push・本番公開する指示と明示許可を受けた。
- 対象HTMLにはNEXT STEPと指定の2ボタンが保存済みだった。開始時の実差分はHP11ファイル、生成管理情報6ファイル、履歴関連3ファイルの20件。追加のHTML・CSS修正は不要だった。
- 本番公開を今回の完了条件に追加した。前記録の未公開状態を本記録の結果で更新する。

### 対応と結果

- 公開コミット: `add3e125413c3b6f43bd9f6c1bc504c6eb18cb4d`。mainへPushした。
- [Actions 40827324435](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/40827324435) はsuccess。比較元は `cfa6bf198827a243ae83d31f1fefc2512cd1b55c`。
- 公開対象はblog.css、対象記事HTML、既存ブログ8件のCSS参照更新、正式テンプレートのCSS参照更新。11件・316,973 bytes、削除・改名なし。保護対象index.php・.htaccessは変更なし。
- PLAN_TOKEN: `f0e09708e1d3e31616b78a1c76728db052671fdd0976769b3aee02fdc8659ba9`。事前検査・承認ゲート、11件の最終ファイルSHA-256検証、正常完了時の一時ファイル処理、同一実行の本番入口契約チェックが成功した。対象PHP変更なし。
- [公開記事](https://www.55810.com/kagoshima-deliveryhealth-blog-autumn-winter-cuddle.php) はHTTP 200。CTA HTMLはローカルと一致し、CSS参照は `v=9dc575f4`。通常CSS URL・バージョン付きURLともローカルSHA-256と一致した。
- CTAの女の子一覧（./girls_list.php）・本日の出勤情報（./schedule.php）はHTTP 200。
- 本番ブラウザーでgirl-choiceと比較し、PC・スマートフォンともCTA全要素の文章・リンク・配色・背景・枠線・角丸・影・余白・文字寸法・行間・配置の取得値が一致した。両ボタンは横並び。
- 本番のclientWidth/scrollWidthはPC 1583/1583、スマートフォン417/417で横スクロールなし。本文アクセント#d59c22を維持した。

## 現在

- Remaining Work: なし。
- Next Action: なし。
