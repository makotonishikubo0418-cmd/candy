# CANDY HOTEL IMAGE CREATION SPEC

更新日: 2026-07-16
対象: hotelページ用画像
状態: 正本

## 1. 必須条件

hotelページは画像2枚を必須とする。

```text
HP/imgHtml/new_202601/hotel/<slug>_1.jpg
HP/imgHtml/new_202601/hotel/<slug>_2.jpg
```

入力txt内の `img_1`、`img_2` は次の形にする。

```text
./imgHtml/new_202601/hotel/<slug>_1.jpg
./imgHtml/new_202601/hotel/<slug>_2.jpg
```

OGP image は `img_1` と一致させる。

## 2. 画像元の扱い

画像元の保存、加工、商用公開条件、必要な帰属表示を確認できない場合は制作しない。

Googleマップ、ホテル公式サイト、予約サイト、SNS、第三者投稿写真は、利用条件が確認できない限り保存・加工・公開しない。

## 3. 作成後の確認

- ファイル名がcanonical slugと一致している
- `_1` と `_2` が別画像である
- 実ファイルが存在する
- 入力txtの `image`、`img_1`、`img_2` と一致する
- 画像不足のまま `publish` しない

## 4. 現在確認済み画像

2026-07-16時点で存在確認済みのhotel画像は次の3ページ分だけ。

| slug | 画像 |
|---|---|
| greenrichkagoshimatenmonkan | `_1.jpg`, `_2.jpg` |
| hotelm | `_1.jpg`, `_2.jpg` |
| villacosta500 | `_1.jpg`, `_2.jpg` |

その他のhotel入力は、画像がない限り新規制作対象にしない。
