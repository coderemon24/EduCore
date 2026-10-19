# Design: Client-Side School Frontend Portal

## Context
See `proposal.md` for background and user requirements. Currently, `routes/web.php` contains only a direct redirect to `/admin/dashboard`. The database contains full institutional data models including `School`, `Notice`, `Staff`, and `AcademicClass`. Multi-tenancy is established at the database level (`school_id`), but domain-based routing is not yet in place. The frontend must operate cleanly on `laravel-school.test` while isolating all school-retrieval logic into an abstracted context resolver for future SaaS multi-tenancy.

## Goals / Non-Goals

**Goals:**
- Provide a clean, modern, responsive client frontend adhering to strict "no AI-slop" design rules.
- Support seamless bilingual display (Bengali via `Hind Siliguri` and English via `Plus Jakarta Sans`).
- Architect the frontend layout at `resources/views/layouts/app.blade.php` with modular partials in `resources/views/layouts/partials/`.
- Organize all public views under `resources/views/frontend/` and controllers under `app/Http/Controllers/Frontend/`.
- Provide a modern 3-column institutional feed inspired by the reference website and screenshot.
- Implement an abstracted `CurrentSchoolContext` resolver so switching to domain-based SaaS multi-tenancy requires zero changes to frontend views or controllers.

**Non-Goals:**
- Implementing the full SaaS custom domain routing/wildcard DNS engine (deferred to the future SaaS phase).
- Re-architecting existing administrative routes or modifying admin layout files.
- Implementing online payment processing or multi-step admission forms (mock/lead contact is sufficient for phase 1).

## Decisions

### 1. Architectural Pattern: Abstracted `CurrentSchoolContext` Resolver
- **Decision**: Introduce `App\Services\CurrentSchoolContext` (and a lightweight middleware `App\Http\Middleware\SetFrontendSchoolContext`).
- **Rationale**: Currently, requests arrive at `laravel-school.test` (or `localhost`). The resolver retrieves the active school record (checking an optional `FRONTEND_DEFAULT_SCHOOL_ID` in `.env`, falling back to `School::where('status', SchoolStatus::ACTIVE)->first()`). In the future SaaS phase, this single class can be upgraded to inspect `request()->getHost()` or `request()->header('X-Tenant-Domain')` without touching any of the frontend Blade views or controllers.
- **Alternatives Considered**: 
  - Hardcoding query in `HomeController`: Violates DRY and couples views to single-tenant assumptions.
  - Full domain routing regex in `routes/web.php`: Overengineered before DNS/wildcard infrastructure is ready.

### 2. Frontend Layout & Modular Partials
- **Decision**: Structure the frontend layout identically to the clean admin pattern:
  - `resources/views/layouts/app.blade.php` (Master document shell)
  - `resources/views/layouts/partials/head.blade.php` (Meta, fonts, Vite)
  - `resources/views/layouts/partials/topbar.blade.php` (EIIN, School code, phone, quick portal login)
  - `resources/views/layouts/partials/navbar.blade.php` (Institutional branding, main navigation, mobile drawer)
  - `resources/views/layouts/partials/notice-ticker.blade.php` (Live announcement marquee/ticker)
  - `resources/views/layouts/partials/footer.blade.php` (Institutional credentials, map, links, copyright)
- **Rationale**: Isolates reusable UI elements and matches user's explicit folder convention.

### 3. Controller & Route Segregation
- **Decision**: Place all frontend controllers in `app/Http/Controllers/Frontend/` and routes in `routes/web.php` prefixed by `frontend.` name group.
  - `HomeController@index` (`/` - Homepage)
  - `HomeController@about` (`/about` - Institutional history & committee)
  - `NoticeController@index` (`/notices` - Notice board directory)
  - `NoticeController@show` (`/notices/{slug}` - Notice circular view)
  - `TeacherController@index` (`/teachers` - Faculty & staff list)
  - `AcademicController@routine` (`/academic/routine` - Class & exam routine)
  - `ContactController@index` (`/contact` - Location map & contact form)
- **Rationale**: Prevents collision with `app/Http/Controllers/Admin/*` and keeps public routing clean.

### 4. Bilingual Typography and "No-Slop" Visual Language
- **Decision**: Load Google Fonts:
  - `Hind Siliguri` (Weights: 400, 500, 600, 700) for authentic Bengali rendering.
  - `Plus Jakarta Sans` (Weights: 400, 500, 600, 700) for crisp Latin characters.
- **Color Discipline**:
  - Primary: Deep Forest Emerald (`#0F382C`) for institutional authority.
  - Secondary: Crisp Slate (`#0F172A` text, `#F8FAFC` surface).
  - Borders: Strict 1px hairline (`#E2E8F0`).
  - No AI-slop: No generic 3D floating icons, no glowing drop shadows, no neon gradients, no blurry colored blobs.

### 5. 3-Column Homepage Feed Layout
- **Decision**: Implement a 12-column responsive grid on the homepage:
  - **Left (Col 1 - 6 cols / 50%)**: Featured Lead circular/news with high-resolution image banner, plus a 2-column secondary highlighted news grid.
  - **Center (Col 2 - 3 cols / 25%)**: "সর্বশেষ" (Latest Announcements) vertical high-density list with real-time date stamps and quick links.
  - **Right (Col 3 - 3 cols / 25%)**: Headmaster message portrait card, Quick Action CTA buttons (Online Admission, Result Archive, Portal Login), and Emergency Contact helpline widget.
- **Rationale**: Direct translation of the Sonatala High School layout and user screenshot, modernized into clean Tailwind CSS grid components.

## Risks / Trade-offs

- **[Risk] Database has no seeded school records on fresh setup** → **Mitigation**: `CurrentSchoolContext` includes a graceful fallback object populated with standard default data (Sonatala High School defaults, EIIN, contact) so views never crash with null pointer exceptions if the database is unseeded.
- **[Risk] Attached circular files missing in local Laragon storage** → **Mitigation**: Detail view checks `Storage::disk('public')->exists()` and gracefully falls back to formatted HTML text or dummy placeholder with warning.
- **[Risk] Font loading latency for Bengali fonts** → **Mitigation**: Use `preconnect` to Google Fonts and specify `font-display: swap` so text renders immediately without FOIT (Flash of Invisible Text).

