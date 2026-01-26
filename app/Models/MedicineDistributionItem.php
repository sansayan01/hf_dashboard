<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineDistributionItem extends Model
{
    protected $fillable = [
        'distribution_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'total_price'
    ];

    public function distribution()
    {
        return $this->belongsTo(MedicineDistribution::class, 'distribution_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
