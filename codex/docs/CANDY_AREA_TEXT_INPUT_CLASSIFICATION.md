# CANDY Area Text Input Classification

Updated: 2026-07-16

## 1. Scope

- Target: Direct files matching `Text_area_data/*.txt`
- Command: `codex\scripts\candy-area.cmd audit-inputs --render`
- Input total: 169
- Parsed: 157
- Parse failed: 12
- Render targets: 157
- Render passed: 147
- Render stopped: 10

## 2. Classification Summary

The category values in this table are exact domain values used by the area-input workflow.

| Category | Count | Meaning |
|---|---:|---|
| `通常` | 147 | Parse and pre-render validation succeeded |
| `情報足りない` | 20 | Required field, scene, or image is missing |
| `間違い` | 2 | Shop-name mismatch or remaining placeholder |

## 3. Incomplete Inputs

These files have the exact domain classification `情報足りない`.

| File | Reason |
|---|---|
| `伊敷.txt` | Insufficient information for the popular-delivery-health-shop scene |
| `宇宿_テンプレート.txt` | Insufficient information for a normal article scene |
| `小原町_テンプレート.txt` | Missing canonical slug |
| `小川町_テンプレート.txt` | Missing canonical slug |
| `小野_テンプレート.txt` | Missing canonical slug |
| `岡野原町_テンプレート.txt` | Missing canonical slug |
| `易居町_テンプレート.txt` | Scene has no H2 |
| `有屋田町_テンプレート.txt` | Missing canonical slug |
| `石谷町_テンプレート.txt` | Insufficient basic-information scene |
| `荒田.txt` | Missing `img_1`, `img_2`, and `page_title_h1` |
| `薬師_テンプレート.txt` | Missing canonical slug |
| `向陽_テンプレート.txt` | Missing images: `koyo_1` and `koyo_2` |
| `城南町_テンプレート.txt` | Missing images: `jonancho_1` and `jonancho_2` |
| `大竜町_テンプレート.txt` | Missing images: `dairyuucho_1` and `dairyuucho_2` |
| `天保山町_テンプレート.txt` | Missing images: `tenpozancho_1` and `tenpozancho_2` |
| `新屋敷町_テンプレート .txt` | Missing images: `shinayashikicho_1` and `shinayashikicho_2` |
| `松陽台町_テンプレート.txt` | Missing images: `shouyoudaichou_1` and `shouyoudaichou_2` |
| `池之上町_テンプレート.txt` | Missing image: `ikenouecho` |
| `甲突町_テンプレート.txt` | Missing images: `kotsukicho_1` and `kotsukicho_2` |
| `花野光ヶ丘_テンプレート.txt` | Missing images: `kenohikarigaoka.1` and `kenohikarigaoka.2` |

## 4. Incorrect Inputs

These files have the exact domain classification `間違い`.

| File | Reason |
|---|---|
| `伊敷台_テンプレート.txt` | Shop name absent from `template_shop.html`: `劇場団地妻` |
| `喜入生見町_テンプレート.txt` | Remaining placeholder: `aaaaaaaaaaaaaaaaaaaa` |

## 5. Notes

- This file replaces the older 135-input classification from the previous Codex work folder.
- The current correct folder is `C:\Codex\candy`.
- `下福元町_テンプレート.txt`, `下竜尾町.txt`, and `慈眼寺町_テンプレート.txt` were carried over from the previous Codex work folder because the current folder still contained placeholders or an image-path typo.
