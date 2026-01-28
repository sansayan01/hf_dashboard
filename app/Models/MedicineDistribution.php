<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineDistribution extends Model
{
    protected $fillable = [
        'patient_id',
        'camp_id',
        'pharmacist_id',
        'total_amount',
        'discount_percentage',
        'discount_amount',
        'final_amount'
    ];

    public function patient()
    {
        return $this->belongsTo(Survey::class, 'patient_id');
    }

    public function camp()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'camp_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function items()
    {
        return $this->hasMany(MedicineDistributionItem::class, 'distribution_id');
    }
}
