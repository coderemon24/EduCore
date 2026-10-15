# Design

## Context
See `proposal.md` for background and user requirements. Currently, the admin layout in `resources/views/admin.blade.php` uses a `flex min-h-screen` container where desktop scrolling cascades through the entire viewport window. As a result, the sidebar moves out of sight when users scroll through lengthy data tables, charts, and activity logs. Furthermore, menu items are set to `text-xs` (12px), adorned with non-essential numeric badges, the navbar has no academic calendar/session controls, and the user profile button is an unadorned circular avatar without user details or an action dropdown.

## Goals / Non-Goals

**Goals:**
- Provide a pinned, fixed desktop sidebar with an internal vertical scroll container (`overflow-y-auto`) for menu items when height is constrained.
- Maintain an independent vertical scroll container (`overflow-y-auto`) for the main content canvas (`#admin-main-content`).
- Upgrade sidebar menu item typography to a minimum of 16px (`text-base` / 1rem) with matching icon dimensions (`h-5 w-5`).
- Streamline sidebar menu item labels and strip all numeric/status badges (`.nav-badge`).
- Implement an Academic Calendar widget and switcher dropdown in the navbar.
- Enrich the navbar user profile section to show the user avatar, name, and designation side-by-side, toggling an interactive dropdown containing "Profile", "Change Password", and "Logout".

**Non-Goals:**
- Altering backend database schema for user roles or auth providers in this change (mock/session state and clean routes/actions suffice).
- Rewriting dashboard chart components or KPI calculations.
- Removing mobile drawer functionality (mobile responsiveness remains fully intact).

## Decisions

### 1. Viewport Flex Container for Fixed Sidebar & Independent Main Scroll
- **Decision**: Configure root container `#admin-layout-wrapper` as `h-screen overflow-hidden flex bg-slate-50`.
  - The sidebar `#admin-sidebar` occupies full screen height (`h-screen flex flex-col shrink-0`) with its internal navigation area set to `flex-1 overflow-y-auto`.
  - The main container `#admin-main-content` occupies `flex-1 h-screen overflow-y-auto flex flex-col min-w-0`.
- **Alternatives Considered**:
  - `position: fixed` with CSS `padding-left: 16rem` on the body: Viable, but standard flex `h-screen overflow-hidden` + independent scroll containers is cleaner in Tailwind CSS v4, prevents layout shifts when expanding/collapsing the sidebar, and natively ensures only the main canvas or menu scrollbars activate.

### 2. Sidebar Item Typography & Badge Purge
- **Decision**:
  - Replace `text-xs font-medium` with `text-base font-medium` (`font-size: 1rem; line-height: 1.5rem;` 16px minimum).
  - Update icon sizing from `h-4.5 w-4.5` to `h-5 w-5` (20px) to balance the 16px text.
  - Simplify menu labels to concise nouns: "Overview", "Students", "Teachers", "Parents", "Classes", "Attendance", "Examinations", "Fees & Billing", "Notice Board", "Settings".
  - Completely remove the `.nav-badge` spans from all sidebar items.
- **Alternatives Considered**:
  - Keeping badges optional or behind a toggle: Rejected because the user explicitly stated badges should not be present.

### 3. Navbar Academic Calendar Switcher Architecture
- **Decision**: Introduce a dedicated session dropdown widget in `resources/views/layouts/admin-partial/navbar.blade.php`:
  - Visual indicator with calendar icon, current session title (e.g., `2025-2026 Academic Year`), active term badge, and chevron.
  - Dropdown menu allowing administrators to select different academic years (e.g., "2025–2026 (Active)", "2024–2025 (Archived)", "2026–2027 (Upcoming)").
  - Client-side event in `admin-actions.js` to handle session selection with SweetAlert2 confirmation feedback and persistent localStorage/cookie state.
- **Alternatives Considered**:
  - Hardcoded static text: Discarded because switching academic calendar contexts is an explicit requirement.

### 4. Navbar Profile Trigger and Dropdown Architecture
- **Decision**:
  - Redesign the right-side profile trigger into an accessible button displaying:
    1. Circular avatar with initials `AV` or avatar image.
    2. Vertical text container displaying the user's Full Name (`Arthur Vance`, `text-sm font-semibold`) and Designation (`Super Administrator` or `Principal`, `text-xs text-slate-500`).
    3. Downward chevron icon that rotates upon dropdown activation.
  - Interactive dropdown menu containing:
    - User quick info card (Name, Email, Role badge).
    - `Profile` action (triggers profile modal or navigation).
    - `Change Password` action (triggers secure password update dialog).
    - Divider.
    - `Logout` button (POST request with CSRF token to logout route or SweetAlert confirmation).
  - Wired into `admin-actions.js` with click-outside listener and Escape key dismiss.

## Risks / Trade-offs

- **[Risk] Viewport height overflow in mobile browsers with dynamic URL bars**
  → **Mitigation**: Use `h-screen` paired with Tailwind CSS `h-[100dvh]` fallback where applicable, maintaining standard touch drawer behavior on `<lg` screens.
- **[Risk] Collapsed sidebar icon alignment with 16px font changes**
  → **Mitigation**: Ensure `w-20` collapsed state centers icons cleanly and hides label containers via `.sidebar-collapsed .nav-label { display: none !important; }`.
- **[Risk] Navbar profile text truncation on smaller desktop screens**
  → **Mitigation**: Use `hidden sm:flex` or `truncate max-w-[140px]` on designation text so it collapses gracefully on narrow desktop viewports.
