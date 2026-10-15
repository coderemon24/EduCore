# Proposal

## Why
Currently, the KitoMark homepage showcases client brand cards inside an animated scroller carousel. However, these clients are hardcoded directly inside the Blade view (`frontend/index.blade.php`), and the JavaScript scroller (`animations.js`) relies on a hardcoded count (`uniqueCount = 8`).

To allow the administrative team to manage client entries dynamically—adding new enterprise clients, modifying existing details, uploading company logos, reordering, and removing old entries without code modifications or redeployments—we need a complete client management system in the admin dashboard and dynamic server-rendered presentation on the frontend homepage.

## What Changes
1. **Database Schema & Eloquent Model**:
   - Create `clients` database table with fields: `id`, `client_name`, `client_logo`, `label`, `our_solution`, `link`, `is_active`, `order`, and timestamps.
   - Include database indexing on `client_name`, `label`, `is_active`, and `order` for optimal search and retrieval query performance.
   - Implement `App\Models\Client` with fillable attributes, type casting, query scopes (`active()`, `ordered()`), and a convenient `logo_url` accessor.
   - Implement `database/seeders/ClientSeeder.php` pre-populated with the existing 8 static brands and their assets so existing content is preserved immediately upon migration.

2. **Admin Dashboard Client Management**:
   - Add a "Clients" navigation link to the admin sidebar ([layouts/admin-partial/sidebar.blade.php](file:///I:/KitoMark/kitomark/kito-app/resources/views/layouts/admin-partial/sidebar.blade.php)) under the "Agency & Content" section.
   - Implement `App\Http\Controllers\Admin\ClientController` to manage CRUD operations with validation, file upload handling to `public/uploads/clients`, and automatic deletion of old/orphan logo files.
   - Implement `resources/views/admin/clients/index.blade.php` with:
     - Responsive data table displaying client logo thumbnail, name, industry label, solution, external link, status badge, and action buttons.
     - Real-time client-side / AJAX search filtering as the admin types in the search field.
     - Server-side pagination controls.
     - Bootstrap 5 modal for adding new clients with live image preview.
     - Bootstrap 5 modal for editing existing clients with current logo preview and replacement.
     - SweetAlert2 prompt for deletion confirmation and Toastr feedback alerts.

3. **Frontend Dynamic Homepage Integration**:
   - Update `HomeController::index()` to query active clients (`Client::active()->ordered()->get()`) and pass them to `frontend.index`.
   - Update `resources/views/frontend/index.blade.php` to render client cards dynamically across the continuous track sets, preserving the static "Verified" badge, responsive layout, theme styling, and clickable external links when provided.
   - Update `public/assets/js/animations.js` so `uniqueCount` dynamically calculates from the DOM count of unique client cards rather than the hardcoded constant `8`.

## Capabilities

### New Capabilities
- `client-management`: Administrative CRUD management for client partners with image upload, real-time table search, and dynamic Blade server-rendered homepage scroller delivery.

### Modified Capabilities
<!-- None: This is a new capability in the OpenSpec registry. -->

## Impact
- **Database & Migrations**: Adds `clients` table.
- **Backend Application Code**:
  - New Model: `app/Models/Client.php`.
  - New Controller: `app/Http/Controllers/Admin/ClientController.php`.
  - Updated Controller: `app/Http/Controllers/HomeController.php`.
  - New Seeder: `database/seeders/ClientSeeder.php`.
  - Updated Routes: `routes/admin.php`.
- **Admin Views & UI**:
  - New View: `resources/views/admin/clients/index.blade.php`.
  - Updated Sidebar: `resources/views/layouts/admin-partial/sidebar.blade.php`.
- **Frontend Views & Assets**:
  - Updated View: `resources/views/frontend/index.blade.php`.
  - Updated JavaScript: `public/assets/js/animations.js`.
- **Dependencies**: No new Composer or NPM packages required. Utilizes existing Bootstrap 5, Boxicons, SweetAlert2, Toastr, and Axios libraries.
