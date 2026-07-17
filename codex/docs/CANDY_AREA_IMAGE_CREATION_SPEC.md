# CANDY Area Image Creation Specification

- Purpose: Safely produce two regional images for an area page while matching the existing design
- Status: conditional canonical document
- Updated: 2026-07-16
- Applies to: Image production for candy area pages

## 1. Priority and Rights Conditions

This document defines composition, modification, naming, storage, and page integration. Follow `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` for image acceptance, slug reconciliation, duplication checks, and Git management.

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
- Lowercase slug matching canonical
- Image source and usage conditions
- Public destination
- Source HTML reference
- Same-name file existence
- Existing area image used as the design reference

Reconcile romanization and slug against canonical in the target text file, existing pages, existing filenames, and management documents. Do not mix multiple spellings for one region or automatically convert a mismatch candidate.

## 4. Chrome and Map Display

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

Close or frame out place labels, road names, facility names, search panels, and related elements only when doing so does not impair required rights notices. Capture only after map rendering completes and buildings or terrain are clear.

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

Use the same region and change both:

- Zoom in slightly compared with the first image.
- Change the angle or direction.

Do not move to another region. The difference from the first composition must be visible. Verify central text space, completed rendering, and required attribution.

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

STOP when the existing design cannot be achieved while preserving required rights notices.

## 8. Text Placement

Place the romanized region name in the image center.

Region name:

- Uppercase letters
- White, bold, and centered
- Largest text in the image
- One line by default

Place this fixed text below the region name:

`Kagoshima Area Information`

Fixed text:

- Preserve capitalization exactly.
- White and centered
- Smaller than the region name

Match typeface, weight, size, line height, position, shadow, outline, and darkening to the existing reference image selected at task start. Do not add custom decoration, strong bands, frames, or new gradients.

When a measured text template exists, prioritize it over visual matching.

## 9. Legibility

- Region name and fixed text are immediately readable.
- White text does not disappear into the background.
- Text remains inside the image.
- Region name and fixed text do not overlap.
- Text position matches between both images.
- The background is not excessively dark.
- Required attribution and added text do not overlap.
- The result does not differ materially from existing images.

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
- [ ] Required attribution is preserved.
- [ ] The target region is correct.
- [ ] The first image is a wider view.
- [ ] The second image is slightly closer with a different angle or direction.
- [ ] The two images do not use the same composition.
- [ ] Romanized region name and canonical slug are correct.
- [ ] `Kagoshima Area Information` is exact.
- [ ] Both images are 1000×750 JPG files.
- [ ] Filenames, destination, and HTML references agree.
- [ ] Desktop and mobile rendering are correct.
- [ ] No broken link or existing-layout damage exists.

## 14. STOP Conditions

- The target region cannot be uniquely identified.
- Official romanization or canonical slug cannot be confirmed.
- Storage, modification, and commercial-publication conditions for the image source cannot be verified.
- Required attribution cannot be preserved.
- Chrome cannot be operated.
- Map, 3D, or aerial imagery cannot load.
- A screenshot cannot be saved.
- The image-modification tool cannot run.
- Existing-image dimensions or text specification cannot be verified.
- The destination cannot be confirmed.
- Same-name overwrite eligibility cannot be decided.

On STOP, report the stopped phase, verified items, unexecuted work, and required decision.

## 15. Extension to Other Categories

Do not copy area-specific elements directly to hotel, blog, or another project.

Candidate common elements are image-source and rights checks, Chrome operation, cropping, attribution, overwrite prevention, and quality checks. Keep count, composition, text, fixed copy, naming, destination, and page integration in category-specific specifications.
