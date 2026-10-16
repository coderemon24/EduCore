# Proposal

## Why

To function as a multi-tenant School Management System & ERP, institutions on EduCore SIS require a complete, robust academic hierarchy. Currently, the admin sidebar displays placeholder alerts for academic operations, and no database entities exist for academic years, classes, sections, subjects, or academic streams. 

Institutions cannot enroll students, schedule timetables, conduct exams, or track attendance without this foundational structure. Implementing this academic domain now provides the relational backbone for all upcoming SIS modules (Students, Teachers, Attendance, Exams, and Fees) while enforcing strict school ownership isolation, optimal database indexing, and a clean, high-density administrative user interface.

## What Changes

1. **Multi-Tenant Academic Database Schema & Indexing**:
   - Create migrations for 6 core academic entities:
     - `academic_years`: Sessions (e.g., 2025-2026, 2026-2027) with start/end dates, active status, and an atomic `is_current` toggle (only one current year per school).
     - `academic_classes`: Grade levels (e.g., Class 1 to Class 12, Nursery, Kindergarten) with numerical ordering.
     - `sections`: Class divisions (e.g., Section A, Section B, Rose, Lily) with capacity and room number, uniquely named within their class.
     - `subjects`: Institutional course catalog (e.g., General Mathematics, English Literature, Physics) with subject codes and type (`theory`, `practical`, `both`).
     - `class_subject_assignments`: Relational assignment mapping subjects to classes with credit/mark weighting, compulsory/elective designation, and optional academic-year overrides.
     - `academic_groups`: Optional academic streams (Science, Commerce/Business Studies, Humanities/Arts, General) assignable to senior grade sections.
   - Enforce row-level multi-tenancy using `BelongsToSchool` and `SchoolScope`.
   - Prevent cross-tenant record contamination by verifying that related classes, sections, and subjects belong exclusively to the active school.
   - Apply composite indexes (`[school_id, is_current, status]`, `[school_id, status, order_index]`, `[school_id, academic_class_id, status]`) for sub-millisecond query execution.

2. **Query & Performance Optimization**:
   - Prevent N+1 queries by eager-loading relations (`with(['sections', 'subjects'])`) and counting child records with single-query subqueries (`withCount(['sections', 'subjects'])`).
   - Use conditional SQL aggregation (`COUNT(CASE WHEN ... THEN 1 END)`) for listing tab metrics in a single query rather than multi-query loops.
   - Restrict select queries to explicitly necessary columns.

3. **Human-Crafted Sidebar Accordion Dropdown ("No-Slop" UI)**:
   - Upgrade [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) under "Academic & Operations" to an interactive, accessible collapsible dropdown menu ("Academic Structure") with smooth 180-degree chevron rotation.
   - Auto-expand the dropdown when active on any child academic route (`admin.academic.*`).
   - Support floating flyout navigation on hover when the sidebar is collapsed in desktop icon mode.
   - Strictly follow the `no-slop-ui` protocol: zero cliché gradients, zero bloated glassmorphism, authentic typography (`Plus Jakarta Sans`), crisp 1px borders, high-information-density data tables, and subtle violet accents.

4. **Complete Administrative CRUD & Dependent Dropdown Workflows**:
   - Full list, create, edit, and deletion interfaces for Academic Years, Classes, Sections, Subjects, Class-Subject Assignments, and Groups.
   - Dependent dropdown UI: Selecting a Class dynamically filters Sections and Streams via lightweight JSON endpoints without full-page reloading.
   - Integrate existing Select2 for searchable single- and multi-select elements.
   - Implement SweetAlert2 for destructive action confirmations and ToastMagic / Toastr for instant session feedback.

## Capabilities

### New Capabilities
- `academic-structure`: Core academic hierarchy management covering academic years/sessions, classes, sections, subjects catalog, class-subject curriculum assignments, and academic streams with tenant-scoped validation, query optimizations, and an administrative dropdown interface.

### Modified Capabilities
<!-- None: No existing capability requirements are modified. -->

## Impact

- **Database Migrations**: 6 new migrations creating `academic_years`, `academic_classes`, `sections`, `subjects`, `class_subject_assignments`, and `academic_groups` tables with foreign keys and composite indexes.
- **Eloquent Models**: 6 new models in `app/Models/` using `BelongsToSchool` trait and typed relationships.
- **Form Requests**: 6 Form Requests in `app/Http/Requests/Admin/Academic/` with unique tenant-aware validation rules and cross-school integrity checks.
- **Controllers**: Resource controllers in `app/Http/Controllers/Admin/Academic/` utilizing eager loading, pagination, and single-query metrics.
- **Routes**: Academic route group registered under `admin.academic.*` in [routes/admin.php](file:///c:/laragon/www/laravel-school/routes/admin.php).
- **Views**: Blade templates in `resources/views/admin/academic/` (index, create, edit, assign-subjects) plus sidebar dropdown in [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php).
- **JavaScript**: Sidebar dropdown accordion handler in `admin-sidebar.js` and dependent dropdown script in `academic-dependent-dropdown.js`.
