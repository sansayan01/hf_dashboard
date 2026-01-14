<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'parent_id',
        'status',
        'is_office_in_charge',
        'office_in_charge_creator_id',
        'office_in_charge_type',
        'office_in_charge_end_date',
        'upline_id',
        'upline_designation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
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

    // Check if user is Office In-Charge
    public function isOfficeInCharge()
    {
        return $this->designation === 'office_in_charge';
    }

    // Check if user can create users
    public function canCreateUsers()
    {
        if ($this->isSuperAdmin())
            return true;
        return RolePermission::check($this->designation, 'can_create_users');
    }

    public function canEditUserDetails()
    {
        if ($this->isSuperAdmin())
            return true;
        return RolePermission::check($this->designation, 'can_edit_user_details');
    }

    public function canApprove(User $user)
    {
        if ($this->isSuperAdmin())
            return true;
        return RolePermission::check($this->designation, 'can_approve_users');
    }

    // Get allowed child designation
    public function getAllowedChildDesignation()
    {
        $designationMap = [
            'super_admin' => 'super_admin', // SA can create another SA 
            'hs' => 'dm',
            'dm' => 'bm',
            'bm' => 'rm',
            'rm' => 'ro',
        ];

        // If currently SA, they can create HS (implicit or explicit in controller)
        // But for default child selection:
        if ($this->isSuperAdmin())
            return 'hs';


        return $designationMap[$this->designation] ?? null;
    }

    public function getDirectChildren()
    {
        if ($this->isSuperAdmin() || $this->isOfficeInCharge()) {
            // Only HS is direct child of SA/OI now. DMs must be under HS.
            return self::where('designation', 'hs')->get();
        }
        return $this->children;
    }

    // Get all downline users (entire tree)
    public function getAllDownline()
    {
        if ($this->isSuperAdmin()) {
            // Super Admin sees everyone except other SAs in downline list (usually)
            // But technically SA has access to everyone. 
            // For the purpose of "Downline", we usually mean people below.
            return User::where('designation', '!=', 'super_admin')->get();
        }

        if ($this->isOfficeInCharge()) {
            // Office In-Charge with upline sees the same downline as their upline
            if ($this->upline_id && $this->upline) {
                // Get the upline's downline
                return $this->upline->getAllDownline();
            }

            // Fallback: Office In-Charge without upline sees everyone EXCEPT Super Admins
            // They can see other Office In-Charges if they exist (though usually only 1)
            // But per requirement "can't see super user", so we exclude SA.
            return User::where('designation', '!=', 'super_admin')->get();
        }

        $ids = $this->getAllDownlineIds();
        return User::whereIn('id', $ids)->get();
    }

    // Helper to get recursive IDs (Iterative to avoid N+1 and deep recursion)
    public function getAllDownlineIds()
    {
        $allIds = [];
        $toProcess = [$this->id];

        while (!empty($toProcess)) {
            $batchIds = self::whereIn('parent_id', $toProcess)->pluck('id')->toArray();
            if (empty($batchIds))
                break;
            $allIds = array_merge($allIds, $batchIds);
            $toProcess = $batchIds;
        }

        return $allIds;
    }

    // Count total downline
    public function getDownlineCount()
    {
        if ($this->isSuperAdmin() || $this->isOfficeInCharge()) {
            return self::where('designation', '!=', 'super_admin')->count();
        }

        $ids = $this->getAllDownlineIds();
        return self::whereIn('id', $ids)->count();
    }

    public function getPendingApprovalsCount()
    {
        if ($this->isSuperAdmin() || $this->isOfficeInCharge()) {
            return User::pending()->count();
        }

        // Non-admin users cannot approve, so they have 0 pending approvals to handle
        return 0;
    }

    public static function generateEmployeeId($designation)
    {
        $designationCodes = [
            'super_admin' => 'SA',
            'office_in_charge' => 'OI',
            'hs' => 'HS',
            'dm' => 'DM',
            'bm' => 'BM',
            'rm' => 'RM',
            'ro' => 'RO',
        ];

        $code = $designationCodes[$designation] ?? 'XX';
        $prefix = 'HF' . $code;

        // Find the last used sequence for this specific designation prefix
        $lastUser = self::where('employee_id', 'like', $prefix . '%')
            ->orderBy('employee_id', 'desc')
            ->first();

        if ($lastUser) {
            // Extract the numeric part (last 6 digits)
            $lastId = $lastUser->employee_id;
            $lastSequence = (int) substr($lastId, strlen($prefix));
            $newSequence = str_pad($lastSequence + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '000001';
        }

        return $prefix . $newSequence;
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

            // Cannot access their own upline (the person they represent)
            if ($this->upline_id && $targetUser->id === $this->upline_id) {
                return false;
            }

            return true;
        }

        // Can access self
        if ($this->id === $targetUser->id) {
            return true;
        }

        // Can access if target is in downline
        return $this->getAllDownline()->contains('id', $targetUser->id);
    }

    // Check if user can edit another user's data
    public function canEdit(User $targetUser)
    {
        // Super admin can always edit everyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Self-edit is allowed (profile section handles specific field restrictions)
        if ($this->id === $targetUser->id) {
            return true;
        }

        // Check for specific edit permission (Applies to all non-Super Admins)
        if ($this->canEditUserDetails()) {
            // Cannot edit Super Admin even with permission
            if ($targetUser->isSuperAdmin()) {
                return false;
            }

            // Can edit if they have permission and target is in downline (or accessible)
            return $this->canAccess($targetUser);
        }

        // By default, if active, only SA or those with specific permission can edit
        if ($targetUser->status === 'active') {
            return false;
        }

        // While pending, users with access can edit (implied ability for managers to fix submissions)
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
        $labels = [
            'super_admin' => 'Super Admin',
            'office_in_charge' => 'Office In-Charge',
            'hs' => 'Head of State',
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];

        return $labels[$this->designation] ?? 'Unknown';
    }
}
