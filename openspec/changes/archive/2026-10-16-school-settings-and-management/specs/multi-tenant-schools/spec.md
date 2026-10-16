# Spec Delta

## ADDED Requirements

### Requirement: Super Admin Multi-School Directory Interface
The application MUST provide a dedicated administrative school directory at `GET /admin/schools` displaying all academic institutions across the platform, restricted to users with the `super_admin` role.

#### Scenario: Super Admin views school listing
- **WHEN** an authenticated user with `role === 'super_admin'` visits `/admin/schools`
- **THEN** the system displays a table listing all registered schools including institution name, unique code, contact details, established year, status badge, and created date with pagination.

#### Scenario: Status filter navigation
- **WHEN** the Super Admin selects a status filter tab (`all`, `active`, `pending`, `suspended`, `inactive`)
- **THEN** the table reloads showing only institutions matching the selected status parameter.

#### Scenario: Non-super-admin access restriction
- **WHEN** an authenticated administrator whose role is not `super_admin` attempts to access `/admin/schools`
- **THEN** the system denies access with HTTP 403 Forbidden.

---

### Requirement: Super Admin School Detailed Inspection
The application MUST provide a detailed institutional profile view at `GET /admin/schools/{school}` displaying comprehensive institutional metadata and operational health.

#### Scenario: Inspect school profile details
- **WHEN** a Super Admin visits `/admin/schools/{school}`
- **THEN** the system displays the complete school details including institutional identity, contact information, regional localization settings (timezone, currency), and affiliated user accounts summary.

#### Scenario: Missing school record
- **WHEN** accessing a school ID or slug that does not exist or has been permanently removed
- **THEN** the system returns HTTP 404 Not Found.

---

### Requirement: Super Admin School Status Lifecycle Control
The application MUST provide an endpoint `PATCH /admin/schools/{school}/status` allowing Super Administrators to update an institution's operational status.

#### Scenario: Transition school status
- **WHEN** a Super Admin submits a new valid status (`active`, `inactive`, `suspended`) for an institution
- **THEN** the system updates the `status` column on the `schools` table
- **AND** returns a success response with feedback for SweetAlert2 display.

#### Scenario: Invalid status rejection
- **WHEN** a request contains an unsupported status string outside the `SchoolStatus` enum
- **THEN** the system rejects the update with HTTP 422 Unprocessable Entity.
