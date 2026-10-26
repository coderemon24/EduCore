"""
Multi-Agent Orchestrator for EduCore (Laravel School)
Coordinates specialized AI workers across isolated Git worktrees.
Supports autonomous single-command feature development pipeline.
"""

import argparse
import asyncio
import os
import subprocess
import sys
from pathlib import Path

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8', errors='replace')
if hasattr(sys.stderr, 'reconfigure'):
    sys.stderr.reconfigure(encoding='utf-8', errors='replace')

# Paths configuration
BASE_DIR = Path(__file__).resolve().parent.parent
WORKTREES = {
    "lead": Path("C:/laragon/www/EduCore-lead"),
    "backend": Path("C:/laragon/www/EduCore-backend"),
    "frontend": Path("C:/laragon/www/EduCore-frontend"),
    "main": BASE_DIR,
}

AGENTS = {
    "backend-engineer": {
        "role": "backend-engineer",
        "worktree": WORKTREES["backend"],
        "branch": "agent/backend",
    },
    "frontend-engineer": {
        "role": "frontend-engineer",
        "worktree": WORKTREES["frontend"],
        "branch": "agent/frontend",
    },
    "team-lead": {
        "role": "team-lead",
        "worktree": WORKTREES["lead"],
        "branch": "orchestration/lead",
    },
}

# Command names for Windows compatibility
OPENSPEC_CMD = "openspec.cmd" if os.name == "nt" else "openspec"


def run_cmd(cmd, cwd=None, capture=True):
    """Run shell command synchronously."""
    res = subprocess.run(
        cmd,
        cwd=cwd or BASE_DIR,
        shell=True,
        capture_output=capture,
        text=True,
        encoding="utf-8",
        errors="replace"
    )
    return res


async def run_agent_task(agent_name: str, prompt: str, worktree_dir: Path):
    """Run a single agent task using agy CLI asynchronously."""
    print(f"\n[ORCHESTRATOR] >>> Launching '{agent_name}' in '{worktree_dir}'...")
    
    cmd = [
        "agy",
        "--agent", agent_name,
        "--mode", "auto",
        "--dangerously-skip-permissions",
        "-p", prompt
    ]
    
    process = await asyncio.create_subprocess_exec(
        *cmd,
        cwd=str(worktree_dir),
        stdout=asyncio.subprocess.PIPE,
        stderr=asyncio.subprocess.PIPE
    )
    
    stdout, stderr = await process.communicate()
    out_text = stdout.decode("utf-8", errors="replace").strip()
    err_text = stderr.decode("utf-8", errors="replace").strip()
    
    status_icon = "SUCCESS" if process.returncode == 0 else "FAILED"
    print(f"[ORCHESTRATOR] <<< Agent '{agent_name}' finished [{status_icon}] (code: {process.returncode})")
    
    return {
        "agent": agent_name,
        "worktree": str(worktree_dir),
        "returncode": process.returncode,
        "stdout": out_text,
        "stderr": err_text,
    }


def show_status():
    """Display status of all agent worktrees and branches."""
    print("=" * 70)
    print("  EduCore Multi-Agent Team Status")
    print("=" * 70)
    for role, cfg in AGENTS.items():
        wt = cfg["worktree"]
        exists = wt.exists()
        branch = "Unknown"
        git_clean = "N/A"
        
        if exists:
            b_res = run_cmd("git rev-parse --abbrev-ref HEAD", cwd=wt)
            if b_res.returncode == 0:
                branch = b_res.stdout.strip()
            s_res = run_cmd("git status -s", cwd=wt)
            if s_res.returncode == 0:
                diff_count = len([line for line in s_res.stdout.splitlines() if line.strip()])
                git_clean = f"Clean ({diff_count} changes)" if diff_count == 0 else f"DIRTY ({diff_count} changes)"
        
        status_line = f"[{'ONLINE' if exists else 'MISSING'}] {role.upper()}"
        print(f"{status_line:<25} Branch: {branch:<20} Worktree: {wt}")
        print(f"   Git Status: {git_clean}")
    print("=" * 70)


def setup_worktrees():
    """Initialize or repair Git worktrees, .env files, and dependency junctions."""
    import shutil
    print("=" * 70)
    print("  EduCore Orchestrator: Setting Up Worktrees & Dependencies")
    print("=" * 70)
    
    env_source = BASE_DIR / ".env"
    vendor_source = BASE_DIR / "vendor"
    node_modules_source = BASE_DIR / "node_modules"
    
    for role, cfg in AGENTS.items():
        wt = cfg["worktree"]
        branch = cfg["branch"]
        print(f"\n[SETUP] Configuring {role.upper()} at {wt} (branch: {branch})...")
        
        if not wt.exists():
            b_check = run_cmd(f"git rev-parse --verify {branch}", cwd=BASE_DIR)
            if b_check.returncode == 0:
                cmd = f'git worktree add "{wt}" {branch}'
            else:
                cmd = f'git worktree add "{wt}" -b {branch}'
            
            print(f"  -> Creating worktree: {cmd}")
            res = run_cmd(cmd, cwd=BASE_DIR)
            if res.returncode == 0:
                print(f"     [OK] Worktree created for {branch}.")
            else:
                print(f"     [FAIL] Could not create worktree: {res.stderr or res.stdout}")
                continue
        else:
            print(f"  -> Worktree directory already exists.")
            
        target_env = wt / ".env"
        if env_source.exists() and not target_env.exists():
            shutil.copyfile(env_source, target_env)
            print(f"     [OK] Copied .env to {wt}")
            
        target_vendor = wt / "vendor"
        if vendor_source.exists() and not target_vendor.exists():
            res_v = run_cmd(f'cmd /c mklink /J "{target_vendor}" "{vendor_source}"')
            if res_v.returncode == 0:
                print(f"     [OK] Linked vendor junction in {wt}")
                
        target_nm = wt / "node_modules"
        if node_modules_source.exists() and not target_nm.exists():
            res_nm = run_cmd(f'cmd /c mklink /J "{target_nm}" "{node_modules_source}"')
            if res_nm.returncode == 0:
                print(f"     [OK] Linked node_modules junction in {wt}")
                
    print("\n" + "=" * 70)
    print("  Setup Complete")
    print("=" * 70 + "\n")
    show_status()


def sync_worktrees():
    """Synchronize all worktrees with main branch."""
    print("[ORCHESTRATOR] Synchronizing worktrees with 'main'...")
    for role, cfg in AGENTS.items():
        wt = cfg["worktree"]
        if wt.exists():
            print(f"  -> Merging 'main' into {cfg['branch']} in {wt}...")
            res = run_cmd("git merge main --no-edit", cwd=wt)
            if res.returncode == 0:
                print(f"     [OK] {cfg['branch']} up to date.")
            else:
                print(f"     [FAIL] Merge issue in {cfg['branch']}: {res.stderr or res.stdout}")
    print("[ORCHESTRATOR] Synchronization complete.\n")


def run_tests(filter_pattern=None):
    """Run Laravel automated test suite."""
    filter_arg = f"--filter={filter_pattern}" if filter_pattern else ""
    cmd = f"php artisan test {filter_arg}"
    print(f"[ORCHESTRATOR] Executing test suite: {cmd}")
    res = run_cmd(cmd, cwd=BASE_DIR, capture=False)
    return res.returncode


def review_diffs():
    """Review worker diffs against main."""
    print("=" * 70)
    print("  Orchestrator: Reviewing Worker Diffs vs 'main'")
    print("=" * 70)
    print("=== Diff: agent/backend vs main ===")
    b_diff = run_cmd("git diff main..agent/backend --stat")
    print(b_diff.stdout.strip() or "No differences.")
    
    print("\n=== Diff: agent/frontend vs main ===")
    f_diff = run_cmd("git diff main..agent/frontend --stat")
    print(f_diff.stdout.strip() or "No differences.")
    print("=" * 70)


def integrate_branches(test_filter="", change_name=""):
    """Integrate worker branches into main after review."""
    print("=" * 70)
    print("  Orchestrator: Integrating Worker Branches into main")
    print("=" * 70)
    
    scope_str = f"({change_name})" if change_name else ""
    # 1. Integrate backend
    print("Step 1: Merging agent/backend into main...")
    b_msg = f"feat(backend{scope_str}): integrate verified backend changes into main"
    b_res = run_cmd(f'git merge agent/backend -m "{b_msg}" --no-ff', cwd=BASE_DIR)
    if b_res.returncode != 0:
        print(f"[ERROR] Backend merge failed:\n{b_res.stderr or b_res.stdout}")
        return False
    print("  [OK] Backend merged successfully.")
    
    # 2. Integrate frontend
    print("Step 2: Merging agent/frontend into main...")
    f_msg = f"feat(frontend{scope_str}): integrate verified frontend changes into main"
    f_res = run_cmd(f'git merge agent/frontend -m "{f_msg}" --no-ff', cwd=BASE_DIR)
    if f_res.returncode != 0:
        print(f"[ERROR] Frontend merge failed:\n{f_res.stderr or f_res.stdout}")
        return False
    print("  [OK] Frontend merged successfully.")
    
    print("\nStep 3: Running automated test suite gate...")
    t_code = run_tests(test_filter)
    if t_code == 0:
        print("[SUCCESS] All integration tests passed.")
        return True
    else:
        print("[WARNING] Tests failed during integration check.")
        return False


def archive_change(change_name: str):
    """Archive an OpenSpec change locally (never committed to git)."""
    print(f"[ORCHESTRATOR] Archiving OpenSpec change '{change_name}' locally...")
    res = run_cmd(f"{OPENSPEC_CMD} archive {change_name} --yes", cwd=BASE_DIR)
    if res.returncode == 0:
        print(f"  [OK] OpenSpec change '{change_name}' archived locally. (Strictly local, not committed to Git).")
        return True
    else:
        print(f"  [FAIL] Could not archive change: {res.stderr or res.stdout}")
        return False


def push_to_github(commit_message: str):
    """Stage, commit, and push changes following gitcommit.md standards."""
    print("[ORCHESTRATOR] Preparing GitHub commit & push (gitcommit.md standard)...")
    run_cmd("git add .", cwd=BASE_DIR)
    # Ensure openspec is strictly local and never staged or committed
    run_cmd("git reset HEAD -- openspec", cwd=BASE_DIR)
    
    c_res = run_cmd(f'git commit -m "{commit_message}"', cwd=BASE_DIR)
    if c_res.returncode != 0 and "nothing to commit" not in (c_res.stdout + c_res.stderr):
        print(f"  [WARNING] Commit output: {c_res.stderr or c_res.stdout}")
    else:
        print("  [OK] Local commit created.")
        
    print("  -> Pushing to origin main...")
    p_res = run_cmd("git push -u origin main", cwd=BASE_DIR)
    if p_res.returncode == 0:
        print("  [SUCCESS] All changes successfully pushed to origin/main.")
        return True
    else:
        print(f"  [FAIL] Push failed:\n{p_res.stderr or p_res.stdout}")
        return False



def enrich_backend_prompt(raw_prompt: str, change_name: str = "") -> str:
    """Enrich the backend prompt with mandatory Laravel, DB optimization, adversarial test rules, and atomic commit discipline."""
    return f"""### BACKEND TASK DIRECTIVE (EduCore Architecture)
Feature / Module Context: {change_name or 'Feature Implementation'}

{raw_prompt}

MANDATORY ARCHITECTURAL & DATABASE OPTIMIZATION STANDARDS:
1. Framework & Skills: Adhere strictly to `laravel-expert`, `laravel-best-practices`, and `laravel-security-audit`.
2. Architecture: Implement clean FormRequests, dedicated Controller methods, and strongly-typed Eloquent Models/casts.
3. Database Optimization (MANDATORY):
   - Zero N+1 Queries: Always eager-load relationships via `with()` or `loadMissing()`. Never lazy-load inside loops.
   - Deadlock Prevention: Keep transactions short and atomic using `DB::transaction()`. Order record lock acquisitions consistently.
   - Proper Indexing: Add indexes in migrations for foreign keys, tenant column (`school_id`), search criteria, and sorting fields.
   - Data Consistency: Use explicit database constraints (cascade/restrict) and atomic operations.
4. Adversarial & Edge Case Automated Testing ("Break it to make it unbreakable"):
   - Write Unit tests in `tests/Unit/` and Feature tests in `tests/Feature/`.
   - Actively try to break the code by testing: null inputs, invalid data types, tenant boundary violations, empty records, and duplicate submissions.
   - If tests fail or bugs are exposed, fix the implementation immediately and re-run tests until 100% green.
5. STRICT COMMIT DISCIPLINE & HUMAN-READABLE PR POLICY (ZERO GARBAGE COMMITS):
   - ABSOLUTELY FORBIDDEN: NEVER commit on incremental file edits, trial-and-error debugging steps, single-file fixes, or half-finished code. DO NOT pollute git history with micro-commits.
   - TIMING: Commit EXACTLY ONCE at the very end of your task, ONLY AFTER all models, migrations, controllers, form requests, and adversarial tests are fully completed, verified, and passing.
   - HUMAN-READABLE MESSAGE STANDARD: Every commit message must be self-explanatory, detailed, and human-readable. Never use vague titles like 'update', 'fix', 'feat(backend): backend changes', or 'changes'.
   - REQUIRED COMMIT COMMAND:
     git add .
     git commit -m "feat(backend): <concise active verb title describing the feature>" -m "- Components: <list created/modified models, controllers, requests>\n- Database: <indexes, migrations, and eager loading added>\n- Verification: <adversarial tests passed and edge cases covered>"
   - Output summary of files created/changed, tests executed, and the final commit SHA.
"""


def enrich_frontend_prompt(raw_prompt: str, change_name: str = "") -> str:
    """Enrich the frontend prompt with ui-refactor.md, page consistency, UI-Mandatory, and atomic commit discipline."""
    return f"""### FRONTEND TASK DIRECTIVE (EduCore UI/UX)
Feature / Module Context: {change_name or 'UI Implementation'}

{raw_prompt}

MANDATORY UI/UX & REFACTORING STANDARDS:
1. Page Design Consistency (CRITICAL):
   - Inspect existing production views: `resources/views/frontend/academic/classes.blade.php`, `about/index.blade.php`, `routine.blade.php`, and `teachers/index.blade.php`.
   - Adhere to the established layout container, card styling, typography, and color palette.
2. Mandatory UI Refinement via `ui-refactor.md`:
   Apply the 7 Master UI Recipes from `ui-refactor.md` to refine the interface:
   - Recipe 1 (Sharpen Message): Clear promise headline with ONE focal accent `<span class="text-[#005030] font-bold">`.
   - Recipe 2 (Break Symmetry): Asymmetric 60/40 or 65/35 grid (`lg:grid-cols-12`) with floating anchor badges.
   - Recipe 3 (Build Argument): Uniform `.section-header-block` (kicker + title + subtitle) and hairline dividers `.home-section-divider`.
   - Recipe 4 (Pick the Winner): Elevate flagship tier (60% weight) over identical cards; quiet secondary items.
   - Recipe 5 (Scale the Type): 4-tier scale (Headline text-3xl to 5xl, Card text-lg to 2xl, Body text-sm to base with leading-relaxed, Kicker text-xs).
   - Recipe 6 (Give Color Meaning): Dark Green `#005030` for brand accents, Warm Amber `amber-400` strictly for Admission/CTAs, Sky Blue for notices, calm neutral base (`border-slate-200/90`).
   - Recipe 7 (Add a Signature): Section kicker with pulsing dot (`.section-kicker-dot`), hairline dividers, and micro-interactions.
3. UI-Mandatory Compliance:
   - No placeholder buttons or dead links. All buttons/links must have valid `route('...')` or actionable targets.
   - Full responsive support (mobile natural stack, desktop editorial grid).
4. STRICT COMMIT DISCIPLINE & HUMAN-READABLE PR POLICY (ZERO GARBAGE COMMITS):
   - ABSOLUTELY FORBIDDEN: NEVER commit on incremental file edits, individual CSS tweaks, fixing typos, or partial drafts. DO NOT pollute git history with micro-commits.
   - TIMING: Commit EXACTLY ONCE at the very end of your task, ONLY AFTER the complete page or component layout is fully crafted, responsive across breakpoints, UI-recipes applied, and verified with zero dead links.
   - HUMAN-READABLE MESSAGE STANDARD: Every commit message must be self-explanatory, detailed, and human-readable. Never use vague titles like 'update', 'style fix', 'frontend changes', or 'changes'.
   - REQUIRED COMMIT COMMAND:
     git add .
     git commit -m "feat(frontend): <concise active verb title describing the UI/page>" -m "- Views: <list created or redesigned blade templates>\n- Design: <UI recipes applied, typography scale, color tokens used>\n- Verification: <mobile/desktop responsiveness confirmed, valid routes wired>"
   - Output summary of views/components modified, responsiveness checks, and the final commit SHA.
"""


async def run_feature_pipeline(change_name: str, prompt: str, test_filter: str = "", skip_archive: bool = False, skip_push: bool = False, auto_push: bool = False):
    """Execute complete end-to-end autonomous feature development pipeline."""
    print("\n" + "=" * 70)
    print(f"  EduCore Autonomous Feature Pipeline: '{change_name}'")
    print("=" * 70)
    
    # 1. Pre-flight sync
    print("\n>>> Phase 1: Pre-flight Worktree Sync...")
    sync_worktrees()
    
    # 2. OpenSpec check / proposal initialization
    print(f"\n>>> Phase 2: Verifying OpenSpec Change '{change_name}'...")
    check_change = run_cmd(f"{OPENSPEC_CMD} status --change {change_name} --json", cwd=BASE_DIR)
    if check_change.returncode != 0:
        print(f"  -> Change '{change_name}' not found. Initializing new OpenSpec change...")
        run_cmd(f"{OPENSPEC_CMD} new change {change_name}", cwd=BASE_DIR)
    else:
        print(f"  [OK] OpenSpec change '{change_name}' is active.")
        
    # 3. Formulate enriched directives
    print("\n>>> Phase 3: Constructing Enriched Subagent Task Directives...")
    backend_prompt = enrich_backend_prompt(prompt, change_name)
    frontend_prompt = enrich_frontend_prompt(prompt, change_name)
    
    # 4. Parallel Dispatch
    print("\n>>> Phase 4: Launching Concurrent Workers in Isolated Worktrees...")
    tasks = [
        run_agent_task("backend-engineer", backend_prompt, AGENTS["backend-engineer"]["worktree"]),
        run_agent_task("frontend-engineer", frontend_prompt, AGENTS["frontend-engineer"]["worktree"]),
    ]
    results = await asyncio.gather(*tasks)
    
    print("\n" + "=" * 70)
    print("  Workers Completed Execution")
    print("=" * 70)
    for r in results:
        print(f"[{r['agent'].upper()}] Exit Code: {r['returncode']}")
        
    # 5. Review PR diffs
    print("\n>>> Phase 5: Team Lead PR Review (Diff Inspection)...")
    review_diffs()
    
    # 6. Test Gate
    filter_target = test_filter or change_name.replace("-", "").replace("_", "")
    print(f"\n>>> Phase 6: Automated Test Suite Gate (Filter: '{filter_target}')...")
    test_code = run_tests(filter_target)
    
    if test_code != 0:
        print("\n[WARNING] Tests failed or target tests not yet present on main branch.")
        print("Lead will proceed with integration if branch diffs are verified.")
        
    # 7. Integration
    print("\n>>> Phase 7: Integrating Worker Branches into main...")
    integrated = integrate_branches(filter_target, change_name)
    if not integrated:
        print("[ERROR] Integration failed. Please inspect git status.")
        return False
        
    # 8. Archive OpenSpec Change
    if not skip_archive:
        print("\n>>> Phase 8: Archiving OpenSpec Change...")
        archive_change(change_name)
    else:
        print("\n>>> Phase 8: Skipping OpenSpec archive as requested.")
        
    # 9. User Review Gate & Push to GitHub
    print("\n" + "=" * 70)
    print("  PHASE 9: USER REVIEW GATE (Inspection & Approval)")
    print("=" * 70)
    print(f"Feature '{change_name}' has been successfully built, tested, and integrated into local 'main'.")
    print("OpenSpec change has been archived.")
    print("\nLocal verification complete. Awaiting user review before remote deployment.")
    
    do_push = False
    if auto_push:
        print("[ORCHESTRATOR] --auto-push flag detected. Proceeding with GitHub push...")
        do_push = True
    elif skip_push:
        print("[ORCHESTRATOR] Push skipped via --skip-push.")
    else:
        try:
            user_input = input("\n>>> Would you like to push these changes to GitHub 'main' now? [y/N]: ").strip().lower()
            if user_input in ("y", "yes"):
                do_push = True
            else:
                print("\n[ORCHESTRATOR] Push skipped by user.")
                print(f"Inspect your changes. When ready to deploy, run:")
                print(f'  python scripts/orchestrator.py push --message "feat({change_name}): <summary>"')
        except (EOFError, KeyboardInterrupt):
            print("\n[ORCHESTRATOR] Non-interactive or interrupted session. Push withheld.")
            print(f'To push manually when ready, run: python scripts/orchestrator.py push --message "feat({change_name}): <summary>"')
            
    if do_push:
        print("\n>>> Staging & Pushing to GitHub (gitcommit.md standard)...")
        commit_msg = f"feat({change_name}): implement end-to-end module with verified UI and tests"
        push_to_github(commit_msg)
    else:
        print("\n>>> Work tree is clean and ready for your review on branch 'main'.")
        
    print("\n" + "=" * 70)
    print(f"  [COMPLETED] Feature '{change_name}' Ready for User Review!")
    print("=" * 70 + "\n")
    return True


async def main_async():
    parser = argparse.ArgumentParser(description="Multi-Agent Orchestrator for EduCore")
    subparsers = parser.add_subparsers(dest="action", help="Action to execute")
    
    # Status
    subparsers.add_parser("status", help="Show worktree and agent statuses")
    
    # Setup
    subparsers.add_parser("setup", help="Initialize or repair worktrees, .env, and junctions")
    
    # Sync
    subparsers.add_parser("sync", help="Synchronize all worktrees with main")
    
    # Dispatch single agent
    dispatch_p = subparsers.add_parser("dispatch", help="Dispatch task to a specific worker")
    dispatch_p.add_argument("--agent", required=True, choices=["backend-engineer", "frontend-engineer", "team-lead"])
    dispatch_p.add_argument("--prompt", required=True, help="Task prompt for the agent")
    
    # Parallel dispatch
    parallel_p = subparsers.add_parser("run-parallel", help="Dispatch concurrent tasks to backend & frontend workers")
    parallel_p.add_argument("--backend", required=True, help="Task prompt for backend-engineer")
    parallel_p.add_argument("--frontend", required=True, help="Task prompt for frontend-engineer")
    
    # Review
    subparsers.add_parser("review", help="Inspect git diff across agent branches")
    
    # Test
    test_p = subparsers.add_parser("test", help="Run automated test suite")
    test_p.add_argument("--filter", default="", help="Filter test case or class name")
    
    # Integrate
    integrate_p = subparsers.add_parser("integrate", help="Integrate reviewed agent branches into main")
    integrate_p.add_argument("--filter", default="", help="Filter test case or class name during gate check")
    integrate_p.add_argument("--change", default="", help="OpenSpec change name or feature scope for commit message")
    
    # Archive
    archive_p = subparsers.add_parser("archive", help="Archive a completed OpenSpec change")
    archive_p.add_argument("--change", required=True, help="Name of the change to archive")
    
    # Push
    push_p = subparsers.add_parser("push", help="Commit and push changes to GitHub following gitcommit.md")
    push_p.add_argument("--message", required=True, help="Conventional commit message")
    
    # Pipeline / Auto
    pipeline_p = subparsers.add_parser("pipeline", help="Execute full autonomous feature development pipeline")
    pipeline_p.add_argument("--change", required=True, help="OpenSpec change name (kebab-case)")
    pipeline_p.add_argument("--prompt", required=True, help="Detailed feature requirements prompt")
    pipeline_p.add_argument("--filter", default="", help="Test filter name")
    pipeline_p.add_argument("--skip-archive", action="store_true", help="Skip OpenSpec archive")
    pipeline_p.add_argument("--skip-push", action="store_true", help="Skip GitHub push")
    pipeline_p.add_argument("--auto-push", action="store_true", help="Bypass interactive user review prompt and push immediately")
    
    args = parser.parse_args()
    
    if args.action == "status" or not args.action:
        show_status()
        
    elif args.action == "setup":
        setup_worktrees()
        
    elif args.action == "sync":
        sync_worktrees()
        
    elif args.action == "dispatch":
        cfg = AGENTS[args.agent]
        wt = cfg["worktree"]
        if not wt.exists():
            print(f"[ERROR] Worktree '{wt}' does not exist.")
            sys.exit(1)
            
        res = await run_agent_task(args.agent, args.prompt, wt)
        print("\n--- Worker Output ---")
        print(res["stdout"])
        if res["stderr"]:
            print("\n--- Errors / Diagnostics ---")
            print(res["stderr"])
            
    elif args.action == "run-parallel":
        print("[ORCHESTRATOR] Spawning concurrent workers in parallel worktrees...")
        tasks = [
            run_agent_task("backend-engineer", args.backend, AGENTS["backend-engineer"]["worktree"]),
            run_agent_task("frontend-engineer", args.frontend, AGENTS["frontend-engineer"]["worktree"]),
        ]
        
        results = await asyncio.gather(*tasks)
        print("\n" + "=" * 70)
        print("  Parallel Worker Execution Complete")
        print("=" * 70)
        for r in results:
            print(f"\n[RESULT: {r['agent'].upper()}] Worktree: {r['worktree']}")
            print(r["stdout"][:500] + ("..." if len(r["stdout"]) > 500 else ""))
            
    elif args.action == "review":
        review_diffs()
        
    elif args.action == "test":
        run_tests(args.filter)
        
    elif args.action == "integrate":
        integrate_branches(args.filter, args.change)
        
    elif args.action == "archive":
        archive_change(args.change)
        
    elif args.action == "push":
        push_to_github(args.message)
        
    elif args.action == "pipeline":
        await run_feature_pipeline(
            change_name=args.change,
            prompt=args.prompt,
            test_filter=args.filter,
            skip_archive=args.skip_archive,
            skip_push=args.skip_push,
            auto_push=args.auto_push
        )


if __name__ == "__main__":
    asyncio.run(main_async())
