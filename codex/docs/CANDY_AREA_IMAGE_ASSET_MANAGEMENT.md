# CANDY AREA IMAGE ASSET MANAGEMENT

更新日: 2026-07-13
対象: areaページ用画像の受入、照合、公開配置、Git管理

## 1. 管理区分

| 区分 | パス | 扱い |
|---|---|---|
| 準備・受入用 | `Text_area_data/画像データ` | ユーザーが準備した画像を一時確認する場所 |
| 公開用正本 | `HP/imgHtml/new_202601/area` | HTMLから参照する実画像の配置先 |
| HTML参照 | `./imgHtml/new_202601/area/<ファイル名>` | area source HTML内の参照形式 |

`Text_area_data/画像データ`を公開用正本として参照してはいけません。公開ページは `HP/imgHtml/new_202601/area` を参照します。

## 2. 2026-07-13確認結果

準備フォルダ:

```text
\\192.168.1.3\disk1\FSG_SEO\candy\Text_area_data\画像データ
```

| 項目 | 結果 |
|---|---:|
| 全ファイル | 344 |
| JPG | 343 |
| 正式命名画像 | 342 |
| slug数 | 171 |
| `_1`・`_2` 完全ペア | 171 |
| ペア不足 | 0 |
| 読込不能画像 | 0 |
| サイズ | 全JPG 1000×750 |
| 正式命名外JPG | `sample.jpg` 1件 |
| 非画像 | `Thumbs.db` 1件 |

正式命名342枚は、公開用正本 `HP/imgHtml/new_202601/area` の同名画像とSHA-256が全件一致しました。現在はすでに公開用正本へ同じ画像が存在するため、コピー・上書きは不要です。

公開用正本にだけ存在し、準備フォルダにない正式画像は次の2枚です。

```text
kagoshima-deliveryhealth-area-ikenouecho_1.jpg
kagoshima-deliveryhealth-area-ikenouecho_2.jpg
```

## 3. 正式ファイル名

```text
kagoshima-deliveryhealth-area-<slug>_1.jpg
kagoshima-deliveryhealth-area-<slug>_2.jpg
```

- `_1`: メイン画像、OGP画像候補
- `_2`: 地域紹介画像
- 拡張子は現行仕様に合わせて `.jpg`
- 新規画像の基本サイズは現行一式と同じ1000×750
- slugは対象txtのcanonicalと一致させる

## 4. slug不一致候補

準備画像slugと `Text_area_data` のcanonical slugを照合した結果、次は自動対応・自動リネーム禁止です。

| 元テキスト側 | 準備画像側の候補 |
|---|---|
| `dairyuucho` | `dairyucho` |
| `inusakocho` | `inuzakocho` |
| `jonancho` | `jounancho` |
| `kotsukicho` | `koutukicho` |
| `koyo` | `kouyou` |
| `oroshihommachi` | `oroshihonmachi` |
| `seiryo` | `seiryou` |
| `shinayashikicho` | `shinyashikicho` |
| `tenpozancho` | `tempozancho` |
| `ikenouecho` | 準備フォルダにはなし。公開用正本にはあり |

準備画像側にあり、現行元テキストcanonicalと直接一致しない追加候補:

```text
kinkocho
onocho
sennen
shouyoudaicho
```

これらは別地域、別表記、旧slug、作成予定のいずれか未確認です。ユーザー確認なしに統合、削除、リネーム、流用しません。

## 5. 重複内容候補

次の2枚はファイル内容が完全一致します。

```text
kagoshima-deliveryhealth-area-ishikidai_1.jpg
kagoshima-deliveryhealth-area-ishikidai_2.jpg
```

意図的に同一画像を使うのか、画像2の用意漏れかは未確認です。新規ページ公開前にユーザー確認が必要です。

## 6. 正式命名外ファイル

- `sample.jpg`: 正式ページ画像として扱わない
- `Thumbs.db`: Windows管理ファイル。HTMLから参照しない

これらは現在、準備フォルダと公開用正本の両方に存在します。この調査では削除していません。削除・Git除外は別指示が必要です。

## 7. 今後の受入手順

新規areaページの依頼時に `_1`・`_2` の必要画像がない場合は、`CANDY_AREA_IMAGE_CREATION_SPEC.md` を確認します。画像元の保存・加工・商用公開条件と必要な帰属表示を確認できる場合だけ同仕様に従って制作します。条件を確認できない場合は停止し、正式ファイル名と必要な画像または許可情報をユーザーへ依頼します。既存画像の無断流用、ダミー画像、画像名の推測、権利未確認画像、画像なしでの公開は禁止します。

画像が揃ったことだけをページ制作完了としてはいけません。新規areaページへ画像を適用するときは、`CANDY_AREA_PAGE_GENERATION_SPEC.md` に従い、公開PHP、source HTML、ページ別dataset PHP、`dataset_base.php` のcase登録とリンク変換、area一覧・関連内部リンク、`sitemap.xml` への登録要否まで一体で確認します。必要なリンク設置や登録が未完了なら、ページを完成扱い・公開可能扱いにしません。

1. 準備画像を `Text_area_data/画像データ` で受け取る
2. ファイル名からslugと `_1`・`_2` を抽出する
3. 対象txtのcanonical slugと一致するか確認する
4. `_1`・`_2` が両方あるか確認する
5. JPGとして読込可能か確認する
6. 幅・高さを確認する
7. 同一ペアや他画像との完全重複を確認する
8. 公開用正本に同名ファイルがあるか確認する
9. 同名がある場合はハッシュ比較する
10. 同一ならコピーしない
11. 内容が違う場合は上書きせず、差異を報告して承認を得る
12. 公開用正本にない場合だけ、ユーザー承認後にコピーする
13. source HTMLのsrc、alt、OGPを確認する
14. ローカル画像確認と本番画像HTTP確認を分けて報告する

## 8. Git管理

- 公開ページで使用する正本画像は `HP/imgHtml/new_202601/area` 側をGit管理対象とする
- `Text_area_data/画像データ` は準備・受入用であり、同一画像を二重Commitしない
- 現在 `画像データ` は未追跡である
- `.gitignore` は現在存在しない
- 準備フォルダをGit除外するか、別保管場所へ移すかはユーザー判断が必要
- ユーザー判断までは、`git add -A`で準備画像を一括ステージしてはいけない

## 9. 完了判定

- [ ] 対象txtのslugと画像slugが一致する
- [ ] `_1`・`_2` が揃っている
- [ ] JPGが正常に読み込める
- [ ] サイズが確認済み
- [ ] 重複・同一ペアを確認済み
- [ ] 公開用正本とのハッシュ比較済み
- [ ] 上書きがある場合はユーザー承認済み
- [ ] source HTMLのsrc、alt、OGPが一致する
- [ ] 公開PHP、dataset、area一覧、内部リンク、sitemapを含むページ制作チェックが完了している
- [ ] 本番反映未確認の場合は明記した

## 10. 今回変更していないもの

画像のコピー、上書き、削除、移動、リネーム、Git追加は実施していません。
