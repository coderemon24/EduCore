# Specification: Frontend News & Media Portal

## Capability: `frontend-news-page`

### Requirement 1: Public Route Availability
- The route `GET /news` MUST be accessible publicly without authentication.
- The route MUST be named `frontend.news.index`.
- The route `GET /news/{slug}` MUST be accessible publicly and named `frontend.news.show`.
- Both routes MUST execute within the `web` and `frontend.school` middleware stack.

### Requirement 2: Institutional Design Consistency & UI Recipes
- The page MUST extend `layouts.app` and adopt the existing Sonatala High School layout frame.
- The header banner MUST use institutional dark green (`#005030`) with white text, breadcrumbs, and FontAwesome icons.
- The layout MUST adhere to the 7 Master UI Recipes in `ui-refactor.md`:
  - Flagship story card dominance (Recipe 4)
  - Asymmetric composition and responsive card grid (Recipe 2)
  - 4-tier typography with `Noto Sans Bengali` and `Plus Jakarta Sans` (Recipe 5)
  - Strict palette mapping with `#005030` and amber accents (Recipe 6)
  - Section kicker with pulsing dot and signature accents (Recipe 7)
- The page MUST be 100% responsive on mobile (<640px), tablet (640-1024px), and desktop (>1024px).

### Requirement 3: Interactive GSAP Animations
- Hero elements MUST hook into existing GSAP entrance triggers (`.page-hero-banner`, `.page-hero-title`).
- News cards and sidebar cards MUST leverage `.gsap-reveal` for staggered ScrollTrigger entrance.
- News cards MUST feature smooth hover transitions (`hover-card-lift`, image scaling, link arrow slide).

### Requirement 4: Search & Category Filtering
- The news portal MUST support filtering by category via query parameter `category`.
- The news portal MUST support keyword search via query parameter `q`.
- When filtering returns results or fallback data, items MUST be clearly displayed with pagination or active filter indicators.

### Requirement 5: Navigation Integration
- Navigation header (`navbar.blade.php`) and mobile drawer MUST link the "সংবাদ" item directly to `route('frontend.news.index')`.
- Active route state MUST highlight when visiting `/news*`.
