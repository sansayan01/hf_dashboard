<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'test_name',
        'amount',
        'discount_percentage',
        'discount_amount',
        'final_amount',
        'payment_method',
        'amount_paid',
        'due_amount',
        'created_by',
        'ro_id',
        'camp_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Survey::class, 'patient_id');
    }

    public function creator()
    {
        // The one who recorded the test (Pharmacist/Staff)
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ro()
    {
        // The Relationship Officer who gets the incentive
        return $this->belongsTo(User::class, 'ro_id');
    }

    public function camp()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'camp_id');
    }
}
