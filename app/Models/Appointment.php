<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'survey_id',
        'doctor_type',
        'location',
        'appointment_date',
        'appointment_time',
        'status',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Auto-generate Appointment ID on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->appointment_id) {
                $model->appointment_id = static::generateAppointmentId();
            }
        });
    }

    public static function generateAppointmentId()
    {
        $prefix = 'HFDA';

        // Find the last appointment ID with this prefix
        $lastAppointment = self::where('appointment_id', 'like', $prefix . '%')
            ->orderBy('appointment_id', 'desc')
            ->first();

        if ($lastAppointment) {
            $lastId = $lastAppointment->appointment_id;
            $lastSequence = (int) substr($lastId, strlen($prefix));
            $newSequence = str_pad($lastSequence + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '000001';
        }

        return $prefix . $newSequence;
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Clean up appointment_time to ensure it only returns a time string.
     * Handles cases where the database might have stored it as "Y-m-d H:i:s H:i:s".
     */
    public function getAppointmentTimeAttribute($value)
    {
        if (empty($value))
            return $value;

        // If it contains a space, it might be a date-time string
        if (strpos($value, ' ') !== false) {
            // Find the last space and take everything after it
            $parts = explode(' ', trim($value));
            return end($parts);
        }

        return $value;
    }
}
