# Spec Delta: admin-dashboard

## MODIFIED Requirements

### Requirement: Collapsible Sidebar Navigation with Hover Tooltips
The admin sidebar MUST remain persistently fixed on desktop viewports while the main dashboard area scrolls independently, MUST support internal vertical scrolling when menu items exceed viewport height, MUST display clean badge-free menu items with a font size of 14px (`text-sm`), MUST render a pointer cursor for all interactive elements, and MUST preserve collapsible toggling between expanded mode (260px / `w-64`) and compact icon mode (~72px / `w-20`) with hover tooltips.

#### Scenario: Expanded state displays full branding and labels
- **WHEN** the sidebar is in expanded state
- **THEN** it displays the school logo from `public/admin-assets/images/laravel-school-transparent.png`, navigation category titles, text labels, and section chevrons with a width of 260px (`w-64`).

#### Scenario: Collapsed state displays icons and hover tooltips
- **WHEN** the hamburger menu is clicked to collapse the sidebar
- **THEN** the sidebar width shrinks to compact icon-only mode (~72px / `w-20`)
- **AND** text labels and sub-chevrons are hidden
- **AND** hovering over any navigation item displays a floating tooltip with the menu title positioned to the right of the icon.

#### Scenario: Persistent fixed desktop sidebar and independent main container scroll
- **WHEN** an administrator views the administrative portal on desktop (`lg` screen)
- **THEN** the sidebar stays pinned in place
- **AND** scrolling vertically scrolls only the main dashboard content canvas without scrolling the sidebar out of view.

#### Scenario: Internal sidebar navigation scrolling
- **WHEN** the number of sidebar navigation items and categories exceeds the available viewport height
- **THEN** the sidebar's internal navigation container becomes scrollable (`overflow-y-auto`)
- **AND** the sidebar brand header and user profile quick card remain visible at top and bottom.

#### Scenario: Menu label optimization and badge elimination
- **WHEN** sidebar navigation items are rendered
- **THEN** menu items use concise, institutional naming ("Overview", "Students", "Teachers", "Parents", "Classes", "Attendance", "Examinations", "Fees & Billing", "Notice Board", "Settings")
- **AND** all numeric badges and status indicators (`.nav-badge`) are removed from navigation links.

#### Scenario: 14px typography and cursor pointer for interactive elements
- **WHEN** navigation links and interactive controls are displayed
- **THEN** the menu item labels have a font size of 14px (`text-sm`)
- **AND** hovering over any clickable button, navigation link, dropdown item, or toggle displays a pointer cursor (`cursor: pointer`).

---

### Requirement: Minimalist and Clean Navbar Architecture
The administrative navbar MUST provide contextual academic operations and authenticated user controls, including an Academic Calendar switcher, essential tools (hamburger toggle, search, quick actions, notifications), and a profile menu displaying the user's name, designation, and account dropdown.

#### Scenario: Minimal navbar layout rendering
- **WHEN** the administrative portal header renders
- **THEN** it displays essential controls: hamburger sidebar toggle, compact search input with keyboard shortcut hint (`⌘K`), academic calendar session switcher, quick-action trigger, clean notification bell with a static indicator dot, and the user profile trigger
- **AND** it omits redundant textual session badges and verbose page-specific action buttons.

#### Scenario: Static non-pulsing alert indicator
- **WHEN** there are unread administrative notifications
- **THEN** the notification bell displays a solid, static indicator badge
- **AND** no infinite CSS pulse or ping animations are rendered.

#### Scenario: Academic calendar session switcher in navbar
- **WHEN** the administrative navbar renders
- **THEN** an Academic Calendar widget displays the active academic year (e.g., "2025–2026 Academic Year • Term 1")
- **AND** clicking the widget reveals a switcher dropdown or modal allowing the user to select and switch the active academic session with instant feedback.

#### Scenario: User profile trigger displaying name and designation
- **WHEN** viewing the right section of the navbar
- **THEN** the profile trigger displays the user avatar along with the user's full name (e.g., "Arthur Vance") and designation (e.g., "Super Administrator" or "Principal") directly to the right of the avatar.

#### Scenario: Interactive user profile dropdown menu
- **WHEN** an administrator clicks the user profile trigger
- **THEN** an interactive dropdown menu opens
- **AND** the dropdown displays options for:
  - "Profile" (navigating to profile management)
  - "Change Password" (triggering password update modal or view)
  - "Logout" (triggering secure session termination with logout action)
- **AND** clicking outside or pressing Escape closes the dropdown.
