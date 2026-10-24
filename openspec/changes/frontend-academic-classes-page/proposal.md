# Proposal: Frontend Academic Classes Page and Navigation Integration

## Why
Currently, the public frontend of the school management system provides pages for the Homepage, About Us, Notices, Faculty Directory (`/teachers`), and Academic Routine (`/academic/routine`). However, prospective parents, enrolled students, and visitors lack a dedicated overview of the academic structure—specifically the active classes, their assigned sections, room numbers, seat capacities, class teachers, and curriculum subjects with credit hours and optional/compulsory markings.

Following the reference school portal (`https://sonatalahighschool.edu.bd/academic/classes`), introducing a modern, minimalistic, and user-friendly Academic Classes page (`/academic/classes`) along with an entry in the primary and mobile navigation dropdowns ("একাডেমিক" / "Academic") fulfills a key transparency and institutional marketing requirement.

## What Changes
1. **Public Academic Classes Route & Controller Action**:
   - Register `GET /academic/classes` mapped to `AcademicController@classes` with route name `frontend.academic.classes`.
   - Eager-load active classes, sections with class teachers, and assigned subjects with pivot attributes (`type`, `full_marks`, `pass_marks`).
   - Implement graceful multi-tenant school resolution via `CurrentSchoolContext`.

2. **Dedicated Frontend Blade Template (`resources/views/frontend/academic/classes.blade.php`)**:
   - Follow the reference layout from `sonatalahighschool.edu.bd` elevated to the project's modern, editorial, and tactile aesthetic.
   - Breadcrumb navigation: `প্রচ্ছদ / একাডেমিক / শ্রেণিসমূহ ও পাঠ্যসূচি`.
   - Header banner featuring Font Awesome icons (`fa-graduation-cap`), academic session indicator, and metric chips (classes, sections, curriculum subjects).
   - Interactive tactile class selector pills (Alpine.js-powered instant switching with query parameter / URL hash support).
   - Class overview summary card with live statistics.
   - 2-Column responsive split:
     - **Sections Column (শাখাসমূহ)**: Interactive section chips/cards displaying section title, room number (`fa-door-open`), seat capacity (`fa-user-group`), and appointed class teacher (`fa-chalkboard-user`).
     - **Subjects Column (বিষয়সমূহ)**: Structured curriculum rows displaying subject title, code (`fa-barcode`), full marks / pass marks (`fa-award`), compulsory badge, and optional/elective pill indicator (`fa-book-open`).
   - Secondary streams/groups tags for Classes 9–10 (Science, Humanities, Business Studies).
   - Quick shortcut button jumping to the class routine at `/academic/routine`.
   - Robust empty/fallback state when no classes or subjects are configured.

3. **Navigation Integration (`resources/views/layouts/partials/navbar.blade.php`)**:
   - Desktop: Add `শ্রেণিসমূহ` as the premier item in the `একাডেমিক` dropdown menu (`data-dropdown="academic"`), before `ক্লাস রুটিন`.
   - Mobile: Add `শ্রেণিসমূহ` in the mobile drawer accordion under `একাডেমিক` (`data-mobile-sub="academic"`).
   - Active state styling: Automatically apply `.is-active` / green accent when on `frontend.academic.classes*`.

## Capabilities

### Modified Capabilities
- `client-school-portal`: Add requirement and scenarios for public academic classes directory (`/academic/classes`), interactive class/section/subject visualizer, and academic dropdown navigation integration.

## Impact
- **Routes**: Adds `GET /academic/classes` in `routes/web.php`.
- **Controllers**: Updates `app/Http/Controllers/Frontend/AcademicController.php` with `classes()` method.
- **Views**: Creates `resources/views/frontend/academic/classes.blade.php` and edits `resources/views/layouts/partials/navbar.blade.php`.
- **Assets/Styling**: Uses existing CSS utility tokens from `public/assets/css/style.css` and Font Awesome 6 icons loaded in `layouts.partials.head`.
- **Database**: Strictly read-only queries against existing `academic_classes`, `sections`, `subjects`, `staff`, and `class_subject_assignments` tables. Zero breaking schema changes.
