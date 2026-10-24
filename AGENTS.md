# EduCore Multi-Agent Orchestration & OpenSpec Master Guidelines

## 1. Operating Model: Team Lead & Multi-Agent Architecture

You operate as the **Team Lead** coordinating specialized engineering agents across isolated Git worktrees:
- **Backend Engineer**: `C:/laragon/www/EduCore-backend` (branch: `agent/backend`)
- **Frontend Engineer**: `C:/laragon/www/EduCore-frontend` (branch: `agent/frontend`)
- **Team Lead / Integration**: `C:/laragon/www/EduCore-lead` & Root `laravel-school` (branch: `main`)

Zero file collisions: Workers build simultaneously in their dedicated worktrees, commit their changes as Pull Requests (branches), and the Team Lead acts as the architectural reviewer, test gatekeeper, and integrator.

---

## 2. End-to-End Feature Development Lifecycle (Single-Command Autonomous Flow)

Whenever the user specifies a feature or module requirement (via `/opsx-explore`, `/opsx-propose`, `/opsx-apply`, or a direct prompt):

### Step 1: Requirements & OpenSpec Change Setup
1. Take the user's requirement prompt (or OpenSpec explore output).
2. Create or verify the OpenSpec change:
   ```bash
   openspec.cmd new change <change-name>
   ```
3. Formulate the design, delta specs, and task checklist in `openspec/changes/<change-name>/`.
4. Validate apply readiness:
   ```bash
   openspec.cmd instructions apply --change <change-name> --json
   ```

### Step 2: Architecture Contracts & Worktree Synchronization
1. **Pre-flight Sync**: Ensure all worktrees are up to date:
   ```bash
   python scripts/orchestrator.py sync
   ```
2. **Define Shared Contracts**:
   - **Route Contracts**: Exact URI, HTTP verb, and named route (e.g., `route('frontend.academic.classes')`).
   - **Controller -> View Contracts**: Exact variable names, collections, and pagination parameters.
   - **Form Payloads**: Field names, validation rules, required status, and error keys.
   - **Database Schemas**: Table structures, foreign key constraints, and performance indexes.

### Step 3: Concurrent Worker Dispatch
Dispatch parallel workers with enriched prompt directives:
```bash
python scripts/orchestrator.py run-parallel \
  --backend "<Backend contract, DB optimization & adversarial test prompt>" \
  --frontend "<Frontend contract, UI-Mandatory & ui-refactor.md prompt>"
```

### Step 4: Subagent Execution & Worktree PR Commits
- **Frontend Subagent**:
  - Implements Blade views in `resources/views/` and styles in `public/assets/`.
  - **Refines UI with `ui-refactor.md`**: Must apply the 7 Master UI Recipes (Sharpen Message, Break Symmetry, Build Argument, Pick Winner, Scale Type, Give Color Meaning, Add Signature).
  - **Preserves Page Consistency**: Inspects and adheres to design patterns of existing pages (`classes.blade.php`, `about/index.blade.php`, `routine.blade.php`, `teachers/index.blade.php`).
  - Adheres to [UI-Mandatory.md](file:///c:/laragon/www/laravel-school/UI-Mandatory.md) (no dead buttons, responsive, real links).
  - Commits changes to `agent/frontend`:
    `git commit -m "feat(frontend): <description>"`
- **Backend Subagent**:
  - Follows `laravel-expert`, `laravel-best-practices`, and `laravel-security-audit`.
  - Implements FormRequests, Controllers, Models, and Migrations in `app/`, `database/`, `routes/`.
  - **Database Optimization (Mandatory)**:
    - **Zero N+1 Queries**: Eager-load relations (`with()`, `loadMissing()`). No lazy-loading inside loops.
    - **Deadlock Prevention**: Short atomic `DB::transaction()` blocks, consistent record lock order.
    - **Proper Indexing**: Foreign keys, search/filter fields, and composite lookup columns.
    - **Data Consistency**: Relational constraints, cascading rules, and atomic counters.
  - **Automated & Adversarial Testing ("Break It to Make It Unbreakable")**:
    - Writes Unit & Feature tests in `tests/Unit/` and `tests/Feature/`.
    - Actively attempts to break the feature with real-life edge cases: invalid types, nulls, tenant boundary crossovers, duplicate requests, high concurrency, and malicious input.
    - If broken during testing, fixes the root cause and re-tests until 100% green.
  - Commits changes to `agent/backend`:
    `git commit -m "feat(backend): <description>"`

### Step 5: Team Lead PR Review & Edge-Case Gate
1. **Review Diffs (PR Inspection)**:
   ```bash
   python scripts/orchestrator.py review
   ```
2. **Execute Automated & Edge-Case Test Gate**:
   ```bash
   python scripts/orchestrator.py test --filter=<FeatureName>
   ```
3. **Iterative Feedback Loop**:
   - If any test fails, edge case breaks, N+1 query is identified, or UI violates `ui-refactor.md`:
     Dispatch corrective prompt directly to that worker's worktree:
     ```bash
     python scripts/orchestrator.py dispatch --agent <agent-name> --prompt "Fix: <precise issue details>"
     ```
   - Worker fixes and re-commits. Re-review until 100% clean and verified.

### Step 6: Integration, OpenSpec Archive & GitHub Push
1. **Integrate Worker Branches**:
   ```bash
   python scripts/orchestrator.py integrate --filter=<FeatureName>
   ```
2. **Archive OpenSpec Change**:
   ```bash
   openspec.cmd archive <change-name> --yes
   ```
3. **Auto Commit & Push to GitHub**:
   Follow [gitcommit.md](file:///c:/laragon/www/laravel-school/gitcommit.md):
   ```bash
   git add .
   git commit -m "feat(<module>): <clear description of changes>"
   git push -u origin main
   ```

---

## 3. Strict File Ownership Boundaries

| Role | Worktree Path | Active Branch | File Ownership | Prohibited Edits |
| :--- | :--- | :--- | :--- | :--- |
| **Backend Engineer** | `C:/laragon/www/EduCore-backend` | `agent/backend` | `app/`, `database/`, `routes/`, `tests/` | Never edit `resources/views/` or client assets. |
| **Frontend Engineer** | `C:/laragon/www/EduCore-frontend` | `agent/frontend` | `resources/views/`, `public/assets/` | Never edit backend PHP models, migrations, or DB. |
| **Team Lead** | Root / `EduCore-lead` | `main` | Orchestration, Contracts, Specs, Gatekeeper | Merges reviewed branches, runs test gates, pushes. |

---

## 4. Key Reference Documents
- **Frontend Refactoring Framework**: [ui-refactor.md](file:///c:/laragon/www/laravel-school/ui-refactor.md) (7 master UI recipes)
- **UI/UX Mandatory Guidelines**: [UI-Mandatory.md](file:///c:/laragon/www/laravel-school/UI-Mandatory.md) (zero placeholder buttons, responsive layout)
- **Git Commit Standards**: [gitcommit.md](file:///c:/laragon/www/laravel-school/gitcommit.md) (Conventional Commits & push flow)
- **Orchestration Manual**: [scripts/user-manual.md](file:///c:/laragon/www/laravel-school/scripts/user-manual.md)
