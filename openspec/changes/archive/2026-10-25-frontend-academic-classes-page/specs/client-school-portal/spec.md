# Spec Delta: Client School Portal

## ADDED Requirements

### Requirement: Public Academic Classes Directory and Curriculum Visualizer
The system MUST provide a public-facing academic classes directory at `/academic/classes` (route name `frontend.academic.classes`) within the client school portal, displaying all active classes, their assigned sections, room numbers, seat capacities, appointed class teachers, and curriculum subjects with credit hours and optional/compulsory markings.

#### Scenario: Visitor navigates to the academic classes page
- **WHEN** a visitor navigates to `/academic/classes`
- **THEN** the request is processed by `AcademicController@classes` under `frontend.school` middleware
- **AND** the page displays an institutional header banner with breadcrumbs (`প্রচ্ছদ / একাডেমিক / শ্রেণিসমূহ ও পাঠ্যসূচি`), academic session indicator, and total telemetry chips (active classes, sections, curriculum subjects)
- **AND** the first active class in ordering hierarchy is selected by default unless a valid `class_id` query parameter is provided.

#### Scenario: Interactive class selection via tactile pill triggers
- **WHEN** a visitor clicks on any class pill in the class selector grid
- **THEN** the selected class becomes actively highlighted (`bg-[#005030] text-white`)
- **AND** the class overview summary, section breakdown, and assigned subject lists dynamically update without broken layout reflow
- **AND** the browser URL updates smoothly via hash or query state to allow bookmarking and direct linking.

#### Scenario: Comprehensive section and subject breakdown with class teacher context
- **WHEN** a class with sections and subjects is displayed
- **THEN** the left column renders section cards showing section name (e.g. `শাখা - ক`), appointed class teacher name and designation (`fa-chalkboard-user`), room number (`fa-door-open`), and seat capacity badge (`fa-user-group`)
- **AND** the right column renders the curriculum subjects list showing subject name (`fa-book-open`), subject code (`fa-barcode`), full marks and pass marks (`fa-award`), and distinct badge indicators for compulsory vs. optional/elective subjects
- **AND** secondary classes (Classes 9–10) display group/stream badges (Science, Humanities, Business Studies).

#### Scenario: Navigation dropdown integration across desktop and mobile
- **WHEN** a visitor inspects the `একাডেমিক` (Academic) dropdown in the main navigation
- **THEN** `শ্রেণিসমূহ` appears as the premier navigation item linking to `route('frontend.academic.classes')` on desktop (`data-dropdown="academic"`)
- **AND** `শ্রেণিসমূহ` appears inside the mobile navigation accordion under `একাডেমিক` (`data-mobile-sub="academic"`)
- **AND** the link highlights with active state styling (`.is-active`) when the current route matches `frontend.academic.classes*`.

#### Scenario: Resilient fallback when no classes are configured
- **WHEN** the school context has no active classes seeded or configured
- **THEN** the page renders a clean, friendly educational empty-state banner with an institutional inquiry action button without throwing PHP or Blade exceptions.
