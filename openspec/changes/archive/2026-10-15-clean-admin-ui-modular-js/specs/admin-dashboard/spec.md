# Spec Delta: admin-dashboard

## ADDED Requirements

### Requirement: Minimalist and Clean Navbar Architecture
The administrative navbar must eliminate visual clutter ("hijibiji") and adopt a "minimum text, maximum understanding" layout.

#### Scenario: Minimal navbar layout rendering
- **WHEN** the administrative portal header renders
- **THEN** it displays only essential controls: hamburger sidebar toggle, compact search input with keyboard shortcut hint (`⌘K`), quick-action trigger, clean notification bell with a static indicator dot, and an avatar profile button
- **AND** it omits redundant textual session badges, verbose user role labels, and bulky page-specific action buttons.

#### Scenario: Static non-pulsing alert indicator
- **WHEN** there are unread administrative notifications
- **THEN** the notification bell displays a solid, static indicator badge
- **AND** no infinite CSS pulse or ping animations are rendered.

---

### Requirement: Feature-Wise Modular JavaScript Assets
Administrative layout and dashboard interactions must be structured into dedicated, modular JavaScript files stored in `public/admin-assets/js/` rather than hardcoded inline within Blade templates.

#### Scenario: Script modularization and separation of concerns
- **WHEN** the admin master layout `admin.blade.php` is rendered
- **THEN** it includes `admin-sidebar.js` for sidebar collapse and mobile drawer management
- **AND** it includes `admin-search.js` for global keyboard shortcut focus handling
- **AND** it includes `admin-actions.js` for SweetAlert2 modal dialogues and interactive prompts
- **AND** no inline JavaScript exceeding layout bootstrapping is embedded in `admin.blade.php`.

#### Scenario: Sidebar state persistence across page loads
- **WHEN** a user toggles the sidebar state in desktop mode
- **THEN** `admin-sidebar.js` saves the preference to `localStorage` under `educore_sidebar_collapsed`
- **AND** restores this state on subsequent navigation.
