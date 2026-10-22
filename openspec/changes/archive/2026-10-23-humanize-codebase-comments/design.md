# Design: Humanize Codebase Comments & Remove Prompt Jargon

## Context

See [proposal.md](proposal.md) for background and motivation. During rapid UI development, prompt text, design recipes, and excessive explanatory comments were committed across several Blade templates, `style.css`, and `main.js`. 

This design establishes a clean commenting convention and maps out exact substitution patterns so every file retains readable navigation anchors without robotic AI noise.

## Goals / Non-Goals

**Goals:**
- Eliminate prompt-specific phrases (`Recipe 2: Break the Symmetry`, `Pick the Winner`, `(The "What")`, `(The "Why")`).
- Replace verbose, grandiose labels with natural, short human engineer comments (`Breadcrumb`, `Leadership`, `Faculty directory`, `Campus facilities`).
- Remove pure visual micro-comments that state the obvious (e.g., redundant `{{-- Hairline Structural Divider --}}` comments on `<div class="home-section-divider"></div>`).
- Retain helpful high-level section bookmarks in long templates to keep navigation quick.
- Guarantee 100% preservation of code execution: zero edits to HTML markup, Blade directives, CSS declarations, or JavaScript logic.

**Non-Goals:**
- No refactoring of CSS class names or structure.
- No changes to Blade layout logic, variables, or routes.
- No changes to JavaScript functionality or DOM event listeners.

## Decisions

### 1. Comment Standard by File Type

| File Type | Syntax | Style Guideline | Example |
| :--- | :--- | :--- | :--- |
| **Blade Views** | `{{-- Comment --}}` | Sentence case or simple title case. 1-4 words. No numbers, no recipe names, no parentheses describing rhetorical questions. | `{{-- Faculty directory --}}` instead of `{{-- 5. Core Faculty & Staff Directory (The "What") --}}` |
| **CSS Stylesheets** | `/* Comment */` | Lowercase or clean title case section headers. No grand theoretical terms. | `/* Teachers page */` instead of `/* Teachers Page Components & Universal Hover Physics (Matches Home Page) */` |
| **JavaScript** | `// Comment` | Concise statement of intent or fallback purpose. No defensive hype. | `// Visibility fallback check` instead of `// Comprehensive visibility guarantee: ensure NO element across the application stays hidden` |

### 2. Elimination vs. Simplification Strategy

1. **Delete**:
   - Comments on self-explanatory divider tags:
     `{{-- Hairline Structural Divider --}}` preceding `<div class="home-section-divider"></div>`.
   - Repeated sub-block comments that merely duplicate the HTML tag purpose:
     `{{-- Timeline Dot --}}` on `<span class="w-3 h-3 rounded-full ..."></span>`.
   - Prompt compliance notes:
     `{{-- Dot Grid Calendar Icon matching reference design --}}`.
2. **Simplify**:
   - Major section headers:
     `{{-- 4. Leadership Spotlight (Recipe 2: Break the Symmetry & Recipe 4: Pick the Winner) --}}` → `{{-- Leadership --}}`.
   - Main card/block indicators:
     `{{-- Flagship Winner: Headmaster (65% width / 7 cols) --}}` → `{{-- Headmaster card --}}`.
     `{{-- Fixed Assistant Headmaster Card (35% width / 5 cols) --}}` → `{{-- Assistant headmaster card --}}`.
   - Action / CTA blocks:
     `{{-- 10. The Next Step: Campus Visit & Admissions CTA Block (Recipe 3, Step 6) --}}` → `{{-- Campus visit CTA --}}`.

### 3. File Execution Scope

The cleanup targets 4 distinct groups:
1. **Frontend Views**:
   - `resources/views/frontend/teachers/index.blade.php`
   - `resources/views/frontend/about/index.blade.php`
   - `resources/views/frontend/home/index.blade.php`
   - `resources/views/frontend/home/partials/notice-board.blade.php`
   - `resources/views/frontend/home/partials/fullscreen-slider.blade.php`
   - `resources/views/frontend/home/partials/stats-counter.blade.php`
   - `resources/views/frontend/notices/index.blade.php`
   - `resources/views/frontend/notices/show.blade.php`
2. **Layout Partials**:
   - `resources/views/layouts/partials/topbar.blade.php`
   - `resources/views/layouts/partials/navbar.blade.php`
   - `resources/views/layouts/partials/footer.blade.php`
3. **Assets**:
   - `public/assets/css/style.css`
   - `public/assets/js/main.js`

## Risks / Trade-offs

- **[Risk] Accidental deletion of Blade tags or closing braces** → **Mitigation**: Perform precise line-targeted replacements. Run git diff review and verify syntax with `php artisan view:cache` or by rendering templates.
- **[Risk] Loss of developer orientation in long Blade files** → **Mitigation**: Do not delete major structural section headers; convert them into clean, short human markers (e.g., `{{-- Section: Notices --}}` or `{{-- Leadership --}}`).
