<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Income extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Donations',
        'Grants',
        'Membership Fees',
        'Service Revenue',
        'Camp Revenue',
        'Pathology Revenue',
        'Medicine Sales',
        'Sponsorship',
        'Interest',
        'Miscellaneous',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'upi' => 'UPI',
        'bank_transfer' => 'Bank Transfer',
        'cheque' => 'Cheque',
        'other' => 'Other',
    ];

    public const RECEIVED_BY_OPTIONS = [
        'SK Alamgir',
        'Raju Das',
        'Sayan Mondal',
    ];

    protected $fillable = [
        'title',
        'description',
        'amount',
        'category',
        'income_date',
        'payment_method',
        'received_by',
        'source',
        'reference_number',
        'receipt_path',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'income_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Mutators
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }
}
