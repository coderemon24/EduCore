# Design: Standardize Institutional PDF Design System

## Context

All PDF documents in this application are compiled using `barryvdh/laravel-dompdf` (DomPDF 2.x/3.x). DomPDF operates under specific HTML/CSS rendering constraints:
- Does NOT reliably support CSS Grid, Flexbox, or modern CSS variables (`var(--...)`).
- Requires strict HTML `<table>` layouts with explicit percentage column widths (`table-layout: fixed; width: 100%`) to avoid horizontal overflow and column clipping.
- Requires internal `<style>` sheets with standard web-safe fonts (`Helvetica`, `Arial`, `sans-serif`, and `monospace`).

Currently, each PDF template (`pdf-collections.blade.php`, `pdf-dues.blade.php`, `invoices/pdf.blade.php`, `receipts/pdf.blade.php`, `attendance/reports/pdf.blade.php`) contains repetitive, isolated CSS and header structures, leading to inconsistent branding, heavy ink usage, and inconsistent sign-off verification.

See `proposal.md` for overarching motivation and capability definitions.

## Goals / Non-Goals

**Goals:**
- Provide a unified, DRY Blade layout architecture (`layouts/pdf-base.blade.php` + shared partials) for all PDF outputs.
- Implement an **Executive Institutional Print Standard**:
  - High information density with crisp 0.75pt hairline dividers (`#cbd5e1`).
  - Strict typography scale using Helvetica and monospace tabular figures.
  - Toner-safe outline badges and high-contrast slate text (`#0f172a`).
  - Standardized school branding and 3-tier authorization signature blocks.
- Guarantee 100% compatibility with DomPDF page breaks, header repeats, and pagination.

**Non-Goals:**
- Replacing DomPDF with Node.js/Puppeteer/Browsershot (avoids introducing heavy binary and headless browser dependencies).
- Modifying underlying accounting or attendance calculation business logic (this change is presentation-layer and architectural standardization only).

## Decisions

### 1. Reusable Blade Base Layout and Partials vs. Isolated Templates
- **Decision**: Create `resources/views/layouts/pdf-base.blade.php` alongside modular partials:
  - `layouts/partials/pdf-header.blade.php`: Institutional name, address, phone, EIIN, document title, and generation timestamp.
  - `layouts/partials/pdf-signatures.blade.php`: Configurable verification blocks (supports 2-column for vouchers and 3-column for registers).
- **Rationale**: Eliminates ~500 lines of duplicate CSS across fee and attendance modules; changes to institution contact or logo automatically reflect on all PDFs.
- **Alternative considered**: Keeping separate files. Rejected due to drift in color palettes, margin bugs, and inconsistent headers.

### 2. Toner-Safe Outline Badges vs. Solid Dark Fills
- **Decision**: Replace solid dark fills (`background-color: #4f46e5; color: #fff;`) with outline badges:
  ```css
  .doc-badge {
      display: inline-block;
      border: 1px solid #0f172a;
      color: #0f172a;
      font-size: 8.5px;
      font-weight: bold;
      padding: 2px 7px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-radius: 3px;
  }
  ```
- **Rationale**: Prevents heavy ink usage on school laser printers, eliminates photocopy blotching, and maintains high-contrast executive polish.

### 3. Monospace Financial Numerals and Alignment Discipline
- **Decision**: All currency amounts, transaction IDs, invoice numbers, and dates must render in `font-family: monospace;` with explicit `text-align: right` (for currency) and `text-align: center` (for dates and IDs).
- **Rationale**: Aligns decimal points vertically across ledger rows for immediate scanning by school accountants and auditors.

### 4. DomPDF Page Pagination via Embedded PHP / CSS Counter
- **Decision**: Use DomPDF-compatible page numbering in `@page` or footer:
  ```css
  @page {
      margin: 10mm 12mm 14mm 12mm;
  }
  .page-number:before {
      content: "Page " counter(page) " of ";
  }
  ```
- **Rationale**: Provides audit traceability across multi-page collection registers and class rosters without overflowing printable margins.

## Risks / Trade-offs

- **[Risk] DomPDF Table Page-Break Clipping** → *Mitigation*: Apply `page-break-inside: avoid;` to table rows (`tr`) and signature blocks; declare `thead { display: table-header-group; }` so table headers automatically repeat on successive pages.
- **[Risk] Long School Names or Addresses Wrapping** → *Mitigation*: Implement `table-layout: fixed` with two-column header split (65% institution info, 35% document metadata) with `word-wrap: break-word`.
- **[Risk] Variable Paper Sizes (A4 Portrait vs. Landscape)** → *Mitigation*: Allow child templates to override `@section('paper_size', 'a4 portrait')` or `'a4 landscape'` in the base layout.

## Migration Plan

1. Create `resources/views/layouts/pdf-base.blade.php` and signature/header partials.
2. Refactor Fee Management templates:
   - `admin/fees/invoices/pdf.blade.php`
   - `admin/fees/receipts/pdf.blade.php`
   - `admin/fees/reports/pdf-collections.blade.php`
   - `admin/fees/reports/pdf-dues.blade.php`
3. Refactor Attendance report template:
   - `admin/attendance/reports/pdf.blade.php`
4. Execute test rendering script (`scratch/test_collections_pdf.php` and invoice/receipt rendering) to visually verify DomPDF compilation and layout fidelity.
