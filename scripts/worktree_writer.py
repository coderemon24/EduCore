"""
Worktree File Writer Helper for EduCore Multi-Agent Worktrees.
Safely writes exact file contents without shell quote escaping issues.
"""
import sys
from pathlib import Path

def main():
    if len(sys.argv) < 3:
        print("Usage: python worktree_writer.py <target_path> <content_file>")
        sys.exit(1)
        
    target_path = Path(sys.argv[1]).resolve()
    content_file = Path(sys.argv[2]).resolve()
    
    target_path.parent.mkdir(parents=True, exist_ok=True)
    content = content_file.read_text(encoding="utf-8")
    target_path.write_text(content, encoding="utf-8")
    print(f"[OK] Wrote {len(content)} characters to {target_path}")

if __name__ == "__main__":
    main()
