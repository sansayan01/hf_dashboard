<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Models\Conversation;

/**
 * @property int $id
 * @property string $employee_id
 * @property string $email
 * @property string $password
 * @property string $designation
 * @property string|null $post
 * @property int|null $parent_id
 * @property string $status
 * @property bool $is_office_in_charge
 * @property int|null $office_in_charge_creator_id
 * @property string|null $office_in_charge_type
 * @property \Illuminate\Support\Carbon|null $office_in_charge_end_date
 * @property int|null $upline_id
 * @property string|null $upline_designation
 * @property bool $can_create_users
 * @property bool $can_edit_user_details
 * @property float $joining_donation
 * @property string $payment_status
 * @property string|null $payment_reference
 * @property int|null $camp_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \App\Models\BankDetail|null $bankDetails
 * @property-read \App\Models\User|null $parent
 * @property-read \App\Models\User|null $upline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $children
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($user) {
            // When soft-deleted, change employee_id to free up the original ID for gap-filling
            // We use a prefix that won't conflict with regular IDs
            $user->employee_id = 'TRASH_' . $user->employee_id . '_' . now()->timestamp;
            $user->save();
        });

        static::restoring(function ($user) {
            // When restored, assign the latest available serial number as requested
            $user->employee_id = self::generateEmployeeId($user->designation, true);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'email',
        'password',
        'designation',
        'salary_mode',
        'post',
        'parent_id',
        'status',
        'is_office_in_charge',
        'office_in_charge_creator_id',
        'office_in_charge_type',
        'office_in_charge_end_date',
        'upline_id',
        'upline_designation',
        'can_create_users',
        'can_edit_user_details',
        'joining_donation',
        'payment_status',
        'payment_reference',
        'camp_id',
        'password_plain',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_plain',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'office_in_charge_end_date' => 'date',
            'is_office_in_charge' => 'boolean',
            'can_create_users' => 'boolean',
            'can_edit_user_details' => 'boolean',
            'joining_donation' => 'decimal:2',
        ];
    }

    // Add camp_id to fillable in the model property definition, but since it's guarded usually, 
    // I should check $fillable property. 
    // Assuming $fillable is defined at top, I will use a separate tool call to check/update it 
    // or just add the relationship method here first.

    public function camp()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'camp_id');
    }

    /**
     * Relationships
     */

    // Parent (Upline)
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Children (Downline)
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // All descendants (recursive downline)
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // Profile
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Bank Details
    public function bankDetails()
    {
        return $this->hasOne(BankDetail::class);
    }

    // Activity Logs
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Office in charge creator
    public function officeInChargeCreator()
    {
        return $this->belongsTo(User::class, 'office_in_charge_creator_id');
    }

    // Upline (the user this Office In-Charge represents)
    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_id');
    }

    // Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Incentive Configs
    public function incentiveConfigs()
    {
        return $this->hasMany(IncentiveConfig::class);
    }

    // Completed appointments created by this user (for DAB salary mode)
    public function completedAppointments()
    {
        return $this->hasMany(Appointment::class, 'created_by')->where('status', 'successful');
    }

    /**
     * Get DAB earnings for a given month (defaults to current month).
     * Returns ['count' => int, 'earnings' => float]
     */
    public function getMonthlyDabEarnings($month = null)
    {
        $start = $month ? Carbon::parse($month)->startOfMonth() : now()->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $count = Appointment::where('created_by', $this->id)
            ->where('status', 'successful')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        // Get configured DA amount (fallback to ₹20 if not configured)
        $config = $this->getCurrentIncentive($start);
        $daAmount = ($config && $config->da_amount > 0) ? $config->da_amount : 20;

        return [
            'count' => $count,
            'earnings' => $count * $daAmount,
        ];
    }

    /**
     * Get current incentive and TA for the user for a specific date
     * @param string|null $date
     * @return \App\Models\IncentiveConfig|null
     */
    public function getCurrentIncentive($date = null)
    {
        $date = $date ? Carbon::parse($date)->toDateString() : now()->toDateString();

        // 1. Check user-specific override
        $specific = $this->incentiveConfigs()
            ->where('effective_from', '<=', $date)
            ->orderBy('effective_from', 'desc')
            ->first();

        if ($specific) {
            return $specific;
        }

        // 2. Check designation-based global default
        $designationDefault = IncentiveConfig::whereNull('user_id')
            ->where('designation', $this->designation)
            ->where('effective_from', '<=', $date)
            ->orderBy('effective_from', 'desc')
            ->first();

        if ($designationDefault) {
            return $designationDefault;
        }

        // 3. Fallback to general global default (where user_id AND designation are null)
        $fallback = IncentiveConfig::whereNull('user_id')
            ->whereNull('designation')
            ->where('effective_from', '<=', $date)
            ->orderBy('effective_from', 'desc')
            ->first();

        if ($fallback) {
            return $fallback;
        }

        // 4. Absolute Fallback: Get the earliest available config for this designation 
        // regardless of date (to handle past dates with only 'future' configs)
        return IncentiveConfig::whereNull('user_id')
            ->where('designation', $this->designation)
            ->orderBy('effective_from', 'asc')
            ->first();
    }

    // Today's attendance
    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)->whereDate('date', Carbon::today());
    }

    /**
     * Scopes
     */

    // Active users only
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Pending approval
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // By designation
    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }

    /**
     * Helper Methods
     */

    // Check if user is Super Admin
    public function isSuperAdmin()
    {
        return $this->designation === 'super_admin';
    }

    public function isHS()
    {
        return $this->designation === 'hs';
    }

    // Check if user is DM
    public function isDM()
    {
        return $this->designation === 'dm';
    }

    // Check if user is BM
    public function isBM()
    {
        return $this->designation === 'bm';
    }

    // Check if user is RM
    public function isRM()
    {
        return $this->designation === 'rm';
    }

    // Check if user is RO
    public function isRO()
    {
        return $this->designation === 'ro';
    }

    // Check if user is on DAB (Doctor Appointment Basis) salary mode
    public function isDabMode()
    {
        return $this->salary_mode === 'dab';
    }

    // Check if user is on TAB (Travel Allowance Basis) salary mode (default)
    public function isTabMode()
    {
        return $this->salary_mode !== 'dab';
    }

    // Check if user is Office In-Charge
    public function isOfficeInCharge()
    {
        return in_array($this->designation, ['office_in_charge', 'camp_organizer']) || $this->is_office_in_charge;
    }

    // Check if user is strictly a regular Staff (Pharmacist)
    public function isRegularStaff()
    {
        return $this->designation === 'staff' && !$this->is_office_in_charge;
    }

    // Check if user can create users
    public function canCreateUsers()
    {
        if ($this->isSuperAdmin())
            return true;

        // Check per-user override first (Inherited)
        if ($this->hasInheritedPermission('can_create_users')) {
            return true;
        }

        // For OIC, we check if they have permission button enabled.
        // What they can create is determined by getAllowedChildDesignation (proxied to Upline).
        return RolePermission::check($this->designation, 'can_create_users');
    }

    /**
     * Helper to check if permission is granted to self or any ancestor via override
     */
    public function hasInheritedPermission($column)
    {
        // Check self
        if ($this->{$column}) {
            return true;
        }

        // Check ancestors recursively
        $parent = $this->isOfficeInCharge() ? $this->upline : $this->parent;
        if ($parent) {
            return $parent->hasInheritedPermission($column);
        }

        return false;
    }

    public function canViewDownline()
    {
        if ($this->isSuperAdmin())
            return true;

        // If they can create members, they must be able to view their team to access the button
        if ($this->canCreateUsers()) {
            return true;
        }

        return RolePermission::check($this->designation, 'can_view_downline');
    }

    public function canEditUserDetails()
    {
        if ($this->isSuperAdmin())
            return true;

        // Check per-user override first
        if ($this->can_edit_user_details) {
            return true;
        }

        return RolePermission::check($this->designation, 'can_edit_user_details');
    }

    public function canApprove(User $user)
    {
        if ($this->isSuperAdmin())
            return true;

        // If OIC, check if Upline could approve (based on designation logic)
        // AND check if OIC has the specific permission enabled
        if ($this->isOfficeInCharge() && $this->upline) {
            // Proxy upline's designation for the logic, but use OIC's own enabled permission toggle
            // Actually, "if admin permit him" implies OIC's RolePermission.
            // But the SCOPE relies on Upline.
            return RolePermission::check($this->designation, 'can_approve_users');
        }

        return RolePermission::check($this->designation, 'can_approve_users');
    }

    // Get allowed child designation
    public function getAllowedChildDesignation()
    {
        $designationMap = [
            'super_admin' => 'super_admin', // SA can create another
            'hs' => 'dm',
            'dm' => 'bm',
            'bm' => 'rm',
            'rm' => 'ro',
        ];

        // If currently SA, they can create HS (implicit or explicit in controller)
        // But for default child selection:
        if ($this->isSuperAdmin())
            return 'hs';


        // If OIC, they should have same creation capabilities as their Upline
        if ($this->isOfficeInCharge() && $this->upline) {
            return $this->upline->getAllowedChildDesignation();
        }

        return $designationMap[$this->designation] ?? null;
    }

    public function getDirectChildren()
    {
        if ($this->isSuperAdmin()) {
            // SA sees all HS users plus everyone reporting to ANY SA
            $saRoleIds = self::where('designation', 'super_admin')->pluck('id');
            return self::where(function ($q) use ($saRoleIds) {
                $q->where('designation', 'hs')
                    ->orWhereIn('parent_id', $saRoleIds);
            })
                ->whereNotIn('designation', ['super_admin', 'office_in_charge', 'camp_organizer', 'staff'])
                ->get();
        }

        if ($this->isOfficeInCharge()) {
            if ($this->upline_id && $this->upline) {
                // OIC sees their upline's direct children
                return $this->upline->getDirectChildren();
            }
            // Fallback for OIC without upline: see HS and direct SA children
            $saRoleIds = self::where('designation', 'super_admin')->pluck('id');
            return self::where(function ($q) use ($saRoleIds) {
                $q->where('designation', 'hs')
                    ->orWhereIn('parent_id', $saRoleIds);
            })
                ->whereNotIn('designation', ['super_admin', 'office_in_charge', 'camp_organizer', 'staff'])
                ->get();
        }

        // Standard behavior: Return direct children but exclude admins
        return $this->children()
            ->whereNotIn('designation', ['super_admin', 'office_in_charge', 'camp_organizer', 'staff'])
            ->get();
    }

    public function getDashboardChildrenCount()
    {
        return \Illuminate\Support\Facades\Cache::remember("user_{$this->id}_dashboard_children_count_v2", 300, function () {
            if ($this->isSuperAdmin() || ($this->isOfficeInCharge() && !$this->upline)) {
                $saRoleIds = self::where('designation', 'super_admin')->pluck('id');
                return self::where(function ($q) use ($saRoleIds) {
                    $q->where('designation', 'hs')
                        ->orWhereIn('parent_id', $saRoleIds);
                })
                    ->whereNotIn('designation', ['super_admin', 'office_in_charge', 'camp_organizer', 'staff'])
                    ->count();
            }

            if ($this->isOfficeInCharge() && $this->upline) {
                // OIC sees their upline's children count (same as upline)
                return $this->upline->getDashboardChildrenCount();
            }

            return $this->children()->whereNotIn('designation', ['office_in_charge', 'camp_organizer', 'staff'])->count();
        });
    }

    // Get all downline users (entire tree)
    public function getAllDownline()
    {
        if ($this->isSuperAdmin()) {
            // Super Admin sees everyone except other SAs
            return User::where('designation', '!=', 'super_admin')->get();
        }

        if ($this->isOfficeInCharge()) {
            // Office In-Charge with upline sees the same downline as their upline
            if ($this->upline_id && $this->upline) {
                // Get the upline's downline
                return $this->upline->getAllDownline();
            }

            // Fallback: Office In-Charge without upline sees everyone EXCEPT Super Admins
            return User::where('designation', '!=', 'super_admin')->get();
        }

        $ids = $this->getAllDownlineIds();
        return User::whereIn('id', $ids)->get();
    }

    // Helper to get recursive IDs (Iterative to avoid N+1 and deep recursion)
    public function getAllDownlineIds()
    {
        return \Illuminate\Support\Facades\Cache::remember("user_{$this->id}_downline_ids_v2", 300, function () {
            if ($this->isSuperAdmin()) {
                return self::where('designation', '!=', 'super_admin')->pluck('id')->toArray();
            }

            if ($this->isOfficeInCharge() && $this->upline_id) {
                $ids = $this->upline->getAllDownlineIds();
                return array_values(array_diff($ids, [$this->id]));
            }

            $allIds = [];
            $toProcess = [$this->id];
            $processed = [$this->id]; // Track what we have already added to avoid cycles

            while (!empty($toProcess)) {
                $batchIds = self::whereIn('parent_id', $toProcess)
                    ->whereNotIn('id', $processed)
                    ->pluck('id')
                    ->toArray();

                if (empty($batchIds))
                    break;

                $allIds = array_merge($allIds, $batchIds);
                $processed = array_merge($processed, $batchIds);
                $toProcess = $batchIds;
            }

            return $allIds;
        });
    }

    /**
     * Get IDs of all "Team" members (excludes Staff and System roles)
     */
    public function getTeamDownlineIds()
    {
        return \Illuminate\Support\Facades\Cache::remember("user_{$this->id}_team_downline_ids", 300, function () {
            $allIds = $this->getAllDownlineIds();
            if (empty($allIds))
                return [];

            return self::whereIn('id', $allIds)
                ->whereNotIn('designation', ['super_admin', 'office_in_charge', 'camp_organizer', 'staff'])
                ->where('is_office_in_charge', false)
                ->pluck('id')
                ->toArray();
        });
    }

    /**
     * Get IDs of all users in the team of the RM assigned to this user's camp.
     * Useful for Pharmacists (staff) to restrict visibility to their camp's RM team.
     */
    public function getCampRMTeamIds()
    {
        if ($this->designation !== 'staff' || !$this->camp_id || !$this->camp || !$this->camp->parent_id) {
            return [];
        }

        $rm = $this->camp->parent;
        // RM's own ID + all their downline
        return array_merge([$rm->id], $rm->getAllDownlineIds());
    }

    // Count total downline
    public function getDownlineCount()
    {
        return \Illuminate\Support\Facades\Cache::remember("user_{$this->id}_downline_count_v2", 300, function () {
            if ($this->isOfficeInCharge() && $this->upline) {
                return $this->upline->getDownlineCount();
            }

            if ($this->isSuperAdmin()) {
                return self::where('designation', '!=', 'super_admin')->count();
            }

            $ids = $this->getAllDownlineIds();
            return self::whereIn('id', $ids)->count();
        });
    }

    public function getPendingApprovalsCount()
    {
        if ($this->isSuperAdmin()) {
            return User::pending()->count();
        }

        if ($this->isOfficeInCharge() && $this->upline) {
            return $this->upline->getPendingApprovalsCount();
        }

        // For RM/BM/DM, check if they have approval permission
        if (\App\Models\RolePermission::check($this->designation, 'can_approve_users')) {
            $downlineIds = $this->getAllDownlineIds();
            if (empty($downlineIds))
                return 0;
            return User::pending()->whereIn('id', $downlineIds)->count();
        }

        return 0;
    }

    public static function generateEmployeeId($designation, $latestOnly = false)
    {
        $designationCodes = [
            'super_admin' => 'SA',
            'office_in_charge' => 'OI',
            'camp_organizer' => 'CO',
            'hs' => 'HS',
            'dm' => 'DM',
            'bm' => 'BM',
            'rm' => 'RM',
            'ro' => 'RO',
            'staff' => 'PH',
        ];

        $code = $designationCodes[$designation] ?? 'XX';
        $prefix = 'HF' . $code;

        if ($latestOnly) {
            // Find max sequence among active users to get the "latest"
            $lastUser = self::withTrashed()
                ->where('employee_id', 'like', $prefix . '%')
                ->where('employee_id', 'not like', 'TRASH_%')
                ->orderBy('employee_id', 'desc')
                ->first();

            if ($lastUser) {
                $lastSequence = (int) substr($lastUser->employee_id, strlen($prefix));
                $newSequence = str_pad($lastSequence + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newSequence = '000001';
            }
        } else {
            // Find first gap among active users
            $existingIds = self::withTrashed()
                ->where('employee_id', 'like', $prefix . '%')
                ->where('employee_id', 'not like', 'TRASH_%')
                ->pluck('employee_id')
                ->map(function ($id) use ($prefix) {
                    $seqPart = substr($id, strlen($prefix));
                    return is_numeric($seqPart) ? (int) $seqPart : null;
                })
                ->filter()
                ->toArray();

            sort($existingIds);

            $next = 1;
            foreach ($existingIds as $id) {
                if ($id == $next) {
                    $next++;
                } elseif ($id > $next) {
                    break;
                }
            }
            $newSequence = str_pad($next, 6, '0', STR_PAD_LEFT);
        }

        // Final verification to ensure we don't return a duplicate
        try {
            $candidateId = $prefix . $newSequence;
            while (\Illuminate\Support\Facades\DB::table('users')->where('employee_id', $candidateId)->exists()) {
                $seq = (int) substr($candidateId, strlen($prefix));
                $seq++;
                $newSequence = str_pad($seq, 6, '0', STR_PAD_LEFT);
                $candidateId = $prefix . $newSequence;
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return $candidateId;
    }

    /**
     * Get the user who should be used as the context for data viewing.
     * Returns the "View As" user if session is set and authorized, otherwise current user.
     */
    public static function getEffectiveUser()
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return null;
        }

        $viewAsId = session('view_as_user_id');
        if ($viewAsId && $viewAsId != $currentUser->id) {
            $targetUser = self::find($viewAsId);
            if ($targetUser && $currentUser->canAccess($targetUser)) {
                return $targetUser;
            } else {
                // Invalid or unauthorized context, clear it
                session()->forget('view_as_user_id');
            }
        }

        return $currentUser;
    }

    // Check if user can access another user's data
    public function canAccess(User $targetUser)
    {
        // Super admin can access everyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Office In-Charge can access everyone EXCEPT Super Admin and their upline
        if ($this->isOfficeInCharge()) {
            // Cannot access Super Admin
            if ($targetUser->isSuperAdmin()) {
                return false;
            }

            // Office In-Charge inherits access scope from their Upline
            if ($this->upline_id && $this->upline) {
                // Can access the Upline? Usually subordinates can see manager profile.
                if ($targetUser->id === $this->upline_id) {
                    return true;
                }

                return $this->upline->getAllDownline()->contains('id', $targetUser->id);
            }

            return false;
        }

        // Can access self
        if ($this->id === $targetUser->id) {
            return true;
        }

        // Pharmacists can access users who are in their camp RM's team
        if ($this->designation === 'staff') {
            $allowedIds = $this->getCampRMTeamIds();
            return in_array($targetUser->id, $allowedIds);
        }

        // Can access if target is in visibility scope
        return in_array($targetUser->id, $this->getDataVisibilityIds());
    }

    /**
     * Get all IDs that the current user has visibility over for data/patients
     */
    public function getDataVisibilityIds()
    {
        if ($this->isSuperAdmin()) {
            return self::where('designation', '!=', 'super_admin')->pluck('id')->toArray();
        }

        if ($this->designation === 'staff') {
            return $this->getCampRMTeamIds();
        }

        $ids = [$this->id];

        if ($this->isOfficeInCharge()) {
            // OIC inherits scope from Upline
            if ($this->upline_id && $this->upline) {
                $ids[] = $this->upline_id;
                $ids = array_merge($ids, $this->upline->getAllDownlineIds());
            }
        } else {
            // Regular user sees self + downline
            $ids = array_merge($ids, $this->getAllDownlineIds());
        }

        return array_values(array_unique($ids));
    }

    // Check if user can edit another user's data
    public function canEdit(User $targetUser)
    {
        // Super admin can always edit everyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Strict Check: Must have 'can_edit_user_details' permission enabled
        if (!$this->canEditUserDetails()) {
            return false;
        }

        // Cannot edit Super Admin
        if ($targetUser->isSuperAdmin()) {
            return false;
        }

        // Can edit self or downline (if accessible)
        return $this->canAccess($targetUser);
    }

    public function isOfficeInChargeExpired()
    {
        if (!$this->is_office_in_charge) {
            return false;
        }

        if ($this->office_in_charge_type === 'permanent') {
            return false;
        }

        if ($this->office_in_charge_end_date && Carbon::parse($this->office_in_charge_end_date)->isPast()) {
            return true;
        }

        return false;
    }

    // Get designation label
    public function getDesignationLabel()
    {
        if ($this->isSuperAdmin() && $this->post) {
            return $this->post;
        }

        $labels = [
            'super_admin' => 'Super Admin',
            'office_in_charge' => 'Office In-Charge',
            'camp_organizer' => 'Camp Organizer',
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
            'staff' => 'Pharmacist',
        ];

        return $labels[$this->designation] ?? 'Unknown';
    }

    /**
     * Get the joining donation amount for a specific designation
     */
    public static function getJoiningDonationAmount($designation)
    {
        $amounts = [
            'dm' => 999,
            'bm' => 999,
            'rm' => 499,
            'ro' => 199,
            'staff' => 0,
        ];

        return $amounts[$designation] ?? 0;
    }

    public function medicineDistributions()
    {
        return $this->hasMany(MedicineDistribution::class, 'pharmacist_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

}
