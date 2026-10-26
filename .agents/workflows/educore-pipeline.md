---
description: "Autonomous End-to-End Multi-Agent Feature Development (Explore -> Plan -> Propose -> Parallel Workers -> PR Review -> Test Gate -> Integration -> Archive -> Git Push)"
---

# EduCore Autonomous Feature Pipeline

Use this workflow to develop, test, refine, review, integrate, and deploy any feature or module across isolated Git worktrees with a single prompt.

---

## Workflow Steps

### Step 1: Ingest Feature Requirements
- Receive the feature requirements from the user (from `/opsx-explore`, detailed prompt, or change specifications).
- Identify the target capability and derive a kebab-case change name (e.g., `routine-management`, `admission-portal`, `class-syllabus`).

### Step 2: OpenSpec Change Setup (Strictly Local)
1. Verify OpenSpec status or initialize the change:
   ```bash
   openspec.cmd new change <change-name>
   ```
2. Populate or verify planning artifacts in `openspec/changes/<change-name>/`:
   - `proposal.md`: Context, motivation, user value.
   - `specs/<capability>/spec.md`: Delta requirements and acceptance criteria.
   - `design.md`: Technical architecture and database schema.
   - `tasks.md`: Decoupled backend and frontend task checklists.
3. Check apply instructions:
   ```bash
   openspec.cmd instructions apply --change <change-name> --json
   ```
*(Note: OpenSpec is strictly a local planning tool. NEVER stage, commit, or push OpenSpec files to GitHub).*

### Step 3: Establish Shared Contracts
Extract and verify shared interface contracts:
- **Routes**: HTTP method, URI, named route (e.g., `route('frontend.routine.index')`).
- **Controller-to-View Variables**: Exact variable names, collections, pagination keys.
- **Form Payloads**: Field names, validation rules, error message keys.
- **Database Schema**: Table names, foreign key constraints, indexes.

### Step 4: Pre-flight Worktree Sync
Ensure all agent worktrees are up-to-date with `main`:
```bash
python scripts/orchestrator.py sync
```

### Step 5: Concurrent Worker Dispatch
Launch the Backend and Frontend engineers simultaneously in their isolated worktrees:
```bash
python scripts/orchestrator.py run-parallel \
  --backend "<Enriched Backend prompt with DB optimization & adversarial tests>" \
  --frontend "<Enriched Frontend prompt with ui-refactor.md & page consistency>"
```

#### Frontend Engineer Mandatory Protocols:
- **Design Consistency**: Match styling, card patterns, typography, and palette of existing pages (`classes.blade.php`, `about/index.blade.php`, `routine.blade.php`, `teachers/index.blade.php`).
- **UI Refactoring Framework (`ui-refactor.md`)**:
  - Recipe 1 (Sharpen Message): Headline focal span `<span class="text-[#005030] font-bold">`.
  - Recipe 2 (Break Symmetry): Asymmetric 60/40 or 65/35 grid with floating anchor badges.
  - Recipe 3 (Build Argument): Uniform `.section-header-block` and `.home-section-divider`.
  - Recipe 4 (Pick the Winner): 60% flagship card dominance; quiet secondary stack.
  - Recipe 5 (Scale the Type): 4-tier scale (Headline text-3xl to 5xl, Card text-lg to 2xl, Body text-sm to base with leading-relaxed, Kicker text-xs).
  - Recipe 6 (Give Color Meaning): Dark green `#005030`, warm amber `amber-400` strictly for primary CTA/Admission, sky blue for notices, calm neutral card base.
  - Recipe 7 (Add a Signature): Section kicker with pulsing dot (`.section-kicker-dot`), hairline dividers, and micro-interactions.
- **UI-Mandatory Guidelines**: Zero placeholder buttons, full CRUD, responsive layouts.
- **Single Atomic Commit Policy**: NEVER micro-commit after minor file edits or styling attempts. Commit EXACTLY ONCE at the very end when the entire page/view is fully completed and responsive:
  `git commit -m "feat(frontend): <active verb title>" -m "- Views: <blade files>\n- Design: <UI recipes & tokens>\n- Verification: <responsive proof>"`

#### Backend Engineer Mandatory Protocols:
- **Framework Best Practices**: Follow `laravel-expert`, `laravel-best-practices`, and `laravel-security-audit`.
- **Database Optimization (Mandatory)**:
  - **Zero N+1 Queries**: Eager-load relations (`with()`, `loadMissing()`). No lazy loading inside loops.
  - **Deadlock Prevention**: Short atomic `DB::transaction()` blocks, consistent record lock order.
  - **Proper Indexing**: Add indexes for foreign keys, tenant column (`school_id`), search/filter columns.
  - **Data Consistency**: Foreign key cascades/restricts and atomic counters.
- **Automated & Adversarial Testing ("Break it to make it unbreakable")**:
  - Write Unit tests (`tests/Unit/`) and Feature tests (`tests/Feature/`).
  - Actively test edge cases to break the feature: invalid types, nulls, tenant boundary violations, empty records, and duplicate submissions.
  - If broken, diagnose, fix, and re-test until 100% green.
- **Single Atomic Commit Policy**: NEVER micro-commit on incremental edits, debugging, or partial code. Commit EXACTLY ONCE at the end when all models, migrations, controllers, and tests are complete and 100% passing:
  `git commit -m "feat(backend): <active verb title>" -m "- Components: <classes & migrations>\n- Database: <indexes & eager loading>\n- Verification: <adversarial tests passed>"`

### Step 6: Team Lead PR Review
Inspect worker diffs against `main`:
```bash
python scripts/orchestrator.py review
```

### Step 7: Automated Test Gate & Edge Case Verification
Run the Laravel automated test suite:
```bash
python scripts/orchestrator.py test --filter=<FeatureName>
```

### Step 8: Iterative Feedback Loop
If any issue, broken test, N+1 query, or UI inconsistency is detected:
1. Dispatch corrective prompt to that worker:
   ```bash
   python scripts/orchestrator.py dispatch --agent <agent-name> --prompt "Fix: <details of issue>"
   ```
2. Worker fixes the issue in its worktree, re-runs tests, and re-commits.
3. Team Lead re-reviews.

### Step 9: Integration
Merge verified worker branches into `main`:
```bash
python scripts/orchestrator.py integrate --filter=<FeatureName>
```

### Step 10: OpenSpec Local Archive (Never Commit to Git)
Archive the completed change locally:
```bash
openspec.cmd archive <change-name> --yes
```
*(Note: Archiving moves the change files locally to `openspec/changes/archive/`. Do NOT stage, commit, or push OpenSpec archive changes to GitHub. Commits must strictly contain feature code).*

### Step 11: Mandatory User Review Gate
- The Team Lead **MUST NEVER** push directly to GitHub without explicit user permission.
- Present a detailed summary of all implemented features, views, DB changes, and test results to the user.
- Ask the user to review the work:
  > *"Feature `<change-name>` is built, tested, and integrated locally on `main`. Please inspect and review the changes. When you are ready and confirm, give me the command to push to GitHub main."*
- **Pause and wait for user's explicit command/confirmation.**

### Step 12: GitHub Commit & Push (Only Upon User Command)
- Once the user confirms/commands to push:
  ```bash
  python scripts/orchestrator.py push --message "feat(<change-name>): implement verified module"
  ```
  or
  ```bash
  git add .
  git commit -m "feat(<change-name>): implement end-to-end module with verified UI and tests"
  git push -u origin main
  ```
