# Tasks: Frontend Color Hierarchy & Semantic Discipline

## 1. Bright Accent Refactor (Primary Admission Action & Headlines)

- [x] 1.1 Refactor Hero Slider and Navigation Bar: reserve bright accent (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold`) for the admission application button in navbar and hero CTA, and ensure headline highlights use exactly one accent span per slide.
- [x] 1.2 Refactor Closing Callout Banner in `resources/views/frontend/home/index.blade.php`: style the primary "ভর্তি আবেদন" action button in `bg-amber-400 text-slate-950 font-extrabold` while keeping secondary buttons (WhatsApp, Phone) in calm neutrals (`bg-slate-900/60`, `bg-white/15`).

## 2. Dedicated Second Color for Notices & Circulars

- [x] 2.1 Refactor Homepage Notice Board Widget in `resources/views/frontend/home/partials/notice-board.blade.php`: transition the bell badge and circular tags to the dedicated Sky Blue scheme (`bg-sky-50 text-sky-700 border border-sky-200`) and style the "সকল নোটিশ" button in calm institutional green (`bg-[#005030]`).
- [x] 2.2 Refactor Notice Index & Show Views in `resources/views/frontend/notices/index.blade.php` and `resources/views/frontend/notices/show.blade.php`: apply the dedicated Sky Blue scheme to notice category pills, circular badges, and date indicators (`bg-sky-50 text-sky-800 border-sky-200`), making notice signaling consistent across pages.

## 3. General Interface Neutralization & Data Preservation

- [x] 3.1 Neutralize Academic Capabilities and Why-Choose-Us Pillars in `resources/views/frontend/home/index.blade.php`: replace arbitrary emerald bubbles with calm neutral slate icon containers (`bg-slate-100 text-slate-700 border border-slate-200`) and neutral borders.
- [x] 3.2 Neutralize About Us View Cards in `resources/views/frontend/about/index.blade.php`: convert mission/vision icon bubbles and facility containers to clean neutral containers, preserving telemetry stats and verification badges.

## 4. Verification & Scope Gate

- [x] 4.1 Verify administrative boundary isolation: check `git status` to ensure zero files under `resources/views/admin/` or `resources/views/layouts/admin.blade.php` are touched.
- [x] 4.2 Run asset build verification (`npm run build`) and frontend test suite to ensure clean compilation and zero visual regressions.
