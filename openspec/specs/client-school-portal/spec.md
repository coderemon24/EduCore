# Client School Portal Specification

## Purpose
Provides a responsive, minimalist, and modern client-side frontend for schools to showcase institutional information, publish real-time notices, display staff directories, and communicate with students and parents while being architecturally decoupled for future SaaS multi-tenancy.

## Requirements

### Requirement: SaaS-Ready Tenant Context Resolution and Route Architecture
The system MUST provide public client routes registered exclusively in `routes/web.php` and resolve the active institution via a dedicated `CurrentSchoolContext` resolver, defaulting to the primary active school for `laravel-school.test` while supporting future domain/subdomain matching without frontend template changes.

#### Scenario: Visitor requests the root homepage
- **WHEN** a visitor navigates to `/` (e.g., on `http://laravel-school.test`)
- **THEN** the request is handled by `App\Http\Controllers\Frontend\HomeController@index`
- **AND** the active school model is resolved and shared with all frontend Blade views.

#### Scenario: Active school model resolution fallback
- **WHEN** no custom domain is configured in the current request
- **THEN** `CurrentSchoolContext` retrieves the first active `School` record with status `active`
- **AND** makes school metadata (name, code, EIIN, logo, contact) accessible across the layout.

### Requirement: Master Layout and Modular Partial Architecture
The frontend SHALL implement a master layout at `resources/views/layouts/app.blade.php` with modular partials located in `resources/views/layouts/partials/` (`head.blade.php`, `topbar.blade.php`, `navbar.blade.php`, `notice-ticker.blade.php`, `footer.blade.php`), strictly avoiding AI-slop visual artifacts.

#### Scenario: Bilingual typography and styling verification
- **WHEN** any frontend page is rendered
- **THEN** fonts `Hind Siliguri` (Bengali) and `Plus Jakarta Sans` (English) are loaded via `<head>`
- **AND** text displays with high-contrast legibility, 1px subtle hairline borders, and zero ambient blur halos or generic gradient text fills.

#### Scenario: Responsive navigation drawer on mobile viewports
- **WHEN** a visitor views the site on a screen width below 768px
- **THEN** desktop navigation links collapse into an accessible mobile hamburger trigger
- **AND** activating the trigger toggles a smooth, touch-friendly mobile slide-over drawer without horizontal overflow.

### Requirement: 3-Column Modern Editorial Homepage Feed
The homepage (`resources/views/frontend/home/index.blade.php`) SHALL feature an institutional editorial feed with an asymmetric visual hierarchy: (1) An asymmetric academic capabilities grid where the primary academic offering commands dominant width and scale over lighter secondary tiers, eliminating redundant notice cards, (2) An institutional information hub with real-time notice board and campus stories, and (3) An institutional metrics bar that elevates hallmark educational outcomes (student enrollment, board success rate, institutional longevity) above administrative registry codes.

#### Scenario: Homepage 3-column content presentation
- **WHEN** a visitor loads the homepage on desktop screens (>= 1024px)
- **THEN** Column 1 renders the lead highlight story with photo and secondary highlighted items
- **AND** Column 2 displays the most recent notices in a compact, scrollable or structured list with publication dates
- **AND** Column 3 displays the Headmaster / Principal message card and quick access links (online admission, portal login, result archive).

#### Scenario: Homepage statistics and notice ticker
- **WHEN** the homepage loads
- **THEN** a marquee or smooth-scroll ticker displays urgent/pinned notices at the top
- **AND** an institutional metrics section displays key numbers (total students, faculty count, classrooms, established year).

#### Scenario: Visual dominance in academic capabilities section
- **WHEN** a visitor navigates to the academic capabilities section (`#capabilities`) on desktop viewports (>= 1024px)
- **THEN** the primary capability (*ব্যবহারিক বিজ্ঞান ও উদ্ভাবনী ল্যাব*) spans 60%–65% of the grid width with prominent headline scale (`text-2xl`), expanded padding, and dedicated laboratory curriculum proof points
- **AND** secondary capabilities (*ডিজিটাল আইসিটি শিক্ষা* and *স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন*) sit in a lighter secondary tier with smaller headings (`text-base`), subdued borders, and concise summaries
- **AND** no duplicate notice board or evaluation card is rendered in the capabilities grid.

#### Scenario: Responsive reflow of capabilities grid
- **WHEN** the homepage is viewed on mobile screens (< 768px) or tablet screens (768px–1023px)
- **THEN** the capabilities layout reflows into a natural stacked order without horizontal overflow, preserving the visual prominence of the primary capability at the top of the stack.

#### Scenario: Visual hierarchy of institutional metrics
- **WHEN** a visitor views the institutional metrics section in `#advantage`
- **THEN** key educational outcomes (*৯৮.৫% এসএসসি সাফল্য*, *১,২৫০+ বর্তমান শিক্ষার্থী*, and *৫৭ বছরের ঐতিহ্য*) render with primary typographic weight and spacious padding
- **AND** administrative registry numbers (such as EIIN: 125693 and School Code: 8107) are displayed in a secondary meta strip rather than occupying identical metric cards.

### Requirement: Public Notice Directory and Circular Detail Preview
The system MUST provide dedicated notice listing and detail views (`resources/views/frontend/notices/`) with category filtering, date stamps, and downloadable circular attachments.

#### Scenario: Visitor browses notices directory
- **WHEN** a user navigates to `/notices`
- **THEN** published notices for the active school are paginated and sorted by `is_pinned` DESC and `published_at` DESC
- **AND** each entry displays title, publication date in localized format, audience badge, and attachment indicator.

#### Scenario: Visitor opens single notice circular
- **WHEN** a user navigates to `/notices/{notice:slug}`
- **THEN** the system displays the complete announcement text, author details, and direct secure download link for attached PDF/image circulars.

### Requirement: Faculty Directory and Institutional Contact
The system MUST provide a high-contrast, editorial-grade public directory view for teaching and administrative faculty (`/teachers`) adhering strictly to the 7 UI Master Recipes, featuring an asymmetric leadership spotlight, qualifying faculty telemetry, modern minimalistic cards with tactile touchpoints, interactive department filtering, search controls, and guardian consultation appointments.

#### Scenario: Viewing faculty directory page layout and narrative rhythm
- **WHEN** a visitor navigates to `/teachers`
- **THEN** the page renders an institutional header banner with breadcrumbs and establishment context
- **AND** a qualifying metrics strip displays verified faculty telemetry (Total Faculty, Higher Degree Holders, Student-Teacher Ratio, and Certification status)
- **AND** all core sections feature standardized `.section-header-block` headers with pulsing `.section-kicker` badges and `.home-section-divider` hairline dividers
- **AND** the page concludes with a guardian consultation call-to-action block featuring an amber conversion button.

#### Scenario: Asymmetric leadership spotlight for Headmaster and Assistant Headmaster
- **WHEN** a visitor views the leadership section on desktop viewports (>= 1024px)
- **THEN** the Headmaster (প্রধান শিক্ষক) flagship profile card commands dominant width (60%–65%) featuring portrait photo, leadership manifesto quote, educational qualifications, experience, and direct contact buttons
- **AND** the Assistant Headmaster (সহকারী প্রধান শিক্ষক) card occupies the secondary column (35%–40%) highlighting curriculum oversight, academic supervision, and office contact.

#### Scenario: Modern minimalistic teacher card presentation and interactive touchpoints
- **WHEN** faculty members are rendered in the directory grid
- **THEN** each card displays an architectural 4:5 aspect ratio portrait with a pinned department tag
- **AND** typography adheres to the 4-tier scale with high optical contrast
- **AND** each card provides direct contact actions including a clickable telephone link (`tel:`) and email link
- **AND** cards provide subtle hover elevation (`hover:-translate-y-1 hover:shadow-md`) with no generic ambient halos or text gradients.

#### Scenario: Interactive department filtering and search
- **WHEN** a visitor selects a department filter tab or submits the search form
- **THEN** the page displays faculty matching the selected department or query parameter
- **AND** active filter pills render with institutional green styling (`bg-[#005030] text-white`)
- **AND** if no records match or the database is unseeded, authentic verified fallback faculty profiles are displayed to maintain zero broken UI states.

