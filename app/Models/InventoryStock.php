<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    protected $fillable = [
        'medicine_id',
        'warehouse_id',
        'sponsor_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'purchase_price'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        // When a stock is deleted, delete all related transactions
        static::deleting(function ($stock) {
            $stock->transactions()->delete();
        });
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(InventorySponsor::class, 'sponsor_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'stock_id');
    }
}
