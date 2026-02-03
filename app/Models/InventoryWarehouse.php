<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryWarehouse extends Model
{
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_CAMP = 'camp';

    protected $fillable = ['name', 'location', 'type', 'is_active', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'warehouse_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'warehouse_id');
    }

    public function medicineDistributions()
    {
        return $this->hasMany(MedicineDistribution::class, 'camp_id');
    }
}
