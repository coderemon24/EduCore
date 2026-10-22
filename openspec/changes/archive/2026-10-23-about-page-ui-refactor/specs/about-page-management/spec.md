# Spec Delta: about-page-management

## MODIFIED Requirements

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
