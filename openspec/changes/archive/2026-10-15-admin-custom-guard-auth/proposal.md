# Proposal: Admin Model, Custom Guard Authentication & Minimalist School Login

## Why

The administrative portal currently utilizes the default `users` table, standard `web` session guard, and generic user model for administrative authentication. In an institutional School Management System (EduCore), administrative personnel (principals, deans, system administrators, registrars) require an isolated entity domain with dedicated profile metadata (`username`, `designation`, `bio`, `role`, `profile_image`, `cover_image`) distinct from standard users, students, or guardians.

Furthermore:
1. Administrators must be able to authenticate flexibly using either their **username** or **email** address alongside their password.
2. Route protection requires an explicit, dedicated `admin` guard and custom `AdminAuthentication` middleware to prevent session bleeding between different portal domains.
3. The existing login interface contains excessive AI-generated boilerplate text, verbose status pillars, and generic decorations ("AI slop") that distract from the primary authentication workflow. A clean, minimalist layout with high focus on the login form and an authentic School Management System brand/graphic on the left side is needed.

## What Changes

1. **Database & Schema**:
   - Create a dedicated migration for the `admins` table containing: `username`, `email`, `password`, `full_name`, `bio`, `designation`, `role` (default: `'member'`), `profile_image`, `cover_image`, `remember_token`, and timestamps.
2. **Eloquent Model**:
   - Create `App\Models\Admin` implementing `Illuminate\Foundation\Auth\User as Authenticatable`, with appropriate `$fillable`, `$hidden`, and attribute casts.
3. **Authentication Guard Configuration**:
   - Configure a dedicated `admin` guard in `config/auth.php` using the session driver and an `admins` Eloquent provider pointing to `App\Models\Admin`.
4. **Custom Middleware**:
   - Create `AdminAuthentication` middleware (e.g. `App\Http\Middleware\AdminAuthentication` or registered in `bootstrap/app.php`) to enforce the `admin` guard for protected administrative routes, gracefully redirecting unauthenticated guests to `route('admin.login')`.
   - Update guest redirection for admin login routes to check the `admin` guard.
5. **Controller & Request Login Logic**:
   - Update `App\Http\Requests\Admin\LoginRequest` to accept `login` (or `username_or_email`) allowing authentication via either username or email.
   - Update `App\Http\Controllers\Admin\Auth\LoginController` to authenticate against `Auth::guard('admin')` and logout from the `admin` guard.
6. **Admin Seeder Customization**:
   - Update `Database\Seeders\AdminUserSeeder` (and main `DatabaseSeeder`) to seed sample administrator records with complete profile attributes (username, email, full_name, bio, designation, role, profile_image, cover_image).
7. **Login UI Fixing (De-Slopping)**:
   - Strip out redundant buzzwords, excessive narrative paragraphs, and verbose telemetry cards from `resources/views/admin/auth/login.blade.php`.
   - Implement a clean, minimalist split layout featuring an institutional School Management System graphic/crest on the left side and a high-focus, accessible login form on the right.
   - Adjust input labels and placeholders to reflect username/email login.

## Capabilities

### Modified Capabilities
- `admin-auth`: Update administrative authentication requirements to mandate the `admins` table, `Admin` model, `admin` custom guard, `AdminAuthentication` middleware, flexible identifier (username or email) authentication, comprehensive administrator seeding, and a minimalist, zero-slop school login interface.

## Impact

- **Database**: Adds `admins` table. Existing migrations remain intact.
- **Configuration**: Modifies `config/auth.php` to register `admin` guard and `admins` provider.
- **Middleware & Routing**: Replaces default `auth` middleware with custom `AdminAuthentication` middleware on `routes/admin.php` and updates `bootstrap/app.php`.
- **Views**: Updates `resources/views/admin/auth/login.blade.php` and associated frontend assets for a minimalist, school-focused presentation.
- **Controllers & Requests**: `LoginController` and `LoginRequest` updated to use `admin` guard and dual identifier lookup.
- **Layout / Navbar**: Navbar user dropdown updated to inspect `Auth::guard('admin')->user()`.
