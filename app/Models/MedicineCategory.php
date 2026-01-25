<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'category_id');
    }
}
