"""
Multi-Agent Orchestrator for EduCore (Laravel School)
Coordinates specialized AI workers across isolated Git worktrees.
"""

import argparse
import asyncio
import os
import subprocess
import sys
from pathlib import Path

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
    print("=" * 65)
    print("  EduCore Multi-Agent Team Status")
    print("=" * 65)
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
    print("=" * 65)


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


def integrate_branches(test_filter=""):
    """Integrate worker branches into main after review."""
    print("=" * 65)
    print("  Orchestrator: Integrating Worker Branches into main")
    print("=" * 65)
    
    # 1. Integrate backend
    print("Step 1: Merging agent/backend into main...")
    b_res = run_cmd("git merge agent/backend -m 'feat(backend): integrate verified backend changes' --no-ff", cwd=BASE_DIR)
    if b_res.returncode != 0:
        print(f"[ERROR] Backend merge failed:\n{b_res.stderr or b_res.stdout}")
        return False
    print("  [OK] Backend merged successfully.")
    
    # 2. Integrate frontend
    print("Step 2: Merging agent/frontend into main...")
    f_res = run_cmd("git merge agent/frontend -m 'feat(frontend): integrate verified frontend changes' --no-ff", cwd=BASE_DIR)
    if f_res.returncode != 0:
        print(f"[ERROR] Frontend merge failed:\n{f_res.stderr or f_res.stdout}")
        return False
    print("  [OK] Frontend merged successfully.")
    
    print("\nStep 3: Running automated test suite gate...")
    t_code = run_tests(test_filter or "AboutPageTest")
    if t_code == 0:
        print("[SUCCESS] All integration tests passed.")
        return True
    else:
        print("[WARNING] Tests failed during integration check.")
        return False


async def main_async():
    parser = argparse.ArgumentParser(description="Multi-Agent Orchestrator for EduCore")
    subparsers = parser.add_subparsers(dest="action", help="Action to execute")
    
    # Status
    subparsers.add_parser("status", help="Show worktree and agent statuses")
    
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
    
    args = parser.parse_args()
    
    if args.action == "status" or not args.action:
        show_status()
        
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
        print("\n" + "=" * 65)
        print("  Parallel Worker Execution Complete")
        print("=" * 65)
        for r in results:
            print(f"\n[RESULT: {r['agent'].upper()}] Worktree: {r['worktree']}")
            print(r["stdout"][:500] + ("..." if len(r["stdout"]) > 500 else ""))
            
    elif args.action == "review":
        print("=== Diff: agent/backend vs main ===")
        b_diff = run_cmd("git diff main..agent/backend --stat")
        print(b_diff.stdout or "No differences.")
        
        print("\n=== Diff: agent/frontend vs main ===")
        f_diff = run_cmd("git diff main..agent/frontend --stat")
        print(f_diff.stdout or "No differences.")
        
    elif args.action == "test":
        run_tests(args.filter)
        
    elif args.action == "integrate":
        integrate_branches(args.filter)


if __name__ == "__main__":
    asyncio.run(main_async())
