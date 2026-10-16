# Proposal

## Why

The application currently has a foundational multi-tenant architecture with the `schools` database schema, `School` Eloquent model, and `BelongsToSchool` isolation traits. However:
1. Super Administrators have no interface to view, inspect, search, filter, or manage academic institutions and their operational statuses (`active`, `inactive`, `suspended`, `pending`).
2. School Administrators have no institutional settings page to view and update their school's profile (name, code, contact email, phone, address, currency, timezone, and branding).
3. The sidebar navigation contains dead placeholder items for "Schools & Campuses" and "Settings", triggering temporary SweetAlert informational dialogs rather than functioning routes.

Establishing both an institutional profile/settings module and a dedicated Super Admin tenant management interface fulfills the multi-tenant SaaS governance layer while maintaining complete architectural integrity and preserving the existing dashboard and login UI without alteration.

## What Changes

1. **Super Admin School Management Interface**:
   - Provide a high-information-density School Directory at `/admin/schools` displaying all registered institutions with pagination, status badges, and keyword search.
   - Filter schools by lifecycle status (`All`, `Active`, `Pending`, `Suspended`, `Inactive`).
   - Provide a comprehensive School Details view at `/admin/schools/{school}` showing institution metadata, contact details, location, and tenant configuration.
   - Provide status management at `PATCH /admin/schools/{school}/status` allowing Super Administrators to safely transition school operational status (`active`, `inactive`, `suspended`) with SweetAlert2 confirmation.
   - Restrict access to Super Administrators (`role === 'super_admin'`).

2. **Institution Settings & Profile Interface**:
   - Provide a dedicated School Profile & Settings page at `GET /admin/settings/school` where administrators can inspect and update institutional parameters.
   - Handle form updates via `PUT /admin/settings/school` with comprehensive validation (unique email, phone formatting, timezone, currency, address, and logo URL/upload).
   - Wire the existing sidebar "Settings" link directly to this route.

3. **Sidebar & Route Integration**:
   - Connect the sidebar "Schools & Campuses" item to the school management route for Super Admins.
   - Connect the sidebar "Settings" item to the school profile page.
   - **Zero regressions**: Ensure existing `/admin/login` and `/admin/dashboard` layouts, KPIs, charts, and styling remain 100% unaltered.

## Capabilities

### New Capabilities
- `school-settings`: Institutional profile and operational settings management interface for academic institutions, allowing administrators to configure institutional branding, contact metadata, regional timezones, and currency.

### Modified Capabilities
- `multi-tenant-schools`: Expands the foundational multi-tenant architecture to include administrative tenant lifecycle management, Super Admin institutional directory, detailed tenant profile auditing, and safe operational status modification.

## Impact

- **Routes**: Adds new routes inside `routes/admin.php` under the authenticated `admin.auth` group.
- **Controllers**: Adds `App\Http\Controllers\Admin\SchoolController` and `App\Http\Controllers\Admin\SchoolSettingController`.
- **Form Requests**: Adds validation requests `App\Http\Requests\Admin\UpdateSchoolStatusRequest` and `App\Http\Requests\Admin\UpdateSchoolSettingRequest`.
- **Views**: Adds Blade views in `resources/views/admin/schools/index.blade.php`, `resources/views/admin/schools/show.blade.php`, and `resources/views/admin/settings/school.blade.php`.
- **Navigation**: Updates `resources/views/layouts/admin-partial/sidebar.blade.php` to link active routes.
- **Preservation**: No breaking modifications to `database/migrations/`, `AdminController.php`, `DashboardController.php`, `dashboard.blade.php`, or `login.blade.php`.
