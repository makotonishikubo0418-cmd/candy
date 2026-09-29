# 実対象の本番公開で確認した残存する自動化不整合

- History: [20260920_PROBLEM_hotel-accepted-image-gap.md](../20260920_PROBLEM_hotel-accepted-image-gap.md)
- Record Date: 2026-09-29
- Sequence: 6
- Status: Modification Pending

## 記録

### 確認済み事実

- 実対象2件で、採用元画像から初回ローカル設置、画像の独立Git登録、Actions配信、本番バイト一致、ページ公開までの運用経路は完了した。
- `candy_hotel_publish.py` は、サイト状態生成で内容が変わらなかった `CANDY_UPCOMING_AREA_PAGES.tsv` と `CANDY_UPCOMING_BLOG_PAGES.tsv` も変更必須と判定し、両対象でページコミット直前に停止した。停止出力は `unexpected=[]`、`wrong_status=[]`、当該2ファイルだけが `missing` だった。
- 同スクリプトの本番検証はトップページ登録確認に `https://www.55810.com/source/` を取得するが、現行の内部パス契約では `/source/` は404対象であり、実行時にトップページ領域を解析できなかった。

### 結果

- 画像資産経路は実環境で合格した。
- ページ公開は対象ごとのステージ内容、検査、配備計画を確認して手動継続し、本番検証まで完了した。
- 自動公開コマンド単独でページ公開まで完結する条件は未達のため、本案件は完了扱いにしない。

## 現在

- Remaining Work: 内容不変の生成出力を必須変更としないステージ判定への修正、トップページ本番検証先の現行公開契約への整合、回帰テスト追加。
- Next Action: 公開ツールの修正が明示的に指示された場合、当該2点を修正し、自己検査と実対象相当のオフライン検査で再確認する。
