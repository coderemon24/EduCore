# Spec Delta: admin-dashboard

## MODIFIED Requirements

### Requirement: High-Information-Density School KPI Dashboard UI
The dashboard landing page MUST display authentic academic indicators, comparative analytics, and student records following strict no-slop institutional design standards, completely avoiding pseudo-scientific jargon, arbitrary pastel color clashes, and fake inline scripts.

#### Scenario: Primary KPI metrics rendering
- **WHEN** the dashboard page loads
- **THEN** four primary metrics cards are displayed: Total Students, Teachers & Faculty, Registered Parents, and Fee Collections
- **AND** cards MUST utilize cohesive, solid 1px borders (`border-slate-200`) and subtle, semantic status badges rather than mismatched pastel border tints
- **AND** cards MUST avoid arbitrary all-caps sub-label typography with exaggerated letter tracking.

#### Scenario: Comparative exam results and distribution charts
- **WHEN** viewing the analytics grid
- **THEN** the performance overview displays academic indicators comparing student performance with clear institutional labels
- **AND** student distribution demographics are rendered with clean semantic progress elements and authentic typography instead of brittle, hardcoded SVG math.

#### Scenario: Star students data table rendering
- **WHEN** viewing the Star Students section
- **THEN** a clean data table renders top performers with student avatar/initials, name, grade, roll ID, marks, percentage badge, and academic year
- **AND** dummy non-functional checkboxes MUST be omitted
- **AND** action triggers MUST avoid inline `onclick="Swal.fire(...)"` handlers and instead link to legitimate profiles or use data attributes handled by modular JavaScript.

## ADDED Requirements

### Requirement: Prohibition of AI Slop Artifacts in Dashboard Blade Template
The dashboard Blade view MUST adhere strictly to the "No-Slop UI" design protocol, eliminating artificial buzzwords, mock inline JavaScript popups, and visual clutter.

#### Scenario: Authentic institutional terminology
- **WHEN** reading page titles, section headers, and breadcrumbs
- **THEN** text MUST use direct, human-crafted terms ("Dashboard Overview", "School Administration", "Academic Performance")
- **AND** MUST avoid hyperbolic AI buzzwords ("Academic Telemetry", "Financial Realization", "Curriculum Milestone Targets").

#### Scenario: Clean script delegation
- **WHEN** interacting with dashboard action buttons (e.g. Export, Admission, Student Profile)
- **THEN** interactions MUST be handled unobtrusively via external modular scripts (`admin-actions.js`) using semantic data attributes or direct URL navigation
- **AND** no inline `onclick="window.Swal && ..."` scripts MUST be present within `dashboard.blade.php`.
