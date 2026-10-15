# admin-dashboard Specification Delta

## MODIFIED Requirements

### Requirement: Dedicated Admin Route File & Bootstrapping
The application MUST register and route administrative requests through a dedicated `routes/admin.php` file isolated from the default web route file, with administrative routes protected by authentication middleware.

#### Scenario: Admin routes registered in bootstrap/app.php
- **WHEN** the Laravel application boots
- **THEN** `routes/admin.php` is registered via the `then:` routing callback in `bootstrap/app.php` with web middleware
- **AND** all routes inside `routes/admin.php` are grouped under prefix `admin` and named route prefix `admin.`.

#### Scenario: Admin dashboard URL access
- **WHEN** an authenticated administrator visits `/admin/dashboard`
- **THEN** `App\Http\Controllers\Admin\AdminController@index` handles the request and returns HTTP 200 with the admin dashboard view
- **AND** unauthenticated visitors attempting to access `/admin/dashboard` MUST be redirected to `/admin/login`.
