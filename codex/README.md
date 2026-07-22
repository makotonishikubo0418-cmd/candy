# candy Management Entry Point

This README is the entry point for the management documents under `C:\Codex\candy\codex`.

## 1. Canonical Sources and Work Locations

| Type | Location | Responsibility |
|---|---|---|
| Local Git working repository | `C:\Codex\candy` | The only working repository root synchronized with GitHub |
| GitHub synchronization hub | `makotonishikubo0418-cmd/candy` | Shares commits between Codex tasks. Push only with explicit user instruction |
| Canonical Codex management source | `C:\Codex\candy\codex` | Contains the management entry point, management documents, HP production specifications, and work tools |
| Project management | `C:\Codex\candy\codex\project_management` | Canonical source for rules, current state, reservations, history, and safety procedures |
| Actual site tree | `C:\Codex\candy\HP` | Contains HP data such as PHP, source, includefile, images, logs, and movies |
| Production inputs | Root-level `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` | Source data for page production that is not published directly to HP |
| NAS storage | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only location for `Backup/`. Git operations are prohibited |

At the start of work, run `git fetch origin` and `git status --short --branch`. If `main` is behind `origin/main`, pull before editing. Push only with explicit user instruction.

## 2. Required Start

1. Root `AGENTS.md`
2. This `codex/README.md`
3. `codex/project_management/TASK_RESERVATIONS.md`
4. Only the management document or HP runbook required for the current task
5. `HP/AGENTS.md` for HP work

## 3. Folder Responsibilities

| Folder | Responsibility |
|---|---|
| `codex/` | Codex management documents, production specifications, and scripts. Only active canonical management sources belong on the normal route |
| `codex/project_management/` | Management rules, structure, progress, communication, task reservations, history, and safety procedures |
| `codex/docs/` | Active HP production runbooks and specifications for area, hotel, blog, and other categories |
| `codex/docs/generated/` | Current page, production-candidate, code/asset, and SEO state generated from actual files. Manual editing is prohibited |
| `codex/data/` | Canonical operational mapping data consumed by production tooling, including the approved area nearby-link graph |
| `codex/scripts/` | Page generation, validation, and publishing scripts |
| `HP/` | The actual public site tree. `includefile`, `log`, and `movie` are also HP data |
| `Text_area_data/` | Area-page production inputs. Accepted area images are stored in the Git-managed local `Text_area_data/画像データ/` directory |
| `Text_blog_data/` | Blog-page production inputs |
| `Text_hotel_data/` | Hotel-page production inputs |
| NAS `Backup/` | Stores backups, isolated files, and legacy materials outside Git. Do not run Git operations on the NAS |

## 4. Canonical Document Index

| Purpose | Canonical document |
|---|---|
| Management architecture overview | `codex/MANAGEMENT_SYSTEM_OVERVIEW.md` |
| Document separation and update rules | `codex/project_management/DOCUMENT_RULES.md` |
| Overall plan, current state, and issues | `codex/project_management/PROJECT_STATUS.md` |
| Inter-Codex communication and handoff | `codex/project_management/CODEX_COMMUNICATION.md` |
| Task and file reservations | `codex/project_management/TASK_RESERVATIONS.md` |
| Individual task history | `codex/project_management/TASK_LOG.md` |
| Code and folder structure | `codex/project_management/CODE_STRUCTURE.md` |
| Safety procedure for deletion, movement, and bulk operations | `codex/project_management/SAFETY_PROTOCOL.md` |
| HP production and generation specifications | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Area nearby-link mapping | `codex/data/CANDY_AREA_RELATED_LINKS.json` |
| Stable HP structure | `codex/docs/CANDY_HP_STRUCTURE_MAP.md`, `CANDY_CODE_FILE_STRUCTURE.md`, and `CANDY_SEO_SPEC.md` |
| Current HP state | The four documents under `codex/docs/generated/` |

## 5. Task Routes

| Task | Required route |
|---|---|
| Management architecture change | `AGENTS.md` → `codex/README.md` → `codex/MANAGEMENT_SYSTEM_OVERVIEW.md` → `codex/project_management/DOCUMENT_RULES.md` |
| Multi-Codex coordination | `AGENTS.md` → `codex/README.md` → `codex/project_management/TASK_RESERVATIONS.md` → `codex/project_management/CODEX_COMMUNICATION.md` |
| Overall status review | `AGENTS.md` → `codex/README.md` → `codex/project_management/PROJECT_STATUS.md` |
| HP page production | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → applicable runbook |
| HP management-document update | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Current site-wide state | `CANDY_MASTER_DOC_INDEX.md` → `generated/CANDY_SITE_PAGE_LEDGER.md` |
| Unbuilt pages and production candidates | `CANDY_MASTER_DOC_INDEX.md` → `generated/CANDY_UPCOMING_PAGES.md` → category queue/classification |
| PHP, source, and dataset structure | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_CODE_ASSET_INVENTORY.md` |
| CSS, JavaScript, and image investigation | `CANDY_CODE_FILE_STRUCTURE.md` → `generated/CANDY_CODE_ASSET_INVENTORY.md` |
| Replace an existing approved area-image pair under the same canonical filenames | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` → actual target files |
| Replace another existing public static asset at the same path | `AGENTS.md` → `codex/README.md` → `HP/AGENTS.md` → `CANDY_MASTER_DOC_INDEX.md` → `CANDY_OPERATION_BASICS.md` → `CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| SEO investigation or change | `CANDY_SEO_SPEC.md` → `generated/CANDY_SEO_STATUS.md` → category specification |
| After a page addition or change | `codex\scripts\candy-site-state.cmd write` → `check` |
| Deletion, movement, or bulk cleanup | `AGENTS.md` → `codex/README.md` → `codex/project_management/SAFETY_PROTOCOL.md` |

## 6. Current Execution Restrictions

- Internal path migration under `codex/scripts/` and read-only validation for area, hotel, and blog are complete. Verify content and authority separately before actual page generation or publish operations.
- `audit`, `preview`, and `check` in `candy-site-state` are read-only. `write` modifies only the four documents under `codex/docs/generated/`.
- A task that changes an HP page, PHP, source, dataset, CSS, JavaScript, image, or SEO MUST run `write` and `check` before staging.
- Publish, Commit, Push, Actions, production, and database operations still require explicit instruction. Permission to update management documents does not authorize those operations.

## 7. Duplicate-Source Prohibitions

- Do not duplicate a canonical management source at the local repository root, under `HP/`, or on the NAS.
- Do not create `HP/HP/`.
- Do not create `HP/README.md`. HP routing is centralized in `HP/AGENTS.md`.
- Do not use legacy documents in NAS `Backup/` as current specifications. Reconcile them with the local canonical source before use.
- Do not mix specifications, current state, task history, and reports in one document.
