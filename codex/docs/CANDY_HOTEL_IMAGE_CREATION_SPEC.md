# CANDY Hotel Image Creation Specification

- Updated: 2026-07-24
- Target: Two images for one hotel page
- Status: Canonical specification
- Route: Direct staff-completed Text image preparation or Phase 4
- Lifecycle authority: `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`

## 1. Position and Start Conditions

This specification supports two independent starts. Select one `SOURCE_ROUTE` and do not require evidence from the other route.

### 1.1 `DIRECT_TEXT`

Use this route when staff already completed the target Text. Run:

```powershell
codex\scripts\candy-hotel.cmd direct-check --input "Text_hotel_data/対象ホテル.txt"
```

Start image creation only when the result is `DIRECT_TEXT_STATUS=READY_FOR_IMAGES`. This result confirms that the Text parsed, the slug is unique, the input is tracked, no page or shared registration exists, and the only blockers are the two declared image files. Phase results and Phase hash records are not required.

### 1.2 `PHASE_PREPARED`

Use this route after Phases 1-3. Start only when Phase 1 and Phase 3 are `PASS`, Phase 3 records `READY_FOR_PHASE_4: YES`, and the current target Text hash matches the Phase 3 output hash.

### 1.3 Common Start Conditions

For either route, `HOTEL_NAME_JA`, `HOTEL_NAME_EN`, hotel address, and `CANONICAL_SLUG` MUST be confirmed; `img_1`, `img_2`, and OGP image paths MUST use that slug; and the approved reference images MUST be inspectable.

There is no separate image slug. The filename slug MUST equal `CANONICAL_SLUG`.

Keep unaccepted working output outside the accepted-source and public-source folders. After the pair passes this specification, use `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` for acceptance, storage, first installation, replacement review, Git scope, and publication state.

Do not use generative AI to create, complete, remove, replace, or materially alter the hotel, roads, buildings, or geographic background.

### 1.4 Optimized Candidate Route

Use the dedicated helper to remove repeated manual cropping, font sizing, placement, JPEG encoding, hashing, and file checks. It does not replace the applicable `DIRECT_TEXT` or `PHASE_PREPARED` start gate and does not perform visual acceptance, accepted-source storage, public installation, Git, or publication.

Plan one target before capture:

```powershell
codex\scripts\candy-hotel.cmd image-plan `
  --input "Text_hotel_data/対象ホテル.txt" `
  --hotel-name-en "Exact approved English name" `
  --source-route DIRECT_TEXT
```

Keep one Google Earth browser session for consecutive targets. Disable labels once, reuse the same `1280 x 960` capture viewport, search each confirmed hotel name and address for `_1` and the confirmed address only for `_2`, and capture only after the `_1` target-building measurements and unambiguous-subject test in Section 3.1 pass. The default crop is `x=140, y=100, width=1000, height=750`; pass `--earth-crop` or `--maps-crop` whenever that crop would fail a Section 3 composition gate. Until the compatibility parameter is renamed, pass the Google Earth top-down `_2` source through `--maps-source` and its crop through `--maps-crop`.

Render both candidates and one evidence manifest in a single command:

```powershell
codex\scripts\candy-hotel.cmd image-render `
  --input "Text_hotel_data/対象ホテル.txt" `
  --hotel-name-en "Exact approved English name" `
  --source-route DIRECT_TEXT `
  --earth-source "C:\path\earth-full.png" `
  --maps-source "C:\path\maps-full.png"
```

The default candidate directory is `%TEMP%\candy-hotel-images\<CANONICAL_SLUG>`. The helper rejects candidate output inside the repository, prevents accidental candidate overwrite unless `--overwrite` is explicit, uses one stored `HOTEL_NAME_EN` value for both renders, and records font sizes, bounding boxes, measured centers, file properties, and SHA-256 values.

Recheck an existing candidate manifest without rendering again:

```powershell
codex\scripts\candy-hotel.cmd image-check `
  --input "Text_hotel_data/対象ホテル.txt" `
  --hotel-name-en "Exact approved English name" `
  --source-route DIRECT_TEXT `
  --manifest "C:\candidate\<CANONICAL_SLUG>_image_manifest.json"
```

`DETERMINISTIC_FILE_GATES=PASS` covers only the measurable renderer and file requirements. `VISUAL_GATES=REQUIRED` means target identity, clean background, readability, and material composition difference still require inspection under Sections 3, 4, and 9. Do not accept or install a pair from the deterministic result alone.

Validate the helper contract after changing its implementation:

```powershell
codex\scripts\candy-hotel.cmd image-self-test
```

## 2. Deliverables

A hotel page requires exactly two accepted-source images:

```text
Text_hotel_data/画像データ/<CANONICAL_SLUG>_1.jpg
Text_hotel_data/画像データ/<CANONICAL_SLUG>_2.jpg
```

After acceptance, the asset-management procedure may install the exact same bytes under the canonical local public paths:

```text
HP/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg
HP/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_2.jpg
```

`img_1` and `img_2` in the input text file use:

```text
./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg
./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_2.jpg
```

The OGP image MUST be:

```text
https://www.55810.com/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg
```

Common output requirements:

- Dimensions: `1000 x 750`
- Format: JPG
- Color space: RGB
- JPG quality: `92`
- Title: exact `HOTEL_NAME_EN`
- Subtitle: exact `Kagoshima Hotel Information`

Preserve every letter, number, space, symbol, and capitalization in the approved `HOTEL_NAME_EN`. Pass one stored value to both render operations; do not retype it separately.

## 3. Source Views

### 3.1 Image `_1`: Google Earth 3D

Search with:

```text
<HOTEL_NAME_JA> <confirmed hotel address>
```

Confirm the name, address, building position, exterior form, and surrounding roads before capture.

The source view MUST:

- Use 3D display.
- Make the confirmed hotel the single unmistakable primary subject in the final clean `1000 x 750` crop before text is rendered. A wide city, district, or rural panorama with the hotel as one small building is prohibited.
- Measure the smallest axis-aligned rectangle enclosing the visible confirmed hotel building in that final crop. Its width MUST be at least `220 px`, its height MUST be at least `180 px`, and its rectangle area MUST be at least `120000 px²` (`16%` of the canvas). Record `x`, `y`, `width`, `height`, area, and canvas percentage; estimated descriptions such as “large enough” are not evidence.
- Pass an unlabeled three-second identification test: while viewing only the clean crop and the confirmed exterior reference, the reviewer MUST be able to point to the hotel within three seconds without using an address, map position, neighboring landmark, or hidden pin to infer which building it is.
- Reject a view when another building is equally or more visually dominant, when two or more buildings are plausible targets, or when the hotel can be identified only from its surroundings.
- Show enough surrounding streets and buildings to confirm the setting without weakening the hotel-subject thresholds above.
- Show the hotel exterior and height clearly enough to distinguish its building form.
- Avoid a flat top-down composition.
- Avoid a direction where another building materially hides the hotel.
- Contain no major unloaded tile or malformed 3D geometry.

Image `_1` is the main page image and OGP image.

### 3.2 Image `_2`: Google Earth Top-Down 2D

Search by the confirmed hotel address only, then keep Google Earth in a two-dimensional top-down aerial view.

The source view MUST:

- Show the hotel building or confirmed parcel clearly.
- Show useful surrounding roads and blocks.
- Use a straight top-down orientation without 3D tilt.
- Use a composition materially different from `_1`.
- Not be an enlargement, reduction, or crop of `_1`.
- Avoid a zoom level at which the target cannot be identified.

## 4. Clean Capture Gate

Before capture, close or frame out every unnecessary interface element and wait for imagery to finish loading.

The final background MUST NOT contain:

- Browser tabs, address bar, bookmarks bar, or operating-system UI
- Search box, result list, place-information panel, login control, notification, or popup
- Map buttons, layer buttons, zoom buttons, Street View controls, thumbnails, or selection outlines
- Pins, category icons, location markers, place labels, road labels, facility labels, or business labels
- Unnecessary blank margins

Disable labels through supported display controls or exclude them before capture. Do not remove labels or UI by cloning, inpainting, blurring, generative filling, or hiding them beneath text.

## 5. Deterministic Text Template

Use this exact renderer standard for new hotel images:

- Font file: Windows `Arial Bold` (`arialbd.ttf`)
- Text color: RGB `255, 255, 255`
- Alignment: centered
- Background, panel, band, gradient, vignette, outline, stroke, halo, glow, and shadow: prohibited
- Canvas: `1000 x 750`

Use the current Green Rich pair as the placement reference:

```text
HP/imgHtml/new_202601/hotel/greenrichkagoshimatenmonkan_1.jpg
HP/imgHtml/new_202601/hotel/greenrichkagoshimatenmonkan_2.jpg
```

Authoritative visual bounding-box anchors:

| Text | Center X | Center Y | Tolerance |
|---|---:|---:|---:|
| Hotel title | `500 px` | `354 px` | `+/- 5 px` on each axis |
| Fixed subtitle | `500 px` | `407 px` | `+/- 5 px` on each axis |

Corresponding title and subtitle centers in `_1` and `_2` MUST differ by no more than `2 px` on either axis.

Image `_1` sizes:

- Hotel title: start at `48 px`.
- Fixed subtitle: `32 px`.
- Maximum title width: `840 px`, within `x = 80..920`.

Image `_2` sizes:

- Hotel title: start at `42 px`.
- Fixed subtitle: `28 px`.
- Maximum title width: `760 px`, within `x = 120..880`.

When a hotel title exceeds its maximum width, reduce only its font size in `2 px` steps while preserving the fixed center. Minimum title size is `28 px`. Do not abbreviate, wrap, stretch, condense, or move the name.

The renderer MUST use the stored `HOTEL_NAME_EN` value byte for byte for both images. Record the renderer input, character count, font size, and measured visual bounding-box center for each output.

When the fixed text is unreadable or materially hides the target, select a different clean background composition. Do not move or decorate the text.

## 6. Permitted Processing

Permitted:

- Crop
- Resize to `1000 x 750`
- Minor rotation correction
- Light brightness, contrast, and color correction
- Deterministic text rendering
- RGB conversion
- JPG encoding at quality `92`

Prohibited:

- Adding or deleting a hotel, road, sign, facility, or building
- Combining multiple geographic images
- Generative filling or object replacement
- Altering the hotel shape
- Making the property appear materially newer, larger, or more luxurious
- Forced enlargement of a low-resolution image
- Non-proportional stretching
- Darkening the background to make text readable

## 7. Filename and Overwrite Gate

Before acceptance, inspect all four exact accepted-source and local-public destination names through `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`.

- Keep candidate output outside both canonical folders until every hard gate passes.
- Save a passing new pair to the accepted-source folder first.
- Copy exact accepted bytes to absent public filenames only through the first-installation procedure.
- Same accepted/public filenames with matching SHA-256 are reused without rewrite.
- Different SHA-256 for the same accepted or public filename is `REVIEW`; do not overwrite without explicit target-specific replacement authority.
- A public file without an accepted-source counterpart is `LEGACY_PUBLIC_ONLY`; do not backfill it automatically.
- An image belonging to another hotel is `STOP`.

The two final images MUST have different SHA-256 values and materially different compositions.

## 8. Alt and Input Values

Use these exact generated alt responsibilities:

```text
MAIN_IMAGE_1_ALT = <HOTEL_NAME_JA>
MAIN_IMAGE_2_ALT = <HOTEL_NAME_JA>基本情報
```

Verify that the target Text contains the exact relative image paths and absolute OGP path from Section 2. Phase 4 MUST NOT edit HTML, PHP, dataset PHP, shared registrations, or production. Local accepted-source storage and first public installation remain separate lifecycle states and follow the asset-management document.

## 9. Acceptance Gates

Every item MUST pass:

| Gate | Required evidence |
|---|---|
| Target identity | Name, address, building, and map position agree with the selected route's confirmed target Text evidence; surrounding landmarks alone are not accepted as building identity |
| View type | `_1` is 3D; `_2` is 2D aerial |
| Clean background | No prohibited label, pin, result, control, browser UI, or OS UI |
| Title identity | Both renderer inputs equal `HOTEL_NAME_EN` byte for byte |
| Fixed text | Exact `Kagoshima Hotel Information` on both images |
| Numeric placement | All four measured centers meet Section 5 |
| Text appearance | Exact font family, approved size rule, white color, and no prohibited effect |
| `_1` subject scale | The recorded hotel rectangle is at least `220 x 180 px`, has an area of at least `120000 px²` (`16%`), and the hotel is the single visually dominant plausible target |
| Composition | The unlabeled `_1` crop passes the three-second identification test, the hotel is not identified by surrounding landmarks alone, and `_1` and `_2` are materially different |
| File | Both are readable `1000 x 750` RGB JPG files at quality `92` |
| Naming | Filenames and all target Text image paths match `CANONICAL_SLUG` |
| OGP | Absolute OGP URL matches `_1` |
| Duplication | Pair hashes differ and neither image belongs to another hotel |

There is no partial visual pass. A failed hard gate rejects the affected image pair.

## 10. STOP and Review Conditions

STOP when:

- The target hotel, address, approved English name, or canonical slug is unavailable or ambiguous.
- For `PHASE_PREPARED`, the target Text hash no longer matches the Phase 3 handoff.
- Google Earth cannot load the required source view.
- A clean capture cannot be obtained.
- The `_1` hotel rectangle fails any minimum width, height, or area threshold in Section 3.1.
- The unlabeled `_1` crop fails the three-second identification test, contains another equally or more dominant building, presents multiple plausible targets, or requires surrounding landmarks to infer the hotel.
- The hotel cannot remain identifiable in the required composition.
- The text renderer, output destination, or numeric placement cannot be verified.
- The two required views cannot be produced.
- A same-name file belongs to another hotel.

Use `REVIEW` only when a same-name file has different content or when choosing between multiple compositions that each already pass every Section 3.1 identity, subject-scale, and prominence gate. A failed identity, subject-scale, prominence, or three-second gate is `STOP`, not `REVIEW`.

Do not proceed to local page build unless both images pass every hard gate and the asset-management state is at least `INSTALLED_LOCAL`. For `DIRECT_TEXT`, rerun `direct-check` and require `DIRECT_TEXT_STATUS=READY_FOR_BUILD`. For `PHASE_PREPARED`, proceed only when the Phase 4 result is ready for Phase 5. Page publication additionally requires a newly accepted pair to reach `DEPLOYED_ASSET` through `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`; image creation alone never proves publication readiness.

## 11. Image Result

Record:

```text
SOURCE_ROUTE: DIRECT_TEXT / PHASE_PREPARED
IMAGE RESULT: PASS / REVIEW / STOP
IMAGE LIFECYCLE: CANDIDATE / ACCEPTED / INSTALLED_LOCAL / REVIEW / STOP
PHASE 4 (PHASE_PREPARED only): PASS / REVIEW / STOP / NOT_APPLICABLE
Target hotel:
HOTEL_NAME_EN:
CANONICAL_SLUG:
Accepted image 1 path and SHA-256:
Accepted image 2 path and SHA-256:
Public image 1 path and SHA-256:
Public image 2 path and SHA-256:
Accepted/public hash agreement:
Image 1 renderer size and measured centers:
Image 1 confirmed-hotel rectangle (x, y, width, height, area, canvas percentage):
Image 1 three-second identification gate:
Image 1 competing-building/prominence gate:
Image 2 renderer size and measured centers:
Dimensions, format, color, and quality:
Clean-capture gate:
Composition gate:
Same-name file state:
Target Text image-path agreement:
READY_FOR_LOCAL_PAGE_BUILD: YES / NO
READY_FOR_PAGE_PUBLICATION: YES / NO
READY_FOR_PHASE_5 (PHASE_PREPARED only): YES / NO / NOT_APPLICABLE
Human decision required:
```

Report local image acceptance separately from page generation, Commit, Push, Actions, and production-image HTTP verification.
