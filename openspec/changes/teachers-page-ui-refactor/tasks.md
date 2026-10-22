# Tasks: Teachers Page UI Refactoring & Redesign

## 1. Layout & Blade Architecture

- [x] 1.1 Establish 6-section container architecture in `resources/views/frontend/teachers/index.blade.php` alternating between `bg-white` and `bg-slate-50/60` surfaces separated by `<div class="home-section-divider"></div>`.
- [x] 1.2 Construct the executive breadcrumb navigation bar and institutional hero banner (`bg-[#005030] py-14 sm:py-20`) with category kicker and establishment trust badge.
- [x] 1.3 Construct the qualifying faculty telemetry strip (`bg-slate-900 text-slate-200 py-4`) with 4 verified metrics (Total Faculty, First-Class/Master's Degrees, Student-Teacher Ratio, B.Ed/ICT Certifications).

## 2. Leadership Spotlight & Editorial Asymmetry

- [x] 2.1 Build the asymmetric 65/35 desktop grid (`grid grid-cols-1 lg:grid-cols-12 gap-8`) for the Leadership Spotlight section.
- [x] 2.2 Construct the Headmaster (প্রধান শিক্ষক) flagship profile card (Tier 1 Winner) with large portrait, institutional seal, leadership manifesto quote, credentials (`এম.এ, বি.এড ১ম শ্রেণি`), and direct contact actions.
- [x] 2.3 Construct the Assistant Headmaster (সহকারী প্রধান শিক্ষক) spotlight card highlighting academic supervision, examination coordination, and office hours.

## 3. Core Faculty Directory & Modern Card Design

- [x] 3.1 Construct the modern, premium, unique, and minimalistic teacher card container with generous internal padding (`p-4 sm:p-5`), top hairline accent bar, and architectural 4:5 aspect ratio portrait framing.
- [x] 3.2 Implement strict 4-tier typographic scale on teacher cards (Name, Designation, Qualification, Joining Year/Experience) with high optical contrast.
- [x] 3.3 Add tactile action footer to each card with clickable telephone links (`tel:`) and email links.
- [x] 3.4 Build the interactive department filter pill bar with active green styling (`bg-[#005030] text-white`) and query parameter synchronization.
- [x] 3.5 Build the functional search form (`method="GET"`) targeting `route('frontend.teachers.index')` with a filter reset button and empty state handling.

## 4. Pedagogical Standards & Closing Conversion Action

- [x] 4.1 Construct the Academic Standards & Mentorship section featuring 3 editorial capability cards (Structured Lesson Planning, Smart ICT Lab Classrooms, Remedial Student Mentorship).
- [x] 4.2 Construct the Guardian Consultation & Visiting Schedule CTA block with a Warm Amber (`amber-400`) primary conversion button linking to `route('frontend.contact')`.

## 5. Data Integration, Resiliency & Verification

- [x] 5.1 Implement defensive data layer handling: display dynamic `$teachers` with pagination controls, backed by authentic fallback profiles if unseeded.
- [x] 5.2 Verify complete mobile responsiveness and zero horizontal overflow across mobile (<768px), tablet, and desktop (>=1024px) viewports.
- [x] 5.3 Execute automated test suite to confirm zero regressions across all frontend routes and controller actions.
