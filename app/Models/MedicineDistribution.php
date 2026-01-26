<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineDistribution extends Model
{
    protected $fillable = [
        'patient_id',
        'camp_id',
        'pharmacist_id',
        'total_amount'
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

    public function getDiscountPercentageAttribute()
    {
        return $this->total_amount > 300 ? 20 : 18;
    }

    public function getDiscountAmountAttribute()
    {
        return round(($this->total_amount * $this->discount_percentage) / 100, 2);
    }

    public function getFinalAmountAttribute()
    {
        return $this->total_amount - $this->discount_amount;
    }
}
