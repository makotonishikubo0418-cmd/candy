# CANDY HOTEL TEXT INPUT CLASSIFICATION

更新日: 2026-07-16
対象: `HP/Text_hotel_data/*.txt`
状態: 正本

## 1. 目的

hotel入力txtの状態と、新規ページとして制作できるかを分ける。

`作成可能` は、入力txtの文章状態だけではなく、画像、既存ページ、共有登録、Git追跡状態まで通過したものだけを指す。

## 2. 分類

| 分類 | 意味 | 次の扱い |
|---|---|---|
| 作成可能 | ゲートを通過し、`NEW_HOTEL_TARGET_OK` が出る | 1件だけ制作へ進む |
| 画像なし | 入力は読めるが必要画像2枚がない | 画像を用意してから再判定 |
| 入力不備 | placeholder、canonical不足、危険URL、未登録店舗、部分入力など | txt修正後に再判定 |
| 作成済み/登録あり | 公開PHP、source、dataset、dataset_base、hotel一覧、sitemapのどれかに既存状態がある | 新規制作へ進めない。既存修正Taskとして扱う |
| 入力未追跡 | txtがGit HEADにない | Git管理へ入れるか確認してから制作 |
| 重複slug | 複数txtが同じslugを持つ | 1つに確定するまで停止 |
| 管理用txt | 手順など制作入力ではないtxt | 制作対象外 |

## 3. 現在値

2026-07-16に実ファイルを専用ゲートで確認した結果。

主分類:

| 分類 | 件数 |
|---|---:|
| 作成可能 | 0 |
| 画像なし | 35 |
| 入力不備 | 37 |
| 作成済み/登録あり | 1 |
| 管理用txt | 1 |
| 合計 | 74 |

停止理由は複数同時に存在するため、主分類だけで判断しない。

| 停止理由 | 件数 |
|---|---:|
| 画像なし | 35 |
| 入力未追跡 | 35 |
| 危険URL/http URL | 16 |
| canonical slug不足 | 10 |
| placeholder残存 | 5 |
| 未登録店舗 | 2 |
| h1ホテル名不一致 | 2 |
| 途中入力 | 1 |
| 基本情報不足 | 1 |
| 既存ページファイルあり | 1 |
| 共有登録あり | 1 |

`画像なし` の35件は、同時に `入力未追跡` でも止まる。画像だけを置いても、対象txtがGit管理へ入っていなければ公開へ進めない。
## 4. 実行コマンド

```powershell
HP\codex\scripts\candy-hotel.cmd audit-inputs
HP\codex\scripts\candy-hotel.cmd audit-inputs --write-report
HP\codex\scripts\candy-hotel.cmd audit-existing
HP\codex\scripts\candy-hotel.cmd target-next
HP\codex\scripts\candy-hotel.cmd target-check --input "HP/Text_hotel_data/対象ホテル.txt"
```

`--write-report` は次を出力する。

```text
HP/Text_hotel_data/制作可否管理_ホテル_最新.tsv
HP/Text_hotel_data/制作可否管理_ホテル_<timestamp>.tsv
```

## 5. 禁止

- `画像なし` を作成可能として扱わない。
- `入力不備` を推測で補完しない。
- `作成済み/登録あり` を新規ページとして上書きしない。
- Git未追跡txtをそのまま本番制作へ使わない。
