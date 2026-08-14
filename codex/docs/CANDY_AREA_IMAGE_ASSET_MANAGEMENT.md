# CANDY Area Image Asset Management

- Purpose: Define the lifecycle of accepted and public area-image pairs
- Creation requirements: `CANDY_AREA_IMAGE_CREATION_SPEC.md`
- Creation sequence: `CANDY_AREA_IMAGE_CREATION_RUNBOOK.md`
- Existing same-name replacement: `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`

## 1. Responsibility and Storage

| Class | Canonical path | Responsibility |
|---|---|---|
| Accepted source | `Text_area_data/画像データ/` | Git-managed approved source pair |
| Local public | `HP/imgHtml/new_202601/area/` | Exact files referenced by public HTML |
| HTML reference | `./imgHtml/new_202601/area/<filename>` | Public relative path |

Accepted-source files are never referenced directly by public HTML. Current
counts, missing files, pairs, and hash relationships MUST be derived from
actual files and the generated code/asset inventory; they MUST NOT be stored in
this specification.

## 2. Identity and Filename Contract

```text
kagoshima-deliveryhealth-area-<canonical-slug>_1.jpg
kagoshima-deliveryhealth-area-<canonical-slug>_2.jpg
```

- One target consists of the complete `_1` and `_2` pair.
- The canonical slug comes from the target Text or verified page identity.
- The displayed Romanization is independently verified under the creation
  specification and is not derived from the slug.
- A similar slug, legacy name, or same-region candidate is not an automatic
  match.

## 3. Lifecycle States

| State | Meaning |
|---|---|
| `CANDIDATE` | Rendered pair has not passed every acceptance gate |
| `ACCEPTED` | Complete pair passed the creation specification and is stored under the accepted-source path |
| `PENDING_FIRST_INSTALL` | Accepted pair exists and both local-public names are absent |
| `INSTALLED_LOCAL` | Accepted and local-public same-name bytes match |
| `REPLACEMENT_REQUIRED` | A local-public same-name target exists with different bytes |
| `PUBLISHED` | Authorized production deployment and required production verification passed |
| `REVIEW` | Identity, pair completeness, collision, or verification is unresolved |

## 4. Acceptance

1. Validate the pair through `CANDY_AREA_IMAGE_CREATION_SPEC.md`.
2. Verify the target slug and both exact filenames.
3. Verify both files are readable and satisfy the specification.
4. Verify the two pair members differ.
5. Compare each same-name accepted and local-public file.
6. Record the resulting lifecycle state.

This document does not repeat visual thresholds from the creation
specification.

## 5. First Local-Public Installation

When a complete accepted pair exists and both local-public names are absent,
the state is `PENDING_FIRST_INSTALL`, not missing images. The target-limited
installation step may copy the exact accepted bytes only when it is included
by the applicable authorized routes selected from `codex/WORK_ROUTING.md` Section 5.2.

After copying:

1. Verify both names.
2. Verify same-name SHA-256 equality.
3. Verify the public pair remains different within the pair.
4. Continue to the page target gate; image installation alone is not page
   completion.

## 6. Existing Same-Name Files

- Matching bytes require no copy.
- A partial local-public pair is `REVIEW`.
- Different same-name bytes require
  `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md`.
- Do not rename, substitute, delete, or consolidate a similar-slug asset by
  inference.

## 7. Integration and Publication

For page integration, verify the target source references, alt values, OGP
value, page files, shared registration, index, links, and sitemap through the
applicable routes selected from `codex/WORK_ROUTING.md` Section 5.2.

For Git or production work, use the applicable Git and production routes
selected from `codex/WORK_ROUTING.md` Section 5.2. This document grants no Commit,
Push, or deployment authority.

## 8. Completion Record

Record:

```text
Target:
Canonical slug:
Accepted pair:
Local-public pair:
Same-name hash result:
Lifecycle state:
Page integration:
Production verification:
Unverified:
```

Do not report `INSTALLED_LOCAL` or `PUBLISHED` unless the evidence for that
exact state was verified.
