<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySponsor extends Model
{
    protected $fillable = ['name', 'description', 'contact_person', 'contact_email', 'contact_phone'];

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'sponsor_id');
    }
}
