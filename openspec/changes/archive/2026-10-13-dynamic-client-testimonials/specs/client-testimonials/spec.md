# Spec Delta

## Purpose
Enables dynamic administrative curation of client testimonials and delivers a lightning-fast, cached infinite marquee showcase on the public KitoMark agency homepage.

## ADDED Requirements

### Requirement: Testimonials Database Persistence and Model
The system SHALL persist client testimonials in a dedicated MySQL database table with optimized querying indexes and Eloquent casting.

#### Scenario: Running database migration
- **WHEN** the artisan migration command is executed
- **THEN** a `testimonials` table is created with `id`, `client_name` (varchar 255, not null), `client_avatar` (varchar 255, nullable), `designation` (varchar 255, nullable), `company_name` (varchar 255, nullable), `rating` (tinyint unsigned, default 5), `testimonial` (text, not null), `order` (int, default 0), `is_active` (boolean, default true), `created_at`, `updated_at`, and a composite index on `(is_active, order ASC)`.

#### Scenario: Eloquent model type casting
- **WHEN** a `Testimonial` model instance is loaded or serialized
- **THEN** `is_active` is cast to boolean, `rating` is cast to integer, and `order` is cast to integer.

### Requirement: Admin Dashboard Testimonials Management
The authenticated admin dashboard SHALL provide full CRUD management for client testimonials under the "Agency & Content" navigation group.

#### Scenario: Viewing testimonials index table
- **WHEN** an authenticated administrator navigates to `/admin/testimonials`
- **THEN** the system displays a data table listing client avatar thumbnails, client names, designation and company names, star rating representations, testimonial quote previews, display orders, status badges/toggles, and action controls (Edit, Delete).

#### Scenario: Real-time search and pagination
- **WHEN** an administrator types into the search field on the testimonials index
- **THEN** the displayed list filters in real-time by client name, designation, or company name, with page navigation controls preserving manageable page sizes.

#### Scenario: Creating a testimonial with avatar upload
- **WHEN** an administrator submits the "Add Testimonial" modal form with valid client details, star rating (1–5), quote text, and an optional image (jpeg, png, webp, max 2MB)
- **THEN** the uploaded avatar is stored in `public/uploads/testimonials/`, a new database record is created, the frontend cache is invalidated, and a success notification is presented.

#### Scenario: Input validation failure
- **WHEN** an administrator submits invalid data (e.g. missing client name, missing testimonial text, invalid rating outside 1–5, or file exceeding 2MB)
- **THEN** the system rejects the submission with descriptive validation errors and preserves user input in the form.

#### Scenario: Editing an existing testimonial
- **WHEN** an administrator edits an existing testimonial and saves modifications
- **THEN** the testimonial record in the database is updated, the previous avatar is replaced if a new file is supplied, the frontend cache is flushed, and the table reflects the updated data.

#### Scenario: Toggling active status
- **WHEN** an administrator toggles the active status switch for a testimonial
- **THEN** the `is_active` field updates immediately via an AJAX patch request, the cache is evicted, and a confirmation toast is displayed.

#### Scenario: Deleting a testimonial
- **WHEN** an administrator confirms deletion through a SweetAlert2 prompt
- **THEN** the testimonial record and associated avatar file are deleted, the cache is flushed, and the table row is removed.

### Requirement: Public Homepage Testimonial Showcase & Marquee
The public agency homepage SHALL dynamically display active testimonials in a continuous marquee scroll with query caching.

#### Scenario: Serving cached testimonials
- **WHEN** a visitor navigates to the public homepage (`/`)
- **THEN** `HomeController@index` retrieves only testimonials where `is_active = true` sorted by `order ASC` from cache key `frontend_testimonials`, preventing repeated database queries across requests.

#### Scenario: Rendering infinite marquee loop
- **WHEN** active testimonials exist in the database
- **THEN** `.km-tscroll-track` renders the testimonials set twice (original plus a clone marked with `aria-hidden="true"`) to maintain a continuous, uninterrupted `km-rtl` CSS scrolling animation with dynamic star SVGs, quote text, avatar, name, and designation.

#### Scenario: Graceful fallback when no testimonials exist
- **WHEN** no active testimonials exist in the database
- **THEN** the homepage displays a clean fallback state without breaking the section layout or throwing runtime errors.
