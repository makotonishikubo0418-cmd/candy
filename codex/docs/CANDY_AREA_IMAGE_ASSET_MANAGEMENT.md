# CANDY Area Image Asset Management

- Updated: 2026-07-13
- Target: Acceptance, reconciliation, public placement, and Git management of area-page images

## 1. Management Classes

| Class | Path | Handling |
|---|---|---|
| Preparation and acceptance | `\\192.168.1.3\disk1\FSG_SEO\candy\Text_area_data\画像データ` | Accepted-asset storage on the NAS. Do not treat it as a Git working repository |
| Canonical public source | `HP/imgHtml/new_202601/area` | Actual images referenced by HTML |
| HTML reference | `./imgHtml/new_202601/area/<filename>` | Reference format in area source HTML |

Do not reference accepted NAS assets directly as the canonical public source. Public pages reference `HP/imgHtml/new_202601/area` in the local Git working repository.

## 2. Verification Results on 2026-07-13

Preparation folder, which is NAS storage only and prohibits Git operations:

```text
\\192.168.1.3\disk1\FSG_SEO\candy\Text_area_data\画像データ
```

| Item | Result |
|---|---:|
| All files | 344 |
| JPG | 343 |
| Correctly named images | 342 |
| Slugs | 171 |
| Complete `_1` and `_2` pairs | 171 |
| Incomplete pairs | 0 |
| Unreadable images | 0 |
| Dimensions | Every JPG is 1000×750 |
| JPG outside the naming standard | One `sample.jpg` |
| Non-image | One `Thumbs.db` |

All 342 correctly named images have SHA-256 values matching files with the same names in the canonical public source `HP/imgHtml/new_202601/area`. The same images already exist in the canonical public source, so no copy or overwrite is required.

Only these two correctly named images exist in the canonical public source and are absent from the preparation folder:

```text
kagoshima-deliveryhealth-area-ikenouecho_1.jpg
kagoshima-deliveryhealth-area-ikenouecho_2.jpg
```

## 3. Standard Filenames

```text
kagoshima-deliveryhealth-area-<slug>_1.jpg
kagoshima-deliveryhealth-area-<slug>_2.jpg
```

- `_1`: Main image and OGP-image candidate
- `_2`: Regional introduction image
- Extension: `.jpg`, matching the current specification
- Default dimensions for new images: 1000×750, matching the current set
- Slug: MUST match the canonical value in the target text file

## 4. Slug-Mismatch Candidates

Comparison of preparation-image slugs against canonical slugs in `Text_area_data` found the following candidates. Automatic pairing and renaming are prohibited.

| Source Text | Preparation-image candidate |
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
| `ikenouecho` | Absent from the preparation folder; present in the canonical public source |

Additional preparation-image candidates without a direct match to a current source Text canonical value:

```text
kinkocho
onocho
sennen
shouyoudaicho
```

It is unverified whether these represent another region, alternate spelling, legacy slug, or planned page. Do not consolidate, delete, rename, or reuse them without user confirmation.

## 5. Duplicate-Content Candidate

These two files have identical content:

```text
kagoshima-deliveryhealth-area-ishikidai_1.jpg
kagoshima-deliveryhealth-area-ishikidai_2.jpg
```

It is unverified whether this duplication is intentional or the second image is missing. User confirmation is required before publishing a new page with the pair.

## 6. Files Outside the Naming Standard

- `sample.jpg`: Do not treat as a standard page image.
- `Thumbs.db`: Windows management file. Do not reference it from HTML.

Both files currently exist in the preparation folder and canonical public source. This investigation did not delete them. Deletion or Git exclusion requires separate instruction.

## 7. Future Acceptance Procedure

When a new area-page request lacks required `_1` and `_2` images, review `CANDY_AREA_IMAGE_CREATION_SPEC.md`. Produce images according to that specification only when storage, modification, commercial-publication, and required-attribution conditions for the source are verified. Otherwise STOP and request correctly named images or permission information from the user. Unauthorized reuse of existing images, dummy images, inferred image names, rights-unverified images, and publication without images are prohibited.

Do not report page production complete merely because images are available. When applying images to a new area page, follow `CANDY_AREA_PAGE_GENERATION_SPEC.md` and validate public PHP, source HTML, page-specific dataset PHP, case registration and link transformation in `dataset_base.php`, the area index and related internal links, and required `sitemap.xml` registration as one unit. If required links or registrations remain incomplete, do not report the page complete or publishable.

1. Receive preparation images under NAS `\\192.168.1.3\disk1\FSG_SEO\candy\Text_area_data\画像データ`.
2. Extract the slug and `_1` or `_2` from each filename.
3. Verify a match with the target text file's canonical slug.
4. Verify that both `_1` and `_2` exist.
5. Verify that each file is readable as JPG.
6. Verify width and height.
7. Check for a duplicate pair and complete duplication against other images.
8. Check for a file with the same name in the canonical public source.
9. When the same name exists, compare hashes.
10. Do not copy a matching file.
11. When contents differ, do not overwrite; report the difference and obtain approval.
12. Only when the file is absent from the canonical public source, copy it after user approval.
13. Verify source HTML `src`, alt text, and OGP.
14. Report local-image validation separately from production-image HTTP validation.

## 8. Git Management

- Track canonical images used by public pages from `HP/imgHtml/new_202601/area` in the local Git working repository.
- NAS `Text_area_data/画像データ` is for preparation and acceptance and is outside Git management.
- Do not run Git operations on the NAS or stage and commit accepted assets directly.
- Before adding to the canonical public source, verify target images and destination, then copy into the local Git working repository only after explicit approval.
- Do not bulk-stage accepted images with `git add -A`.

## 9. Completion Criteria

- [ ] Target text-file slug matches the image slug.
- [ ] Both `_1` and `_2` exist.
- [ ] JPG files are readable.
- [ ] Dimensions are verified.
- [ ] Duplication and identical pairs are checked.
- [ ] Hashes were compared with the canonical public source.
- [ ] Any overwrite has user approval.
- [ ] Source HTML `src`, alt text, and OGP agree.
- [ ] Page-production validation covers public PHP, dataset, area index, internal links, and sitemap.
- [ ] Any unverified production deployment is stated explicitly.

## 10. Unchanged Scope

No image copy, overwrite, deletion, movement, rename, or Git registration was performed by this verification.
