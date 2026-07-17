# Code and Folder Structure

- Purpose: Define the major work areas, each folder's responsibility, and the current canonical sources
- Status: canonical document
- Updated: 2026-07-17

## 1. Canonical Sources and Work Locations

| Type | Path | Responsibility |
|---|---|---|
| Local Git working repository | `C:\Codex\candy` | The only working repository root synchronized with GitHub |
| GitHub synchronization hub | `makotonishikubo0418-cmd/candy` | Shares commits between Codex tasks. Push only with explicit user instruction |
| Common-rule entry point | `C:\Codex\candy\AGENTS.md` | Short entry point read first for every task |
| Canonical Codex management source | `C:\Codex\candy\codex` | Contains the README, management documents, HP specifications, and scripts |
| Project management | `C:\Codex\candy\codex\project_management` | Contains rules, current state, reservations, history, and safety procedures |
| Actual HP site tree | `C:\Codex\candy\HP` | Contains public PHP, source, includefile, images, logs, and movies |
| Production inputs | Root-level `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` | Non-public source data for page production |
| NAS storage | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only location for `Backup/` and accepted source assets. Git operations are prohibited |

## 2. Primary Areas

| Area | Contents | Entry point |
|---|---|---|
| Management entry point | Canonical document index and required reading order | `codex/README.md` |
| Project management | Document rules, state, communication, tasks, and safety | `codex/project_management/` |
| HP production specifications | Area, hotel, and blog runbooks and generation specifications | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Stable HP specifications | Page structure, code and asset structure, and SEO specifications | `codex/docs/CANDY_HP_STRUCTURE_MAP.md` and related documents |
| Generated HP current state | Automatically generated page, production-candidate, code/asset, and SEO inventories | `codex/docs/generated/` |
| HP generation tools | Area, hotel, and blog generation, validation, and publishing scripts | `codex/scripts/` |
| Public HP files | PHP, source, dataset, images, logs, and movies | `HP/` |
| Area input | Regional text files and classification results | `Text_area_data/` |
| Accepted area images | Assets outside Git used before area-page production | NAS `Text_area_data/画像データ/` |
| Blog input | Article text files | `Text_blog_data/` |
| Hotel input | Hotel text files and classification results | `Text_hotel_data/` |
| Backups and accepted assets | Legacy data, excluded data, historical materials, and accepted assets outside Git | NAS `Backup/` and accepted-asset locations |

## 3. Current Constraints

- `HP/` is exclusively for actual site data. Do not place Codex management documents or production inputs there.
- Canonical management sources belong under `codex/`; project-management documents belong under `codex/project_management/`.
- `Text_*_data/` is not published directly to HP.
- At the start of work, run `git fetch origin` and `git status --short --branch`. Pull first when the branch is behind.
- The NAS is storage-only for `Backup/` and accepted assets. Do not run Git operations there.
- NAS `Backup/` is for reference and MUST NOT be used as the basis for a current specification.
- Internal path migration under `codex/scripts/` and read-only dry runs are verified. Page generation and publish operations remain subject to the applicable runbook and explicit authority.
- After an HP change, include generated-document synchronization in the same work unit by running `candy-site-state write` and `check`.
- Do not create `HP/HP/`.
- Do not treat generation, publication, input, management documents, and backups as the same responsibility.
