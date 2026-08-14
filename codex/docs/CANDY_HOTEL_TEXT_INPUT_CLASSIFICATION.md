# CANDY Hotel Text Input Classification

- Updated: 2026-07-23
- Target: `Text_hotel_data/*.txt`
- Status: canonical document

## 1. Purpose

Separate hotel-input format and text quality from eligibility for new-page production. Classification applies directly to staff-completed Text and does not require Phase-result files.

The exact domain value `作成可能` applies only when the input text, images, existing pages, shared registrations, and Git tracking state all pass their gates.

## 2. Classifications

These classification names are exact domain values used by the hotel-input workflow.

| Classification | Meaning | Next handling |
|---|---|---|
| `作成可能` | The target passes the gate and returns `NEW_HOTEL_TARGET_OK` | `direct-check` returns `READY_FOR_BUILD`; proceed with only one target |
| `画像なし` | The input is readable but the two required images are absent | When no other blocker exists, `direct-check` returns `READY_FOR_IMAGES`; prepare both images, then rerun it |
| `旧形式要変換` | The Text uses legacy metadata labels, generic photo labels, or numbered scene structure | Run `legacy-check`; convert only when it returns `READY_TO_CONVERT` |
| `入力不備` | Placeholder, missing canonical value, unsafe URL, unregistered shop, partial input, or another input defect exists | Correct the text file, then re-evaluate |
| `作成済み/登録あり` | An existing state is present in public PHP, source, dataset, dataset_base, the hotel index, or the sitemap | Do not proceed as new production. Handle it as an existing-page fix task |
| `入力未追跡` | The text file is absent from Git HEAD | Decide whether to register it in Git before production |
| `重複slug` | Multiple text files have the same slug | STOP until one canonical input is selected |
| `管理用txt` | The text file is an instruction or another non-production file | Exclude it from production targets |

## 3. Current State

Do not store volatile classification or blocker counts in this canonical classification document. Use `generated/CANDY_UPCOMING_PAGES.md` and the commands in Section 4 for the current population.

Multiple blockers may apply at the same time. Review both the primary classification and `BLOCKER_COUNTS_JSON`; do not decide eligibility from only one value.

## 4. Commands

```powershell
codex\scripts\candy-hotel.cmd audit-inputs
codex\scripts\candy-hotel.cmd audit-inputs --write-report
codex\scripts\candy-hotel.cmd audit-existing
codex\scripts\candy-hotel.cmd target-next
codex\scripts\candy-hotel.cmd legacy-check --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd legacy-convert --input "Text_hotel_data/対象ホテル.txt" --output "$env:TEMP\対象ホテル_現行形式.txt"
codex\scripts\candy-hotel.cmd legacy-convert --input "Text_hotel_data/対象ホテル.txt" --replace
codex\scripts\candy-hotel.cmd direct-check --input "Text_hotel_data/対象ホテル.txt"
codex\scripts\candy-hotel.cmd target-check --input "Text_hotel_data/対象ホテル.txt"
```

`direct-check` is the authoritative entry gate for a staff-completed Text. It returns success for exactly two continuation states: `READY_FOR_IMAGES` when the only blockers are the declared image files, or `READY_FOR_BUILD` when the common new-page gate is ready. Every other state returns `STOP`.

`--write-report` produces the current classification report files defined by the tool. Treat them as generated current state, not as a second canonical specification.

## 5. Legacy Text Migration Contract

`legacy-check` is read-only. It classifies the source as `CURRENT`, `LEGACY_V1`, `LEGACY_NUMBERED`, or `UNKNOWN`; maps only values explicitly present in the source; and validates the normalized result with the current hotel Text parser.

- `CURRENT_TEXT_STATUS=VALID`: conversion is unnecessary; continue to `direct-check`.
- `LEGACY_TEXT_STATUS=READY_TO_CONVERT`: every mapped value and section passes the current parser; conversion is allowed.
- `LEGACY_TEXT_STATUS=STOP`: one or more required values, image identities, shop names, pairs, scene blocks, URLs, or canonical values are missing, conflicting, ambiguous, or placeholders. Correct the source facts before conversion.

`legacy-convert --output` creates only a new non-existing `.txt` file and reparses it before success. It MUST NOT overwrite an existing output.

`legacy-convert --replace` is an explicit in-place operation. It is allowed only when the source is tracked in Git and has no staged or unstaged difference. The tool validates the converted content before replacement, writes atomically, reparses the stored file, and restores the original content automatically if post-write validation fails.

Legacy conversion is a preparation step for `SOURCE_ROUTE: DIRECT_TEXT`; it is not a third page-production route. After conversion, rerun `legacy-check`, then `direct-check`. Do not retain both the legacy and converted versions as active inputs with the same canonical slug.

The converter MUST NOT:

- Invent or research a missing hotel fact, URL, shop, image path, canonical slug, access value, or answer.
- Select two images when generic legacy `写真` blocks do not resolve to exactly two unambiguous source paths.
- Save a partial normalized file or a file rejected by the current parser.
- Generate HTML, images, pages, shared registrations, or production changes.

## 6. Prohibitions

- Do not treat `画像なし` as eligible for production.
- Do not pass `旧形式要変換` directly to `direct-check`, build, or publication.
- Do not infer missing content in an `入力不備` input.
- Do not overwrite `作成済み/登録あり` as a new page.
- Do not use an untracked text file directly for production publication.
