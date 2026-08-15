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
| Project management | `C:\Codex\FSG\Candy\codex\project_management` | Canonical source for rules, current state, central cases, case parents, reservations, history, communication, and safety procedures |
| Actual site tree | `C:\Codex\FSG\Candy\HP` | Contains HP data such as PHP, source, includefile, images, logs, and movies |
| Production inputs | Root-level `Text_area_data`, `Text_blog_data`, and `Text_hotel_data` | Source data for page production that is not published directly to HP |
| NAS storage | `\\192.168.1.3\disk1\FSG_SEO\candy` | Storage-only location for `Backup/`. Git operations are prohibited |

### 1.1 Local Git Layout

- The current Candy working repository is `C:\Codex\FSG\Candy`; its Git metadata belongs to `C:\Codex\FSG\Candy\.git`.
- `HP/`, `codex/`, `docs/`, and the root-level production-input folders are parts of the same repository unless a live `.git` directory or file proves otherwise.
- The configured GitHub repository is `makotonishikubo0418-cmd/candy`. Verify its live branches through `docs/rules/GIT_RULES.md`; do not treat local remote-tracking references as live GitHub evidence.
- The NAS storage location is not a Candy working repository. Do not include it in local Candy Git operations.

### 1.2 Stable Site and Access Entry Points

| Item | Canonical entry |
|---|---|
| Public canonical URL | `https://www.55810.com/` |
| Direct server verification URL | `http://firststar.kir.jp/group/candy/` |
| Production server path | `/public_html/group/candy/` |
| Test server path | `/public_html/group_test/candy/` |
| GitHub repository | `makotonishikubo0418-cmd/candy` |
| Production deployment | Push-triggered GitHub Actions for eligible `HP/` targets; management-only Markdown changes are excluded from deployment |
| External runtime configuration | `/home/firststar/public_html/group/control/includefile/setting_session_vv.php` and `/home/firststar/public_html/group/control/includefile/incfiles_vv.php` |
| Read-only database entry | `codex/docs/CANDY_OPERATION_BASICS.md` Section 8; the live Candy-to-database mapping remains `UNVERIFIED` until checked through the approved wrapper |

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
| `codex/project_management/cases/` | Parent documents for non-atomic active and completed cases; creation is governed only by `DOCUMENT_RULES.md` |
| `codex/project_management/task_history/` | Time-bounded historical task-log children owned by `TASK_LOG.md` |
| `codex/docs/` | Active HP production runbooks and specifications for area, hotel, blog, and other categories |
| `codex/docs/generated/` | Current page, production-candidate, code/asset, and SEO state generated from actual files. Manual editing is prohibited |
| `codex/data/` | Canonical operational mapping data consumed by production tooling, including the approved area nearby-link graph |
| `codex/scripts/` | Page generation, validation, and publishing scripts |
| `HP/` | The actual public site tree. `includefile`, `log`, and `movie` are also HP data |
| `HP/docs/` | Source-attached member-system technical references outside the formal management-document tree. `MEMBER_ARCHITECTURE.md` is their technical index; `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md` remains the canonical management owner |
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
| All-case list and case-parent mapping | `codex/project_management/CASE_REGISTRY.md` |
| One non-atomic case's analysis, phases, implementation, verification, and completion | Its registered parent under `codex/project_management/cases/` or its registered historical parent |
| Inter-Codex communication and handoff | `codex/project_management/CODEX_COMMUNICATION.md` |
| Task and file reservations | `codex/project_management/TASK_RESERVATIONS.md` |
| Completed task execution history | `codex/project_management/TASK_LOG.md` and its children under `codex/project_management/task_history/` |
| Safety procedure for deletion, movement, and bulk operations | `codex/project_management/SAFETY_PROTOCOL.md` |
| HP production and generation specifications | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| Member mypage, API, authentication, and notification management | `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`; the non-management technical index is `HP/docs/MEMBER_ARCHITECTURE.md`, and actual behavior remains in the implementation and verified environment |
| Area nearby-link mapping | `codex/data/CANDY_AREA_RELATED_LINKS.json` |
| Stable HP structure | `codex/docs/CANDY_HP_STRUCTURE_MAP.md`, `codex/docs/CANDY_CODE_FILE_STRUCTURE.md`, and `codex/docs/CANDY_SEO_SPEC.md` |
| Current HP state | The generated Markdown parents, Markdown child, and deterministic tabular sidecars under `codex/docs/generated/` |

### 4.1 System and Operational Responsibility Lookup

This table identifies the existing owner for each operational information class. It does not add task-routing rules; select required documents through `WORK_ROUTING.md`.

| Information class | Canonical owner or evidence source |
|---|---|
| GitHub repository identity and local repository location | This README |
| Git branch, live GitHub comparison, staging, Commit, and Push procedure | `docs/rules/GIT_RULES.md` |
| Database dependency, external configuration, and approved read-only connection boundary | `codex/docs/CANDY_OPERATION_BASICS.md`, then the actual configuration and approved live evidence |
| Session, Cookie, authentication, member API, and external-integration impact | `codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md`, the source-attached technical index under `HP/docs/`, and the actual implementation |
| Production and test server paths, GitHub Actions, FTP, placement, recovery, and rollback | `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` and the exact workflow or script |
| Canonical public host and URL requirements | `codex/docs/CANDY_SEO_SPEC.md` and the production entry contract in `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| DNS records, TLS certificate, and TLS termination state | The live external provider or server is the evidence source; `codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` owns the verification and change boundary. Treat the current value as `UNVERIFIED` until checked |

## 5. Formal Management-Document Tree

This tree describes actual ownership and lifecycle. It is not a second task-routing source; select required documents only through `WORK_ROUTING.md`. Deprecated and historical documents appear so no retained Markdown is orphaned.

```text
C:\Codex\FSG\Candy
├─ AGENTS.md [Authority / Active]
├─ docs/
│  └─ rules/
│     └─ GIT_RULES.md
└─ codex/
   ├─ WORK_ROUTING.md
   ├─ README.md
   ├─ MANAGEMENT_SYSTEM_OVERVIEW.md
   ├─ project_management/
   │  ├─ DOCUMENT_RULES.md
   │  ├─ PROJECT_STATUS.md
   │  ├─ CASE_REGISTRY.md
   │  ├─ cases/
   │  │  ├─ CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md
   │  │  ├─ CANDY_MANAGEMENT_SYSTEM_REBUILD.md
   │  │  └─ CANDY_MANAGEMENT_SYSTEM_REPAIR.md
   │  ├─ SAFETY_PROTOCOL.md
   │  ├─ TASK_RESERVATIONS.md
   │  ├─ CODEX_COMMUNICATION.md
   │  ├─ TASK_LOG.md
   │  ├─ task_history/
   │  │  ├─ TASK_LOG_2026_07_01_20.md
   │  │  ├─ TASK_LOG_2026_07_21_31.md
   │  │  └─ TASK_LOG_2026_08.md
   │  ├─ CODE_STRUCTURE.md [Deprecated Compatibility]
   │  └─ CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md [Historical Evidence]
   ├─ docs/
   │  ├─ CANDY_MASTER_DOC_INDEX.md
   │  ├─ CANDY_OPERATION_BASICS.md
   │  ├─ CANDY_CODE_FILE_STRUCTURE.md
   │  ├─ CANDY_HP_STRUCTURE_MAP.md
   │  ├─ CANDY_PAGE_GENERATION_GOVERNANCE.md
   │  ├─ CANDY_AREA_PAGE_GENERATION_SPEC.md
   │  ├─ CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md
   │  ├─ CANDY_AREA_105_PAGE_QUEUE.md
   │  ├─ CANDY_AREA_IMAGE_CREATION_RUNBOOK.md
   │  ├─ CANDY_AREA_IMAGE_CREATION_SPEC.md
   │  ├─ CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md
   │  ├─ CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md
   │  ├─ CANDY_HOTEL_PAGE_GENERATION_SPEC.md
   │  ├─ CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md
   │  ├─ CANDY_HOTEL_TEXT_INPUT_CLASSIFICATION.md
   │  ├─ CANDY_HOTEL_CONTENT_PREPARATION_RUNBOOK.md
   │  ├─ CANDY_HOTEL_IMAGE_CREATION_SPEC.md
   │  ├─ CANDY_HOTEL_IMAGE_ASSET_MANAGEMENT.md
   │  ├─ CANDY_BLOG_PAGE_GENERATION_SPEC.md
   │  ├─ CANDY_OTHER_PAGES_MANAGEMENT.md
   │  ├─ CANDY_SEO_SPEC.md
   │  ├─ CANDY_VERIFICATION_PLAN.md
   │  ├─ CANDY_PRODUCTION_MIGRATION_MASTER.md
   │  ├─ CANDY_FIX_BACKLOG.md
   │  ├─ CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md
   │  ├─ CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md [Historical Evidence]
   │  ├─ CANDY_AI_EXECUTION_DISCIPLINE.md [Deprecated Compatibility]
   │  ├─ CANDY_CODEX_BACKUP_REMARKS.md [Deprecated Compatibility]
   │  ├─ CANDY_EXISTING_DOCS_INVENTORY.md [Deprecated Compatibility]
   │  ├─ CANDY_FOLDER_ROLE_MAP.md [Deprecated Compatibility]
   │  ├─ CANDY_FULL_FILE_CODE_INVENTORY.md [Deprecated Compatibility]
   │  ├─ CANDY_NON_CODE_ASSET_INVENTORY.md [Deprecated Compatibility]
   │  ├─ CANDY_PAGE_CATEGORY_STRUCTURE.md [Deprecated Compatibility]
   │  ├─ CANDY_PAGE_SPEC_INDEX.md [Deprecated Compatibility]
   │  ├─ CANDY_PHASE_RECHECK.md [Deprecated Compatibility]
   │  └─ generated/
   │     ├─ CANDY_SITE_PAGE_LEDGER.md
   │     ├─ CANDY_SITE_PAGE_LEDGER.tsv
   │     ├─ CANDY_UPCOMING_PAGES.md
   │     ├─ CANDY_UPCOMING_AREA_PAGES.tsv
   │     ├─ CANDY_UPCOMING_HOTEL_PAGES.tsv
   │     ├─ CANDY_UPCOMING_BLOG_PAGES.tsv
   │     ├─ CANDY_CODE_ASSET_INVENTORY.md
   │     ├─ CANDY_CODE_REFERENCE_INVENTORY.md
   │     ├─ CANDY_SEO_STATUS.md
   │     └─ CANDY_SEO_STATUS.tsv
   ├─ data/
   │  └─ hotel-image-handoff-20260723/
   │     └─ HANDOFF_README.md [Completed]
   └─ 指示書監査.md [Historical Evidence]
```

The complete filename-level tree is maintained in `WORK_ROUTING.md` Section 5.1 and MUST match this ownership tree and the filesystem.

## 6. Duplicate-Source Prohibitions

- Do not duplicate a canonical management source at the local repository root, under `HP/`, or on the NAS. Repository-root `AGENTS.md` and `docs/rules/GIT_RULES.md`, explicitly selected by the Candy authority and routing documents, are the only current exceptions.
- The seven existing files under `HP/docs/` are non-management source-attached technical references. `CANDY_OTHER_PAGES_MANAGEMENT.md` is the canonical management owner and links to the single technical index `MEMBER_ARCHITECTURE.md`; the technical index links to its six Phase children. None of the seven proves live database, server, authentication, deployment, or production state. Do not create another `HP/docs/` Markdown file without a routed owner and explicit approval.
- Do not create `HP/HP/`.
- Do not use legacy documents in NAS `Backup/` as current specifications. Reconcile them with the local canonical source before use.
- Do not mix specifications, current state, task history, and reports in one document.
