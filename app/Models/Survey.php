<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'full_name',
        'relative_name',
        'age',
        'gender',
        'phone_number',
        'address',
        'pin',
        'aadhar_number',
        'pan_number',
        'blood_group',
        'district',
        'block',
        'gp',
        'landmark',
        'past_diseases',
        'health_issues',
        'insurance_loan_req',
        'created_by'
    ];

    /**
     * Auto-generate Patient ID on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->patient_id) {
                $model->patient_id = static::generatePatientId();
            }
        });
    }

    public static function generatePatientId()
    {
        $prefix = 'HFP';

        // Find the last patient ID with this prefix (including deleted ones)
        $lastPatient = self::withTrashed()
            ->where('patient_id', 'like', $prefix . '%')
            ->orderBy('patient_id', 'desc')
            ->first();

        if ($lastPatient) {
            $lastId = $lastPatient->patient_id;
            $lastSequence = (int) substr($lastId, strlen($prefix));
            $newSequence = str_pad($lastSequence + 1, 7, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0000001';
        }

        return $prefix . $newSequence;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
