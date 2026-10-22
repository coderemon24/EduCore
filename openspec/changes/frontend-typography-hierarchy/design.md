# Design: Frontend Typography Hierarchy

## Context

See [proposal.md](file:///C:/laragon/www/laravel-school/openspec/changes/frontend-typography-hierarchy/proposal.md) for motivation and problem background.

The public school portal is built with Laravel 12 Blade views (`resources/views/frontend/`) and styled with Tailwind CSS v4 (`public/assets/css/style.css` and `resources/css/app.css`). The font stack declared on the frontend includes `Noto Sans Bengali` for Bengali institutional text and `Plus Jakarta Sans` / `Instrument Sans` for Latin glyphs. Prior implementations frequently assigned micro font sizes (`text-[11px]`, `text-[13px]`, `text-xs`) across disparate elements (both body copy and labels), flattening the visual depth.

## Goals / Non-Goals

**Goals:**
- Implement a distinct 4-tier typographic scale across all public frontend views (`resources/views/frontend/`).
- Enhance readability of complex Bengali characters by guaranteeing body copy renders at `text-sm` (14px) or `text-base` (16px) with comfortable line height (`leading-relaxed`).
- Subdue secondary scaffolding, category tags, kickers, and timestamps into a dedicated quiet tier (`text-xs` / `text-[11px]`, muted slate tone, subtle tracking).
- Apply exactly one accent-colored highlighted phrase per major public section title.
- Keep layout structures (grid column spans, flex wrappers, container widths) and non-typography background colors completely unchanged.

**Non-Goals:**
- **Zero changes to Admin Dashboard**: The admin dashboard (`resources/views/admin/*`, `resources/views/layouts/admin.blade.php`, and `public/admin-assets/*`) is strictly excluded.
- **No layout or structural alterations**: No rearranging cards, altering column ratios (e.g. 60/40 or 3-column grids), or modifying component layouts.
- **No changes to backend data/models**: No controllers, database migrations, or server-side payload alterations.

## Decisions

### 1. Concrete 4-Tier Utility Mapping

| Tier | Semantic Role | Tailwind Utility Classes | Applied To |
| :--- | :--- | :--- | :--- |
| **Tier 1 (Dominant)** | Hero Titles & Flagship Section Headings | `text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight` | Slider H1, Section H2 titles ("WHAT WE DELIVER", "NOTICES", etc.) |
| **Tier 2 (Structural)** | Card Headlines & Subsections | `text-xl sm:text-2xl font-bold leading-snug text-slate-900` | Feature hero card titles, notice item headers, headmaster title |
| **Tier 3 (Body Copy)** | Readable Content & Descriptions | `text-sm sm:text-base font-normal leading-relaxed text-slate-700` | Section intro paragraphs, card descriptions, notice excerpts, quotes |
| **Tier 4 (Quiet Meta)** | Scaffolding, Badges, Dates, Counters | `text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500` | Kickers, audience tags, publication dates, category badges, author labels |

*Alternative Considered*: Using custom arbitrary CSS classes in `style.css`. Rejected in favor of standard Tailwind utility classes directly in Blade templates to ensure maintainability and full compilation transparency under Tailwind v4.

### 2. Headline Accent Highlighting Restraint

In each major public section header (`.section-title` or equivalent H2):
- The core headline is rendered in high-contrast neutral (`text-slate-900` on light backgrounds, `text-white` on dark sections).
- Exactly one thematic phrase (2–4 words) is wrapped in `<span class="text-emerald-600 dark:text-emerald-400">...</span>` or institutional accent.
- Smaller cards, subheadings, and body copy MUST NOT use accent highlights to preserve visual contrast.

### 3. File Scope Isolation

All typographic changes are confined to:
- `resources/views/frontend/home/index.blade.php` and its partials (`fullscreen-slider.blade.php`, etc.)
- `resources/views/frontend/about/index.blade.php`
- `resources/views/frontend/notices/index.blade.php` and `show.blade.php`
- `resources/views/layouts/app.blade.php`

All administrative views (`resources/views/admin/**`) and layout files (`resources/views/layouts/admin.blade.php`) remain untouched.

## Risks / Trade-offs

- **[Risk] Line-height expansion causing card height growth**: Increasing body copy from `11px` to `14px` (`text-sm`) can increase text block height.
  $\to$ *Mitigation*: Pair with `leading-relaxed` (1.625) and verify responsive wrapping across cards without causing overflow or breaking grid alignment.
- **[Risk] Accidental modification of layout padding or margins**:
  $\to$ *Mitigation*: Strictly modify `text-*`, `font-*`, `leading-*`, and `tracking-*` classes; do not alter `p-*`, `m-*`, `gap-*`, or `grid-cols-*`.
