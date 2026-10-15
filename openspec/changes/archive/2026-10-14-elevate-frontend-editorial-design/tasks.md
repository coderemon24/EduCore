# Tasks

## 1. Design System Tokens & CSS Overhaul

- [x] 1.1 Purge AI slop utilities from `kito-app/public/assets/css/input.css` (remove `.ambient-glow`, `.bg-grid`, `.team-laser-line`, `.team-glow-bg`, `shadow-brand-glow`, and multi-stop `.gradient-text`).
- [x] 1.2 Define clean architectural surface tokens (`--bg-deep`, `--bg-surface`, `--bg-card`, `--border-subtle`) with 1px hairline rules for both dark and light themes.
- [x] 1.3 Refine `--brand-orange` accent rules to ensure disciplined application to primary interactive controls, focal metrics, and active states.
- [x] 1.4 Normalize card border radii to consistent `rounded-xl` (12px) and `rounded-lg` (8px), replacing nested 32px pill cards.

## 2. Layout Shell & Navigation Optimization

- [x] 2.1 Remove link click interception (`e.preventDefault()`), artificial 400ms setTimeout delays, and modal synthetic delays from `kito-app/resources/views/layouts/partial/preloader.blade.php`.
- [x] 2.2 Remove ambient glow containers and background grid overlays from `kito-app/resources/views/layouts/partial/grid.blade.php`.
- [x] 2.3 Refine `kito-app/resources/views/layouts/partial/navbar.blade.php`: remove artificial blinking radar dots from availability badges while retaining theme toggling and modal triggers.
- [x] 2.4 Update `kito-app/resources/views/layouts/partial/head.blade.php` and `scripts.blade.php` to retire the background Three.js hero particle script import.

## 3. Interactive Deliverables Hero Showcase

- [x] 3.1 Overhaul the hero section in `kito-app/resources/views/frontend/index.blade.php`: remove `#webgl-canvas-container` and replace multi-stop gradient headings with solid, commanding display typography.
- [x] 3.2 Build the interactive **Agency Deliverables Showcase** component using Alpine.js (`x-data="{ activeDeliverable: 'marketing' }"`) featuring real work preview cards for:
  - Paid Growth & Attribution Funnel (live ROAS, conversion metrics).
  - Bespoke Web Architecture (Core Web Vitals 100/100 score, responsive topology).
  - Enterprise App Engineering (cross-platform architecture, cloud synchronization).
- [x] 3.3 Style the hero proof metrics bar with clean tabular hairline dividers and high information density.

## 4. Homepage Section Editorial Refinement

- [x] 4.1 Refine the Client Ecosystem carousel in `index.blade.php`: simplify to a clean, single-track infinite scroller, removing redundant markup clone sets.
- [x] 4.2 Clean up the Core Services tabbed section: remove arbitrary uppercase monospace eyebrows and glowing card borders.
- [x] 4.3 Refine Case Studies cards and the Conversion Lift ROI Calculator into an executive financial valuation layout.
- [x] 4.4 Format Client Testimonials with authentic editorial quotes and clean attribution avatars.

## 5. Inner Pages & Leadership Pods Refinement

- [x] 5.1 Refine `kito-app/resources/views/frontend/about.blade.php`: replace `01 //` monospace badges with clean editorial numbered essays, and remove laser hover lines from team cards.
- [x] 5.2 Refine telemetry panels in `kito-app/resources/views/frontend/services/details.blade.php`: remove fake blinking radar indicators and organize technical deliverables cleanly.
- [x] 5.3 Refine metric cards in `kito-app/resources/views/frontend/case-studies/details.blade.php` to emphasize verified client business transformation outcomes.

## 6. Build Compilation & Verification

- [x] 6.1 Compile Tailwind CSS assets to update `kito-app/public/assets/css/output.css`.
- [x] 6.2 Verify instant navigation between routes in the browser with zero artificial delays.
- [x] 6.3 Test dark mode and light mode contrast across all updated pages to confirm zero visual regression.
- [x] 6.4 Validate responsive layouts on mobile viewports (< 768px) and desktop viewports.
