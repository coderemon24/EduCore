# Proposal: Frontend Color Hierarchy & Semantic Discipline

## Why

On the public school frontend portal, vibrant colors are currently spread decoratively across generic cards, arbitrary icon boxes, and secondary buttons. When every element competes with accent colors, color ceases to carry communicative meaning and no longer directs visitor attention toward high-priority actions.

By reserving the bright accent color exclusively for the primary conversion action (Online Admission) and one focal phrase per headline, and dedicating a second distinct color exclusively for Official Notices & Circulars, the interface achieves clear semantic focus while keeping all other surfaces calm, dignified, and neutral.

## What Changes

1. **Bright Accent Reservation (`amber-400` / `amber-500`)**:
   - Reserved exclusively for the primary conversion action: **Online Admission / Apply Now ("অনলাইন ভর্তি আবেদন" / "ভর্তি আবেদন")**.
   - Reserved for exactly one highlighted phrase per headline.
   - Removed from secondary links, casual buttons, and arbitrary icon backgrounds.

2. **Reserved Second Color (`sky-600` / `blue-600`)**:
   - Reserved exclusively for **Notices, Announcements & Circulars ("নোটিশ ও সার্কুলার")**.
   - Consistently applied to notice category badges, circular date indicators, and bulletin alert tags across the site so this color always signifies an official institutional notice.

3. **Calm Institutional Neutrality**:
   - General interface elements, secondary action buttons, feature cards, and structural containers transition to a calm, high-contrast neutral palette (`slate-50`, `slate-100`, `slate-200`, `slate-700`, `slate-800`, `slate-900`) anchored by the school's signature dark forest green (`#005030`).
   - Arbitrary multi-color washes on icon boxes and borders are eliminated in favor of clean slate and subtle brand neutrals.

4. **Preservation of Status and Data Colors**:
   - Real-world telemetry statistics, form verification states, and academic status indicators (e.g. pinned badges, pass rates, attendance markers) remain functional and untouched.

5. **Strict Frontend Isolation**:
   - Changes are strictly bounded to the public frontend (`resources/views/frontend/*` and `resources/views/layouts/app.blade.php`).
   - Zero administrative Blade templates (`resources/views/admin/*`), admin layouts, or admin stylesheets will be altered.

## Capabilities

### New Capabilities
- `frontend-color-palette`: Establishes semantic color discipline across the public school frontend, reserving the bright accent for admissions and headline highlights, dedicating a second color for notices and circulars, and keeping all auxiliary elements calm and neutral.

### Modified Capabilities
<!-- None -->

## Impact
- **Views**: `resources/views/frontend/home/index.blade.php`, `resources/views/frontend/home/partials/fullscreen-slider.blade.php`, `resources/views/frontend/home/partials/notice-board.blade.php`, `resources/views/frontend/about/index.blade.php`, `resources/views/frontend/notices/index.blade.php`, `resources/views/frontend/notices/show.blade.php`, and `resources/views/layouts/partials/navbar.blade.php`.
- **Backend / Database**: Zero impact; purely visual styling and utility class refactor.
- **Admin Portal**: Strictly zero modifications.
