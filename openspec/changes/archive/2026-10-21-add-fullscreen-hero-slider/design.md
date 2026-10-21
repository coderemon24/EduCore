# Design: Full-Screen Hero Slider Below Navbar

## Context
The Laravel School application renders its public portal using the master layout `resources/views/layouts/app.blade.php`, with the top navigation header defined in `resources/views/layouts/partials/navbar.blade.php`. 

Currently, `resources/views/frontend/home/index.blade.php` renders all content within a constrained `<div class="max-w-7xl mx-auto px-4 ...">` container, including an 8-column slider and a 4-column headmaster desk message widget.

The user requirement mandates adding an edge-to-edge, full-width hero slider directly below the navigation bar—styled after the provided panoramic photograph featuring the institution's teachers and staff with left-aligned animated captions—while strictly preserving all existing layouts, components, and widgets without modification.

See `proposal.md` for motivation and background context.

## Goals / Non-Goals

**Goals:**
- Implement a dedicated, full-width hero slider component placed directly beneath the navigation header on the public homepage.
- Power the slider using Swiper.js, featuring continuous looping, autoplay with pause-on-hover, touch navigation, custom prev/next chevrons, and bottom-centered pagination indicators.
- Display left-aligned Bengali/English typography (headline and sub-headline/tagline) over a protective dark gradient overlay for optimal legibility.
- Trigger smooth entrance animations (staggered slide-in and fade-in) on the text elements whenever a slide becomes active.
- Match the visual reference provided by the user: teachers & staff banner, circular navigation buttons on edges, bottom-centered pagination dots with amber/gold active highlight.
- Strictly isolate this feature as an additive module without modifying or breaking any existing homepage sections.

**Non-Goals:**
- Creating database migrations or admin CRUD interfaces for slides at this stage (slide items will be maintained via structured Blade data array, easily pluggable into future database models).
- Removing or altering the existing 8-column slider or 4-column headmaster message inside the main container.
- Modifying site-wide navigation headers, menus, or footer partials.

## Decisions

### 1. Template Architecture: Dedicated Blade Partial Component
- **Choice**: Create `resources/views/frontend/home/partials/fullscreen-slider.blade.php` and include it at the very top of `resources/views/frontend/home/index.blade.php`, positioned before the existing `<div class="max-w-7xl ...">` container.
- **Rationale**: Placing the slider outside the `max-w-7xl` container allows it to stretch edge-to-edge (100% viewport width) without tricky CSS break-outs. Keeping it within `home/index.blade.php` ensures that secondary pages (e.g., `/about`, `/teachers`, `/contact`) remain unaffected.
- **Alternatives Considered**: 
  - *Adding directly into `layouts/app.blade.php`*: Would require route-conditional checks or yield stacks in the global master layout, cluttering the base layout.
  - *CSS break-out inside `max-w-7xl` (`w-screen -ml-[calc(50vw-50%)]`)*: Fragile on mobile screens and often induces horizontal scrollbar bugs.

### 2. Carousel Management: Independent Swiper Instance
- **Choice**: Initialize a new, distinct Swiper instance with class `.fullscreen-hero-swiper` and scoped control classes (`.fullscreen-swiper-button-prev`, `.fullscreen-swiper-button-next`, `.fullscreen-swiper-pagination`) in `public/assets/js/main.js`.
- **Rationale**: The existing homepage has an 8-col slider bound to `.hero-swiper`. A separate class name prevents event listener collisions, pagination overlap, and configuration interference.
- **Alternatives Considered**: Re-using `.hero-swiper`. Discarded due to DOM selector conflicts between two carousels on the same page.

### 3. Text Entrance Animation Strategy: Scoped CSS Transitions
- **Choice**: Utilize CSS keyframes triggered via `.swiper-slide-active`:
  ```css
  /* Inactive state */
  .fullscreen-hero-swiper .swiper-slide .slide-caption-title,
  .fullscreen-hero-swiper .swiper-slide .slide-caption-desc {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.4s ease, transform 0.4s ease;
  }
  /* Active slide animation */
  .fullscreen-hero-swiper .swiper-slide.swiper-slide-active .slide-caption-title {
    animation: slideInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .fullscreen-hero-swiper .swiper-slide.swiper-slide-active .slide-caption-desc {
    animation: slideInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.18s forwards;
  }
  ```
- **Rationale**: Swiper dynamically toggles `.swiper-slide-active` on the current slide. CSS animations provide 60fps performance and zero dependency overhead.
- **Alternatives Considered**: Using an external animation library (e.g. GSAP or Animate.css). Discarded because modern CSS keyframes deliver lightweight, high-performance animations natively.

### 4. Visual Styling & Reference Alignment
- **Dimensions**: Full width (`w-full`), responsive height: `h-[340px] sm:h-[420px] md:h-[480px] lg:h-[540px]`.
- **Navigation Buttons**: Circular semi-transparent buttons (`w-10 h-10 sm:w-12 sm:h-12 bg-white/25 hover:bg-white/45 text-white backdrop-blur-sm rounded-full flex items-center justify-center transition-all`) positioned at `left-4` and `right-4`.
- **Pagination Bullets**: Centered at `bottom-4` or `bottom-5`, with the active bullet styled in amber `#F59E0B` and slightly elongated, matching the reference image.
- **Contrast & Legibility**: Directional multi-stop linear gradient: `background: linear-gradient(to right, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.5) 45%, rgba(0,0,0,0) 80%)`.

## Risks / Trade-offs

- **[Risk] Extreme panoramic images may crop faces on vertical mobile viewports.**
  → *Mitigation*: Set `object-cover object-[center_35%]` on the `<img>` tags and utilize responsive typography sizing (`text-xl sm:text-3xl lg:text-4xl`) with `max-w-xl` on captions.
- **[Risk] Autoplay interfering with user reading.**
  → *Mitigation*: Enable `pauseOnMouseEnter: true` in Swiper autoplay options, and set autoplay delay to a comfortable 5000ms.
