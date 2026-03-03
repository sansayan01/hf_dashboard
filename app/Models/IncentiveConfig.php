<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $designation
 * @property float $incentive_amount
 * @property float $ta_amount
 * @property float $da_amount
 * @property float $medicines_amount
 * @property float $pathology_amount
 * @property float $membership_amount
 * @property float $ots_amount
 * @property \Illuminate\Support\Carbon $effective_from
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class IncentiveConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation',
        'incentive_amount',
        'ta_amount',
        'da_amount',
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
