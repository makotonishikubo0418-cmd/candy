# candy Management Entry Point

This README is the entry point for the management documents under `C:\Codex\FSG\Candy\codex`.

## 1. Canonical Sources and Work Locations

| Type | Location | Responsibility |
|---|---|---|
| Local Git working repository | `C:\Codex\FSG\Candy` | The only working repository root synchronized with GitHub |
| GitHub synchronization hub | `makotonishikubo0418-cmd/candy` | Shares commits between Codex tasks |
| Candy management authority | `C:\Codex\FSG\Candy\AGENTS.md` | Highest-authority rules for this Candy project |
| Candy work routing | `C:\Codex\FSG\Candy\codex\WORK_ROUTING.md` | Selects the management documents and execution method required for Candy work |
| Candy Git rules | `C:\Codex\FSG\Candy\docs\rules\GIT_RULES.md` | Contains Candy repository verification, branch, and publication rules |
| Canonical Codex management source | `C:\Codex\FSG\Candy\codex` | Contains the management entry point, management documents, HP production specifications, and work tools |
| Project management | `C:\Codex\FSG\Candy\codex\project_management` | Canonical source for rules, current state, reservations, history, and safety procedures |
| Actual site tree | `C:\Codex\FSG\Candy\HP` | Contains HP data such as PHP, source, includefile, images, logs, and movies |
| Production inputs | Root-level `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` | Source data for page production that is not published directly to HP |
| NAS storage | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only location for `Backup/`. Git operations are prohibited |

### 1.1 Local Git Layout

- The current Candy working repository is `C:\Codex\FSG\Candy`; its Git metadata belongs to `C:\Codex\FSG\Candy\.git`.
- `HP/`, `codex/`, `docs/`, and the root-level production-input folders are parts of the same repository unless a live `.git` directory or file proves otherwise.
- The configured GitHub repository is `makotonishikubo0418-cmd/candy`. Verify its live branches through `docs/rules/GIT_RULES.md`; do not treat local remote-tracking references as live GitHub evidence.
- The NAS storage location is not a Candy working repository. Do not include it in local Candy Git operations.

## 2. Responsibility Boundary

`C:\Codex\FSG\Candy\AGENTS.md` is the highest authority. `docs/rules/GIT_RULES.md` owns the
common pre-work Git procedure, and `codex/WORK_ROUTING.md` owns selection
of the management documents required for a task. This README defines locations and document
responsibilities only. It does not add authority, preflight, Git, reservation,
reporting, reading, or execution rules.

## 3. Folder Responsibilities

| Folder | Responsibility |
|---|---|
| `AGENTS.md` | Highest-authority Candy project entry point |
| `docs/rules/` | Candy Git rules selected by `codex/WORK_ROUTING.md` |
| `codex/` | Candy work routing, Codex management documents, production specifications, and scripts. Only active canonical management sources belong on the normal route |
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
| Work routing and required-document selection | `codex/WORK_ROUTING.md` |
| Git branch selection, verification, reporting, and management-branch publication | `docs/rules/GIT_RULES.md` |
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

- Do not duplicate a canonical management source at the local repository root, under `HP/`, or on the NAS. Repository-root `AGENTS.md` and `docs/rules/GIT_RULES.md`, explicitly selected by the Candy authority and routing documents, are the only current exceptions.
- Do not create `HP/HP/`.
- Do not use legacy documents in NAS `Backup/` as current specifications. Reconcile them with the local canonical source before use.
- Do not mix specifications, current state, task history, and reports in one document.
