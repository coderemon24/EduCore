# Agentix (agx) â€” Universal Multi-Agent User Manual
> **Production-Grade AI Engineering & Design Orchestration Across Isolated Git Worktrees**

**Agentix** (CLI: **`agx`**) is an autonomous multi-agent orchestration system for software engineering teams. Inspired by OpenSpec (`opsx`), `agx` coordinates 5 specialized AI agents working concurrently in isolated Git worktrees with staged design gates, automated Playwright visual testing, and zero file collisions.

---

## 1. Quick Overview: How Agentix Works

Instead of one AI trying to do everything and generating generic templates or colliding Git merges, **Agentix** splits the work across 5 dedicated roles:

```
                                  AGENTIX ARCHITECTURE
                             â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
                             â”‚    Team Lead (Main Worktree)  â”‚
                             â”‚   Orchestrator & Gatekeeper   â”‚
                             â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                                             â”‚
               â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¼â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
               â”‚                             â”‚                             â”‚
               â–¼                             â–¼                             â–¼
  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”  â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
  â”‚     Design Engineer      â”‚  â”‚     Backend Engineer     â”‚  â”‚    Frontend Engineer     â”‚
  â”‚ UX Discovery, Tokens,    â”‚  â”‚ Endpoints, DB Opt,       â”‚  â”‚ Components, Views, CSS,  â”‚
  â”‚ Wireframes, Direction    â”‚  â”‚ Adversarial Tests        â”‚  â”‚ 7 Master UI Recipes      â”‚
  â”‚ (Root Workspace)         â”‚  â”‚ (../<project>-backend)   â”‚  â”‚ (../<project>-frontend)  â”‚
  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜  â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
               â”‚                             â”‚                             â”‚
               â”‚ Design Specs & Tokens       â”‚ API Endpoints & Logic       â”‚ Rendered Markup & Styles
               â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¼â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                                             â”‚
                                             â–¼
                                â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
                                â”‚   Visual QA Specialist   â”‚
                                â”‚ Playwright Multi-Device  â”‚
                                â”‚ Screenshot & Slop Audit  â”‚
                                â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”¬â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
                                             â”‚
                                             â–¼
                                â”Œâ”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
                                â”‚   Team Lead Integration  â”‚
                                â”‚ Merge, Archive, Git Push â”‚
                                â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜
```

---

## 2. Copy & Setup in ANY Workspace (In 1 Minute)

Agentix is 100% portable. It contains **no hardcoded paths** and **no project branding**.

### Step 1: Copy Agentix Assets
Copy these files and folders into your new repository root:
```
your-new-project/
â”œâ”€â”€ .agents/              # Agent prompts, 11 design skills, 16 templates
â”œâ”€â”€ scripts/              # agx.py engine (backward-compatible with orchestrator.py)
â”œâ”€â”€ tests/visual-qa/      # Playwright browser screenshot capture harness
â”œâ”€â”€ agx.cmd / agx         # Native CLI launchers (Windows .cmd & Linux/Mac bash)
â”œâ”€â”€ AGENTS.md             # Master operating guidelines
â””â”€â”€ USER_MANUAL.md        # This operating manual
```

### Step 2: Install Visual QA Engine
```bash
npm install -D @playwright/test playwright
npx playwright install chromium
```

### Step 3: Run the Agentix Initializer
In your project root, run:
```bash
agx init
```
What `agx init` does automatically:
1. Detects your project directory name dynamically.
2. Creates the 3 Git branches: `agent/backend`, `agent/frontend`, `orchestration/lead`.
3. Creates dedicated Git worktrees alongside your project folder (`../<project>-backend`, `../<project>-frontend`, `../<project>-lead`).
4. Generates `.orchestrator.json` for custom overrides.

---

## 3. Fast Command Cheat Sheet (`agx` vs `opsx`)

Agentix commands are short, memorable, and support single-letter aliases:

| What You Want To Do | Full Command | Shorthand Alias | What It Does |
| :--- | :--- | :--- | :--- |
| **Check Team & Worktree Status** | `agx status` | `agx st` | Shows branches, worktree directories, and uncommitted git changes. |
| **Initialize Workspace** | `agx init` | â€” | Automatically sets up Git branches and worktrees in 3 seconds. |
| **Sync All Worktrees** | `agx sync` | â€” | Merges latest `main` into all agent worktree branches. |
| **Run Parallel Workers** | `agx run-parallel -b "..." -f "..."` | `agx run -b "..." -f "..."` | Launches Backend and Frontend engineers simultaneously in their isolated worktrees. |
| **Review Worker PR Diffs** | `agx review` | `agx diff` | Shows exact git diffs of `agent/backend` and `agent/frontend` against `main`. |
| **Run Automated Tests** | gx test -f <filter> | gx t -f <filter> | Executes PHPUnit/test suite with optional filter. |
| **Orchestrator Self-Test** | gx self-test | gx test --self | Executes full 26-test hermetic test suite (Unit, Worktrees, Pipeline Simulation & Visual QA). |
| **Export Production Bundle** | gx export [-o <path>] [--dir] | gx bundle / gx pack | Strips all AI agent/orchestration files and creates clean production zip or directory. |
| **Run Playwright Visual QA** | `agx visual-qa -u <URL>` | `agx vqa -u <URL>` | Launches Playwright Chromium and captures Mobile (375px), Tablet (768px), and Desktop (1440px) screenshots. |
| **Integrate & Merge** | `agx integrate -f <filter>` | `agx merge -f <filter>` | Merges verified worker branches into `main` after test gate verification. |
| **Push to GitHub** | `agx push -m "<commit message>"` | `agx p -m "<msg>"` | Formulates Conventional Commit and pushes to `origin main`. |
| **Single-Command Super Flow** | `agx pipeline -c <name> -p "<prompt>"` | `agx auto -c <name> -p "<prompt>"` | Autonomous end-to-end pipeline: sync â†’ parallel code â†’ PR review â†’ tests â†’ visual QA â†’ merge â†’ push. |
| **Dispatch Targeted Fix** | `agx dispatch -a <agent> -p "<prompt>"` | `agx d -a <agent> -p "<prompt>"` | Dispatches corrective prompt to a specific agent in its worktree. |

---

## 4. The 5 Specialized Agents

| Agent Name | Active Workspace / Branch | File Ownership | Prohibited Actions |
| :--- | :--- | :--- | :--- |
| **`team-lead`** | Root & Lead worktree (`main`) | OpenSpec specs, contracts, integration | Never writes code directly without PR review. |
| **`design-engineer`** | Root workspace (`main`) | `.agents/templates/`, tokens, wireframes | Never touches backend server code or database tables. |
| **`backend-engineer`** | `../<project>-backend` (`agent/backend`) | `app/`, `database/`, `routes/`, `tests/` | Never touches frontend views, HTML, or CSS. |
| **`frontend-engineer`** | `../<project>-frontend` (`agent/frontend`) | `resources/views/`, `public/assets/` | Never touches backend models, DB, or routes. |
| **`visual-qa`** | Root workspace (`main`) | `tests/visual-qa/`, screenshots, reports | Never touches application business logic. |

---

## 5. The 6 Staged Design Approval Gates

To avoid ugly "AI slop" (clichÃ© purple gradients, generic bento grids, and floating glass cards), Agentix enforces 6 gates:

```
   Gate 1: Discovery Approval
   â”œâ”€â”€ PROJECT_BRIEF.md
   â”œâ”€â”€ UX_RESEARCH.md
   â”œâ”€â”€ USER_PERSONAS.md
   â””â”€â”€ INFORMATION_ARCHITECTURE.md
              â”‚
              â–¼
   Gate 2: Visual Direction Approval
   â”œâ”€â”€ DESIGN_DIRECTION.md
   â”œâ”€â”€ DESIGN_REFERENCES.md
   â””â”€â”€ VISUAL_MOODBOARD.md
              â”‚
              â–¼
   Gate 3: Design System Approval
   â”œâ”€â”€ DESIGN_SYSTEM.md
   â”œâ”€â”€ DESIGN_TOKENS.md
   â”œâ”€â”€ design-tokens.css
   â””â”€â”€ COMPONENT_GUIDELINES.md
              â”‚
              â–¼
   Gate 4: Wireframe Approval
   â””â”€â”€ WIREFRAMES.md (Asymmetric 60/40 layouts & Mobile Reflow)
              â”‚
              â–¼
   Gate 5: Implementation Review
   â”œâ”€â”€ Backend PR (Zero N+1 queries, adversarial edge tests)
   â””â”€â”€ Frontend PR (7 UI recipes, responsive reflow, zero dead buttons)
              â”‚
              â–¼
   Gate 6: Visual QA Approval
   â”œâ”€â”€ Multi-viewport screenshots (375px, 768px, 1440px)
   â”œâ”€â”€ DESIGN_REVIEW.md
   â””â”€â”€ VISUAL_QA_REPORT.md
```

---

## 6. Daily Workflows: Examples

### Workflow 1: The Step-by-Step Staged Flow (Full Quality Control)

#### 1. UX Discovery & Design Tokens (Gates 1â€“4)
```bash
agy --agent design-engineer -p "Conduct UX research and generate DESIGN_TOKENS.md and WIREFRAMES.md for student report card download module."
```

#### 2. Dispatch Parallel Coding (Gate 5)
```bash
agx run -b "Build ReportCardController, PDF generation service, and adversarial unit tests." \
        -f "Build report card index and download views in resources/views/. Adopt design-tokens.css and the 7 UI refactoring recipes."
```

#### 3. Review Diffs & Run Tests
```bash
agx diff
agx t -f ReportCard
```

#### 4. Audit Visuals in Browser (Gate 6)
```bash
agx vqa -u http://127.0.0.1:8000/report-cards
```
*Inspect screenshots in `tests/visual-qa/screenshots/`.*  
If minor styling is needed, send a quick fix:
```bash
agx d -a frontend-engineer -p "Fix: Add px-4 py-2 to mobile download button for 48px touch target."
```

#### 5. Merge & Push
```bash
agx merge -f ReportCard
agx p -m "feat(report-cards): implement report card download with verified tests and visual QA"
```

---

### Workflow 2: The Autonomous Super Flow (`agx auto`)
When requirements are clear and you want the entire lifecycle automated:
```bash
agx auto -c class-timetable \
         -p "Implement class routine timetable with day/period filter and PDF download button." \
         -f Routine \
         -u http://127.0.0.1:8000/routine
```
This runs:
1. `agx sync` (pre-flight worktree update)
2. OpenSpec change setup
3. Parallel backend & frontend coding in isolated worktrees
4. Git PR diff inspection
5. PHPUnit / test suite gate
6. Playwright multi-viewport screenshot capture (Gate 6)
7. Git branch merge into `main`
8. Git push to GitHub

---

## 7. Global CLI Setup (Run `agx` from ANY folder)

To run `agx` from any terminal window without `./` or `python`:
- On **Windows**: `agx.cmd` is already linked in `%APPDATA%\npm\agx.cmd` (in your PATH).
- On **Mac/Linux**: Symlink `agx` to `/usr/local/bin/agx`:
  ```bash
  sudo ln -s $(pwd)/agx /usr/local/bin/agx
  ```

Now, anywhere on your machine, simply type:
```bash
agx st
agx sync
agx run -b "..." -f "..."
agx vqa -u http://localhost:8000
```
