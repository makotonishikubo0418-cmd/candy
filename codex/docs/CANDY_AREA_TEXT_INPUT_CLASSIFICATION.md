# CANDY Area Text Input Classification

Updated: 2026-07-18

## 1. Source of Truth

- Active Text root: `Text_area_data/分類_20260716_115215/`
- Full file-level inventory: `Text_area_data/分類_20260716_115215/分類結果.tsv`
- Active `.txt` total: **156**
- Direct `.txt` files under `Text_area_data/`: **0**
- `.txt` files under `Text_area_data/Completion/`: **0**
- Every active Text file is present exactly once in the six classification folders and exactly once in `分類結果.tsv`.

Do not count the NAS backup as active Text input. The NAS is storage-only and is not a second management source of truth.

## 2. Active Classification Summary

These are the exact domain values and counts required for the area-input workflow.

The folder and TSV classifications are the retained input-audit snapshot. They
are not the authority for current image-file availability after later image
creation. Use actual files and `generated/CANDY_UPCOMING_PAGES.md` for the live
image gate.

| Category | Count | Meaning |
|---|---:|---|
| `01_間違い無し` | 98 | No classification issue; page bundle is not yet complete |
| `02_画像無し` | 5 | Image was missing at classification time; the folder has not been moved or reclassified |
| `03_情報足りない` | 0 | No current file |
| `04_間違い` | 0 | No current file |
| `05_複合_画像無し_情報足りない` | 0 | No current file |
| `06_複合_画像無し_間違い` | 2 | Image missing and incorrect information |
| `07_複合_情報足りない_間違い` | 1 | Information missing and incorrect information |
| `08_複合_画像無し_情報足りない_間違い` | 10 | Image missing, information missing, and incorrect information |
| `09_作成済み` | 40 | Public PHP, source HTML, dataset PHP, and the required `dataset_base.php` registrations are complete |
| **Total** | **156** | Active Text inputs |

Categories `01` through `08` are the fixed combinations of the three input issues: image missing, information missing, and incorrect information. `03`, `04`, and `05` are intentionally shown as zero so the numbering is not mistaken for an omission; physical folders are not created for empty categories. `09_作成済み` is an operational status and takes priority over an `01`-`08` input-issue category once the page bundle is complete. Its TSV `issues` field continues to preserve any input issue details.

As of 2026-07-18, all five inputs physically retained under `02_画像無し`
have complete canonical accepted and public image pairs. Their folder placement
and TSV issue text remain the original classification record; changing that
classification requires a separately authorized file-movement and inventory
update task.

The exact `09_作成済み` gate is all of the following: the public PHP exists, the source HTML exists, the dataset PHP exists, and `HP/includefile/dataset_base.php` contains exactly one case registration and exactly one conversion registration for the slug. Area-list and sitemap status are tracked separately and do not change whether the page itself is classified as created. A partial page artifact does not qualify.

## 3. Necessary and Unnecessary Files

| Decision | Count | Verified reason | Current location |
|---|---:|---|---|
| Necessary active Text | 156 | One unique filename and one unique SHA-256 per classified input | `Text_area_data/分類_20260716_115215/` |
| Necessary source images | 352 | All files are valid 1000×750 JPEG data; 176 complete `_1`/`_2` filename pairs are retained | `Text_area_data/画像データ/` |
| Necessary classification inventory | 1 | The 156 TSV rows match the 156 active Text files one-to-one | `Text_area_data/分類_20260716_115215/分類結果.tsv` |
| Unnecessary old Text | 35 | A current public, source, and dataset page bundle exists for every file | NAS `Backup/Text_area_data_unneeded_20260718/` |
| Unnecessary duplicate Text | 2 | Byte-for-byte duplicate of the classified `新屋敷町` and `真砂本町` inputs | NAS `Backup/Text_area_data_unneeded_20260718/` |
| Unnecessary old TSV reports | 3 | All described the old 159-input state; two were byte-for-byte duplicates | NAS `Backup/Text_area_data_unneeded_20260718/legacy_reports/` |
| Unnecessary cache | 1 | Windows-generated `Thumbs.db`; not a page input or source image | NAS `Backup/Text_area_data_unneeded_20260718/cache/` |
| Unnecessary nonstandard image | 1 | Unreferenced `sample.jpg`; exact duplicate of the two retained `ishikidai` correction targets | NAS `Backup/Text_area_data_unneeded_20260718/unused_images/` |

No file was discarded. The 42 unnecessary files were relocated to storage after applicable UTF-8 and SHA-256 verification. Exact source paths, destination paths, hashes, and reasons are recorded in:

- `Backup/Text_area_data_unneeded_20260718/manifest.tsv`
- `Backup/Text_area_data_unneeded_20260718/legacy_reports_manifest.tsv`
- `Backup/Text_area_data_unneeded_20260718/cache_manifest.tsv`
- `Backup/Text_area_data_unneeded_20260718/unused_images_manifest.tsv`

## 4. Full Validation Result

All 156 active files were read as UTF-8 and passed one-to-one inventory checks.

All 352 retained JPEG files are readable. `kagoshima-deliveryhealth-area-ishikidai_1.jpg` and `_2.jpg` have identical content, but both remain because they are the two explicitly named correction targets for the classified `ishikidai` input; they are not treated as an extra backup copy.

| Check | Result |
|---|---:|
| Active files | 156 |
| Unique filenames | 156 |
| Unique SHA-256 values | 156 |
| Parser passed | 145 |
| Parser failed | 11 |
| Pre-render passed | 137 |
| Pre-render stopped | 8 |

| Category | Files | Parser passed | Parser failed | Pre-render passed | Pre-render stopped |
|---|---:|---:|---:|---:|---:|
| `01_間違い無し` | 98 | 98 | 0 | 98 | 0 |
| `02_画像無し` | 5 | 5 | 0 | 0 | 5 |
| `06_複合_画像無し_間違い` | 2 | 2 | 0 | 1 | 1 |
| `07_複合_情報足りない_間違い` | 1 | 0 | 1 | 0 | 0 |
| `08_複合_画像無し_情報足りない_間違い` | 10 | 0 | 10 | 0 | 0 |
| `09_作成済み` | 40 | 40 | 0 | 38 | 2 |

The folder category is the current operational classification. Runtime parser and pre-render results are tracked separately. The two pre-render stops under `09_作成済み` are `向陽_テンプレート.txt` and `甲突町_テンプレート.txt`: their page bundles are complete, while their source image shortages remain recorded in the TSV.

## 5. Files Outside `01_間違い無し`

| Category | Files |
|---|---|
| `02_画像無し` | `城南町_テンプレート.txt`, `大竜町_テンプレート.txt`, `天保山町_テンプレート.txt`, `新屋敷町_テンプレート .txt`, `松陽台町_テンプレート.txt` |
| `06_複合_画像無し_間違い` | `池之上町_テンプレート.txt`, `真砂本町_テンプレート .txt` |
| `07_複合_情報足りない_間違い` | `石谷町_テンプレート.txt` |
| `08_複合_画像無し_情報足りない_間違い` | `伊敷.txt`, `伊敷台_テンプレート.txt`, `宇宿_テンプレート.txt`, `小原町_テンプレート.txt`, `小川町_テンプレート.txt`, `小野_テンプレート.txt`, `岡野原町_テンプレート.txt`, `易居町_テンプレート.txt`, `有屋田町_テンプレート.txt`, `薬師_テンプレート.txt` |
| `09_作成済み` | `三和町_テンプレート.txt`, `下伊敷町_テンプレート.txt`, `下田町_テンプレート.txt`, `下福元町_テンプレート.txt`, `下竜尾町.txt`, `五ヶ別府町_テンプレート.txt`, `光山_テンプレート.txt`, `原良_テンプレート.txt`, `吉野_テンプレート.txt`, `吉野町_テンプレート.txt`, `呉服町_テンプレート.txt`, `喜入町_テンプレート.txt`, `坂之上_テンプレート.txt`, `坂元町_テンプレート.txt`, `小山田町_テンプレート.txt`, `山下町_テンプレート.txt`, `山之口町_テンプレート.txt`, `山田町_テンプレート.txt`, `希望ヶ丘町_テンプレート.txt`, `広木_テンプレート.txt`, `春日町_テンプレート.txt`, `桜ヶ丘_テンプレート.txt`, `玉里団地_テンプレート.txt`, `玉里町_テンプレート.txt`, `皆与志町_テンプレート.txt`, `皇徳寺台_テンプレート.txt`, `祇園之洲町_テンプレート.txt`, `花尾町_テンプレート.txt`, `郡元_テンプレート.txt`, `郡元町_テンプレート.txt`, `郡山岳町_テンプレート.txt`, `郡山町_テンプレート.txt`, `金生町_テンプレート.txt`, `錦江台_テンプレート.txt`, `高麗町_テンプレート.txt`, `向陽_テンプレート.txt`, `甲突町_テンプレート.txt`, `荒田.txt`, `慈眼寺町_テンプレート.txt`, `自由ヶ丘_テンプレート.txt` |

Use `分類結果.tsv` for all 156 file names, slugs, parser states, and issue details.

## 6. Immediate Count Answer

When asked for the Text total and breakdown, answer:

```text
Text total: 156
01_間違い無し: 98
02_画像無し: 5
03_情報足りない: 0
04_間違い: 0
05_複合_画像無し_情報足りない: 0
06_複合_画像無し_間違い: 2
07_複合_情報足りない_間違い: 1
08_複合_画像無し_情報足りない_間違い: 10
09_作成済み: 40
Unnecessary Text remaining inside Text_area_data: 0
```

The physical file total under `Text_area_data/` is 509: 156 `.txt`, 352 `.jpg`, and one `.tsv`. The `.tsv` is the active classification inventory; images are not part of the Text count.
