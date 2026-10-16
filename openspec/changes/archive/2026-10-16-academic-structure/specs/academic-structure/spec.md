# Spec Delta

## Purpose
The academic-structure capability provides the multi-tenant foundational hierarchy for institutions operating on EduCore SIS, encompassing academic sessions/years, classes/grades, sections, course subjects, class-subject assignments, and academic streams with query-optimized indexing and an administrative sidebar accordion navigation.

## ADDED Requirements

### Requirement: Academic Year and Session Management
The system MUST allow administrative users to manage academic years (sessions) with defined date ranges, active/inactive statuses, and a single active current session per school institution.

#### Scenario: Creating a valid academic year
- **WHEN** an administrator creates an academic year with a name, valid start date, and an end date after the start date
- **THEN** the record is persisted for the current school with status "active" and scoped by `school_id`.

#### Scenario: Preventing invalid date ranges
- **WHEN** an administrator submits an academic year where the end date is on or before the start date
- **THEN** the system rejects the submission with a validation error indicating that end date must be after start date.

#### Scenario: Setting active current year
- **WHEN** an academic year is designated as `is_current = true`
- **THEN** the system atomically updates all other academic years belonging to that school to `is_current = false`.

#### Scenario: Preventing duplicate academic year names in the same school
- **WHEN** an administrator creates an academic year with a name that already exists in that school
- **THEN** the system rejects the submission with a unique constraint validation error.

---

### Requirement: Academic Class and Grade Level Management
The system MUST provide class/grade management with configurable numerical ordering, active/inactive status, and institutional uniqueness.

#### Scenario: Creating an academic class
- **WHEN** an administrator creates a class (e.g., "Class 10") with a unique name and order index
- **THEN** the class is stored with the institution's `school_id` and order index for proper sorted display.

#### Scenario: Preventing duplicate class names within a school
- **WHEN** an administrator attempts to create a class with a name already existing in the current school
- **THEN** the system rejects the request with a validation message.

#### Scenario: Cross-school class isolation
- **WHEN** two distinct schools create a class named "Class 10"
- **THEN** both records are successfully saved independently under their respective `school_id` values.

---

### Requirement: Section Management and Class Association
The system MUST allow schools to create sections associated with specific classes, with student capacity limits, room assignments, and unique naming per class.

#### Scenario: Creating a section within a class
- **WHEN** an administrator creates Section "A" under "Class 10" with capacity 40 and room "Room 101"
- **THEN** the section is persisted and associated with both the class and the school.

#### Scenario: Preventing duplicate section names within the same class
- **WHEN** an administrator attempts to create another section named "A" under the same class in the same school
- **THEN** the system rejects the submission with a duplicate validation error.

#### Scenario: Allowing identical section names across different classes
- **WHEN** an administrator creates Section "A" under "Class 9" and Section "A" under "Class 10"
- **THEN** both sections are created successfully because section uniqueness is scoped to `(school_id, academic_class_id, name)`.

---

### Requirement: Subjects Catalog Management
The system MUST provide an institution-wide subject catalog defining subject names, unique subject codes, subject types (`theory`, `practical`, `both`), and credit hours/marks.

#### Scenario: Creating a new subject
- **WHEN** an administrator adds a subject with name "Mathematics", code "MATH-101", and type "theory"
- **THEN** the subject is saved under the institution with active status.

#### Scenario: Enforcing unique subject codes per school
- **WHEN** an administrator attempts to create a subject with a code that already exists in that school
- **THEN** the system blocks creation with a validation error on the code field.

---

### Requirement: Class-Subject Assignment Matrix
The system MUST allow institutions to assign subjects from the school catalog to specific classes, specifying whether the subject is compulsory or elective, its full marks, pass marks, and optional academic-year associations.

#### Scenario: Assigning subjects to a class
- **WHEN** an administrator assigns "Mathematics" to "Class 10" with full marks 100 and pass marks 33
- **THEN** the assignment is stored and linked to the class.

#### Scenario: Preventing cross-tenant assignment
- **WHEN** a request attempts to assign a subject belonging to School B to a class belonging to School A
- **THEN** the system rejects the request with a 422 unprocessable entity or 403 forbidden error.

#### Scenario: Preventing duplicate subject assignments within the same class and session
- **WHEN** an administrator attempts to assign a subject to a class that is already assigned to that class
- **THEN** the system prevents duplicate assignment and reports that the subject is already assigned.

---

### Requirement: Academic Groups and Streams
The system MUST support optional academic groups or streams (e.g., Science, Commerce, Humanities, General) and allow assigning them to classes or sections for senior secondary levels.

#### Scenario: Managing academic groups
- **WHEN** an administrator creates a group named "Science" with code "SCI"
- **THEN** the group is saved under the school and available for section or class categorization.

---

### Requirement: Sidebar Accordion Dropdown Navigation
The administrative navigation sidebar MUST provide a collapsible "Academic Structure" dropdown menu under the Academic category, replacing static alerts with working sub-navigation.

#### Scenario: Expanding the Academic dropdown menu
- **WHEN** an administrator clicks "Academic Structure" in the sidebar
- **THEN** the submenu smoothly expands, rotating the chevron icon 180 degrees, displaying links to Academic Years, Classes, Sections, Subjects, Assign Subjects, and Groups.

#### Scenario: Auto-expanding on active child route
- **WHEN** an administrator navigates to any route prefixed with `admin.academic.*`
- **THEN** the sidebar dropdown renders in an open state with the active sub-item highlighted.

#### Scenario: Responsive collapsed sidebar mode
- **WHEN** the sidebar is collapsed into desktop icon-only mode
- **THEN** hovering over the Academic icon displays a floating, accessible flyout menu with the sub-items.

---

### Requirement: Dependent Dropdown Filtering
The system MUST provide dynamic dependent dropdown interactions on the frontend when assigning records.

#### Scenario: Dynamic section loading based on selected class
- **WHEN** a user selects a class in a dropdown form
- **THEN** the section dropdown dynamically fetches and populates only sections belonging to that selected class without a full page reload.

---

### Requirement: Multi-Tenant Scoping and Query Optimization
All academic entity queries MUST automatically apply the tenant scope, and listing views MUST use eager loading and single-query aggregations to eliminate N+1 queries.

#### Scenario: Automatic tenant filtering on academic listings
- **WHEN** an administrator queries classes, sections, or subjects
- **THEN** `SchoolScope` automatically restricts the query to `school_id = current_school_id`, never exposing data from other institutions.

#### Scenario: Optimized class listing with eager loaded relations
- **WHEN** the class listing page loads
- **THEN** sections and subject counts are eager-loaded via `withCount(['sections', 'subjects'])`, executing in bounded constant queries regardless of table row counts.
