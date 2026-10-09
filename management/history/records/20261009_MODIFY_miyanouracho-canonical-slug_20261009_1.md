# 宮之浦町の正式slug確定完了

- History: [20261009_MODIFY_miyanouracho-canonical-slug.md](../20261009_MODIFY_miyanouracho-canonical-slug.md)
- Record Date: 2026-10-09
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 鹿児島市の町名一覧、合併後住所表示、施設所在地では、宮之浦町の公式読みを `みやのうらちょう` としている。日本郵便も `ミヤノウラチョウ` としている。
- 元Textのcanonical、OGP画像、本文画像2件、分類結果のslugはすべて `miyanouracho` で一致する。
- accepted画像2件は `kagoshima-deliveryhealth-area-miyanouracho_1.jpg` と `_2.jpg` で存在する。
- `miyanouramachi` の実ファイルは0件。有効なHP参照も0件で、該当文字列は訂正前のキュー注記にだけ存在した。
- 宮之浦町の公開ページ3ファイルはまだ存在せず、本番公開も未実施である。

### 決定

- 正式slugは、公式読みと既存の正式入力が一致する `miyanouracho` とする。
- `miyanouramachi` は正式slugとして採用しない。

### 対応

- 固定105件キューの宮之浦町を `BLOCKED` から `READY_CANDIDATE` に変更し、確定根拠と現在の競合不存在を記録した。

### 結果

- slug判断待ちは解消した。宮之浦町は、通常制作フローで準備可能な候補となった。
- この対応ではページ作成、Gitコミット・Push、本番公開を実行していない。

## 現在

- Remaining Work: None（正式slug確定とキュー訂正の範囲）
- Next Action: None
