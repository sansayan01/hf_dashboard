<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
