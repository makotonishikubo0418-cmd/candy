# CANDY Area Image Creation Specification

- Purpose: Safely produce two regional images for an area page while matching the existing design
- Status: conditional canonical document
- Updated: 2026-07-22
- Applies to: Image production for candy area pages

## 1. Priority and Rights Conditions

This document defines composition, modification, naming, storage, and page integration. Follow `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` for image acceptance, slug reconciliation, duplication checks, and Git management.

The project administrator's explicit instruction is the authority for a task-specific source, method, design exception, overwrite, integration, or publication decision within the instructed scope. When that decision has already been stated, do not stop or request the same decision again merely because this document contains a different default. Apply the instructed exception, record it in the work result, and update the canonical specification when the administrator has explicitly instructed a permanent rule change.

External rights and license terms are not waived by project authority. A source requirement that can be satisfied, such as adding a required attribution line, MUST be implemented as part of the image instead of being treated as a blocker.

Do not use generative AI. Use images that verify the actual region.

Before using Google Maps, Google Earth, aerial photography, map tiles, or third-party photographs, verify that storage, modification, and commercial publication are permitted for the intended use. Do not remove, hide, or alter attribution, Google logos, copyright notices, or data-provider notices.

Current Google guidance requires appropriate attribution when publishing Google Maps content and restricts commercial or promotional use of Google Maps satellite imagery on the web. Until individual permission or applicable usage terms are verified, do not create, save, integrate, or publish a Google Maps screenshot as a public image.

Verification sources:

- `https://about.google/brand-resource-center/products-and-services/geo-guidelines/`
- `https://www.google.com/help/terms_maps/`

When the user specifies an alternate image source with verified rights, apply this document's composition, modification, naming, storage, and integration procedure.

## 2. Deliverables

Produce two images per region:

- `_1`: A wider view of the target region; candidate main and OGP image
- `_2`: A closer view of the same region with a changed angle or direction

Differences in cityscape, sea, mountains, forests, and farmland between regions are expected. Prioritize the target region's actual characteristics instead of matching another region's background.

Standard:

- Dimensions: `1000×750`
- Format: JPG
- Public destination: `HP/imgHtml/new_202601/area/`
- HTML reference: `./imgHtml/new_202601/area/<filename>`

## 3. Required Decisions Before Production

When producing page data and images in the same task, confirm first:

- Official Japanese region name
- Romanized region name used for display
- Authority for the exact displayed Romanization
- Lowercase slug matching canonical
- Image source and usage conditions
- Public destination
- Source HTML reference
- Same-name file existence
- Existing area image used as the design reference

Reconcile the displayed Romanization and slug separately. The slug controls
filenames and URLs; it MUST NOT be uppercased or otherwise transformed into the
displayed title. Confirm the displayed Romanization from an explicit
user-approved value, a readable approved image for the same Japanese area, or a
repository field explicitly designated for display Romanization. Filenames,
URLs, automatic transliteration, memory, and pronunciation guesses are not
display-name authorities.

Record the exact authority, uppercase the confirmed display value without
changing any character, store it once as `EXPECTED_DISPLAY_NAME`, and use that
same value for both images. Record its ASCII character count. STOP before title
rendering when any of these values is unavailable or ambiguous.

Known exception: `新屋敷町` uses canonical slug `shinayashikicho`, while its
confirmed displayed title is `SHINYASHIKICHO`. This difference MUST be
preserved.

## 4. Browser and Map Display

Connect the Codex app and Chrome extension so Codex can operate the normal Chrome session. Do not include credentials, personal information, notifications, or other-tab content in the deliverable.

Only when rights conditions for Google Maps use are verified, enter this exact search form:

`鹿児島県鹿児島市［地域名］`

Verify that the result matches the target region. When the same name in another municipality appears, add the address required to identify it uniquely.

When using aerial or 3D display, adjust within the permitted usage conditions:

- Zoom
- Center
- Angle
- Direction
- 3D tilt

The final source capture MUST contain no place-name labels, road-name labels, route shields, facility or business labels, POI pins, category icons, selected-area boundaries, search-result outlines, search boxes, side panels, login controls, menus, layer controls, zoom controls, Street View controls, thumbnails, browser UI, or operating-system UI. Required logos, copyright notices, data-provider notices, and attribution MUST remain fully visible and readable. When a permitted source requires written attribution but does not render it in the clean capture, add the exact required attribution to the final image as compliance text under Section 8.

Turn labels off through the source's supported controls. Frame out other prohibited UI before capture. Do not remove labels or UI through cloning, inpainting, blurring, generative editing, or a dark overlay. STOP only when a clean capture cannot be obtained and the required attribution also cannot be preserved or added in the permitted compliance form.

## 5. First Image

Use a wider composition that identifies the target region:

- Shows the region or relevant surroundings
- Is not excessively zoomed out
- Keeps the target region near center
- Preserves central space for text
- Does not leave a search panel or unnecessary UI in the main view
- Is not dominated by unloaded or flat-color areas
- Retains required attribution in readable form

## 6. Second Image

Use the same region and change all of the following:

- Zoom in slightly compared with the first image.
- Change the heading or camera direction clearly.
- Change the composition and center visibly.

Do not move to another region. A same-heading enlargement, crop, or slight pan is prohibited. When the source cannot provide a clearly different heading or camera direction, STOP and do not create the second image.

## 7. Cropping

Crop the permitted image-display area to `1000×750`.

Exclude:

- Chrome tabs, address bar, and bookmarks bar
- Search panels and unnecessary controls
- Operating-system taskbar
- Notifications, popups, and unnecessary margins

Do not exclude:

- Required attribution for Google Maps or another source
- Google logo
- Copyright notice
- Aerial-imagery or map-data provider notice

STOP only when the existing design cannot be achieved while preserving embedded rights notices or adding the exact required compliance attribution under Section 8.

## 8. Fixed Text Template

Text placement is fixed and MUST NOT be selected by eye or adjusted to suit the
background. Use the approved Wakabacho pair as the visual reference:

- `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-wakabacho_1.jpg`
- `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-wakabacho_2.jpg`

On the final `1000 x 750` canvas, use these authoritative measured anchors:

- Main-title visual bounding-box center: `x = 500 +/- 5 px`, `y = 320 +/- 5 px`.
- Subtitle visual bounding-box center: `x = 500 +/- 5 px`, `y = 400 +/- 5 px`.
- Corresponding text centers in `_1` and `_2` MUST differ by no more than `2 px`
  on either axis.
- Both lines MUST use center alignment on `x = 500 px`.
- The main title MUST remain within the horizontal safe range `x = 80..920`.

If a long title does not fit, reduce its font size while preserving the fixed
anchor. Never move the text away from the fixed center.

Region name:

- Uppercase letters
- White, bold, and centered on the fixed anchor
- Largest text in the image
- One line by default

Place this fixed text below the region name:

`Kagoshima Area Information`

Fixed text:

- Preserve capitalization exactly.
- White and centered on the fixed anchor
- Smaller than the region name

Use one shared render template for both images. Match typeface, weight, size,
and line height to the approved reference. Do not position `_1` and `_2`
independently.

The renderer MUST receive `EXPECTED_DISPLAY_NAME` directly. Retyping the title
for either image is prohibited. Before saving, record the exact renderer input
and character count for both images and compare them byte for byte with the
confirmed display value. Any one-character difference rejects the pair.

Add only the white title and white subtitle as design copy. A legally or contractually required source-attribution line is compliance text and is expressly excluded from this design-copy restriction. When the source requires attribution that is not already embedded, add the smallest readable exact attribution in a corner, separate from the title and subtitle, without covering geographic content or changing the background. Do not add a black or translucent rectangle, dark band, gradient, vignette, background darkening, outline, stroke, halo, drop shadow, duplicated offset shadow text, glow, frame, badge, label, or text panel. When white title text is not readable without one of these prohibited treatments, select a different clean source composition.

When a measured text template exists, prioritize it over visual matching.

After rasterization, record the measured center of both text lines in both
images. A visual assertion such as "centered" is not acceptance evidence.
Reject and recreate the pair when any measured center is outside tolerance.
When the fixed placement is unreadable, replace the background capture; do not
move or decorate the text.

## 9. Legibility

- Region name and fixed text are immediately readable.
- White text does not disappear into the background.
- Text remains inside the image.
- Region name and fixed text do not overlap.
- Text position matches between both images.
- Main-title and subtitle centers satisfy the fixed numeric template.
- The background is not excessively dark.
- Required attribution and added text do not overlap.
- The result does not differ materially from existing images.
- No map labels, POI markers, selected-area boundaries, or unnecessary controls remain.
- No black or translucent text background, dark band, outline, halo, or shadow exists.
- The second image uses both a closer zoom and a clearly different heading or camera direction.

## 10. Filenames

```text
kagoshima-deliveryhealth-area-<slug>_1.jpg
kagoshima-deliveryhealth-area-<slug>_2.jpg
```

- Prefix: `kagoshima-deliveryhealth-area-`
- Slug: exact canonical value from the target text file
- Lowercase with no spaces
- Same slug for both images of one region
- Distinguish only with `_1` and `_2`
- Extension: `.jpg`

## 11. Storage and Overwrite Prevention

Save to the canonical public source `HP/imgHtml/new_202601/area/`.

Before saving, check for a same-name file. When present, compare hashes. Do not save when identical. When contents differ, do not overwrite; report the difference and required decision.

Use JPG quality that preserves readable text and terrain without visible degradation and does not differ materially from existing images.

## 12. Page Integration

Place both images in the specified locations on the target area page. Verify:

- `_1` and `_2` order
- Filename case
- Destination and source HTML reference
- OGP, `src`, and alt text
- Absence of legacy image names
- Desktop and mobile rendering
- No clipping of text or attribution caused by cropping

For non-image page outputs and shared registrations, follow `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` and `CANDY_AREA_PAGE_GENERATION_SPEC.md`.

## 13. Completion Criteria

- [ ] Storage, modification, and commercial-publication conditions for the image source are verified.
- [ ] Required attribution is preserved or added exactly as required by the permitted source.
- [ ] The target region is correct.
- [ ] The first image is a wider view.
- [ ] The second image is slightly closer with a different angle or direction.
- [ ] The two images do not use the same composition.
- [ ] No place, road, facility, business, or POI labels remain.
- [ ] No pins, category icons, selected boundaries, search results, or unnecessary controls remain.
- [ ] No black or translucent text background, dark band, gradient, vignette, outline, halo, or shadow exists.
- [ ] The second image is not a same-heading enlargement, crop, or slight pan of the first image.
- [ ] Romanized region name and canonical slug are correct.
- [ ] Display-name authority and `EXPECTED_DISPLAY_NAME` are recorded.
- [ ] The title was not derived from the slug, filename, URL, or automatic transliteration.
- [ ] `_1` and `_2` renderer title strings match `EXPECTED_DISPLAY_NAME` byte for byte.
- [ ] Expected, `_1`, and `_2` display-name character counts are identical.
- [ ] `Kagoshima Area Information` is exact.
- [ ] The four measured text centers satisfy the fixed coordinate tolerances.
- [ ] Corresponding `_1` and `_2` text centers differ by no more than `2 px` per axis.
- [ ] Both images are 1000×750 JPG files.
- [ ] Filenames, destination, and HTML references agree.
- [ ] Desktop and mobile rendering are correct.
- [ ] No broken link or existing-layout damage exists.

## 14. STOP Conditions

- The target region cannot be uniquely identified.
- Official romanization or canonical slug cannot be confirmed.
- The only proposed display spelling is derived from a slug, filename, URL, memory, or automatic transliteration.
- Renderer title identity or character count cannot be verified.
- Storage, modification, and commercial-publication conditions for the image source cannot be verified.
- Required attribution cannot be preserved or added in the permitted compliance form.
- Chrome cannot be operated.
- Map, 3D, or aerial imagery cannot load.
- A screenshot cannot be saved.
- The image-modification tool cannot run.
- Existing-image dimensions or text specification cannot be verified.
- A clean label-free and control-free capture cannot be obtained and required attribution cannot be added separately in the permitted compliance form.
- A second capture with both closer zoom and a clearly different heading or direction cannot be obtained.
- White text requires a prohibited background or text effect to remain readable.
- The fixed text coordinates cannot be met or cannot be verified numerically.
- The destination cannot be confirmed.
- Same-name overwrite eligibility cannot be decided.

Do not STOP solely because a permitted source requires an attribution line that is absent from its clean capture. Add the required compliance text under Section 8 and continue.

Do not STOP or request duplicate approval for a task-specific decision that the project administrator has already stated explicitly. On a genuine unresolved STOP condition, report the stopped phase, verified items, unexecuted work, and exact decision still required.

## 15. Extension to Other Categories

Do not copy area-specific elements directly to hotel, blog, or another project.

Candidate common elements are image-source and rights checks, Chrome operation, cropping, attribution, overwrite prevention, and quality checks. Keep count, composition, text, fixed copy, naming, destination, and page integration in category-specific specifications.
