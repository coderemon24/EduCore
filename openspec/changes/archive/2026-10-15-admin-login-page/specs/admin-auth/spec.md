# admin-auth Specification Delta

## Purpose
Provides dedicated administrative authentication, secure session lifecycle management, institutional login gateway interfaces, brute-force rate limiting, and route protection for the EduCore School Management System.

## ADDED Requirements

### Requirement: Dedicated Administrative Login Gateway Interface
The application MUST provide a dedicated, modern, premium login interface at `/admin/login` specifically designed for administrative personnel, strictly adhering to institutional "no-slop" UI standards without generic AI templates or decorative fluff.

#### Scenario: Administrative login page presentation
- **WHEN** an unauthenticated administrator navigates to `/admin/login`
- **THEN** the system renders a high-contrast, institutional login interface with HTTP 200
- **AND** the layout features an asymmetric split structure presenting school institutional branding ("EduCore Academy") on the left and a crisp authentication form on the right
- **AND** the UI contains zero neon/purple gradients, zero floating blurred glassmorphism cards, zero sparkle icons (`✨`), and zero vacuous low-density spacers.

#### Scenario: Interactive form controls and accessible micro-interactions
- **WHEN** the user interacts with form inputs
- **THEN** email and password inputs display crisp 1px borders with subtle, high-contrast focus rings (`ring-2 ring-indigo-500/20 border-indigo-500`)
- **AND** the password field includes an accessible show/hide password toggle button with clean SVG eye icons
- **AND** a "Remember Me" checkbox is available for session persistence.

#### Scenario: Quick-fill development and demonstration helper
- **WHEN** running in local or staging environment
- **THEN** the login page provides a subtle, professional "Quick Fill Credentials" helper chip or button that populates default administrative credentials (`admin@educore.test` / `password`) without cluttering production aesthetics.

---

### Requirement: Administrative Credential Verification and Session Management
The application MUST securely authenticate administrators against the database, regenerate session tokens to prevent fixation attacks, and route authenticated sessions to the admin dashboard.

#### Scenario: Successful administrator login
- **WHEN** valid administrative credentials are submitted to `POST /admin/login`
- **THEN** the system authenticates the user via `Auth::attempt()`
- **AND** the session is regenerated using `request()->session()->regenerate()`
- **AND** the user is redirected to their intended administrative route or `/admin/dashboard`.

#### Scenario: Invalid credentials submission
- **WHEN** invalid credentials are submitted to `POST /admin/login`
- **THEN** the request fails validation and redirects back to `/admin/login`
- **AND** the email input preserves the submitted email value (without password)
- **AND** a crisp, clear error message ("These credentials do not match our records.") is displayed above the form or on the input field.

#### Scenario: Administrator logout execution
- **WHEN** an authenticated administrator sends a `POST /admin/logout` request with valid CSRF token
- **THEN** the application terminates the authenticated session via `Auth::logout()`
- **AND** invalidates the current session and regenerates the CSRF token
- **AND** redirects the administrator back to `/admin/login` with a clean session termination notice.

---

### Requirement: Brute-Force Rate Limiting and Security Throttling
Administrative authentication routes MUST prevent brute-force attacks by enforcing IP- and credential-based rate limiting on login attempts.

#### Scenario: Excessive failed login attempts trigger throttle lockout
- **WHEN** 5 consecutive failed login attempts occur within 1 minute from the same IP and email combination
- **THEN** subsequent login attempts are blocked immediately with HTTP 429 / validation error
- **AND** the response specifies the number of seconds the administrator must wait before attempting to sign in again.

---

### Requirement: Administrative Route Guarding and Guest Redirection
All administrative routes under the `/admin` prefix (except login routes) MUST require an active authenticated administrative session.

#### Scenario: Unauthenticated visitor accessing admin dashboard
- **WHEN** an unauthenticated visitor attempts to access `/admin/dashboard` or any protected admin route
- **THEN** the application intercepts the request and redirects to `route('admin.login')`
- **AND** stores the intended URL in session so the user returns to it upon successful authentication.

#### Scenario: Authenticated administrator accessing login page
- **WHEN** an already authenticated administrator visits `/admin/login`
- **THEN** the system detects the active session and redirects immediately to `route('admin.dashboard')`.
