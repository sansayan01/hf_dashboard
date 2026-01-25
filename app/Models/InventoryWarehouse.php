<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryWarehouse extends Model
{
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_CAMP = 'camp';

    protected $fillable = ['name', 'location', 'type', 'is_active'];

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'warehouse_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'warehouse_id');
    }
}
