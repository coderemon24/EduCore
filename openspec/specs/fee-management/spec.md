# fee-management Specification

## Purpose
Enables schools to configure fee categories and structures, issue student invoices, record offline payments with idempotency protection, generate print-friendly receipts, authorize fee waivers, audit payment reversals, and review financial collection reports.

## Requirements

### Requirement: Fee Category and Structure Configuration
The system MUST allow authorized staff (`fees.structure.manage`) to create, update, and manage fee categories and academic fee structures scoped to the active school tenant.

#### Scenario: Creating a fee category
- **WHEN** an administrator submits a valid fee category name and unique code
- **THEN** the system stores the fee category under the active `school_id` and displays it in the fee catalog.

#### Scenario: Duplicate fee category code rejection
- **WHEN** an administrator attempts to create a fee category with a code that already exists within the same school
- **THEN** the system rejects the creation with a validation error indicating uniqueness within the institution.

#### Scenario: Creating a fee structure
- **WHEN** an administrator configures a fee structure selecting an academic year, optional academic class, fee category, frequency, due day, and decimal amount
- **THEN** the system validates that the amount is a positive decimal and records the structure for future invoice generation.

---

### Requirement: Student Fee Invoicing and Authoritative Balance Tracking
The system MUST generate student-specific invoices either individually or in bulk for a class, tracking itemized charges, authorized discounts, collected amounts, and remaining due balances with database-enforced decimal precision.

#### Scenario: Bulk invoice generation for an academic class
- **WHEN** an authorized officer (`fees.invoice.create`) selects an academic year, target class, and fee structure items to generate invoices for a specific due date
- **THEN** the system creates an invoice for each actively enrolled student in that class, assigns a unique sequential invoice number (e.g. `INV-2026-00001`), populates itemized line items, and sets the initial status to `unpaid`.

#### Scenario: Server-authoritative balance calculation
- **WHEN** an invoice is queried or updated
- **THEN** the server computes `due_amount = subtotal - discount_amount - paid_amount` and automatically assigns the correct status (`unpaid`, `partially_paid`, `paid`, or `overdue`).

---

### Requirement: Offline Payment Collection with Idempotency Protection
The system MUST accept partial and full offline payments against unpaid or partially paid invoices, validating amounts on the server within atomic database transactions and preventing duplicate submissions via unique idempotency tokens.

#### Scenario: Full payment of outstanding balance
- **WHEN** an authorized collector (`fees.collect`) enters an amount exactly equal to the invoice due balance along with payment method and an idempotency token
- **THEN** the system executes the transaction in a database lock, creates a payment record with a unique receipt number, updates the invoice `paid_amount` and `due_amount = 0.00`, and sets the invoice status to `paid`.

#### Scenario: Partial payment acceptance
- **WHEN** an authorized collector submits a payment amount less than the outstanding due balance
- **THEN** the system records the partial payment, increments the invoice `paid_amount`, decrements `due_amount`, and updates the status to `partially_paid`.

#### Scenario: Rejection of overpayment
- **WHEN** a payment submission specifies an amount greater than the current invoice `due_amount`
- **THEN** the server rejects the request with a validation error stating the payment exceeds the outstanding balance.

#### Scenario: Duplicate submission prevention
- **WHEN** a user accidentally submits a payment form multiple times with the same `idempotency_token`
- **THEN** the system detects the existing token within the school tenant, prevents duplicate balance deduction, and gracefully redirects to the already generated receipt.

---

### Requirement: Receipt Generation and Print-Ready Voucher Layout
The system MUST automatically generate a uniquely numbered receipt voucher for every completed payment and render a clean, high-density printable layout suitable for browser printing and thermal/A4 paper.

#### Scenario: Viewing and printing a receipt
- **WHEN** an administrator views a payment receipt
- **THEN** the page displays school credentials, logo, receipt number, date, student details (name, admission number, class, section), itemized fee summary, payment method, cashier name, and provides a one-click print view with print-optimized CSS.

---

### Requirement: Fee Discounts and Waivers Authorization
The system MUST allow authorized personnel (`fees.discount`) to apply fee discounts or waivers to an invoice, recording the authorized user and mandatory reason for auditability.

#### Scenario: Applying an authorized waiver
- **WHEN** an authorized user applies a discount amount with a mandatory justification to an unpaid invoice
- **THEN** the system adjusts the invoice `discount_amount`, recalculates `due_amount`, records the authorizer ID, and updates the invoice audit trail.

#### Scenario: Unauthorized discount attempt
- **WHEN** a user without the `fees.discount` permission attempts to apply or modify a fee waiver
- **THEN** the server rejects the request with a 403 Forbidden response.

---

### Requirement: Safe Payment Reversals and Zero Silent Deletions
The system MUST support reversing erroneous or bounced payments without deleting transaction history from the database, requiring an authorized user (`fees.reverse`) and a mandatory justification.

#### Scenario: Reversing a completed payment
- **WHEN** an authorized user reverses a payment and provides a mandatory reason
- **THEN** the system updates the payment status to `reversed`, logs `reversed_by`, `reversed_at`, and `reversal_reason`, increments the associated invoice's `due_amount`, decrements `paid_amount`, updates invoice status, and retains the complete payment record in history.

---

### Requirement: Financial Collection Summaries and Date-Based Reports
The system MUST provide fee collection summaries, date-range filters, daily collection registers, and class-wise outstanding dues reports with performance-optimized queries.

#### Scenario: Filtering daily collection report
- **WHEN** an administrator views the collection report with a specified start and end date
- **THEN** the system aggregates total collections, total discounts, payment method breakdowns, and displays a paginated list of transactions without N+1 query overhead.

---

### Requirement: Navigation and Dropdown Integration
The system MUST integrate a "Fees & Accounts" collapsible dropdown menu into the administrative sidebar, featuring clean transitions and active-state indicators for fee sub-routes.

#### Scenario: Accessing fee management from sidebar
- **WHEN** an administrator navigates through the admin portal
- **THEN** the sidebar displays a "Fees & Accounts" dropdown containing links to Invoices, Fee Structures, Fee Categories, and Financial Reports, with active states highlighted according to current routes.
