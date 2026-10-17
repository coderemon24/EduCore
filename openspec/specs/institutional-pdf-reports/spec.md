# institutional-pdf-reports Specification

## Purpose
Establishes system-wide standards for institutionally generated PDF documents (Invoices, Receipts, Academic Registers, and Attendance Summaries), ensuring high information density, toner-safe contrast, and consistent school administrative branding.

## Requirements

### Requirement: Unified Institutional Layout and Typography Architecture
The system MUST provide a shared institutional PDF layout architecture (`pdf-base.blade.php`) defining consistent `@page` margins, print-safe neutral palettes (`#0f172a`, `#475569`, `#cbd5e1`, `#f8fafc`), disciplined font hierarchy (Helvetica/Arial sans-serif body, monospace tabular numerals), and zero ink-heavy solid fills.

#### Scenario: Rendering an administrative PDF document
- **WHEN** any administrative module compiles a PDF document via DomPDF
- **THEN** the output uses standardized 10-12mm page margins, fixed table layouts, crisp 0.75pt border dividers, and high-contrast typography readable on both screen and monochrome laser printers.

### Requirement: Institutional Header and Certification Sign-Off Standards
The system MUST incorporate standardized institutional branding (school name, EIIN, contact details, dynamic timestamp) in the document header and standardized multi-tier verification blocks (Prepared By, Verified By, Head of Institution / Principal) in the document footer.

#### Scenario: Verifying official sign-off on generated records
- **WHEN** an institutional financial or attendance report is printed
- **THEN** the bottom section renders designated signature lines for institutional accountability and audit compliance.

### Requirement: Toner-Safe Status Indication and Outline Badges
The system MUST replace saturated solid dark badge fills with clean outline-bordered labels and clear textual indicators to ensure legibility when printed on low-cost monochrome laser printers or duplicated via photocopiers.

#### Scenario: Printing transaction with status badge
- **WHEN** a document containing status tags (e.g., PAID, PARTIAL, UNPAID, PRESENT, ABSENT) is rendered in PDF
- **THEN** each status tag is rendered with a 1px solid border and distinct uppercase text, preventing black blotches or muddy gray fills during printing.
