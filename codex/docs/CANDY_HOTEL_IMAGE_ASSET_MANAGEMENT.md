# CANDY Hotel Image Asset Management

- Updated: 2026-07-24
- Target: Acceptance, accepted-source storage, local public installation, replacement, Git management, and production publication of hotel-page image pairs
- Status: Canonical specification
- Creation and visual authority: `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`
- Page-production authority: `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`
- Production-deployment authority: `CANDY_PRODUCTION_MIGRATION_MASTER.md`

## 1. Responsibility and Boundary

This document owns the hotel-image lifecycle after an image pair is created or received. It defines where an accepted pair is stored, when it may be installed into the public source, how same-name files are reconciled, what Git unit is required, and when the pair may be called published.

It does not redefine image composition, capture, renderer, dimensions, title, or visual acceptance. Those requirements remain in `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`.

It does not authorize page generation, Commit, Push, Actions, FTP, production replacement, deletion, or rename. Use the user-authorized result and the applicable canonical procedure for those operations.

## 2. Storage Classes

| Class | Canonical location | Responsibility |
|---|---|---|
| Candidate | Outside the canonical accepted and public folders | Unaccepted working output. It MUST NOT be referenced by a page or reported as accepted |
| Accepted local source | `Text_hotel_data/画像データ/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Git-managed source of an image pair that passed every creation and acceptance gate |
| Canonical local public source | `HP/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Exact bytes used by local page source and eligible for production deployment |
| Target Text reference | `./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Canonical relative references used by the hotel generator |
| OGP reference | `https://www.55810.com/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` | Canonical absolute OGP reference |
| Production public asset | The deployed public URL under `/imgHtml/new_202601/hotel/` | Runtime result only. It is not an accepted-source or specification authority |

Public HTML, OGP, JSON-LD, CSS, and PHP MUST NOT reference `Text_hotel_data/画像データ/`.

The accepted-source directory MAY be absent before its first accepted pair. Create it only as part of an authorized first acceptance; do not add a placeholder file solely to preserve an empty directory.

The NAS is backup storage only. It is not an accepted hotel-image source and MUST NOT be used as the current basis for page production.

## 3. Identity and Filename Contract

One hotel image unit contains exactly two files:

```text
<CANONICAL_SLUG>_1.jpg
<CANONICAL_SLUG>_2.jpg
```

- `_1` is the main and OGP image.
- `_2` is the hotel-basic-information image.
- `CANONICAL_SLUG` MUST equal the current target Text and canonical URL slug.
- The accepted and public filenames MUST be byte-for-byte identical.
- The image display title is a separate confirmed value and MUST NOT be inferred from the slug.
- Renaming, alternate-slug pairing, extension conversion, and reuse for another hotel are prohibited without an explicit target-specific instruction.

## 4. Lifecycle States

| State | Meaning | Permitted next action |
|---|---|---|
| `CANDIDATE` | The pair has not passed every hard gate | Correct or reject it outside the accepted/public folders |
| `ACCEPTED` | Both accepted-source files exist and passed the creation and acceptance gates | At page-production start, perform the target-limited first local public installation without duplicate approval |
| `INSTALLED_LOCAL` | Accepted and public pairs exist under the same names and each same-name SHA-256 matches | Enter the local target/build route or the image-registration route |
| `REGISTERED_GIT` | Both pairs are tracked and clean in the current `HEAD`, and that `HEAD` is synchronized with `origin/main` | Deploy and verify the public pair before page publication |
| `DEPLOYED_ASSET` | Actions deployed the public pair and production bytes match; the hotel page may not yet exist | Enter the page-publication route |
| `PUBLISHED` | The hotel page was deployed, its image references and production bytes were verified, and the required rendering checks passed | Maintain the pair under normal Git and production rules |
| `LEGACY_PUBLIC_ONLY` | A public file or pair exists without a verified accepted-source counterpart | Preserve it; do not backfill, promote, replace, rename, or delete automatically |
| `REVIEW` | Identity is known, but a same-name content difference or human composition choice remains | Obtain the exact target decision before overwrite or replacement |
| `STOP` | Identity, pair completeness, file integrity, slug, reference, or recovery cannot be verified | Resolve the stated blocker before continuing |

`ACCEPTED`, `INSTALLED_LOCAL`, `REGISTERED_GIT`, `DEPLOYED_ASSET`, and `PUBLISHED` are different states. Do not report one as another.

## 5. Acceptance Procedure

1. Fix one `SOURCE_ROUTE` as `DIRECT_TEXT` or `PHASE_PREPARED`.
2. Verify the exact target Text, hotel identity, address, `HOTEL_NAME_EN`, and `CANONICAL_SLUG` through the applicable start route.
3. Keep unaccepted output outside both canonical storage classes.
4. Validate both images against every hard gate in `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`.
5. Verify exactly two readable RGB JPG files at `1000 x 750` with the standard filenames.
6. Verify `_1` and `_2` have different SHA-256 values and materially different compositions.
7. Check complete-hash duplication against other hotel images. A match to another hotel is `STOP` unless the target identity proves it is the same canonical asset and the user explicitly authorized reuse.
8. Verify the target Text relative paths and OGP absolute path.
9. Inspect both exact accepted-source and public-source names before writing either location.
10. Apply the reconciliation matrix in Section 6.
11. Only after every acceptance gate passes, save the pair under `Text_hotel_data/画像データ/`.
12. Reopen the accepted files and verify format, dimensions, color mode, names, SHA-256 values, and pair difference.
13. Record the result independently from local public installation and production publication.

A partial pair is never accepted. Do not place one accepted file while the other file remains a candidate.

## 6. Accepted/Public Reconciliation

| Accepted source | Local public source | Hash relationship | State and action |
|---|---|---|---|
| Absent pair | Absent pair | Not applicable | New acceptance may create the accepted pair; first installation is a separate next state |
| Complete pair | Absent pair | Not applicable | `ACCEPTED`; copy exact accepted bytes only through an authorized first installation |
| Complete pair | Complete pair | Each same-name hash matches | `INSTALLED_LOCAL`; do not rewrite either pair |
| Complete pair | Complete pair | Any same-name hash differs | `REVIEW`; do not overwrite. Use the replacement route in Section 8 after explicit authority |
| Absent pair | Complete pair | Not applicable | `LEGACY_PUBLIC_ONLY`; preserve the public pair and do not create an accepted copy by assumption |
| Partial pair | Any state | Any state | `STOP`; identify the missing or extra file and recovery method |
| Any state | Partial pair | Any state | `STOP`; do not publish or repair by inference |

If `_1` and `_2` have the same hash, the pair is `STOP` even when accepted/public same-name hashes otherwise agree.

## 7. First Local Public Installation

First installation applies only when both canonical public filenames are absent.

1. Require `IMAGE RESULT: PASS` and a complete accepted pair.
2. A page-production request authorizes the first local installation required
   by its canonical workflow for that target. Perform it before the final page
   target gate without another question. A standalone image-acceptance-only
   request may end at `ACCEPTED`.
3. Copy the accepted files without re-rendering, re-encoding, resizing, metadata editing, or renaming.
4. Verify that each accepted/public same-name SHA-256 matches.
5. Verify that the public pair remains two different hashes.
6. Verify the target Text relative paths and OGP path again.
7. Rerun `direct-check` for `DIRECT_TEXT`, or finish the Phase 4 gate for `PHASE_PREPARED`.

Local installation does not mean that a page exists, a Commit was created,
GitHub was updated, or production serves the image. It is nevertheless a
required continuation step of an authorized page-production task, not a
completion report or STOP point. The current hotel publication command
requires public images to be tracked and clean dependencies, so a newly
installed pair MUST complete Section 9 before page publication.

## 8. Existing Same-Name Replacement

An existing public filename with different proposed bytes is not a first installation.

- Require explicit target-specific replacement or overwrite authority.
- Treat the hotel pair as one inspection and rollback unit. Inspect both accepted and public files even when only one file's bytes change.
- Validate the proposed pair through `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` before acceptance.
- Do not create an accepted-source copy of a legacy public file merely to make the hashes agree.
- Preserve recoverability through Git and the transactional deployment procedure.
- Follow Section 5.1 of `CANDY_PRODUCTION_MIGRATION_MASTER.md` for same-path cacheable-asset replacement, including controlled content-version references and production rollback.
- If the current hotel generator, Text format, or controlled source cannot preserve every required replacement reference, `STOP` before modifying the public pair.
- Deletion, rename, slug correction, and cross-hotel replacement remain separate operations.

No hotel-specific replacement automation or production guard is established by this document. Do not describe manual checks as an automated guard.

## 9. Git Registration and Asset Deployment Unit

- Accepted-source and local-public image pairs are both Git-managed.
- For a new pair, register the accepted pair and public pair together in one target-limited image-asset Commit before invoking hotel page publication.
- The current `candy_hotel_publish.py` treats the public pair as tracked, clean dependencies and does not stage new image files. Do not include untracked or modified image files in the later page Commit.
- The accepted pair is management/source evidence and MUST NOT be deployed as a public target.
- The public pair is the only deployable output of this image-asset unit. It may be deployed before the page exists, but that state is `DEPLOYED_ASSET`, not `PUBLISHED`.
- Stage only the exact two accepted-source files, two local-public copies, and any generated current-state files required by the canonical state tool. `git add .` and `git add -A` are prohibited.
- Commit and Push require explicit authority unless they are part of an explicitly requested canonical page-publication result.
- When a user explicitly requests creation and publication of a hotel page
  whose complete accepted pair is not yet locally public, the required
  target-limited sequence is two separately reported Git units: first the
  image-asset Commit, Push, Actions deployment, and production-byte
  verification; then the page-production Commit, Push, Actions deployment,
  and page verification. Both units belong to the same authorized task; do not
  stop between them for duplicate approval.
- Do not move accepted images to the public folder. Copy exact bytes and retain both storage responsibilities.
- Do not remove an accepted pair after publication merely because a public pair exists.

After the image-asset Push, require `HEAD` and `origin/main` to match, both pairs to be tracked and clean, Actions to succeed for that exact Commit SHA, and both production image bytes to match the local public hashes. Only then set `DEPLOYED_ASSET` and start the page-publication unit.

## 10. Production Publication and Verification

Use `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` for a new hotel page. A newly created pair MUST have reached `DEPLOYED_ASSET`; an unchanged legacy public-only pair MUST already be tracked and clean. Do not perform an independent FTP upload before or after the canonical Git and Actions routes.

Before staging:

1. Verify `DEPLOYED_ASSET` for a newly accepted pair, or verified tracked-and-clean dependency status for an unchanged `LEGACY_PUBLIC_ONLY` pair.
2. Verify the target Text, generated source `src`, alt values, OGP, and any JSON-LD image reference.
3. Verify no unauthorized image, deletion, rename, copy source, or unrelated accepted asset is staged.
4. Run the required page, generated-state, and diff checks.

After an authorized deployment:

1. Confirm page-publication Actions success for the exact page Commit SHA.
2. Re-download both production image URLs and verify HTTP success, JPG content, `1000 x 750`, and SHA-256 equality with the local public pair.
3. Verify the production page references the intended `_1` and `_2` URLs and OGP uses `_1`.
4. Verify the required desktop and mobile views render the intended pair.
5. Report accepted-source, local-public, image-asset Git/Actions, page Git/Actions, production bytes, page references, and rendering as separate results.

Only then may the pair be reported as `PUBLISHED`.

## 11. STOP and Review Conditions

`STOP` when:

- The target Text, hotel identity, address, display name, or canonical slug is unavailable or conflicting.
- The source Text is legacy, invalid, partial, or contains placeholder image paths.
- Either image is missing, unreadable, misnamed, not RGB JPG, or not `1000 x 750`.
- The pair is identical, materially the same composition, or belongs to another hotel.
- The target Text, accepted filenames, public filenames, relative paths, and OGP path do not agree.
- One storage class contains a partial pair.
- A write, replacement, deletion, rename, or publication exceeds the authorized target.
- A production replacement cannot preserve cache correctness and rollback.

Use `REVIEW` when:

- The exact same accepted or public filename exists with different bytes.
- The target identity is confirmed but the acceptable composition requires a human choice.
- A legacy public-only pair is proposed for replacement or formal re-acceptance.

## 12. Required Result Record

```text
SOURCE_ROUTE: DIRECT_TEXT / PHASE_PREPARED
IMAGE LIFECYCLE: CANDIDATE / ACCEPTED / INSTALLED_LOCAL / REGISTERED_GIT / DEPLOYED_ASSET / PUBLISHED / LEGACY_PUBLIC_ONLY / REVIEW / STOP
TARGET_TEXT_PATH:
CANONICAL_SLUG:
ACCEPTED_IMAGE_1_PATH_AND_SHA256:
ACCEPTED_IMAGE_2_PATH_AND_SHA256:
PUBLIC_IMAGE_1_PATH_AND_SHA256:
PUBLIC_IMAGE_2_PATH_AND_SHA256:
ACCEPTED_PUBLIC_HASH_MATCH: YES / NO / NOT_APPLICABLE
PAIR_HASH_DIFFERENCE: PASS / FAIL
CREATION_ACCEPTANCE_GATE: PASS / FAIL / NOT_EXECUTED
TARGET_TEXT_PATH_AGREEMENT: PASS / FAIL
LOCAL_INSTALLATION: PASS / FAIL / NOT_EXECUTED
IMAGE_ASSET_COMMIT_PUSH: PASS / FAIL / NOT_EXECUTED
IMAGE_ASSET_ACTIONS_DEPLOYMENT: PASS / FAIL / NOT_EXECUTED
IMAGE_ASSET_COMMIT_URL:
IMAGE_ASSET_ACTIONS_URL:
PRODUCTION_IMAGE_BYTES: PASS / FAIL / NOT_EXECUTED
PAGE_COMMIT_PUSH: PASS / FAIL / NOT_EXECUTED
PAGE_ACTIONS_DEPLOYMENT: PASS / FAIL / NOT_EXECUTED
PAGE_COMMIT_URL:
PAGE_ACTIONS_URL:
PRODUCTION_PAGE_URL:
PRODUCTION_PAGE_REFERENCES: PASS / FAIL / NOT_EXECUTED
DESKTOP_MOBILE_RENDERING: PASS / FAIL / NOT_EXECUTED
HUMAN_DECISION_REQUIRED:
```

## 13. Completion Criteria

- [ ] One canonical target Text and slug were fixed.
- [ ] Both images passed the creation specification.
- [ ] The accepted-source pair is complete and internally different.
- [ ] Same-name accepted/public states were reconciled without inference.
- [ ] Any local public installation copied exact accepted bytes.
- [ ] Target Text paths and OGP agree.
- [ ] Any replacement had explicit authority, cache handling, and rollback.
- [ ] Git scope contains only authorized files.
- [ ] A newly accepted pair reached `REGISTERED_GIT` and `DEPLOYED_ASSET` before page publication.
- [ ] Local acceptance, local installation, image-asset Git/Actions, page Git/Actions, production bytes, page references, and rendering were reported separately.
- [ ] No unverified state was reported as `PUBLISHED`.
