# CANDY Operation Basics

## 1. Responsibility

This document owns the short common procedure for investigating, fixing, and
validating the existing HP site. It does not define instruction priority,
document routing, Git authority, production limits, or response format.

## 2. Target-State Preflight

When a target page exists, verify agreement between the generated ledger and
actual files before treating the page state as known.

```powershell
codex\scripts\candy-site-state.cmd check --target "<slug>"
```

## 3. Investigation Unit

As required, review the following as one unit:

- Public PHP directly under HP
- Matching HTML under `HP/source/`
- `HP/includefile/dataset_*.php`
- `dataset_base.php`, `class.hpgcoder2.php`, and `funcs.php`
- CSS, JavaScript, images, and movies
- Source Text data
- Indexes, related pages, internal links, and sitemap
- Database, session, and external-integration dependencies

File and reference counts change. Count actual files instead of using fixed values in this document. Do not infer a specification from one file.

## 4. Before a Change

Determine the target-specific facts:

- Affected pages, desktop/mobile, and common processing
- Impact on databases, production, secrets, logs, and payments
- Validation method
- Unverified items and required user decisions

When `check --target` fails because of drift, identify the cause of the existing inconsistency first. Do not mix an existing inconsistency fix with separate new production or a feature change.

Do not create mechanical `.before` copies beside Git-tracked files. Use Git and the explicitly defined production rollback method. When an untracked asset or production file requires preservation, identify the target, destination, and recovery method before acting.

## 5. During a Change

- Validate replacement tokens, datasets, includes, links, and image references together.
- For an existing approved area-image pair replaced under the same canonical filenames, use `CANDY_AREA_IMAGE_REPLACEMENT_RUNBOOK.md` as the self-contained fast route. For another cacheable static-file replacement at the same public path, follow `CANDY_PRODUCTION_MIGRATION_MASTER.md`.
- Do not set a fixed maximum file count.
- For a common-processing change, check impact on out-of-scope pages.

## 6. After a Change

After changing an HP page, PHP, source, dataset, CSS, JavaScript, image, or SEO, update the generated documents and verify agreement before staging.

```powershell
codex\scripts\candy-site-state.cmd preview-sitemap-lastmod
codex\scripts\candy-site-state.cmd sync-sitemap-lastmod
codex\scripts\candy-site-state.cmd write
codex\scripts\candy-site-state.cmd check
```

Sitemap synchronization changes only `lastmod` values whose matching
`HP/source/<stem>.html` Git change date differs. Review the sitemap diff before
staging. A source mapping, duplicate URL, or Git-date failure is a STOP
condition.

Add as required:

- PHP, HTML, or JavaScript syntax validation
- Generated-output validation
- Internal links and referenced images
- Desktop/mobile rendering
- JavaScript console
- Database, session, and external service behavior
- HTTP responses

### 6.1 Efficient Verification and Browser Fallback

- Assign one authoritative verification method to each requirement. Do not repeat a passed check through another tool unless a relevant file changed after the result or the first result was incomplete.
- Group independent read-only or syntax checks into one bounded execution when this reduces tool startup and waiting time without hiding individual failures.
- Use HTTP, source inspection, or deterministic tests for status codes, headers, URLs, file contents, and generated output. Use Chrome only for behavior or rendering that cannot be verified by those methods.
- If browser control disconnects, do not repeat reconnection attempts. Retry once only when an unresolved screen-only requirement remains; otherwise switch to HTTP or deterministic checks and report the screen result as unverified.
- Do not wait on or rerun a slow tool after it already returned a complete
  authoritative result. When a tool must continue, keep the wait bounded and
  surface a material delay or blocker.

## 7. Production and Test

| Purpose | Path |
|---|---|
| Production | `/public_html/group/candy/` |
| Test | `/public_html/group_test/candy/` |

Environment behavior, protection targets, workflow triggers, limits, rollback,
and production verification are owned by
`CANDY_PRODUCTION_MIGRATION_MASTER.md` and the exact workflow/scripts selected
by the applicable production route in root `AGENTS.md` Section 5.2.

## 8. Unverified Scope

Treat the production PHP version, web-server type, actual database, and
external-service settings as unverified until rechecked. Do not treat path
candidates or values from old documents as current values. For information
close to secrets, record its location without copying the value.
