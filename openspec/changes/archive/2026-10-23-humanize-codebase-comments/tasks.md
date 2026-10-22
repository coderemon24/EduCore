# Tasks: Humanize Codebase Comments & Remove Prompt Jargon

## 1. Frontend Core Views Cleanup

- [x] 1.1 Clean up comments in `resources/views/frontend/teachers/index.blade.php` (remove `Recipe 2/4`, `(The "What")`, `(The "Why")`, `NOT Too Tall`, `Hairline Structural Divider`, convert section markers to simple labels).
- [x] 1.2 Clean up comments in `resources/views/frontend/about/index.blade.php` (remove `Recipe 3, Step 6`, `Pick the Winner`, repeated `Hairline Structural Divider`, replace grandiose headers with concise markers).
- [x] 1.3 Clean up comments in `resources/views/frontend/home/index.blade.php` (remove prompt recipe headers, all-caps numbers, rhythm pattern jargon, and defensive element notes).
- [x] 1.4 Clean up comments in `resources/views/frontend/home/partials/` (`notice-board.blade.php`, `fullscreen-slider.blade.php`, `stats-counter.blade.php` - remove `matching reference design`, etc.).

## 2. Layout Partials Cleanup

- [x] 2.1 Clean up comments in `resources/views/layouts/partials/topbar.blade.php` (remove `Matching User Reference Image`, `Flawless Infinite Seamless Looping`).
- [x] 2.2 Clean up comments in `resources/views/layouts/partials/footer.blade.php` (shorten column descriptions and remove aesthetic jargon).
- [x] 2.3 Clean up comments in `resources/views/layouts/partials/navbar.blade.php` (simplify section navigation markers).

## 3. Stylesheet & JavaScript Cleanup

- [x] 3.1 Clean up custom comments in `public/assets/css/style.css` (replace `Universal Hover Physics`, `Asymmetric Bento Hierarchy`, `guaranteed clearance` with natural CSS section titles).
- [x] 3.2 Clean up comments in `public/assets/js/main.js` (replace dramatic visibility guarantees with standard developer intent notes).

## 4. Verification & Smoke Testing

- [x] 4.1 Run `php artisan view:cache` to ensure all Blade templates compile with zero syntax errors.
- [x] 4.2 Verify rendered HTML and styles in browser to confirm zero visual or functional regression.
- [x] 4.3 Clean up view cache with `php artisan view:clear`.
