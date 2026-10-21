# Proposal

## Why

Sonatala High School (and schools running on the EduCore platform) require an authentic, modern, and high-impact "About Us / Institutional Profile" page (`/about` or `/history`) that communicates the school's heritage, achievements, leadership, vision, and campus facilities to parents, prospective students, education boards, and the community.

Currently, the frontend `/about` page contains hardcoded placeholder HTML without database backing or administrative management. School administrators have no way to update institutional history, milestones, mission/vision statements, statistics, or managing committee records from the admin dashboard. Furthermore, modern institutional websites require search engine optimization (SEO) configurations (meta titles, descriptions, keywords, Open Graph metadata, canonical URLs, and JSON-LD structured data) so schools rank prominently and present professionally when shared on social media or searched online.

This change delivers a clean, modern, minimalistic About page inspired by and improving upon the reference (`https://sonatalahighschool.edu.bd/history`), backed by a dynamic admin management suite accessible under a dedicated "Frontend > Pages > About" menu item in the EduCore admin sidebar.

## What Changes

1. **Frontend About / History Page Experience (`/about`)**:
   - Modern, minimalistic, and high-density editorial layout designed consistently with EduCore's brand palette (institutional deep forest green `#005030`, crisp 1px borders, subtle warm accents, Hind Siliguri and Plus Jakarta Sans typography).
   - **Hero & Breadcrumb Banner**: Institutional title, established year badge, clean breadcrumb, and tagline.
   - **Brief History & Narrative**: Rich story of the school's founding, growth, historical campus photograph, and heritage badges.
   - **Vision, Mission & Core Values**: Three-pillar card layout highlighting institutional goals and ethical principles.
   - **Interactive / Chronological Milestones Timeline**: Key historical turning points (founding, first SSC batch, MPO enlistment, digital lab modernization, academic excellence).
   - **Key Statistics & Achievements**: Dynamic counter metrics (students, faculty, passing rate, GPA-5 achievements, years of service).
   - **Institutional Profile / At a Glance**: Structured data sheet (EIIN, School Code, MPO Code, Board, Location, Curriculum, Shift).
   - **Managing Committee & Leadership**: Clean roster table or card grid displaying committee members, designations, and roles.
   - **Campus Facilities & Academic Environment**: Highlighting science labs, digital computer lab, library, sports grounds, and multimedia classrooms.
   - **Full Dynamic SEO Integration**: Dynamic meta tags (`<title>`, `<meta name="description">`, keywords, canonical link, OpenGraph tags, and Schema.org JSON-LD structured data) populated from database settings.

2. **Backend Admin Dashboard Navigation**:
   - Add a new top-level sidebar navigation item named **Frontend** with a globe/layout icon and collapsible dropdown hierarchy:
     - **Frontend**
       - **Pages**
         - **About** (links to `route('admin.frontend.pages.about')`)
   - Fully integrated with the admin sidebar's existing active state indicators, accordion transitions, tooltips for collapsed viewport modes, and mobile responsiveness.

3. **Backend Admin Management Architecture & SEO Controls**:
   - Dedicated controller `App\Http\Controllers\Admin\Frontend\AboutPageController` providing a tabbed or sectioned administrative management interface:
     - **Basic & Header Settings**: Page headline, subtitle, established year, banner image upload/path.
     - **History & Overview**: Founding story, history details (rich HTML/markdown supported), featured history image.
     - **Vision, Mission & Values**: Vision statement, mission statement, core institution values list.
     - **Timeline & Milestones**: Dynamic repeater / structured JSON array of milestones (year, title, description).
     - **Statistics Counter**: Dynamic repeater / structured JSON array of stats (label, value, icon/suffix).
     - **At a Glance (Institutional Info)**: EIIN, School Code, MPO Code, Education Board, Shift, Campus Area, etc.
     - **Managing Committee**: Committee roster (name, designation, role/comment, sort order).
     - **SEO & Social Metadata**: Meta title, meta description, keywords, canonical URL, OG title, OG description, OG image upload, and search engine preview snippet.
   - School-tenant isolation (`school_id`) ensuring multi-tenant safety across institutional databases.

4. **Database & Persistence**:
   - Database migration creating `about_page_contents` (or `frontend_pages`) table with tenant isolation (`school_id` foreign key referencing `schools.id`), JSON-capable columns for structured repeater data (milestones, stats, committee, at-a-glance), and dedicated SEO columns.
   - Eloquent Model `App\Models\AboutPageContent` (or `FrontendPage`) with attribute casting, default fallbacks for instant out-of-the-box display, and image upload handling.
   - Database seeder populating authentic Sonatala High School historical data so the about page renders completely upon installation.

## Capabilities

### New Capabilities
- `about-page-management`: Administrative management, dynamic database persistence, SEO customization, and modern frontend rendering of the institutional About & History page (`/about`) and its dedicated admin sidebar menu integration.

### Modified Capabilities
- None. (Existing capabilities remain intact and unaffected).

## Impact

- **Database**: New migration creating `about_page_contents` table with foreign key `school_id` and indexed lookups.
- **Backend Admin**:
  - New Controller: [App\Http\Controllers\Admin\Frontend\AboutPageController.php](file:///c:/laragon/www/laravel-school/app/Http/Controllers/Admin/Frontend/AboutPageController.php).
  - New Form Request: [App\Http\Requests\Admin\UpdateAboutPageRequest.php](file:///c:/laragon/www/laravel-school/app/Http/Requests/Admin/UpdateAboutPageRequest.php).
  - New Model: [App\Models\AboutPageContent.php](file:///c:/laragon/www/laravel-school/app/Models/AboutPageContent.php).
  - Admin View: [resources/views/admin/frontend/pages/about.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/frontend/pages/about.blade.php).
  - Sidebar Update: [resources/views/layouts/admin-partial/sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php).
  - Admin Routes: [routes/admin.php](file:///c:/laragon/www/laravel-school/routes/admin.php) under `admin.frontend.pages.about`.
- **Frontend**:
  - Updated Controller: [App\Http\Controllers\Frontend\HomeController.php](file:///c:/laragon/www/laravel-school/app/Http/Controllers/Frontend/HomeController.php) (`about` method) to fetch dynamic page content and pass it to view.
  - Updated View: [resources/views/frontend/about/index.blade.php](file:///c:/laragon/www/laravel-school/resources/views/frontend/about/index.blade.php) with modern, clean, minimalistic UI and dynamic SEO meta tags.
- **Tests**:
  - Feature test [tests/Feature/AboutPageTest.php](file:///c:/laragon/www/laravel-school/tests/Feature/AboutPageTest.php) verifying public `/about` rendering, admin authorization, validation rules, update persistence, image handling, and dynamic SEO tags.
