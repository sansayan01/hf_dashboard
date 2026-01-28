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
        'is_member',
        'membership_fee',
        'payment_method',
        'payment_screenshot',
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
                if ($model->is_member) {
                    $model->patient_id = static::generateMembershipId();
                } else {
                    $model->patient_id = static::generatePatientId();
                }
            }
        });

        static::deleted(function ($survey) {
            // When soft-deleted, change patient_id to free up the original ID for gap-filling
            $survey->patient_id = 'TRASH_' . $survey->patient_id . '_' . now()->timestamp;
            $survey->save();
        });

        static::restoring(function ($survey) {
            // When restored, assign the latest available serial number as requested
            if ($survey->is_member) {
                $survey->patient_id = self::generateMembershipId(true);
            } else {
                $survey->patient_id = self::generatePatientId(true);
            }
        });
    }

    public static function generatePatientId($latestOnly = false)
    {
        return self::generateSequenceId('HFP', 7, $latestOnly);
    }

    public static function generateMembershipId($latestOnly = false)
    {
        return self::generateSequenceId('HFM', 7, $latestOnly);
    }

    private static function generateSequenceId($prefix, $length, $latestOnly)
    {
        if ($latestOnly) {
            // Find max sequence among active users to get the "latest"
            $last = self::where('patient_id', 'like', $prefix . '%')
                ->where('patient_id', 'not like', 'TRASH_%')
                ->orderBy('patient_id', 'desc')
                ->first();

            if ($last) {
                $lastSequence = (int) substr($last->patient_id, strlen($prefix));
                $newSequence = str_pad($lastSequence + 1, $length, '0', STR_PAD_LEFT);
            } else {
                $newSequence = str_pad(1, $length, '0', STR_PAD_LEFT);
            }
        } else {
            // Find first gap among active users
            $existingIds = self::where('patient_id', 'like', $prefix . '%')
                ->where('patient_id', 'not like', 'TRASH_%')
                ->pluck('patient_id')
                ->map(function ($id) use ($prefix) {
                    $seqPart = substr($id, strlen($prefix));
                    return is_numeric($seqPart) ? (int) $seqPart : null;
                })
                ->filter()
                ->toArray();

            sort($existingIds);

            $next = 1;
            foreach ($existingIds as $id) {
                if ($id == $next) {
                    $next++;
                } elseif ($id > $next) {
                    break;
                }
            }
            $newSequence = str_pad($next, $length, '0', STR_PAD_LEFT);
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

    public function medicineDistributions()
    {
        return $this->hasMany(MedicineDistribution::class, 'patient_id');
    }
}
