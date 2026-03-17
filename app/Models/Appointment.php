<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'doctor_type',
        'location',
        'appointment_date',
        'appointment_time',
        'status',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date:Y-m-d',
    ];

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
