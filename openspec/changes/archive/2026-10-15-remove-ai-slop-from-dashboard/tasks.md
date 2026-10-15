# Tasks: Remove AI Slop from Admin Dashboard View

## 1. Layout & Header Refactoring

- [x] 1.1 Replace pseudo-academic jargon in `@section('title')`, page heading, and breadcrumbs in `resources/views/admin/dashboard.blade.php` with clean, authentic institutional terminology ("Dashboard Overview", "School Administration", "Academic Performance").
- [x] 1.2 Clean action buttons in page header, removing inline `onclick="window.Swal && Swal.fire(...)"` script handlers and binding buttons cleanly via semantic attributes (`data-action="export-report"`, `data-action="new-admission"`).

## 2. Metric Cards Refactoring

- [x] 2.1 Standardize the 4 KPI metric cards in `resources/views/admin/dashboard.blade.php` to use cohesive solid 1px borders (`border-slate-200`) and neutral backgrounds, eliminating the mismatched pastel borders (`border-violet-100`, `border-sky-100`, `border-amber-100`, `border-emerald-100`).
- [x] 2.2 Remove all-caps eyebrow typography with excessive letter spacing (`uppercase tracking-wider`) and replace with disciplined, high-contrast, accessible labels.
- [x] 2.3 Refactor indicator badges and subtext on KPI cards to present clear, authentic statistics.

## 3. Analytics & Demographics Refactoring

- [x] 3.1 Refactor the monthly comparative performance chart: remove hyperbolic explanatory text ("Academic assessment index comparing student achievement vs. curriculum milestone targets"), simplify legend, and ensure crisp 1px grid styling.
- [x] 3.2 Refactor the student gender ratio demographic widget into a high-density, accessible layout with clean progress indicators and clear headcount metrics.

## 4. Merit Roll Table & Notice Widget Cleanup

- [x] 4.1 Refactor the Star Students table: remove unhandled dummy header/row checkboxes, eliminate inline `onclick="window.Swal && Swal.fire(...)"` popups from the action column and "View All Honors" button, and implement clean table markup.
- [x] 4.2 Streamline the Institutional Notice Board widget: remove the repetitive "Official" pill badge and the redundant nested "Mid-Term Exams Notice" banner callout.

## 5. Modular JavaScript Delegation

- [x] 5.1 Ensure `public/admin-assets/js/admin-actions.js` cleanly handles report export or delegated dialogs without requiring inline scripts in `dashboard.blade.php`.
- [x] 5.2 Verify that quick admission triggers `#page-admission-btn` and `[data-action="new-admission"]` continue to work properly with the modal controller.

## 6. Verification & Quality Assurance

- [x] 6.1 Inspect `resources/views/admin/dashboard.blade.php` to ensure zero remaining inline `onclick` handlers, zero generic AI buzzwords, and adherence to `.agents/skills/no-slop-ui/SKILL.md`.
- [x] 6.2 Run Laravel Pint or view validation checks to ensure clean syntax and template integrity.
- [x] 6.3 Verify the dashboard view renders with HTTP 200 and passes layout checks in the browser.
