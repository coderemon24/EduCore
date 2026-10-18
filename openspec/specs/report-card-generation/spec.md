# Report Card Generation Specification

## Purpose
Generates official, print-ready student mark sheets and academic transcripts formatted to institutional standards, featuring dynamic subject component breakdowns, combined paper totals, highest class marks, attendance matrices, co-curricular evaluations, GPA legends, triple verification signature blocks, and custom background watermark integration from Marksheet Settings.

## Requirements

### Requirement: Standardized Institutional Mark Sheet Layout
The system MUST generate a high-density, printable student mark sheet matching the institutional structure (as exemplified by Joypurhat Government Girls' High School standards), clearly indicating the student's designated optional (4th) subject within the subject breakdown table.

#### Scenario: Rendering student demographic and academic meta header
- **WHEN** a report card is generated for an enrolled student
- **THEN** the layout renders a top branding header with school logo, school name, exam title, student passport photo, and a 3-column metadata panel displaying:
  - Column 1: Student Name, Father's Name, Mother's Name, Student ID (SID), Date of Birth (DOB)
  - Column 2: Shift, Board, Class, Group, Section
  - Column 3: Roll Number, Final GPA, and Final Letter Grade.

#### Scenario: Rendering subject-wise dynamic marks breakdown
- **WHEN** the subject table is rendered for a student's exam
- **THEN** the table displays columns for Subject Code, Subject Name, dynamic component marks (Written, MCQ, CA, Practical as configured for that subject), Subject Total Marks, Class Highest Mark for that subject, Grade Point (GP), and Letter Grade.

#### Scenario: Rendering optional subject label in subject table
- **WHEN** the student mark sheet subject-wise grade table renders a subject identified as the student's optional (4th) subject
- **THEN** the layout displays `(Optional)` directly next to or below the subject name in the SUBJECT column without introducing extra summary boxes.

#### Scenario: Rendering compulsory subjects in subject table
- **WHEN** compulsory subjects are rendered in the mark sheet subject table
- **THEN** they display their standard subject titles without the `(Optional)` tag.

#### Scenario: Rendering combined paper subject groups
- **WHEN** subjects are configured as multi-paper pairs (e.g. Bengali 1st & 2nd Paper, English 1st & 2nd Paper)
- **THEN** the mark sheet visually groups them with combined component totals, combined total marks, class highest marks, and unified GP and Grade.

#### Scenario: Rendering bottom analytical panels
- **WHEN** the bottom analytical section is rendered
- **THEN** the system displays:
  1. An Overall Tabulation summary box
  2. An Attendance Report panel showing month-by-month present vs. working days (Jan-Dec) alongside Extra Activities evaluation (Cultural, Scout/BNCC, Sports, Olympiad)
  3. A GPA Grading Scale legend showing mark ranges, grades, and grade points
  4. Triple verification signature blocks: Guardian, Class Teacher, and Head Master / Principal.

### Requirement: Mark Sheet Background Template Integration
The system MUST support configuring and uploading custom mark sheet background templates via Marksheet & ID Card Settings to allow printing on plain paper with pre-rendered graphics or on pre-printed institutional stationery.

#### Scenario: Rendering with uploaded background template
- **WHEN** a school has uploaded a custom mark sheet background template image in Marksheet Settings
- **THEN** the generated mark sheet applies the background image (`background-image: url(...)` or absolute full-page layer), positioning textual data, tables, and photos over the background with pixel-accurate alignment.

#### Scenario: Rendering without background template (clean border fallback)
- **WHEN** no background template is uploaded or the school chooses plain paper printing
- **THEN** the system renders high-density typographic borders, crisp hairlines, and institutional seal blocks directly via CSS.

### Requirement: PDF Export and Batch Generation
The system MUST support streaming, downloading, and printing single student mark sheets as well as bulk class-wide report cards using `Spatie\LaravelPdf`.

#### Scenario: Single student mark sheet PDF download
- **WHEN** an authorized user requests a student's mark sheet PDF
- **THEN** the system compiles the view via `Pdf::view(...)` with A4 dimensions and streams the named PDF file (e.g., `marksheet-SID2309009-half-yearly-2026.pdf`).

#### Scenario: Bulk section report card export
- **WHEN** an administrator requests report cards for an entire section
- **THEN** the system renders multi-page PDF documents with each student's mark sheet formatted cleanly on a separate page using CSS page-break rules (`page-break-after: always`).
