<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'status',
        'recorded_by',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The Relationship Officer (RO) whose attendance is recorded.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The Manager (RM) or Admin who recorded this attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
