# CANDY Existing Area-Image Replacement Runbook

- Purpose: Replace an existing public area-image pair quickly, once, and without retaining obsolete live content
- Status: Canonical execution runbook for existing area-image replacement
- Applies to: Approved replacement images that retain the existing canonical `_1` and `_2` filenames
- Does not apply to: Creating or materially editing the replacement images

## 1. Responsibility Boundary

This runbook owns the normal same-name replacement sequence only when all of
these conditions hold:

- The replacement pair is already approved and requires no further image creation or editing.
- The canonical slug and existing filenames do not change.
- The normal protected Push-triggered deployment is used.
- No deletion, rename, rollback, workflow change, manual FTP, or server exception is required.

Image creation, asset ownership ambiguity, deployment exceptions, Git, and
production authority remain in the applicable documents selected from root
`AGENTS.md` Section 5.2; this runbook does not add or override those routes.

## 2. Replacement Preconditions

Before changing files, verify:

1. The exact target area and canonical slug.
2. The existing canonical `_1` and `_2` filenames.
3. The replacement pair is readable JPG, exactly `1000 x 750`, visually approved, and has different hashes and compositions.
4. The accepted-source and canonical-public destinations.
5. Every controlled reference to either public image.
6. No existing change or active reservation overlaps the target files.

If any replacement image still requires production or material editing, leave this route, complete the image-creation route, and return here only after the pair passes its acceptance gates.

## 3. One Replacement Work Unit

The replacement work unit contains only the required target files:

- `Text_area_data/画像データ/kagoshima-deliveryhealth-area-<slug>_1.jpg`
- `Text_area_data/画像データ/kagoshima-deliveryhealth-area-<slug>_2.jpg`
- `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-<slug>_1.jpg`
- `HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-<slug>_2.jpg`
- Every controlled source, OGP, template, or generated-source reference that produces either public image URL
- Generated current-state documents changed by the canonical generator

Do not include unrelated images, pages, cleanup, formatting, or refactoring.

## 4. Replacement Procedure

Use the integrated replacement command. `<new-image-1>` and `<new-image-2>` are the already approved replacement JPEGs and MUST be outside the four canonical target paths.

Preview without changing files:

```powershell
codex\scripts\candy-area.cmd replace-images --slug "<slug>" --image1 "<new-image-1>" --image2 "<new-image-2>"
```

After reviewing the exact targets and hashes printed by preview, execute the same plan:

```powershell
codex\scripts\candy-area.cmd replace-images --slug "<slug>" --image1 "<new-image-1>" --image2 "<new-image-2>" --write
```

The command performs one transaction:

1. Validate both input JPEGs as `1000 x 750` and reject an identical pair.
2. Verify that the four canonical target files and every controlled reference are tracked and have no existing uncommitted change.
3. Compute SHA-256 and its first seven lowercase hexadecimal characters for each image.
4. Replace both accepted-source images and both canonical-public images under the existing filenames.
5. Find every controlled HTML, OGP, template, CSS, JavaScript, or generated-source reference to the two canonical public paths and set `?v=<content-version>`.
6. Run the mandatory replacement guard against the completed worktree.
7. If any write or guard check fails, restore every file changed by this command.

The command does not retain the obsolete image under another filename and does not perform Commit, Push, Actions, or production deployment.

## 5. Fast Local Validation

Run only the checks required for this replacement:

1. Treat `AREA_IMAGE_REPLACE_OK=<slug>` and the preceding `AREA_IMAGE_REPLACEMENT_GUARD: passed` as the authoritative asset/reference result.
2. Run:

```powershell
codex\scripts\candy-site-state.cmd preview-sitemap-lastmod
codex\scripts\candy-site-state.cmd sync-sitemap-lastmod
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

3. Review the exact target-file diff. Do not repeat the same validation through multiple tools when one authoritative result already passed.

For diagnosis or an explicit guard rerun, use `python .github\scripts\candy_area_image_replacement_guard.py --before HEAD --worktree`. The integrated write command already invokes this gate automatically.

## 6. Publication-Specific Verification

When publication is included by the applicable Git and production routes
selected from root `AGENTS.md` Section 5.2, keep the asset and every controlled
reference in one deployment unit.

The normal production deployment invokes the same area-image replacement guard before it creates the FTP plan. A failed guard exits the Actions run before any FTP connection or production change.

After Actions succeeds, verify:

1. The production canonical image paths match the local replacement SHA-256.
2. The production page response contains both new content-version URLs.
3. Each versioned URL returns the new image.
4. One desktop page load shows both replacement images in the correct order.
5. One mobile page load shows both replacement images without layout damage.
6. Browser DOM `currentSrc` values contain the new content versions.
7. No obsolete live copy, temporary file, or rollback file remains.

Use exact image locators or DOM references for below-fold images. Do not use repeated freehand scrolling when a direct locator can bring the image into view.

## 7. Responsibility Transfers and STOP Conditions

These conditions leave the responsibility of this runbook:

| Condition | Responsibility source selected by the root route |
|---|---|
| Replacement images must be created or edited | `CANDY_AREA_IMAGE_CREATION_RUNBOOK.md` and `CANDY_AREA_IMAGE_CREATION_SPEC.md` |
| Slug, filename, accepted/public ownership, or duplication is ambiguous | `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` |
| Manual deployment, workflow change, rollback, deletion, rename, or server exception is required | `CANDY_PRODUCTION_MIGRATION_MASTER.md` |

STOP only the affected operation when the replacement target, authority, new image identity, controlled references, or recovery path cannot be verified. Do not expand into unrelated investigation.

## 8. Completion Criteria

- [ ] The obsolete image content was replaced under the same canonical filenames.
- [ ] No obsolete live copy or alternate replacement filename remains.
- [ ] Accepted/public same-name SHA-256 values match.
- [ ] `_1` and `_2` remain different images.
- [ ] Every controlled public reference uses the new content version.
- [ ] The asset and references belong to one target-limited work unit.
- [ ] Required generated-state and Git checks passed.
- [ ] When publication was authorized, one deployment completed and desktop/mobile showed the new images.
