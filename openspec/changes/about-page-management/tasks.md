# Tasks: About Page Management

## 1. Models & Migrations (Backend)

- [ ] 1.1 Create migration `2026_10_22_000000_create_about_page_contents_table.php` with `school_id`, scalar text columns, JSON repeater columns (`milestones`, `statistics`, `at_a_glance`, `committee_members`, `facilities`), and SEO columns.
- [ ] 1.2 Create Eloquent model `App\Models\AboutPageContent` with array casts, image accessors, `school()` relationship, and `forSchool($schoolId)` fallback generator.
- [ ] 1.3 Create seeder `database/seeders/AboutPageSeeder.php` populated with authentic Sonatala High School historical data and register it in `DatabaseSeeder.php`.

## 2. Controllers & Business Logic (Backend)

- [ ] 2.1 Create Form Request `App\Http\Requests\Admin\UpdateAboutPageRequest` with comprehensive validation rules for text, nested JSON repeaters, and uploaded images.
- [ ] 2.2 Create Admin Controller `App\Http\Controllers\Admin\Frontend\AboutPageController` with `edit()` and `update()` methods, image file handling, and ToastMagic session messages.
- [ ] 2.3 Register admin routes in `routes/admin.php` under prefix `frontend/pages/about` with route name `admin.frontend.pages.about`.
- [ ] 2.4 Update `App\Http\Controllers\Frontend\HomeController::about()` to retrieve `AboutPageContent::forSchool($school->id)` and pass the dynamic record to the view.

## 3. Admin Dashboard Navigation & UI (Frontend)

- [ ] 3.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` adding parent `Frontend` menu item with collapsible dropdown containing `Pages` -> `About`, active highlight styling, and tooltip integration.
- [ ] 3.2 Create admin edit view `resources/views/admin/frontend/pages/about.blade.php` with tabbed/card navigation:
  - Basic & History Tab (titles, subtitle, established year, narrative, image upload)
  - Vision, Mission & Values Tab
  - Milestones & Timeline Tab (dynamic repeater: year, title, description)
  - Statistics Counter Tab (dynamic repeater: label, value, note)
  - At a Glance & Committee Tab (specifications key-value pairs, committee member roster)
  - SEO & Social Metadata Tab (meta title, description, keywords, canonical, OG tags, live Google search snippet preview)

## 4. Public Frontend About Page Experience (Frontend)

- [ ] 4.1 Overhaul `resources/views/frontend/about/index.blade.php` into a modern, clean, minimalistic editorial design consistent with EduCore's forest green (`#005030`) theme and the reference (`https://sonatalahighschool.edu.bd/history`).
- [ ] 4.2 Build Institutional Hero section with breadcrumb, established badge, and dynamic headings.
- [ ] 4.3 Build Brief History & Narrative section with featured campus photograph card and heritage badge.
- [ ] 4.4 Build Vision, Mission & Core Values three-pillar responsive card grid.
- [ ] 4.5 Build Chronological Milestones Timeline component documenting historical progression.
- [ ] 4.6 Build Key Statistics Counter metrics grid showcasing institutional achievements.
- [ ] 4.7 Build "At a Glance" (এক নজরে প্রতিষ্ঠান) specification table with clean 1px borders.
- [ ] 4.8 Build Managing Committee (পরিচালনা পর্ষদ) directory table/cards.
- [ ] 4.9 Implement dynamic SEO output in `<head>` (dynamic title, meta description, keywords, canonical URL, OG tags, and Schema.org JSON-LD).

## 5. Verification & Testing (Team Lead)

- [ ] 5.1 Run database migration `php artisan migrate` and seed initial data via `php artisan db:seed --class=AboutPageSeeder`.
- [ ] 5.2 Create feature tests in `tests/Feature/AboutPageTest.php` testing public page rendering, admin authorization, validation errors, update persistence, image uploads, and SEO tags.
- [ ] 5.3 Execute tests using `php artisan test --filter=AboutPageTest` and verify all tests pass.
- [ ] 5.4 Verify visual layout and responsiveness in browser for both `/about` and `/admin/frontend/pages/about`.
