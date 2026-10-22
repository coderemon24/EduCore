# Tasks: Frontend Typography Hierarchy

## 1. Homepage Hero & Audience Strip Typography

- [ ] 1.1 Update hero slider headings in `resources/views/frontend/home/partials/fullscreen-slider.blade.php` to Tier 1 dominant scale (`text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight`) with `leading-tight`.
- [ ] 1.2 Refactor qualifying strip in `resources/views/frontend/home/index.blade.php`: set audience title to `text-sm font-semibold text-white`, descriptions to `text-xs leading-relaxed text-slate-300`, and kicker tag to quiet Tier 4 (`text-[11px] font-bold uppercase tracking-wider`).

## 2. Homepage Capabilities & Notice Board Sections Typography

- [ ] 2.1 Refactor Academic Capabilities section in `resources/views/frontend/home/index.blade.php`: upgrade section title to Tier 1 with exactly one highlighted accent phrase (`text-emerald-600`), card titles to Tier 2 (`text-xl sm:text-2xl font-bold text-slate-900`), and body descriptions to Tier 3 (`text-sm leading-relaxed text-slate-700`).
- [ ] 2.2 Refactor Homepage Notice Board & Circulars section: set notice headings to Tier 2 (`text-lg sm:text-xl font-bold text-slate-900`), notice body to Tier 3 (`text-sm leading-relaxed text-slate-600`), and dates/categories to quiet Tier 4 (`text-xs text-slate-500 font-medium`).
- [ ] 2.3 Refactor Headmaster Statement and Institutional Telemetry stats: upgrade message quotes to Tier 3 (`text-base leading-relaxed text-slate-700`), stat figures to bold high-impact numbers, and captions/designations to quiet Tier 4.

## 3. Secondary Public Views Typography Refactor

- [ ] 3.1 Refactor About Us view in `resources/views/frontend/about/index.blade.php`: update page header to Tier 1, section cards to Tier 2 (`text-xl font-bold`), and institutional history body copy to Tier 3 (`text-base leading-relaxed text-slate-700`).
- [ ] 3.2 Refactor Public Notice index and detail views in `resources/views/frontend/notices/index.blade.php` and `resources/views/frontend/notices/show.blade.php`: upgrade notice title to Tier 1/2, article content to Tier 3 (`text-base leading-relaxed`), and meta/tags to quiet Tier 4.

## 4. Scope Verification & Visual Testing

- [ ] 4.1 Verify administrative boundary isolation: check `git status` to guarantee zero files in `resources/views/admin/` or `resources/views/layouts/admin.blade.php` were touched.
- [ ] 4.2 Verify layout preservation: confirm flex/grid structures, container widths, and background colors have no regressions across mobile, tablet, and desktop breakpoints.
- [ ] 4.3 Run asset build verification (`npm run build`) to ensure all Tailwind utility classes compile cleanly without errors.
