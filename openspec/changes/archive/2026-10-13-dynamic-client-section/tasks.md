# Tasks

## 1. Database & Migrations

- [x] 1.1 Create migration `create_clients_table.php` with columns `id`, `client_name`, `client_logo`, `label`, `our_solution`, `link`, `is_active`, `order`, timestamps, and performance indexes on `[is_active, order]`, `client_name`, and `label`.
- [ ] 1.2 Run database migration (`docker exec kito_app php artisan migrate`) and confirm the `clients` table exists in the database.
- [ ] 1.3 Create `database/seeders/ClientSeeder.php` with the 8 existing brand partners (Apex Capital, Nexus AI, Strata Cloud, Volt Dynamics, Lumina Health, Zenith Pay, Vanguard Media, Hyperion Labs) and register in `DatabaseSeeder.php`.
- [ ] 1.4 Execute `docker exec kito_app php artisan db:seed --class=ClientSeeder` and confirm seeded records.

## 2. Models & Relationships

- [ ] 2.1 Create Eloquent model `app/Models/Client.php` with mass assignment `$fillable` attributes, casting for `is_active` (boolean) and `order` (integer).
- [ ] 2.2 Add query scopes `scopeActive($query)` and `scopeOrdered($query)` in `app/Models/Client.php`.
- [ ] 2.3 Implement `getLogoUrlAttribute()` accessor in `app/Models/Client.php` providing resolved asset URL with fallback placeholder handling.

## 3. Admin Controllers & CRUD Views

- [ ] 3.1 Register resource routes for `ClientController` inside the `admin.auth` route group in `routes/admin.php`, plus optional status toggle route.
- [ ] 3.2 Create `app/Http/Controllers/Admin/ClientController.php` with full CRUD methods:
  - `index()`: Paginated listing with search support.
  - `store()`: Input validation, file upload to `public/uploads/clients/`, and record creation.
  - `update()`: Input validation, optional logo replacement with old file deletion, and record update.
  - `destroy()`: Image file unlinking and database record deletion.
- [ ] 3.3 Add "Clients" navigation menu item with icon `bx-buildings` under "Agency & Content" in `resources/views/layouts/admin-partial/sidebar.blade.php`.
- [ ] 3.4 Create `resources/views/admin/clients/index.blade.php` containing:
  - Responsive clients data table displaying logo, name, label, solution, link, active badge, and action buttons.
  - Real-time client-side / AJAX search filtering as keywords are entered.
  - Pagination controls.
  - Bootstrap 5 modal for adding new clients with live image preview.
  - Bootstrap 5 modal for editing existing clients with current image preview and replacement option.
  - SweetAlert2 interactive delete confirmation and Toastr notifications.

## 4. Frontend Binding & Blade Views

- [ ] 4.1 Update `HomeController@index` in `app/Http/Controllers/HomeController.php` to fetch active clients (`Client::active()->ordered()->get()`) and pass `$clients` to `frontend.index`.
- [ ] 4.2 Update the `#clients` section in `resources/views/frontend/index.blade.php`:
  - Replace hardcoded client cards with dynamic Blade loops over `$clients` for Set 1 (originals), Set 2, and Set 3 (clones).
  - Preserve the static "Verified" badge pill on every card.
  - Render the single client logo with theme brightness contrast styling.
  - Render clickable external link if `client->link` is populated.
  - Implement minimum item duplication if active client count is less than 4 to prevent scroller gaps.
- [ ] 4.3 Update `public/assets/js/animations.js` in `initKitoClientsScroller()` to derive `uniqueCount` dynamically from unique DOM cards instead of hardcoded `8`.

## 5. Verification & Testing

- [ ] 5.1 Test admin CRUD operations in browser: create a client with logo, edit client details, replace logo, and delete a client verifying file unlinking.
- [ ] 5.2 Test admin real-time search filtering by client name and industry label, and verify pagination.
- [ ] 5.3 Test homepage scroller on mobile, tablet, and desktop: verify infinite seamless loop, drag interaction, pause on hover, arrow controls, and dark/light mode rendering.
