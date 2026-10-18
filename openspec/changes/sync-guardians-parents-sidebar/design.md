# Design: Sync Guardians & Parents Sidebar with No-Slop UI

## Context

The system has an existing `Guardian` Eloquent model with multi-tenant `BelongsToSchool` scoping and `SoftDeletes`, linked to `Student` models via `guardian_student` pivot table. However, the sidebar menu item for "Parents" in `resources/views/layouts/admin-partial/sidebar.blade.php` is hardcoded with a JavaScript `Swal.fire` modal placeholder.

See `proposal.md` for motivation and background.

## Goals / Non-Goals

**Goals:**
- Connect the admin sidebar's "Parents" item to a production-ready, fully synchronized route (`admin.guardians.index`).
- Build `App\Http\Controllers\Admin\GuardianController` with full listing, eager loading, search, inspection, editing, and soft-delete/restore capabilities.
- Construct a high-density, utility-focused directory view (`resources/views/admin/guardians/index.blade.php`) and profile view (`show.blade.php`) adhering strictly to the **No-Slop UI** standard (1px crisp borders, authentic typography, no cheesy gradients or floating glassmorphism).
- Ensure zero N+1 database queries when displaying multi-child family relationships.
- Add administrative permissions (`guardians.view`, `guardians.manage`, `guardians.delete`) into `RolePermissionSeeder`.

**Non-Goals:**
- Self-service parent login portal authentication (guardians accessing student report cards online is a separate future capability; this change provides the administrative directory and contact management).
- External SMS/Email gateway broadcast engine (handled by specialized communications modules).

## Decisions

### 1. Controller & Route Architecture
- **Choice**: Standard administrative resource controller at `app/Http/Controllers/Admin/GuardianController.php`.
- **Routes**: Registered in `routes/admin.php` under prefix `admin/guardians` with route name prefix `admin.guardians.`.
- **Alternative considered**: Nesting guardians under `admin/students/guardians`.
  - *Why rejected*: A single guardian often has multiple children across different classes and academic sessions. Managing them as top-level entities under User Management accurately models the family entity.

### 2. Sidebar Integration & Route Active State
- **Choice**: Update `resources/views/layouts/admin-partial/sidebar.blade.php`:
  ```blade
  @php
      $isGuardiansActive = request()->routeIs('admin.guardians.*');
  @endphp
  <li>
      <a href="{{ route('admin.guardians.index') }}"
         class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isGuardiansActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
         ...
      </a>
  </li>
  ```
- **Rationale**: Retains the familiar "Parents" icon and position under *Core Management* while matching the styling and hover tooltips of `Teachers & Faculty` and `Staff Directory`.

### 3. Eager Loading & Query Performance
- **Choice**: Eager load `students` with nested `currentEnrollment.academicClass` and `currentEnrollment.section`:
  ```php
  $query = Guardian::with([
      'students' => function ($q) {
          $q->with(['currentEnrollment.academicClass', 'currentEnrollment.section']);
      }
  ]);
  ```
- **Rationale**: Avoids exponential N+1 query execution when rendering the guardian directory table with student badges and class/section affiliations.

### 4. No-Slop UI Design Implementation
- **Layout**: High-density data grid using `border border-slate-200`, subtle `bg-slate-50/75` header rows, crisp badges for relationship types (`Father`, `Mother`, `Guardian`), emergency indicators (`rose-50 text-rose-700 border-rose-200`), and quick-copy contact tools.
- **Color Palette**: Solid Slate neutrals with Violet functional accents (`bg-violet-600` primary buttons, `bg-violet-50` active tab/sidebar states). No neon glows, no pastel rainbow gradients, and no bloated cards.

## Risks / Trade-offs

- **[Multi-child display overflowing table cells]** → Mitigation: Render student chips in a flex-wrap container with a badge displaying student name, relationship, and class. If a guardian has more than 3 students, display a compact "+N more" badge linking to the guardian profile.
- **[Orphan Guardians without active students]** → Mitigation: Render a subtle slate badge "No Active Students" and allow filtering or linking students.
- **[Duplicate Phone Numbers]** → Mitigation: Search indexing covers phone and national ID; duplicate phone checks are surfaced during edit validation.
