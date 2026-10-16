# Design

## Context

See [proposal.md](file:///c:/laragon/www/laravel-school/openspec/changes/academic-structure/proposal.md) for background and institutional motivation.

The application uses Laravel 12 on PHP 8.2+ with row-level multi-tenancy enforced through `BelongsToSchool` and `SchoolScope` bound to `app('current_school_id')`. The admin layout ([admin.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin.blade.php)) includes Select2 for enhanced inputs, ToastMagic for session alerts, and Tailwind CSS v4. Currently, [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) contains static placeholder alerts for academic links.

This design introduces the complete academic domain model, database indexing, query optimization, and an accessible sidebar dropdown interface following the `no-slop-ui` protocol.

## Goals / Non-Goals

**Goals:**
- Provide 6 relational academic entities: `AcademicYear`, `AcademicClass`, `Section`, `Subject`, `ClassSubjectAssignment`, and `AcademicGroup`.
- Prevent cross-tenant data contamination via `BelongsToSchool`, `SchoolScope`, and Form Request validation.
- Enforce that exactly one academic year per institution can be marked `is_current = true` at any given time.
- Implement composite indexes to optimize tenant status filtering, ordering, and foreign key traversals.
- Eliminate N+1 queries using selective eager loading (`withCount(['sections', 'subjects'])`) and single-query metrics.
- Upgrade the sidebar navigation to include a collapsible "Academic Structure" dropdown with active auto-expand and flyout support.
- Provide dependent dropdown filtering for classes and sections without page reloads.

**Non-Goals:**
- Student enrollment and student admissions (part of the upcoming SIS Student module).
- Teacher timetable period scheduling and routine generation.
- Exam tabulation and mark sheet generation (handled by the Examination module).

## Decisions

### 1. Model Naming: `AcademicClass` (Table: `academic_classes`)
- **Choice**: Name the model `AcademicClass` and table `academic_classes`.
- **Alternatives Considered**: `Class` (table `classes`).
- **Rationale**: `class` is a PHP reserved keyword. While PHP 8 allows `Class::class`, it causes IDE static analysis friction, namespace confusion, and syntax issues. `AcademicClass` is unambiguous and aligns with `openspec/config.yaml`.

### 2. Class-Subject Assignment: General Curriculum with Optional Academic Year Override
- **Choice**: Store assignments in `class_subject_assignments` with a nullable `academic_year_id`, `type` (`core`, `elective`, `optional`), `full_marks`, and `pass_marks`.
- **Alternatives Considered**: Strict year-only curriculum (where every single subject must be re-assigned every academic year).
- **Rationale**: Most schools maintain consistent curricula across years. Allowing `academic_year_id` to be nullable allows defining standard class subjects once, while supporting specific academic-year syllabus variations or marks adjustments when necessary.

### 3. Atomic Active Academic Year Switching
- **Choice**: Wrap the "Set Active / Current Year" action in a database transaction:
  ```php
  DB::transaction(function () use ($schoolId, $academicYear) {
      AcademicYear::where('school_id', $schoolId)->where('id', '!=', $academicYear->id)->update(['is_current' => false]);
      $academicYear->update(['is_current' => true, 'status' => 'active']);
  });
  ```
- **Alternatives Considered**: Client-side radio toggle without database transaction.
- **Rationale**: Prevents race conditions that could leave an institution with zero or multiple active academic years.

### 4. Human-Crafted Sidebar Accordion Dropdown
- **Choice**: Build the sidebar dropdown using semantic HTML, pure Tailwind CSS v4 utility classes, and vanilla JavaScript in `admin-sidebar.js`:
  ```html
  <div class="academic-dropdown-wrapper">
      <button type="button" class="academic-dropdown-toggle flex items-center justify-between w-full ..." aria-expanded="false">
          <!-- Icon & Label -->
          <svg class="chevron transition-transform duration-200 ..."></svg>
      </button>
      <ul class="academic-submenu space-y-1 pl-7 hidden ...">
          <!-- Links: Academic Years, Classes, Sections, Subjects, Assign Subjects, Groups -->
      </ul>
  </div>
  ```
- **Alternatives Considered**: Alpine.js or heavy external accordion plugins.
- **Rationale**: Keeps bundle size minimal, zero dependencies, full compatibility with the existing sidebar state management, and reliable flyout tooltip rendering in collapsed mode.

### 5. Dependent Dropdown Architecture
- **Choice**: Create lightweight AJAX/JSON endpoints:
  - `GET /admin/academic/classes/{class}/sections` returning `[{id, name, capacity, room_number}]`.
  - `GET /admin/academic/classes/{class}/subjects` returning assigned subjects.
- **Alternatives Considered**: Rendering all combinations directly in inline data attributes.
- **Rationale**: Clean separation of concerns, fast response times (<20ms), reusable across forms (e.g., student admissions, timetable scheduling, exam marks).

### 6. Database Optimization & Composite Indexing
- **Choice**: Add compound indexes tailored specifically for multi-tenant query access patterns:
  - `academic_years`: `INDEX(school_id, is_current, status)`
  - `academic_classes`: `INDEX(school_id, status, order_index)`
  - `sections`: `INDEX(school_id, academic_class_id, status)`
  - `subjects`: `INDEX(school_id, status, type)`
  - `class_subject_assignments`: `INDEX(school_id, academic_class_id, academic_year_id)`
  - `academic_groups`: `INDEX(school_id, status)`
- **Rationale**: Eliminates file-sort and table scans for tenant queries.

## Risks / Trade-offs

- **[Risk] Cross-Tenant Subject or Section Hijacking**  
  *Mitigation*: Form Requests validate that foreign keys (`academic_class_id`, `subject_id`) belong to the authenticated session's `current_school_id`:
  `Rule::exists('academic_classes', 'id')->where('school_id', app('current_school_id'))`.
- **[Risk] Deleting a Class with Active Sections and Subjects**  
  *Mitigation*: Use foreign key cascades on child relations (`sections`, `class_subject_assignments`) with SweetAlert2 confirmation dialog warning the administrator of cascading deletions.
- **[Risk] N+1 Query Degradation on High-Volume Institutions**  
  *Mitigation*: Enforce eager loading with column constraints and single-query status aggregations in all controller index methods.
