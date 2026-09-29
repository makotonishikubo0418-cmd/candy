# CANDY Hotel Image Asset Management

- Updated: 2026-08-18
- Target: Acceptance, accepted-source storage, unpublished-public-copy retention and removal, local public installation, replacement-unit boundaries, and publication-state verification of hotel-page image pairs
- Status: Canonical specification
- Creation and visual authority: `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`
- Page-production authority: `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`
- Production-deployment authority: `CANDY_PRODUCTION_MIGRATION_MASTER.md`

## 1. Responsibility and Boundary

This document owns the hotel-image lifecycle after an image pair is created or
received. It defines where an accepted pair is stored, when it may be installed
into the public source, how same-name files are reconciled, which files form one
asset unit, when an unpublished public copy may be removed, and when the pair
may be called published.

It does not redefine image composition, capture, renderer, dimensions, title, or visual acceptance. Those requirements remain in `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`.

It does not define routing, operation authority, Git procedure, deployment
procedure, or response format. Those remain in the documents selected
from the applicable routes in `codex/WORK_ROUTING.md` Section 5.2.

## 2. Storage Classes

| Class | Canonical location | Responsibility |
|---|---|---|
| Candidate | Outside the canonical accepted and public folders | Unaccepted working output. It MUST NOT be referenced by a page or reported as accepted |
| Accepted local source | `Text_hotel_data/画像データ/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Git-managed, non-public source of an image pair that passed every creation and acceptance gate; retain it regardless of public-copy state |
| Canonical local public source | `HP/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Deployable copy required by a published page or by an actively authorized page-publication operation; it may be absent otherwise |
| Target Text reference | `./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` and `_2.jpg` | Canonical relative references used by the hotel generator |
| OGP reference | `https://www.55810.com/imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg` | Canonical absolute OGP reference |
| Production public asset | The deployed public URL under `/imgHtml/new_202601/hotel/` | Runtime copy required by a published page; it is not an accepted-source or specification authority |

Public HTML, OGP, JSON-LD, CSS, and PHP MUST NOT reference `Text_hotel_data/画像データ/`.

Accepted-source retention and public-copy retention are separate decisions.
The accepted pair MUST remain stored. A public pair MUST be retained only while
an exact published page requires it or while the exact page is moving through
an authorized publication operation. Do not install, register, or deploy public
copies merely because an accepted pair exists.

The accepted-source directory MAY be absent before its first accepted pair.
Create it only while accepting the first pair; do not add a placeholder file
solely to preserve an empty directory.

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
| `ACCEPTED` | Both accepted-source files exist and passed the creation and acceptance gates; the public pair may be absent | Retain the accepted pair until the exact page publication is authorized |
| `INSTALLED_LOCAL` | Accepted and public pairs exist under the same names and each same-name SHA-256 matches | Continue only within the exact active page-publication route or retain it for an already published page |
| `REGISTERED_GIT` | Both pairs are tracked and clean in the current `HEAD`, and that `HEAD` is synchronized with `origin/main` | Deploy only as the immediate image prerequisite of the authorized page publication |
| `DEPLOYED_ASSET` | Actions deployed the public pair and production bytes match; the hotel page publication is the active next operation | Complete the page-publication route; if that route stops, preserve the accepted pair and resolve or remove the unpublished public copy under Section 7.1 |
| `PUBLISHED` | The hotel page was deployed, its image references and production bytes were verified, and the required rendering checks passed | Maintain the pair under normal Git and production rules |
| `LEGACY_PUBLIC_ONLY` | A public file or pair exists without a verified accepted-source counterpart | Preserve it; do not backfill, promote, replace, rename, or delete automatically |
| `REVIEW` | Identity is known, but a same-name content difference or human composition choice remains | Obtain the exact target decision before overwrite or replacement |
| `STOP` | Identity, pair completeness, file integrity, slug, reference, or recovery cannot be verified | Resolve the stated blocker before continuing |

`ACCEPTED`, `INSTALLED_LOCAL`, `REGISTERED_GIT`, `DEPLOYED_ASSET`, and `PUBLISHED` are different states. Do not report one as another.

An unpublished pair may return from `INSTALLED_LOCAL`, `REGISTERED_GIT`, or
`DEPLOYED_ASSET` to `ACCEPTED` only through the exact removal procedure in
Section 7.1. A `PUBLISHED` pair cannot return to `ACCEPTED` while its page
remains published.

## 5. Acceptance Procedure

1. Fix one `SOURCE_ROUTE` as `DIRECT_TEXT` or `PHASE_PREPARED`.
2. Verify the exact target Text, hotel identity, address, `HOTEL_NAME_EN`, and `CANONICAL_SLUG` through the applicable start route.
3. Keep unaccepted output outside both canonical storage classes.
4. Validate both images against every hard gate in `CANDY_HOTEL_IMAGE_CREATION_SPEC.md`.
5. Verify exactly two readable RGB JPG files at `1000 x 750` with the standard filenames.
6. Verify `_1` and `_2` have different SHA-256 values and materially different compositions.
7. Check complete-hash duplication against other hotel images. A match to
   another hotel is `STOP`; reuse is a separate target operation selected by
   an applicable route in `codex/WORK_ROUTING.md` Section 5.2.
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
| Complete pair | Absent pair | Not applicable | `ACCEPTED`; copy exact accepted bytes only when an applicable route selected from `codex/WORK_ROUTING.md` Section 5.2 includes first installation |
| Complete pair | Complete pair | Each same-name hash matches | `INSTALLED_LOCAL` when a published page or active authorized publication requires it; otherwise it is eligible for the target-specific removal decision in Section 7.1; do not rewrite either pair |
| Complete pair | Complete pair | Any same-name hash differs | `REVIEW`; do not overwrite. Use the applicable replacement route selected from `codex/WORK_ROUTING.md` Section 5.2 |
| Absent pair | Complete pair | Not applicable | `LEGACY_PUBLIC_ONLY`; preserve the public pair and do not create an accepted copy by assumption |
| Partial pair | Any state | Any state | `STOP`; identify the missing or extra file and recovery method |
| Any state | Partial pair | Any state | `STOP`; do not publish or repair by inference |

If `_1` and `_2` have the same hash, the pair is `STOP` even when accepted/public same-name hashes otherwise agree.

## 7. First Local Public Installation

First installation applies only when both canonical public filenames are absent.

1. Require `IMAGE RESULT: PASS` and a complete accepted pair.
2. Perform first local installation only while the exact hotel page publication
   is explicitly authorized and active through the applicable routes selected
   from `codex/WORK_ROUTING.md` Section 5.2. An acceptance-only, preparation,
   normalization, or future-page task MUST end at `ACCEPTED` and MUST NOT create
   the public pair.
3. Copy the accepted files without re-rendering, re-encoding, resizing, metadata editing, or renaming.
4. Verify that each accepted/public same-name SHA-256 matches.
5. Verify that the public pair remains two different hashes.
6. Verify the target Text relative paths and OGP path again.
7. Rerun `direct-check` for `DIRECT_TEXT`, or finish the Phase 4 gate for `PHASE_PREPARED`.

Local installation does not mean that a page exists, a Commit was created,
GitHub was updated, or production serves the image. It is nevertheless a
required prerequisite when first installation is included in the authorized
page-production scope. The current hotel publication command
requires public images to be tracked and clean dependencies, so a newly
installed pair MUST complete Section 9 before page publication.

Do not stockpile local-public or production-public copies for future hotel
pages. Installation, image registration, deployment, and page publication must
remain one target-limited publication objective even when technical safeguards
require separate Commits or Actions runs.

### 7.1 Unpublished Public-Copy Removal

An unpublished public pair may be removed while its accepted-source pair is
retained. Removal is permitted only when all of the following are verified:

1. The accepted-source pair is complete, readable, correctly named, internally
   different, and still passes its recorded format, dimensions, and SHA-256
   checks.
2. No page in `PUBLISHED` state requires the pair, and no currently authorized
   page-publication operation is using it.
3. The local-public pair is complete and each same-name SHA-256 matches the
   accepted-source pair before removal.
4. The exact pair and the intended local, GitHub, and production removal scope
   are explicitly authorized. A rule change, audit, or general cleanup finding
   alone is not deletion authority.

When removal is authorized:

1. Preserve both accepted-source files without rewriting, moving, renaming, or
   re-encoding them.
2. Remove both local-public files as one pair. Never leave a partial pair.
3. When GitHub or production removal is included, publish the exact two-file
   Git deletion through the applicable Git and deployment routes and verify both
   production public URLs are absent.
4. Reverify the accepted-source paths and SHA-256 values after removal and set
   the lifecycle to `ACCEPTED`.
5. If the page is authorized later, recreate the public pair only by the first
   local installation procedure using the unchanged accepted bytes.

This procedure does not apply to `PUBLISHED`, partial, hash-mismatched, or
`LEGACY_PUBLIC_ONLY` pairs. Those states require their existing page,
reconciliation, recovery, or legacy review route before any deletion.

## 8. Existing Same-Name Replacement

An existing public filename with different proposed bytes is not a first installation.

- Treat replacement or overwrite as a separate target operation under the
  applicable replacement route selected from `codex/WORK_ROUTING.md` Section 5.2.
- Treat the hotel pair as one inspection and rollback unit. Inspect both accepted and public files even when only one file's bytes change.
- Validate the proposed pair through `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` before acceptance.
- Do not create an accepted-source copy of a legacy public file merely to make the hashes agree.
- Preserve recoverability through Git and the transactional deployment procedure.
- Follow Section 5.1 of `CANDY_PRODUCTION_MIGRATION_MASTER.md` for same-path cacheable-asset replacement, including controlled content-version references and production rollback.
- If the current hotel generator, Text format, or controlled source cannot preserve every required replacement reference, `STOP` before modifying the public pair.
- Deletion, rename, slug correction, and cross-hotel replacement remain separate operations.

No hotel-specific replacement automation or production guard is established by this document. Do not describe manual checks as an automated guard.

## 9. Asset Registration and Deployment Unit

- The accepted-source pair is always Git-managed and retained. The local-public
  pair is Git-managed only while required by a published page or the exact
  active page-publication operation.
- Do not register or deploy a public pair merely because its accepted pair is
  complete. When the exact page publication is authorized, keep the new public
  pair in one target-limited image-asset Git unit and proceed directly to the
  matching page-publication unit.
- The current `candy_hotel_publish.py` treats the public pair as tracked, clean dependencies and does not stage new image files. Do not include untracked or modified image files in the later page Commit.
- The accepted pair is management/source evidence and MUST NOT be deployed as a public target.
- The public pair is the only deployable output of this image-asset unit. It may
  be deployed immediately before the page because deployment safeguards require
  that order, but only within the same authorized page-publication objective;
  that temporary state is `DEPLOYED_ASSET`, not `PUBLISHED`.
- When the applicable authorized routes selected from `codex/WORK_ROUTING.md`
  Section 5.2 include creation and publication of a hotel page whose complete
  accepted pair is not yet locally public, preserve
  two distinct units: first image-asset registration, deployment, and
  production-byte verification; then page registration, deployment, and page
  verification.
- Do not move accepted images to the public folder. Copy exact bytes and retain both storage responsibilities.
- Do not remove an accepted pair after publication merely because a public pair exists.
- If page publication stops after the pair reaches `DEPLOYED_ASSET`, do not
  leave it as an undocumented future-page stockpile. Either resume the same
  authorized publication or obtain target-specific removal authority and apply
  Section 7.1.

Set `DEPLOYED_ASSET` only after both pairs are tracked and clean, the exact
asset Commit's Actions succeeds, and both production image bytes match the
local public hashes.

## 10. Production Publication and Verification

A newly created pair MUST reach `DEPLOYED_ASSET` within the same authorized
page-publication objective; an unchanged legacy
public-only pair MUST already be tracked and clean. Publication follows the
applicable page, Git, and production routes selected from `codex/WORK_ROUTING.md`
Section 5.2 and does not create a separate image-specific authority path.

Before staging:

1. Verify `DEPLOYED_ASSET` for a newly accepted pair, or verified tracked-and-clean dependency status for an unchanged `LEGACY_PUBLIC_ONLY` pair.
2. Verify the target Text, generated source `src`, alt values, OGP, and any JSON-LD image reference.
3. Run the required page and generated-state checks.

After deployment:

1. Confirm page-publication Actions success for the exact page Commit SHA.
2. Re-download both production image URLs and verify HTTP success, JPG content, `1000 x 750`, and SHA-256 equality with the local public pair.
3. Verify the production page references the intended `_1` and `_2` URLs and OGP uses `_1`.
4. Verify the required desktop and mobile views render the intended pair.
5. Record accepted-source, local-public, image-asset Git/Actions, page
   Git/Actions, production bytes, page references, and rendering as separate
   lifecycle results.

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
- An accepted-source pair would be deleted, altered, or left incomplete while
  removing a public copy.
- A public pair is proposed for removal while its page remains `PUBLISHED` or
  while an active authorized publication requires it.
- A production replacement cannot preserve cache correctness and rollback.

Use `REVIEW` when:

- The exact same accepted or public filename exists with different bytes.
- The target identity is confirmed but the acceptable composition requires a human choice.
- A legacy public-only pair is proposed for replacement or formal re-acceptance.

## 12. Required Result Record

```text
SOURCE_ROUTE: DIRECT_TEXT / PHASE_PREPARED
IMAGE LIFECYCLE: CANDIDATE / ACCEPTED / INSTALLED_LOCAL / REGISTERED_GIT / DEPLOYED_ASSET / PUBLISHED / LEGACY_PUBLIC_ONLY / REVIEW / STOP
PUBLIC_COPY_RETENTION: REQUIRED / REMOVABLE / REMOVED / NOT_APPLICABLE
PUBLISHED_PAGE_REQUIREMENT: YES / NO / UNVERIFIED
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
- [ ] The accepted-source pair remains stored regardless of public-copy state.
- [ ] Same-name accepted/public states were reconciled without inference.
- [ ] Any local public installation copied exact accepted bytes.
- [ ] A public pair exists only for a published page or an active authorized
      page-publication operation.
- [ ] Any unpublished public-copy removal preserved both accepted files,
      removed the public pair as one unit, and verified the resulting state as
      `ACCEPTED`.
- [ ] Target Text paths and OGP agree.
- [ ] Any replacement followed the applicable replacement route selected from
      `codex/WORK_ROUTING.md` Section 5.2 with cache handling and rollback.
- [ ] A newly accepted pair reached `REGISTERED_GIT` and `DEPLOYED_ASSET` before page publication.
- [ ] Local acceptance, local installation, image-asset Git/Actions, page
      Git/Actions, production bytes, page references, and rendering were
      recorded separately.
- [ ] No unverified state was reported as `PUBLISHED`.
