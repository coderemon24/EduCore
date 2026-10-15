# Proposal

## Why

The current KitoMark public frontend exhibits generic AI aesthetic clichés ("AI slop") that detract from the agency's credibility as a high-end digital marketing and web/mobile engineering partner:
1. **Unmotivated Visual Clutter**: Floating `800px` blurred ambient glow blobs (`.ambient-glow`), full-screen fixed math grids (`.bg-grid`), and glowing 2px top-border "laser lines" on card hovers.
2. **Artificial CPU/GPU Load**: A Three.js WebGL canvas running 80 bouncing particles randomly behind hero copy without conveying any real product value.
3. **Artificial Navigation Latency**: An intrusive preloader script that hijacks every internal link click (`e.preventDefault()`) to enforce an artificial 400ms–600ms loading delay.
4. **AI-Template Chrome & Typography**: Monospace numbers slapped onto marketing labels (`01 // ZERO SLOP PRINCIPLE`), excessive all-caps eyebrow tags, blinking radar dots (`.blink-dot`) on every badge, and cliché multi-stop gradient headings (`.gradient-text`).

By elevating the frontend into an authentic, editorial, human-crafted design system, KitoMark will present a distinctive visual identity: high contrast, disciplined typography, solid architectural grid lines, an interactive deliverables showcase in place of particle noise, and instant zero-friction navigation.

## What Changes

- **Design System & Tokens**:
  - Refine color tokens in `input.css` to retire glowing neon gradients and radial blur blobs.
  - Maintain the signature `#FF5A00` brand orange as an intentional, disciplined accent on a high-contrast dark/light architectural canvas.
  - Establish a clean typographic scale with tight display tracking (`-0.03em`), clear line-height ratios, and crisp solid headings.
  - Rationalize border radii (8px–14px) and replace nebulous shadow drops with 1px solid hairline borders.

- **Hero Experience Overhaul**:
  - Remove the Three.js bouncing particle cloud (`three-hero.js`) and background WebGL canvas.
  - Build an interactive **Agency Deliverables Showcase** allowing prospective clients to preview real agency work artifacts (Digital Marketing conversion funnel metrics, Bespoke Web Architecture performance audit, and Mobile Solutions interface schemas).
  - Clean up headline typography: eliminate multi-stop gradient text hacks in favor of authoritative, solid display type with intentional brand accents.

- **Navigation & Shell Polish**:
  - Remove link-hijacking click delays and modal artificial delays from `preloader.blade.php`, ensuring instant page navigation.
  - Clean up `navbar.blade.php` and `footer.blade.php`, removing artificial blinking radar dots while preserving dark/light mode toggling and Alpine consultation modals.
  - Remove background ambient glow elements from `grid.blade.php`.

- **Component & Section Refinement**:
  - Homepage (`index.blade.php`): Clean up Core Services tabs, Case Study cards, ROI Calculator, Client Logos scroller, and Testimonials into high-density editorial presentations.
  - About Us (`about.blade.php`): Strip `01 //` monospace eyebrows and card laser lines; present leadership pods with authentic editorial styling.
  - Service Details & Case Study Details: Streamline telemetry panels, eliminate fake radar tags, and emphasize real metrics and deliverables.

## Capabilities

### New Capabilities
- `frontend-editorial-design`: Covers the human-crafted, high-contrast editorial design system, typography tokens, interactive deliverable showcase component, instant navigation without blocking delays, and streamlined responsive layouts across the public agency website.

### Modified Capabilities
<!-- None: client-management and client-testimonials backend data schemas remain intact. -->

## Impact

- **Affected Files**:
  - `kito-app/public/assets/css/input.css` (tokens, removal of glow/laser styles, clean card architecture)
  - `kito-app/resources/views/layouts/partial/head.blade.php` (clean script and stylesheet imports)
  - `kito-app/resources/views/layouts/partial/grid.blade.php` (removal of ambient glow & background grid divs)
  - `kito-app/resources/views/layouts/partial/preloader.blade.php` (removal of click-hijacking latency)
  - `kito-app/resources/views/layouts/partial/navbar.blade.php` (header refinement)
  - `kito-app/resources/views/layouts/partial/scripts.blade.php` (retire three-hero.js load)
  - `kito-app/resources/views/frontend/index.blade.php` (hero overhaul, interactive showcase, clean sections)
  - `kito-app/resources/views/frontend/about.blade.php` (clean editorial team and manifesto sections)
  - `kito-app/resources/views/frontend/services/details.blade.php` (clean telemetry and layout)
  - `kito-app/resources/views/frontend/case-studies/details.blade.php` (clean metrics showcase)
- **Database / Backend**: Zero schema changes or migrations required; fully compatible with existing Eloquent models and CMS records.
- **Dependencies**: No new npm or Composer packages needed; uses existing Tailwind CSS v4, Alpine.js, and GSAP.
