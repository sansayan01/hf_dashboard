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

    // Check if user can create users
    public function canCreateUsers()
    {
        return in_array($this->designation, ['super_admin', 'dm', 'bm', 'rm']);
    }

    public function canApprove(User $user)
    {
        // Strictly only Super Admin can approve users
        return $this->isSuperAdmin();
    }

    // Get allowed child designation
    public function getAllowedChildDesignation()
    {
        $designationMap = [
            'super_admin' => 'super_admin', // SA can create another SA
            'dm' => 'bm',
            'bm' => 'rm',
            'rm' => 'ro',
        ];

        // If currently SA, we might want to default to 'dm' for the regular flow 
        // but SA can create any role including SA in the controller logic.
        // This map is used by non-SA users to know what they can create.
        if ($this->isSuperAdmin())
            return 'dm';


        return $designationMap[$this->designation] ?? null;
    }

    // Get direct children (for hierarchy tree)
    public function getDirectChildren()
    {
        if ($this->isSuperAdmin()) {
            return User::where('designation', 'dm')->get();
        }
        return $this->children;
    }

    // Get all downline users (entire tree)
    public function getAllDownline()
    {
        if ($this->isSuperAdmin()) {
            // Treat all non-SA users as downline for ANY Super Admin
            return User::where('designation', '!=', 'super_admin')->get();
        }

        $downline = collect();

        foreach ($this->children as $child) {
            $downline->push($child);
            $downline = $downline->merge($child->getAllDownline());
        }

        return $downline;
    }

    // Count total downline
    public function getDownlineCount()
    {
        if ($this->isSuperAdmin()) {
            return User::where('designation', '!=', 'super_admin')->count();
        }
        return $this->getAllDownline()->count();
    }

    public function getPendingApprovalsCount()
    {
        if ($this->isSuperAdmin()) {
            return User::pending()->count();
        }

        // Non-super admins cannot approve, so they have 0 pending approvals to handle
        return 0;
    }

    public static function generateEmployeeId($designation)
    {
        $designationCodes = [
            'super_admin' => 'SA',
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

        // After approval (active status), ONLY super admin can edit
        if ($targetUser->status === 'active') {
            return false;
        }

        // While pending, managers can edit their direct or indirect downline
        // (Self-edit is also allowed here if pending, but hierarchy is the focus)
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
            'dm' => 'District Manager',
            'bm' => 'Block Manager',
            'rm' => 'Relationship Manager',
            'ro' => 'Relationship Officer',
        ];

        return $labels[$this->designation] ?? 'Unknown';
    }
}
