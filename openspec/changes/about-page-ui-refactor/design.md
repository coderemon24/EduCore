# Design: About Page UI Refactoring & Redesign

## Context

The public About page (`resources/views/frontend/about/index.blade.php`) is powered by `HomeController::about()`, which supplies `$school` (`CurrentSchoolContext::get()`) and `$about` (`AboutPageContent::forSchool($school->id)`). 

While the backend data pipeline and administrative CRUD are functional, the current Blade view exhibits severe frontend design debt:
- Symmetrical 3-column and 4-column cards without visual hierarchy.
- Inline CSS rules in `<style>` that conflict with centralized styles in `public/assets/css/style.css`.
- Inconsistent typographic sizing (under-scaled headlines, undersized `text-xs` paragraphs).
- Lack of an asymmetric layout, qualifying credentials strip, and closing conversion call-to-action (CTA).

This design document outlines the exact DOM reconstruction, layout partitioning, and class composition needed to implement the 7 UI Master Recipes from `ui-refactor.md`.

## Goals / Non-Goals

**Goals:**
- Eliminate all embedded `<style>` blocks from `about/index.blade.php`, delegating 100% of styling to existing tokens in `style.css`.
- Implement an asymmetric 60/40 or 65/35 grid rhythm for all core content sections.
- Enforce the 4-tier typographic scale (`Tier 1` through `Tier 4`) with high optical contrast.
- Standardize the Section Header Rhythm pattern (`.section-header-block`, `.section-header-left`, `.section-kicker`, `.section-title`, `.section-header-right`, `.section-subtitle`).
- Elevate flagship items (e.g., Sheikh Russel Digital Lab, top academic statistics) using the "Pick the Winner" principle.
- Enforce the 5-color semantic discipline (reserve `amber-400` exclusively for the primary CTA button; primary green `#005030` for brand anchors).
- Maintain 100% mobile responsiveness with natural vertical stacking and zero horizontal overflow.

**Non-Goals:**
- No changes to database migrations, Eloquent schema, or `about_page_contents` table.
- No modifications to the administrative dashboard (`resources/views/admin/frontend/pages/about.blade.php`) or `AboutPageController`.
- No modifications to the controller signature in `HomeController::about()`.

## Decisions

### 1. Section Rhythm & Architectural Layout
- **Decision**: Reconstruct the page into 6 distinct sections alternating between `bg-white` and `bg-slate-50/60`, separated by `<div class="home-section-divider"></div>`.
- **Rationale**: Replaces the crowded `space-y-8` single container with breathing editorial vertical rhythm (`py-14 sm:py-20 lg:py-24`) that matches the homepage.
- **Alternatives Considered**: Keeping a single continuous background wrapper. Rejected because it flattens the page and causes reader fatigue.

### 2. Elimination of Embedded `<style>` in Favor of Centralized Tokens
- **Decision**: Remove lines 48–114 in `about/index.blade.php`. Rely entirely on `public/assets/css/style.css` (which already defines `.section-kicker`, `.section-kicker-dot`, `.home-section-divider`, `.section-header-block`, etc.) and standard utility classes.
- **Rationale**: Consolidates design tokens, reduces bundle clutter, and ensures consistent hover transitions and border radiuses across all public pages.
- **Alternatives Considered**: Scoping custom CSS in `@push('styles')`. Rejected because redundant classes violate the single-source-of-truth principle.

### 3. Asymmetric Section Compositions (Recipes 2 & 4)
- **History & Campus Showcase**:
  - `lg:col-span-7`: Detailed story paragraphs with a stylized blockquote-style lead paragraph (`border-l-2 border-[#005030] pl-4 bg-slate-50/70 py-2.5 rounded-r`).
  - `lg:col-span-5`: Campus photography card with an overlapping trust pill (`-top-3 -right-3 absolute bg-slate-900/90 text-white border border-slate-700/80 px-3 py-1.5 rounded-full text-xs font-bold`) and a quiet telemetry strip at the bottom.
- **Institutional Manifesto (Vision, Mission & Values)**:
  - `lg:col-span-7`: Flagship Vision & Mission combined manifesto card with generous padding, an institutional icon, and structured commitments.
  - `lg:col-span-5`: Core Ethical Values vertical stack featuring high-contrast check badges and distinct institutional principles.
- **Campus Facilities Showcase**:
  - `lg:col-span-7`: Dominant 65% flagship card featuring the *Sheikh Russel Digital Computer Lab* (hardware specs, internet speed, multimedia capability).
  - `lg:col-span-5`: 35% compact stack for Science Lab, Library, and Playground.

### 4. Semantic Color Discipline (Recipe 6)
- **Primary Brand Green (`#005030`)**: Used for header backgrounds, section title focal accents (`<span class="text-[#005030] font-bold">`), and kicker dots.
- **Warm Amber (`amber-400`)**: Restricted strictly to the closing conversion CTA button (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`). Removed from the established year hero badge.
- **Sky Blue (`sky-600` / `bg-sky-50 text-sky-800 border-sky-200`)**: Reserved for institutional accreditation pills and official notices.

### 5. Closing Conversion Action (Recipe 3, Step 6)
- **Decision**: Add a dedicated Next Step / Call to Action section at the bottom before the footer.
- **Content**: A clean conversion banner inviting prospective guardians and visitors to schedule an in-person campus tour or contact the administrative office, featuring an Amber CTA button linking to `route('frontend.contact')` and a secondary link to `route('frontend.academic.admission')`.

## Risks / Trade-offs

- **[Risk] Long historical text causing visual imbalance on desktop**:
  - *Mitigation*: The 60/40 grid uses `items-start` with sticky positioning on the right-hand media card so both columns balance naturally without awkward whitespace.
- **[Risk] Null or empty database attributes when dynamic values are omitted**:
  - *Mitigation*: Maintain defensive `@if(!empty(...))` Blade checks and utilize the robust fallback data provided by `AboutPageContent::forSchool()` to ensure zero empty containers.
- **[Risk] Responsive overflow on small mobile devices (<360px)**:
  - *Mitigation*: Use responsive grid utilities (`grid-cols-1 lg:grid-cols-12`) and flexible wrapping (`flex-wrap gap-2`) on all badges and meta-strips.
