# about-page-management Specification

## Purpose
Provides full dynamic management, SEO customization, and modern institutional editorial presentation for the public About Us and History page (`/about`) and integrates a dedicated Frontend Pages management interface in the EduCore admin dashboard sidebar.

## Requirements

### Requirement: Frontend About Page Visual Experience & Layout
The public About page (`/about`) SHALL present an institutional, minimalistic, and modern editorial profile of the school that matches the established design system (forest green `#005030`, clean 1px borders, subtle warm accents, and responsive card grids) while elevating the reference structure (`https://sonatalahighschool.edu.bd/history`).

#### Scenario: Public visitor navigates to the About page
- **WHEN** a visitor navigates to `/about`
- **THEN** the system renders a clean responsive page featuring:
  - An institutional hero section with page title, established year badge, breadcrumb navigation, and institutional subtitle.
  - A brief history and founding narrative accompanied by a campus or historical featured image.
  - A three-card grid highlighting Vision (লক্ষ্য), Mission (উদ্দেশ্য), and Core Values (মূল্যবোধ).
  - A chronological milestone timeline documenting historical institutional milestones.
  - Key statistics cards showcasing student count, faculty count, academic pass rate, and years of excellence.
  - An "At a Glance" (এক নজরে প্রতিষ্ঠান) structured specification card detailing EIIN, School Code, MPO Code, Education Board, Location, and Shift.
  - A Managing Committee (পরিচালনা পর্ষদ) roster detailing committee members, designations, and roles.
  - Campus facilities overview detailing modern educational infrastructure (computer lab, science lab, library, auditorium, playground).

#### Scenario: Fallback rendering when no custom database content is configured
- **WHEN** no custom `about_page_contents` record exists for the current school
- **THEN** the system gracefully renders curated authentic fallback content for Sonatala High School without throwing errors or displaying empty/broken UI sections.

---

### Requirement: Backend Admin Dashboard Sidebar Navigation
The backend administrative dashboard sidebar SHALL include a new parent menu item named **Frontend** with a collapsible dropdown containing **Pages** and sub-item **About**.

#### Scenario: Administrator views the sidebar
- **WHEN** an authenticated administrator accesses any `/admin/*` route
- **THEN** the sidebar displays a new "Frontend" menu item with a layout/globe icon.
- **AND** expanding the "Frontend" dropdown displays "Pages", which expands or directly links to "About".
- **AND** clicking "About" navigates to the About Page Management route (`route('admin.frontend.pages.about')`).

#### Scenario: Active state highlighting and mobile collapse
- **WHEN** the administrator is on the About Page Management route
- **THEN** the "Frontend" parent menu and "About" child link display the active violet highlight styling (`bg-violet-50 text-violet-700`).
- **AND** the dropdown auto-expands on page load if active.
- **AND** hovering in collapsed sidebar mode displays accessible floating tooltips.

---

### Requirement: Administrative Management Interface & CRUD
The administrative portal SHALL provide a dedicated management screen at `GET /admin/frontend/pages/about` allowing school administrators to edit all sections of the About page dynamically.

#### Scenario: Administrator updates basic information and history
- **WHEN** an authorized administrator submits updated headline, subtitle, established year, and historical narrative via `PUT /admin/frontend/pages/about`
- **THEN** the system validates input, persists changes to `about_page_contents` associated with the current `school_id`, displays a success toast notification, and immediately reflects updates on `/about`.

#### Scenario: Administrator updates dynamic repeaters (milestones, statistics, committee)
- **WHEN** an administrator adds, edits, reorders, or removes milestone items, statistical metrics, or committee members
- **THEN** the system validates the structured array data, encodes it into the respective JSON columns (`milestones`, `statistics`, `committee_members`, `at_a_glance`), and saves the record.

#### Scenario: Administrator uploads featured images
- **WHEN** an administrator uploads a new banner image or historical campus photo
- **THEN** the system validates file format (`image`, max 2MB), stores the image in public storage disk, removes any outdated file, and saves the relative storage path.

---

### Requirement: Dynamic Search Engine Optimization (SEO) & Social Sharing
The About page SHALL support dynamic SEO meta tags and social sharing attributes configured via the admin dashboard.

#### Scenario: Administrator configures SEO metadata
- **WHEN** an administrator specifies custom Meta Title, Meta Description, Meta Keywords, Canonical URL, OpenGraph Title, OpenGraph Description, and OpenGraph Image
- **THEN** the system stores these SEO attributes in the database.
- **AND** when `/about` is served, the HTML `<head>` outputs:
  - `<title>` matching the dynamic meta title (or school name fallback).
  - `<meta name="description">` matching the dynamic meta description.
  - `<meta name="keywords">` matching configured keywords.
  - `<link rel="canonical">` matching the canonical URL.
  - OpenGraph tags (`og:title`, `og:description`, `og:image`, `og:type`, `og:url`).
  - Schema.org JSON-LD structured data (`EducationalOrganization` and `AboutPage`).

#### Scenario: Live search engine preview in admin dashboard
- **WHEN** the administrator edits SEO fields in the admin dashboard
- **THEN** a live search preview widget dynamically simulates how the page title, URL slug, and description snippet appear on Google search results.

---

### Requirement: Multi-Tenant Isolation & Role Authorization
All about page records and management actions SHALL enforce multi-tenant isolation and strict permission checks.

#### Scenario: Multi-tenant database isolation
- **WHEN** multiple schools exist within the EduCore platform
- **THEN** each school's `/about` page queries only the record where `school_id` matches `CurrentSchoolContext::get()->id`.
- **AND** an administrator of School A cannot view or update about page content belonging to School B.

#### Scenario: Permission authorization
- **WHEN** a staff user lacks administrative permissions for website management
- **THEN** access to `GET` and `PUT` `/admin/frontend/pages/about` is denied with HTTP 403 Forbidden.
