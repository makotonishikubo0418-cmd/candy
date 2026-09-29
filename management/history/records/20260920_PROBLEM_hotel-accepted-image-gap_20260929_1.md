# ホテル採用元画像経路の不足を現行実装で再確認

- History: [20260920_PROBLEM_hotel-accepted-image-gap.md](../20260920_PROBLEM_hotel-accepted-image-gap.md)
- Record Date: 2026-09-29
- Sequence: 1
- Status: Modification Pending

## 記録

### 確認済み事実

- ホテル・エリア制作ツールの稼働監査で、`candy-hotel.cmd audit-inputs` が72原稿を既存23件、画像なし48件、管理用1件に分類した。
- 画像なしとされた48件すべてで、原稿が指定するファイル名の画像2枚が `Text_hotel_data/画像データ/` に存在し、いずれも空ファイルではなかった。公開側 `HP/imgHtml/new_202601/hotel/` の同名ペアはない。保管元の存在確認であり、画像内容の再審査や本番配信確認ではない。
- [candy_hotel_target_gate.py](../../scripts/candy_hotel_target_gate.py) の `check_candidate` は `HP` 側の画像だけを確認し、保管元ペアを制作可能入力として扱わない。
- [ホテル仕様](../../specs/CANDY_HOTEL_PAGE_GENERATION_SPEC.md) の現在の記述は、完全な採用元ペアも画像が利用可能な状態として扱い、公開コピーだけがない場合を画像不足扱いしないことを要求している。
- したがって、前回記録では未確認だった候補選定段階の不足が、今回の実装と入力で再現した。未公開48件が直ちに全件制作可能と判定されたわけではない。

### 対応と結果

読み取り専用で候補分類と画像ファイルの存在を照合した。画像設置、コード変更、公開は行っていない。監査全体の実行環境と未確認範囲は [稼働監査記録](20260929_INVESTIGATE_area-hotel-tooling-audit_20260929_1.md) を参照。

## 現在

- Remaining Work: 保管元ペアを考慮する候補判定、初回設置・同名照合・画像登録・公開と本番バイト確認を含む経路の不足解消。候補判定より先の公開工程は今回未実行。
- Next Action: 修正指示を得た範囲で対応し、保管元のみ・公開コピーあり・同名不一致の各状態を検証する。
