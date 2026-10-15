# Frontend Editorial Design Specification

## Purpose
Establishes a human-crafted editorial design system, typography tokens, an interactive deliverables showcase, and zero-friction navigation across the KitoMark public frontend, eliminating generic AI visual clichés.

## Requirements

### Requirement: Editorial Design System and Typography Tokens
The frontend design system SHALL enforce high-contrast display typography, solid 1px hairline structural borders, disciplined border radii (8px–14px), and eliminate cliché gradient texts, ambient glow blobs, and full-screen math grid overlays.

#### Scenario: Visual presentation in dark and light modes
- **WHEN** a visitor views any public page in either dark mode (`html.dark`) or light mode (`html.light`)
- **THEN** headings render with solid, high-contrast typography without 3-stop linear gradient fills (`.gradient-text`), background surfaces have no `800px` blurred radial glow halos (`.ambient-glow`) or fixed `60px` grid patterns (`.bg-grid`), and cards use crisp 1px borders without glowing drop shadows or hover laser lines.

#### Scenario: Brand color discipline
- **WHEN** brand color styling is applied to interactive elements or highlights
- **THEN** the primary `#FF5A00` color is used with restraint on key buttons, active states, and focal metrics rather than as omnipresent decorative glowing washes.

---

### Requirement: Interactive Agency Deliverables Hero Showcase
The hero section SHALL replace the arbitrary Three.js particle cloud canvas with an interactive, responsive deliverables showcase demonstrating tangible digital marketing, bespoke web development, and mobile engineering artifacts.

#### Scenario: Prospective client interacts with showcase tabs
- **WHEN** a user clicks on one of the deliverable tabs (e.g., "Paid Marketing Funnel", "Web Performance Architecture", "Mobile App Topology")
- **THEN** the showcase panel updates instantly with high-fidelity, authentic preview cards and verified metric highlights corresponding to that discipline.

#### Scenario: Responsive mobile rendering of showcase
- **WHEN** the hero section is viewed on mobile screens under 768px
- **THEN** the showcase renders as a clean, scrollable or stacked presentation with zero horizontal overflow and zero layout shift.

---

### Requirement: Zero-Latency Instant Page Navigation
The public layout SHALL provide immediate, unhindered page navigation between routes without hijacking link clicks or introducing artificial delay timers.

#### Scenario: Internal link click execution
- **WHEN** a user clicks an internal navigation link (such as "About Us", "Services", or "Case Studies")
- **THEN** the browser executes standard instantaneous page navigation without `e.preventDefault()` interruption and without artificial 400ms–600ms `setTimeout` delays.

#### Scenario: Modal consultation trigger
- **WHEN** a user clicks an action to open the consultation modal (`$store.app.openConsultation`)
- **THEN** the modal opens immediately without any preloader-induced synthetic timeout delay.

---

### Requirement: Editorial Section Layouts and Restraint
Public sections (Services, Case Studies, ROI Calculator, About Us manifesto, and Team pods) SHALL present structured, high-density layouts free from arbitrary monospace eyebrows, blinking radar dots, and repetitive marquee clones.

#### Scenario: Inspection of section headings and badges
- **WHEN** viewing section introductions across the homepage, about page, and service details
- **THEN** arbitrary uppercase monospace prefixes (such as `01 //` or `FEATURE //`) and blinking radar dots (`.blink-dot`) are absent, presenting clean, contextual headings and status labels.

#### Scenario: Team card and case study presentation
- **WHEN** hovering over team cards or case study previews
- **THEN** cards respond with subtle, accessible border and surface elevation changes without glowing top laser line animations (`.team-laser-line`) or radial gradient hover washes.
