# Hotel Image Bulk Handoff

## Purpose

Continue `TASK-20260723-HOTEL-IMAGE-BULK-NORMALIZATION-001` and `COMM-20260723-020` from another Codex local working folder.

- Repository: `makotonishikubo0418-cmd/candy`
- Branch: `main`
- Transfer snapshot: `codex/data/hotel-image-handoff-20260723/`
- After pulling `main`, verify this snapshot against `SHA256SUMS.tsv`, then copy it to a user-authorized local working folder before resuming image work.
- Do not use a NAS, network share, cloud location, backup directory, or another transfer destination unless the user explicitly authorizes that exact destination for the current task.
- This repository directory is a transfer snapshot only. It is not accepted-source storage, public-source storage, or the active image-production folder.

## Current Progress

- Targets: 69 hotels.
- Image `_1`: 69 Google Earth 3D rendered candidates exist and were visually reviewed.
- Image `_2`: 15 Google Earth top-down source/evidence pairs exist for target indices `0..14`.
- Remaining `_2`: 54 targets at indices `15..68`.
- Resume target: index `15`, slug `kisyabahotel`.
- `earth2d_records.json` contains 15 `CAPTURED` rows and seven later `ERROR` rows caused by a closed browser tab. Actual source-file existence is authoritative.

## Active Inputs

- `hotel_image_bulk_targets.json`: ordered 69-target list, identity, address, slug, and source Text path.
- `hotel_image_bulk_english_names.json`: exact approved English display name for all 69 slugs.
- `hotel-image-bulk/sources/<slug>_earth.jpg`: 69 Google Earth 3D raw sources.
- `hotel-image-bulk/sources/<slug>_earth2d.jpg`: 15 completed top-down raw sources.
- Matching `*_earth2d_evidence.jpg`: identity evidence for the 15 completed top-down sources.
- `hotel-image-bulk/candidates/<slug>/<slug>_1.jpg`: 69 completed `_1` rendered candidates.
- `hotel-image-bulk/contact-sheets/earth_01.jpg` through `earth_08.jpg`: `_1` visual-review sheets.
- `hotel-image-bulk/capture_records.json` and `earth2d_records.json`: progress and source-URL records.

## Rejected or Superseded Data

Do not install, accept, or use any of the following as final `_2` data:

- Every `hotel-image-bulk/sources/*_maps*.jpg` file.
- Every existing `hotel-image-bulk/candidates/<slug>/<slug>_2.jpg` file.
- Every existing `*_image_manifest.json`; each records the rejected Maps `_2` source and old absolute PC paths.
- `maps_*.jpg` contact sheets.
- `coordinate_maps_records.json` and `final_maps_records.json`.
- `quintessa_maps_z16_test.jpg`.

For `kokohotelkagoshimatenmonkan`, `grandbasekagoshimachuo`, and `hotelnewnishino`, use `*_earth_evidence_retry.jpg` instead of the superseded normal Earth evidence file. `cococlass_earth_evidence.jpg` is absent; reconfirm its `_1` identity before final acceptance.

## Google Earth Top-Down Capture Procedure

1. Use a fresh Google Earth browser tab with a `1280 x 960` viewport.
2. Read the next target from `hotel_image_bulk_targets.json`.
3. Search by the confirmed address only.
4. Wait for all imagery to load.
5. Save identity evidence while the result panel, target label, and marker are visible as:
   `hotel-image-bulk/sources/<slug>_earth2d_evidence.jpg`
6. Close the result panel and save the clean top-down view before switching to 3D as:
   `hotel-image-bulk/sources/<slug>_earth2d.jpg`
7. Append the result to `earth2d_records.json`.
8. Continue through target index `68`.

## Rendering and Acceptance

1. Update `codex/docs/CANDY_HOTEL_IMAGE_CREATION_SPEC.md` minimally so `_2` is Google Earth top-down 2D instead of Google Maps.
2. Render all 69 pairs with:
   - `_1` source: `<slug>_earth.jpg`
   - `_2` source: `<slug>_earth2d.jpg`
   - `_1` crop: `140,100,1000,750`
   - `_2` crop: `140,100,1000,750`
3. Until the CLI parameter is renamed, pass the Earth top-down source through `--maps-source` and its crop through `--maps-crop`.
4. Regenerate every candidate pair and manifest. Do not reuse the old mixed manifests.
5. Regenerate contact sheets and visually inspect all 138 final candidates.
6. Run `image-check` for all 69 targets; require 138 unique hashes, no cross-hotel duplicates, and different hashes within each pair.
7. Only after all hard gates pass, copy exact candidate bytes to the accepted and public locations defined by `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md`.
8. Verify accepted/public same-name SHA-256 equality, rerun all target `direct-check` commands, run the full hotel audit, and synchronize generated state through the canonical generator.

## Current Restrictions

- Do not edit hotel Text files.
- Do not overwrite the three legacy public hotel-image pairs.
- Do not generate hotel pages.
- Do not Commit, Push, run Actions, modify a database, or deploy without separate authorization.
- Do not modify this repository transfer snapshot as the active image-production folder.

## Transfer Verification

1. Pull `main`.
2. Verify every file in `codex/data/hotel-image-handoff-20260723/` against `SHA256SUMS.tsv`.
3. Copy the verified snapshot to the exact local working folder authorized by the user.
4. Resume from target index `15`, slug `kisyabahotel`.
