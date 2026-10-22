# Spec Delta: Client School Portal

## MODIFIED Requirements

### Requirement: Faculty Directory and Institutional Contact
The system MUST provide a high-contrast, editorial-grade public directory view for teaching and administrative faculty (`/teachers`) adhering strictly to the 7 UI Master Recipes, featuring an asymmetric leadership spotlight, qualifying faculty telemetry, modern minimalistic cards with tactile touchpoints, interactive department filtering, search controls, and guardian consultation appointments.

#### Scenario: Viewing faculty directory page layout and narrative rhythm
- **WHEN** a visitor navigates to `/teachers`
- **THEN** the page renders an institutional header banner with breadcrumbs and establishment context
- **AND** a qualifying metrics strip displays verified faculty telemetry (Total Faculty, Higher Degree Holders, Student-Teacher Ratio, and Certification status)
- **AND** all core sections feature standardized `.section-header-block` headers with pulsing `.section-kicker` badges and `.home-section-divider` hairline dividers
- **AND** the page concludes with a guardian consultation call-to-action block featuring an amber conversion button.

#### Scenario: Asymmetric leadership spotlight for Headmaster and Assistant Headmaster
- **WHEN** a visitor views the leadership section on desktop viewports (>= 1024px)
- **THEN** the Headmaster (প্রধান শিক্ষক) flagship profile card commands dominant width (60%–65%) featuring portrait photo, leadership manifesto quote, educational qualifications, experience, and direct contact buttons
- **AND** the Assistant Headmaster (সহকারী প্রধান শিক্ষক) card occupies the secondary column (35%–40%) highlighting curriculum oversight, academic supervision, and office contact.

#### Scenario: Modern minimalistic teacher card presentation and interactive touchpoints
- **WHEN** faculty members are rendered in the directory grid
- **THEN** each card displays an architectural 4:5 aspect ratio portrait with a pinned department tag
- **AND** typography adheres to the 4-tier scale with high optical contrast
- **AND** each card provides direct contact actions including a clickable telephone link (`tel:`) and email link
- **AND** cards provide subtle hover elevation (`hover:-translate-y-1 hover:shadow-md`) with no generic ambient halos or text gradients.

#### Scenario: Interactive department filtering and search
- **WHEN** a visitor selects a department filter tab or submits the search form
- **THEN** the page displays faculty matching the selected department or query parameter
- **AND** active filter pills render with institutional green styling (`bg-[#005030] text-white`)
- **AND** if no records match or the database is unseeded, authentic verified fallback faculty profiles are displayed to maintain zero broken UI states.
