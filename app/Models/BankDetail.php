<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $bank_name
 * @property string $account_number
 * @property string $ifsc_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
class BankDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'ifsc_code',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
