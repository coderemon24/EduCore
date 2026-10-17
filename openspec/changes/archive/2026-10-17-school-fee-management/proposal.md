# Proposal: School Fee Management & Financial Records

## Why

Schools require an internal financial accounting and fee collection system to define fee structures (tuition, examination, lab, library, sports, admission), issue student-specific invoices/dues, accept offline payments (cash, bank transfer, cheque, POS), apply authorized discounts or waivers, track overdue balances, issue print-friendly receipts, and audit transaction reversals without data loss.

Currently, EduCore SIS manages students, staff, academic hierarchy, timetables, and attendance, but lacks institutional fee records. Providing a robust, multi-tenant, offline-first fee management subsystem directly inside the administrative portal empowers school finance officers, accountants, and principals to maintain accurate, immutable financial audit trails with exact decimal precision and high performance.

## What Changes

1. **Fee Categories & Structures**:
   - Create catalog for institutional fee categories (e.g. Tuition, Admission, Term Examination, Laboratory, Library, Transport).
   - Define multi-tenant fee structures per academic year, grade class, with set frequencies (monthly, term, one-time, annual) and scheduled due dates.

2. **Invoice Generation & Dues Tracking**:
   - Single-student and bulk-class invoice generation.
   - Authoritative database calculation of subtotal, discounts, paid amount, and outstanding due balance.
   - Status indicators: `unpaid`, `partially_paid`, `paid`, `overdue`, and `void`.

3. **Offline Payment Collection & Receipts**:
   - Support partial and full payments with client-side idempotency tokens to prevent duplicate double-charges.
   - Record offline payment details: payment method (`cash`, `bank_transfer`, `cheque`, `pos_card`, `other`), transaction references, bank name, and cashier notes.
   - Generate unique, auto-sequenced receipt vouchers with thermal and A4 print-ready layouts.

4. **Waivers, Discounts & Authorizations**:
   - Role-based discount and waiver recording with mandatory authorization tracking (`authorized_by` and audit reasoning).

5. **Reversals, Voids & Audit Integrity**:
   - Zero silent deletion of financial transactions: payments can be safely reversed or voided by authorized staff with recorded justification, automatically recalculating invoice balances without purging history.

6. **Financial Reports & Dashboard Summaries**:
   - Date-filtered daily collection logs, payment method breakdowns, outstanding dues ledger, and class-wise balance reports.

7. **Institutional Navigation & Admin UI**:
   - Integrate a dedicated "Fees & Accounts" collapsible dropdown in the sidebar navigation matching the existing layout design.

## Capabilities

### New Capabilities
- `fee-management`: Core fee structure management, student invoice generation, offline payment processing, receipt vouchers, discounts/waivers, transaction reversals, dues ledger, and financial reporting.

### Modified Capabilities
<!-- None: Existing student and academic specs remain unmodified; fee-management integrates alongside them via foreign keys and tenant scoping. -->

## Impact

- **Database Schemas**: Adds `fee_categories`, `fee_structures`, `fee_invoices`, `fee_invoice_items`, `fee_payments`, and `fee_discounts` tables with multi-tenant `school_id` foreign keys, `DECIMAL(12, 2)` monetary columns, and composite performance indexes.
- **Models**: Introduces `FeeCategory`, `FeeStructure`, `FeeInvoice`, `FeeInvoiceItem`, `FeePayment`, and `FeeDiscount` utilizing the `BelongsToSchool` trait and tenant scoping.
- **Permissions**: Adds `fees.view`, `fees.structure.manage`, `fees.invoice.create`, `fees.collect`, `fees.discount`, `fees.reverse`, and `fees.reports` to the institutional permission matrix.
- **Routes & Controllers**: Adds dedicated administrative routes under `admin.fees.*` with controllers for categories, structures, invoices, payments, receipts, and reports.
- **Frontend Views**: Blade templates for fee categories, fee structures, invoice directories, student financial profiles, payment drawers, print vouchers, and report registers.
- **Sidebar Navigation**: Adds a collapsible "Fees & Accounts" dropdown in `resources/views/layouts/admin-partial/sidebar.blade.php`.
