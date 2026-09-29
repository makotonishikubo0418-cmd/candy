# 実対象2件での画像初回設置とページ生成確認

- History: [20260920_PROBLEM_hotel-accepted-image-gap.md](../20260920_PROBLEM_hotel-accepted-image-gap.md)
- Record Date: 2026-09-29
- Sequence: 5
- Status: Verification Pending

## 記録

### 確認済み事実

- コンフォートイン鹿児島谷山とホテル サントリーニは、採用元画像のみの `READY_FOR_IMAGE_INSTALLATION` から処理を開始した。

### 対応

- 公開許可フラグを使用せず、ページ作成指示の範囲で各画像ペアを初回ローカル設置した。
- 採用元と公開用コピーの同名SHA-256一致、各ペア内の画像差異、両原稿の `READY_FOR_BUILD`、対象ゲート、ページ生成、専用検査を確認した。

### 結果

- 2件とも `INSTALLED_LOCAL` となり、実対象でのローカル画像設置からページ生成までの経路は合格した。
- Git登録、Actions配信、本番画像バイト確認、ページ公開は実行していない。ローカル結果を公開完了として扱わない。

## 現在

- Remaining Work: 採用元画像のみから自動公開する経路について、画像のGit登録・Actions配信・本番バイト確認・ページ公開までの実環境受入。
- Next Action: 対象ページのGit・本番公開が具体的に指示された場合、画像資産単位とページ単位を分けて順番に検証する。
