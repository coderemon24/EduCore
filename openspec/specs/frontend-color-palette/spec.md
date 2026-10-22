# Frontend Color Palette Specification

## Purpose
Establishes semantic color discipline on the public frontend portal by reserving the bright accent exclusively for online admissions and headline highlights, dedicating a second distinct color exclusively for notices and circulars, maintaining calm neutrality across general surfaces, and strictly preserving admin isolation.

## Requirements

### Requirement: Bright Accent Reservation for Primary Conversion Action and Headlines
The public frontend views SHALL reserve the bright accent color (`amber-400` / `amber-500` / `#f59e0b`) exclusively for the primary institutional conversion action (Online Admission) and for exactly one highlighted phrase per headline.

#### Scenario: Visual presentation of primary admission action
- **WHEN** the primary call-to-action is rendered (such as the Admission CTA in the navbar, hero slider, or closing enrollment section)
- **THEN** it renders with the prominent bright accent styling (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`), while all secondary buttons and casual navigation links use calm neutral or dark institutional styling.

#### Scenario: Headline accent phrase highlight
- **WHEN** major section headlines are displayed on public pages
- **THEN** exactly one thematic phrase per headline utilizes the bright accent color, providing clear focal orientation without diffusing attention.

### Requirement: Reserved Second Color for Notices and Circulars
The public frontend SHALL dedicate a consistent second distinct color (`sky-600` / `blue-600` / `#2563eb`) exclusively for the Notices, Circulars, and Official Announcements category, ensuring this color consistently signals an official circular across all pages.

#### Scenario: Rendering notice badges and indicators
- **WHEN** notice board category badges, circular date squares, or bulletin alert pills are displayed
- **THEN** they utilize the reserved notice color scheme (`bg-sky-50 text-sky-800 border-sky-200` or `text-sky-600`), establishing an unmistakable semantic pattern where that color always signifies an official announcement.

### Requirement: Calm Institutional Neutral Palette for Auxiliary Elements
The public frontend SHALL render general cards, secondary action buttons, feature list icons, and layout containers in a calm, high-contrast neutral palette, eliminating arbitrary decorative multi-color treatments.

#### Scenario: Rendering feature cards and icons
- **WHEN** academic capabilities, value pillars, or campus facilities cards are displayed
- **THEN** card backgrounds remain neutral (`bg-white` or `bg-slate-50`), icon boxes utilize neutral slate or signature institutional green tints, and card borders adhere to subtle neutral dividers (`border-slate-200`).

### Requirement: Preservation of Functional Status and Data Colors
The public frontend color refactor SHALL NOT alter existing functional status badges, telemetry data visualizations, or form state indicators.

#### Scenario: Inspection of data metrics and status indicators
- **WHEN** telemetry statistics, board exam pass percentages, or attendance markers are displayed
- **THEN** functional status colors (such as success green or caution amber for operational states) remain intact and unmodified.

### Requirement: Strict Frontend Isolation and Admin Protection
The color hierarchy refactoring SHALL strictly modify public frontend views and assets only, leaving administrative portal views untouched.

#### Scenario: Verification of administrative dashboard boundaries
- **WHEN** the administrative dashboard or management interfaces are inspected
- **THEN** zero files in `resources/views/admin/*`, `resources/views/layouts/admin.blade.php`, or admin stylesheets are modified.
