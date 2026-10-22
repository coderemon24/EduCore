# Proposal: Humanize Codebase Comments & Remove Prompt Jargon

## Why

During iterative UI/UX implementation and AI-assisted pair-programming, multiple frontend Blade templates, stylesheets, and JavaScript files accumulated comments that sound robotic, unnatural, or cluttered. Specifically:
- **Prompt echoes**: Direct copies of design framework recipes (e.g., `(Recipe 2: Break the Symmetry & Recipe 4: Pick the Winner)`, `(The "What")`, `(The "Why")`).
- **User reference remnants**: Internal prompts and review markers (e.g., `(Exact match to reference design)`, `(Matching User Reference Image)`).
- **Defensive micro-comments**: Overly cautious explanations for standard CSS/JS properties (e.g., `(NOT Too Tall)`, `guaranteed clearance for the search icon`, `NEVER get stuck at opacity: 0`).
- **Grandiosity and marketing buzzwords**: Pretentious section titles where simple labels belong (e.g., `Executive Breadcrumb Bar`, `Universal Hover Physics`, `Primary Conversion Action (Strictly Reserved Warm Amber)`).
- **Cluttered repetitions**: Repeated comments on structural dividers (e.g., `{{-- Hairline Structural Divider --}}` repeated 6+ times in single views).

Cleaning these up makes the codebase feel handcrafted, professional, readable, and maintained by experienced human engineers.

## What Changes

- **Frontend Blade Views (`resources/views/frontend/`)**:
  - Replace verbose section comments with concise, natural human headings (e.g., `{{-- Breadcrumb --}}`, `{{-- Leadership --}}`, `{{-- Faculty directory --}}`, `{{-- Admissions CTA --}}`).
  - Strip repetitive divider comments (`{{-- Hairline Structural Divider --}}`) and micro-comments that label self-explanatory HTML elements.
  - Purge prompt residue such as recipe references and design instructions.
- **Layout Partials (`resources/views/layouts/partials/`)**:
  - Clean up navigation, topbar, and footer comments in `topbar.blade.php`, `navbar.blade.php`, and `footer.blade.php`.
- **Custom Stylesheet (`public/assets/css/style.css`)**:
  - Replace theoretical design comments (e.g., `Universal Hover Physics`, `Asymmetric Bento Hierarchy`, `guaranteed clearance`) with standard CSS section headings (e.g., `/* Teachers page */`, `/* Capabilities */`, `/* Search input */`).
- **Frontend JavaScript (`public/assets/js/main.js`)**:
  - Replace dramatic comments (`Bulletproof: Elements remain 100% visible by default and NEVER get stuck at opacity: 0`) with brief, standard developer notes (e.g., `// Visibility fallback`).

No application markup, CSS styling, responsive behavior, or backend PHP logic will be modified.

## Capabilities

### New Capabilities
None. This is a code hygiene and refactoring change.

### Modified Capabilities
None. System capabilities and business requirements are unchanged.

## Impact

- **Affected Files**:
  - `resources/views/frontend/teachers/index.blade.php`
  - `resources/views/frontend/about/index.blade.php`
  - `resources/views/frontend/home/index.blade.php`
  - `resources/views/frontend/home/partials/notice-board.blade.php`
  - `resources/views/frontend/home/partials/fullscreen-slider.blade.php`
  - `resources/views/layouts/partials/topbar.blade.php`
  - `resources/views/layouts/partials/footer.blade.php`
  - `public/assets/css/style.css`
  - `public/assets/js/main.js`
- **Runtime & Build Impact**: Zero. Only code comments are modified. No CSS rules, Blade variables, or JavaScript behaviors are changed.
