# Proposal: Teachers Page UI Refactoring & Redesign

## Why

The current public Teachers & Faculty Directory page (`resources/views/frontend/teachers/index.blade.php`) exhibits critical frontend design debt and incomplete interactive behavior:
1. **Outdated Passport-Style Grid**: The faculty directory renders as an undifferentiated 4-column grid of identical, boxy passport-photo cards (`w-24 h-28`) with dull gray borders (`border-gray-300`). It lacks modern proportions, tactile elevation, and aesthetic polish.
2. **Absence of Hierarchy ("Pick the Winner")**: The Headmaster (প্রধান শিক্ষক) and Assistant Headmaster (সহকারী প্রধান শিক্ষক) are lost inside the identical grid with the exact same weight as general staff, rather than being elevated as institutional leaders.
3. **Flat, Non-Engaging Scroll ("Build the Argument")**: The page transitions abruptly from a basic green container into the card grid. It lacks a qualifying faculty credentials strip, pedagogical standards pillars, and a closing next-step CTA for guardian consultation.
4. **Cramped Spacing & Missing Tokens**: The page uses an ad-hoc `py-8 space-y-6` wrapper instead of the project's standard breathing section rhythm (`py-14 sm:py-20 lg:py-24`).
5. **Non-Functional Form Controls**: The search input in the header banner is a disconnected `<input>` with no `<form>` submission wrapper, the department list (`$departments`) passed by the controller is not rendered as filter tabs, and pagination links (`$teachers->links()`) are missing.

Refactoring this page according to the **Universal UI Refactoring Framework** (`ui-refactor.md`) will transform it into an editorial-grade, modern, premium, unique, and minimalistic faculty directory that matches the homepage and about page.

## What Changes

- **Recipe 1: Sharpen the Message & Voice**: Replace generic headlines with authoritative, plain-spoken institutional copy celebrating experienced educators and mentors. Enforce exactly one `<span class="text-[#005030] font-bold">` focal accent per section headline.
- **Recipe 2 & 4: Break the Symmetry & Pick the Winner**: Implement an asymmetric 65/35 Leadership Spotlight section:
  - **Flagship Headmaster Spotlight (65%)**: Elevated portrait with institutional seal, educational philosophy manifesto quote, credentials (`এম.এ, বি.এড ১ম শ্রেণি`), 20+ years of leadership, office visiting hours, and direct call/email action buttons.
  - **Assistant Headmaster Spotlight (35%)**: Dedicated card covering academic supervision, examination coordination, and contact details.
- **Recipe 3: Build the Argument & Section Rhythm**: Re-architect the view into a 6-phase sequential narrative:
  1. Executive Hero & Context Banner (`bg-[#005030] py-14 sm:py-20`) with breadcrumbs and faculty count badge.
  2. Qualifying Faculty Telemetry Strip (`bg-slate-900 py-4`) highlighting 32+ faculty members, 85%+ master's/first-class degree holders, 1:30 student-teacher ratio, and 100% B.Ed./ICT certified teachers.
  3. Institutional Leadership Showcase (Headmaster & Assistant Headmaster asymmetric spotlight).
  4. Core Faculty & Staff Directory (Interactive department filter pills, functional search bar, and modern minimalistic teacher cards).
  5. Academic Mentorship & Pedagogical Standards (3 editorial pillars: Structured Lesson Planning, Smart ICT Lab Classrooms, and Remedial Mentorship).
  6. Closing Consultation Action Block (Guardian meeting schedule and an Amber CTA button).
- **Teacher Card Redesign (Modern, Premium, Unique & Minimalistic)**:
  - Generous internal padding (`p-4 sm:p-5`), subtle top hairline accent bar, and rounded corners (`rounded-2xl`).
  - Architectural 4:5 aspect ratio portrait frame with hover zoom micro-interaction and overlay department tag badge.
  - Strict 4-tier typographic hierarchy for teacher name, designation, qualification, and experience.
  - Functional action footer with one-touch phone call (`tel:`) and email links.
- **Interactivity & Data Integration**:
  - Encapsulate search input inside a functional GET form targeting `route('frontend.teachers.index')` with clear/reset controls.
  - Render interactive department filter pills using `$departments` with active state styling.
  - Support dynamic `$teachers` with defensive fallback data to guarantee 100% complete and visually stunning presentation regardless of database seeding state.
- **Color & Aesthetic Restraint**: Enforce the 5-color semantic discipline: Institutional Dark Green (`#005030`), Warm Amber (`amber-400`) strictly reserved for the primary CTA button, Sky Blue (`sky-600`) for office hours/official badges, and calm neutral surfaces (`bg-white`, `bg-slate-50/60`, `border-slate-200/90`).
- **Zero Admin Modifications**: Strictly preserve `resources/views/admin/*`, database migrations, and administrative controllers.

## Capabilities

### New Capabilities
None.

### Modified Capabilities
- `client-school-portal`: Refactor the public faculty directory (`/teachers`) requirement to specify the 6-phase sequential narrative rhythm, asymmetric leadership spotlight, modern minimalistic card design, and interactive department filtering.

## Impact

- **Frontend Blade View**: `resources/views/frontend/teachers/index.blade.php` refactored and modernized.
- **Controller & Routes**: `App\Http\Controllers\Frontend\TeacherController` and `route('frontend.teachers.index')` leveraged with full query parameter support (`q`, `department`).
- **CSS Tokens**: Centralized tokens in `public/assets/css/style.css` (`.section-kicker`, `.section-header-block`, `.home-section-divider`) reused without introducing ad-hoc style tags.
- **Database & Admin**: No schema changes; administrative portal untouched.
- **Dependencies**: No external npm or composer packages added.
