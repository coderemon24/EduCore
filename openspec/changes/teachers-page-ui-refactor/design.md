# Design: Teachers Page UI Refactoring & Redesign

## Context

The public Teachers & Faculty Directory (`resources/views/frontend/teachers/index.blade.php`) is rendered by `TeacherController@index`, supplying `$school`, `$teachers` (`Staff::paginate(12)`), and `$departments` (`Staff::distinct()->pluck('department')`).

While the backend controller already provides search (`?q=`) and department filtering (`?department=`), the existing Blade template is currently disconnected from this functionality, relying on a hardcoded, symmetrical 4-column passport card grid with non-functional inputs and flat spacing.

This design document outlines the exact architectural decomposition, component hierarchy, card design, and class compositions needed to implement the 7 UI Master Recipes from `ui-refactor.md`.

## Goals / Non-Goals

**Goals:**
- Reconstruct the page into 6 distinct narrative sections with alternating backgrounds (`bg-white` and `bg-slate-50/60`) and `.home-section-divider` hairline dividers.
- Implement an asymmetric 65/35 Leadership Spotlight section elevating the Headmaster (Tier 1 Flagship) and Assistant Headmaster over identical grid cards.
- Design an editorial-grade, modern, premium, and minimalistic teacher card with proper margins, paddings, architectural 4:5 portrait framing, and interactive touchpoints (`tel:`, email).
- Connect the search input and department filter pills directly to `route('frontend.teachers.index')`.
- Ensure data layer resilience: render dynamic `$teachers` with pagination controls, backed by defensive fallback data to guarantee 100% complete visual fidelity in all database states.
- Enforce the 4-tier typographic scale and the 5-color semantic discipline (reserve `amber-400` exclusively for the consultation CTA button).
- Maintain 100% mobile responsiveness with natural vertical stacking and zero horizontal overflow.

**Non-Goals:**
- No changes to database schema, migrations, or `staff` table.
- No modifications to the administrative dashboard (`resources/views/admin/staff/*`) or admin controllers.
- No modifications to the backend query logic in `TeacherController@index`.

## Decisions

### 1. Section Rhythm & Architectural Layout (Recipe 3)
- **Decision**: Structure the view into 6 distinct sections:
  1. *Executive Breadcrumb & Institutional Hero Banner* (`bg-[#005030] py-14 sm:py-20`)
  2. *Qualifying Telemetry Strip* (`bg-slate-900 text-slate-200 py-4`)
  3. *Leadership Spotlight* (`bg-white py-14 sm:py-20`)
  4. *Core Faculty & Staff Directory* (`bg-slate-50/60 py-14 sm:py-20 lg:py-24`)
  5. *Pedagogical Standards & Mentorship Pillars* (`bg-white py-14 sm:py-20`)
  6. *Guardian Consultation & Visiting Schedule CTA* (`bg-slate-50/80 py-14 sm:py-20`)
- **Rationale**: Replaces the cramped single container (`py-8 space-y-6`) with breathing vertical rhythm matching the Homepage and About page.

### 2. Asymmetric Leadership Spotlight (Recipes 2 & 4)
- **Decision**: Use a 65/35 desktop grid (`grid grid-cols-1 lg:grid-cols-12 gap-8`):
  - `lg:col-span-7`: Headmaster flagship card featuring high-resolution portrait, institutional seal, educational philosophy manifesto quote, credentials (`এম.এ, বি.এড ১ম শ্রেণি`), and direct office contact buttons.
  - `lg:col-span-5`: Assistant Headmaster card highlighting academic coordination, examination administration, and consultation hours.
- **Rationale**: Solves the lack of hierarchy where institution heads were visually lost in an identical grid.

### 3. Modern Minimalistic Teacher Card Architecture
- **Decision**:
  - Container: `bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md hover:border-[#005030]/40 transition-all duration-300 flex flex-col justify-between group`.
  - Top Accent Hairline: `h-1 w-full bg-slate-100 group-hover:bg-[#005030] transition-colors rounded-full mb-3`.
  - Portrait Framing: `aspect-[4/5] w-full rounded-xl overflow-hidden bg-slate-100 relative mb-3.5 border border-slate-200/60`.
  - Pinned Department Tag: `absolute bottom-2 left-2 bg-slate-900/80 text-white backdrop-blur-xs text-[11px] font-bold px-2.5 py-0.5 rounded-md`.
  - Typography Block: Name in `text-base sm:text-lg font-bold text-slate-900 group-hover:text-[#005030] transition-colors`, designation in `text-xs font-semibold text-emerald-800`, qualification in `text-xs text-slate-600 line-clamp-2`.
  - Action Footer: Clickable `tel:` phone call button and mail link with hover transitions.
- **Alternatives Considered**: Keeping round avatars. Rejected because passport squircle/rectangle portraits fit academic conventions far better and provide distinct editorial presence.

### 4. Interactive Department Filtering & Search Form
- **Decision**:
  - Department filter pills: A horizontal scrollable flex container of pills (`px-4 py-2 rounded-full text-xs font-semibold`) with active state (`bg-[#005030] text-white`) and inactive state (`bg-white text-slate-700 border border-slate-200 hover:bg-slate-100`).
  - Search Form: A clean search bar with an enclosed `<form action="{{ route('frontend.teachers.index') }}" method="GET">`, maintaining active department selection via a hidden input, with a clear/reset action when query parameters exist.

### 5. Semantic Color Discipline (Recipe 6)
- **Primary Brand Green (`#005030`)**: Used for header backgrounds, focal headline accents (`<span class="text-[#005030] font-bold">`), kicker dots, and active department pills.
- **Warm Amber (`amber-400`)**: Strictly reserved for the primary conversion CTA button (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`).
- **Sky Blue (`sky-600` / `bg-sky-50 text-sky-800 border-sky-200`)**: Reserved for office hours and advisory notice badges.

### 6. Data Layer Resiliency
- **Decision**: Check if dynamic `$teachers` contains items. If populated, render `$teachers` with `$teachers->links()`. If empty or unseeded, render the curated collection of authentic faculty profiles so the view never renders empty or broken.

## Risks / Trade-offs

- **[Risk] Missing teacher photo in database**:
  - *Mitigation*: Defensive fallback to realistic high-quality avatars and SVG initials placeholders so cards never display broken image icons.
- **[Risk] Excessive search terms returning zero records**:
  - *Mitigation*: Provide an editorial empty state with a "ফিল্টার রিসেট করুন" button redirecting to `route('frontend.teachers.index')`.
- **[Risk] Long teacher designations causing card misalignment**:
  - *Mitigation*: Flexbox `flex flex-col justify-between` layout ensures card heights remain uniform across each row regardless of text length.
