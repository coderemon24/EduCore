# Spec Delta: report-card-generation

## MODIFIED Requirements

### Requirement: Standardized Institutional Mark Sheet Layout
The system MUST generate a high-density, printable student mark sheet matching the institutional structure, clearly indicating the student's designated optional (4th) subject within the subject breakdown table.

#### Scenario: Rendering optional subject label in subject table
- **WHEN** the student mark sheet subject-wise grade table renders a subject identified as the student's optional (4th) subject
- **THEN** the layout displays `(Optional)` directly next to or below the subject name in the SUBJECT column without introducing extra summary boxes.

#### Scenario: Rendering compulsory subjects in subject table
- **WHEN** compulsory subjects are rendered in the mark sheet subject table
- **THEN** they display their standard subject titles without the `(Optional)` tag.
