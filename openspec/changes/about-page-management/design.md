# Design: About Page Management

## Context

The EduCore platform currently has hardcoded placeholder views for public institution profiles (`resources/views/frontend/about/index.blade.php`), and the admin sidebar does not have a dedicated section for managing public frontend pages. See [proposal.md](file:///c:/laragon/www/laravel-school/openspec/changes/about-page-management/proposal.md) for full motivation.

To satisfy the user's requirements:
- Design a clean, modern, minimalistic About page improving upon `https://sonatalahighschool.edu.bd/history` with authentic Bengali typography and the school's existing brand theme (`#005030`).
- Add a new sidebar menu item named `Frontend` with dropdown items `Pages` -> `About`.
- Provide complete dynamic data management and SEO configuration from the administrative dashboard.

## Goals / Non-Goals

### Goals:
- Deliver a modern institutional About page (`/about`) that elevates the reference with clean visual hierarchy, rich typography, interactive timeline, stats counters, school at a glance table, committee directory, and campus facilities.
- Build a robust admin dashboard interface (`/admin/frontend/pages/about`) for customizing all text, repeaters, images, and SEO tags without touching code.
- Add the `Frontend` menu item with nested `Pages > About` dropdown in the admin sidebar matching the existing layout style, active states, and mobile collapse behavior.
- Support complete search engine optimization (SEO) controls (meta title, description, keywords, canonical URL, OpenGraph tags, and Schema.org JSON-LD).
- Multi-tenant data safety: isolate all configurations by `school_id`.

### Non-Goals:
- Building an arbitrary block-based drag-and-drop page builder for arbitrary static pages. The focus is specifically on structured, high-integrity academic institutional pages (starting with About / History).
- Modifying other public frontend pages (e.g. Notices, Teachers, Contact), which already have their own dedicated architectures.

## Decisions

### 1. Data Model: Dedicated Tenant-Isolated Content Table
- **Choice**: Create an `about_page_contents` table with `school_id` foreign key, scalar text columns for primary sections, and JSON columns for structured repeaters (`milestones`, `statistics`, `committee_members`, `at_a_glance`, `facilities`).
- **Rationale**:
  - A school has exactly one active About page configuration.
  - JSON columns allow editing timeline events, key stats, and committee members in a single atomic form submission without needing 4 extra relational tables and complex transaction logic.
  - Eloquent array casting (`'milestones' => 'array'`, `'statistics' => 'array'`, etc.) provides type-safe array manipulation in PHP.
- **Alternatives Considered**:
  - *Separate tables for milestones, statistics, committee members*: Over-engineered for a single page; requires complex nested form requests, multiple foreign keys, and multiple database queries per page view.
  - *Generic key-value settings table*: Lacks structured validation, difficult to query, and loses schema clarity.

### 2. Schema Structure
```sql
CREATE TABLE about_page_contents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,
    -- Header & Hero
    title VARCHAR(255) NULL,
    subtitle VARCHAR(500) NULL,
    established_year VARCHAR(50) NULL,
    banner_image VARCHAR(255) NULL,
    -- History & Narrative
    history_heading VARCHAR(255) NULL,
    history_content LONGTEXT NULL,
    history_image VARCHAR(255) NULL,
    -- Vision, Mission & Values
    vision_title VARCHAR(255) NULL,
    vision_text TEXT NULL,
    mission_title VARCHAR(255) NULL,
    mission_text TEXT NULL,
    values_title VARCHAR(255) NULL,
    values_list JSON NULL,
    -- Dynamic Repeaters
    milestones JSON NULL,        -- [{"year": "1968", "title": "...", "description": "..."}]
    statistics JSON NULL,        -- [{"label": "...", "value": "1250+", "suffix": "জন"}]
    at_a_glance JSON NULL,       -- [{"key": "EIIN", "value": "125693"}, ...]
    committee_members JSON NULL, -- [{"name": "...", "designation": "...", "role": "..."}]
    facilities JSON NULL,        -- [{"title": "...", "description": "...", "icon": "..."}]
    -- Dynamic SEO & Social
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords VARCHAR(500) NULL,
    canonical_url VARCHAR(500) NULL,
    og_title VARCHAR(255) NULL,
    og_description TEXT NULL,
    og_image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_about_page_school FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE,
    UNIQUE KEY uk_about_page_school (school_id)
);
```

### 3. Model Default Fallbacks (`forSchool` Factory / Accessor)
- **Choice**: Implement `AboutPageContent::forSchool(?int $schoolId)` helper. If no database record exists for the school, it returns an unsaved model pre-populated with authentic default data for Sonatala High School.
- **Rationale**: Ensures the public `/about` page renders beautifully out-of-the-box before the administrator performs initial edits, preventing null pointer exceptions or empty sections.

### 4. Admin Sidebar Menu Hierarchy
- **Choice**: Integrate a dedicated menu item in [resources/views/layouts/admin-partial/sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) inside a `Frontend Website` or under `Core Management`:
  ```html
  <!-- Frontend Website Menu Item -->
  <li class="relative group/dropdown" id="sidebar-frontend-dropdown-wrapper">
      <button type="button" id="sidebar-frontend-toggle" onclick="window.toggleFrontendSidebarDropdown(event)">
          <svg>...</svg>
          <span>Frontend</span>
          <chevron>...</chevron>
      </button>
      <ul id="sidebar-frontend-submenu">
          <!-- Pages Header / Subgroup -->
          <li>
              <div class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pages</div>
              <ul>
                  <li><a href="{{ route('admin.frontend.pages.about') }}">About</a></li>
              </ul>
          </li>
      </ul>
  </li>
  ```
- **Rationale**: Strictly meets the user's requested specification:
  ```text
  Frontend
   - Pages
      - About
  ```
- Follows the identical accordion animation, chevron rotation, active violet highlight (`bg-violet-50 text-violet-700`), and collapsed-sidebar tooltip standards established in the admin theme.

### 5. Admin Management UI Architecture
- Tabbed or anchored card layout in [resources/views/admin/frontend/pages/about.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/frontend/pages/about.blade.php):
  - **Tab 1: Basic & History** (Page Title, Subtitle, Established Year, History Story, Featured Image upload).
  - **Tab 2: Vision, Mission & Values** (Pillars configuration).
  - **Tab 3: Milestones & Stats** (Dynamic add/remove repeater rows for timeline and numerical counters).
  - **Tab 4: Info & Committee** (At-a-Glance specifications and Managing Committee members).
  - **Tab 5: SEO & Social Sharing** (Meta title, meta description, keywords, canonical link, OG image upload, and live Google SERP preview snippet).
- Standard form submission to `PUT /admin/frontend/pages/about` with ToastMagic toast confirmation.

### 6. Dynamic SEO Rendering on Frontend
- In [resources/views/frontend/about/index.blade.php](file:///c:/laragon/www/laravel-school/resources/views/frontend/about/index.blade.php):
  - `@section('title', $about->meta_title ?: $about->title.' | '.($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))`
  - `@section('meta_description', $about->meta_description ?: Str::limit(strip_tags($about->history_content), 160))`
  - `@push('styles')` or `@push('head')` yielding OpenGraph tags (`og:title`, `og:description`, `og:image`, `og:url`), canonical URL tag, and JSON-LD structured data for `EducationalOrganization` and `AboutPage`.

## Risks / Trade-offs

- **[Risk] Complex repeater row additions in pure JavaScript**: Users adding multiple timeline items or committee members without a JS framework could suffer DOM sync issues.
  - *Mitigation*: Provide lightweight, robust vanilla JS repeater helpers (`addMilestoneRow()`, `removeRow()`) that clone template `<template>` nodes with indexed input names (`milestones[0][year]`, etc.).
- **[Risk] File upload size and storage disk accessibility**:
  - *Mitigation*: Validate file types (`image|mimes:jpeg,png,jpg,webp|max:2048`) and store uploads in `public/uploads/about/` using public asset URLs with immediate preview.
- **[Risk] Unescaped HTML in History Narrative**:
  - *Mitigation*: Sanitize rich text with `Purifier` or strip unsafe script tags, while rendering safe paragraphs and line breaks cleanly.

## Migration Plan

1. Create migration `database/migrations/2026_10_22_000000_create_about_page_contents_table.php`.
2. Run `php artisan migrate`.
3. Create seeder `database/seeders/AboutPageSeeder.php` with rich default content and register in `database/seeders/DatabaseSeeder.php`.
4. Deploy model, controller, request, blade views, and routes.
5. Compile frontend styling if needed (`npm run build`).
6. Run feature tests to verify zero regressions.
