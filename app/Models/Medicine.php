<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'generic_name',
        'category_id',
        'unit',
        'dosage',
        'description',
        'min_stock_level',
        'market_price',
        'market_price_unit_count',
        'units_per_box'
    ];

    protected static function boot()
    {
        parent::boot();

        // When a medicine is deleted (soft or hard), delete all related stocks
        static::deleting(function ($medicine) {
            // Delete all stocks (which will cascade to transactions via DB foreign key)
            $medicine->stocks()->delete();
        });
    }

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function distributionItems()
    {
        return $this->hasMany(MedicineDistributionItem::class, 'medicine_id');
    }

    public function getTotalStockAttribute()
    {
        return $this->stocks()->sum('quantity');
    }
}
