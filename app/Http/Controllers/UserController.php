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
use App\Services\AIService;

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
            // Permission check: Can view downline
            if (!$currentUser->canViewDownline()) {
                abort(403, 'Unauthorized access: You do not have permission to view the team members list.');
            }
            $downlineIds = $currentUser->getAllDownline()->pluck('id');
            $query->whereIn('id', $downlineIds);
        } else {
            // Super Admin sees everyone except themselves
            $query->where('id', '!=', $currentUser->id);
        }

        // Exclude Staff and Office In-Charge from My Team view (managed in Staff section)
        $query->whereNotIn('designation', ['staff', 'office_in_charge'])
            ->where('is_office_in_charge', false);

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

        $limit = $request->has('view_all') ? 5000 : 20;
        $users = $query->latest()->paginate($limit)->withQueryString();

        // Calculate Stats for the top bar (matches Dashboard)
        $allDownlineIds = $currentUser->getAllDownlineIds();
        $stats = [
            'total_downline' => count($allDownlineIds),
            'pending_approvals' => $currentUser->getPendingApprovalsCount(),
            'direct_children' => $currentUser->getDashboardChildrenCount(),
            'active_downline' => count($allDownlineIds) > 0 ? User::whereIn('id', $allDownlineIds)->where('status', 'active')->count() : 0,
        ];

        // Get allowed designations for filtering based on hierarchy
        // super_admin > dm > bm > rm > ro
        $hierarchyLevels = [
            'super_admin' => 0,
            'office_in_charge' => 1,
            'hs' => 2,
            'dm' => 3,
            'bm' => 4,
            'rm' => 5,
            'ro' => 6
        ];

        $designationLabels = [
            'office_in_charge' => 'Office In-Charge',
            'hs' => 'Head of State',
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

        return view('users.index', compact('users', 'allowedFilters', 'stats'));
    }

    public function staffIndex(Request $request)
    {
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = User::with('profile')->where(function ($q) {
            $q->whereIn('designation', ['staff', 'office_in_charge'])
                ->orWhere('is_office_in_charge', true);
        });

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

        return view('users.staff_index', compact('users'));
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
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
            'staff' => 'Pharmacist',
        ];

        if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
            // Super Admin can create any role
            // Office In-Charge can create any role except SA and OI
            $allowedDesignation = null; // Let the view handle dropdown

            // Context check: Are we adding via Staffs section or standard User section?
            // We can infer this from previous url or a request parameter, but simpler is to pass 'type' in route or request.
            // However, since we use same route, let's filter based on logic request.
            // User requested: "at the my team section keep only HS, DM, BM, RM and RO registration."
            // "and at the staffs section keep the super admin, office in charge and the staffs registration"

            // We will add a 'type' parameter to the Create button in views.
            $type = request('type', 'team'); // Default to team

            // Only Super Admin can access Staff creation section
            if ($type === 'staff' && $currentUser->isSuperAdmin()) {
                $allDesignations = [
                    'super_admin' => 'Super Admin',
                    'office_in_charge' => 'Office In-Charge',
                    'staff' => 'Pharmacist',
                ];
            } else {
                // My Team (Available to SA and OIC)
                $allDesignations = [
                    'hs' => 'Head of State',
                    'dm' => 'District Manager',
                    'bm' => 'Block Manager',
                    'rm' => 'Relationship Manager',
                    'ro' => 'Relationship Officer',
                ];
            }

            if ($currentUser->isOfficeInCharge()) {
                unset($allDesignations['super_admin']);
                unset($allDesignations['office_in_charge']);
            }

            // Get potential parents grouped by their designation
            $potentialParents = User::whereIn('designation', ['super_admin', 'hs', 'dm', 'bm', 'rm'])
                ->with('profile')
                ->active()
                ->get()
                ->groupBy('designation');

            // Get potential uplines for Office In-Charge (only for Super Admin)
            $potentialUplines = [];
            if ($currentUser->isSuperAdmin()) {
                $potentialUplines = User::whereIn('designation', ['super_admin', 'hs', 'dm', 'bm', 'rm'])
                    ->with('profile')
                    ->active()
                    ->get()
                    ->groupBy('designation');
            }



            // Get Camps for Pharmacist assignment
            $camps = \App\Models\InventoryWarehouse::where('type', 'camp')->where('is_active', true)->get();

            return view('users.create', compact('allowedDesignation', 'allDesignations', 'potentialParents', 'potentialUplines', 'camps'));
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
            $allowed = 'hs,dm,bm,rm,ro';
            if ($currentUser->isSuperAdmin()) {
                $allowed .= ',super_admin,office_in_charge,staff';
            }
            $rules['designation'] = "required|in:$allowed";

            // Parent ID required unless creating SA, Office In-Charge, or HS (if top level) or Staff
            $rules['parent_id'] = 'required_unless:designation,super_admin,office_in_charge,hs,staff|nullable|exists:users,id';

            // Office In-Charge specific validation (only Super Admin can create)
            if ($currentUser->isSuperAdmin()) {
                $rules['upline_designation'] = 'nullable|required_if:designation,office_in_charge|in:super_admin,hs,dm,bm,rm';
                $rules['upline_id'] = 'nullable|required_if:designation,office_in_charge|exists:users,id';
            }
        }

        // Determine effective designation for validation
        $designation = $request->designation;
        if (!$designation && !($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge())) {
            $designation = $currentUser->getAllowedChildDesignation();
        }

        // Only require payment screenshot for paid roles (DM, BM, RM, RO)
        $isPaidRole = in_array($designation, ['dm', 'bm', 'rm', 'ro']);
        if ($isPaidRole) {
            $rules['payment_screenshot'] = 'required_without:coupon_code|nullable|image|max:10000';
        } else {
            $rules['payment_screenshot'] = 'nullable|image|max:10000';
        }
        $rules['coupon_code'] = 'nullable|string|max:50';

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
            'camp_id' => 'nullable|exists:inventory_warehouses,id',
        ]));

        if ($request->designation === 'staff' && empty($request->camp_id)) {
            return back()->withInput()->with('error', 'Please select a Camp Location for the Pharmacist.');
        }

        \Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();

        try {
            // Determine designation and parent
            if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
                $designation = $request->designation;
                if ($designation === 'super_admin') {
                    // Super Admin doesn't need a parent
                    $parentId = null;
                } elseif ($designation === 'office_in_charge') {
                    // Office In-Charge reports to Upline
                    $parentId = $request->upline_id;
                } elseif ($designation === 'hs') {
                    // HS reports to current user (SA/OI) or is top level. 
                    // Per req: "HS will inform to super admin".
                    $parentId = $currentUser->id;
                } elseif ($designation === 'dm') {
                    // DM now reports to HS. Parent ID must be supplied (HS ID)
                    $parentId = $request->parent_id;

                    // Enforce that the selected parent is indeed an HS
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'hs') {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'District Managers must be assigned to a Head of State (HS). Please select a valid HS as the parent.');
                    }
                } elseif ($designation === 'bm') {
                    $parentId = $request->parent_id;
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'dm') {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Block Managers must be assigned to a District Manager (DM).');
                    }
                } elseif ($designation === 'rm') {
                    $parentId = $request->parent_id;
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'bm') {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Relationship Managers must be assigned to a Block Manager (BM).');
                    }
                } elseif ($designation === 'ro') {
                    $parentId = $request->parent_id;
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'rm') {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Relationship Officers must be assigned to a Relationship Manager (RM).');
                    }
                } elseif ($designation === 'hs') {
                    // Safety check if not caught by previous block
                    $parentId = $currentUser->id;
                } elseif ($designation === 'staff') {
                    $parentId = $currentUser->id;
                } else {
                    // Fallback for any other future roles, though strictness implies we handled all.
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
            $userData = [
                'employee_id' => $employeeId,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $designation,
                'parent_id' => $parentId,
                'status' => 'pending',
                'is_office_in_charge' => ($designation === 'office_in_charge'),
                'joining_donation' => User::getJoiningDonationAmount($designation),
                'camp_id' => $request->camp_id,
            ];

            // Handle coupon code or payment screenshot
            $couponUsed = false;

            if ($request->filled('coupon_code')) {
                // Validate coupon code
                $coupon = \App\Models\CouponCode::where('code', $request->coupon_code)
                    ->where('is_used', false)
                    ->first();

                if (!$coupon) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Invalid coupon code. Please check and try again.');
                }

                if (!$coupon->isValid($designation)) {
                    DB::rollBack();
                    return back()->withInput()->with('error', $coupon->getValidationError($designation));
                }

                // Coupon is valid - bypass payment screenshot
                $userData['payment_reference'] = 'COUPON-' . $coupon->code;
                $userData['payment_status'] = 'completed';
                $couponUsed = true;

            } elseif ($request->hasFile('payment_screenshot')) {
                // Handle payment screenshot
                $screenshotPath = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
                $userData['payment_screenshot'] = $screenshotPath;

                // AI Verification
                $aiService = app(AIService::class);
                $expectedAmount = User::getJoiningDonationAmount($designation);
                $verification = $aiService->verifyPaymentScreenshot(storage_path('app/public/' . $screenshotPath), $expectedAmount);

                if (!$verification['success']) {
                    // Delete the screenshot if verification fails to avoid clutter
                    Storage::disk('public')->delete($screenshotPath);
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Payment Verification Failed: ' . $verification['message']);
                }

                $userData['payment_reference'] = $verification['transaction_id'];
                $userData['payment_status'] = 'completed'; // Mark as completed if AI verified it as a success screen
            }

            // If creating Office In-Charge, add upline information
            if ($designation === 'office_in_charge' && $currentUser->isSuperAdmin()) {
                $userData['upline_id'] = $request->upline_id;
                $userData['upline_designation'] = $request->upline_designation;

                // Validate that upline_id matches the upline_designation
                $uplineUser = User::find($request->upline_id);
                if (!$uplineUser || $uplineUser->designation !== $request->upline_designation) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'The selected upline does not match the upline designation.');
                }
            }

            $newUser = User::create($userData);

            // Mark coupon as used if applied
            if (isset($couponUsed) && $couponUsed && isset($coupon)) {
                $coupon->markAsUsed($newUser->id);
            }

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
        $user = User::with(['profile', 'bankDetails', 'parent.profile', 'children.profile', 'upline.profile'])->findOrFail($id);

        // Check access
        if (!$currentUser->canAccess($user)) {
            abort(403, 'Unauthorized access');
        }

        $attendances = collect();
        if ($user->isRO() && ($currentUser->isSuperAdmin() || $currentUser->isRM())) {
            $month = request('month', date('Y-m'));
            try {
                $targetDate = \Carbon\Carbon::createFromFormat('Y-m', $month);
            } catch (\Exception $e) {
                $targetDate = now();
            }

            $attendances = $user->attendances()
                ->where('date', '>=', $targetDate->copy()->startOfMonth())
                ->where('date', '<=', $targetDate->copy()->endOfMonth())
                ->get();
        }

        return view('users.show', compact('user', 'currentUser', 'attendances'));
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

        // Prepare data for role/hierarchy management (for Admins)
        $allDesignations = [
            'super_admin' => 'Super Admin',
            'office_in_charge' => 'Office In-Charge',
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
            'staff' => 'Pharmacist',
        ];

        $potentialParents = [];
        $potentialUplines = [];
        if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) {
            $potentialParents = User::whereIn('designation', ['super_admin', 'hs', 'dm', 'bm', 'rm'])
                ->with('profile')
                ->active()
                ->get()
                ->groupBy('designation');

            if ($currentUser->isSuperAdmin()) {
                $potentialUplines = User::whereIn('designation', ['super_admin', 'hs', 'dm', 'bm', 'rm'])
                    ->with('profile')
                    ->active()
                    ->get()
                    ->groupBy('designation');
            }
        }

        return view('users.edit', compact('user', 'allDesignations', 'potentialParents', 'potentialUplines'));
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
            $rules['designation'] = 'sometimes|required|string';
            $rules['parent_id'] = 'nullable|exists:users,id';

            // Office In-Charge specific validation (only Super Admin)
            if ($currentUser->isSuperAdmin()) {
                $rules['upline_designation'] = 'nullable|required_if:designation,office_in_charge|in:super_admin,hs,dm,bm,rm';
                $rules['upline_id'] = 'nullable|required_if:designation,office_in_charge|exists:users,id';
            }

            $rules['can_create_users'] = 'nullable|boolean';
            $rules['can_edit_user_details'] = 'nullable|boolean';
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

            // Handle Hierarchy/Role Change (Admins Only)
            if (($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) && $request->has('designation') && $request->designation !== $user->designation) {
                $designation = $request->designation;
                $parentId = $request->parent_id;

                // Validate Logic
                if ($designation === 'super_admin') {
                    $parentId = null;
                } elseif ($designation === 'staff') {
                    $parentId = $currentUser->id;
                } elseif ($designation === 'office_in_charge') {
                    // Office In-Charge reports to Upline
                    $parentId = $request->upline_id ?? $user->parent_id; // Use new upline or keep current if not provided? 
                    // IMPORTANT: validation in rule ensures upline_id is present if designation is OIC in some cases, 
                    // but here we rely on request upline_id if we are setting designation/upline. 
                    // If just updating designation to OIC, upline_id should be in request.
                    if ($request->has('upline_id')) {
                        $parentId = $request->upline_id;
                    }
                } elseif ($designation === 'hs') {
                    $parentId = $currentUser->id; // Assign to admin updating it safely? Or null? Let's use current admin or null.
                    // If changing to HS, they become top level.
                    // If request->parent_id is null, it's fine.
                } elseif ($designation === 'dm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'hs') {
                        throw new \Exception('District Managers must be assigned to a Head of State (HS).');
                    }
                } elseif ($designation === 'bm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'dm') {
                        throw new \Exception('Block Managers must be assigned to a District Manager (DM).');
                    }
                } elseif ($designation === 'rm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'bm') {
                        throw new \Exception('Relationship Managers must be assigned to a Block Manager (BM).');
                    }
                } elseif ($designation === 'ro') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'rm') {
                        throw new \Exception('Relationship Officers must be assigned to a Relationship Manager (RM).');
                    }
                }

                $userData['designation'] = $designation;
                $userData['parent_id'] = $parentId;
            } elseif (($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge()) && $request->has('parent_id') && $request->parent_id != $user->parent_id) {
                // Just changing parent not designation
                $designation = $user->designation; // Current
                $parentId = $request->parent_id;

                // Same validation logic...
                if ($designation === 'dm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'hs')
                        throw new \Exception('DM must match HS parent.');
                } elseif ($designation === 'bm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'dm')
                        throw new \Exception('BM must match DM parent.');
                } elseif ($designation === 'rm') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'bm')
                        throw new \Exception('RM must match BM parent.');
                } elseif ($designation === 'ro') {
                    $parentUser = User::find($parentId);
                    if (!$parentUser || $parentUser->designation !== 'rm')
                        throw new \Exception('RO must match RM parent.');
                }
                $userData['parent_id'] = $parentId;
            }

            // Update Office In-Charge Upline Info (Super Admin Only)
            if ($currentUser->isSuperAdmin()) {
                $targetDesignation = $request->input('designation', $user->designation);
                if ($targetDesignation === 'office_in_charge') {
                    $userData['is_office_in_charge'] = true;
                    if ($request->has('upline_id')) {
                        $userData['upline_id'] = $request->upline_id;

                        // Validation
                        $uplineUser = User::find($request->upline_id);
                        if (!$uplineUser) {
                            throw new \Exception('Selected upline user not found.');
                        }

                        // Check designaiton match if provided
                        if ($request->has('upline_designation')) {
                            $userData['upline_designation'] = $request->upline_designation;
                            if ($uplineUser->designation !== $request->upline_designation) {
                                throw new \Exception('The selected upline does not match the upline designation.');
                            }
                        }
                    }
                }
            }

            if ($request->filled('password')) {
                // Only Super Admin/Office In-Charge or the user themselves can change the password
                if ($currentUser->isSuperAdmin() || $currentUser->isOfficeInCharge() || $currentUser->id === $user->id) {
                    $userData['password'] = Hash::make($request->password);
                }
            }

            // Update Per-User Permissions (Super Admin Only)
            if ($currentUser->isSuperAdmin()) {
                $userData['can_create_users'] = $request->has('can_create_users');
                $userData['can_edit_user_details'] = $request->has('can_edit_user_details');
            }

            $user->update($userData);

            // Handle profile picture upload
            $newProfilePicturePath = null;
            $oldProfilePicturePath = $user->profile->profile_picture;

            if ($request->hasFile('profile_picture')) {
                // Upload NEW picture first, don't delete old one yet
                $newProfilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $profilePicturePath = $newProfilePicturePath;
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

            // SUCCESS: Now effectively safe to delete the OLD image if it was replaced
            if ($newProfilePicturePath && $oldProfilePicturePath && $newProfilePicturePath !== $oldProfilePicturePath) {
                Storage::disk('public')->delete($oldProfilePicturePath);
            }

            return redirect()->route('users.show', $user->id)
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // FAILURE: Delete the NEW image that was just uploaded since we are rolling back
            if (isset($newProfilePicturePath) && $newProfilePicturePath) {
                Storage::disk('public')->delete($newProfilePicturePath);
            }

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

        // Check if user has downline access (Strict Hierarchical Check)
        if (!$currentUser->canAccess($user)) {
            abort(403, 'Unauthorized: You can only approve members in your downline.');
        }

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
     * Approve multiple users at once (Optimized for bulk operations)
     */
    public function bulkApprove(Request $request)
    {
        $currentUser = auth()->user();

        // Permission check
        if (!$currentUser->isSuperAdmin() && !\App\Models\RolePermission::check($currentUser->designation, 'can_approve_users')) {
            abort(403, 'Unauthorized access.');
        }

        $userIds = $request->input('selected_users', []);
        if (empty($userIds)) {
            return back()->with('error', 'No members selected for approval.');
        }

        // Fetch all pending users with their profiles (eager loading for performance)
        $users = User::with('profile')
            ->whereIn('id', $userIds)
            ->where('status', 'pending')
            ->get();

        // Filter users based on access permissions
        $accessibleUsers = $users->filter(function ($user) use ($currentUser) {
            return $currentUser->canAccess($user);
        });

        if ($accessibleUsers->isEmpty()) {
            return back()->with('error', 'No accessible members found for approval.');
        }

        $approvedCount = 0;
        $activityLogs = [];
        $now = now();

        // Use database transaction for data consistency
        \DB::beginTransaction();
        try {
            // Bulk update all users to active status (single query instead of N queries)
            User::whereIn('id', $accessibleUsers->pluck('id'))
                ->update([
                    'status' => 'active',
                    'updated_at' => $now
                ]);

            // Prepare bulk activity logs (insert all at once)
            foreach ($accessibleUsers as $user) {
                $activityLogs[] = [
                    'action' => 'approved',
                    'user_id' => $user->id,
                    'performed_by' => $currentUser->id,
                    'description' => "Approved user (Bulk): {$user->profile->full_name}",
                    'model_type' => 'User',
                    'model_id' => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Queue approval emails (non-blocking, processed in background)
                try {
                    \Mail::to($user->email)->queue(new \App\Mail\UserApproved($user, $currentUser));
                } catch (\Exception $e) {
                    \Log::error('Failed to queue bulk approval email for user ' . $user->id . ': ' . $e->getMessage());
                }

                $approvedCount++;
            }

            // Bulk insert all activity logs (single query)
            if (!empty($activityLogs)) {
                \App\Models\ActivityLog::insert($activityLogs);
            }

            \DB::commit();

            return back()->with('success', "{$approvedCount} members approved successfully and notifications queued.");

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Bulk approval failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during bulk approval. Please try again.');
        }
    }
    /**
     * Soft delete a user (move to BIN)
     */
    public function destroy($id)
    {
        $currentUser = auth()->user();
        $user = User::findOrFail($id);

        // Check access
        if (!$currentUser->isSuperAdmin() && !\App\Models\RolePermission::check($currentUser->designation, 'can_delete_users')) {
            abort(403, 'Permission denied: You do not have permission to delete members.');
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

    /**
     * Generate ID Card View
     */
    public function idCard(User $user, Request $request)
    {
        // Only Super Admin can generate ID cards for now
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only Super Admin can generate ID cards.');
        }

        $format = $request->get('format', 'png'); // Default to PNG, can be 'pdf' or 'svg'
        return view('users.id_card', compact('user', 'format'));
    }



    /**
     * Print all ID cards in A4 grid
     */
    public function printAllIdCards(Request $request)
    {
        // Only Super Admin can print IDs
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only Super Admin can print ID cards.');
        }

        // Get active users with profile pictures, optionally filtered by selection
        $query = User::with('profile')
            ->where('status', 'active');

        if ($request->has('selected_users')) {
            $query->whereIn('id', $request->selected_users);
        }

        $users = $query->get();

        $format = $request->get('format', 'png'); // Default to PNG, can be 'pdf' or 'svg'
        return view('users.printable_id_cards', compact('users', 'format'));
    }
}
