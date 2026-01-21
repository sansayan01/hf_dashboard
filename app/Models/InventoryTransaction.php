<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
