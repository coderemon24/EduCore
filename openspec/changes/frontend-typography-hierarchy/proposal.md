# Proposal

## Why

The public frontend portal (including the homepage, institutional profile, notice board, and academic pages) currently suffers from compressed typographic hierarchy. Secondary descriptions, card labels, audience tags, and metadata frequently sit in the same optical tier (`11px` to `13px`) as body text, while Bengali script (`Noto Sans Bengali`) requires adequate breathing room, vertical line-height, and disciplined optical jumps to be comfortably readable.

To elevate visual distinction and scan-ability without risking visual regressions or layout breakage, a unified 4-tier typographic hierarchy must be established exclusively across the public frontend views, leaving the administrative dashboard completely untouched.

## What Changes

- **Public Frontend Only**: Scope is strictly restricted to `resources/views/frontend/` and public layout components (`resources/views/layouts/app.blade.php`). No files within `resources/views/admin/` or administrative assets will be modified.
- **Clear 4-Tier Type Scale**:
  - **Tier 1 (Dominant)**: Hero title and major section titles scaled up (`text-3xl` to `text-5xl`), font-extrabold/bold, with proportional line-heights.
  - **Tier 2 (Section & Feature Titles)**: Card headlines and subsection titles set to `text-xl` / `text-2xl` with crisp leading.
  - **Tier 3 (Content & Body Copy)**: Body paragraphs and descriptive copy normalized to `text-sm` (14px) / `text-base` (16px), ensuring high legibility for Bengali typography.
  - **Tier 4 (Quiet Meta & Labels)**: Category badges, audience kickers, dates, and metadata placed into a quiet tier (`text-xs` / `text-[11px]`, uppercase or muted weight, quiet slate tone).
- **Headline Accent Styling**: For major public section headlines, emphasize exactly one key phrase in the brand accent color (`text-emerald-600` or `text-emerald-400` depending on dark/light surface context).
- **Strict Boundary Constraints**: Only typography utility classes (`text-*`, `font-*`, `leading-*`, `tracking-*`, and targeted headline highlight spans) will be modified. Layout dimensions, flex/grid containers, existing DOM structure, and background colors will remain completely intact.

## Capabilities

### New Capabilities
- `frontend-typography-scale`: Defines and enforces a unified 4-tier typographic scale, readable Bengali/Latin optical sizes, quiet label tiers, and selective headline accent styling across all public school frontend views.

### Modified Capabilities
<!-- None: Existing backend or administrative capabilities remain unaltered. -->

## Impact

- **Affected Views**:
  - `resources/views/frontend/home/index.blade.php` and home partials (hero slider, qualifying strip, capabilities, notice board section, statistics, headmaster message).
  - `resources/views/frontend/about/index.blade.php`.
  - `resources/views/frontend/notices/index.blade.php` and `show.blade.php`.
  - `resources/views/layouts/app.blade.php`.
- **Zero Impact on Admin**:
  - All files in `resources/views/admin/` and `resources/views/layouts/admin.blade.php` remain completely unaffected.
- **Zero Schema / Database Impact**: No migrations, database models, or backend controllers are altered.
