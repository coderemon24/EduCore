# EduCore Multi-Agent Orchestration User Manual
> **Unified Autonomous Engineering Workflow with Git Worktrees, OpenSpec, and Automated Push**

This document outlines the architecture, roles, and step-by-step workflow for orchestrating specialized AI engineering agents across isolated Git worktrees in the **EduCore (Laravel School)** platform.

---

## 1. System Architecture & Lifecycle Overview

The multi-agent system uses isolated **Git Worktrees** to eliminate file collisions and merge conflicts when multiple agents build features simultaneously. Each subagent operates inside its dedicated worktree, commits changes as Pull Requests (branches), and the Team Lead reviews, tests edge cases, integrates, archives OpenSpec changes, and pushes to GitHub.

```
                         ┌─────────────────────────────┐
                         │      User / Product Owner   │
                         └──────────────┬──────────────┘
                                        │ 1. Feature Prompt / Explore
                                        ▼
                         ┌─────────────────────────────┐
                         │     Team Lead (Main Repo)   │
                         │   - OpenSpec Propose/Apply  │
                         │   - Interface Contracts     │
                         │   - Pre-flight Sync         │
                         └──────┬───────────────┬──────┘
             Enriched Directive │               │ Enriched Directive
             (DB Opt + Break)   │               │ (ui-refactor.md)
                                ▼               ▼
      ┌─────────────────────────────┐         ┌─────────────────────────────┐
      │      Backend Engineer       │         │      Frontend Engineer      │
      │  C:/laragon/www/EduCore-    │         │  C:/laragon/www/EduCore-    │
      │  backend                    │         │  frontend                   │
      │  (branch: agent/backend)    │         │  (branch: agent/frontend)   │
      └──────────────┬──────────────┘         └──────────────┬──────────────┘
                     │                                       │
                     │ Autonomous Parallel Coding            │ Autonomous Parallel Coding
                     │ - N+1 Elimination, Indexing           │ - Blade Templates & CSS
                     │ - Adversarial Stress Testing          │ - 7 ui-refactor.md Recipes
                     │ - Commit to agent/backend (PR)        │ - Commit to agent/frontend (PR)
                     ▼                                       ▼
      ┌─────────────────────────────┐         ┌─────────────────────────────┐
      │   Backend PR Ready          │         │   Frontend PR Ready         │
      └──────────────┬──────────────┘         └──────────────┬──────────────┘
                     │                                       │
                     └───────────────────┬───────────────────┘
                                         ▼
                         ┌─────────────────────────────┐
                         │    Phase 3: Lead PR Review  │
                         │    & Edge-Case Test Gate    │
                         └──────┬───────────────┬──────┘
                                │               │
                  Issues Found? │               │ Zero Issues & Green Tests
                                ▼               ▼
               ┌────────────────────────┐  ┌─────────────────────────────┐
               │ Subagent Feedback Loop │  │ Phase 4: Integration        │
               │ (Dispatch targeted fix)│  │ - Merge to main             │
               └────────────────────────┘  │ - OpenSpec Archive Change   │
                                           │ - Push to GitHub (gitcommit)│
                                           └─────────────────────────────┘
```

---

## 2. Team Roles & Specialized Mandates

### 1. Frontend Engineer
- **Worktree**: `C:/laragon/www/EduCore-frontend` (branch: `agent/frontend`)
- **File Ownership**: `resources/views/`, `public/assets/`
- **Design Consistency (CRITICAL)**:
  - Must inspect existing production views before writing code: `resources/views/frontend/academic/classes.blade.php`, `about/index.blade.php`, `routine.blade.php`, and `teachers/index.blade.php`.
  - Maintain identical card styling, container widths, spacing rhythm, and color schemes.
- **Mandatory UI Refinement via [ui-refactor.md](file:///c:/laragon/www/laravel-school/ui-refactor.md)**:
  After assembling initial views, apply the 7 Master UI Recipes:
  1. **Sharpen the Message**: Clear headline promise with focal span `<span class="text-[#005030] font-bold">`.
  2. **Break the Symmetry**: 60/40 or 65/35 editorial layout with floating anchor credential badges.
  3. **Build the Argument**: Uniform `.section-header-block` (kicker + title + subtitle) and hairline dividers `.home-section-divider`.
  4. **Pick the Winner**: 60-65% flagship card dominance over uniform card grids.
  5. **Scale the Type**: Strict 4-tier typographic scale with `leading-relaxed` on body text.
  6. **Give Color Meaning**: Dark Green `#005030` for brand accents, Warm Amber `amber-400` strictly for Admission/CTAs, Sky Blue for notices, calm neutral card base.
  7. **Add a Signature**: Section kicker with pulsing dot (`.section-kicker-dot`), hairline dividers, and micro-interactions.
- **UI-Mandatory Guidelines**: Zero placeholder buttons, full CRUD, responsive layouts without horizontal scroll.
- **Commit & PR**: Stages and commits to `agent/frontend` branch ready for Lead PR review.

### 2. Backend Engineer
- **Worktree**: `C:/laragon/www/EduCore-backend` (branch: `agent/backend`)
- **File Ownership**: `app/`, `database/`, `routes/`, `tests/`
- **Framework Standards**: Strictly follows `laravel-expert`, `laravel-best-practices`, and `laravel-security-audit`.
- **Database Optimization (MANDATORY)**:
  - **Zero N+1 Queries**: Eager-load relations (`with()`, `loadMissing()`). No lazy loading inside loops.
  - **Deadlock Prevention**: Short atomic `DB::transaction()` blocks, consistent record lock order.
  - **Proper Indexing**: Add indexes for foreign keys, tenant column (`school_id`), search/filter columns, and sorting criteria.
  - **Data Consistency**: Foreign key cascades/restricts and atomic counters.
- **Automated & Adversarial Testing ("Break it to make it unbreakable")**:
  - Writes Unit tests (`tests/Unit/`) and Feature tests (`tests/Feature/`).
  - Actively attempts to break the feature with real-life edge cases: invalid types, nulls, tenant boundary violations, empty records, and duplicate submissions.
  - If broken, diagnoses, fixes root causes, and re-tests until 100% green.
- **Commit & PR**: Stages and commits to `agent/backend` branch ready for Lead PR review.

### 3. Team Lead
- **Worktree**: `C:/laragon/www/laravel-school` (branch: `main`) & `EduCore-lead`
- **Responsibilities**:
  - Analyzes user feature prompt or OpenSpec explore requirements.
  - Initializes OpenSpec change (`openspec.cmd new change <name>`).
  - Formulates architecture and shared contracts (routes, view variables, form payloads, database schema).
  - Syncs worktrees (`python scripts/orchestrator.py sync`).
  - Dispatches parallel workers with enriched directives.
  - Inspects PR diffs (`python scripts/orchestrator.py review`).
  - Runs automated test suite and edge-case testing (`python scripts/orchestrator.py test`).
  - **Iterative Feedback Loop**: If issues are found, dispatches corrective tasks to subagents until clean.
  - Merges approved branches (`python scripts/orchestrator.py integrate`).
  - Archives OpenSpec change (`openspec.cmd archive <name> --yes`).
  - Staged commit and push to GitHub following [gitcommit.md](file:///c:/laragon/www/laravel-school/gitcommit.md) (`git push -u origin main`).

---

## 3. Single-Command Autonomous Execution Pipeline

You can run the entire lifecycle from planning to GitHub push with a single command:

```bash
python scripts/orchestrator.py pipeline \
  --change "student-admission-portal" \
  --prompt "Build an online admission application module where prospective students can submit applications, view application status, and administrators can review submissions."
```

### What this single command executes automatically:
1. **Sync**: Merges `main` into all worktrees.
2. **OpenSpec Propose**: Creates or validates the change in `openspec/changes/student-admission-portal/`.
3. **Enrich Directives**: Injects DB optimization, adversarial testing, and `ui-refactor.md` rules into worker prompts.
4. **Parallel Dispatch**: Launches `backend-engineer` and `frontend-engineer` concurrently in their isolated worktrees.
5. **PR Review**: Generates git diffs between agent branches and `main`.
6. **Test Gate**: Runs the Laravel test suite (`php artisan test`).
7. **Integrate**: Merges `agent/backend` and `agent/frontend` cleanly into `main`.
8. **Archive**: Archives the change via `openspec.cmd archive student-admission-portal --yes`.
9. **GitHub Push**: Creates a conventional commit message and pushes to `origin/main` following `gitcommit.md`.

---

## 4. Orchestrator CLI Command Reference

All orchestration actions are controlled via [scripts/orchestrator.py](file:///c:/laragon/www/laravel-school/scripts/orchestrator.py):

| Command | Purpose | Example |
| :--- | :--- | :--- |
| `status` | Check worktree existence, branches, and git dirty status | `python scripts/orchestrator.py status` |
| `setup` | Initialize or repair worktrees, branches, .env, and junctions | `python scripts/orchestrator.py setup` |
| `sync` | Merge latest `main` into all agent worktrees | `python scripts/orchestrator.py sync` |
| `pipeline` | Full end-to-end autonomous feature lifecycle | `python scripts/orchestrator.py pipeline --change fee-voucher --prompt "..."` |
| `run-parallel` | Concurrently dispatch backend and frontend agents | `python scripts/orchestrator.py run-parallel --backend "..." --frontend "..."` |
| `dispatch` | Run a single agent task in its isolated worktree | `python scripts/orchestrator.py dispatch --agent backend-engineer --prompt "..."` |
| `review` | Inspect git diff summary between agent branches and main | `python scripts/orchestrator.py review` |
| `test` | Run Laravel automated test suite with optional filter | `python scripts/orchestrator.py test --filter=AdmissionTest` |
| `integrate` | Merge reviewed agent branches into `main` after test gate | `python scripts/orchestrator.py integrate --filter=AdmissionTest` |
| `archive` | Archive an OpenSpec change | `python scripts/orchestrator.py archive --change fee-voucher` |
| `push` | Commit and push changes to GitHub following `gitcommit.md` | `python scripts/orchestrator.py push --message "feat(admission): ..."` |

---

## 5. Iterative Subagent Feedback Loop (Handling Issues)

If the Team Lead discovers an issue during PR review or test execution:
1. Identify the responsible worktree (`backend` or `frontend`).
2. Dispatch a corrective task:
   ```bash
   python scripts/orchestrator.py dispatch \
     --agent backend-engineer \
     --prompt "Fix: The admission store method fails when guardian_phone is null. Add nullable validation and database constraint handling. Re-run AdmissionTest."
   ```
3. The subagent fixes the issue in its worktree, verifies tests, and creates a new commit.
4. The Team Lead runs `python scripts/orchestrator.py review` and `test` again before completing integration.

---

## 6. Safety & Best Practices

1. **Strict File Ownership**: Backend never touches `resources/views/`. Frontend never touches backend PHP classes or database migrations.
2. **Database Optimization**: No N+1 queries allowed. Relations must be eager-loaded. All foreign keys and search fields must be indexed.
3. **Adversarial Testing**: Every backend module must include edge-case tests designed to break assumptions before passing the gate.
4. **UI Refinement**: Every frontend page must pass through the 7 recipes of [ui-refactor.md](file:///c:/laragon/www/laravel-school/ui-refactor.md).
5. **Clean Worktrees**: Worktrees should always be clean before running `sync` or `pipeline`.
