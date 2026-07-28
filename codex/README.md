# candy Management Entry Point

This README is the entry point for the management documents under `C:\Codex\Candy\codex`.

## 1. Canonical Sources and Work Locations

| Type | Location | Responsibility |
|---|---|---|
| Local Git working repository | `C:\Codex\Candy` | The only working repository root synchronized with GitHub |
| GitHub synchronization hub | `makotonishikubo0418-cmd/candy` | Shares commits between Codex tasks |
| Canonical Codex management source | `C:\Codex\Candy\codex` | Contains the management entry point, management documents, HP production specifications, and work tools |
| Project management | `C:\Codex\Candy\codex\project_management` | Canonical source for rules, current state, reservations, history, and safety procedures |
| Actual site tree | `C:\Codex\Candy\HP` | Contains HP data such as PHP, source, includefile, images, logs, and movies |
| Production inputs | Root-level `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` | Source data for page production that is not published directly to HP |
| NAS storage | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only location for `Backup/`. Git operations are prohibited |

## 2. Responsibility Boundary

Root `AGENTS.md` Section 5.2 is the sole authority for selecting the management
documents required for a task. This README defines locations and document
responsibilities only. It does not add authority, preflight, Git, reservation,
reporting, reading, or execution rules.

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
| `Text_hotel_data/` | Hotel-page production inputs. Accepted hotel-image source pairs are stored under the Git-managed local `Text_hotel_data/画像データ/` directory and are never referenced directly by public HTML |
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
| Safety procedure for deletion, movement, and bulk operations | `codex/project_management/SAFETY_PROTOCOL.md` |
| HP production and generation specifications | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Area nearby-link mapping | `codex/data/CANDY_AREA_RELATED_LINKS.json` |
| Stable HP structure | `codex/docs/CANDY_HP_STRUCTURE_MAP.md`, `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`, and `codex/docs/CANDY_SEO_SPEC.md` |
| Current HP state | The four documents under `codex/docs/generated/` |

## 5. Duplicate-Source Prohibitions

- Do not duplicate a canonical management source at the local repository root, under `HP/`, or on the NAS.
- Do not create `HP/HP/`.
- Do not use legacy documents in NAS `Backup/` as current specifications. Reconcile them with the local canonical source before use.
- Do not mix specifications, current state, task history, and reports in one document.
