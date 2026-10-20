# EduCore — Multi-Tenant School Management System (SMS)

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/PHPUnit-17_Feature_Suites-green?style=flat&logo=php)](https://phpunit.de)

EduCore is an enterprise-grade School Management System (SMS / SIS) built with **Laravel 12**. Designed with institutional multi-tenancy, it streamlines academic operations, student admissions, dynamic examination grading, automated fee billing, timetable management, and public school portals.

---

## 🏛️ Architectural Highlights

- **Multi-Tenancy Data Isolation**: Implemented via global Eloquent scopes (`BelongsToSchool`) and route model binding, ensuring strict data boundaries between schools without cross-tenant leakage.
- **Service-Oriented Architecture**: Clean separation of concerns keeping controllers lean by delegating complex business transactions (promotions, mark tabulation, fee reversals) to dedicated services.
- **Granular RBAC**: Institution-level Role-Based Access Control with fine-grained permission middleware protecting every administrative route.
- **Audit-Safe Transactions**: Soft deletes with restore/force-delete capabilities, attendance audit logging, and strict ledger-like fee reversals.
- **Comprehensive Test Coverage**: 17 dedicated Feature Test suites covering core business workflows (admissions, fee invoicing, mark calculation, PDF exports, permissions).

---

## 🚀 Key Modules & Features

### 1. Academic & Curriculum Hierarchy
- Multi-session management with active academic year switching.
- Class, section, and stream/group management.
- Dynamic curriculum matrix (Class-Subject assignments with compulsory/optional designations).
- Academic calendar and holiday exception tracker.

### 2. Student Information System (SIS) & Promotion
- Full admission lifecycle with guardian relationships and emergency profiles.
- Automated roll number assignments and section enrollments.
- Batch optional subject assigner.
- Session-to-session bulk student promotion engine with eligibility checks.

### 3. Examination & Automated Tabulation
- Dynamic mark component breakdown (Theory, Practical, Viva, Continuous Assessment).
- Configurable grading scales and tier intervals.
- Automated marks tabulation, GPA computation, and result publishing control.
- Bulk & individual Report Card / Academic Transcript PDF generation.

### 4. Fee & Financial Management
- Structured fee categories and class-wise fee schedules.
- Bulk invoice generation with due dates and discount/waiver adjustments.
- Payment collection workflow with partial settlement support.
- Audit-compliant payment reversals and printable PDF money receipts.

### 5. Staff, Attendance & Timetables
- Teaching and non-teaching staff directories with employee records.
- Daily attendance registers for both students and staff with audit logs.
- Weekly timetable scheduling matrix with teacher clash validation.

### 6. Public Portal & Notice Board
- Multi-tenant public frontend for each school (Institutional profile, staff directory, academic routines, contact inquiries).
- Public notice board with pin-to-top circulars and file attachments.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Language**: PHP 8.2+
- **Database**: SQLite (default local) / MySQL / PostgreSQL compatible
- **Styling & Assets**: Tailwind CSS v4, Vite 7
- **Client Interactions**: Alpine.js, Select2, SweetAlert2, Swiper
- **PDF Generation**: `spatie/laravel-pdf`, `dompdf/dompdf`
- **Testing**: PHPUnit 11 (Feature & Unit suites)

---

## ⚡ Quick Start / Local Setup

Follow these steps to run the application locally:

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/laravel-school.git
   cd laravel-school
   ```

2. **Install PHP and JavaScript dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seed the demo data**:
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets and start dev servers**:
   ```bash
   npm run build
   php artisan serve
   ```
   *Or run the all-in-one dev command:*
   ```bash
   composer dev
   ```

The application will be accessible at `http://127.0.0.1:8000`.

---

## 🔐 Default Demo Credentials

After seeding the database (`php artisan db:seed`), use the following credentials to access the administrative dashboard at `/admin/login`:

| Field | Credential |
|---|---|
| **Login URL** | `http://127.0.0.1:8000/admin/login` |
| **Email / Username** | `admin@gmail.com` (or `admin`) |
| **Password** | `password` |
| **Access Level** | Super Administrator |

---

## 🧪 Running Automated Tests

To run the complete test suite:

```bash
php artisan test
```

Or run specific feature tests:
```bash
php artisan test --filter=ExamGradingAndResultsTest
php artisan test --filter=FeeManagementTest
php artisan test --filter=StudentManagementTest
```
