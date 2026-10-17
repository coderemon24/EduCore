# Proposal: Standardize Institutional PDF Design System

## Why

Currently, PDF generation across school administrative modules—such as Fee Invoices, Fee Receipts, Collection Registers, Dues Analysis, and Attendance Reports—exhibits visual inconsistencies and printer-unfriendly patterns. 

When developers or automated workflows attempt to "clean up AI slop" (overused gradients, glowing drop shadows, heavy solid dark badges), the typical overcorrection strips away all visual rhythm and hierarchy, reducing output to raw, stark black-and-white tables that look unpolished and unfinished. Conversely, unmanaged default templates rely on heavy solid colored backgrounds (`#4f46e5`, `#0f172a`), which waste excessive printer toner on school laser printers, smudge easily when printed on standard paper, and become unreadable gray blotches on monochrome photocopiers.

Institutions require a **Level 3 Executive Print Standard**:
1. **Typography & Hierarchy Driven**: High visual contrast achieved through structured font sizes, explicit font weights, tabular monospace numbers, and disciplined line heights rather than heavy background fills.
2. **Toner-Friendly & Photocopier-Safe**: Clean white canvas, crisp 0.75pt hairline dividers (`#cbd5e1`), subtle outline badges instead of solid fills, and high-contrast dark text (`#0f172a` Slate-900).
3. **Institutional Certification Architecture**: Reusable official institutional header (school details, EIIN/affiliation, contact), scope/parameter box, right-aligned monetary values, and official sign-off verification blocks (Prepared by, Accountant/Cashier, Principal).
4. **Unified Reusable Base Layout**: A shared Blade PDF layout/partial architecture preventing duplicate CSS declarations across different reports.

## What Changes

- **Create Unified Base PDF Template Architecture**:
  - Introduce `resources/views/layouts/pdf-base.blade.php` and shared partials for institutional headers, document meta badges, and official signature verification blocks.
  - Standardize `@page` margins, paper sizing (A4 portrait vs. landscape), print-optimized reset rules, and clean page-break behaviors.
- **Refactor Fee Management PDF Templates**:
  - `resources/views/admin/fees/invoices/pdf.blade.php`: Convert invoice badges and status pills to toner-safe high-contrast outline borders; add official institutional sign-off block.
  - `resources/views/admin/fees/receipts/pdf.blade.php`: Streamline fee payment voucher with dual verification lines and crisp monospace financial ledgers.
  - `resources/views/admin/fees/reports/pdf-collections.blade.php`: Refactor collections register into executive layout with parameter block and signature lines.
  - `resources/views/admin/fees/reports/pdf-dues.blade.php`: Standardize class-wise dues analysis report.
- **Standardize Attendance Report PDF**:
  - `resources/views/admin/attendance/reports/pdf.blade.php`: Align attendance register styling to the same institutional PDF standard.
- **Provide Preview & Verification Route/Script**:
  - Ensure administrative controllers render and stream PDFs with zero DomPDF layout breakage, accurate pagination, and exact column percentage distributions.

## Capabilities

### Modified Capabilities
- `fee-management`: Enhance requirement 4 (Receipt Generation and Print-Ready Voucher Layout) and requirement 7 (Financial Collection Summaries and Date-Based Reports) to mandate adherence to the institutional print-ready PDF design standard with toner-safe outlines, monospace financial alignment, and official multi-sign-off verification blocks.

### New Capabilities
- `institutional-pdf-reports`: Defines the system-wide visual, typographical, and structural standards for all institutionally generated PDF documents (Invoices, Receipts, Academic Registers, and Attendance Summaries), ensuring high information density, print-safe contrast, and consistent school administrative branding.

## Impact

- **Templates Affected**:
  - `resources/views/layouts/pdf-base.blade.php` (New)
  - `resources/views/admin/fees/invoices/pdf.blade.php`
  - `resources/views/admin/fees/receipts/pdf.blade.php`
  - `resources/views/admin/fees/reports/pdf-collections.blade.php`
  - `resources/views/admin/fees/reports/pdf-dues.blade.php`
  - `resources/views/admin/attendance/reports/pdf.blade.php`
- **Dependencies & Tools**:
  - Works seamlessly with Laravel DomPDF (`barryvdh/laravel-dompdf`). No new composer or npm packages required.
- **Database / Schema**:
  - No database migration required. Utilizes existing school tenant settings (`$school->name`, `$school->address`, `$school->phone`, `$school->logo_path`).
