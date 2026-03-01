<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_name',
        'location',
        'rm',
        'date',
        'patients_count',
        'medicine_mrp',
        'medicine_discount',
        'billing_price',
        'profit',
        'doctor_name',
        'pathologist',
        'pharmacists_name',
        'expenses',
        'net_profit_loss',
    ];
}
