# about-page-management Specification

## Purpose
Provides full dynamic management, SEO customization, and modern institutional editorial presentation for the public About Us and History page (`/about`) and integrates a dedicated Frontend Pages management interface in the EduCore admin dashboard sidebar.

## Requirements

### Requirement: Frontend About Page Visual Experience & Layout
The public About page (`/about`) SHALL present an institutional, high-contrast, editorial-grade profile of the school adhering strictly to the **Universal UI Refactoring Framework** (`ui-refactor.md` Recipes 1–7), featuring asymmetric editorial compositions, a standardized 4-tier typography scale, semantic color discipline, and signature micro-motifs.

#### Scenario: Public visitor navigates to the About page
- **WHEN** a visitor navigates to `/about`
- **THEN** the system renders a clean, responsive page featuring:
  - An executive breadcrumb bar and modernized institutional hero section with page title, established year, and plain-spoken subtitle.
  - A dark institutional qualifying credentials strip displaying EIIN (১২৫৬৯৩), Rajshahi Education Board, MPO status, and founding year (১৯৬৮).
  - An asymmetric 60/40 founding narrative paired with an offset campus photograph containing a floating credential anchor badge (`Estd. 1968`).
  - An institutional manifesto elevating Vision and Mission alongside a vertical stack of Core Ethical Values.
  - Hallmark accomplishment statistics counters elevating top academic metrics.
  - A chronological milestone timeline documenting historical institutional milestones.
  - An "At a Glance" (এক নজরে প্রতিষ্ঠান) structured specification table detailing EIIN, School Code, MPO Code, and Education Board.
  - A Managing Committee (পরিচালনা পর্ষদ) roster detailing committee members, designations, and roles.
  - A campus facilities showcase elevating the Sheikh Russel Digital Computer Lab to 65% dominance with a compact 35% secondary stack.
  - A closing Next Step / Call to Action block providing direct routes for campus visits and admissions inquiries.

#### Scenario: Narrative section rhythm and asymmetric balance
- **WHEN** a visitor navigates to `/about`
- **THEN** all sections feature standardized `.section-header-block` headers with pulsing `.section-kicker` badges and are separated by `.home-section-divider` hairline dividers.
- **AND** the layout alternates between light (`bg-white`) and subtle slate (`bg-slate-50/60`) backgrounds.

#### Scenario: Pick the winner hierarchy in facilities showcase
- **WHEN** the campus facilities section is rendered
- **THEN** the system elevates the primary flagship facility (e.g., Sheikh Russel Digital Computer Lab) to span 60–65% width with prominent visual prominence, hardware specs, and proof points.
- **AND** secondary facilities (Science Lab, Library, Playground) are displayed in a quiet, compact secondary card tier.

#### Scenario: Strict semantic color discipline
- **WHEN** the About page is viewed
- **THEN** Primary Green (`#005030`) is used for headlines, accents, and brand elements.
- **AND** Warm Amber (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`) is strictly reserved for the primary conversion CTA button and removed from routine metadata.
- **AND** card backgrounds use clean neutral surfaces (`bg-white`, `border-slate-200/90`, `shadow-2xs`).

#### Scenario: Zero embedded style block and complete token centralization
- **WHEN** `resources/views/frontend/about/index.blade.php` is compiled
- **THEN** it contains zero embedded `<style>` blocks and relies entirely on centralized utility tokens and classes in `public/assets/css/style.css`.
- **AND** all body copy maintains a minimum size of `text-sm` (14px) and line height of `leading-relaxed`.

#### Scenario: Fallback rendering when no custom database content is configured
- **WHEN** no custom `about_page_contents` record exists for the current school
- **THEN** the system gracefully renders curated authentic fallback content for Sonatala High School without throwing errors or displaying empty/broken UI sections.

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
