# admin-auth Specification

## Purpose
Provides dedicated administrative authentication, secure session lifecycle management, institutional login gateway interfaces, brute-force rate limiting, and route protection for the EduCore School Management System.

## Requirements

### Requirement: Dedicated Administrative Login Gateway Interface
The application MUST provide a clean, minimalist administrative login interface at `/admin/login` specifically tailored for School Management System administrators, eliminating verbose AI-generated marketing copy, decorative badges, and bloated telemetry cards while emphasizing authentication efficiency and school institutional identity.

#### Scenario: Minimalist login interface presentation
- **WHEN** an unauthenticated administrator navigates to `/admin/login`
- **THEN** the system renders a clean, focused login page with HTTP 200
- **AND** the left column features school institutional imagery or education-themed iconography representing the School Management System (EduCore) without distracting fluff
- **AND** the right column features a concise authentication form accepting either username or email and password
- **AND** zero neon gradients, zero generic AI marketing cards, and zero low-density decorative fluff are rendered.

#### Scenario: Multi-identifier credential input
- **WHEN** the administrator interacts with the credential input field
- **THEN** the input accepts either a valid `username` or `email` address
- **AND** placeholder text clearly indicates "Username or institutional email"
- **AND** crisp focus styling and accessible password visibility toggle remain available.

---

### Requirement: Administrative Credential Verification and Session Management
The application MUST securely authenticate administrators using the dedicated `admin` authentication guard backed by the `admins` table and `Admin` model, supporting login via either username or email.

#### Scenario: Successful login using email or username
- **WHEN** an administrator submits either their valid `email` or `username` along with their password to `POST /admin/login`
- **THEN** the application verifies credentials using `Auth::guard('admin')->attempt(...)`
- **AND** regenerates the session via `request()->session()->regenerate()`
- **AND** redirects the administrator to their intended administrative route or `/admin/dashboard`.

#### Scenario: Failed authentication with multi-identifier
- **WHEN** an administrator submits invalid credentials (wrong username/email or wrong password)
- **THEN** authentication fails and the request redirects back to `/admin/login`
- **AND** the submitted username/email input is preserved with a clear validation error message
- **AND** rate limiting increments for the IP and identifier combination.

#### Scenario: Administrator logout execution
- **WHEN** an authenticated administrator sends a `POST /admin/logout` request with valid CSRF token
- **THEN** the application terminates the session via `Auth::guard('admin')->logout()`
- **AND** invalidates the session and regenerates the CSRF token
- **AND** redirects the user back to `/admin/login` with a clean status message.

---

### Requirement: Brute-Force Rate Limiting and Security Throttling
Administrative authentication routes MUST prevent brute-force attacks by enforcing IP- and credential-based rate limiting on login attempts.

#### Scenario: Excessive failed login attempts trigger throttle lockout
- **WHEN** 5 consecutive failed login attempts occur within 1 minute from the same IP and email combination
- **THEN** subsequent login attempts are blocked immediately with HTTP 429 / validation error
- **AND** the response specifies the number of seconds the administrator must wait before attempting to sign in again.

---

### Requirement: Administrative Route Guarding and Guest Redirection
All administrative routes under the `/admin` prefix (except guest login routes) MUST require an active administrative session enforced by a custom `AdminAuthentication` middleware inspecting the `admin` guard.

#### Scenario: Unauthenticated access blocked by AdminAuthentication middleware
- **WHEN** an unauthenticated visitor attempts to access any protected `/admin/*` route
- **THEN** the `AdminAuthentication` middleware intercepts the request
- **AND** redirects the visitor to `route('admin.login')` with intended URL stored in session.

#### Scenario: Authenticated administrator accessing guest login route
- **WHEN** an administrator authenticated via the `admin` guard visits `/admin/login`
- **THEN** the system redirects immediately to `route('admin.dashboard')`.

---

### Requirement: Dedicated Admins Table and Model Entity
The application MUST maintain an isolated `admins` database table and corresponding `App\Models\Admin` authenticatable Eloquent model to govern school administrative accounts independently from other user types.

#### Scenario: Admins table schema structure
- **WHEN** database migrations are executed
- **THEN** the `admins` table is created with the following columns:
  - `id` (bigint, auto-increment, primary key)
  - `username` (string, unique)
  - `email` (string, unique)
  - `password` (string)
  - `full_name` (string)
  - `bio` (text, nullable)
  - `designation` (string, nullable)
  - `role` (string, default `'member'`)
  - `profile_image` (string, nullable)
  - `cover_image` (string, nullable)
  - `remember_token` (string, nullable)
  - `created_at` and `updated_at` (timestamps).

#### Scenario: Admin authenticatable model configuration
- **WHEN** the `Admin` model is loaded
- **THEN** it extends `Illuminate\Foundation\Auth\User`
- **AND** hides `password` and `remember_token` from serialized output
- **AND** casts `password` to `'hashed'`.

---

### Requirement: Comprehensive Administrator Database Seeder
The application MUST provide a customized database seeder that populates default administrative records containing all schema fields.

#### Scenario: AdminUserSeeder execution
- **WHEN** `php artisan db:seed --class=AdminUserSeeder` is executed
- **THEN** an administrative account is created or updated in the `admins` table
- **AND** contains valid `username`, `email`, `password` (hashed), `full_name`, `bio`, `designation`, `role`, `profile_image`, and `cover_image` attributes.
