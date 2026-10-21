# Proposal: Full-Screen Hero Slider Below Navbar

## Why
The school frontend portal currently lacks a prominent, edge-to-edge visual showcase immediately beneath the institutional navigation header. Adding a modern, full-width hero slider powered by Swiper.js will present an authentic visual identity of the institution—showcasing teachers and staff, academic activities, and campus life—matching the provided design reference. 

Crucially, this is an entirely additive component that elevates the aesthetic appeal while strictly preserving all existing homepage layouts, widgets, grids, and administrative functionality.

## What Changes
- **Full-Width Hero Slider Component**: Introduce a dedicated hero slider placed immediately below the primary navigation header (`resources/views/layouts/partials/navbar.blade.php`) and above the main container on the public homepage.
- **Swiper.js Carousel Architecture**: Utilize the existing Swiper.js library (`swiper-bundle.min.js` and `swiper-bundle.min.css`) to power smooth transitions, infinite looping, responsive touch dragging, autoplay, left/right navigation controls (`<` and `>`), and bottom-centered pagination bullets with an active amber/gold accent indicator.
- **Animated Left-Aligned Captions**: Position high-contrast typography on the left side of each slide with a semi-transparent dark gradient backdrop for optimal readability. When each slide transitions into the active state, the title, badge, and descriptive caption animate into view using staggered CSS keyframe animations (e.g., slide-in from left and fade-in).
- **Responsive & Safe Integration**: Ensure the slider scales smoothly across desktop, tablet, and mobile devices without horizontal overflow, while leaving existing homepage sections (such as notice tickers, 3-column content grid, and headmaster message) 100% intact.

## Capabilities

### New Capabilities
- `fullscreen-hero-slider`: Edge-to-edge Swiper.js hero slider positioned directly beneath the navigation bar with animated left-aligned typography captions, accessible navigation controls, custom pagination indicators, and responsive mobile adaptations.

### Modified Capabilities
*(None. All existing capabilities and layouts remain unchanged).*

## Impact
- **Blade Views & Partials**: 
  - New partial: `resources/views/frontend/home/partials/fullscreen-slider.blade.php`.
  - Updated template: `resources/views/frontend/home/index.blade.php` (rendering the slider right below the navbar before the main container).
- **Styles & Scripts**:
  - `public/assets/css/style.css` (or dedicated CSS rules for slider typography, gradient overlay, arrow controls, and slide text entrance animations).
  - `public/assets/js/main.js` (initialize `.fullscreen-hero-swiper` with active slide event handlers to trigger text animations).
- **Assets**:
  - Teacher/staff banner image and campus slide imagery located in `public/assets/images/`.
- **Database & Backend**:
  - No database schema migrations required. Slide data is structured cleanly via Blade configuration with ready support for future dynamic Eloquent model binding.
