# Technical Design: Frontend News & Media Portal

## Architectural Approach
The feature provides a cohesive client-facing news portal built on Laravel 11/12/13 conventions and Tailwind CSS / Vanilla CSS design system.

### 1. Routes & Controller Architecture
- **Route Definitions** (`routes/web.php`):
  - `GET /news` -> `App\Http\Controllers\Frontend\NewsController@index`, named `frontend.news.index`.
  - `GET /news/{slug}` -> `App\Http\Controllers\Frontend\NewsController@show`, named `frontend.news.show`.
- **Controller Responsibilities** (`NewsController`):
  - Resolves `CurrentSchoolContext::get()`.
  - Supports query parameters: `category` (slug), `q` (search keywords), and `page` (pagination).
  - Supplies curated rich fallback news items when the database table has no published records, guaranteeing an immediate, production-ready visual showcase.
  - Passes view variables: `$school`, `$featuredNews`, `$newsList`, `$categories`, `$recentNews`, and current query params.

### 2. View Architecture & Design System Integration
- **Directory Structure**:
  - `resources/views/frontend/news/index.blade.php`: Main news index canvas with flagship card, news grid, category pills, search bar, and sidebar widgets.
  - `resources/views/frontend/news/show.blade.php`: Single article deep-dive template with meta tags, publication timestamp, reading time estimate, social share triggers, content body, and related news cards.
  - Updates to `resources/views/layouts/partials/navbar.blade.php`: Link both desktop and mobile drawer "সংবাদ" items to `route('frontend.news.index')`.

### 3. UI/UX Compliance & 7 Master Recipes (`ui-refactor.md`)
- **Recipe 1 (Sharpen Message)**: Clear, bold institutional headline with `<span class="text-[#005030] font-bold">`.
- **Recipe 2 (Break Symmetry)**: Flagship 65/35 visual split with anchor pill badges and featured card dominance.
- **Recipe 3 (Build Argument)**: Standard `.section-header-block` and `.home-section-divider`.
- **Recipe 4 (Pick the Winner)**: Hero flagship news story dominates with prominent photography, high-contrast kicker, and excerpt before branching into secondary grid.
- **Recipe 5 (Scale the Type)**: 4-tier typographic hierarchy (Headline text-3xl to 5xl, Card text-lg to 2xl, Body text-sm to base leading-relaxed, Kicker text-xs uppercase).
- **Recipe 6 (Give Color Meaning)**: Institutional `#005030` deep green for identity and headers; warm amber `amber-400` for primary highlight/read badges; calm slate neutrals.
- **Recipe 7 (Add a Signature)**: Pulsing kicker dot, smooth card elevation on hover, micro-interactions on read buttons.

### 4. Animation & GSAP Integration
- Seamless integration with `public/assets/js/main.js`:
  - Uses `.page-hero-banner`, `.page-hero-title`, `.page-hero-badge`, `.page-hero-subtitle` for hero sequence.
  - Cards tagged with `.gsap-reveal` and `.gsap-reveal-card` for staggered ScrollTrigger entrance.
  - Dedicated hover micro-interactions (`.hover-card-lift`, `.group-hover:scale-105`).
