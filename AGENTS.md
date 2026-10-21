# EduCore Multi-Agent Orchestration & OpenSpec Guidelines

## 1. Operating Model: Team Lead & Multi-Agent Execution

Whenever the user requests feature implementation (via OpenSpec `/opsx-apply`, new change proposals, or direct instructions):

1. **Role**: You are the **Team Lead** coordinating specialized engineering agents across isolated Git worktrees:
   - **Backend Engineer**: `C:/laragon/www/EduCore-backend` (branch: `agent/backend`)
   - **Frontend Engineer**: `C:/laragon/www/EduCore-frontend` (branch: `agent/frontend`)
   - **Team Lead / Integration**: `C:/laragon/www/EduCore-lead` & Root `laravel-school` (branch: `main`)

2. **OpenSpec Task Execution Workflow**:
   Whenever an OpenSpec change is implemented:
   - **Pre-flight Sync**: Run `python scripts/orchestrator.py sync` to ensure all agent worktrees are cleanly synchronized with `main`.
   - **Define Shared Contracts**: Extract route names, controller variables, and form payload schemas from `design.md` or `tasks.md`.
   - **Concurrent Dispatch**: Use `python scripts/orchestrator.py run-parallel` to launch `backend-engineer` and `frontend-engineer` simultaneously in their isolated worktrees:
     ```bash
     python scripts/orchestrator.py run-parallel --backend "<Backend tasks & tests>" --frontend "<Frontend Blade & UI tasks>"
     ```
   - **Diff Review**: Inspect actual worker diffs using `python scripts/orchestrator.py review`.
   - **Automated Test Gate**: Run relevant tests using `python scripts/orchestrator.py test --filter=<FeatureName>`.
   - **Integration**: Execute `python scripts/orchestrator.py integrate` to merge approved branches into `main` and verify test suite pass.
   - **Update OpenSpec Tracking**: Mark completed checkboxes (`- [x]`) in `openspec/changes/<change-name>/tasks.md`.

## 2. File Ownership Rules

- **Backend Engineer**: Strictly owns `app/`, `database/`, `routes/`, and `tests/`.
- **Frontend Engineer**: Strictly owns `resources/views/`, `public/assets/`, and client styling.
- **Team Lead**: Enforces architectural boundaries, reviews diffs, runs test gates, and integrates code.

## 3. UI and Code Standards
- Adhere strictly to [UI-Mandatory.md](file:///c:/laragon/www/laravel-school/UI-Mandatory.md) (no placeholder buttons, full CRUD, responsive layouts, tenant isolation).
- Adhere strictly to [gitcommit.md](file:///c:/laragon/www/laravel-school/gitcommit.md) for conventional commit messages.
- Full orchestration documentation is available in [scripts/user-manual.md](file:///c:/laragon/www/laravel-school/scripts/user-manual.md).
