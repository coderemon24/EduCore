# Tasks: Client-Side School Frontend Portal

## 1. Layout & Blade Architecture

- [x] 1.1 Create `resources/views/layouts/app.blade.php` with master HTML5 markup, bilingual font links (`Hind Siliguri` + `Plus Jakarta Sans`), and yield sections.
- [x] 1.2 Create `resources/views/layouts/partials/head.blade.php` containing SEO tags, favicon binding, Google Fonts, and `@vite(['resources/css/app.css', 'resources/js/app.js'])`.
- [x] 1.3 Create `resources/views/layouts/partials/topbar.blade.php` featuring EIIN, school code, emergency helpline, and administrative portal login link.
- [x] 1.4 Create `resources/views/layouts/partials/navbar.blade.php` with desktop institution branding, accessible dropdowns, and an Alpine.js/pure JS mobile drawer.
- [x] 1.5 Create `resources/views/layouts/partials/notice-ticker.blade.php` for urgent scrolling/marquee circular updates.
- [x] 1.6 Create `resources/views/layouts/partials/footer.blade.php` with institutional credentials, contact addresses, quick links, and government portal badges.

## 2. Context Resolver & SaaS-Ready Service Logic

- [x] 2.1 Create `App\Services\CurrentSchoolContext` to resolve the active school tenant (defaulting to primary active school for `laravel-school.test`, structured for domain matching in future SaaS phase).
- [x] 2.2 Create middleware `App\Http\Middleware\SetFrontendSchoolContext` (or controller base resolution) to share `$currentSchool` across all frontend Blade views.
- [x] 2.3 Register middleware in `bootstrap/app.php` for the web middleware group.

## 3. Controllers & Business Logic

- [x] 3.1 Create `App\Http\Controllers\Frontend\HomeController` handling homepage data aggregation (lead stories, latest notices, principal quote, and statistics).
- [x] 3.2 Create `App\Http\Controllers\Frontend\NoticeController` handling notice directory pagination, audience filtering, and single notice detail view with attachment downloads.
- [x] 3.3 Create `App\Http\Controllers\Frontend\TeacherController` querying active faculty grouped by designation/department.
- [x] 3.4 Create `App\Http\Controllers\Frontend\AcademicController` displaying class/exam routine timetables.
- [x] 3.5 Create `App\Http\Controllers\Frontend\ContactController` with contact form validation and institutional map display.
- [x] 3.6 Define all public routes in `routes/web.php` replacing the unconditional redirect while preserving `/admin/*` routes.

## 4. Blade Views & 3-Column Portal Interactions

- [x] 4.1 Create `resources/views/frontend/home/index.blade.php` implementing the modernized 3-column feed:
  - Column 1: Featured lead story banner and secondary highlight cards.
  - Column 2: "সর্বশেষ" (Latest announcements) compact feed with date badges.
  - Column 3: Headmaster portrait/message, Quick Admission/Result CTA buttons, and helpline widget.
- [x] 4.2 Create `resources/views/frontend/home/partials/stats-counter.blade.php` rendering student count, faculty count, classrooms, and passing rate.
- [x] 4.3 Create `resources/views/frontend/notices/index.blade.php` with search, category pills, and paginated notice list.
- [x] 4.4 Create `resources/views/frontend/notices/show.blade.php` with circular title, publication metadata, body text, and PDF download button.
- [x] 4.5 Create `resources/views/frontend/teachers/index.blade.php` with faculty cards (photos, subject, email, qualification).
- [x] 4.6 Create `resources/views/frontend/academic/routine.blade.php` with clean timetable display.
- [x] 4.7 Create `resources/views/frontend/contact/index.blade.php` with interactive contact info and message form.

## 5. Verification & Testing

- [x] 5.1 Verify asset compilation and Tailwind CSS v4 styling with `npm run build`.
- [x] 5.2 Verify route list and URL resolution with `php artisan route:list`.
- [x] 5.3 Test responsiveness on mobile (< 768px), tablet (768px–1023px), and desktop (>= 1024px) viewports with zero horizontal overflow.
- [x] 5.4 Verify bilingual font rendering (`Hind Siliguri` and `Plus Jakarta Sans`) across all views.
- [x] 5.5 Run `laravel/pint` to ensure clean PSR-12/Laravel code formatting across all new controllers and services.

