<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $patient_id
 * @property string $full_name
 * @property string|null $relative_name
 * @property int $age
 * @property string $gender
 * @property string $phone_number
 * @property string $address
 * @property string $pin
 * @property string|null $aadhar_number
 * @property string|null $pan_number
 * @property string|null $blood_group
 * @property string|null $district
 * @property string|null $block
 * @property string|null $gp
 * @property string|null $landmark
 * @property string|null $past_diseases
 * @property string|null $health_issues
 * @property string $insurance_loan_req
 * @property bool $is_member
 * @property float|null $membership_fee
 * @property string|null $payment_method
 * @property string|null $payment_screenshot
 * @property int $created_by
 * @property-read \App\Models\User $creator
 */
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
            // Find max sequence among all users (including trashed but not renamed ones) to avoid collision
            $last = self::withTrashed()
                ->where('patient_id', 'like', $prefix . '%')
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
            $existingIds = self::withTrashed()
                ->where('patient_id', 'like', $prefix . '%')
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

        // Final verification to ensure we don't return a duplicate
        // Use DB table directly to bypass any model scopes/soft delete filters that might hide the record
        $candidateId = $prefix . $newSequence;
        while (\Illuminate\Support\Facades\DB::table('surveys')->where('patient_id', $candidateId)->exists()) {
            $seq = (int) substr($candidateId, strlen($prefix));
            $seq++;
            $newSequence = str_pad($seq, $length, '0', STR_PAD_LEFT);
            $candidateId = $prefix . $newSequence;
        }

        return $candidateId;
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
