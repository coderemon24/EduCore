# Proposal

## Why
The administrative dashboard layout currently lacks independent scrolling separation: when desktop users scroll through voluminous dashboard telemetry and reports, the sidebar does not stay pinned in place, making navigation inaccessible without scrolling all the way back up. Furthermore:
1. The sidebar menu items use small 12px (`text-xs`) typography with non-essential numeric/status badges that introduce visual clutter.
2. The navbar lacks an institutional Academic Calendar switcher, which is vital for school administrators who routinely manage and switch between active academic years, terms, and sessions.
3. The user avatar in the top navigation is currently an isolated button without adjacent user name/designation indicators or a functional dropdown menu for managing user profiles, updating passwords, and securely logging out.

Revamping the sidebar to be persistently fixed with an independent scroll container, optimizing menu naming, elevating typography to a minimum of 16px (`text-base`), adding an Academic Calendar switcher, and introducing a full user profile dropdown directly addresses these ergonomics and usability issues.

## What Changes
- **Fixed Sidebar & Independent Main Scroll**:
  - Restructure the desktop layout (`resources/views/admin.blade.php` and `resources/views/layouts/admin-partial/sidebar.blade.php`) so the sidebar remains fixed in viewport on desktop (`lg:fixed` or flex viewport container).
  - The main dashboard body (`#admin-main-content`) scrolls independently with its own scroll container, preventing viewport jump.
  - The sidebar navigation item list (`overflow-y-auto`) scrolls independently when the list of menu items exceeds screen height.
- **Sidebar Menu Item Optimization & Badge Removal**:
  - Clean up menu labels to be concise, professional, and institutional (e.g., "Overview", "Students", "Teachers", "Parents", "Classes & Sections", "Attendance", "Examinations", "Fees & Billing", "Notice Board", "Settings").
  - Completely eliminate sidebar numeric and status badges (`.nav-badge`) next to menu items.
  - Increase menu item text font size to at least 16px (`text-base` / 1rem) with matching icon dimensions (`h-5 w-5` / 20px) and spacing.
- **Navbar Academic Calendar Switcher**:
  - Add an Academic Calendar selector widget in `resources/views/layouts/admin-partial/navbar.blade.php` displaying the current active academic session (e.g., "2025–2026 Academic Year • Term 1").
  - Clicking this widget opens a dropdown or modal switcher allowing the administrator to switch active academic years/terms with instant visual feedback (and SweetAlert2/session handling).
- **Navbar Profile Dropdown with Name & Designation**:
  - In `resources/views/layouts/admin-partial/navbar.blade.php`, expand the user profile section to render the user avatar alongside the user's Full Name (e.g., "Arthur Vance") and Designation / Role (e.g., "Super Administrator" or "Principal") on the right.
  - Clicking anywhere on the profile trigger toggles a dropdown menu containing:
    - **Profile** link (pointing to user profile view or modal)
    - **Change Password** option
    - **Logout** button (with secure POST form / confirmation)

## Capabilities

### New Capabilities
None.

### Modified Capabilities
- `admin-dashboard`: Update requirements to enforce a fixed sidebar layout with independent main body and sidebar scrolling, minimum 16px menu typography, badge removal, navbar academic calendar switcher, and profile dropdown with user designation and account management actions.

## Impact
- **Blade Layouts**: `resources/views/admin.blade.php`, `resources/views/layouts/admin-partial/sidebar.blade.php`, `resources/views/layouts/admin-partial/navbar.blade.php`, and `resources/views/layouts/admin-partial/head.blade.php`.
- **Client JavaScript**: `public/admin-assets/js/admin-sidebar.js` and `public/admin-assets/js/admin-actions.js` to manage dropdown state toggles (Academic Calendar and Profile Menu) and collapsed/expanded sidebar layout recalculation.
- **Styles**: Tailwind CSS utility classes adjusted for 16px menu items, independent viewport overflow containers, and dropdown positioning.
