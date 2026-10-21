# Tasks: Full-Screen Hero Slider Below Navbar

## 1. Asset & Image Preparation

- [x] 1.1 Configure panoramic slide imagery in `public/assets/images/` (including the primary teachers & staff banner matching the reference photo and secondary campus slides).
- [x] 1.2 Verify that Swiper CSS and JS bundles (`public/assets/css/swiper-bundle.min.css` and `public/assets/js/swiper-bundle.min.js`) are properly loaded in `resources/views/layouts/partials/head.blade.php` and `resources/views/layouts/app.blade.php`.

## 2. Layout & Blade Architecture

- [x] 2.1 Create the modular Blade component `resources/views/frontend/home/partials/fullscreen-slider.blade.php` with full-width container markup, Swiper wrapper, individual slide items, dark gradient overlays, left-aligned typography captions, circular navigation chevrons, and pagination container.
- [x] 2.2 Mount the `fullscreen-slider` partial in `resources/views/frontend/home/index.blade.php` directly above the constrained `max-w-7xl` container, ensuring zero modifications or regressions to existing homepage sections below.

## 3. Styling & Keyframe Text Entrance Animations

- [x] 3.1 Add scoped styles in `public/assets/css/style.css` for `.fullscreen-hero-swiper`, including responsive viewport heights, dark multi-stop linear gradient backdrop (`from-black/85 via-black/50 to-transparent`), circular translucent navigation controls, and custom amber/gold pagination indicators.
- [x] 3.2 Implement CSS keyframe animations (`slideInLeft`, `fadeInUp`) triggered on `.fullscreen-hero-swiper .swiper-slide-active` so headlines and subheadings animate into view with staggered timing and reset seamlessly when transitioning between slides.

## 4. Swiper.js Initialization & Interactions

- [x] 4.1 In `public/assets/js/main.js`, configure and instantiate `new Swiper('.fullscreen-hero-swiper', ...)` with continuous loop, 5000ms autoplay delay, pause-on-hover, smooth 800ms slide transition, responsive touch swiping, and explicit navigation/pagination element bindings.
- [x] 4.2 Verify slide change events and DOM class lifecycle to ensure smooth animation triggers on every slide rotation.

## 5. Verification & Testing

- [x] 5.1 Test responsive viewport rendering across mobile (< 640px), tablet (640px–1024px), and desktop (>= 1024px) screens to ensure zero horizontal scrollbars and pristine text legibility.
- [x] 5.2 Validate that the existing 8-col slider, headmaster desk widget, notice ticker, statistics counter, and footer remain completely intact, functional, and unaffected.
- [x] 5.3 Run code formatting checks and inspect the browser console for clean script execution without warnings or errors.
