# CANDY Area Image Creation Runbook
- Parent / Owner: `CANDY_MASTER_DOC_INDEX.md`
- Scope: Execution sequence for creating one area-image pair
- Lifecycle: Active
- Source of Truth Responsibility: Canonical area-image creation sequence
- Related Documents: `CANDY_AREA_IMAGE_CREATION_SPEC.md` and `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
- Related Implementation Files: Target source image and area-image editing outputs

- Purpose: Define the execution order for creating one area-image pair
- Requirement source: `CANDY_AREA_IMAGE_CREATION_SPEC.md`
- Asset-lifecycle source: `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
- Applies to: Creation, editing, and pre-adoption review

## 1. Responsibility Boundary

This runbook defines sequence only. It does not redefine the visual, source,
identity, typography, filename, storage, acceptance, or completion
requirements in the specification and asset-lifecycle document.

If wording here appears to conflict with either responsibility source, STOP
and report the exact conflict. This sequence-only runbook cannot override
either source.

## 2. Required Inputs

Freeze one target and record:

```text
Japanese area name:
Canonical slug:
Display Romanization:
Display-name authority:
Target Text or page:
Image source:
Local-only / page integration / publication:
Existing same-name files: absent / present
```

The display Romanization and slug are separate values. Do not derive one from
the other.

## 3. Execution

1. Confirm the exact target and authorized scope.
2. Read the target Text or page reference and establish the canonical slug.
3. Establish one authoritative display Romanization and store its uppercase
   value once as `EXPECTED_DISPLAY_NAME`.
4. Select two source views that satisfy the distinct `_1` and `_2` roles in
   `CANDY_AREA_IMAGE_CREATION_SPEC.md`.
5. Capture clean source views without accepting a source that fails the
   specification.
6. Render both candidates from the same `EXPECTED_DISPLAY_NAME`.
7. Run every mechanical and visual acceptance gate in the specification.
8. Reject the complete pair when either image fails; do not adopt one image
   independently.
9. Pass the accepted pair to `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md` for
   filename, storage, collision, first-installation, replacement, and Git
   lifecycle handling.
10. When page integration or publication is included, apply the applicable
    routes selected from `codex/WORK_ROUTING.md` Section 5.2 for the area page, Git,
    verification, and production portions of that work.

## 4. Validation Record

Record the evidence used by the specification without restating its thresholds:

```text
Target:
Canonical slug:
EXPECTED_DISPLAY_NAME:
Display-name authority:
Character count:
_1 source and viewpoint:
_2 source and viewpoint:
_1 rendered title:
_2 rendered title:
Pair hashes:
Mechanical gate result:
Visual gate result:
Asset-lifecycle result:
Page-integration result:
Production result:
Unverified:
```

## 5. STOP Conditions

STOP when:

- The target, slug, display Romanization, or authority is unresolved.
- The two required source roles cannot both be satisfied.
- Any specification acceptance gate fails.
- Same-name handling cannot follow the asset-lifecycle document within the
  authorized scope.
- An applicable route selected from `codex/WORK_ROUTING.md` Section 5.2 identifies a
  conflict, missing authority, or failed verification.

Do not replace a STOP with inferred spelling, unrelated imagery, a second live
filename, hidden edits, or a lower-level exception.

## 6. User Report

Use the common response structure in root `AGENTS.md` and add the target,
identity authority, two source roles, pair-validation result, asset-lifecycle
state, page/production state, and every unverified item from Section 4.
