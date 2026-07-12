<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoodLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'message_id',
        'mood',
        'notes',
        'emotion_label',
        'confidence_score',
        'source',
        'mood_date'
    ];

    public function user()
    {
        return $this->belongsTo(AnonymousUser::class,'anonymous_user_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}