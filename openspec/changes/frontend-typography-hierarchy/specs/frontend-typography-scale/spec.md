# Spec Delta: frontend-typography-scale

## Purpose
Establishes a unified 4-tier typographic scale, readable font sizes for Bengali and Latin scripts, disciplined headline accent styling, and quiet metadata tiers across the public school frontend portal without altering layouts or touching admin dashboards.

## ADDED Requirements

### Requirement: Four-Tier Typographic Hierarchy Scale
The public school frontend views SHALL strictly enforce a 4-tier typographic scale where the primary titles are unmistakably the largest, section titles form a distinct second tier, body copy and descriptions provide comfortable reading size for Bengali characters, and metadata sits in a quiet tier.

#### Scenario: Visual presentation of section headings and cards
- **WHEN** a visitor navigates through public pages (Homepage, About, Notices, Academic)
- **THEN** main section headlines render in Tier 1 (`text-3xl` to `text-5xl` with `font-extrabold` or `font-bold`), card headlines and secondary titles render in Tier 2 (`text-xl` to `text-2xl`), body paragraphs and descriptive copy render in Tier 3 (`text-sm` or `text-base` with `leading-relaxed`), ensuring clear optical jumps between levels.

#### Scenario: Legibility of Bengali script
- **WHEN** Bengali text is displayed in body descriptions and card content
- **THEN** font size is at least `text-sm` (14px) or `text-base` (16px) with adequate line-height (`leading-relaxed` or `leading-normal`), preventing vertical glyph crowding.

---

### Requirement: Quiet Tier for Scaffolding, Badges, and Metadata
The public frontend SHALL position auxiliary labels, audience tags, category pills, kickers, timestamps, and character counts into a quiet tier that does not visually compete with substantive content.

#### Scenario: Display of kickers, dates, and category pills
- **WHEN** section kickers, publication dates, notice categories, or qualifying audience tags are rendered
- **THEN** they utilize subtle font sizes (`text-xs` or `text-[11px]`), muted weights (`font-medium` or `font-semibold`), gentle letter-spacing (`tracking-wider` or `tracking-normal`), and subdued tones (`text-slate-400`, `text-slate-500`, or quiet translucent tints).

---

### Requirement: Headline Accent Highlight Discipline
The public frontend section titles SHALL emphasize exactly one focal phrase per headline in the institution's brand accent color, avoiding repetitive or excessive multi-word color washes.

#### Scenario: Accent phrase rendering on public headlines
- **WHEN** major section headlines are displayed on the public landing page
- **THEN** exactly one thematic phrase per headline is highlighted in the brand accent color (`text-emerald-600` or `text-emerald-400` depending on surface brightness) wrapped in a styled `<span>`, while the remainder of the headline stays in neutral high-contrast tone (`text-slate-900` or `text-white`).

---

### Requirement: Strict Layout and Administrative Isolation
The typographic hierarchy updates SHALL modify only typography-related utility classes on the public frontend without altering DOM structure, grid/flex layouts, background colors, or any administrative dashboard views.

#### Scenario: Verification of administrative dashboard boundaries
- **WHEN** the administrative portal (`/admin/*`) is loaded or inspected
- **THEN** zero administrative Blade templates (`resources/views/admin/*`), admin layouts (`resources/views/layouts/admin.blade.php`), or admin stylesheet assets are modified or affected by this change.

#### Scenario: Verification of layout preservation on public pages
- **WHEN** typographic utility classes are updated on public views
- **THEN** grid column definitions (`grid-cols-*`), flex arrangements, container widths (`max-w-*`), padding, and background surface colors remain identical to their previous structural specifications.
