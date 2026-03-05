<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CampRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_name',
        'camp_type',
        'location',
        'rm',
        'date',
        'patients_count',
        'medicine_mrp',
        'medicine_discount',
        'total_discount',
        'buying_percentage',
        'doctor_appointment_fees',
        'profit',
        'doctor_name',
        'pathologist',
        'pharmacists_name',
        'expenses',
        'expense_details',
        'net_profit_loss',
    ];

    protected $casts = [
        'expense_details' => 'array',
    ];

    /**
     * Capitalization Attributes (Title Case)
     */

    protected function campName(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function doctorName(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function pathologist(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function pharmacistsName(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucwords(strtolower($value)),
        );
    }
}
