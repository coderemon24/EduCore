# Tasks

## 1. Models & Migrations

- [x] 1.1 Create database migration for `fee_categories` and `fee_structures` with `school_id`, composite indexes, and `DECIMAL(12, 2)` monetary columns.
- [x] 1.2 Create database migration for `fee_invoices` and `fee_invoice_items` with tenant isolation, status, and authoritative balance columns (`subtotal`, `discount_amount`, `paid_amount`, `due_amount`).
- [x] 1.3 Create database migration for `fee_payments` and `fee_discounts` supporting offline payment methods, idempotency tokens, and non-destructive reversal audit fields.
- [x] 1.4 Create Eloquent models (`FeeCategory`, `FeeStructure`, `FeeInvoice`, `FeeInvoiceItem`, `FeePayment`, `FeeDiscount`) using the `BelongsToSchool` trait and defining relationships.
- [x] 1.5 Update `RolePermissionSeeder` to register fee permissions (`fees.view`, `fees.structure.manage`, `fees.invoice.create`, `fees.collect`, `fees.discount`, `fees.reverse`, `fees.reports`) and execute database seeder.

## 2. Layout & Blade Architecture

- [x] 2.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to add a collapsible "Fees & Accounts" dropdown menu with sub-routes (Invoices & Dues, Fee Structures, Fee Categories, Financial Reports).
- [x] 2.2 Implement JavaScript dropdown toggle helper `toggleFeesSidebarDropdown` and tooltip state in the admin sidebar.
- [x] 2.3 Create reusable Blade badges and print-friendly styles for invoice statuses (`unpaid`, `partially_paid`, `paid`, `overdue`, `reversed`).

## 3. Controllers & Business Logic

- [x] 3.1 Implement `InvoiceGenerationService` to orchestrate single and bulk student invoice generation with sequential invoice number formatting.
- [x] 3.2 Implement `FeePaymentService` for atomic offline payment processing with pessimistic row locks (`lockForUpdate`), idempotency token validation, receipt number generation, and safe reversal workflows.
- [x] 3.3 Create FormRequests (`StoreFeeCategoryRequest`, `StoreFeeStructureRequest`, `StoreInvoiceRequest`, `StorePaymentRequest`, `ReversePaymentRequest`, `StoreWaiverRequest`) with strict validation rules.
- [x] 3.4 Implement `FeeCategoryController` and `FeeStructureController` for institutional fee catalogs and grade-level rate matrices.
- [x] 3.5 Implement `FeeInvoiceController` for invoice directory filtering, single/bulk generation, invoice show view, and waiver applications.
- [x] 3.6 Implement `FeePaymentController` for offline payment processing, payment history listing, payment reversal action, and printable receipt voucher generation.
- [x] 3.7 Implement `FeeReportController` for daily collection register, date-range collection logs, payment method breakdown, and class-wise outstanding balance summaries.
- [x] 3.8 Register administrative routes in `routes/admin.php` under `admin.fees.*` with permission middleware guards.

## 4. Blade Views & Interactions

- [x] 4.1 Build `resources/views/admin/fees/categories/index.blade.php` for managing fee categories with inline create/edit modals.
- [x] 4.2 Build `resources/views/admin/fees/structures/index.blade.php` for configuring academic fee rates by class and session.
- [x] 4.3 Build `resources/views/admin/fees/invoices/index.blade.php` with summary metric cards, status pills, and advanced filters (Class, Section, Status, Date).
- [x] 4.4 Build `resources/views/admin/fees/invoices/create.blade.php` supporting both individual student and bulk class-level invoice creation.
- [x] 4.5 Build `resources/views/admin/fees/invoices/show.blade.php` displaying itemized charges, applied discounts, payment history, waiver drawer, and payment triggers.
- [x] 4.6 Build `resources/views/admin/fees/payments/collect.blade.php` (or modal drawer) with offline payment fields, idempotency tokens, and double-click prevention.
- [x] 4.7 Build `resources/views/admin/fees/receipts/show.blade.php` with high-density print voucher styling (`@media print` support, school logo, student data, cashier signature).
- [x] 4.8 Build `resources/views/admin/fees/reports/index.blade.php` with date-range filters, daily collection log, payment method summaries, and print/export buttons.

## 5. Verification & Testing

- [x] 5.1 Test exact decimal balance arithmetic on invoices with multiple items, discounts, and payments.
- [x] 5.2 Test partial payment processing and status transitions (`unpaid` -> `partially_paid` -> `paid`).
- [x] 5.3 Test idempotency token enforcement to verify duplicate payment submissions are prevented.
- [x] 5.4 Test payment reversal workflow: verify payment is marked reversed, audit fields populated, and invoice due balance restored.
- [x] 5.5 Test multi-tenant isolation: verify cross-school access is blocked by `SchoolScope` and `BelongsToSchool`.
- [x] 5.6 Test permission gates (`fees.collect`, `fees.discount`, `fees.reverse`) with authorized and unauthorized users.
- [x] 5.7 Run `vendor/bin/pint` to ensure code styling compliance and run PHPUnit test suite.
