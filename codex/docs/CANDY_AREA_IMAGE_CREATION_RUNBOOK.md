# CANDY Area Image Creation Runbook

- Purpose: Produce two visually correct official images for one CANDY area page
- Status: Canonical execution runbook
- Applies to: Area-image creation, validation, integration, and optional publication
- Governing documents: `CANDY_AREA_IMAGE_CREATION_SPEC.md` and `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
- Existing approved pair replacement: `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`

## 1. Non-Negotiable Result

Create exactly two `1000 x 750` JPG images for one confirmed area.

| Image | Required result |
|---|---|
| `_1` | Wide establishing view of the correct area |
| `_2` | Closer view of the same area from a clearly different heading or camera direction |

An image pair MUST NOT be shown to the user, installed, staged, committed, pushed, or published unless every hard visual gate in Section 10 passes.

There is no partial visual pass. One failed gate makes the pair `REJECTED`.

The project administrator's explicit task-specific instruction controls source, method, design exceptions, overwrite, integration, and publication within the instructed scope. Do not stop or request duplicate approval for a decision the administrator has already stated. Generic defaults in this runbook are not an independent reason to block an administrator-approved exception.

## 2. Required Inputs

Confirm these values from current repository data or explicit user instruction:

```text
Japanese area name:
Canonical lowercase slug:
Display name in uppercase Roman letters:
Display-name authority:
Display-name character count:
Target Text, HTML, or authoritative data file:
Canonical URL:
Image source:
Deployment required: yes / no
```

The user MAY provide only the Japanese area name when every other value can be verified uniquely from an authoritative source. Do not guess or automatically normalize a conflicting slug or Romanization.

### 2.1 Text Identity Gate

The canonical slug and the displayed Romanized name are separate identifiers.
They MAY differ. A slug is never evidence for the spelling of the displayed
name.

Before any title is rendered, all of the following MUST pass:

1. Confirm the exact Japanese area name.
2. Confirm the canonical lowercase slug only for filenames and URLs.
3. Confirm the exact displayed Romanized name from one of these authorities:
   - an explicit user-approved display string;
   - an approved existing image for the same Japanese area whose title is
     visually readable; or
   - a repository field explicitly designated as the display Romanization.
4. Record the authority and count the ASCII characters in the confirmed
   display string.
5. Convert only the confirmed display string to uppercase. Do not add, remove,
   reorder, transliterate, or normalize characters.
6. Store the uppercase result once as `EXPECTED_DISPLAY_NAME` and pass that
   same value directly to the renderer for both `_1` and `_2`.

MUST NOT:

- Uppercase the slug and use it as the title.
- Derive the title from a filename, URL, automatic transliteration, memory, or
  pronunciation guess.
- Retype the title separately for `_1` and `_2`.
- Treat a matching filename as proof that the displayed spelling is correct.

Known example: the canonical filename slug `shinayashikicho` does not authorize
the displayed title `SHINAYASHIKICHO`. The confirmed displayed title for
`新屋敷町` is `SHINYASHIKICHO`.

If no display-name authority exists, STOP and ask the user to approve the exact
Romanized display string. Image capture MAY be prepared, but title rendering,
acceptance, saving, installation, and presentation MUST NOT proceed.

## 3. Required Authority Check

Before creating images:

1. Read root `AGENTS.md`, `codex/README.md`, and `HP/AGENTS.md`.
2. Read this runbook, `CANDY_AREA_IMAGE_CREATION_SPEC.md`, and `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`.
3. Run `git fetch origin` and `git status --short --branch`.
4. Check `codex/project_management/TASK_RESERVATIONS.md`.
5. Confirm the target page, slug, canonical URL, expected filenames, and publication scope.
6. Inspect the approved placement reference pair:
   `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-wakabacho_1.jpg`
   and `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-wakabacho_2.jpg`.

STOP on conflicting instructions, unresolved ownership, or an ambiguous target.

## 4. Source and Location Verification

Use an actual view of the confirmed area according to `CANDY_AREA_IMAGE_CREATION_SPEC.md`.

MUST:

- Search the full location in the form `鹿児島県鹿児島市<地域名>`.
- Verify the displayed municipality and area name before capture.
- Verify that visible geography matches the target area.

MUST NOT:

- Generate the geographic background with AI.
- Use a different area, generic city image, unrelated stock image, or unverified screenshot.
- Hide an incorrect location by cropping out location evidence.

## 5. Clean Map Mode Before Capture

The captured map area MUST be visually clean.

Before each capture:

1. Select the aerial, satellite, or 3D view.
2. Turn map labels off when the source provides a label control.
3. Close or frame out the search panel and information panel.
4. Frame out all unnecessary map controls.
5. Wait for all imagery tiles and buildings to finish rendering.

The final background MUST NOT contain:

- Place-name labels.
- Road-name labels or route shields.
- Facility or business labels.
- Hotel, restaurant, attraction, transit, or other POI labels.
- POI pins, colored category icons, location markers, boundary selections, or search-result outlines.
- Search boxes, side panels, login buttons, menus, layer buttons, zoom buttons, Street View controls, thumbnails, or other map UI.
- Browser tabs, address bar, bookmarks bar, operating-system taskbar, notifications, or popups.

If any prohibited label, marker, boundary, or control remains, the capture is `REJECTED`. Do not attempt to hide it with a dark overlay or text placement.

## 6. Image `_1`: Wide Establishing View

Image `_1` MUST:

- Show the broad structure of the correct area and relevant surroundings.
- Include enough roads, buildings, terrain, coastline, mountains, forest, farmland, or residential form to identify the area.
- Keep the target area near the center.
- Avoid excessive zoom-out.
- Preserve a visually usable center for the title without modifying the background.

Record the zoom, heading or camera direction, tilt, and center used for `_1`.

## 7. Image `_2`: Different Viewpoint

Image `_2` MUST satisfy all of these conditions:

1. Use a closer zoom than `_1`.
2. Use a clearly different heading or camera direction from `_1`.
3. Use a visibly different composition and center from `_1`.
4. Show more local detail while remaining within the same confirmed area.

The following do not qualify as a different viewpoint:

- Zooming into the center of `_1` without changing heading or camera direction.
- Cropping `_1`.
- Panning only slightly.
- Moving labels or title text while keeping the same background view.
- Using the same north-up view at a different zoom.

When the source cannot change heading, direction, or 3D viewpoint, STOP. Do not create `_2` from a same-angle enlargement.

Before editing, compare the two raw captures side by side. If a reviewer could reasonably describe `_2` as "the same angle, only closer," both captures are `REJECTED` and MUST be recaptured.

## 8. Crop and Output Size

Crop only the image-display area.

Final output:

```text
Width: 1000 px
Height: 750 px
Aspect ratio: 4:3
Format: JPG
```

Do not stretch the image. Do not reconstruct, clone, inpaint, blur, or generatively remove map labels or UI. Obtain a clean source view instead.

## 9. Typography: Fixed Placement Contract

Text placement is not a creative decision. It MUST use the fixed coordinate
template below. Background brightness, terrain, buildings, coastline, or empty
space MUST NOT be used as a reason to move either line.

The coordinate system is the final `1000 x 750` canvas, with `(0, 0)` at the
top-left corner.

| Element | Mandatory value |
|---|---|
| Shared horizontal center | `x = 500 px` |
| Main-title visual center | `y = 320 px` |
| Main-title center tolerance | `x = 500 +/- 5 px`; `y = 320 +/- 5 px` |
| Subtitle visual center | `y = 400 px` |
| Subtitle center tolerance | `x = 500 +/- 5 px`; `y = 400 +/- 5 px` |
| Main-title alignment | Center aligned on `x = 500 px` |
| Subtitle alignment | Center aligned on `x = 500 px` |
| Pair consistency | Identical font settings and identical anchor coordinates in `_1` and `_2` |

Main title:

- Use the confirmed uppercase Romanized area name.
- Use white bold type.
- Keep it on one line.
- Set its measured visual bounding-box center to `(500, 320)` within the stated tolerance.
- Scale the font down only when necessary to keep the full title inside the
  horizontal safe range `x = 80..920`; never move the anchor.

Place this exact subtitle below the title:

```text
Kagoshima Area Information
```

Subtitle:

- White.
- Regular weight.
- Set its measured visual bounding-box center to `(500, 400)` within the stated tolerance.
- Smaller than the main title.
- Exact spelling and capitalization.

The Wakabacho reference pair named in Section 3 defines the intended visual
relationship. The numeric coordinates in this section are authoritative if a
visual estimate and the coordinates disagree.

### 9.1 Mandatory Render Procedure

For each pair:

1. Create one reusable text template containing the font family, weights,
   sizes, line spacing, colors, and the fixed anchors above.
2. Assert that the renderer input equals `EXPECTED_DISPLAY_NAME` byte for byte
   and that its ASCII character count matches the recorded count.
3. Apply that same template and the same title variable to `_1` and `_2`. Do not place the two images by eye
   independently.
4. Measure the rendered bounding box of each line after rasterization.
5. Record the four measured centers: `_1` title, `_1` subtitle, `_2` title, and
   `_2` subtitle.
6. Record the exact renderer title string and character count for each image.
7. Reject the pair unless both recorded renderer strings equal
   `EXPECTED_DISPLAY_NAME` byte for byte.
8. Reject the pair unless all four centers fall within the numeric tolerances.
9. Reject the pair if corresponding centers between `_1` and `_2` differ by
   more than `2 px` on either axis.

If the fixed placement is unreadable on either background, recapture the
background. Moving the text, adding an effect, or accepting reduced readability
is prohibited.

### 9.2 Forbidden Text Treatment

The title and subtitle MUST have:

- No black rectangle.
- No translucent black rectangle.
- No dark band.
- No gradient band.
- No vignette added for readability.
- No background darkening behind the text.
- No outline or stroke.
- No black halo.
- No drop shadow.
- No duplicated offset text used as a shadow.
- No glow, frame, badge, label, or text panel.

Only the white title and white subtitle may be added. The geographic background MUST remain unchanged except for crop and resize.

If the white text is not readable without a forbidden treatment, choose a different clean capture with a more suitable center. Do not darken the image.

## 10. Hard Visual Acceptance Gates

Inspect both final images at full size before showing or saving them as accepted outputs.

Every item MUST pass:

| Gate | Required result |
|---|---|
| Correct area | Repository data and visible geography confirm the requested area |
| Clean background | No place, road, facility, business, or POI labels |
| No map clutter | No pins, icons, boundary outlines, search results, or map controls |
| Display-name authority | Exact authority is recorded and is not the slug alone |
| Title identity | Both renderer inputs equal `EXPECTED_DISPLAY_NAME` byte for byte |
| Title character count | Expected, `_1`, and `_2` ASCII character counts are identical |
| Title coordinates | Measured center is `x = 500 +/- 5 px`, `y = 320 +/- 5 px` |
| Subtitle coordinates | Measured center is `x = 500 +/- 5 px`, `y = 400 +/- 5 px` |
| Pair placement | Corresponding `_1` and `_2` centers differ by no more than `2 px` per axis |
| Title | Exact uppercase Romanized name, white, bold, one line, unclipped |
| Subtitle | Exact `Kagoshima Area Information`, white, unclipped |
| Text background | No rectangle, translucent panel, band, gradient, vignette, or darkening |
| Text effects | No outline, stroke, halo, shadow, glow, or duplicated offset text |
| `_1` composition | Valid wide establishing view |
| `_2` zoom | Clearly closer than `_1` |
| `_2` viewpoint | Clearly different heading or camera direction from `_1` |
| Pair difference | Not the same angle enlarged, panned slightly, or cropped |
| File | Readable JPG, exactly `1000 x 750` |
| Pair uniqueness | Different hashes and visibly different source compositions |

After checking the table, inspect both images side by side one final time.

The acceptance record MUST contain the measured text centers. A statement such
as "centered," "looks centered," or "matches the reference" without numeric
measurements is not evidence and MUST be treated as a failed gate.

If any item fails:

1. Mark the pair `REJECTED`.
2. Do not show it as completed.
3. Do not install or publish it.
4. Recapture or recreate the pair from the failed phase.

## 11. Filenames and Storage

Use only:

```text
kagoshima-deliveryhealth-area-<canonical-slug>_1.jpg
kagoshima-deliveryhealth-area-<canonical-slug>_2.jpg
```

- Lowercase ASCII slug.
- No spaces, Japanese characters, full-width characters, or invented abbreviation.
- `_1` and `_2` are the only filename difference.
- `.jpg` extension.

Public destination:

```text
HP/imgHtml/new_202601/area/
```

Public reference:

```text
./imgHtml/new_202601/area/<filename>
```

Before saving, check for same-name files and compare hashes. Do not overwrite without explicit authorization.

Do not perform an existing same-name public replacement inside this creation route. After the replacement pair passes every creation and acceptance gate, switch to `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`; do not reread this creation runbook during the replacement phase.

For a preview-only task, save outside the public image directory and state that the images are not installed.

## 12. Page Integration

This section applies to first installation after image creation. An existing approved same-name replacement uses `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` instead.

When first installation is authorized:

1. Use `_1` first and `_2` second.
2. Verify exact filename case and relative paths.
3. Use `_1` for OGP when required by the page structure.
4. Verify alt text and absence of obsolete references.
5. Change only the target page or authoritative data source.
6. Follow `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` and `CANDY_AREA_PAGE_GENERATION_SPEC.md` for the complete page unit.

## 13. Repository and Browser Validation

For integrated work:

```powershell
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

Verify the exact Git diff, explicit stage allowlist, and `git diff --cached --check`.

Open the page and visually verify both desktop and mobile layouts. Do not report browser validation without actually opening the page.

## 14. Deployment

Deploy only with explicit user authorization.

Production completion requires:

- Successful authorized deployment.
- Successful HTTP response for both image URLs.
- Both images loaded in the correct order on the production page.
- Correct canonical URL.
- Successful desktop and mobile visual checks.
- No unrelated production change.

Commit or Push alone is not production completion.

## 15. STOP Conditions

STOP when:

- Target area, slug, Romanization, canonical URL, or page is ambiguous.
- Display Romanization is derived only from the slug, filename, URL, memory, or automatic transliteration.
- `EXPECTED_DISPLAY_NAME`, its authority, or its character count is not recorded.
- Either rendered title string differs from `EXPECTED_DISPLAY_NAME` by one or more characters.
- A clean label-free and control-free source view cannot be captured.
- A second view with both closer zoom and different heading or direction cannot be captured.
- White text cannot remain readable without a forbidden background or effect.
- Same-name overwrite authorization is missing.
- Any hard visual acceptance gate fails.
- Unrelated changes are required.
- Local validation, deployment, or production verification fails.

Do not stop or request duplicate approval for a task-specific decision that the project administrator has already stated explicitly. Do not bypass a genuine STOP condition by assumption, cropping, overlays, cloning, inpainting, or generative editing.

## 16. Completion Report

Use this exact evidence model. Translate the final user-facing report into Japanese as required by root `AGENTS.md`, but keep this canonical instruction file in English.

```text
Conclusion: COMPLETE / REJECTED / STOP
Target area:
Confirmed slug:
Confirmed display name:
Display-name authority:
Expected display-name character count:
Created files:
Saved location:
Installed in public directory: yes / no

Hard visual gates:
- _1 renderer title string and character count:
- _2 renderer title string and character count:
- Both title strings exactly match confirmed display name:
- Labels and POI markers absent:
- Map controls absent:
- _1 title measured center (x, y):
- _1 subtitle measured center (x, y):
- _2 title measured center (x, y):
- _2 subtitle measured center (x, y):
- Pair text-coordinate difference within 2 px:
- Black or translucent text background absent:
- Text shadow, outline, and halo absent:
- _1 wide view:
- _2 closer zoom:
- _2 different heading or direction:
- Same-angle enlargement absent:
- Dimensions and JPG readability:
- Pair hashes differ:

Page integration:
Git:
Production deployment:
Uncompleted items:
STOP reason:
```

Never mark an unchecked item as passed. Never show a rejected pair as a completed result.
