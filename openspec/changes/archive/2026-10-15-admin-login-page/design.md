# Technical Design: Institutional Admin Authentication & Premium Login Interface

## Context

The EduCore School Management System operates on Laravel 12, Tailwind CSS v4, and Vite 7. The admin portal currently routes directly to `AdminController@index` without authentication guards or session protection. Standard web applications frequently rely on generic scaffolding (e.g. Breeze, Jetstream) or default templates that exhibit "AI slop" (overused purple/pink gradients, blurry floating glassmorphism, sparkle icons, unaligned low-density layouts).

This design defines a bespoke, lightweight, and institutional authentication architecture strictly tailored for school administrators, adhering to the "no-slop" UI protocol and clean Laravel 12 conventions.

See [proposal.md](file:///c:/laragon/www/laravel-school/openspec/changes/admin-login-page/proposal.md) for background and motivation, and [spec.md](file:///c:/laragon/www/laravel-school/openspec/changes/admin-login-page/specs/admin-auth/spec.md) for testable requirements.

## Goals / Non-Goals

**Goals:**
- Provide a dedicated, secure authentication barrier for the `/admin` route namespace.
- Design an authoritative, high-density, human-crafted login page free of AI slop.
- Enforce brute-force attack throttling and session security hygiene (session fixation protection, CSRF validation, secure logout).
- Provide an accessible, responsive form with zero-dependency password visibility toggling and client-side usability features.
- Provide a clean local testing seed/helper for administrator credentials.

**Non-Goals:**
- Public user registration or self-service signup (the school administrative portal is strictly invite/staff provisioned).
- Multi-factor SMS/hardware token authentication (can be introduced as an extension later).
- Social OAuth logins (Google/GitHub/Microsoft) for admin accounts.
- Modifying student or parent end-user authentication schemes.

## Decisions

### 1. Dedicated Admin Authentication Controller vs. Heavy Starter Kits (Breeze/Jetstream)
- **Decision:** Build a dedicated `App\Http\Controllers\Admin\Auth\LoginController` accompanied by `App\Http\Requests\Admin\LoginRequest` rather than installing Laravel Breeze or Jetstream.
- **Rationale:** The application already possesses a tailored layout, Vite bundle, and routing structure (`routes/admin.php`). Installing Breeze would introduce redundant route files, unwanted user views, and generic Tailwind classes that conflict with the school's bespoke design system.
- **Alternatives Considered:**
  - *Laravel Breeze*: Rejected due to excess boilerplate, generic styling, and unnecessary student/public scaffolding.
  - *Laravel Fortify*: Considered, but adds unnecessary headless abstraction for a straightforward, highly customized Blade view.

### 2. No-Slop Institutional Visual Design System
- **Decision:** Anchor the interface in an asymmetric split layout:
  - **Left Side (Institutional Showcase, 55-60% width on desktop):**
    - Deep structural slate background (`bg-slate-900` / `#0F172A`) with a subtle 1px grid architectural line motif.
    - Institutional seal/crest with the authentic "EduCore Academy" identity.
    - System status bar with live status indicator ("Operational // 99.98% Telemetry", "256-bit Session Encryption", "Admin Portal v2.4.0").
    - Clean academic mission statement and security notice.
    - Strict avoidance of cliché purple-to-blue neon gradients, fuzzy glassmorphism blurs, `✨` sparkle emojis, or generic 120px empty heroes.
  - **Right Side (Authentication Console, 40-45% width on desktop, 100% on mobile):**
    - High-contrast card surface (`bg-white` on `bg-slate-50`) with crisp 1px borders (`border-slate-200`).
    - Clear typographic hierarchy: Title ("Administrator Sign In"), subtitle with guidance, high-contrast field labels (`text-slate-700 font-medium`).
    - Inputs with 1px border (`border-slate-300`), smooth transition to focus state (`focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/15`).
    - Integrated show/hide password toggle using pure SVG icons (eye / eye-slash) with keyboard accessibility.
    - Submit CTA: Solid high-contrast indigo (`bg-indigo-600 hover:bg-indigo-700 text-white font-medium`) with clear active/pressed states.
    - Subtle developer/demo helper chip in non-production environments to populate default admin credentials (`admin@educore.test` / `password`).
- **Typography & Font Tokens:**
  - Base: `Plus Jakarta Sans` (weights 400, 500, 600, 700).
  - Telemetry & Badges: `JetBrains Mono` for system version and encryption badges.

### 3. Route Guarding and Middleware Hierarchy
- **Decision:** Partition `routes/admin.php` into two distinct middleware groups:
  ```php
  // Guest-only routes
  Route::middleware('guest')->group(function () {
      Route::get('/login', [LoginController::class, 'create'])->name('login');
      Route::post('/login', [LoginController::class, 'store'])->name('login.store');
  });

  // Authenticated admin routes
  Route::middleware('auth')->group(function () {
      Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
      Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
  });
  ```
- **Rationale:** Ensures clean separation of concerns, native redirection of authenticated users away from the login screen, and immediate redirection of unauthenticated requests to `/admin/login` with `intended()` tracking.

### 4. Rate Limiting and Session Fixation Defense
- **Decision:** Enforce throttle rate limiting directly in `LoginRequest` using `Illuminate\Support\Facades\RateLimiter` (5 attempts per minute per IP + email key).
- **Rationale:** Native Laravel RateLimiter provides accurate IP-bound throttling, clear lockout seconds calculation, and standardized validation error formatting without requiring external Redis or third-party packages.

### 5. Modular Client-Side JavaScript
- **Decision:** Encapsulate password reveal toggling and demo-fill logic in a dedicated script `public/admin-assets/js/admin-login.js` loaded via defer on the login page.
- **Rationale:** Keeps Blade templates clean, adheres to existing project standards (`admin-sidebar.js`, `admin-search.js`), and ensures zero inline scripts.

## Risks / Trade-offs

- **[Risk] Existing developer bookmarks directly to `/admin/dashboard` fail:**
  - *Mitigation:* Ensure `LoginController` redirects to `redirect()->intended(route('admin.dashboard'))` so once authenticated, the developer lands directly on their desired page.
- **[Risk] Missing seeded admin user blocks local testing:**
  - *Mitigation:* Add a dedicated `AdminUserSeeder` and include an unobtrusive "Quick Fill" button in local environments that fills credentials into the input fields.
- **[Risk] Mobile viewport cramping on split screen:**
  - *Mitigation:* Employ a responsive breakpoint (`hidden lg:flex` on the showcase column) so mobile and tablet screens render a focused, centered authentication card without horizontal overflow.

## Migration Plan

1. Create `AdminUserSeeder` or update `DatabaseSeeder` to guarantee an administrator record exists (`admin@educore.test` / `password`).
2. Run database migrations and seeders (`php artisan db:seed`).
3. Deploy new controllers, requests, views, and routes.
4. Verify `/admin/dashboard` redirects unauthenticated requests to `/admin/login`.
5. Rollback strategy: In case of authentication issues, routes in `routes/admin.php` can temporarily omit `auth` middleware while issues are diagnosed.
