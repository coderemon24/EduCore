# Proposal: Remove AI Slop from Admin Dashboard View

## Why

The current administrative dashboard (`resources/views/admin/dashboard.blade.php`) contains multiple AI-generated design and code anti-patterns ("AI slop") that degrade professionalism, maintainability, and user experience. 

Specifically:
- **Cliché buzzwords and pseudo-academic jargon**: Labels such as "Institutional Overview & Academic Telemetry", "Comprehensive academic telemetry, faculty records, student benchmarks, and financial realization", and "Academic assessment index comparing student achievement vs. curriculum milestone targets".
- **Inline dummy JavaScript alerts**: Fragile inline `onclick="window.Swal && Swal.fire(...)"` event handlers scattered across action buttons and table rows instead of adhering to the project's modular JS architecture (`public/admin-assets/js/admin-actions.js`).
- **Inconsistent multi-color pastel card styling**: Arbitrary rainbow borders (`border-violet-100`, `border-sky-100`, `border-amber-100`, `border-emerald-100`) that conflict with the clean, high-contrast, institutional design guidelines established in `.agents/skills/no-slop-ui/SKILL.md` and `openspec/config.yaml`.
- **Arbitrary all-caps eyebrow typography**: Tiny `uppercase tracking-wider` subheaders stacked above every heading.
- **Brittle inline SVG graphics**: Hacky hardcoded SVG coordinate math for monthly bars and radial donut charts that clutter the Blade template.
- **Superfluous non-functional elements**: Useless checkbox inputs in read-only tables and redundant nested alert boxes within widgets.

Refactoring this dashboard brings it into strict compliance with the project's "No-Slop UI" protocol, improving visual clarity, accessibility, and architectural cleanlines.

## What Changes

1. **Dashboard Header & Breadcrumbs**:
   - Replace hyperbolic "Academic Telemetry" terminology with authentic, concise institutional headers ("Dashboard Overview", "School Administrative Overview").
   - Simplify breadcrumb trail to standard hierarchical paths (`Admin / Dashboard`).
   - Remove inline JavaScript onclick events from export and action buttons, delegating interaction to data attributes handled by modular scripts.

2. **Unified Metric Cards**:
   - Standardize KPI cards to use crisp, solid 1px borders (`border-slate-200`) and a cohesive palette rather than mismatched pastel border tints.
   - Standardize typography to disciplined font sizes, weights, and high contrast, removing excessive uppercase tracking-wider eyebrow styling.

3. **Analytics & Performance Section**:
   - Refactor the hardcoded SVG bar chart and gender ratio donut chart into clean, semantic, responsive layouts with structured metrics, high information density, and accessible presentation.
   - Remove pseudo-scientific explanatory text and replace with clear institutional captions.

4. **Data Tables & Notice Board**:
   - Clean up the Star Students merit table: eliminate dummy unhandled checkboxes, remove inline `Swal.fire` event handlers, provide accessible table structure with clean badge and action styling.
   - Refactor Institutional Notices widget: remove repetitive "Official" pill badges and the redundant nested alert callout, presenting alerts with clear hierarchy, date stamps, and category tags.

## Capabilities

### New Capabilities
*(None)*

### Modified Capabilities
- `admin-dashboard`: Refactor dashboard view requirements to ban inline event handlers and pseudo-scientific AI jargon, enforcing cohesive solid borders, disciplined typography, and clean semantic structure.

## Impact

- **Templates**: `resources/views/admin/dashboard.blade.php` will be thoroughly cleaned and streamlined.
- **Controllers & Routing**: No breaking changes to `App\Http\Controllers\Admin\AdminController` or `routes/admin.php`; data bindings are preserved.
- **JavaScript**: Dashboard interactive hooks will cleanly interface with `public/admin-assets/js/admin-actions.js` via semantic `data-action` attributes instead of inline Blade scripts.
- **Dependencies**: No external library additions.
