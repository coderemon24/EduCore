# client-management Specification

## Purpose
The client management capability allows administrators to manage partner brands and clients via a dedicated admin interface, enabling single logo uploads, real-time filtering, and dynamic delivery to the homepage carousel without code modification.

## Requirements

### Requirement: Database Schema and Eloquent Model
The system SHALL persist client records in a dedicated `clients` database table with appropriate indexing, constraints, and Eloquent helper scopes.

#### Scenario: Migration runs successfully
- **WHEN** the migration `create_clients_table` is executed
- **THEN** a table named `clients` is created containing `id`, `client_name` (string, required), `client_logo` (string, nullable), `label` (string, required), `our_solution` (text, required), `link` (string, nullable), `is_active` (boolean, default true), `order` (integer, default 0), and timestamps, with indexes on `client_name`, `label`, `is_active`, and `order`.

#### Scenario: Model query scopes and accessors
- **WHEN** querying `App\Models\Client::active()->ordered()`
- **THEN** only records with `is_active = true` are returned, sorted in ascending order by `order`, and any record with `client_logo` resolves `logo_url` pointing to the public storage path or fallback asset.

---

### Requirement: Administrative Client Listing, Search, and Pagination
The admin dashboard SHALL provide a dedicated client management page accessible from the sidebar menu under "Agency & Content", displaying existing clients in a paginated table with real-time search.

#### Scenario: Accessing the clients list
- **WHEN** an authenticated administrator navigates to `/admin/clients`
- **THEN** the page displays a responsive data table of clients showing each client's logo thumbnail, client name, industry label, solution summary, link status, active state badge, and action buttons (Edit, Delete).

#### Scenario: Real-time search filtering
- **WHEN** the administrator types keywords into the table search input
- **THEN** the table immediately filters client rows matching the search term against `client_name` or `label` without requiring a full manual page refresh.

#### Scenario: Navigating paginated clients
- **WHEN** the client collection exceeds the configured items-per-page limit (10 items)
- **THEN** standard pagination links are rendered at the bottom of the table allowing seamless navigation between pages while preserving active search query strings.

---

### Requirement: Client Creation via Modal Interface
The admin dashboard SHALL allow creating new client partners through a Bootstrap 5 modal form with client-side and server-side validation.

#### Scenario: Successfully creating a client
- **WHEN** the administrator clicks "Add Client", enters a valid client name, label, solution description, optional external URL, uploads a logo file (PNG, JPG, SVG, WebP up to 2MB), and submits
- **THEN** the logo is securely saved under `public/uploads/clients/`, a new `clients` record is created, the modal closes, a Toastr success notification is displayed, and the table updates.

#### Scenario: Client creation validation failure
- **WHEN** the administrator submits the creation form with missing required fields (e.g. empty `client_name` or invalid URL format)
- **THEN** the submission is rejected with HTTP 422 / validation error messages highlighted on the respective form fields without closing the modal.

---

### Requirement: Client Modification via Modal Interface
The admin dashboard SHALL allow editing existing client details and optionally replacing their uploaded logo through a Bootstrap 5 modal form.

#### Scenario: Editing client details without changing logo
- **WHEN** the administrator clicks "Edit" on a client, modifies the `our_solution` text, and submits without selecting a new logo file
- **THEN** the client record is updated in the database, the existing logo file remains intact, and a Toastr success message confirms the update.

#### Scenario: Replacing client logo
- **WHEN** the administrator uploads a new logo file during edit
- **THEN** the new file is stored in `public/uploads/clients/`, the prior logo file is removed from disk to prevent orphaned files, the database reference is updated, and the new logo is displayed immediately.

---

### Requirement: Client Deletion and Storage File Cleanup
The admin dashboard SHALL provide safe deletion of client records with interactive confirmation and automated file cleanup.

#### Scenario: Deleting a client record
- **WHEN** the administrator clicks the "Delete" button for a client and confirms the SweetAlert2 prompt
- **THEN** the associated logo file is removed from disk, the record is removed from the `clients` table, the table refreshes, and a Toastr confirmation is displayed.

#### Scenario: Cancelling client deletion
- **WHEN** the administrator clicks "Cancel" on the SweetAlert2 confirmation dialog
- **THEN** no deletion occurs and the client record remains unaffected.

---

### Requirement: Frontend Dynamic Homepage Integration
The homepage client ecosystem section SHALL render active clients dynamically from the database using server-side Blade rendering while preserving the interactive marquee carousel and visual styling.

#### Scenario: Homepage renders active clients
- **WHEN** a public visitor accesses the homepage (`/`)
- **THEN** `HomeController@index` supplies all active clients ordered by `order`, and the `#km-clients-track` carousel renders the dynamic cards across track sets with the client logo, name, label, solution description, and the static "Verified" badge.

#### Scenario: Handling clients with external links
- **WHEN** a client record includes a valid external URL in `link`
- **THEN** the client card on the homepage includes an interactive clickable link/button opening the destination in a new secure tab (`target="_blank" rel="noopener noreferrer"`).

#### Scenario: Dynamic carousel track loop calculation
- **WHEN** the homepage loads with an arbitrary count of active clients (e.g. 5, 8, or 12 clients)
- **THEN** `public/assets/js/animations.js` derives the unique card count dynamically from the rendered DOM cards instead of using a hardcoded `8`, ensuring seamless infinite looping, drag exploration, and hover pause behavior.
