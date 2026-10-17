# Tasks: Standardize Institutional PDF Design System

## 1. Layout & Blade Architecture

- [ ] 1.1 Create `resources/views/layouts/pdf-base.blade.php` containing standardized DomPDF print resets, configurable `@page` sizing (portrait/landscape), slate-based high-contrast typography, and 0.75pt hairline table border tokens.
- [ ] 1.2 Create `resources/views/layouts/partials/pdf-header.blade.php` providing standardized institutional branding (school name, address, contact, EIIN/code), document classification badge, and generation timestamps.
- [ ] 1.3 Create `resources/views/layouts/partials/pdf-signatures.blade.php` supporting both 2-column (cashier & guardian) and 3-column (prepared by, accountant, principal) official sign-off certification blocks.

## 2. Blade Views & Interactions (Fee Management Templates)

- [ ] 2.1 Refactor Fee Invoices template `resources/views/admin/fees/invoices/pdf.blade.php` to extend `layouts.pdf-base`, replacing heavy solid badges with toner-safe outline badges and adding the official authorization block.
- [ ] 2.2 Refactor Fee Receipts template `resources/views/admin/fees/receipts/pdf.blade.php` to extend `layouts.pdf-base` with compact ledger styling, monospace amount alignments, and dual-party signature lines.
- [ ] 2.3 Refactor Fee Collections Register `resources/views/admin/fees/reports/pdf-collections.blade.php` to extend `layouts.pdf-base` (landscape), ensuring exact percentage table widths and 3-tier audit verification.
- [ ] 2.4 Refactor Class-wise Outstanding Dues template `resources/views/admin/fees/reports/pdf-dues.blade.php` to extend `layouts.pdf-base` with parameter summary box and clean ledger balances.

## 3. Blade Views & Interactions (Attendance Module)

- [ ] 3.1 Refactor Student Attendance report `resources/views/admin/attendance/reports/pdf.blade.php` to extend `layouts.pdf-base` with toner-friendly status tags and teacher/headmaster verification blocks.

## 4. Verification & Testing

- [ ] 4.1 Run a verification script rendering Invoices, Receipts, Collections, Dues, and Attendance PDFs to confirm flawless DomPDF compilation without CSS overflow or memory faults.
- [ ] 4.2 Validate print fidelity: confirm table headers repeat properly across page breaks (`table-header-group`) and monetary columns align strictly to the right in monospace.
