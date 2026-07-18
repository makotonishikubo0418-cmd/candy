# CANDY Area Image Asset Management

- Updated: 2026-07-18
- Target: Acceptance, reconciliation, public placement, and Git management of area-page images

## 1. Management Classes

| Class | Path | Handling |
|---|---|---|
| Preparation and acceptance | `C:\Codex\Candy\Text_area_data\画像データ` | Git-managed local source for accepted area images |
| Canonical public source | `HP/imgHtml/new_202601/area` | Actual images referenced by HTML |
| HTML reference | `./imgHtml/new_202601/area/<filename>` | Reference format in area source HTML |

Do not reference accepted source assets under `Text_area_data/画像データ/` directly from HTML. Public pages reference `HP/imgHtml/new_202601/area` in the local Git working repository.

## 2. Current Verified State

Preparation and acceptance folder in the local Git working repository:

```text
C:\Codex\Candy\Text_area_data\画像データ
```

| Item | Result |
|---|---:|
| Accepted JPG files | 352 |
| Correctly named accepted images | 352 |
| Accepted slugs | 176 |
| Complete accepted `_1` and `_2` pairs | 176 |
| Incomplete pairs | 0 |
| Unreadable images | 0 |
| Dimensions | Every JPG is 1000×750 |
| Accepted files outside the naming standard | 0 |
| Accepted files missing from the canonical public source | 0 |
| Accepted/public same-name hash mismatches | 0 |

All 352 accepted images have SHA-256 values matching files with the same names
in the canonical public source `HP/imgHtml/new_202601/area`.

The public source contains two complete canonical pairs that are not accepted
source pairs, plus one nonstandard `sample.jpg`. These public-only items remain
outside the accepted-source count and MUST NOT be copied, renamed, or removed
automatically.

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
- The displayed title is a separate value and MUST NOT be inferred by uppercasing the slug

## 4. Slug-Mismatch Candidates

Comparison of preparation-image slugs against canonical slugs in `Text_area_data` found the following candidates. Automatic pairing and renaming are prohibited.

| Source Text | Preparation-image candidate |
|---|---|
| `dairyuucho` | `dairyucho` |
| `inusakocho` | `inuzakocho` |
| `jonancho` | `jounancho` |
| `kotsukicho` | `koutukicho` |
| `koyo` | `kouyou` |
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

Canonical pairs now exist independently for `dairyuucho`, `jonancho`,
`shinayashikicho`, `tenpozancho`, and `shouyoudaichou`. Their legacy
similar-slug candidates remain separate historical assets and are not the
canonical pairs.

## 5. Duplicate-Content Candidate

These two files have identical content:

```text
kagoshima-deliveryhealth-area-ishikidai_1.jpg
kagoshima-deliveryhealth-area-ishikidai_2.jpg
```

It is unverified whether this duplication is intentional or the second image is missing. User confirmation is required before publishing a new page with the pair.

## 6. Files Outside the Naming Standard

The accepted source contains no file outside the standard filename pattern.
The public source still contains `sample.jpg`; do not treat it as a standard
page image. Deletion or Git exclusion requires separate instruction.

## 7. Future Acceptance Procedure

When a new area-page request lacks required `_1` and `_2` images, review `CANDY_AREA_IMAGE_CREATION_SPEC.md`. Produce images according to that specification only when storage, modification, commercial-publication, and required-attribution conditions for the source are verified. Otherwise STOP and request correctly named images or permission information from the user. Unauthorized reuse of existing images, dummy images, inferred image names, rights-unverified images, and publication without images are prohibited.

Do not report page production complete merely because images are available. When applying images to a new area page, follow `CANDY_AREA_PAGE_GENERATION_SPEC.md` and validate public PHP, source HTML, page-specific dataset PHP, case registration and link transformation in `dataset_base.php`, the area index and related internal links, and required `sitemap.xml` registration as one unit. If required links or registrations remain incomplete, do not report the page complete or publishable.

1. Receive preparation images under local `C:\Codex\Candy\Text_area_data\画像データ`.
2. Extract the slug and `_1` or `_2` from each filename.
3. Verify a match with the target text file's canonical slug.
4. Verify the exact displayed Romanization independently from the slug.
5. Verify that both image titles match the confirmed display value exactly.
6. Verify that both `_1` and `_2` exist.
7. Verify that each file is readable as JPG.
8. Verify width and height.
9. Check for a duplicate pair and complete duplication against other images.
10. Check for a file with the same name in the canonical public source.
11. When the same name exists, compare hashes.
12. Do not copy a matching file.
13. When contents differ, do not overwrite; report the difference and obtain approval.
14. Only when the file is absent from the canonical public source, copy it after user approval.
15. Verify source HTML `src`, alt text, and OGP.
16. Report local-image validation separately from production-image HTTP validation.

## 8. Git Management

- `Text_area_data/画像データ/` is the Git-managed local source for preparation and acceptance.
- `HP/imgHtml/new_202601/area/` is the Git-managed canonical public source used by HTML.
- Add or update an accepted source image and its public copy in the same authorized work unit when both are required.
- Verify that matching accepted and public files have identical SHA-256 values.
- Stage only the explicitly authorized image pair and required generated management files.
- Do not use `git add -A` or treat the NAS as an area-image source.
- Commit and Push still require explicit user instruction.

## 9. Completion Criteria

- [ ] Target text-file slug matches the image slug.
- [ ] Display Romanization was verified independently from the slug.
- [ ] Both rendered titles exactly match the confirmed display value.
- [ ] Both `_1` and `_2` exist.
- [ ] JPG files are readable.
- [ ] Dimensions are verified.
- [ ] Duplication and identical pairs are checked.
- [ ] Hashes were compared with the canonical public source.
- [ ] Any overwrite has user approval.
- [ ] Source HTML `src`, alt text, and OGP agree.
- [ ] Page-production validation covers public PHP, dataset, area index, internal links, and sitemap.
- [ ] Any unverified production deployment is stated explicitly.

## 10. Storage Boundary

- Accepted source: `C:\Codex\Candy\Text_area_data\画像データ`.
- Canonical public source: `C:\Codex\Candy\HP\imgHtml\new_202601\area`.
- NAS: backup storage only; it is not an accepted area-image source.
- HTML MUST reference only the canonical public source, never `Text_area_data/画像データ/` directly.
