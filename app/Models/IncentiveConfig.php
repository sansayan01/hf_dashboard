<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncentiveConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation',
        'incentive_amount',
        'ta_amount',
        'medicines_amount',
        'pathology_amount',
        'membership_amount',
        'ots_amount',
        'effective_from',
    ];

    protected $casts = [
        'effective_from' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
