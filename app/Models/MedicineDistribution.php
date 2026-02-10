<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $patient_id
 * @property int $camp_id
 * @property int $pharmacist_id
 * @property float $total_amount
 * @property float $discount_percentage
 * @property float $discount_amount
 * @property float $final_amount
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class MedicineDistribution extends Model
{
    protected $fillable = [
        'patient_id',
        'camp_id',
        'pharmacist_id',
        'total_amount',
        'discount_percentage',
        'discount_amount',
        'final_amount',
        'payment_method',
        'amount_paid',
        'due_amount'
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
