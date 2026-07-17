# CANDY Hotel Text Input Classification

- Updated: 2026-07-16
- Target: `Text_hotel_data/*.txt`
- Status: canonical document

## 1. Purpose

Separate hotel-input text quality from eligibility for new-page production.

The exact domain value `作成可能` applies only when the input text, images, existing pages, shared registrations, and Git tracking state all pass their gates.

## 2. Classifications

These classification names are exact domain values used by the hotel-input workflow.

| Classification | Meaning | Next handling |
|---|---|---|
| `作成可能` | The target passes the gate and returns `NEW_HOTEL_TARGET_OK` | Proceed with only one target |
| `画像なし` | The input is readable but the two required images are absent | Prepare images, then re-evaluate |
| `入力不備` | Placeholder, missing canonical value, unsafe URL, unregistered shop, partial input, or another input defect exists | Correct the text file, then re-evaluate |
| `作成済み/登録あり` | An existing state is present in public PHP, source, dataset, dataset_base, the hotel index, or the sitemap | Do not proceed as new production. Handle it as an existing-page fix task |
| `入力未追跡` | The text file is absent from Git HEAD | Decide whether to register it in Git before production |
| `重複slug` | Multiple text files have the same slug | STOP until one canonical input is selected |
| `管理用txt` | The text file is an instruction or another non-production file | Exclude it from production targets |

## 3. Current State

The dedicated gate verified actual files on 2026-07-16.

Primary classifications:

| Classification | Count |
|---|---:|
| `作成可能` | 0 |
| `画像なし` | 35 |
| `入力不備` | 37 |
| `作成済み/登録あり` | 1 |
| `管理用txt` | 1 |
| Total | 74 |

Multiple blockers may apply at the same time. Do not decide eligibility from only the primary classification.

| Blocker | Count |
|---|---:|
| Missing images | 35 |
| Untracked input | 35 |
| Unsafe URL or HTTP URL | 16 |
| Missing canonical slug | 10 |
| Remaining placeholder | 5 |
| Unregistered shop | 2 |
| Hotel-name mismatch in H1 | 2 |
| Partial input | 1 |
| Insufficient basic information | 1 |
| Existing page file | 1 |
| Existing shared registration | 1 |

All 35 inputs classified as `画像なし` are also blocked as `入力未追跡`. Adding images alone does not authorize publication while the target text file remains outside Git tracking.

## 4. Commands

```powershell
codex\scripts\candy-hotel.cmd audit-inputs
codex\scripts\candy-hotel.cmd audit-inputs --write-report
codex\scripts\candy-hotel.cmd audit-existing
codex\scripts\candy-hotel.cmd target-next
codex\scripts\candy-hotel.cmd target-check --input "Text_hotel_data/対象ホテル.txt"
```

`--write-report` produces:

```text
Text_hotel_data/制作可否管理_ホテル_最新.tsv
Text_hotel_data/制作可否管理_ホテル_<timestamp>.tsv
```

## 5. Prohibitions

- Do not treat `画像なし` as eligible for production.
- Do not infer missing content in an `入力不備` input.
- Do not overwrite `作成済み/登録あり` as a new page.
- Do not use an untracked text file directly for production publication.
