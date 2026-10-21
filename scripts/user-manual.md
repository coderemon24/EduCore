# EduCore Multi-Agent Orchestration User Manual

This document outlines the architecture, roles, and step-by-step workflow for orchestrating specialized AI engineering agents across isolated Git worktrees in the **EduCore (Laravel School)** platform.

---

## 1. System Architecture & Team Overview

The multi-agent system uses isolated **Git Worktrees** to eliminate file collisions and merge conflicts when multiple agents build features simultaneously.

```
                         ┌─────────────────────────────┐
                         │      User / Product Owner   │
                         └──────────────┬──────────────┘
                                        │ User Request
                                        ▼
                         ┌─────────────────────────────┐
                         │     Team Lead (Main Repo)   │
                         │   C:/laragon/www/laravel-   │
                         │   school (branch: main)     │
                         └──────┬───────────────┬──────┘
             Phase 1: Contracts │               │ Phase 1: Contracts
             & Backend Prompt   │               │ & Frontend Prompt
                                ▼               ▼
     ┌─────────────────────────────┐         ┌─────────────────────────────┐
     │      Backend Engineer       │         │      Frontend Engineer      │
     │  C:/laragon/www/EduCore-    │         │  C:/laragon/www/EduCore-    │
     │  backend                    │         │  frontend                   │
     │  (branch: agent/backend)    │         │  (branch: agent/frontend)   │
     └──────────────┬──────────────┘         └──────────────┬──────────────┘
                    │                                       │
                    │ Phase 2: Autonomous Parallel Coding   │
                    │ (Subprocess execution via agy CLI)    │
                    ▼                                       ▼
     ┌─────────────────────────────┐         ┌─────────────────────────────┐
     │   Backend Worktree Commits  │         │   Frontend Worktree Commits │
     │  - Models, Migrations       │         │  - Blade Templates          │
     │  - Controllers & Requests   │         │  - CSS & Tailwind Classes   │
     │  - Unit & Feature Tests     │         │  - Client-side Interactions │
     └──────────────┬──────────────┘         └──────────────┬──────────────┘
                    │                                       │
                    └───────────────────┬───────────────────┘
                                        ▼
                         ┌─────────────────────────────┐
                         │   Phase 3: Diff Review      │
                         │   & Automated Test Gate     │
                         │   (Team Lead Validation)    │
                         └──────────────┬──────────────┘
                                        │ Pass Gate
                                        ▼
                         ┌─────────────────────────────┐
                         │    Phase 4: Integration     │
                         │    Merged into main branch  │
                         └─────────────────────────────┘
```

---

## 2. Team Roles & File Ownership Matrix

To prevent agents from modifying each other's files, each role has strict file ownership:

| Role | Worktree Path | Active Branch | File Ownership | Key Responsibilities |
| :--- | :--- | :--- | :--- | :--- |
| **Team Lead** | `C:\laragon\www\laravel-school` | `main` / `orchestration/lead` | Architecture, Specs, Orchestrator | Task breakdown, contract definition, diff inspection, gatekeeper, final integration. |
| **Backend Engineer** | `C:\laragon\www\EduCore-backend` | `agent/backend` | `app/`, `database/`, `routes/`, `tests/` | Migrations, Eloquent models, validation requests, API/web controllers, backend tests. |
| **Frontend Engineer** | `C:\laragon\www\EduCore-frontend` | `agent/frontend` | `resources/views/`, `public/assets/` | Blade templates, Tailwind CSS, responsive layouts, accessibility, UI micro-interactions. |

---

## 3. End-to-End Workflow: Step-by-Step

When you ask to build a full-stack feature (requiring both backend and frontend), the workflow executes in four distinct phases:

### Phase 1: Team Lead (Architecture & Contract Definition)
Before writing code, the Team Lead creates the shared contract:
1. **Route Names & HTTP Verbs**: e.g. `GET /admission`, `POST /admission`, `route('frontend.admission.store')`.
2. **View Parameter Contract**: Variable names passed from controller to view (e.g. `$academicClasses`, `$admissionSettings`).
3. **Form Request Fields**: Required input names, validation rules (e.g. `student_name`, `birth_date`, `guardian_phone`).
4. **Task Decomposition**: Writes tailored prompt instructions for both workers.

### Phase 2: Parallel Dispatch (Orchestrator Execution)
The Lead triggers the Python Orchestrator:
```bash
python scripts/orchestrator.py run-parallel \
  --backend "<Prompt with exact DB schemas, validation, controller logic, and test cases>" \
  --frontend "<Prompt with exact Blade paths, UI standards, responsive design, and contract variables>"
```
- Both agents wake up simultaneously in their isolated worktrees.
- Backend writes migrations, models, controllers, and tests in `EduCore-backend`.
- Frontend writes Blade views, components, and CSS in `EduCore-frontend`.
- Zero file locking, zero git conflicts.

### Phase 3: Review & Automated Test Gate
Once workers finish:
1. **Diff Inspection**:
   ```bash
   python scripts/orchestrator.py review
   ```
   The Lead inspects code changes in `agent/backend` and `agent/frontend` against `main`.
2. **Test Execution**:
   ```bash
   python scripts/orchestrator.py test --filter=YourFeatureTest
   ```
   Ensures all automated assertions pass without errors or regressions.

### Phase 4: Integration & Deployment
1. Merges `agent/backend` into `main` with a fast-forward/non-fast-forward merge.
2. Merges `agent/frontend` into `main`.
3. Runs the full test suite gate.
4. Auto-commits and pushes to `origin/main` following conventional commit guidelines (`feat(...)`).

---

## 4. Orchestrator CLI Command Reference

All orchestration actions are controlled via [scripts/orchestrator.py](file:///c:/laragon/www/laravel-school/scripts/orchestrator.py):

### 1. Check Team & Worktree Status
```bash
python scripts/orchestrator.py status
```
*Checks if all worktrees exist, current git branch, and whether changes are uncommitted.*

### 2. Synchronize Worktrees with Main
```bash
python scripts/orchestrator.py sync
```
*Merges the latest changes from `main` into all agent worktrees before starting new tasks.*

### 3. Run Concurrent Workers (Parallel Dispatch)
```bash
python scripts/orchestrator.py run-parallel \
  --backend "Your detailed backend instruction" \
  --frontend "Your detailed frontend instruction"
```
*Spawns asynchronous background processes for both agents simultaneously.*

### 4. Run Single Worker (Targeted Dispatch)
```bash
# Backend only:
python scripts/orchestrator.py dispatch --agent backend-engineer --prompt "Create StudentPromotionService"

# Frontend only:
python scripts/orchestrator.py dispatch --agent frontend-engineer --prompt "Improve notice board mobile padding"
```

### 5. Review Diff
```bash
python scripts/orchestrator.py review
```
*Shows git diff summary between agent branches and the default branch.*

### 6. Run Automated Tests
```bash
python scripts/orchestrator.py test --filter=NoticeBoardTest
```
*Executes the Laravel test suite gate.*

### 7. Integrate Branches into Main
```bash
python scripts/orchestrator.py integrate
```
*Safely merges reviewed worker branches into `main` and verifies the test suite.*

---

## 5. Walkthrough Example: Building an "Online Admission Form"

Suppose you tell the Team Lead:
> *"Build an Online Admission application feature where students can submit an admission request."*

Here is the exact command sequence:

### Step 1: Pre-flight Sync
```bash
python scripts/orchestrator.py sync
```

### Step 2: Parallel Worker Dispatch
```bash
python scripts/orchestrator.py run-parallel \
  --backend "Create AdmissionApplication model with migration (student_name, dob, class_id, guardian_phone, status). Create StoreAdmissionRequest form request with validation. Create AdmissionController with index and store methods. Write Feature test in tests/Feature/AdmissionTest.php." \
  --frontend "Create resources/views/frontend/admission/index.blade.php following UI-Mandatory.md. Form must submit to route('frontend.admission.store') with fields: student_name, dob, class_id, guardian_phone. Include validation error alerts, loading state, and responsive card container."
```

### Step 3: Review Code
```bash
python scripts/orchestrator.py review
```

### Step 4: Run Tests
```bash
python scripts/orchestrator.py test --filter=AdmissionTest
```

### Step 5: Integrate & Push
```bash
python scripts/orchestrator.py integrate
git push -u origin main
```

---

## 6. Safety & Best Practices

1. **Strict File Boundaries**: Never instruct the backend engineer to write Blade templates, and never instruct the frontend engineer to alter database schemas.
2. **Clean Worktrees**: Always ensure worktrees are clean before running `sync` or `run-parallel`.
3. **Idempotent Seeds**: Database seeders must use `updateOrCreate` to allow repeated execution without unique constraint errors.
4. **Continuous Test Verification**: Run `php artisan test` after every integration step to catch regressions immediately.
