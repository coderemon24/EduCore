# Tasks: Frontend Academic Classes Page and Navigation Integration

## 1. Backend Route & Controller Logic (Backend Engineer)

- [x] 1.1 Register the public academic classes route `Route::get('/academic/classes', [AcademicController::class, 'classes'])->name('academic.classes');` in `routes/web.php` under the `frontend.school` middleware group.
- [x] 1.2 Implement the `classes(Request $request): View` method in `app/Http/Controllers/Frontend/AcademicController.php`:
  - Resolve active school context via `CurrentSchoolContext::get()`.
  - Fetch active classes ordered by `order_index`, `numeric_code`, and `name`.
  - Eager-load `sections.classTeacher` and `subjects` (with pivot fields: `type`, `full_marks`, `pass_marks`).
  - Calculate total telemetry metrics (total classes, total sections, total subjects).
  - Pass `school`, `classes`, `selectedClassId`, and telemetry variables to the view.

## 2. Navigation Dropdown Integration (Frontend Engineer)

- [x] 2.1 Update desktop navigation in `resources/views/layouts/partials/navbar.blade.php`:
  - Add `শ্রেণিসমূহ` link pointing to `route('frontend.academic.classes')` as the premier item in the `একাডেমিক` dropdown (`data-dropdown="academic"`).
  - Bind active state styling (`.is-active` / green border accent) when `request()->routeIs('frontend.academic.classes*')`.
- [x] 2.2 Update mobile navigation in `resources/views/layouts/partials/navbar.blade.php`:
  - Add `শ্রেণিসমূহ` link inside the mobile slide-over accordion (`data-mobile-sub="academic"`).
  - Ensure clicking the mobile link closes the mobile drawer and navigates cleanly.

## 3. Dedicated Academic Classes Blade View (Frontend Engineer)

- [x] 3.1 Create `resources/views/frontend/academic/classes.blade.php` extending `layouts.app`:
  - Institutional breadcrumbs: `প্রচ্ছদ / একাডেমিক / শ্রেণিসমূহ ও পাঠ্যসূচি`.
  - Hero banner with institutional title, session badge (`২০২৬ শিক্ষাবর্ষ`), and Font Awesome icons (`fa-solid fa-graduation-cap`).
  - Metric summary chips (Total Classes, Total Sections, Curriculum Subjects).
- [x] 3.2 Build interactive tactile class selection pills:
  - Responsive flex/grid container supporting horizontal swipe on mobile and wrapped grid on desktop.
  - Alpine.js reactive state (`x-data="{ activeClass: '...' }"`) supporting instant zero-flicker tab switching, URL hash sync, and direct URL query parameter fallback.
  - Active pill styling in `#005030` deep emerald with white text and active status dot.
- [x] 3.3 Implement the 2-column detail breakdown:
  - Class summary banner with class title, numeric code, and quick stats (sections, total subjects, optional count).
  - **Left column (শাখাসমূহ / Sections)**: Section cards displaying section name (e.g. `শাখা - ক`), appointed class teacher name & avatar (`fa-solid fa-chalkboard-user`), room number (`fa-solid fa-door-open`), and seat capacity badge (`fa-solid fa-user-group`).
  - **Right column (বিষয়সমূহ / Subjects)**: Structured curriculum rows displaying subject name (`fa-solid fa-book-open`), code (`fa-solid fa-barcode`), full marks & pass marks (`fa-award`), compulsory badge, and optional/elective tag.
- [x] 3.4 Add secondary streams/groups indicators for Classes 9–10 (Science, Humanities, Business Studies) and direct shortcut button to view class routine (`/academic/routine`).
- [x] 3.5 Implement clean, friendly educational empty-state handling for unseeded or inactive classes.

## 4. Verification & Automated Testing (Team Lead)

- [x] 4.1 Write feature tests in `tests/Feature/AcademicClassesTest.php`:
  - Verify `/academic/classes` returns HTTP 200 and loads correct Blade view.
  - Verify active school classes, sections, and subjects render properly.
  - Verify class filter query parameter (`?class_id=X`) selects the requested class.
  - Verify empty-state rendering when no classes exist.
- [x] 4.2 Run test suite via `php artisan test --filter=AcademicClassesTest` and ensure 100% pass rate.
- [x] 4.3 Inspect desktop and mobile layout rendering, touch targets, and dropdown active state.
