# Tasks: Frontend News & Media Portal

## Backend Engineering Tasks
- [x] Implement `App\Http\Controllers\Frontend\NewsController` with `index` and `show` actions.
- [x] Configure `routes/web.php` with `frontend.news.index` and `frontend.news.show` routes.
- [x] Add category taxonomy, keyword filtering, and structured fallback records in `NewsController`.
- [x] Write Feature tests in `tests/Feature/NewsPageTest.php` covering 200 HTTP response, search, filtering, and detail page rendering.
- [x] Update `tests/Feature/FrontendPagesRenderTest.php` to include `frontend.news.index`.

## Frontend Engineering Tasks
- [x] Create `resources/views/frontend/news/index.blade.php` matching the existing school theme and 7 UI recipes.
- [x] Create `resources/views/frontend/news/show.blade.php` for detailed single news viewing.
- [x] Update `resources/views/layouts/partials/navbar.blade.php` to link "সংবাদ" (desktop & mobile) to `route('frontend.news.index')` with active state detection.
- [x] Apply GSAP scroll entrance classes (`.gsap-reveal`, `.page-hero-title`) and micro-interaction hover classes (`hover-card-lift`).
- [x] Ensure 100% responsiveness and FontAwesome 6 icons across all viewport breakpoints.
