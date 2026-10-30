# タイトル修正と公開対象の確定

- History: [20261030_OPERATION_course-time-production-publication.md](../20261030_OPERATION_course-time-production-publication.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: In Progress

## 記録

- HTMLのtitleとog:titleだけから「｜鹿児島Candy」を削除し、H1と完全一致させた。逆変換で元のHTMLと完全一致することを検証し、H1・本文・画像・装飾・JSON-LDを変更していない。
- テンプレート検証は `CHECK_OK BODY=EDITED_NOT_REVIEWED`。PHP構文、登録・メタ情報・一覧・TOP・サイトマップを確認。本文の別途検証ではJSON-LD3件を解析し、FAQ3件の可視内容一致、目次とsceneの12件一致、ID重複なし、画像3枚のファイル・alt・nolazyを確認した。
- サイトマップlastmodプレビューは262件、差分0件。本文追加後の生成管理情報を同期する。
- 未公開範囲は比較元 `1b52e2555680a933d5232d8ac8652478b266f615` 以後のHP10ファイル。公開入口PHP、記事用dataset、dataset_base.php、source HTML、画像3件、一覧、TOP、サイトマップで、削除・名前変更はない。
- 既存の未公開範囲を一括公開するため、修正は `[skip ci]` でGitHub保存し、同じ比較元・修正後SHAによる手動preview成功後、その件数・PLAN_TOKENでdeployする。

## 現在

- Remaining Work: 生成管理情報の検証、GitHub保存、手動preview・deploy、本番確認、完了履歴保存。
- Next Action: 検証結果に従って本番公開へ進む。
