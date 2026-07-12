<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'session_date',
        'summary',
        'dominant_mood',
        'risk_level',
        'message_count'
    ];

    public function anonymousUser()
    {
        return $this->belongsTo(AnonymousUser::class);
    }
}
