# Design: Admin Model, Custom Guard Authentication & Minimalist School Login

## Context

The application currently authenticates administrative users through Laravel's default `users` table and `web` session guard. For the EduCore School Management System, administrative staff have distinct requirements from general portal users. They require unique attributes (`username`, `full_name`, `bio`, `designation`, `role`, `profile_image`, `cover_image`) and multi-identifier login (either username or institutional email).

Additionally, the existing login interface contains AI-generated decorative fluff ("slop"), excessive text, and pseudo-telemetry blocks that detract from a focused, professional school administrative gateway.

See `proposal.md` for background motivation and `specs/admin-auth/spec.md` for acceptance requirements.

## Goals / Non-Goals

**Goals:**
- Provide a dedicated `admins` database table and `Admin` Authenticatable model.
- Configure a first-class `admin` authentication guard and `admins` provider in `config/auth.php`.
- Create a dedicated `AdminAuthentication` middleware to protect `/admin/*` routes.
- Support dual-identifier authentication (`username` OR `email` + `password`).
- Populate rich admin account data via `AdminUserSeeder`.
- Streamline `resources/views/admin/auth/login.blade.php` to a minimalist, high-clarity layout featuring school management iconography on the left and a distraction-free authentication card on the right.
- Align the admin layout header (`navbar.blade.php`) to display authenticated `Admin` model attributes (`full_name`, `designation`, `avatar`).

**Non-Goals:**
- Modifying student or parent authentication portals (this is strictly for administrative users).
- Introducing multi-factor SMS/TOTP authentication in this iteration.
- Modifying core academic scheduling, grading, or fee modules.

## Decisions

### 1. Dedicated `admins` Table & `Admin` Model
- **Choice**: Create a new migration for `admins` and model `App\Models\Admin` extending `Illuminate\Foundation\Auth\User`.
- **Schema Columns**:
  - `id`: bigIncrements
  - `username`: string, unique, indexed
  - `email`: string, unique, indexed
  - `password`: string
  - `full_name`: string
  - `bio`: text, nullable
  - `designation`: string, nullable
  - `role`: string, default `'member'`
  - `profile_image`: string, nullable
  - `cover_image`: string, nullable
  - `remember_token`: nullable
  - `timestamps`
- **Alternatives Considered**: Adding columns directly to `users` table with a `role` check. Rejected because school administrators need an independent entity lifecycle, separate session persistence, and distinct profile configurations without cluttering student/parent/user tables.

### 2. Multi-Identifier Resolution in `LoginRequest`
- **Choice**: The login form provides a single credential field named `login`. In `LoginRequest`, inspect whether the input is a valid email using `filter_var($login, FILTER_VALIDATE_EMAIL)`. If true, authenticate using `['email' => $login, 'password' => $password]`; otherwise authenticate using `['username' => $login, 'password' => $password]`.
- **Rate Limiting**: Throttle key is derived from `Str::lower($this->input('login')) . '|' . $this->ip()`, preventing brute-force attacks across both identifier formats.
- **Alternatives Considered**: Requiring two separate form fields for email and username. Rejected because unified credential input offers a cleaner, frictionless user experience.

### 3. Custom `AdminAuthentication` Middleware and Guard Setup
- **Choice**:
  - Configure `'admin'` guard with `'driver' => 'session'` and `'provider' => 'admins'` in `config/auth.php`.
  - Implement `App\Http\Middleware\AdminAuthentication` that verifies `Auth::guard('admin')->check()`. If unauthenticated, redirect to `route('admin.login')`.
  - In `bootstrap/app.php`, register alias `admin.auth` and configure guest redirection for admin routes.
  - In `routes/admin.php`, replace standard `middleware('auth')` with `middleware('admin.auth')` or `middleware('auth:admin')`.
- **Alternatives Considered**: Relying purely on `auth:admin`. A dedicated `AdminAuthentication` middleware provides explicit control over redirection, session message handling, and future permission checks without conflicting with global web middleware.

### 4. Minimalist School-Themed UI (De-Slopping)
- **Choice**:
  - Left panel: Remove arbitrary grid overlays, fake uptime meters ("99.98%"), and verbose governance bullet boxes. Replace with a focused School Management System identity: high-contrast institutional navy/slate palette, school crest/emblem, clear headline ("EduCore School Management"), institutional badge, and an authentic school/education vector graphic.
  - Right panel: Compact, centered card containing the credential input (`login`), password input with eye toggle, "Remember me", submit button, and a clean dev-mode quick-fill chip.
- **Alternatives Considered**: Single-column centered form without left panel. Retaining an asymmetric split view preserves school branding while eliminating all decorative slop and unnecessary text.

### 5. Seeder Customization
- **Choice**: Update `Database\Seeders\AdminUserSeeder` to populate the `admins` table with:
  - `username`: `'admin'`
  - `email`: `'admin@educore.test'` (or `'admin@school.test'`)
  - `full_name`: `'System Administrator'`
  - `bio`: `'Lead Administrator for EduCore School Management Portal'`
  - `designation`: `'Head Administrator'`
  - `role`: `'super_admin'` (or `'admin'`)
  - `password`: `'password'` (hashed)
  - `profile_image`: `'admin-assets/images/avatar-default.png'` (or clean asset path)
  - `cover_image`: null

## Risks / Trade-offs

- **[Risk] Existing session invalidation for users logged in via `web` guard**:
  - *Mitigation*: The `admins` table and `admin` guard are isolated. Calling `Auth::guard('admin')->check()` will cleanly prompt existing sessions to log into the new admin guard.
- **[Risk] Navbar user details throwing null pointer if expecting `User` model**:
  - *Mitigation*: Update `navbar.blade.php` to access `Auth::guard('admin')->user()` with fallback properties (`full_name`, `designation`, `username`).
- **[Risk] Username vs Email collision or casing issues**:
  - *Mitigation*: Force lower-case trimming on username/email validation and index both columns in the migration.

## Migration Plan

1. Create migration `create_admins_table.php` and run migration.
2. Create `App\Models\Admin.php` model.
3. Update `config/auth.php` guards and providers.
4. Create `App\Http\Middleware\AdminAuthentication.php` and register alias in `bootstrap/app.php`.
5. Update `App\Http\Requests\Admin\LoginRequest.php` for username/email multi-identifier support.
6. Update `App\Http\Controllers\Admin\Auth\LoginController.php` to use `admin` guard.
7. Update `routes/admin.php` route middleware.
8. Customize `database/seeders/AdminUserSeeder.php` and seed database.
9. Redesign `resources/views/admin/auth/login.blade.php` to remove AI slop and introduce school graphic.
10. Update `resources/views/layouts/admin-partial/navbar.blade.php` for `Admin` model attributes.
11. Test authentication with both username and email, logout, and route protection.
