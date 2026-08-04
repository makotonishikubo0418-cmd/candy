# Git Rules

- Purpose: Define local branch selection, management-branch handling, pre-work Git verification, required reporting, and STOP conditions.
- Status: canonical rule document
- Canonical scope: Git repository discovery, branch selection, live GitHub branch verification, management-document publication through Git, and unverifiable-state handling
- Higher authority: root `AGENTS.md`
- Work routing: `docs/rules/WORK_ROUTING.md`

## Work Branch Selection

- Determine the instructed objective, approved file scope, repository root, current branch, all local branches, and all live GitHub branches before selecting a work branch.
- Use an existing branch only when its verified purpose matches the instructed work. Do not select a branch from its name alone when its purpose is unclear.
- The user’s explicitly specified repository and branch take priority.
- Do not create, switch, rename, delete, reset, merge, rebase, or pull a branch without the user’s specific permission.
- If the current branch is not the verified work branch, report the current branch, proposed branch, reason, and impact, then STOP until the user approves the required Git-state change.
- Preserve unrelated uncommitted changes. If the approved targets overlap existing changes and the ownership or intended result cannot be established, report the overlap and STOP.

## Management Branch Management and Publication

- A management branch is a branch explicitly designated to contain management-document changes. Never assume that a branch named `management` exists or is required.
- Verify the exact local and GitHub branch names and SHAs before treating a management branch as available.
- If no designated management branch exists locally or on GitHub, report `NOT_PRESENT`. Do not create or publish one without the user’s specific permission.
- Creating or switching to a management branch, staging, committing, pushing, opening or merging a pull request, and deleting a branch are separate Git-state changes. Each requires permission that specifically covers the operation.
- Publication means that the approved commit is pushed to the approved GitHub branch and the live remote SHA is verified. It does not mean production deployment.
- Before an authorized publication, verify the target repository, target branch, source `HEAD`, staged paths, staged diff, uncommitted out-of-scope changes, remote URL, and live target-branch SHA.
- After an authorized publication, verify that the pushed remote branch SHA equals the intended local commit and report any unmerged or unpublished branch separately.

## Pre-Work Git Verification

- Perform this procedure exactly once at the beginning of each task before investigation or modification.
- Use read-only commands that do not update the working tree, local branches, commit history, index, remote-tracking references, or `.git/FETCH_HEAD`.
- A post-change diff or status check is completion validation and does not repeat this pre-work procedure.

### Verification Method

1. Within the user-approved project root, identify every `.git` directory or file and resolve each unique repository root.
2. For every local repository, obtain the current branch, working-tree state, current `HEAD`, every local branch name and SHA, and configured remotes.
3. For every GitHub remote, obtain every live branch name and SHA without updating local remote-tracking references.
4. Compare branches with the same name by their verified SHAs. Treat a missing same-name branch or different SHA as a difference; do not infer ahead, behind, or divergence direction without complete evidence.
5. Identify the proposed work branch by applying `Work Branch Selection`.

Use the following read-only Git commands as applicable:

```powershell
git -C "<repository>" status --short --branch --untracked-files=all
git -C "<repository>" rev-parse HEAD
git -C "<repository>" for-each-ref --format="%(refname:short)|%(objectname)" refs/heads
git -C "<repository>" remote -v
git -C "<repository>" ls-remote --heads <github-remote>
```

Do not run `fetch`, `pull`, `switch`, `checkout`, `reset`, `merge`, `rebase`, `commit`, or `push` as part of pre-work verification.

### Report Content

Report the following verified facts before work begins:

- Total local repository count and every repository root
- Current branch, current `HEAD`, and whether uncommitted changes exist for each repository
- Every local branch name and SHA
- Every GitHub remote URL and every live GitHub branch name and SHA
- The selected work branch and the evidence used to select it
- Matching, missing, or different local-versus-GitHub branch states
- Every unverified item, exception, and exact error that affects the decision to proceed

### Mismatch and Unverifiable Conditions

- If a required result is incomplete, omitted, or returned with an error, discard that result and retry the complete operation once.
- If complete, error-free information still cannot be obtained, write exactly `情報取得エラー`, identify the affected repository, branch, command or service, and exact cause, then STOP.
- If a same-name local and GitHub branch has a different SHA, a required branch is missing, the work branch cannot be selected unambiguously, or an overlapping uncommitted change cannot be preserved safely, report the facts and STOP.
- If network access, authentication, remote configuration, or repository validity prevents live verification, do not substitute a local remote-tracking reference or memory for the missing live result.
- Work may proceed under an unverified or mismatched condition only after the user explicitly approves that condition. The final report must identify every action and judgment affected by it.
