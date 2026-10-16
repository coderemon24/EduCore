# Design

## Context

The system runs on Laravel 12 with an established administrative portal (`resources/views/layouts/admin.blade.php`), modular partials (`resources/views/layouts/admin-partial/`), and an active `admin` authentication guard backed by the `admins` table. The multi-tenant foundation includes a `schools` database schema, the `School` Eloquent model with a backed `SchoolStatus` enum (`active`, `inactive`, `suspended`, `pending`), and a `BelongsToSchool` query-scoping trait.

Currently, sidebar navigation items for "Schools & Campuses" and "Settings" contain JavaScript SweetAlert placeholders. This design outlines how we implement fully operational interfaces for both capabilities while strictly isolating existing login (`resources/views/admin/auth/login.blade.php`) and dashboard (`resources/views/admin/dashboard.blade.php`) implementations from any modifications.

See `proposal.md` for background and motivation.

## Goals / Non-Goals

**Goals:**
- Provide a high-information-density school directory and status management UI for Super Administrators at `/admin/schools`.
- Provide an institutional profile and configuration settings view for school administration at `/admin/settings/school`.
- Wire navigation in `sidebar.blade.php` to these operational routes.
- Enforce role-based access control protecting platform-level school management routes (`role === 'super_admin'`).
- Adhere strictly to the "No-Slop UI" design protocol (clean 1px slate borders, high-contrast typography, Plus Jakarta Sans, zero arbitrary neon/glow cliches).

**Non-Goals:**
- No visual or code changes to existing login or dashboard views.
- No multi-database architecture (tenancy remains single-database row-level tenant isolation).
- No external automated billing/payment gateway integration in this phase.

## Decisions

### 1. Controller Specialization: Platform Administration vs. Institution Settings
- **Decision**: Separate concerns into two dedicated controllers:
  - `App\Http\Controllers\Admin\SchoolController`: Handles Super Admin platform operations (`index`, `show`, `updateStatus`).
  - `App\Http\Controllers\Admin\SchoolSettingController`: Handles institution-level profile display and updates (`edit`, `update`).
- **Rationale**: Isolates platform SaaS governance from single-tenant institutional settings. Prevents authorization mixing and bloated controllers.
- **Alternatives Considered**: A single `SchoolController` for both platform-wide and self-school actions was rejected because it introduces conditional logic branches for different authorization levels in the same actions.

### 2. Authorization and Role Verification
- **Decision**: Introduce a dedicated controller authorization check or middleware verifying `auth('admin')->user()->role === 'super_admin'` for all `/admin/schools*` routes.
- **Rationale**: Prevents regular school administrators from accessing platform-wide school directories or mutating statuses of other institutions.
- **Alternatives Considered**: Laravel Gates vs. explicit middleware. Controller-level gate check or route middleware `ensure_super_admin` keeps routing declarative and self-documenting in `routes/admin.php`.

### 3. UI Architecture & View Hierarchy
- **Decision**:
  - `resources/views/admin/schools/index.blade.php`: Tabbed list view with search, status filters (`All`, `Active`, `Pending`, `Suspended`, `Inactive`), high-density table, and pagination.
  - `resources/views/admin/schools/show.blade.php`: Institutional overview with identity badge, contact details, regional configurations, and quick status changer.
  - `resources/views/admin/settings/school.blade.php`: Clean settings form organized into logical cards: General Information, Contact & Physical Address, Regional & Localization (Timezone, Currency), and Institutional Branding.
- **Rationale**: Follows existing Blade layout conventions (`@extends('layouts.admin')`, `@section('admin-content')`), utilizing Tailwind CSS v4 utility classes consistent with the existing dashboard palette.

### 4. Interactive Feedback & Status Updating
- **Decision**: Use SweetAlert2 for status modification confirmations (`active` <-> `suspended` <-> `inactive`) with seamless form submission or fetch PATCH handling.
- **Rationale**: SweetAlert2 is already bundled in the layout and referenced across administrative actions, ensuring cohesive user feedback.

## Risks / Trade-offs

- **[Risk]** Non-super-admin users accessing the global school directory → **Mitigation**: Route-level protection checking `$user->role === 'super_admin'` with 403 Forbidden rejection.
- **[Risk]** Inadvertently mutating a school's immutable slug → **Mitigation**: Keep `slug` and `code` read-only or strictly protected during regular profile updates in `UpdateSchoolSettingRequest`.
- **[Risk]** Regressions in existing dashboard or sidebar collapse states → **Mitigation**: Retain exact CSS classes and IDs (`#admin-sidebar`, `.nav-label`, `.nav-item-tooltip`) in `sidebar.blade.php`, simply replacing `javascript:void(0)` with route helpers.

## Migration Plan

- **Database**: No schema migrations required. The `schools` table and `admins` table already have all necessary columns and indices.
- **Rollback**: Any changes can be cleanly rolled back by reverting the added routes, controllers, and Blade templates without affecting existing database state.
