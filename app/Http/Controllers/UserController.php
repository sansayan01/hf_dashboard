<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\BankDetail;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserApproved;

class UserController extends Controller
{
    /**
     * Display a listing of users (downline)
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Base query
        $query = User::with('profile');

        // Hierarchy scoping (only see subordinates)
        if (!$currentUser->isSuperAdmin()) {
            $downlineIds = $currentUser->getAllDownline()->pluck('id');
            $query->whereIn('id', $downlineIds);
        } else {
            // Super Admin sees everyone except themselves
            $query->where('id', '!=', $currentUser->id);
        }

        // Apply filters
        if ($request->filled('district')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('district', $request->district);
            });
        }

        if ($request->filled('block')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        if ($request->filled('gram_panchayat')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('gram_panchayat', $request->gram_panchayat);
            });
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Get allowed designations for filtering based on hierarchy
        // super_admin > dm > bm > rm > ro
        $hierarchyLevels = [
            'super_admin' => 0,
            'dm' => 1,
            'bm' => 2,
            'rm' => 3,
            'ro' => 4
        ];

        $designationLabels = [
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];

        $currentUserLevel = $hierarchyLevels[$currentUser->designation] ?? 99;

        $allowedFilters = [];
        foreach ($designationLabels as $key => $label) {
            if ($hierarchyLevels[$key] > $currentUserLevel) {
                $allowedFilters[$key] = $label;
            }
        }

        return view('users.index', compact('users', 'allowedFilters'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $currentUser = auth()->user();

        // Check if user can create users
        if (!$currentUser->canCreateUsers()) {
            abort(403, 'You do not have permission to create users.');
        }

        $allDesignations = [
            'super_admin' => 'Super Admin',
            'office_in_charge' => 'Office In-Charge',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];

        if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
            // Super Admin can create any role
            // Office In-Charge can create any role except SA and OI
            $allowedDesignation = null; // Let the view handle dropdown

            if ($currentUser->isOfficeInCharge()) {
                unset($allDesignations['super_admin']);
                unset($allDesignations['office_in_charge']);
            }

            // Get potential parents grouped by their designation
            $potentialParents = User::whereIn('designation', ['super_admin', 'dm', 'bm', 'rm'])
                ->with('profile')
                ->active()
                ->get()
                ->groupBy('designation');

            return view('users.create', compact('allowedDesignation', 'allDesignations', 'potentialParents'));
        }

        // Regular users only create their specific child role
        $allowedDesignation = $currentUser->getAllowedChildDesignation();

        return view('users.create', compact('allowedDesignation', 'allDesignations'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        \Log::info('UserController@store started', ['request' => $request->all()]);
        $currentUser = auth()->user();

        // Check if user can create users
        if (!$currentUser->canCreateUsers()) {
            abort(403, 'You do not have permission to create users.');
        }

        // Validation rules
        $rules = [
            'employee_id_option' => 'required|in:auto,manual',
            'employee_id' => 'required_if:employee_id_option,manual|unique:users,employee_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:10',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'aadhaar_number' => 'required|digits:12',
            'pan_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
        ];

        // Additional validation for Super Admin and Office In-Charge
        if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
            $allowed = 'dm,bm,rm,ro';
            if ($currentUser->isSuperAdmin()) {
                $allowed .= ',super_admin,office_in_charge';
            }
            $rules['designation'] = "required|in:$allowed";

            // Parent ID required unless creating SA, Office In-Charge, or DM
            // Note: Office In-Charge can't create SA/OI, so this mainly affects DM creation for them
            $rules['parent_id'] = 'required_unless:designation,super_admin,office_in_charge,dm|nullable|exists:users,id';
        }

        $validated = $request->validate(array_merge($rules, [
            'address' => 'required|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'block' => 'required|string',
            'gram_panchayat' => 'required|string',
            'pin_code' => 'required|digits:6',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'ifsc_code' => 'required|string|max:11',
            'profile_picture' => 'nullable|image|max:10000',
        ]));

        \Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();

        try {
            // Determine designation and parent
            if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
                $designation = $request->designation;
                if ($designation === 'super_admin' || $designation === 'office_in_charge') {
                    // Super Admin and Office In-Charge don't need a parent
                    $parentId = null;
                } elseif ($designation === 'dm') {
                    // Auto-assign current user as parent for DMs if they are creating one
                    $parentId = $currentUser->id;
                } else {
                    $parentId = $request->parent_id;
                }
            } else {
                $designation = $currentUser->getAllowedChildDesignation();
                $parentId = $currentUser->id;
            }

            // Generate or use employee ID
            $employeeId = $request->employee_id_option === 'auto'
                ? User::generateEmployeeId($designation)
                : $request->employee_id;

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user
            $newUser = User::create([
                'employee_id' => $employeeId,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $designation,
                'parent_id' => $parentId,
                'status' => 'pending',
            ]);

            // Create profile
            UserProfile::create([
                'user_id' => $newUser->id,
                'full_name' => $request->full_name,
                'profile_picture' => $profilePicturePath,
                'phone_number' => $request->phone_number,
                'blood_group' => $request->blood_group,
                'aadhaar_number' => $request->aadhaar_number,
                'pan_number' => $request->pan_number,
                'address' => $request->address,
                'state' => $request->state,
                'district' => $request->district,
                'block' => $request->block,
                'gram_panchayat' => $request->gram_panchayat,
                'pin_code' => $request->pin_code,
            ]);

            // Create bank details
            BankDetail::create([
                'user_id' => $newUser->id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
            ]);

            // Log activity
            ActivityLog::logActivity(
                'created',
                $newUser->id,
                $currentUser->id,
                "Created new {$designation} user: {$request->full_name}",
                'User',
                $newUser->id
            );

            DB::commit();

            return redirect()->route('users.show', $newUser->id)
                ->with('success', 'Registration Successful! Your account is now under review. Once the administration approves your profile, your account will be activated. You will then receive a formal notification containing your generated Employee ID, secure login password, and official Joining Letter.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Delete uploaded file if exists
            if (isset($profilePicturePath) && $profilePicturePath) {
                Storage::disk('public')->delete($profilePicturePath);
            }

            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $currentUser = auth()->user();
        $user = User::with(['profile', 'bankDetails', 'parent.profile', 'children.profile'])->findOrFail($id);

        // Check access
        if (!$currentUser->canAccess($user)) {
            abort(403, 'Unauthorized access');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $currentUser = auth()->user();
        $user = User::with(['profile', 'bankDetails'])->findOrFail($id);

        // Check edit permission
        if (!$currentUser->canEdit($user)) {
            abort(403, 'Permission denied: Only Super Admin can edit approved members.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        $user = User::with(['profile', 'bankDetails'])->findOrFail($id);

        // Check edit permission
        if (!$currentUser->canEdit($user)) {
            abort(403, 'Permission denied: Only Super Admin can edit approved members.');
        }

        // Validate request
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:10',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'aadhaar_number' => 'required|digits:12',
            'pan_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'address' => 'required|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'block' => 'required|string',
            'gram_panchayat' => 'required|string',
            'pin_code' => 'required|digits:6',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'ifsc_code' => 'required|string|max:11',
            'profile_picture' => 'nullable|image|max:10000',
            'password' => 'nullable|min:8|confirmed',
        ];

        if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
            $rules['employee_id'] = 'required|string|unique:users,employee_id,' . $user->id;
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            $oldValues = [
                'email' => $user->email,
                'profile' => $user->profile->toArray(),
                'bank_details' => $user->bankDetails->toArray(),
            ];

            // Update user core details
            $userData = [
                'email' => $request->email,
            ];

            if (($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) && $request->has('employee_id')) {
                $userData['employee_id'] = $request->employee_id;
            }

            if ($request->filled('password')) {
                // Only Super Admin/Office In-Charge or the user themselves can change the password
                if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge() || $currentUser->id === $user->id) {
                    $userData['password'] = Hash::make($request->password);
                }
            }

            $user->update($userData);

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old picture
                if ($user->profile->profile_picture) {
                    Storage::disk('public')->delete($user->profile->profile_picture);
                }

                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            } else {
                $profilePicturePath = $user->profile->profile_picture;
            }

            // Update profile
            $user->profile->update([
                'full_name' => $request->full_name,
                'profile_picture' => $profilePicturePath,
                'phone_number' => $request->phone_number,
                'blood_group' => $request->blood_group,
                'aadhaar_number' => $request->aadhaar_number,
                'pan_number' => $request->pan_number,
                'address' => $request->address,
                'state' => $request->state,
                'district' => $request->district,
                'block' => $request->block,
                'gram_panchayat' => $request->gram_panchayat,
                'pin_code' => $request->pin_code,
            ]);

            // Update bank details
            $user->bankDetails->update([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
            ]);

            // Log activity
            ActivityLog::logActivity(
                'updated',
                $user->id,
                $currentUser->id,
                "Updated user profile: {$request->full_name}",
                'User',
                $user->id,
                $oldValues,
                [
                    'email' => $user->email,
                    'profile' => $user->profile->fresh()->toArray(),
                    'bank_details' => $user->bankDetails->fresh()->toArray(),
                ]
            );

            DB::commit();

            return redirect()->route('users.show', $user->id)
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Approve a user
     */
    public function approve($id)
    {
        $currentUser = auth()->user();
        $user = User::findOrFail($id);

        // Check if user can approve
        if (!$currentUser->canApprove($user)) {
            abort(403, 'You do not have permission to approve this user.');
        }

        $user->update(['status' => 'active']);

        // Log activity
        ActivityLog::logActivity(
            'approved',
            $user->id,
            $currentUser->id,
            "Approved user: {$user->profile->full_name}",
            'User',
            $user->id
        );

        // Send Approval Email
        try {
            Mail::to($user->email)->send(new UserApproved($user, $currentUser));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
            // We don't stop the approval process if email fails, but we log it.
        }

        return back()->with('success', 'User approved successfully.');
    }

    /**
     * Approve multiple users at once
     */
    public function bulkApprove(Request $request)
    {
        $currentUser = auth()->user();

        // Only Super Admin can approve
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $userIds = $request->input('selected_users', []);
        if (empty($userIds)) {
            return back()->with('error', 'No members selected for approval.');
        }

        $users = User::whereIn('id', $userIds)->where('status', 'pending')->get();
        $count = 0;

        foreach ($users as $user) {
            $user->update(['status' => 'active']);

            ActivityLog::logActivity(
                'approved',
                $user->id,
                $currentUser->id,
                "Approved user (Bulk): {$user->profile->full_name}",
                'User',
                $user->id
            );

            // Send Approval Email
            try {
                Mail::to($user->email)->send(new UserApproved($user, $currentUser));
            } catch (\Exception $e) {
                \Log::error('Failed to send bulk approval email for user ' . $user->id . ': ' . $e->getMessage());
            }
            $count++;
        }

        return back()->with('success', "{$count} members approved successfully.");
    }

    /**
     * Soft delete a user (move to BIN)
     */
    public function destroy($id)
    {
        $currentUser = auth()->user();
        $user = User::findOrFail($id);

        // Check access - Only Super Admin and Office In-Charge can delete members
        if (!$currentUser->isSuperAdmin() && !$currentUser->isOfficeInCharge()) {
            abort(403, 'Permission denied: Only Admin users can delete members.');
        }

        // Prevent self-deletion
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Prevent Office In-Charge from deleting Super Admin
        if ($currentUser->isOfficeInCharge() && $user->isSuperAdmin()) {
            return back()->with('error', 'Permission denied: You cannot delete a Super Admin.');
        }

        $user->delete();

        // Log activity
        ActivityLog::logActivity(
            'deleted',
            $user->id,
            $currentUser->id,
            "Moved user to BIN: {$user->profile->full_name}",
            'User',
            $user->id
        );

        return redirect()->route('users.index')
            ->with('success', 'User moved to BIN. Can be restored within 30 days.');
    }

    /**
     * Show BIN (deleted users)
     */
    public function bin()
    {
        $currentUser = auth()->user();

        $query = User::onlyTrashed()->with('profile');

        if (!$currentUser->isSuperAdmin() && !$currentUser->isOfficeInCharge()) {
            // Standard users only see their own downline deleted
            $query->where(function ($q) use ($currentUser) {
                $q->where('parent_id', $currentUser->id)
                    ->orWhereIn('id', $currentUser->getAllDownline()->pluck('id'));
            });
        } elseif ($currentUser->isOfficeInCharge()) {
            // Office In-Charge sees all deleted EXCEPT Super Admins
            $query->where('designation', '!=', 'super_admin');
        }

        $deletedUsers = $query->paginate(20);

        return view('users.bin', compact('deletedUsers'));
    }

    /**
     * Restore a user from BIN
     */
    public function restore($id)
    {
        $currentUser = auth()->user();
        $user = User::onlyTrashed()->findOrFail($id);

        // Check if user can restore (Super Admin, Office In-Charge, or direct parent)
        if (!$currentUser->isSuperAdmin() && !$currentUser->isOfficeInCharge() && $user->parent_id !== $currentUser->id) {
            abort(403, 'You do not have permission to restore this user.');
        }

        // Prevent Office In-Charge from restoring Super Admin
        if ($currentUser->isOfficeInCharge() && $user->isSuperAdmin()) {
            abort(403, 'Permission denied: Office In-Charge cannot restore Super Admin.');
        }

        $user->restore();

        // Log activity
        ActivityLog::logActivity(
            'restored',
            $user->id,
            $currentUser->id,
            "Restored user from BIN: {$user->profile->full_name}",
            'User',
            $user->id
        );

        return back()->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a user (Super Admin only)
     */
    public function forceDelete($id)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only Super Admin can permanently delete users.');
        }

        $user = User::onlyTrashed()->findOrFail($id);

        // Delete profile picture
        if ($user->profile && $user->profile->profile_picture) {
            Storage::disk('public')->delete($user->profile->profile_picture);
        }

        $userName = $user->profile->full_name;
        $user->forceDelete();

        // Log activity
        ActivityLog::logActivity(
            'permanently_deleted',
            null,
            $currentUser->id,
            "Permanently deleted user: {$userName}",
            'User',
            $id
        );

        return back()->with('success', 'User permanently deleted.');
    }
}
