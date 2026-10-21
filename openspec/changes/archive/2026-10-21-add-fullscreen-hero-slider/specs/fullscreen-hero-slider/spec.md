# Spec Delta: Full-Screen Hero Slider Below Navbar

## Purpose
Establishes the design, Swiper.js carousel behavior, animated left-aligned captions, and non-destructive layout integration for an edge-to-edge full-width hero slider situated directly beneath the school portal's navigation header.

## ADDED Requirements

### Requirement: Full-Width Slider Placement Below Navbar
The homepage MUST render a full-width hero slider directly below the primary institutional navigation header (`layouts.partials.navbar`) and above the main container content, strictly preserving all existing widgets, news grids, notices, and footer sections without modification or layout shifts.

#### Scenario: Full-width desktop rendering
- **WHEN** a visitor loads the public homepage on a desktop browser (viewport width >= 1024px)
- **THEN** the slider spans the full width of the viewport immediately underneath the navigation bar
- **AND** the image displays in high fidelity with a 16:5 to 21:9 panoramic aspect ratio or dynamic full-width height (minimum 380px–480px on desktop) without introducing horizontal scrollbars.

#### Scenario: Non-interference with existing homepage layout
- **WHEN** the full-width slider is mounted
- **THEN** all subsequent elements in `resources/views/frontend/home/index.blade.php` (including the 8-col/4-col grid, headmaster message, notice ticker, and statistics counter) remain intact, functional, and correctly positioned below the slider.

---

### Requirement: Swiper.js Carousel Configuration and Controls
The slider MUST be managed via the project's bundled Swiper.js library (`public/assets/js/swiper-bundle.min.js`), supporting automated continuous cycling, touch swiping, accessible left/right navigation chevrons, and bottom-centered pagination indicators.

#### Scenario: Slider autoplay and loop behavior
- **WHEN** the homepage is loaded and left unattended
- **THEN** the Swiper carousel automatically advances through slides at a configurable interval (4.5 to 5.5 seconds) with continuous loop enabled
- **AND** temporarily pauses when a user hovers or touches the slider container.

#### Scenario: Navigation arrow controls
- **WHEN** a visitor clicks or taps the previous (`<`) or next (`>`) navigation button located on the left and right edges
- **THEN** the carousel transitions smoothly to the corresponding slide within 600ms–800ms with smooth easing.

#### Scenario: Pagination bullet indicators
- **WHEN** the slider initializes or advances
- **THEN** circular pagination bullets display at the bottom center of the slider
- **AND** the active slide bullet is highlighted in institutional amber/gold (`#F59E0B` / `#EAB308`), while inactive bullets display as semi-transparent white dots.

---

### Requirement: Left-Aligned Captions with Slide Change Animations
Each slide MUST feature left-aligned institutional typography (Bengali headline, sub-headline/tagline, and optional badge) positioned over a soft dark gradient backdrop, triggering entrance animations each time the slide becomes active.

#### Scenario: Active slide triggers entrance animation
- **WHEN** a slide becomes the active slide (`.swiper-slide-active`)
- **THEN** the title (e.g. "শিক্ষক ও কর্মচারী বৃন্দ") executes a smooth slide-in-from-left and fade-in animation
- **AND** the subtitle (e.g. "মাধ্যমিক শিক্ষন হলো কোনো জাতির মেরুদণ্ড গড়ার আসল ভিত্তিপ্রস্তর") executes a staggered entrance animation with a 100ms–200ms delay.

#### Scenario: Inactive slides reset typography state
- **WHEN** a slide transitions out of the active state
- **THEN** its text elements reset their animated transform/opacity states cleanly so they are primed for re-animation on the next loop cycle.

---

### Requirement: Responsive Mobile and Tablet Adaptations
The hero slider MUST scale responsively across all standard viewports without clipping critical textual information or breaking page proportions.

#### Scenario: Mobile viewport rendering (< 640px)
- **WHEN** the slider is viewed on a mobile device
- **THEN** the slider height adjusts dynamically (minimum 240px–300px), font sizes scale proportionally for readability, and navigation arrows remain clickable without obstructing slide captions.

#### Scenario: High-contrast text legibility
- **WHEN** any slide background image is displayed
- **THEN** a directional dark gradient (`from-black/85 via-black/40 to-transparent`) overlay ensures the white text maintains WCAG AA contrast ratio (> 4.5:1) against any photograph.
