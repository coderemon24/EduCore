# Tasks: Admin Model, Custom Guard Authentication & Minimalist School Login

## 1. Models & Database Migrations

- [x] 1.1 Create migration for `admins` table containing `id`, `username` (unique), `email` (unique), `password`, `full_name`, `bio` (nullable), `designation` (nullable), `role` (default `'member'`), `profile_image` (nullable), `cover_image` (nullable), `remember_token`, and timestamps.
- [x] 1.2 Create `App\Models\Admin` model extending `Illuminate\Foundation\Auth\User as Authenticatable` with `$fillable`, `$hidden` (`password`, `remember_token`), and password hashing cast.
- [x] 1.3 Customize `Database\Seeders\AdminUserSeeder` to seed default administrator with complete schema fields (username, email, password, full_name, bio, designation, role, profile_image, cover_image) and update `DatabaseSeeder`.
- [x] 1.4 Execute `php artisan migrate` and verify the `admins` table schema.

## 2. Authentication Guard & Middleware Configuration

- [x] 2.1 Update `config/auth.php` to register `'admin'` guard with `'driver' => 'session'` and `'provider' => 'admins'`, and configure `'admins'` provider with `App\Models\Admin::class`.
- [x] 2.2 Create `App\Http\Middleware\AdminAuthentication` to verify `Auth::guard('admin')->check()` and redirect unauthenticated requests to `route('admin.login')` while storing the intended URL.
- [x] 2.3 Register middleware alias `admin.auth` in `bootstrap/app.php` and configure guest/user redirections for admin portal.
- [x] 2.4 Update `routes/admin.php` to use `admin.auth` for protected routes and guard guest login routes against authenticated admins.

## 3. Controllers & Authentication Logic

- [x] 3.1 Update `App\Http\Requests\Admin\LoginRequest` to accept single `login` credential (supporting username or email format), detect field type with `filter_var()`, authenticate using `Auth::guard('admin')->attempt()`, and throttle failed attempts.
- [x] 3.2 Update `App\Http\Controllers\Admin\Auth\LoginController` `store()` method to invoke request authentication, regenerate session, and redirect to intended dashboard.
- [x] 3.3 Update `App\Http\Controllers\Admin\Auth\LoginController` `destroy()` method to call `Auth::guard('admin')->logout()`, invalidate session, and regenerate token.

## 4. Blade Views & Minimalist School-Themed UI Fixing

- [x] 4.1 Refactor `resources/views/admin/auth/login.blade.php` to remove AI slop (verbose marketing text, fake telemetry indicators, low-density cards).
- [x] 4.2 Build a clean, institutional School Management System left showcase with crisp school crest/emblem, school governance title, and authentic educational visual motif.
- [x] 4.3 Refactor the login form on the right to focus strictly on authentication: multi-identifier input ("Username or Institutional Email"), password with toggle, remember me checkbox, clear submit button, and subtle local dev quick-fill button.
- [x] 4.4 Update `resources/views/layouts/admin-partial/navbar.blade.php` and sidebar to consume authenticated admin details from `Auth::guard('admin')->user()` (`full_name`, `designation`, `username`).

## 5. Verification & Testing

- [x] 5.1 Run `php artisan db:seed --class=AdminUserSeeder` and verify admin record creation in `admins` table.
- [x] 5.2 Verify login via email address (`admin@educore.test` / `password`).
- [x] 5.3 Verify login via username (`admin` / `password`).
- [x] 5.4 Verify failed login attempts display appropriate validation messages and rate limiter lockout.
- [x] 5.5 Verify unauthenticated access to `/admin/dashboard` is blocked and redirects to `/admin/login`.
- [x] 5.6 Verify logout redirects to `/admin/login` and terminates admin session.
- [x] 5.7 Run `vendor/bin/pint` to ensure code styling compliance across modified files.
