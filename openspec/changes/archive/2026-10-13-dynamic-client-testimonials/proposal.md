# Proposal

## Why
The KitoMark agency homepage currently displays a static client testimonial marquee. Updating, adding, or reordering testimonials requires direct template edits and code deployments. To empower administrators to curate social proof without code changes, this change introduces a dynamic, administrative testimonial management system. It allows marketing administrators to manage client feedback through an authenticated CMS dashboard while serving testimonials on the public homepage with low latency, robust query caching, and seamless continuous marquee scrolling.

## What Changes
- **Database Schema**: A dedicated `testimonials` table with columns for client name, avatar upload path, title/designation, company name, star rating (1–5), quote text, display order sequence, active status toggle, and timestamps. Composite index on `(is_active, order)` for optimal query performance.
- **Eloquent Model**: An `App\Models\Testimonial` model with attribute casting (`is_active` as boolean, `rating` as integer) and automatic cache invalidation on save/delete.
- **Administrative CMS**: A full CRUD interface at `/admin/testimonials` placed under the "Agency & Content" sidebar section. Includes data table listing with avatar thumbnails, star rating indicators, status switches, real-time search, pagination, and modal dialogs for adding/editing testimonials. SweetAlert2-backed deletion confirmations.
- **Media Asset Storage**: Direct upload of client avatar images into `public/uploads/testimonials/` adhering to KitoMark asset management standards with secure MIME and size validation.
- **Frontend Controller & Caching**: Updating `HomeController@index` to retrieve active testimonials ordered by display sequence via `Cache::rememberForever('frontend_testimonials', ...)`.
- **Frontend Blade View**: Modifying `frontend.index` (`.km-tscroll-track`) to dynamically loop over testimonials, rendering stars dynamically based on rating, client quotes, avatar with fallback image, name, and designation/company. The card list is rendered twice to guarantee an uninterrupted, smooth marquee loop animation (`km-rtl`).

## Capabilities

### New Capabilities
- `client-testimonials`: Admin CMS for testimonial curation and dynamic cached marquee display on the public homepage.

### Modified Capabilities
<!-- None -->

## Impact
- **Database**: New migration creating the `testimonials` table with `idx_testimonials_active_order` index.
- **Routes & Middleware**: New resourceful routes in `routes/admin.php` protected by `admin.auth` middleware.
- **Controllers & Views**: New `App\Http\Controllers\Admin\TestimonialController` and Blade views in `resources/views/admin/testimonials/index.blade.php`; modifications to `App\Http\Controllers\HomeController` and `resources/views/frontend/index.blade.php`.
- **Navigation**: Sidebar update in `resources/views/layouts/admin-partial/sidebar.blade.php`.
- **External Dependencies**: None. Uses existing Laravel 12 features, SweetAlert2, and Tailwind CSS.
