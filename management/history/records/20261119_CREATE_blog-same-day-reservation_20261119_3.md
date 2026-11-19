# 当日予約ブログ STEP② 画像位置の修正と未反映原因確認

- History: [`20261119_CREATE_blog-same-day-reservation.md`](../20261119_CREATE_blog-same-day-reservation.md)
- Record Date: 2026-11-19
- Sequence: 3
- Status: Waiting for Response

## 記録

### 確認済み事実

- 公開PHP `HP/kagoshima-deliveryhealth-blog-same-day-reservation.php` は `dataset_base.php` 経由で `HP/source/kagoshima-deliveryhealth-blog-same-day-reservation.html` を読み込む。公開表示の本文順は source HTML の順に依存する。
- ローカル source HTML では H3 → figure（画像②+figcaption）→ 本文先頭段落の順になっている（未コミット差分）。
- 本番 `https://www.55810.com/kagoshima-deliveryhealth-blog-same-day-reservation.php` は本文が画像より前の旧順のまま。本番は commit `07b75cb` 時点の内容。
- ローカルプレビューで SP・PC とも DOM 順と画面上の並びを確認した。画像差し替えや本文変更はしていない。

### 結果

- 未反映理由は、source HTML の移動差分が GitHub push / 本番デプロイされていないこと。
- 本番公開はユーザーの別途指示待ち。

## 現在

- Remaining Work: 指示後に本件 source HTML（必要なら履歴）だけを commit・push し、本番反映を確認する。
- Next Action: 本番公開の指示を待つ
