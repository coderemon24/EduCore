"""
Frontend Asset & Blade Template Generator for EduCore News Page
"""
import sys
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent.parent
SCRATCH_DIR = Path("C:/Users/mdemo/.gemini/antigravity-ide/brain/df179ce3-2db9-4d75-9c3d-e83948c676f1/scratch")
SCRATCH_DIR.mkdir(parents=True, exist_ok=True)

def update_navbar():
    navbar_path = BASE_DIR / "resources/views/layouts/partials/navbar.blade.php"
    content = navbar_path.read_text(encoding="utf-8")

    # Replace desktop link
    old_desktop = """                {{-- News --}}
                <div class="snhs-nav-item">
                    <a href="{{ route('frontend.notices.index') }}" class="snhs-nav-link">সংবাদ</a>
                </div>"""

    new_desktop = """                {{-- News --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.news*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.news.index') }}" class="snhs-nav-link">সংবাদ</a>
                </div>"""

    # Replace mobile link
    old_mobile = """            <a href="{{ route('frontend.notices.index') }}" class="snhs-mobile-link">সংবাদ</a>"""
    new_mobile = """            <a href="{{ route('frontend.news.index') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.news*') ? 'is-active' : '' }}">সংবাদ</a>"""

    if old_desktop in content:
        content = content.replace(old_desktop, new_desktop)
        print("[OK] Updated desktop navbar link")
    else:
        print("[WARN] old_desktop link not found")

    if old_mobile in content:
        content = content.replace(old_mobile, new_mobile)
        print("[OK] Updated mobile drawer link")
    else:
        print("[WARN] old_mobile link not found")

    target = SCRATCH_DIR / "navbar.blade.php"
    target.write_text(content, encoding="utf-8")
    print(f"[OK] Generated {target}")

if __name__ == "__main__":
    update_navbar()
