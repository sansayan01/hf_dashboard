<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $stock_id
 * @property int|null $warehouse_id
 * @property int|null $sponsor_id
 * @property string $type
 * @property int $quantity
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property int|null $distribution_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InventoryStock $stock
 */
class InventoryTransaction extends Model
{
    protected $fillable = [
        'stock_id',
        'warehouse_id',
        'sponsor_id',
        'type',
        'quantity',
        'user_id',
        'patient_id',
        'distribution_id',
        'notes'
    ];

    public function stock()
    {
        return $this->belongsTo(InventoryStock::class, 'stock_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(InventorySponsor::class, 'sponsor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Survey::class, 'patient_id');
    }

    public function distribution()
    {
        return $this->belongsTo(MedicineDistribution::class, 'distribution_id');
    }
}
