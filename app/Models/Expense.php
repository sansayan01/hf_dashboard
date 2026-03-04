<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Expense extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Office Supplies',
        'Travel',
        'Event/Camp',
        'Salary/Stipend',
        'Utilities',
        'Medical Supplies',
        'Printing',
        'Food & Refreshments',
        'Miscellaneous',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'upi' => 'UPI',
        'bank_transfer' => 'Bank Transfer',
        'cheque' => 'Cheque',
        'other' => 'Other',
    ];

    protected $fillable = [
        'title',
        'description',
        'amount',
        'category',
        'expense_date',
        'payment_method',
        'reference_number',
        'receipt_path',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
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
