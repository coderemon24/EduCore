# Design: School Fee Management & Financial Records

## Context

EduCore SIS is a multi-tenant Laravel 12 application operating with tenant isolation via `BelongsToSchool` and `SchoolScope`. The system already manages students, academic classes, sections, staff, roles/permissions, timetables, and attendance. Financial operations are internal to each school and require strict transactional consistency, immutable audit records, and offline payment workflows.

See [proposal.md](proposal.md) for business motivation and [specs/fee-management/spec.md](specs/fee-management/spec.md) for requirements.

## Goals / Non-Goals

**Goals:**
- Provide exact decimal calculation (`DECIMAL(12, 2)`) with zero floating-point inaccuracies.
- Ensure strict tenant isolation so financial data is never exposed or mixed across schools.
- Implement server-authoritative balance calculation with pessimistic row locking (`lockForUpdate`) during payment processing.
- Prevent duplicate double-clicks and repeated submissions via client/server idempotency tokens.
- Maintain immutable financial history with an audited reversal/void mechanism rather than silent row deletions.
- Provide a clean, print-optimized receipt voucher for thermal and A4 printers.
- Deliver a responsive, dense, slop-free admin UI with an integrated sidebar dropdown.

**Non-Goals:**
- Online payment gateway integration (Stripe, PayPal, SSLCommerz, etc.) — strictly offline payments in this phase.
- SaaS platform subscription billing or multi-school SaaS tier management.
- Double-entry general ledger accounting (chart of accounts, trial balance) — scoped to student fee management and institutional collection registers.

## Decisions

### 1. Dedicated Service Layer (`FeePaymentService` & `InvoiceGenerationService`)
- **Decision**: Encapsulate financial calculations, invoice creation, and payment collection within dedicated domain services instead of bloating controllers or models.
- **Rationale**: Financial calculations require atomic transactions, balance recalculations, and idempotency checks. Keeping this in services makes actions easily testable and reusable.
- **Alternatives Considered**: Fat models or fat controllers. Rejected because financial operations cross multiple models (`FeeInvoice`, `FeeInvoiceItem`, `FeePayment`).

### 2. Idempotency Token and Row Locking
- **Decision**: Every payment form generates a UUID v4 `idempotency_token`. The payment creation endpoint uses `DB::transaction()` with `FeeInvoice::lockForUpdate()->find($id)`. If `FeePayment::where('school_id', $schoolId)->where('idempotency_token', $token)->first()` exists, it returns the existing receipt without duplicate balance deductions.
- **Rationale**: Network lag or impatient users double-clicking "Submit Payment" could otherwise record two payments and over-deduct dues.
- **Alternatives Considered**: Relying solely on frontend `disabled` button state. Rejected because network retries and browser refresh can still duplicate requests.

### 3. Server-Authoritative Balances vs. Pure Event Sourcing
- **Decision**: Store denormalized balance attributes on `fee_invoices` (`subtotal`, `discount_amount`, `paid_amount`, `due_amount`, `status`) while maintaining full transaction rows in `fee_payments` and `fee_discounts`. Every payment or reversal recalculates these columns inside the same database transaction.
- **Rationale**: Instant index-backed queries and filtering for `status = 'unpaid'` or `status = 'overdue'` across thousands of students without having to execute heavy aggregate subqueries on every listing view.
- **Alternatives Considered**: Pure dynamic calculation on-the-fly (`SUM(payments)`). Rejected due to severe query performance degradation when filtering or sorting large student invoice directories.

### 4. Non-Destructive Payment Reversal Workflow
- **Decision**: Completed payments cannot be deleted (`DELETE`). To undo a payment, an authorized user executes a `reverse` action which sets `status = 'reversed'`, records `reversed_by`, `reversed_at`, and mandatory `reversal_reason`, and recalculates the invoice's `paid_amount` and `due_amount`.
- **Rationale**: Financial and school audit integrity demands that cancelled or bounced receipts remain traceable in reports.
- **Alternatives Considered**: Soft-deletes. Rejected because soft-deleting hides the transaction from view, whereas financial transparency requires visible audit markers on receipts and reports.

### 5. Multi-Tenant Sequential Numbering
- **Decision**: Generate sequential invoice numbers (e.g. `INV-2026-00001`) and receipt numbers (e.g. `REC-2026-00001`) using school-scoped auto-increment or transactional sequence lookups (`MAX(id)` scoped to `school_id`).
- **Rationale**: Schools expect cleanly formatted institutional receipt numbers rather than raw global database IDs.

### 6. Admin Sidebar Dropdown Architecture
- **Decision**: Follow the established pattern used for "Student Management" and "Timetables" in [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php). Implement `toggleFeesSidebarDropdown` with smooth SVG chevron rotation, active route detection (`request()->routeIs('admin.fees.*')`), and tooltips for collapsed sidebar states.
- **Rationale**: Seamless UI consistency without introducing external dependencies.

## Risks / Trade-offs

- **[Risk: Concurrent Payment Submissions]** Multiple cashiers collecting payment for the same student simultaneously.
  - *Mitigation*: Pessimistic row locking (`lockForUpdate`) on the invoice row inside a database transaction guarantees that only one transaction processes at a time, rejecting the second if it exceeds the remaining balance.
- **[Risk: Large Class Bulk Invoicing Timeout]** Generating invoices for 1,000+ students in a single HTTP request.
  - *Mitigation*: Chunk student enrollment records and execute bulk batch inserts (`FeeInvoice::insert` / `FeeInvoiceItem::insert`) within a single database transaction, optimized with composite foreign keys.
- **[Risk: Browser Floating Point Mismatch]** JavaScript calculations in payment modals diverging from PHP calculations.
  - *Mitigation*: Browser calculations are purely informational preview helpers; the server validates all numbers using cent-rounded precision and rejects invalid submissions.

## Migration Plan

1. Create migrations for `fee_categories`, `fee_structures`, `fee_invoices`, `fee_invoice_items`, `fee_payments`, and `fee_discounts`.
2. Add new permissions to `RolePermissionSeeder` and run `db:seed --class=RolePermissionSeeder`.
3. Create models with `BelongsToSchool` trait and proper relationships.
4. Implement `FeePaymentService` and `InvoiceGenerationService`.
5. Build FormRequests with strict validation rules.
6. Create Controllers and Admin Routes under `admin.fees.*`.
7. Build Blade templates and update sidebar navigation.
8. Execute automated feature tests verifying decimal accuracy, partial payments, idempotency, and reversals.
