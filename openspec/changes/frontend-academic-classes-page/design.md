# Design: Frontend Academic Classes Page and Navigation Integration

## Context
See [`proposal.md`](file:///c:/laragon/www/laravel-school/openspec/changes/frontend-academic-classes-page/proposal.md). The application is an institutional school management system powered by Laravel 12. Frontend routing is located in `routes/web.php` guarded by `web` and `frontend.school` middleware. The master layout `layouts.app` already includes `layouts.partials.navbar` (with Alpine.js-powered mobile menus and desktop hover dropdowns) and loads Font Awesome 6 icons, Google Fonts (`Noto Sans Bengali` & `Plus Jakarta Sans`), and Tailwind CSS v4 / local CSS (`style.css`, `responsive.css`).

The database already possesses active tables and relationships:
- `AcademicClass`: `hasMany(Section::class)`, `belongsToMany(Subject::class, 'class_subject_assignments')`.
- `Section`: `belongsTo(Staff::class, 'class_teacher_id')`, `capacity`, `room_number`.
- `Subject`: pivot fields (`type`, `full_marks`, `pass_marks`).

## Goals / Non-Goals

**Goals:**
- Provide a responsive, high-contrast, editorial-grade public directory view for academic classes at `/academic/classes`.
- Provide zero-latency class switching using client-side Alpine.js state tabs while keeping full server-rendered SEO and query param (`?class_id=X`) / hash (`#class-6`) deep-linking.
- Seamlessly integrate with existing `layouts.app` and `layouts.partials.navbar` across desktop and mobile.
- Support secondary class streams (Science, Humanities, Business Studies) and class teacher assignments without throwing errors when records or relationships are null.
- Provide a 100% verified test suite ensuring route accessibility, data rendering, and multi-tenant school isolation.

**Non-Goals:**
- Admin CRUD modifications: Managing classes/sections/subjects is already implemented in the administrative portal (`/admin/academic/*`).
- Public student registration or online admission submission from this page (a clear link to the admission modal/contact page is provided instead).
- Custom database schema alterations: Reuses existing database models and columns.

## Decisions

### 1. Hybrid Client/Server State Architecture for Class Tabs
- **Decision**: Pre-render all active classes and their respective section/subject cards in semantic HTML within the initial Blade payload, and wrap the container with Alpine.js (`x-data="{ activeClass: '...' }"`) for instant tab switching.
- **Rationale**: Instant switching between Class 6, 7, 8, etc., provides an app-like UX without network lag or page flicker. Having all classes in the HTML ensures search engines index all class descriptions and subjects, while allowing URL hash synchronization (`window.location.hash`).
- **Alternative considered**: Pure AJAX fetch on click (`fetch('/api/classes/...')`). Rejected because it requires additional API routes, introduces network delay, and harms SEO crawlability.

### 2. Multi-Tier Visual Hierarchy & Color System
- **Decision**: Follow the institutional palette: Deep Emerald (`#005030` and `#0b6b4b`), Slate-900 typography, subtle 1px hairline borders (`border-slate-200`), and warm amber accents (`bg-amber-400`).
- **Rationale**: Strict adherence to the "no-slop" design guidelines ensures high legibility, clean structure, and alignment with `sonatalahighschool.edu.bd` without tacky neon gradients or generic card templates.

### 3. Navigation Dropdown Order and Active State
- **Decision**: Place `শ্রেণিসমূহ` (Classes) as the very first link under the `একাডেমিক` dropdown (above `ক্লাস রুটিন`), and highlight both the dropdown parent and the item itself when `request()->routeIs('frontend.academic.classes*')`.
- **Rationale**: A user naturally browses the structural classes and curriculum before viewing operational schedules (routines or exam timetables).

### 4. Resilient Fallback Handling
- **Decision**: Eager-load relations using safe null-coalescing. If a section lacks a class teacher, render a generic "অফিস তদারকি" (Office Supervised) badge. If subjects are unassigned, render an educational placeholder banner.
- **Rationale**: Guarantees zero 500 errors and prevents broken UI layouts in unseeded environments.

## Risks / Trade-offs

- **[Risk] High number of classes and subjects increasing initial page DOM size**:
  - *Mitigation*: Schools typically have between 5 to 12 classes with 10–15 subjects each. The total payload is ~35KB uncompressed, which easily gzips under 7KB. This is lightweight and eliminates extra HTTP roundtrips.
- **[Risk] Broken styling on ultra-small mobile devices (<360px)**:
  - *Mitigation*: The class selector grid uses `overflow-x-auto` with a flex container on mobile (`flex-nowrap sm:flex-wrap`), enabling smooth horizontal swipe for class pills while stacking the sections and subjects vertically.
