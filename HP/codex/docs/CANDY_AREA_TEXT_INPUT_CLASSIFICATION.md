# CANDY Area Text Input Classification

Updated: 2026-07-16

## Scope

- Target: `HP/Text_area_data/*.txt` direct files
- Command: `HP\codex\scripts\candy-area.cmd audit-inputs --render`
- Input total: 169
- Parsed: 157
- Parse failed: 12
- Render targets: 157
- Render passed: 147
- Render stopped: 10

## Classification Summary

| Category | Count | Meaning |
| --- | ---: | --- |
| 通常 | 147 | parse/render事前検証OK |
| 情報足りない | 20 | 必須項目不足、scene不足、画像不足 |
| 間違い | 2 | 店舗名不一致、placeholder残存 |

## 情報足りない

| File | Reason |
| --- | --- |
| `伊敷.txt` | 人気デリヘル店sceneの情報不足 |
| `宇宿_テンプレート.txt` | 通常記事sceneの情報不足 |
| `小原町_テンプレート.txt` | canonical slug不足 |
| `小川町_テンプレート.txt` | canonical slug不足 |
| `小野_テンプレート.txt` | canonical slug不足 |
| `岡野原町_テンプレート.txt` | canonical slug不足 |
| `易居町_テンプレート.txt` | scene h2なし |
| `有屋田町_テンプレート.txt` | canonical slug不足 |
| `石谷町_テンプレート.txt` | 基本情報scene不足 |
| `荒田.txt` | img_1、img_2、page_title_h1不足 |
| `薬師_テンプレート.txt` | canonical slug不足 |
| `向陽_テンプレート.txt` | 画像不足: koyo_1/koyo_2 |
| `城南町_テンプレート.txt` | 画像不足: jonancho_1/jonancho_2 |
| `大竜町_テンプレート.txt` | 画像不足: dairyuucho_1/dairyuucho_2 |
| `天保山町_テンプレート.txt` | 画像不足: tenpozancho_1/tenpozancho_2 |
| `新屋敷町_テンプレート .txt` | 画像不足: shinayashikicho_1/shinayashikicho_2 |
| `松陽台町_テンプレート.txt` | 画像不足: shouyoudaichou_1/shouyoudaichou_2 |
| `池之上町_テンプレート.txt` | 画像不足: ikenouecho |
| `甲突町_テンプレート.txt` | 画像不足: kotsukicho_1/kotsukicho_2 |
| `花野光ヶ丘_テンプレート.txt` | 画像不足: kenohikarigaoka.1/kenohikarigaoka.2 |

## 間違い

| File | Reason |
| --- | --- |
| `伊敷台_テンプレート.txt` | template_shop.htmlにない店舗名: 劇場団地妻 |
| `喜入生見町_テンプレート.txt` | placeholder残存: aaaaaaaaaaaaaaaaaaaa |

## Notes

- This file replaces the older 135-input classification from the previous Codex work folder.
- The current correct folder is `\\192.168.1.3\disk1\FSG_SEO\candy`.
- `下福元町_テンプレート.txt`、`下竜尾町.txt`、`慈眼寺町_テンプレート.txt` were reflected from the previous Codex work folder because the current folder still had placeholders or an image-path typo.