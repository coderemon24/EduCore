# Tasks: Dedicated Admin Authentication and Premium Login Experience

## 1. Database & User Seeding

- [x] 1.1 Verify and create `database/seeders/AdminUserSeeder.php` ensuring a default administrator record exists (`admin@educore.test` / `password`) with name "System Administrator".
- [x] 1.2 Wire `AdminUserSeeder` into `database/seeders/DatabaseSeeder.php` and execute seeder via `php artisan db:seed --class=AdminUserSeeder`.

## 2. Controllers & Authentication Logic

- [x] 2.1 Create `app/Http/Requests/Admin/LoginRequest.php` with email and password validation rules, rate-limiting throttle key generation (`throttleKey()`), `RateLimiter::hit()`, `RateLimiter::clear()`, and lockout exception handling.
- [x] 2.2 Create `app/Http/Controllers/Admin/Auth/LoginController.php` implementing:
  - `create()`: Render `admin.auth.login` view.
  - `store(LoginRequest $request)`: Authenticate credentials via `Auth::attempt()`, regenerate session via `request()->session()->regenerate()`, and redirect to `redirect()->intended(route('admin.dashboard'))`.
  - `destroy(Request $request)`: Invalidate session, regenerate CSRF token, and redirect to `route('admin.login')`.

## 3. Routing & Route Guarding

- [x] 3.1 Update `routes/admin.php` to partition administrative routes into:
  - Guest group (`middleware('guest')`): `GET /admin/login` (`admin.login`) and `POST /admin/login` (`admin.login.store`).
  - Authenticated group (`middleware('auth')`): `POST /admin/logout` (`admin.logout`) and `GET /admin/dashboard` (`admin.dashboard`).
- [x] 3.2 Ensure unauthenticated requests to protected admin routes redirect to `route('admin.login')` with intended URL preserved.

## 4. Layout & Blade Architecture (Premium No-Slop UI)

- [x] 4.1 Create `resources/views/admin/auth/login.blade.php` structured as an asymmetric split layout:
  - Institutional Showcase Column: Deep slate `#0F172A` tone, architectural line geometry, institutional crest/logo, system status indicator ("Operational // 99.98% Telemetry", "256-bit Session Security"), and strict absence of AI slop (no generic purple neon gradients, no arbitrary sparkles `✨`, no low-density empty heroes).
  - Authentication Console Column: High-contrast white card with 1px border (`border-slate-200`), clear typographic hierarchy with `Plus Jakarta Sans`, accessible email and password inputs with crisp focus rings, "Remember Me" checkbox, and solid indigo CTA button (`bg-indigo-600 hover:bg-indigo-700`).
- [x] 4.2 Implement accessible password show/hide toggle with clean SVG icons (eye / eye-slash) and keyboard accessibility (`type="button"`).
- [x] 4.3 Add a subtle "Quick Fill Credentials" helper chip in local environment (`app()->environment('local')`) to speed up administrative testing.
- [x] 4.4 Update `resources/views/layouts/admin-partial/navbar.blade.php` to wire the profile dropdown logout button to submit a `POST` form to `route('admin.logout')`.

## 5. Frontend Assets, Modular JS & Verification

- [x] 5.1 Create `public/admin-assets/js/admin-login.js` containing modular password visibility toggling and demo fill handlers without inline script bloat.
- [x] 5.2 Validate frontend compilation with Vite (`npm run build`) and format backend PHP code with Laravel Pint (`vendor/bin/pint`).
- [x] 5.3 Verify all authentication scenarios:
  - Unauthenticated access to `/admin/dashboard` redirects to `/admin/login`.
  - Valid administrator credentials authenticate and redirect to `/admin/dashboard`.
  - Invalid credentials display clean inline error feedback.
  - 5 rapid failed attempts trigger rate-limiting lockout message.
  - Logging out invalidates session and redirects back to `/admin/login`.
