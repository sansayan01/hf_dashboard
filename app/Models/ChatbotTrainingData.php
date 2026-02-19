<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTrainingData extends Model
{
    protected $table = 'chatbot_training_data';

    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
