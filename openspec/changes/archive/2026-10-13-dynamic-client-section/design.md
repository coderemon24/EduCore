# Design

## Context
KitoMark is a dual-interface application:
1. **Frontend**: Public showcase built with Laravel Blade templates, Tailwind CSS v4, and custom vanilla JavaScript animations ([animations.js](file:///I:/KitoMark/kitomark/kito-app/public/assets/js/animations.js)) powering high-velocity interactive components.
2. **Admin Dashboard**: Internal management panel built with Bootstrap 5, MetisMenu, Boxicons, SweetAlert2, and Toastr, protected by the `admin.auth` middleware guard.

Currently, the homepage's client ecosystem section features 8 hardcoded cards cloned across 3 sets inside `#km-clients-track` to produce a continuous loop with mouse/touch drag and pause on hover. The JavaScript animation controller hardcodes `uniqueCount = 8`.

## Goals / Non-Goals

**Goals:**
- Provide full administrative CRUD operations for client entities at `/admin/clients` protected by the `admin.auth` guard.
- Provide responsive modals for adding and editing clients with instant image preview.
- Support single logo file uploads (PNG, JPG, SVG, WebP) stored under `public/uploads/clients/` with automatic unlinking of old/orphan files.
- Deliver real-time table search by client name and industry label, accompanied by server-side pagination.
- Retain the high-velocity infinite marquee carousel scroller on the frontend homepage while feeding data dynamically through `HomeController@index` (Blade SSR).
- Ensure the static "Verified" badge on each client card remains unchanged.
- Dynamically adapt `animations.js` scroller loop metrics based on the actual number of client cards in the DOM.
- Supply a `ClientSeeder` pre-populated with the current 8 partner brands so the transition from static to dynamic is seamless.

**Non-Goals:**
- Client customer login portal or multi-tenant client portals (this is strictly a marketing showcase of agency partner brands).
- Dynamic or editable badge text (the "Verified" badge remains static as specified).
- Replacing the marquee scroller with a static grid layout (confirmed to keep Option A).

## Decisions

### 1. Database & Model Structure
- **Table**: `clients`
  - `id` (bigint, primary key)
  - `client_name` (string, max 191, required)
  - `client_logo` (string, max 255, nullable, relative path to uploaded image)
  - `label` (string, max 191, required, e.g. "Venture Capital", "Enterprise AI")
  - `our_solution` (text, required, e.g. "Growth & Advisory", "DeepTech Infrastructure")
  - `link` (string, max 255, nullable, external website URL)
  - `is_active` (boolean, default true)
  - `order` (integer, default 0)
  - `timestamps`
- **Indexes**: Composite index on `[is_active, order]` for homepage retrieval; individual indexes on `client_name` and `label` for admin searches.
- **Model**: `App\Models\Client` with helper scopes `scopeActive()` and `scopeOrdered()`, and accessor `getLogoUrlAttribute()` providing fallback handling if a logo file is missing.

### 2. Media Upload & Asset Architecture
- Uploads are processed directly by `ClientController` and saved to `public/uploads/clients/` using unique hash filenames (`client_xxxx_timestamp.ext`).
- Only valid image formats (PNG, JPG, WebP, SVG) up to 2048 KB are accepted.
- When an existing client logo is replaced or a client is deleted, the old file on disk is removed via `File::delete(public_path($client->client_logo))` or `@unlink` to prevent orphaned disk assets.
- For frontend display, single transparent logos are rendered with CSS brightness and drop-shadow styling to ensure crisp visibility across both dark and light UI themes.

### 3. Admin UI & Interaction Design
- **Route**: `Route::resource('clients', ClientController::class, ['as' => 'admin'])` inside `routes/admin.php` within the `admin.auth` middleware group.
- **Navigation**: Insert a "Clients" link with icon `bx-buildings` in `resources/views/layouts/admin-partial/sidebar.blade.php` directly under "Agency & Content".
- **View**: `resources/views/admin/clients/index.blade.php`:
  - **Header & Filter Bar**: "Add Client" button triggering the modal, plus a live search input.
  - **Live Search**: JavaScript input listener that filters matching rows instantly on `input` event, falling back to server query parameter if desired.
  - **Modals**: Reusable Bootstrap 5 modal for Create and Edit with file drop/browse input and live `<img id="preview">` update.
  - **Action Deletion**: Delete button intercepts submit to display SweetAlert2 confirmation dialog.

### 4. Frontend Rendering & Scroller Dynamics
- **Controller**: `HomeController@index` queries:
  ```php
  $clients = Client::active()->ordered()->get();
  ```
- **Template**: In `resources/views/frontend/index.blade.php`, render the active clients into `#km-clients-track`. To ensure the infinite scroller operates smoothly even if fewer than 8 clients exist, repeat the set into 3 sets (Set 1 originals without `aria-hidden`, Set 2 and Set 3 clones with `aria-hidden="true"`).
- **Link Handling**: When `client->link` is provided, wrap the solution title or client card with an accessible interactive external link (`target="_blank" rel="noopener noreferrer"`).
- **Badge**: Retain the exact static markup:
  ```blade
  <span class="inline-flex items-center gap-1.5">
      <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
      <span class="text-[10px] text-content-muted">Verified</span>
  </span>
  ```
- **Scroller Script**: In `public/assets/js/animations.js`, update `initKitoClientsScroller()`:
  ```javascript
  const uniqueCards = track.querySelectorAll('.km-client-card:not([aria-hidden="true"])');
  const uniqueCount = uniqueCards.length || Math.floor(cards.length / 3) || 8;
  ```
  This eliminates the hardcoded `8` and accurately computes `setSpan` dynamically for any number of active clients.

## Risks / Trade-offs

| Risk | Mitigation |
|------|------------|
| **Very low client count (< 3 clients)** | If fewer than 3 clients are active in the database, the infinite marquee could experience visual gaps on wide desktop screens. Mitigation: If `$clients->count() < 4`, the template duplicates the items inside the set to maintain at least 8 cards per loop, ensuring continuous motion without empty space. |
| **Missing / Deleted logo files** | If a file is deleted from disk manually, the view could show broken images. Mitigation: Implement a `logo_url` accessor with a fallback placeholder asset SVG. |
| **External links triggering unwanted scroller drag** | In `animations.js`, clicking a link might conflict with mouse drag tracking. Mitigation: Differentiate between a click and a drag release using `didDrag` threshold flag already built into `animations.js`. |
