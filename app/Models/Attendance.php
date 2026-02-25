<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marked_by',
        'recorded_by',
        'status',
        'date',
        'incentive_amount',
        'ta_amount',
        'medicines_amount',
        'pathology_amount',
        'membership_amount',
        'ots_amount',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'datetime',
        'incentive_amount' => 'decimal:2',
        'ta_amount' => 'decimal:2',
        'medicines_amount' => 'decimal:2',
        'pathology_amount' => 'decimal:2',
        'membership_amount' => 'decimal:2',
        'ots_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($attendance) {
            $attendance->total_amount = ($attendance->incentive_amount ?? 0) +
                ($attendance->ta_amount ?? 0) +
                ($attendance->medicines_amount ?? 0) +
                ($attendance->pathology_amount ?? 0) +
                ($attendance->membership_amount ?? 0) +
                ($attendance->ots_amount ?? 0);
        });
    }

    public function isLocked()
    {
        // Locked after 24 hours from creation or if it's not from today
        // Rule: Attendance can be edited only on the same day.
        return !$this->date->isToday();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
