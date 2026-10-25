# Tasks: Frontend News & Media Portal

## Backend Engineering Tasks
- [ ] Implement `App\Http\Controllers\Frontend\NewsController` with `index` and `show` actions.
- [ ] Configure `routes/web.php` with `frontend.news.index` and `frontend.news.show` routes.
- [ ] Add category taxonomy, keyword filtering, and structured fallback records in `NewsController`.
- [ ] Write Feature tests in `tests/Feature/NewsPageTest.php` covering 200 HTTP response, search, filtering, and detail page rendering.
- [ ] Update `tests/Feature/FrontendPagesRenderTest.php` to include `frontend.news.index`.

## Frontend Engineering Tasks
- [ ] Create `resources/views/frontend/news/index.blade.php` matching the existing school theme and 7 UI recipes.
- [ ] Create `resources/views/frontend/news/show.blade.php` for detailed single news viewing.
- [ ] Update `resources/views/layouts/partials/navbar.blade.php` to link "সংবাদ" (desktop & mobile) to `route('frontend.news.index')` with active state detection.
- [ ] Apply GSAP scroll entrance classes (`.gsap-reveal`, `.page-hero-title`) and micro-interaction hover classes (`hover-card-lift`).
- [ ] Ensure 100% responsiveness and FontAwesome 6 icons across all viewport breakpoints.
