<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoodLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'mood',
        'emotion_label',
        'confidence_score'
    ];

    public function user()
    {
        return $this->belongsTo(AnonymousUser::class,'anonymous_user_id');
    }
}