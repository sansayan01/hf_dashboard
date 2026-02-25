<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'designation',
        'original_amount',
        'is_used',
        'used_by_user_id',
        'used_at',
        'generated_by_user_id',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'original_amount' => 'decimal:2',
    ];

    /**
     * Relationship: User who generated this coupon
     */
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }

    /**
     * Relationship: User who used this coupon
     */
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }

    /**
     * Check if coupon is valid for use
     */
    public function isValid($designation = null)
    {
        // Check if already used
        if ($this->is_used) {
            return false;
        }

        // Check if expired (valid until the end of the expiration day)
        if ($this->expires_at && $this->expires_at->endOfDay()->isPast()) {
            return false;
        }

        // Check if designation matches (if coupon is designation-specific)
        if ($this->designation && $designation && $this->designation !== $designation) {
            return false;
        }

        return true;
    }

    /**
     * Mark coupon as used
     */
    public function markAsUsed($userId)
    {
        $this->update([
            'is_used' => true,
            'used_by_user_id' => $userId,
            'used_at' => now(),
        ]);

        // Log activity
        ActivityLog::logActivity(
            'coupon_used',
            $userId,
            $userId,
            "Used coupon code: {$this->code}",
            'CouponCode',
            $this->id
        );
    }

    /**
     * Scope: Get unused coupons
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope: Get used coupons
     */
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    /**
     * Scope: Filter by designation
     */
    public function scopeForDesignation($query, $designation)
    {
        return $query->where(function ($q) use ($designation) {
            $q->whereNull('designation')
                ->orWhere('designation', $designation);
        });
    }

    /**
     * Scope: Get non-expired coupons
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope: Get expired coupons
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Generate a unique coupon code
     */
    public static function generateUniqueCode($designation = null)
    {
        do {
            // Format: HF-CASH-XXXXX (where X is alphanumeric)
            $random = strtoupper(Str::random(5));
            $code = "HF-CASH-{$random}";
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Generate multiple coupons
     */
    public static function generateBatch($quantity, $designation, $generatedById, $expiresAt = null, $notes = null)
    {
        $coupons = [];
        $amounts = [
            'dm' => 999,
            'bm' => 999,
            'rm' => 499,
            'ro' => 199,
            'membership' => 200,
        ];

        for ($i = 0; $i < $quantity; $i++) {
            $code = self::generateUniqueCode($designation);

            $coupons[] = self::create([
                'code' => $code,
                'discount_percentage' => 100,
                'designation' => $designation,
                'original_amount' => $designation ? ($amounts[$designation] ?? null) : null,
                'generated_by_user_id' => $generatedById,
                'expires_at' => $expiresAt,
                'notes' => $notes,
            ]);
        }

        // Log activity
        ActivityLog::logActivity(
            'coupons_generated',
            $generatedById,
            $generatedById,
            "Generated {$quantity} coupon codes for " . ($designation ? strtoupper($designation) : 'any designation'),
            'CouponCode',
            null
        );

        return $coupons;
    }

    /**
     * Get validation error message
     */
    public function getValidationError($designation = null)
    {
        if ($this->is_used) {
            return 'This coupon code has already been used.';
        }

        if ($this->expires_at && $this->expires_at->endOfDay()->isPast()) {
            return 'This coupon code has expired.';
        }

        if ($this->designation && $designation && $this->designation !== $designation) {
            return "This coupon code is only valid for {$this->designation} designation.";
        }

        return null;
    }
}
