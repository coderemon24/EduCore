# school-settings Specification

## Purpose
Provides institutional profile and operational configuration management for school administrators, enabling them to inspect and update school metadata, institutional contact details, regional localization, and school branding.

## Requirements

### Requirement: Institution Profile and Settings Interface
The application MUST provide an authenticated institutional settings page at `GET /admin/settings/school` that displays the current school's institutional profile with an editable form.

#### Scenario: Display current institution profile
- **WHEN** an authenticated administrator navigates to `/admin/settings/school`
- **THEN** the system renders the institutional settings view pre-populated with the current school's name, code, contact email, phone, address, city, state, country, postal code, website, established year, timezone, and currency.

#### Scenario: Unauthorized guest redirection
- **WHEN** an unauthenticated user attempts to access `/admin/settings/school`
- **THEN** the system redirects the user to the administrator login page (`/admin/login`).

---

### Requirement: Institution Settings Persistence and Validation
The application MUST validate and persist updates to the school's profile via `PUT /admin/settings/school`, ensuring data integrity and uniqueness where required.

#### Scenario: Successful profile update
- **WHEN** an administrator submits valid school profile data including updated name, email, phone, address, timezone, and currency
- **THEN** the system updates the `schools` table record for the institution
- **AND** redirects back to `/admin/settings/school` with a success alert
- **AND** the active school banner in the top navigation reflects the updated institution name.

#### Scenario: Validation failure on invalid data
- **WHEN** an administrator submits an invalid email format or empty required institution name
- **THEN** the system rejects the request with HTTP 422
- **AND** displays clear field-level validation error messages without saving invalid attributes.
