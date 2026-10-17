# admin-auth Specification Delta

## MODIFIED Requirements

### Requirement: Administrative Credential Verification and Session Management
The application MUST securely authenticate administrators and school personnel using a multi-guard authentication flow: attempting the `admin` guard backed by `admins` for platform Super Admins first, and the `web` guard backed by `users` for school-level personnel with active status validation and tenant resolution, supporting login via either username or email.

#### Scenario: Successful login using email or username
- **WHEN** an administrator submits either their valid `email` or `username` along with their password to `POST /admin/login`
- **THEN** the application verifies credentials using `Auth::guard('admin')->attempt(...)`
- **AND** regenerates the session via `request()->session()->regenerate()`
- **AND** redirects the administrator to their intended administrative route or `/admin/dashboard`.

#### Scenario: Failed authentication with multi-identifier
- **WHEN** an administrator or school user submits invalid credentials (wrong username/email or wrong password)
- **THEN** authentication fails and the request redirects back to `/admin/login`
- **AND** the submitted username/email input is preserved with a clear validation error message
- **AND** rate limiting increments for the IP and identifier combination.

#### Scenario: Administrator logout execution
- **WHEN** an authenticated administrator sends a `POST /admin/logout` request with valid CSRF token
- **THEN** the application terminates the session via `Auth::guard('admin')->logout()`
- **AND** invalidates the session and regenerates the CSRF token
- **AND** redirects the user back to `/admin/login` with a clean status message.

#### Scenario: School personnel login with active status and tenant binding
- **WHEN** a school user submits valid credentials (email and password) to `POST /admin/login`
- **THEN** the application verifies credentials using `Auth::guard('web')->attempt(...)`
- **AND** confirms the user's `status` is `'active'` (rejecting inactive accounts with a validation error)
- **AND** resolves the user's `school_id` and binds it into `session(['current_school_id' => $user->school_id])` and `app()->instance('current_school_id', $user->school_id)`
- **AND** regenerates the session and redirects to `/admin/dashboard`.

#### Scenario: Inactive or suspended user login attempt
- **WHEN** a school user whose account `status` is `'inactive'` or `'suspended'` attempts to sign in
- **THEN** authentication is rejected immediately
- **AND** the response returns a validation error: "Your account is currently inactive. Please contact your school administrator."

#### Scenario: Multi-guard logout execution
- **WHEN** an authenticated user (either Super Admin or school user) sends `POST /admin/logout`
- **THEN** both `admin` and `web` guards are logged out
- **AND** the session is invalidated and token regenerated
- **AND** the user is redirected to `route('admin.login')`.

---

### Requirement: Administrative Route Guarding and Guest Redirection
All administrative routes under the `/admin` prefix (except guest login routes) MUST require an active administrative session enforced by `AdminAuthentication` middleware inspecting either the `admin` guard or the `web` guard with a valid tenant school.

#### Scenario: Unauthenticated access blocked by AdminAuthentication middleware
- **WHEN** an unauthenticated visitor attempts to access any protected `/admin/*` route
- **THEN** the `AdminAuthentication` middleware intercepts the request
- **AND** redirects the visitor to `route('admin.login')` with intended URL stored in session.

#### Scenario: Authenticated administrator accessing guest login route
- **WHEN** an administrator authenticated via the `admin` guard or a school user authenticated via `web` guard visits `/admin/login`
- **THEN** the system redirects immediately to `route('admin.dashboard')`.

#### Scenario: Accessing admin routes as authenticated school user
- **WHEN** an authenticated school user with active `web` guard accesses `/admin/dashboard`
- **THEN** `AdminAuthentication` middleware allows the request
- **AND** initializes `current_school_id` from the session if not already bound
- **AND** renders the school-scoped dashboard without exposing platform Super Admin controls.

#### Scenario: School user attempting Super Admin routes is forbidden
- **WHEN** an authenticated school user visits `GET /admin/schools`
- **THEN** `EnsureSuperAdmin` middleware intercepts the request
- **AND** returns HTTP 403 Unauthorized.
