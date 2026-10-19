# Proposal: Client-Side School Frontend Portal

## Why
Currently, the application operates purely as an administrative backend (`/admin/*`) with root redirecting to `/admin/dashboard`. Prospective students, guardians, alumni, and community members have no public-facing institutional website to read circulars, view class/exam routines, review faculty profiles, learn about the school, or get in contact.

The reference institution ([Sonatala High School](https://sonatalahighschool.edu.bd/)) demonstrates the essential information architecture expected by parents, students, and education boards in Bangladesh. However, traditional school sites suffer from dated 2010-era clutter, harsh color blocks, low mobile responsiveness, and visual fatigue.

We need a modern, high-contrast, minimalist, and responsive client-side school portal with zero AI slop, bilingual typography (Bengali + English), and a clean 3-column portal feed inspired by the reference website. Crucially, while currently running locally on `laravel-school.test` against the primary school record, the frontend must be decoupled using a clean School Context Provider/Resolver so that when SaaS multi-tenancy (custom domains/subdomains) is activated, the frontend adapts seamlessly without refactoring.

## What Changes
- **Client Route Architecture**: Clean public routes in `routes/web.php` for Homepage, Notices, Teachers/Staff, Academic Information, and Contact.
- **Frontend Template Mastering**:
  - Master layout at `resources/views/layouts/app.blade.php`.
  - Reusable partials in `resources/views/layouts/partials/` (`head.blade.php`, `topbar.blade.php`, `navbar.blade.php`, `notice-ticker.blade.php`, `footer.blade.php`).
- **Page Views Structure**: All public page templates organized inside `resources/views/frontend/` (`home/index.blade.php`, `notices/index.blade.php`, `notices/show.blade.php`, `teachers/index.blade.php`, etc.).
- **Frontend Controllers**: Isolated controller hierarchy in `app/Http/Controllers/Frontend/` (`HomeController`, `NoticeController`, `TeacherController`, `AcademicController`, `ContactController`).
- **School Tenant Resolver**: A lightweight service/helper (`CurrentSchoolContext`) that resolves the active `School` model for the frontend (currently bound to default active school on `laravel-school.test`, but extensible for domain/subdomain matching in SaaS phase).
- **Anti-Slop Editorial Design**:
  - Bilingual typography combining `Hind Siliguri` (Bengali) and `Plus Jakarta Sans` (English).
  - Modernized 3-column information hub: (1) Featured stories/circulars, (2) Real-time latest notices ("সর্বশেষ"), (3) Headmaster quote/portrait & emergency quick-links.
  - Responsive mobile drawer navigation, live notice ticker, academic statistics counter, and accessible document downloads.

## Capabilities

### New Capabilities
- `client-school-portal`: Comprehensive public-facing client portal providing bilingual institutional landing, latest notices feed, faculty directory, routines, and contact information backed by a SaaS-ready tenant resolution bridge.

### Modified Capabilities
None. (Existing administrative capabilities and APIs remain untouched).

## Impact
- **Routes**: `routes/web.php` replaces the blanket redirect with public frontend route groups while preserving admin routes in `routes/admin.php`.
- **Views**: Adds `resources/views/layouts/app.blade.php`, `resources/views/layouts/partials/*`, and `resources/views/frontend/*`.
- **Controllers**: Adds `app/Http/Controllers/Frontend/*`.
- **Dependencies & Build**: Uses existing Tailwind CSS v4, Vite 7, and Google Fonts (`Hind Siliguri` & `Plus Jakarta Sans`).

