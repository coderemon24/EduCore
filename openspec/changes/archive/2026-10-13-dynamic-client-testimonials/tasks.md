# Tasks

## 1. Database & Migrations

- [x] 1.1 Create migration file `database/migrations/xxxx_xx_xx_create_testimonials_table.php` with columns `id`, `client_name`, `client_avatar`, `designation`, `company_name`, `rating`, `testimonial`, `order`, `is_active`, `created_at`, `updated_at`.
- [x] 1.2 Add composite index `idx_testimonials_active_order` on `(is_active, order ASC)` in the migration.
- [x] 1.3 Run migration inside container via `php artisan migrate` and verify table schema.

## 2. Models & Boot Lifecycle

- [x] 2.1 Create Eloquent model `app/Models/Testimonial.php` with fillable attributes and attribute casting (`is_active` to boolean, `rating` to integer, `order` to integer).
- [x] 2.2 Implement automatic cache invalidation on model lifecycle events (`saved` and `deleted` evicting `frontend_testimonials` cache key).
- [x] 2.3 Add helper/scope methods on `Testimonial` (e.g. `scopeActive`, `scopeOrdered`).

## 3. Admin Controllers & CRUD Views

- [x] 3.1 Register admin routes in `routes/admin.php` under `admin.auth` middleware group for resource `testimonials` and patch endpoint `admin.testimonials.toggle-status`.
- [x] 3.2 Add "Testimonials" menu link under "Agency & Content" in `resources/views/layouts/admin-partial/sidebar.blade.php`.
- [x] 3.3 Create `app/Http/Controllers/Admin/TestimonialController.php` with `index`, `store`, `edit`, `update`, `destroy`, and `toggleStatus` methods.
- [x] 3.4 Implement avatar file upload handling in `TestimonialController` saving to `public/uploads/testimonials/` with secure MIME validation and old-file cleanup on replacement/deletion.
- [x] 3.5 Create admin views directory `resources/views/admin/testimonials/` and build `index.blade.php` featuring responsive data table, live search filtering, pagination, and status switch.
- [x] 3.6 Implement Add Testimonial modal and Edit Testimonial modal in `resources/views/admin/testimonials/index.blade.php` with dynamic file upload preview and star rating picker.
- [x] 3.7 Wire SweetAlert2 deletion confirmation and AJAX active status toggle with toast feedback.

## 4. Frontend Binding & Blade Views

- [x] 4.1 Update `app/Http/Controllers/HomeController.php` in `index()` to query active testimonials via `Cache::rememberForever('frontend_testimonials', ...)` and pass `$testimonials` to `frontend.index`.
- [x] 4.2 Update `resources/views/frontend/index.blade.php` marquee section (`.km-tscroll-track`) to render dynamic testimonial cards with star SVGs, quotes, avatars (with fallback), names, and designations.
- [x] 4.3 Implement dual-loop rendering (original + `aria-hidden="true"` clone) inside `.km-tscroll-track` to guarantee smooth, seamless infinite scrolling animation (`km-rtl`).
- [x] 4.4 Add empty state fallback handling if no active testimonials exist.

## 5. Verification & Testing

- [x] 5.1 Test admin CRUD operations: create testimonial with image upload, edit fields, toggle active status, and delete via SweetAlert2.
- [x] 5.2 Verify avatar storage in `public/uploads/testimonials/` and confirm image serves with HTTP 200.
- [x] 5.3 Verify frontend homepage rendering and confirm cache key `frontend_testimonials` is populated and properly evicted upon CMS mutations.
- [x] 5.4 Test edge cases: empty testimonial list, missing avatar fallback image, long testimonial quote text, and mobile responsiveness.
- [x] 5.5 Run code linter/formatter `vendor/bin/pint` to ensure full compliance with Laravel code style standards.
