<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnonymousUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_code'
    ];

    // ==========================
    // Relasi Mood
    // ==========================
    public function moods()
    {
        return $this->hasMany(MoodLog::class);
    }

    // ==========================
    // Relasi Sesi Konseling
    // ==========================
    public function sessions()
    {
        return $this->hasMany(CounselingSession::class);
    }

    // ==========================
    // Relasi Chat
    // ==========================
    public function messages()
    {
        return $this->hasMany(Message::class, 'anonymous_user_id');
    }
}