# CANDY Hotel Content Preparation Runbook

- Purpose: Research and prepare one hotel input through three independently validated phases
- Status: Canonical execution runbook
- Updated: 2026-07-23
- Applies to: Hotel identity and business research, access research, page copy, FAQ, SEO input, shop selection, and nearby-spot preparation
- Output authority: `Text_hotel_data/<hotel-name>.txt`

## 1. Position and Scope

This runbook governs only `SOURCE_ROUTE: PHASE_PREPARED`, where research and writing are required before hotel-image production and normal hotel-page production.

It MUST NOT be required for `SOURCE_ROUTE: DIRECT_TEXT`. When staff already completed a Text under `Text_hotel_data/`, run `candy-hotel.cmd legacy-check` first; after current-format validation or completed legacy conversion, continue with `direct-check` and `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md`.

The three phases remain separate because their evidence, failure causes, and validation differ:

1. Phase 1: hotel identity, operating status, and core facts
2. Phase 2: access research, map data, and access copy
3. Phase 3: page structure, main copy, FAQ, SEO fields, shops, and nearby spots

Phase result Markdown is evidence and handoff material. It is not a production source. The only production input is the exact target file under `Text_hotel_data/`.

This runbook MUST be used with:

- `CANDY_PAGE_GENERATION_GOVERNANCE.md`
- `CANDY_HOTEL_PAGE_GENERATION_SPEC.md`
- `CANDY_HOTEL_IMAGE_CREATION_SPEC.md` and `CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md` for Phase 4 acceptance and local public installation
- `CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` for Phase 5

Phases 1-3 MUST NOT edit HTML, PHP, dataset PHP, shared registrations, CSS, JavaScript, images, or production data.

## 2. Shared Input and Output Contract

### 2.1 Required Inputs

Confirm before Phase 1:

```text
TARGET_HOTEL_NAME:
TARGET_LOCATION:
TARGET_TEXT_PATH: Text_hotel_data/<hotel-name>.txt
PHASE_RESULT_ROOT:
EXISTING_HTML_PATH: optional
KNOWN_ADDRESS: optional
KNOWN_FORMER_NAME: optional
FAQ_REFERENCE_PATH: optional staff-provided reference
```

`TARGET_TEXT_PATH` MUST be inside `Text_hotel_data/` and MUST identify one target. Do not select another hotel automatically.

Phase-result files MUST be stored at a user-specified location outside the repository unless the user explicitly authorizes a different durable record. Do not create temporary research reports inside the repository.

### 2.2 Canonical Identifier

Use one identifier named `CANONICAL_SLUG` for all of these values:

- Public PHP filename
- Source HTML filename
- Dataset PHP filename
- Canonical URL
- `img_1`
- `img_2`
- OGP image
- Shared-registration key

The canonical URL form is:

```text
https://www.55810.com/kagoshima-deliveryhealth-hotel-<CANONICAL_SLUG>.php
```

The image forms are:

```text
./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_1.jpg
./imgHtml/new_202601/hotel/<CANONICAL_SLUG>_2.jpg
```

Do not maintain a separate image slug. Do not infer or automatically translate a slug. Confirm it from the existing target Text, an existing canonical page or registration, or an explicit user decision.

### 2.3 Status Model

Use these field states:

| State | Meaning |
|---|---|
| `CONFIRMED` | Supported by current official information or sufficient independent current evidence |
| `UNCONFIRMED` | Evidence is absent, stale, or insufficient |
| `CONFLICT` | Strong sources disagree and no value can be selected safely |

Use these phase results:

| Result | Meaning |
|---|---|
| `PASS` | The phase's required outputs are complete and may be used by the next applicable phase |
| `REVIEW` | The completed evidence requires a human decision before the affected value is used |
| `STOP` | The target, authority, scope, or required input prevents safe continuation |

An optional field may remain `UNCONFIRMED` without failing the entire phase. Do not place an `UNCONFIRMED` or `CONFLICT` value in public-source fields.

### 2.4 Evidence Model

Use phase-prefixed Source IDs:

- Phase 1: `P1-S01`, `P1-S02`, ...
- Phase 2: `P2-S01`, `P2-S02`, ...
- Phase 3: `P3-S01`, `P3-S02`, ...

Search results, snippets, AI summaries, and unopened pages are not sources.

There is no fixed page count or domain count. Research continues only until every required decision has sufficient evidence or is classified `UNCONFIRMED` or `CONFLICT`.

For identity, operating status, address, map position, and another value that can materially misidentify the hotel, prefer first-party current information plus an independent current confirmation. When first-party information is unavailable, use multiple independent sources and record the limitation.

Do not count mirrored data, the same operator, the same database, or republished material as independent evidence.

### 2.5 Phase-Result Integrity

Each phase record MUST include:

```text
Target hotel:
Target Text path:
CANONICAL_SLUG:
Input phase-result paths:
Target Text SHA-256 before the phase:
Target Text SHA-256 after the phase:
Research date:
Phase result:
Human decisions required:
Prohibited or rejected values:
```

Before a later phase uses an earlier result, compare the recorded input and output hashes. When the target Text changed unexpectedly, recheck the affected fields instead of copying stale values.

## 3. Common Writing and Update Rules

- Use only confirmed facts.
- Preserve hotel names, addresses, telephone numbers, official English forms, rate names, and conditions exactly.
- Do not copy or lightly paraphrase another website.
- Do not reuse another hotel's paragraph structure by changing only the hotel name.
- Do not invent a stay, visit, personal routine, popularity claim, cleanliness claim, quietness claim, or guarantee.
- Do not pad copy to reach a fixed character count.
- Do not repeat the same fact in detail across the introduction, optional feature, access, FAQ, and nearby-spot sections.
- Do not add placeholders, blank containers, dummy values, or incomplete `subtitle_` and `description_` pairs.
- Preserve the existing target Text's encoding and line-ending convention.
- Change only the fields owned by the active phase. Do not overwrite confirmed output from another phase without recording the conflict.

After each phase, inspect the exact target Text diff and confirm that only the phase-owned fields changed.

## 4. Phase 1: Identity, Operating Status, and Core Facts

### 4.1 Objective

Uniquely identify the target hotel, determine current operating status, confirm the canonical identifier, and prepare the reliable core facts used by later phases.

### 4.2 Required Research

Confirm or classify:

- Current formal hotel name
- Reading and official English form
- User-approved image display name
- Former name and whether it remains useful
- Current address, postal code, coordinates, map position, and exterior identity
- Hotel type
- Official hotel, operator, and reservation URLs
- Current operating status
- Hotel-specific representative telephone number
- Room count and parking details
- Current normal rates and their exact conditions
- On-site payment methods
- Check-in, check-out, facilities, meals, membership, reservation rules, and visitor rules when available
- Existing page, canonical, image filenames, and shared registrations
- `CANONICAL_SLUG`

Do not decide identity from one name, address, telephone number, or map result alone. When similarly named hotels exist, compare address, telephone, map position, exterior, operator, and surrounding roads.

### 4.3 Operating Status

Use these values:

| Status | Phase handling |
|---|---|
| `ACTIVE` | Eligible for `PASS` when the identity and location gates also pass |
| `LIKELY_ACTIVE` | `REVIEW` |
| `TEMPORARILY_CLOSED` | `STOP` |
| `INACTIVE` | `STOP` |
| `UNKNOWN` | `REVIEW` or `STOP` according to identity risk |

Use multiple current signals such as first-party updates, current reservation availability, recent operator activity, map state, current listings, and closure or rename notices. No one signal is sufficient by itself.

### 4.4 Core-Field Rules

- Telephone numbers MUST be classified as hotel-specific, reservation, operator, shared reception, advertising relay, tracking, or unknown. Publish only a confirmed hotel-specific number.
- Room count MUST NOT be inferred from room types, plans, floors, or parking spaces.
- Distinguish dedicated parking, partnered parking, nearby parking, capacity, price, reservation, and height limits.
- Preserve original rate names, amounts, tax status, time conditions, weekday conditions, room rank, general or member status, extensions, and campaign restrictions before mapping them to public rows.
- Do not treat coupon, campaign, member, or reservation-site-only prices as ordinary general prices.
- Do not treat online reservation payment as proof of on-site payment methods.
- Do not state that delivery-health use or outside visitors are allowed without a confirmed hotel rule or a reliable operational confirmation.

### 4.5 Target Text Update

Update only confirmed Phase 1 values that have an existing target field:

- Hotel name and official URL
- Address
- Optional telephone number
- Optional room and parking row
- Optional payment row
- Confirmed rate rows and supplemental rate copy
- `title`, `description`, canonical, image paths, and H1 only when their final values are already confirmed

Omit an optional row when no confirmed value exists. Do not add `UNCONFIRMED`, `CONFLICT`, or research notes to production fields.

Information required by later phases but not represented in the target Text, including coordinates, map query, display English name, and evidence, remains in the Phase 1 result.

### 4.6 Phase 1 Gate

`PASS` requires:

- Unique hotel identity
- Current formal name
- `ACTIVE` operating status
- Confirmed current address and map position
- Confirmed `CANONICAL_SLUG`
- Every adopted value linked to evidence
- Target Text updated only with confirmed values

Telephone, room count, parking, rates, payment methods, and English display name MAY remain unconfirmed when omitted from production fields. Record separate readiness values:

```text
READY_FOR_PHASE_2: YES / NO
READY_FOR_PHASE_4: YES / NO
```

Phase 4 readiness additionally requires the exact approved image display name and address.

## 5. Phase 2: Access Research and Access Copy

### 5.1 Start Conditions

Phase 1 MUST be `PASS`, `READY_FOR_PHASE_2` MUST be `YES`, and the Phase 1 target Text hash MUST match the current file.

### 5.2 Route Research

Research these four route perspectives:

1. Tenmonkan-area origin by car
2. Kagoshima-Chuo Station-area origin by car
3. Tenmonkan-area origin by public transport or practical walking route
4. Kagoshima-Chuo Station-area origin by public transport

Use a precise recorded origin. Normally use `天文館通電停` and the east side of `鹿児島中央駅`, but record a better practical origin when one is clearly justified.

For car routes, confirm distance, normal travel-time range, major roads, major direction or intersection, useful arrival landmark, and material entry constraints.

For public transport, confirm boarding location, route or service, direction, transfer, stop, travel time, walking time, and total practical range from current operator information.

When a route is unavailable or impractical, record that state accurately. Do not invent a route or fail the entire phase solely to force four positive route descriptions.

### 5.3 Map Data

Confirm:

```text
MAP_DETAIL_URL:
MAP_EMBED_URL:
MAP_IFRAME_TITLE:
MAP_POSITION_STATUS:
```

The embedded map MUST identify the Phase 1 hotel or confirmed address. If an embed URL is unavailable, retain the verified detail URL in the phase record and classify the access Text block as incomplete; do not create a partial access block.

### 5.4 Access Copy

Write one original access explanation using the confirmed practical routes. Split it at a natural semantic boundary into:

- `ACCESS_LEAD` for the access `subtitle_`
- `ACCESS_BODY` for the access `description_`

There is no minimum character count. The copy MUST be long enough to communicate the confirmed route choices and no longer than the useful evidence supports.

Do not include the full street address, source URLs, research notes, guarantees, unrelated tourism copy, or repeated route statements.

### 5.5 Target Text Update

Create or replace the access block only when all four parser-required values are complete:

```text
scene（h2）
アクセス情報
地図URL
<MAP_EMBED_URL>
地図タイトル
<MAP_IFRAME_TITLE>
subtitle_
<ACCESS_LEAD>
description_
<ACCESS_BODY>
```

Do not write a partial access block. Preserve the target Text's section order and do not change Phase 1 or Phase 3 fields.

### 5.6 Phase 2 Gate

`PASS` requires confirmed hotel-location consistency, enough reliable route evidence for accurate public copy, a complete access block, no unsupported time guarantee, and an exact target Text diff limited to access content.

Use `REVIEW` when a material route, entrance, location, or existing-page conflict needs a human decision. Use `STOP` when Phase 1 is not ready, the hotel location differs, or a complete access block cannot be produced safely.

## 6. Phase 3: Page Copy, FAQ, SEO, Shops, and Nearby Spots

### 6.1 Start Conditions

Phases 1 and 2 MUST be `PASS`, their target Text hashes MUST form an uninterrupted chain, and the current target Text MUST match the Phase 2 output hash.

### 6.2 Page Responsibility

Assign each fact once:

| Information | Primary location |
|---|---|
| Hotel name, URL, address, telephone, rooms, parking, payment, and rates | Basic information and rate rows |
| Roads, transit, walking, and travel time | Access block |
| Location value, hotel-specific strengths, and practical user value | H1 introduction |
| One strong hotel-specific feature that needs separate treatment | Complete legacy `option` block |
| Decision-useful questions not answered adequately elsewhere | FAQ |
| Useful current facilities near the hotel | Nearby spots |
| Shop identity, telephone, hours, travel time, fee, copy, links, and images | Approved `template_shop.html` data plus target Text overrides |

Do not repeat Phase 2 route details in the introduction or FAQ.

### 6.3 Main Introduction and Optional Feature

Create `subtitle_h1` and `description_h1` from confirmed facts. There is no fixed character count. Cover the hotel's actual location value, distinguishing feature, practical consideration, and a natural conclusion without generic filler.

Create the optional feature only when one confirmed characteristic materially helps hotel selection and cannot be covered cleanly in the introduction. A feature block requires all three fields:

```text
option
option_subtitle
option_description
```

Otherwise omit the entire option block.

### 6.4 FAQ

FAQ count is zero or more in the production format and is determined by useful confirmed questions. Do not create or require a repository-wide common FAQ canonical file.

- When staff provides `FAQ_参考情報.txt` or an equivalent task-specific reference, treat it as reference material rather than a production source. The standard selection target is four useful questions: two hotel-related and two delivery-health-related. Adjust wording when needed for the target hotel.
- When a referenced hotel question has no answer, write the answer from the target's confirmed facts and applicable confirmed operating conditions. Do not invent a permission, facility, rate, or guarantee.
- If four relevant, supported question-and-answer pairs cannot be completed, use fewer complete pairs instead of adding unsupported material.
- Write a hotel-specific FAQ only when the answer is confirmed and helps a pre-use decision.
- Answer directly in the first sentence, then state conditions or exceptions.
- Do not create a question whose only answer is to contact the hotel.
- Do not duplicate basic information or access copy.
- Do not state delivery-health or visitor permission without evidence.
- Do not describe a way to evade hotel rules.

Visible FAQ and FAQPage are generated from the same target Text pairs and MUST match exactly.

### 6.5 Nearby Spots

Nearby-spot count is zero or more. Select only current facilities that materially help a guest or identify the hotel location. Confirm formal name, address, current operation, URL, relative position, and optional telephone number.

Do not add a facility only to reach a count.

### 6.6 Shops

`HP/source/template_shop.html` is the canonical shop-detail source. The target Text determines which one or more registered shops are used and may provide travel time and transportation fees.

Do not invent a shop, select an unregistered shop, or rewrite canonical shop data. When target Text omits travel time or transportation fees, the normal hotel-production tool may derive them only through the separately specified coordinate and complete-area-page procedure.

### 6.7 SEO and Structured-Data Inputs

Complete these target Text fields:

- `title`
- `description`
- canonical
- OGP `image`
- `page_title_h1 / パンくずリスト`
- `subtitle_h1`
- `description_h1`

Title, meta description, H1, canonical, hotel name, and body meaning MUST agree. The OGP image path MUST be the absolute public form of `img_1`.

Do not select related articles in Phase 3. The generator deterministically supplies exactly three current indexable blog-detail links and three current indexable area-detail links. It rejects placeholders, self-links, duplicates, and missing public targets.

BreadcrumbList is required. FAQPage is generated only when FAQ exists. ItemList represents nearby spots when they exist and shops otherwise. Structured data MUST match visible output.

### 6.8 Similarity Review

Compare the completed copy with structurally similar current hotel pages as needed to detect reused openings, information order, sentence patterns, conclusions, FAQ structure, and title or description duplication.

There is no fixed comparison-page count. Continue only until meaningful similarity can be accepted or corrected. Do not count an irrelevant page merely to reach a number.

### 6.9 Target Text Completion

Serialize every confirmed Phase 1-3 result into the exact labels accepted by `codex/scripts/candy_hotel_page.py`.

Required before Phase 4 handoff:

- One exact `CANONICAL_SLUG`
- Required SEO, OGP, image-path, H1, and introduction fields
- At least one registered shop
- Hotel name, official URL, and address
- Every included optional block complete
- No placeholder, blank required value, partial pair, unsupported field, duplicate section, or research note

Optional complete normal article scenes, FAQ pairs, rate rows, access, and nearby spots may contain zero or more items according to evidence. Preserve their target Text order.

### 6.10 Phase 3 Gate

`PASS` requires a complete target Text for all content fields, evidence-backed copy, no phase-to-phase conflict, no unsupported FAQ or spot, no duplicated detailed explanation, and a clean exact diff.

Phase 3 may pass before the two image files exist, but it MUST record:

```text
READY_FOR_PHASE_4: YES / NO
READY_FOR_PHASE_5: NO
```

`READY_FOR_PHASE_4` requires the confirmed Japanese name, approved English display name, address, and canonical slug.

## 7. Common Validation

After every phase:

1. Verify phase-result UTF-8 readability and Markdown structure.
2. Verify the target Text encoding and exact changed fields.
3. Verify no placeholder, Source ID, Markdown heading, or research annotation entered a production field.
4. Verify every adopted phase value has an evidence record.
5. Verify the current target Text hash equals the recorded output hash.
6. Run `git diff --check` for repository changes.
7. Run `git status --short` and report every changed path in the authorized phase scope.

Do not run `publish` during Phases 1-3.

## 8. STOP Conditions

STOP the affected phase when:

- The target hotel or target Text cannot be identified uniquely.
- The target Text is outside `Text_hotel_data/`.
- A canonical slug conflicts or is inferred without authority.
- The operating hotel, address, or map location cannot be confirmed.
- A required earlier phase is not `PASS`.
- An earlier target Text hash does not match the current file.
- Required source data conflicts and the affected public value cannot be selected.
- The phase would require HTML, PHP, dataset, image, shared-registration, Commit, Push, or production work.
- The authorized change cannot be limited to the target Text and external phase record.

On STOP, preserve completed evidence and report the stopped phase, exact unresolved field, affected target, unexecuted work, and decision required.

## 9. Phase Report

Use this concise report after each phase:

```text
結論:
PHASE:
対象ホテル:
TARGET_TEXT_PATH:
CANONICAL_SLUG:
成果物:
Text更新項目:
根拠確認済み:
未確定・矛盾:
次フェーズへ進める: YES / NO
変更ファイル:
検証結果:
人間確認が必要な項目:
```

Report research completion separately from image creation, page generation, Git publication, and production verification.
