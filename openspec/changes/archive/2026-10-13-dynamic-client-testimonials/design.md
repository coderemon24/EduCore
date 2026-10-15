# Design

## Context
KitoMark runs on Laravel 12 with a dual-guard authentication architecture (`web` for public visitors and `admin` with `admin.auth` middleware for administrators). The agency homepage currently hardcodes client testimonial cards directly inside `frontend/index.blade.php` in a CSS marquee container (`.km-tscroll-track`). The admin dashboard already provides resource controllers for Services and Case Studies under the "Agency & Content" section. All uploaded media lives under `public/uploads/{feature}/` for immediate public asset serving without symlink dependencies.

## Goals / Non-Goals

**Goals:**
- Provide a dedicated, secure admin CMS module for full CRUD operations on client testimonials under `/admin/testimonials`.
- Store uploaded avatars directly in `public/uploads/testimonials/` with validation (jpeg, png, webp, max 2MB).
- Ensure seamless caching via `Cache::rememberForever('frontend_testimonials', ...)` and deterministic cache invalidation upon any testimonial mutation (create, update, delete, or status toggle).
- Preserve the existing high-converting frontend aesthetic, infinite continuous marquee scrolling (`km-rtl` animation), responsive styling, and zero layout shift.
- Support real-time search, pagination, order sequencing, and modal-driven editing.

**Non-Goals:**
- Public-facing user submission form (testimonials are curated strictly by administrators).
- Third-party review platform syncing (e.g. Google Reviews or Clutch API integration).
- Multi-language/localization for testimonial texts at this stage.

## Decisions

### 1. Database Schema and Indexing Strategy
- **Table**: `testimonials`
- **Fields**:
  - `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
  - `client_name`: VARCHAR(255) NOT NULL
  - `client_avatar`: VARCHAR(255) NULL
  - `designation`: VARCHAR(255) NULL
  - `company_name`: VARCHAR(255) NULL
  - `rating`: TINYINT UNSIGNED DEFAULT 5
  - `testimonial`: TEXT NOT NULL
  - `order`: INT DEFAULT 0
  - `is_active`: BOOLEAN DEFAULT TRUE
  - `created_at`, `updated_at`: TIMESTAMP NULL
- **Composite Index**: `idx_testimonials_active_order` on `(is_active, order ASC)` ensures the frontend query `Testimonial::where('is_active', true)->orderBy('order', 'asc')` is satisfied entirely via index scan.

### 2. Cache Invalidation via Eloquent Model Events
- Rather than manually calling cache evictions in multiple controller methods, `App\Models\Testimonial` will leverage its `booted()` method or model events (`saved`, `deleted`) to execute `Cache::forget('frontend_testimonials')`. This ensures zero stale data risk regardless of where mutations originate.

### 3. Controller Architecture & Admin Routing
- **Controller**: `App\Http\Controllers\Admin\TestimonialController` adhering to resourceful Laravel 12 routing.
- **Routes in `routes/admin.php`**:
  - `Route::patch('/testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('admin.testimonials.toggle-status');`
  - `Route::resource('testimonials', TestimonialController::class, ['as' => 'admin']);`
- **Input Validation**: Dedicated validation rules verifying client name, rating between 1 and 5, testimonial quote, display order, and image upload MIME restrictions (`image/jpeg,image/png,image/webp`) with a 2MB ceiling.

### 4. Admin UI Architecture
- Reuses the existing layout structure (`layouts.admin-partial.sidebar`).
- Adds a new sub-menu or direct link under "Agency & Content":
  - Label: `Testimonials`
  - Icon: `bx bx-star font-size-18 align-middle`
  - Route: `admin.testimonials.index`
- Data Table features:
  - Columns: Avatar, Client Name, Designation & Company, Star Rating, Quote preview, Display Order, Status toggle, Actions (Edit, Delete).
  - Search: Real-time client-side / server-side search input filtering rows by client name, designation, and company.
  - Modals: Reusable Blade modal for "Add Testimonial" and "Edit Testimonial" with image preview on file selection.
  - SweetAlert2 integration for deletion confirmation prompts.

### 5. Frontend Marquee Loop Preservation
- In `frontend/index.blade.php`, the `.km-tscroll-track` container requires two identical iterations of the testimonial collection to achieve an infinite CSS marquee scroll (`km-rtl` animation):
  1. The primary set for standard DOM rendering.
  2. The cloned set annotated with `aria-hidden="true"` to prevent screen reader redundancy while ensuring visual continuity.
- Dynamic star ratings iterate SVG stars up to the `$testimonial->rating` value, with unfilled stars for the remainder up to 5.
- Client avatars render `$testimonial->client_avatar` via `asset(...)`, with a clean fallback SVG/placeholder when null.

## Risks / Trade-offs

- **Risk: Zero Testimonials Added**:
  - *Mitigation*: If the database contains zero active testimonials, render a graceful empty state or keep a placeholder quote so the layout does not collapse.
- **Risk: Cache Stale State on Direct DB Changes**:
  - *Mitigation*: Automated cache flushing in Model boot hooks, supplemented by `php artisan optimize:clear` during deployment.
- **Risk: Broken Infinite Marquee with Low Item Count**:
  - *Mitigation*: Marquee cards need enough horizontal width to wrap cleanly. Repeating the active collection twice (or padding if fewer than 3 items exist) ensures the CSS animation never creates empty blank gaps.
