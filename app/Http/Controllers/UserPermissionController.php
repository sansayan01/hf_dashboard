<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RolePermission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    /**
     * Permission categories with human-readable labels and their keys.
     */
    private function getPermissionCategories(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'permissions' => [
                    'dashboard.view' => 'View Dashboard Page',
                    'dashboard.view_stats' => 'View Team Statistics',
                    'dashboard.view_earnings' => 'View Earnings Breakdown',
                    'dashboard.view_reports' => 'View Reports Section',
                    'dashboard.view_financial_overview' => 'View Financial Overview Widget',
                    'dashboard.view_hierarchy_tree' => 'View Hierarchy Tree',
                    'dashboard.view_as' => 'Use "View As" Feature',
                ],
            ],
            'survey' => [
                'label' => 'Survey',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'permissions' => [
                    'survey.view' => 'View Survey List',
                    'survey.create' => 'Create New Surveys',
                    'survey.edit' => 'Edit Surveys',
                    'survey.delete' => 'Delete Surveys',
                    'survey.bulk_delete' => 'Bulk Delete Surveys',
                    'survey.view_nia' => 'View NIA (Not In Action) List',
                ],
            ],
            'patients' => [
                'label' => 'Patients',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                'permissions' => [
                    'patients.view' => 'View Patient List',
                    'patients.create' => 'Register New Patients',
                    'patients.edit' => 'Edit Patient Records',
                    'patients.delete' => 'Delete Patient Records',
                    'patients.export' => 'Export Patient Data (CSV)',
                    'patients.view_profile' => 'View Patient Profiles',
                ],
            ],
            'appointments' => [
                'label' => 'Appointments',
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'permissions' => [
                    'appointments.view' => 'View Appointment List',
                    'appointments.create' => 'Schedule Appointments',
                    'appointments.edit' => 'Edit Appointments',
                    'appointments.delete' => 'Delete Appointments',
                    'appointments.complete' => 'Mark as Successful',
                    'appointments.report_missed' => 'Report as Not Attended',
                    'appointments.export' => 'Export Appointments (CSV)',
                ],
            ],
            'attendance' => [
                'label' => 'Attendance',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                'permissions' => [
                    'attendance.view' => 'View Attendance Calendar',
                    'attendance.mark' => 'Mark Attendance',
                    'attendance.view_report' => 'View Attendance Report',
                    'attendance.export' => 'Export Attendance Report (CSV)',
                ],
            ],
            'membership' => [
                'label' => 'Membership',
                'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                'permissions' => [
                    'membership.view' => 'View Membership List',
                    'membership.register' => 'Register Members',
                    'membership.cancel' => 'Cancel Memberships',
                ],
            ],
            'inventory' => [
                'label' => 'Inventory',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'permissions' => [
                    'inventory.view' => 'View Inventory Dashboard',
                    'inventory.add_stock' => 'Add New Stock',
                    'inventory.adjust' => 'Manual Stock Adjustment',
                    'inventory.dispense' => 'Dispense Medicines',
                    'inventory.transfer' => 'Transfer Stock',
                    'inventory.view_transactions' => 'View Transaction History',
                    'inventory.edit_transactions' => 'Edit/Delete Transactions',
                    'inventory.export' => 'Export Inventory Data (CSV)',
                    'inventory.manage_medicines' => 'Manage Medicine Catalog',
                    'inventory.manage_warehouses' => 'Manage Warehouses & Camps',
                    'inventory.manage_sponsors' => 'Manage Sponsors',
                    'inventory.billing' => 'Access Billing System',
                ],
            ],
            'pathology' => [
                'label' => 'Pathology',
                'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l.675.337a4 4 0 01-2.5.467l-3.21.321a2 2 0 00-1.554 1.554l-.321 3.21a2 2 0 001.554 1.554l3.21-.321a2 2 0 001.554-1.554l.321-3.21a2 2 0 00-1.554-1.554z',
                'permissions' => [
                    'pathology.view' => 'View Pathology Records',
                    'pathology.create' => 'Record New Pathology',
                ],
            ],
            'team' => [
                'label' => 'My Team',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'permissions' => [
                    'team.view' => 'View Team Member List',
                    'team.create_users' => 'Create/Register Members',
                    'team.edit_users' => 'Edit Member Profiles',
                    'team.approve_users' => 'Approve Pending Members',
                    'team.delete_users' => 'Delete Members (Soft)',
                    'team.view_profile' => 'View Member Profiles',
                    'team.view_password' => 'View/Reveal Passwords',
                    'team.generate_id_card' => 'Generate ID Cards',
                    'team.generate_offer_letter' => 'Generate Offer Letters',
                    'team.toggle_oic' => 'Toggle OIC Assignment',
                    'team.toggle_salary_mode' => 'Switch TAB/DAB Mode',
                    'team.bulk_actions' => 'Use Bulk Actions',
                ],
            ],
            'staffs' => [
                'label' => 'Staffs',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'permissions' => [
                    'staffs.view' => 'View Staff/Pharmacist List',
                    'staffs.create' => 'Create Staff Accounts',
                    'staffs.edit' => 'Edit Staff Profiles',
                    'staffs.delete' => 'Delete Staff Accounts',
                ],
            ],
            'bin' => [
                'label' => 'BIN Recovery',
                'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                'permissions' => [
                    'bin.view' => 'View Deleted Users',
                    'bin.restore' => 'Restore Deleted Users',
                    'bin.force_delete' => 'Permanently Delete Users',
                    'bin.patient_bin' => 'Access Patient BIN',
                    'bin.patient_restore' => 'Restore Deleted Patients',
                ],
            ],
            'finances' => [
                'label' => 'Finances',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'permissions' => [
                    'finances.view' => 'View Finances Hub',
                    'finances.view_income' => 'View Income Tracking',
                    'finances.create_income' => 'Create Income Entries',
                    'finances.edit_income' => 'Edit/Delete Income',
                    'finances.export_income' => 'Export Income Data (CSV)',
                    'finances.view_expense' => 'View Expense Tracking',
                    'finances.create_expense' => 'Create Expense Entries',
                    'finances.edit_expense' => 'Edit/Delete Expenses',
                    'finances.export_expense' => 'Export Expense Data (CSV)',
                    'finances.view_camp_records' => 'View Camp Records',
                    'finances.create_camp_record' => 'Create Camp Records',
                    'finances.edit_camp_record' => 'Edit Camp Records',
                    'finances.delete_camp_record' => 'Delete Camp Records',
                    'finances.export_camp_records' => 'Export Camp Records (CSV)',
                ],
            ],
            'bin' => [
                'label' => 'BIN Recovery',
                'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                'permissions' => [
                    'bin.view' => 'View BIN Recovery Hub',
                    'bin.restore' => 'Restore Record (Any)',
                    'bin.force_delete' => 'Permanently Delete Record (Any)',
                    'bin.patient_bin' => 'View Deleted Patients',
                    'bin.patient_restore' => 'Restore Deleted Patients',
                ],
            ],
            'admin' => [
                'label' => 'Admin Controls',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'permissions' => [
                    'admin.view' => 'Access Admin Control Panel',
                    'admin.manage_roles' => 'Manage Role Permissions',
                    'admin.manage_incentives' => 'Configure Incentive Rates',
                    'admin.manage_coupons' => 'Manage Coupon Codes',
                    'admin.system_settings' => 'Change System Settings',
                ],
            ],
        ];
    }

    /**
     * Show the permissions & controls page for a user.
     */
    public function show(User $user)
    {
        // Only users with permission can access this page
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_permissions')) {
            abort(403, 'You do not have permission to manage user permissions.');
        }

        // Founders (HFSA000001) are immune to override by anyone except potentially themselves if they choose
        // Actually, we should protect the primary admin from being edited by someone who was just granted access.
        if ($user->employee_id === 'HFSA000001' && $currentUser->employee_id !== 'HFSA000001') {
            abort(403, 'The primary administrator permissions cannot be modified.');
        }

        // Can't edit your own permissions (Super Admins always have full access)
        if ($currentUser->id === $user->id) {
            return redirect()->route('users.show', $user->id)
                ->with('error', 'Super Admins always have full access. You cannot modify your own permissions.');
        }

        $categories = $this->getPermissionCategories();
        $userPermissions = $user->getAllPermissions();
        $hasOverrides = !empty($user->permissions);
        $defaults = User::PERMISSION_DEFAULTS;

        return view('users.permissions', compact('user', 'categories', 'userPermissions', 'hasOverrides', 'defaults'));
    }

    /**
     * Update permissions for a user.
     */
    public function update(Request $request, User $user)
    {
        // Only users with permission can modify permissions
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_permissions')) {
            abort(403, 'You do not have permission to manage permissions.');
        }

        if ($user->employee_id === 'HFSA000001' && $currentUser->employee_id !== 'HFSA000001') {
            abort(403, 'The primary administrator permissions cannot be modified.');
        }

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'Cannot modify your own permissions.');
        }

        $permissions = $request->input('permissions', []);
        $overrides = [];

        // In HTML, unchecked checkboxes are not submitted.
        // So we must iterate through ALL known defaults.
        // If it's in $permissions, it's ON (true).
        // If it's not in $permissions, it's OFF (false).
        foreach (User::PERMISSION_DEFAULTS as $key => $default) {
            $submittedValue = isset($permissions[$key]) ? true : false;
            
            // Only store an override if the submitted value differs from the default
            if ($submittedValue !== $default) {
                $overrides[$key] = $submittedValue;
            }
        }

        // If all match defaults, store null (no overrides)
        $user->permissions = !empty($overrides) ? $overrides : null;
        $user->save();

        ActivityLog::logActivity(
            action: 'permissions_updated',
            description: "Permissions updated for {$user->profile?->full_name} ({$user->employee_id})",
            modelType: User::class,
            modelId: $user->id
        );

        return redirect()->route('users.permissions', $user->id)
            ->with('success', 'Permissions updated successfully.');
    }

    /**
     * Reset all permissions to defaults.
     */
    public function reset(User $user)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_permissions')) {
            abort(403, 'You do not have permission to manage permissions.');
        }

        if ($user->employee_id === 'HFSA000001' && $currentUser->employee_id !== 'HFSA000001') {
            abort(403, 'The primary administrator permissions cannot be modified.');
        }

        $user->resetPermissions();
        $user->save();

        ActivityLog::logActivity(
            action: 'permissions_reset',
            description: "Permissions reset to defaults for {$user->profile?->full_name} ({$user->employee_id})",
            modelType: User::class,
            modelId: $user->id
        );

        return redirect()->route('users.permissions', $user->id)
            ->with('success', 'Permissions reset to defaults successfully.');
    }

    // =========================================================================
    // BULK ROLE-LEVEL PERMISSIONS
    // =========================================================================

    /**
     * Designation map for the bulk editor.
     */
    private function getDesignationMap(): array
    {
        return [
            'hs' => ['label' => 'Head of State', 'short' => 'HS', 'gradient' => 'from-violet-600 via-purple-600 to-indigo-600', 'color' => 'violet'],
            'dm' => ['label' => 'District Manager', 'short' => 'DM', 'gradient' => 'from-blue-600 via-cyan-600 to-teal-600', 'color' => 'blue'],
            'bm' => ['label' => 'Block Manager', 'short' => 'BM', 'gradient' => 'from-emerald-600 via-green-600 to-lime-600', 'color' => 'emerald'],
            'rm' => ['label' => 'Relationship Manager', 'short' => 'RM', 'gradient' => 'from-amber-500 via-orange-500 to-yellow-500', 'color' => 'amber'],
            'ro' => ['label' => 'Relationship Officer', 'short' => 'RO', 'gradient' => 'from-rose-500 via-pink-500 to-fuchsia-500', 'color' => 'rose'],
            'office_in_charge' => ['label' => 'Office In-Charge', 'short' => 'OI', 'gradient' => 'from-sky-500 via-blue-500 to-indigo-500', 'color' => 'sky'],
            'staff' => ['label' => 'Pharmacist', 'short' => 'PH', 'gradient' => 'from-teal-500 via-emerald-500 to-green-500', 'color' => 'teal'],
            'camp_organizer' => ['label' => 'Camp Organizer', 'short' => 'CO', 'gradient' => 'from-orange-500 via-red-500 to-rose-500', 'color' => 'orange'],
        ];
    }

    /**
     * Designation selector page — shows all designations as cards.
     */
    public function roleIndex()
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_roles')) {
            abort(403, 'You do not have permission to manage role permissions.');
        }

        $designationMap = $this->getDesignationMap();

        // Get user counts and permission override counts for each designation
        $designations = [];
        foreach ($designationMap as $key => $meta) {
            $userCount = User::where('designation', $key)->where('status', 'approved')->count();
            $roleOverrides = RolePermission::getForRole($key);
            $mergedPerms = RolePermission::getMergedForRole($key);
            $enabledCount = collect($mergedPerms)->filter(fn($v) => $v)->count();
            $totalCount = count(User::PERMISSION_DEFAULTS);

            $designations[$key] = array_merge($meta, [
                'user_count' => $userCount,
                'has_overrides' => !empty($roleOverrides),
                'enabled_count' => $enabledCount,
                'total_count' => $totalCount,
            ]);
        }

        return view('admin.permissions.index', compact('designations'));
    }

    /**
     * Show the bulk permissions page for a specific designation.
     */
    public function roleShow(string $designation)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_roles')) {
            abort(403, 'You do not have permission to manage role permissions.');
        }

        $designationMap = $this->getDesignationMap();
        if (!isset($designationMap[$designation])) {
            abort(404, 'Invalid designation.');
        }

        $meta = $designationMap[$designation];
        $userCount = User::where('designation', $designation)->where('status', 'approved')->count();
        $categories = $this->getPermissionCategories();
        $rolePermissions = RolePermission::getMergedForRole($designation);
        $hasOverrides = !empty(RolePermission::getForRole($designation));
        $defaults = User::PERMISSION_DEFAULTS;

        return view('admin.permissions.role', compact(
            'designation', 'meta', 'userCount', 'categories',
            'rolePermissions', 'hasOverrides', 'defaults'
        ));
    }

    /**
     * Update bulk permissions for a designation.
     */
    public function roleUpdate(Request $request, string $designation)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_roles')) {
            abort(403, 'You do not have permission to manage role permissions.');
        }

        $designationMap = $this->getDesignationMap();
        if (!isset($designationMap[$designation])) {
            abort(404, 'Invalid designation.');
        }

        $submittedPermissions = $request->input('permissions', []);
        $finalPermissions = [];

        // In HTML, unchecked checkboxes are not submitted.
        // Iterate ALL known defaults to determine on/off state.
        foreach (User::PERMISSION_DEFAULTS as $key => $default) {
            $finalPermissions[$key] = isset($submittedPermissions[$key]) ? true : false;
        }

        RolePermission::bulkSetForRole($designation, $finalPermissions);

        $meta = $designationMap[$designation];
        $userCount = User::where('designation', $designation)->where('status', 'approved')->count();

        ActivityLog::logActivity(
            action: 'role_permissions_updated',
            description: "Bulk permissions updated for {$meta['label']} ({$userCount} users affected)",
            modelType: 'RolePermission',
            modelId: 0
        );

        return redirect()->route('admin.permissions.show', $designation)
            ->with('success', "Permissions updated for all {$meta['label']}s. {$userCount} users affected.");
    }

    /**
     * Reset bulk permissions for a designation back to PERMISSION_DEFAULTS.
     */
    public function roleReset(string $designation)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasPermission('admin.manage_roles')) {
            abort(403, 'You do not have permission to manage role permissions.');
        }

        $designationMap = $this->getDesignationMap();
        if (!isset($designationMap[$designation])) {
            abort(404, 'Invalid designation.');
        }

        RolePermission::resetForRole($designation);

        $meta = $designationMap[$designation];

        ActivityLog::logActivity(
            action: 'role_permissions_reset',
            description: "Bulk permissions reset to defaults for {$meta['label']}",
            modelType: 'RolePermission',
            modelId: 0
        );

        return redirect()->route('admin.permissions.show', $designation)
            ->with('success', "Permissions for {$meta['label']} reset to system defaults.");
    }
}
