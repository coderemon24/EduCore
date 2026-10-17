# Spec Delta: Fee Management

## MODIFIED Requirements

### Requirement: Receipt Generation and Print-Ready Voucher Layout
The system MUST automatically generate a uniquely numbered receipt voucher for every completed payment and render a clean, high-density printable layout adhering to the institutional print-ready standard (toner-safe outline badges, crisp hairlines, tabular monospace numbers, and dual verification lines for cashier and guardian signatures).

#### Scenario: Viewing and printing a receipt
- **WHEN** an administrator views a payment receipt
- **THEN** the page displays school credentials, logo, receipt number, date, student details (name, admission number, class, section), itemized fee summary, payment method, cashier name, and provides a one-click print view with print-optimized CSS.

#### Scenario: Printing fee receipt with executive toner-safe layout
- **WHEN** an administrator or cashier clicks "Print Receipt" on a completed payment
- **THEN** the system generates a receipt PDF formatted with the institutional header, exact column widths, outlined status badge, monospace amounts, and cashier/guardian signature sign-off lines without solid ink-heavy dark fills.

### Requirement: Financial Collection Summaries and Date-Based Reports
The system MUST provide fee collection summaries, date-range filters, daily collection registers, and class-wise outstanding dues reports formatted in the standardized institutional PDF layout with structured parameter boxes, monospace financial summaries, and three-tier authorization blocks (Prepared By, Accountant, Principal).

#### Scenario: Filtering daily collection report
- **WHEN** an administrator views the collection report with a specified start and end date
- **THEN** the system aggregates total collections, total discounts, payment method breakdowns, and displays a paginated list of transactions without N+1 query overhead.

#### Scenario: Exporting collections register PDF
- **WHEN** an authorized user requests a PDF export of filtered fee collection transactions
- **THEN** the system renders a landscape A4 report featuring the official institution header, reporting period parameter box, compact tabular rows, bold total summary, and official sign-off lines.

#### Scenario: Exporting class-wise outstanding dues report PDF
- **WHEN** an authorized user requests a PDF export of outstanding class dues
- **THEN** the system renders an executive landscape report summarizing student dues, categorized contact details, grand totals, and institutional certification blocks.
